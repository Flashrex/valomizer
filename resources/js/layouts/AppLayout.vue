<template>
  <div class="flex">
    <Sidebar />
    <main class="w-full">
      <slot />
    </main>
  </div>
</template>

<script lang="ts" setup>
import Sidebar from '@/components/Sidebar.vue';
</script>