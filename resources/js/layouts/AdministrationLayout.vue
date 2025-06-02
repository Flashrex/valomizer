<template>
    <Notification />
    <div class="flex">
        <main class="w-full">
            <slot />
        </main>
    </div>
</template>

<script lang="ts" setup>
import Notification from '@/components/Notification.vue';
</script>
