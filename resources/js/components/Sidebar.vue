<template>
    <nav class="w-48 bg-background h-screen flex flex-col items-center justify-between p-4">
        <ul class="sidebar-menu">
            <li class="flex flex-col items-center justify-center">
                <Link href="/">
                    <Image src="agent.svg" alt="Home" width="24" height="24" :inverted="true" />
                    Agents
                </Link>
            </li>
            <li class="flex flex-col items-center justify-center">
                <Link href="maps">
                    <Image src="map.svg" alt="Home" width="24" height="24" :inverted="true" />
                    Maps
                </Link>
            </li>
            <li class="flex flex-col items-center justify-center">
                <Link href="about">
                    <Image src="about.svg" alt="Home" width="24" height="24" :inverted="true" />
                    About
                </Link>
            </li>
        </ul>
    </nav>
</template>

<script lang="ts" setup>
import { Link } from '@inertiajs/vue3'
import Image from './Image.vue';
</script>