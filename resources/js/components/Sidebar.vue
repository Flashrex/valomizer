<template>
    <nav class="w-24 bg-card h-screen flex flex-col items-center p-4 gap-8">
        <img :src="ValorantLogo" alt="valorant logo" class="w-16 h-16 mb-4" />
        <ul class="flex flex-col items-center justify-center gap-8">
            <li class="flex items-center justify-center" :class="{ active: currentRoute === 'agents' }">
                <Link href="/" class="flex flex-col items-center justify-center">
                    <img :src="AgentIcon" alt="agent icon" class="w-8 h-8 inverted" />
                    <span>{{ t('Agents') }}</span>
                </Link>
            </li>
            <li class="flex items-center justify-center" :class="{ active: currentRoute === 'maps' }">
                <Link href="maps" class="flex flex-col items-center justify-center">
                    <img :src="MapIcon" alt="maps icon" class="w-8 h-8 inverted" />
                    <span>{{ t('Maps') }}</span>
                </Link>
            </li>
            <li class="flex items-center justify-center" :class="{ active: currentRoute === 'about' }">
                <Link href="about" class="flex flex-col items-center justify-center">
                    <img :src="AboutIcon" alt="about icon" class="w-8 h-8 inverted" />
                    <span>{{ t('About') }}</span>
                </Link>
            </li>
        </ul>
    </nav>
</template>

<script lang="ts" setup>
import { Link } from '@inertiajs/vue3'

import ValorantLogo from '@/assets/icons/valorant_logo.svg'
import AgentIcon from '@/assets/icons/agent.svg'
import MapIcon from '@/assets/icons/map.svg'
import AboutIcon from '@/assets/icons/about.svg'

import { useI18n } from 'vue-i18n'
import { route } from '../../../vendor/tightenco/ziggy/src/js'
import { nextTick, ref } from 'vue'
const { t } = useI18n()

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