<script setup lang="ts">
import { Agent } from '@/types';
import { computed, onMounted, ref, type ComputedRef } from 'vue';
import { useI18n } from 'vue-i18n';

import notSelectedImage from '@/assets/icons/not_selected.png';
import selectedImage from '@/assets/icons/selected.png';
import Errors from '@/components/Error.vue';
import Button from './Button.vue';
import Card from './Card.vue';

const { t } = useI18n();

const props = defineProps({
    agents: {
        type: Array as () => Agent[],
        default: () => [],
    },
});

const currentAgent = ref(null as Agent | null);

interface GroupedAgents {
    [key: string]: Agent[];
}

const noAnimation = ref(false);

const errors = ref<string[]>([]);

onMounted(async () => {

    await loadAgentImages();
    currentAgent.value = props.agents.find((agent) => agent.selected) || props.agents[0] || null;

    props.agents.forEach((agent) => {
        agent.selected = true;
    });
});

async function loadAgentImages() {
    props.agents.forEach((agent) => {
        const img = new Image();
        img.src = agent.fullPortrait;
        const bg = new Image();
        bg.src = agent.background;
    });
}

let isRolling = false;

function rollAgents(duration: number = 5000, speed: number = 10) {
    if (isRolling) return;

    if (props.agents.filter((agent) => agent.selected).length === 0) {
        errors.value = ['No agents selected'];
        return;
    }

    errors.value = [];
    const filteredAgents = props.agents.filter((agent) => agent.selected);

    // Don't roll just select random agent
    if (noAnimation.value) {
        const randomIndex = Math.floor(Math.random() * filteredAgents.length);
        currentAgent.value = filteredAgents[randomIndex];
        return;
    }

    let elapsed = 0;
    let currentSpeed = speed;
    const decreaseRate = 5;
    isRolling = true;

    function animate() {
        if (elapsed >= duration) {
            isRolling = false;
            return;
        }
        const randomIndex = Math.floor(Math.random() * filteredAgents.length);
        currentAgent.value = filteredAgents[randomIndex];

        currentSpeed = Math.min(currentSpeed + decreaseRate, 1000); // Cap at 1000ms
        elapsed += currentSpeed;

        setTimeout(animate, currentSpeed);
    }

    animate();

    setTimeout(() => {
        isRolling = false;
    }, duration);
}

function agentSelect(agent: Agent) {
    if (isRolling) return;
    agent.selected = !agent.selected;
}

function selectAllAgents(select = true) {
    if (isRolling) return;
    props.agents.forEach((agent) => (agent.selected = select));
}

function selectGroup(roleName: string) {
    if (isRolling) return;
    props.agents.forEach((agent) => (agent.selected = agent.role.displayName === roleName));
}

const groupedAgents: ComputedRef<GroupedAgents> = computed(() => {
    return props.agents.reduce((groups: GroupedAgents, agent: Agent) => {
        const key = agent.role.displayName;
        if (!groups[key]) {
            groups[key] = [];
        }
        groups[key].push(agent);
        return groups;
    }, {} as GroupedAgents);
});
</script>

<template>
    <div class="relative m-4 flex flex-col items-center gap-8 p-2 md:flex-row">
        <!-- ****** ****** ****** ****** ****** ****** Current Agent Display ****** ****** ****** ****** ****** ****** -->
        <Card v-if="currentAgent" class="flex w-[80vw] flex-col items-center justify-center gap-2 md:w-[20vw]">
            <span class="relative flex h-[25vh] w-full items-center justify-center bg-transparent md:h-[40vh]">
                <img
                    class="absolute top-0 left-0 z-10 h-full w-full opacity-25 invert"
                    :src="currentAgent.background"
                    :alt="currentAgent.displayName"
                />
                <img class="relative z-20 h-[25vh] opacity-100" :src="currentAgent.fullPortrait" :alt="currentAgent.displayName" />
            </span>

            <h2 class="valorant-font text-foreground text-2xl">{{ currentAgent.displayName }}</h2>
            <div class="flex items-center justify-center gap-2">
                <img class="w-4 invert dark:invert-0" :src="currentAgent.role?.displayIcon ?? ''" :alt="currentAgent.role?.displayName" />
                <p>{{ currentAgent.role?.displayName }}</p>
            </div>

            <Errors v-if="errors" :errors="errors" />

            <Button class="w-4/5" color="valorant" :disabled="isRolling" @click="rollAgents()">{{ t('Spin') }}</Button>
            <div class="flex items-center justify-center gap-2">
                <label>{{ t('Skip Animation') }}</label>
                <img
                    v-if="noAnimation"
                    class="flex h-6 w-6 cursor-pointer items-center justify-center invert dark:invert-0"
                    :src="selectedImage"
                    alt="selected"
                    @click="noAnimation = !noAnimation"
                />

                <img
                    v-else
                    class="flex h-6 w-6 cursor-pointer items-center justify-center invert dark:invert-0"
                    :src="notSelectedImage"
                    alt="not_selected"
                    @click="noAnimation = !noAnimation"
                />
            </div>
        </Card>

        <!-- ****** ****** ****** ****** ****** ****** Agent Selector ****** ****** ****** ****** ****** ****** -->
        <div class="flex h-[25vh] flex-col items-start overflow-x-hidden overflow-y-auto md:h-auto">
            <div class="mb-4 flex w-full justify-start">
                <Button color="valorant" :disabled="isRolling" @click="selectAllAgents()">{{ t('Select All') }}</Button>
            </div>

            <div v-for="(group, roleName) in groupedAgents" :key="roleName" class="mb-4 flex flex-col items-start justify-center gap-2">
                <!-- ****** ****** ****** ****** ****** ****** Header ****** ****** ****** ****** ****** ****** -->
                <div class="flex items-center">
                    <h3>{{ roleName }}</h3>
                    <div class="flex">
                        <Button class="ml-2" color="valorant" @click="selectGroup(roleName.toString())" :disabled="isRolling">
                            {{ t('Select Group') }}
                        </Button>
                    </div>
                </div>

                <!-- ****** ****** ****** ****** ****** ****** Agent Icons ****** ****** ****** ****** ****** ****** -->
                <div class="flex flex-wrap gap-2">
                    <div v-for="agent in group" :key="agent.uuid" class="group relative">
                        <img
                            class="w-12 cursor-pointer"
                            :class="{
                                'grayscale-0': agent.selected,
                                grayscale: !agent.selected,
                            }"
                            :src="agent.displayIcon"
                            :alt="agent.displayName"
                            @click="agentSelect(agent)"
                        />
                        <span
                            class="bg-accent pointer-events-none absolute bottom-full left-1/2 z-10 mb-1 min-w-max -translate-x-1/2 rounded-md px-2 py-1 text-center text-foreground opacity-0 transition-opacity group-hover:opacity-100"
                        >
                            {{ agent.selected ? 'Click to disable' : 'Click to enable' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
