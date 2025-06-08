<template>
    <Language />
    <Notification />
    
    <img 
        :src="MenuIcon" alt="valorant logo" class="z-10 fixed top-4 left-4 h-8 w-8 m-2 invert block md:hidden cursor-pointer"
        @click="toggleSidebar" 
    />

    <div class="flex h-screen max-h-screen">
        <Sidebar ref="sidebarRef" />
        <main class="relative flex flex-col w-full h-full">
            <div class="flex-1 min-h-0 overflow-auto h-full">
                <slot />
            </div>
            <footer class="w-full">
                <div class="text-xs text-center p-4">
                    <p>This is a fan made project and in no way associated with Valorant or Riot Games.</p>
                    <p>
                        Created with <span class="text-valorant">♥</span> by
                        <a class="link text-valorant" href="https://github.com/Flashrex/valorant_randomizer" target="_blank"
                            rel="noopener noreferrer">Flashrex</a>
                        and
                        <a class="link text-valorant" href="https://github.com/Flashrex/valorant_randomizer" target="_blank"
                            rel="noopener noreferrer">zlyfer.</a>
                    </p>
                </div>
            </footer>
        </main>
    </div>


</template>

<script lang="ts" setup>
import Language from '@/components/Language.vue';
import Notification from '@/components/Notification.vue';
import Sidebar from '@/components/Sidebar.vue';
import { ref } from 'vue';

import MenuIcon from '@/assets/icons/menu.svg';

const sidebarRef = ref<InstanceType<typeof Sidebar> | null>(null);

function toggleSidebar() {
    sidebarRef.value?.toggleSidebar();
}
</script>
