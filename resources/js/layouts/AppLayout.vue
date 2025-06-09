<template>
    <Language />
    <Notification />

    <img
        :src="MenuIcon"
        alt="valorant logo"
        class="fixed top-4 left-4 z-10 m-2 block h-8 w-8 cursor-pointer invert md:hidden"
        @click="toggleSidebar"
    />

    <div class="flex h-screen max-h-screen">
        <Sidebar ref="sidebarRef" />
        <main class="relative flex h-full w-full flex-col">
            <div class="h-full min-h-0 flex-1 overflow-auto">
                <slot />
            </div>
            <footer class="w-full">
                <div class="p-4 text-center text-xs">
                    <p>This is a fan made project and in no way associated with Valorant or Riot Games.</p>
                    <p>
                        Created with <span class="text-valorant">♥</span> by
                        <a class="link text-valorant" href="https://github.com/Flashrex" target="_blank" rel="noopener noreferrer"
                            >Flashrex</a
                        >
                        and
                        <a class="link text-valorant" href="https://github.com/zlyfer" target="_blank" rel="noopener noreferrer"
                            >zlyfer.</a
                        >
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
