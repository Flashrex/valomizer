<template>
  <Head title="Maps" />
  <div>
    <h2 class="text-2xl font-semibold mb-4">Welcome to Maps</h2>
    <p>This is the maps content.</p>
  </div>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head } from '@inertiajs/vue3'

const props = defineProps({ maps: Array<Object> })

defineOptions({
  layout: AppLayout
})

console.log('Maps props:', props.maps)
</script>