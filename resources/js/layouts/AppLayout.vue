<template>
    <Language />
    <Notification />
    <div class="flex">
        <Sidebar />
        <main class="w-full">
            <slot />
        </main>
    </div>

    <footer class="absolute bottom-2 w-full">
            <div class="text-xs text-center p-4">
                <p>This is a fan made project and in no way associated with Valorant or Riot Games.</p>
                <p>
                    Created with <span class="text-valorant">♥</span> by 
                    <a class="link text-valorant" href="https://github.com/Flashrex/valorant_randomizer" target="_blank" rel="noopener noreferrer">Flashrex</a>
                    and 
                    <a class="link text-valorant" href="https://github.com/Flashrex/valorant_randomizer" target="_blank" rel="noopener noreferrer">zlyfer.</a></p>
            </div>
        </footer>
</template>

<script lang="ts" setup>
import Language from '@/components/Language.vue';
import Notification from '@/components/Notification.vue';
import Sidebar from '@/components/Sidebar.vue';
</script>
