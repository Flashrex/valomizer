<template>
    <Language />
    <Notification />
    <div class="flex">
        <main class="w-full">
            <slot />
        </main>
    </div>
</template>

<script lang="ts" setup>
import Language from '@/components/Language.vue';
import Notification from '@/components/Notification.vue';
</script>
