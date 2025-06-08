<template>
    <Head title="Maps" />
    <div class="m-4 relative flex justify-center items-center gap-8 p-2 overflow-hidden">
        <MapRaffle :maps="props.maps" ></MapRaffle>
    </div>
</template>

<script setup lang="ts">
import MapRaffle from '@/components/MapRaffle.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Map } from '@/types';
import { Head } from '@inertiajs/vue3';

const props = defineProps({ maps: Array<Map> });

defineOptions({
    layout: AppLayout,
});
</script>
