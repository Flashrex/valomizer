<template>
    <button
        class="flex items-center justify-center gap-2 rounded-lg px-2 py-1 focus:outline-none"
        :class="{
            'bg-primary hover:bg-primary-accent': color === 'primary',
            'bg-secondary hover:bg-secondary-accent': color === 'secondary',
            'bg-destructive hover:bg-destructive-accent': color === 'danger',
            'bg-success hover:bg-success-accent': color === 'success',
        }"
    >
        <slot name="pre" />
        <slot />
        <slot name="post" />
    </button>
</template>

<script lang="ts" setup>
defineProps({
    color: {
        type: String,
        default: 'primary',
    },
});
</script>
