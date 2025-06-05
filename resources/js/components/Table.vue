<template>
    <div class="mb-2 flex w-full items-center justify-between">
        <h2 class="text-lg font-bold">{{ t(name) ?? name }}</h2>
        <div class="flex items-center gap-2">
            <label for="search">{{ t('Search:') }}</label>
            <input id="search" type="text" class="rounded border p-1"
                :placeholder="search.length > 0 ? search.join(', ') : t('Search')"
                @input="$emit('search', ($event.target as HTMLInputElement).value)" />
        </div>
    </div>

    <Divider />

    <table class="w-full border-separate [border-spacing:0_1rem]">
        <thead class="p-6">
            <tr class="bg-card text-xs text-muted-foreground">
                <th 
                    v-for="column in columns" 
                    :key="column.label" 
                    class="p-2 text-muted-foreground font-light"
                    scope="col"
                    :class="{
                        'text-left': column.align === 'left',
                        'text-center': column.align === 'center',
                        'text-right': column.align === 'right',
                    }"
                    @click="column.sortable && $emit('sort', column.value)"
                    :style="{ cursor: column.sortable ? 'pointer' : 'default' }"
                >
                    {{ t(column.label) ?? column.label }}
                    <span v-if="column.sortable" class="ml-1 text-xs text-muted-foreground">
                        {{ column.value === sortedBy ? (sortOrder === 'asc' ? '↑' : '↓') : '' }}
                    </span>
                </th>
            </tr>
        </thead>
        <tbody class="p-6">
            <tr class="bg-card" v-for="row in data" :key="row.id || row.uuid">
                <td 
                    v-for="column in columns" 
                    :key="column.value" 
                    class="px-2 py-1 text-xs text-muted-foreground"
                    :class="{
                        'text-left': column.align === 'left',
                        'text-center': column.align === 'center',
                        'text-right': column.align === 'right',
                    }"
                >
                    <slot
                        :name="`col-${column.value}`"
                        :row="row"
                        :column="column"
                    >
                        {{ getValue(row, column.value) }}
                    </slot>
                </td>
            </tr>
        </tbody>
    </table>

    <div class="flex w-full items-center justify-between p-2 bg-card rounded-lg">
        <div class="text-xs">
            {{ t('Showing x to y of z entries', {
                from: (page - 1) * perPage + 1,
                to: Math.min(page * perPage, total),
                total: total,
            }) }}
        </div>
        <div v-if="showPagination" class="flex">
            <button v-if="totalPages > 1 && page > 1"
                class="cursor-pointer rounded py-1 text-white text-xs" :disabled="page <= 1"
                @click="$emit('update:page', page - 1)"
            >
                {{ '<' }} 
            </button>
            <span class="flex items-center justify-center rounded px-2 text-white text-xs">
                {{ page }} / {{ totalPages }}
            </span>
            <button v-if="page < totalPages"
                class="cursor-pointer rounded py-1 text-white text-xs"
                :disabled="page >= totalPages" @click="$emit('update:page', page + 1)"
            >
                {{ '>' }}
            </button>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';
import Divider from './Divider.vue';

const { t } = useI18n();

const props = defineProps({
    name: { type: String, default: 'Table' },
    page: { type: Number, default: 1 },
    perPage: { type: Number, default: 10 },
    showPagination: { type: Boolean, default: true },
    columns: {
        type: Array as () => Array<{
            label: string;
            value: string;
            sortable?: boolean;
            align?: 'left' | 'center' | 'right';
        }>,
        default: () => [],
    },
    data: {
        type: Array as () => Array<Record<string, any>>,
        default: () => [],
    },
    total: { type: Number, default: 0 },
    search: {
        type: Array as () => string[],
        default: () => [],
    },
    sortedBy: {
        type: String,
        default: '',
    },
    sortOrder: {
        type: String,
        default: 'asc',
    },
});

const totalPages = computed(() => (props.perPage > 0 ? Math.ceil(props.total / props.perPage) : 1));

function getValue(row: Record<string, any>, key: string) {
    return key.split('.').reduce((acc, part) => acc && acc[part], row) ?? '';
}
</script>
