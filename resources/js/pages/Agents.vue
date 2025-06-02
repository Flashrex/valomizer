<template>
    <Head title="Agents" />
    <div>
        <h2 class="text-primary mb-4 text-2xl font-semibold">Welcome to Agents</h2>
        <p class="text-primary mb-4 font-semibold">This is the agents content.</p>
    </div>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({ agents: Array<object> });

defineOptions({
    layout: AppLayout,
});

console.log('Agents props:', props.agents);
</script>
