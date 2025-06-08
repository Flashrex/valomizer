<template>
    <Language />
    <Notification />
    <div class="flex">
        <main class="h-full w-screen">
            <slot />
        </main>
    </div>
</template>

<script lang="ts" setup>
import Language from '@/components/Language.vue';
import Notification from '@/components/Notification.vue';
</script>
