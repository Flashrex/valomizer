<script setup lang="ts">
import { Agent } from '@/types';
import { computed, onMounted, ref, watch, type ComputedRef } from 'vue';
import { useI18n } from 'vue-i18n';

import selectedImageSrc from '@/assets/icons/selected.png';
import notSelectedImageSrc from '@/assets/icons/not_selected.png';
import Errors from '@/components/Error.vue';
import Card from './Card.vue';
import Button from './Button.vue';

const { t } = useI18n();

const props = defineProps({
    agents: {
        type: Array as () => Agent[],
        default: () => []
    }
});

const currentAgent = ref(null as Agent | null);

interface GroupedAgents {
    [key: string]: Agent[];
}

const noAnimation = ref(false);
const selectedImage = ref(new Image());
const notSelectedImage = ref(new Image());

const errors = ref<string[]>([]);

onMounted(async () => {
    selectedImage.value.src = selectedImageSrc
    notSelectedImage.value.src = notSelectedImageSrc;

    await loadAgentImages();
    currentAgent.value = props.agents.find(agent => agent.selected) || props.agents[0] || null;

    props.agents.forEach(agent => {
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

    if (props.agents.filter(agent => agent.selected).length === 0) {
        errors.value = ['No agents selected'];
        return;
    }

    errors.value = [];

    const filteredAgents = props.agents.filter(agent => agent.selected);

    // Don't roll just select random agent
    if (noAnimation.value) {
        const randomIndex = Math.floor(Math.random() * filteredAgents.length);
        currentAgent.value = filteredAgents[randomIndex];
        return;
    }

    const decreaseRate = 5;
    let interval: number | undefined;


    const startInterval = () => {
        interval = setInterval(() => {
            isRolling = true;
            const randomIndex = Math.floor(Math.random() * filteredAgents.length);
            currentAgent.value = filteredAgents[randomIndex];

            // Decrease speed
            speed += decreaseRate;

            // Clear interval
            clearInterval(interval);

            // Start new interval with decreased speed
            if (speed <= 1000) { // Maximum speed
                startInterval();
            }
        }, speed);
    };

    // Start the interval
    startInterval();
    setTimeout(() => {
        isRolling = false;
        clearInterval(interval);
    }, duration);
};

function agentSelect(agent: Agent) {
    if (isRolling) return;
    agent.selected = !agent.selected;
}

function selectAllAgents(select = true) {
    if (isRolling) return;
    props.agents.forEach(agent => agent.selected = select);
}

function selectGroup(roleName: string) {
    if (isRolling) return;
    props.agents.forEach(agent => agent.selected = agent.role.displayName === roleName);
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
    <div class="m-4 relative flex items-center gap-8 p-2 overflow-hidden">


        <!-- ****** ****** ****** ****** ****** ****** Current Agent Display ****** ****** ****** ****** ****** ****** -->
        <Card v-if="currentAgent" class="w-[20vw] flex flex-col items-center justify-center gap-2">
            <span class="relative bg-transparent w-full h-[40vh] flex items-center justify-center">
                <img 
                    class="absolute top-0 left-0 w-full h-full opacity-25 z-10 invert" 
                    :src="currentAgent.background"
                    :alt="currentAgent.displayName"
                >
                <img 
                    class="relative h-[25vh] opacity-100 z-20" 
                    :src="currentAgent.fullPortrait"
                    :alt="currentAgent.displayName"
                >
            </span>

            <h2 class="valorant-font">{{ currentAgent.displayName }}</h2>
            <div class="flex items-center justify-center gap-2">
                <img class="w-4" :src="currentAgent.role?.displayIcon ?? ''" :alt="currentAgent.role?.displayName">
                <p>{{ currentAgent.role?.displayName }}</p>
            </div>

            <Errors v-if="errors" :errors="errors" />

            <Button class="w-4/5" color="valorant" @click="rollAgents()">{{ t("Spin") }}</Button>
            <div class="flex gap-2 items-center justify-center">
                <label>{{ t('Skip Animation') }}</label>
                <img 
                    v-if="noAnimation" 
                    class="flex items-center justify-center w-6 h-6 cursor-pointer" 
                    :src="selectedImage.src" 
                    alt="selected"
                    @click="noAnimation = !noAnimation"
                >

                <img 
                    v-else 
                    class="flex items-center justify-center w-6 h-6 cursor-pointer" 
                    :src="notSelectedImage.src" 
                    alt="not_selected"
                    @click="noAnimation = !noAnimation"
                >
            </div>
        </Card>

        <!-- ****** ****** ****** ****** ****** ****** Agent Selector ****** ****** ****** ****** ****** ****** -->
        <div class="flex flex-col overflow-auto overflow-x-hidden items-start">
            <div 
                v-for="(group, roleName) in groupedAgents" 
                :key="roleName"
                class="flex justify-center items-start flex-col gap-2 mb-4"
            >
                <!-- ****** ****** ****** ****** ****** ****** Header ****** ****** ****** ****** ****** ****** -->
                <div class="flex items-center">
                    <h3>{{ roleName }}</h3>
                    <div class="flex">
                        <Button 
                            class="ml-2"
                            color="valorant" 
                            @click="selectGroup(roleName.toString())"
                        >
                            {{ t('Select Group') }}
                        </Button>
                    </div>
                </div>

                <!-- ****** ****** ****** ****** ****** ****** Agent Icons ****** ****** ****** ****** ****** ****** -->
                <div class="flex flex-wrap gap-2">
                    <div v-for="agent in group" :key="agent.uuid" class="relative group">
                        <img 
                            class="w-12" 
                            :class="{
                                'grayscale-0': agent.selected,
                                'grayscale': !agent.selected,
                            }"
                            :src="agent.displayIcon"
                            :alt="agent.displayName" 
                            @click="agentSelect(agent)"
                        >
                        <span
                            class="pointer-events-none bg-accent text-white text-center px-2 py-1 rounded-md absolute z-10 bottom-full left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity mb-1 min-w-max">
                            {{ agent.selected ? "Click to disable" : "Click to enable" }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="w-full flex justify-center">
                <div class="flex gap-4">
                    <Button color="valorant" @click="selectAllAgents()">{{ t('Select All') }}</Button>
                    <Button color="valorant" @click="selectAllAgents(false)">{{ t('De-select All') }}</Button>
                </div>
            </div>
        </div>

    </div>
</template>