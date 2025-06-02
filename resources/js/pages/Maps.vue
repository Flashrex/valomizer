<template>
    <Head title="Maps" />
    <div>
        <h2 class="mb-4 text-2xl font-semibold">Welcome to Maps</h2>
        <p>This is the maps content.</p>
    </div>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({ maps: Array<object> });

defineOptions({
    layout: AppLayout,
});

console.log('Maps props:', props.maps);
</script>
