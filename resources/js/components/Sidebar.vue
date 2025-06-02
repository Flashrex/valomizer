<template>
    <nav class="bg-card flex h-screen w-24 flex-col items-center gap-8 p-4">
        <img :src="ValorantLogo" alt="valorant logo" class="mb-4 h-16 w-16" />
        <ul class="flex flex-col items-center justify-center gap-8">
            <li class="flex items-center justify-center" :class="{ active: currentRoute === 'agents' }">
                <Link href="/" class="flex flex-col items-center justify-center">
                    <img :src="AgentIcon" alt="agent icon" class="inverted h-8 w-8" />
                    <span>{{ t('Agents') }}</span>
                </Link>
            </li>
            <li class="flex items-center justify-center" :class="{ active: currentRoute === 'maps' }">
                <Link href="maps" class="flex flex-col items-center justify-center">
                    <img :src="MapIcon" alt="maps icon" class="inverted h-8 w-8" />
                    <span>{{ t('Maps') }}</span>
                </Link>
            </li>
            <li class="flex items-center justify-center" :class="{ active: currentRoute === 'about' }">
                <Link href="about" class="flex flex-col items-center justify-center">
                    <img :src="AboutIcon" alt="about icon" class="inverted h-8 w-8" />
                    <span>{{ t('About') }}</span>
                </Link>
            </li>
        </ul>
    </nav>
</template>

<script lang="ts" setup>
import { Link } from '@inertiajs/vue3';

import AboutIcon from '@/assets/icons/about.svg';
import AgentIcon from '@/assets/icons/agent.svg';
import MapIcon from '@/assets/icons/map.svg';
import ValorantLogo from '@/assets/icons/valorant_logo.svg';

import { ref } from 'vue';
import { useI18n } from 'vue-i18n';
import { route } from '../../../vendor/tightenco/ziggy/src/js';
const { t } = useI18n();

const currentRoute = ref(route().current());
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
