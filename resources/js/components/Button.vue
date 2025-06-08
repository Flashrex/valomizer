<template>
    <button
        :disabled="disabled"
        class="text-background dark:text-foreground flex items-center justify-center gap-2 rounded-lg px-2 py-1 transition-colors duration-250 ease-in-out focus:outline-none"
        :class="[
            disabled
                ? 'bg-muted'
                : {
                      'cursor-pointer': true,
                      'bg-primary hover:bg-primary-accent': color === 'primary',
                      'bg-secondary hover:bg-secondary-accent': color === 'secondary',
                      'bg-destructive hover:bg-destructive-accent': color === 'danger',
                      'bg-success hover:bg-success-accent': color === 'success'
                  },
        ]"
        :style="color === 'valorant' ? { background: 'linear-gradient(135deg, rgb(255, 51, 66) 0%, rgb(255, 48, 64) 0.01%, rgb(255, 125, 102) 100%)' } : {}"
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
    disabled: {
        type: Boolean,
        default: false,
    },
});
</script>
