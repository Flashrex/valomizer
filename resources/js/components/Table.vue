<template>
    <Card>
        <div class="w-full flex items-center justify-between mb-2">
            
            <h2 class="text-lg font-bold">{{ name }}</h2>
            <div class="flex items-center gap-2">
                <label for="search">{{ t('Search:') }}</label>
                <input 
                    id="search"
                    type="text" 
                    class="border rounded p-1" 
                    :placeholder="search.length > 0 ? search.join(', ') : t('Search')" 
                    @input="$emit('search', ($event.target as HTMLInputElement).value)"
                />
            </div>
        

        </div>

        <Divider />

        <table class="w-full">
            <thead>
                <tr>
                    <th v-for="column in columns" :key="column" class="text-left p-2">
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

        <div class="w-full flex items-center justify-between">
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
                    class="px-2 py-1 cursor-pointer bg-secondary text-white rounded"
                    :disabled="page <= 1"
                    @click="$emit('update:page', page - 1)"
                >{{ "<< " + t('Prev') }}</button>
                <span class="mx-2 bg-secondary text-white rounded flex justify-center items-center px-2">{{ page }} / {{ totalPages }}</span>
                <button
                    v-if="page < totalPages"
                    class="px-2 py-1 cursor-pointer bg-secondary text-white rounded"
                    :disabled="page >= totalPages"
                    @click="$emit('update:page', page + 1)"
                >{{ t('Next') +" >>" }}</button>
            </div>
        </div>
    </Card>
</template>

<script lang="ts" setup>
    import { computed } from 'vue';
    import Card from './Card.vue';
    import { useI18n } from 'vue-i18n';
import Divider from './Divider.vue';

    const { t } = useI18n();

    const props = defineProps({
        name: { type: String, default: 'Table' },
        page: { type: Number, default: 1 },
        perPage: { type: Number, default: 10 },
        showPagination: { type: Boolean, default: true },
        columns: {
            type: Array as () => string[],
            default: () => []
        },
        total: { type: Number, default: 0 },
        search: {
            type: Array as () => string[],
            default: () => []
        }
    });

    const totalPages = computed(() =>
        props.perPage > 0 ? Math.ceil(props.total / props.perPage) : 1
    );
</script>