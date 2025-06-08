<template>
    <nav
        class="bg-card absolute top-0 left-0 z-50 flex h-screen w-24 flex-col items-center justify-between gap-8 overflow-x-hidden p-4 transition-all duration-300 ease-in-out md:justify-start"
        :class="{
            'translate-x-0': active,
            '-translate-x-full': !active,
        }"
    >
        <img :src="ValorantLogo" alt="valorant logo" class="mt-2 mb-4 hidden h-8 w-8 md:block" />
        <ul class="flex flex-col items-center justify-center gap-8">
            <img :src="MenuIcon" alt="valorant logo" class="mt-4 block h-8 w-8 cursor-pointer invert md:hidden" @click="toggleSidebar" />
            <li class="flex items-center justify-center" :class="{ active: currentRoute === 'agents' }">
                <Link href="/" class="flex flex-col items-center justify-center">
                    <img :src="AgentIcon" alt="agent icon" class="inverted h-8 w-8 md:h-12 md:w-12" />
                    <span class="text-xs">{{ t('Agents') }}</span>
                </Link>
            </li>
            <li class="flex items-center justify-center" :class="{ active: currentRoute === 'maps' }">
                <Link href="maps" class="flex flex-col items-center justify-center">
                    <img :src="MapIcon" alt="maps icon" class="inverted h-8 w-8 md:h-12 md:w-12" />
                    <span class="text-xs">{{ t('Maps') }}</span>
                </Link>
            </li>
            <li class="flex items-center justify-center" :class="{ active: currentRoute === 'about' }">
                <Link href="about" class="flex flex-col items-center justify-center">
                    <img :src="AboutIcon" alt="about icon" class="inverted h-8 w-8 md:h-12 md:w-12" />
                    <span class="text-xs">{{ t('About') }}</span>
                </Link>
            </li>
        </ul>
        <img :src="ValorantLogo" alt="valorant logo" class="block h-8 w-8 md:hidden" />
    </nav>
</template>

<script lang="ts" setup>
import { Link, usePage } from '@inertiajs/vue3';

import AboutIcon from '@/assets/icons/about.svg';
import AgentIcon from '@/assets/icons/agent.svg';
import MapIcon from '@/assets/icons/map.svg';
import MenuIcon from '@/assets/icons/menu.svg';
import ValorantLogo from '@/assets/icons/valomizer_logo.svg';

import { ref, watch } from 'vue';
import { useI18n } from 'vue-i18n';
import { route } from '../../../vendor/tightenco/ziggy/src/js';
const { t } = useI18n();
const page = usePage();

defineExpose({
    toggleSidebar,
});

const active = ref<boolean>(window.innerWidth >= 768);

const currentRoute = ref(route().current());

watch(
    () => page.url, // or page.url if not using .value
    () => {
        currentRoute.value = route().current();
    },
);

function toggleSidebar() {
    active.value = !active.value;
}
</script>

<style scoped>
li:hover {
    cursor: pointer;
}

li:hover img {
    filter: brightness(0) saturate(100%) invert(46%) sepia(36%) saturate(5351%) hue-rotate(326deg) brightness(96%) contrast(108%);
}

li:hover span {
    color: var(--valorant-red);
}

li.active img {
    filter: brightness(0) saturate(100%) invert(46%) sepia(36%) saturate(5351%) hue-rotate(326deg) brightness(96%) contrast(108%);
}

li.active span {
    color: var(--valorant-red);
}
</style>
