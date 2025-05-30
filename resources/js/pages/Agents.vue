<template>
  <Head title="Agents" />
  <div>
    <h2 class="text-2xl font-semibold mb-4 text-primary">Welcome to Agents</h2>
    <p class="font-semibold mb-4 text-primary">This is the agents content.</p>
  </div>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head } from '@inertiajs/vue3'

const props = defineProps({ agents: Array<Object> })

defineOptions({
  layout: AppLayout
})

console.log('Agents props:', props.agents)
</script>