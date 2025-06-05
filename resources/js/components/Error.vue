<script setup lang="ts">
import { useI18n } from 'vue-i18n';
const { t } = useI18n();

const props = defineProps({
    errors: Array<String>
});

</script>

<template>
    <ul v-if="props.errors">
        <li v-for="(error, index) in errors" :key="index">{{ t(error.toString()) }}</li>
    </ul>
</template>

<style scoped>
ul {
    list-style-type: none;
    padding: 0;
    margin: 0;
}

li {
    color: rgb(226, 52, 52);
    font-size: 0.8rem;
    margin: 0.2rem;
}
</style>