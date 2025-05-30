<template>
    <img 
        :src="src" 
        :alt="alt"
        :width="width"
        :height="height"
        :class="inverted ? 'inverted' : ''"
    >
</template>

<script lang="ts" setup>
import { computed } from 'vue'

const props = defineProps({
    src: {
        type: String,
        required: true
    },
    alt: {
        type: String,
        default: ''
    },
    width: {
        type: [String, Number],
        default: 'auto'
    },
    height: {
        type: [String, Number],
        default: 'auto'
    },
    inverted: {
        type: Boolean,
        default: false
    }
})

const src = computed(() => {
    return new URL(`../assets/icons/${props.src}`, import.meta.url).href
}) 
</script>

<style scoped>
.inverted {
    filter: invert(1);
}
</style>