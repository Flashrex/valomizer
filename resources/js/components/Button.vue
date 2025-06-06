<template>
    <button
    :disabled="disabled"
    class="flex items-center justify-center gap-2 rounded-lg px-2 py-1 focus:outline-none transition-colors duration-250 ease-in-out"
    :class="[
      disabled
        ? 'bg-muted'
        : {
            'cursor-pointer': true,
            'bg-primary hover:bg-primary-accent': color === 'primary',
            'bg-secondary hover:bg-secondary-accent': color === 'secondary',
            'bg-destructive hover:bg-destructive-accent': color === 'danger',
            'bg-success hover:bg-success-accent': color === 'success',
            'bg-valorant hover:bg-valorant-accent': color === 'valorant',
          }
    ]"
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
