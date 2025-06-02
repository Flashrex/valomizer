<template>
    <Card>
        <div class="mb-2 flex w-full items-center justify-between">
            <h2 class="text-lg font-bold">{{ name }}</h2>
            <div class="flex items-center gap-2">
                <label for="search">{{ t('Search:') }}</label>
                <input
                    id="search"
                    type="text"
                    class="rounded border p-1"
                    :placeholder="search.length > 0 ? search.join(', ') : t('Search')"
                    @input="$emit('search', ($event.target as HTMLInputElement).value)"
                />
            </div>
        </div>

        <Divider />

        <table class="w-full">
            <thead>
                <tr>
                    <th v-for="column in columns" :key="column" class="p-2 text-left">
                        {{ column }}
                    </th>
                </tr>
            </thead>
            <tbody>
                <slot name="body" />
            </tbody>
            <tfoot v-if="$slots.footer">
                <slot name="footer" />
            </tfoot>
        </table>

        <Divider />

        <div class="flex w-full items-center justify-between">
            <div>
                {{ t('Showing') }}
                <span>{{ (page - 1) * perPage + 1 }}</span>
                {{ t('to') }}
                <span>{{ Math.min(page * perPage, total) }}</span>
                {{ t('of') }}
                <span>{{ total }}</span>
                {{ t('entries') }}
            </div>
            <div v-if="showPagination" class="mt-2 flex justify-end">
                <!-- Example pagination controls -->
                <button
                    v-if="totalPages > 1 && page > 1"
                    class="bg-secondary cursor-pointer rounded px-2 py-1 text-white"
                    :disabled="page <= 1"
                    @click="$emit('update:page', page - 1)"
                >
                    {{ '<< ' + t('Prev') }}
                </button>
                <span class="bg-secondary mx-2 flex items-center justify-center rounded px-2 text-white">{{ page }} / {{ totalPages }}</span>
                <button
                    v-if="page < totalPages"
                    class="bg-secondary cursor-pointer rounded px-2 py-1 text-white"
                    :disabled="page >= totalPages"
                    @click="$emit('update:page', page + 1)"
                >
                    {{ t('Next') + ' >>' }}
                </button>
            </div>
        </div>
    </Card>
</template>

<script lang="ts" setup>
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';
import Card from './Card.vue';
import Divider from './Divider.vue';

const { t } = useI18n();

const props = defineProps({
    name: { type: String, default: 'Table' },
    page: { type: Number, default: 1 },
    perPage: { type: Number, default: 10 },
    showPagination: { type: Boolean, default: true },
    columns: {
        type: Array as () => string[],
        default: () => [],
    },
    total: { type: Number, default: 0 },
    search: {
        type: Array as () => string[],
        default: () => [],
    },
});

const totalPages = computed(() => (props.perPage > 0 ? Math.ceil(props.total / props.perPage) : 1));
</script>
