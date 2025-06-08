<template>
    <Head title="Agents" />
    <div class="w-full h-full flex items-start mt-16 justify-center overflow-hidden">
        <AgentCarousel :agents="props.agents" />
    </div>
</template>

<script setup lang="ts">
import AgentCarousel from '@/components/AgentCarousel.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Agent } from '@/types';
import { Head } from '@inertiajs/vue3';

const props = defineProps({ agents: Array<Agent> });

defineOptions({
    layout: AppLayout,
});

</script>
