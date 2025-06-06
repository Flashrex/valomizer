<template>
    <Head title="Dashboard" />
    <div class="mx-auto w-[90vw] md:w-[60vw]">
        <section id="cards" class="p-4">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
                <Card class="flex items-center justify-between gap-4">
                    <div class="flex flex-col items-start">
                        <p>{{ t('Total Visits') }}</p>
                        <h2 class="text-valorant text-xl font-bold">{{ visits }}</h2>
                    </div>
                    <img :src="eyeIcon" alt="visits icon" class="h-16 w-16" />
                </Card>
                <Card class="flex items-center justify-between gap-4">
                    <div class="flex flex-col items-start">
                        <p>{{ t('Uptime') }}</p>
                        <h2 class="text-valorant text-xl font-bold">{{ uptime }}</h2>
                    </div>
                    <img :src="timerIcon" alt="uptime icon" class="h-16 w-16" />
                </Card>
                <Card class="flex items-center justify-between gap-4">
                    <div class="flex flex-col items-start">
                        <p>{{ t('Agents') }}</p>
                        <h2 class="text-valorant text-xl font-bold">{{ agents.length }}</h2>
                        <span class="text-xs">Last updated: {{ fetchedAgents  }}</span>
                    </div>
                    <img :src="agentIcon" alt="agents icon" class="inverted h-16 w-16" />
                </Card>
                <Card class="flex items-center justify-between gap-4">
                    <div class="flex flex-col items-start">
                        <p>{{ t('Maps') }}</p>
                        <h2 class="text-valorant text-xl font-bold">{{ maps.length }}</h2>
                        <span class="text-xs">Last updated: {{ fetchedMaps }}</span>
                    </div>
                    <img :src="mapIcon" alt="maps icon" class="inverted h-16 w-16" />
                </Card>
            </div>
        </section>

        <section id="agents" class="p-4">
            <Table
                name="Agents"
                :page="agentData.page"
                :perPage="agentData.perPage"
                :columns="agentData.columns"
                :data="filteredAgents"
                :total="agentData.searchQuery ? filteredAgents.length : agents.length"
                :search="['UUID', 'Name', 'Role']"
                :sorted-by="agentData.sortBy"
                :sort-order="agentData.sortOrder"
                @update:page="agentData.page = $event"
                @search="agentData.searchQuery = $event"
                @sort="(sortBy: string) => {
                    agentData.sortBy = sortBy;
                    agentData.sortOrder = agentData.sortOrder === 'asc' ? 'desc' : 'asc';
                }"
            >
                <template #header>
                    <Button @click="runFetchAgentsCommand" color="valorant">
                        <span>Fetch</span>
                    </Button>
                </template>

                <template #col-displayIconSmall="{ row }">
                    <img :src="row.displayIconSmall" :alt="row.displayName" class="inline-block h-8 w-8" />
                </template>

                <template #col-active="{ row }">
                    <form @submit.prevent="toggleAgent(row as Agent)">
                        <input
                            type="checkbox"
                            :checked="row.active"
                            @change="toggleAgent(row as Agent)"
                            class="h-4 w-4"
                        />
                    </form>

                </template>

                <template #col-actions="{ row }">
                    <div class="flex items-center justify-center gap-2 p-1.5">
                        <Button :color="'danger'" @click="deleteAgent(row.id)">
                            <template #pre>
                                <img :src="deleteIcon" alt="delete icon" class="h-4 w-4" />
                            </template>
                            <span class="text-xs text-primary">{{ t('Delete') }}</span>
                        </Button>
                    </div>
                </template>
            </Table>
        </section>

        <section id="maps" class="p-4">
            <Table
                name="Maps"
                :page="mapData.page"
                :perPage="mapData.perPage"
                :columns="mapData.columns"
                :data="filteredMaps"
                :total="mapData.searchQuery ? filteredMaps.length : maps.length"
                :search="['UUID', 'Name', 'Gamemode']"
                :sorted-by="mapData.sortBy"
                :sort-order="mapData.sortOrder"
                @update:page="mapData.page = $event"
                @search="mapData.searchQuery = $event"
                @sort="(sortBy: string) => {
                    mapData.sortBy = sortBy;
                    mapData.sortOrder = mapData.sortOrder === 'asc' ? 'desc' : 'asc';
                }"
            >
                <template #header>
                    <Button @click="runFetchMapsCommand" color="valorant">
                        <span>Fetch</span>
                    </Button>
                </template>

                <template #col-displayIcon="{ row }">
                    <img :src="row.displayIcon" :alt="row.displayName" class="inline-block h-8 w-8" />
                </template>

                <template #col-active="{ row }">
                    <form @submit.prevent="toggleMap(row as Map)">
                        <input
                            type="checkbox"
                            :checked="row.active"
                            @change="toggleMap(row as Map)"
                            class="h-4 w-4"
                        />
                    </form>
                </template>

                <template #col-gamemode="{ row }">
                    <select v-model="row.gamemode" class="rounded border p-1" @change="updateGamemode($event, row as Map)">
                        <option v-for="mode in gamemodes" :key="mode.value" :value="mode.value" class="text-secondary">
                            {{ mode.label }}
                        </option>
                    </select>
                </template>

                <template #col-actions="{ row }">
                    <div class="flex items-center justify-center gap-2 p-1">
                        <Button :color="'danger'" @click="deleteMap(row.id)">
                            <template #pre>
                                <img :src="deleteIcon" alt="delete icon" class="h-4 w-4" />
                            </template>
                            <span class="text-xs text-primary">{{ t('Delete') }}</span>
                        </Button>
                    </div>
                </template>
            </Table>
        </section>
    </div>
</template>

<script setup lang="ts">
import Button from '@/components/Button.vue';
import Card from '@/components/Card.vue';
import Table from '@/components/Table.vue';
import { Agent, Map } from '@/types';
import { useI18n } from 'vue-i18n';
import { Head } from '@inertiajs/vue3';

import agentIcon from '@/assets/icons/agent.svg';
import eyeIcon from '@/assets/icons/eye.svg';
import mapIcon from '@/assets/icons/map.svg';
import timerIcon from '@/assets/icons/timer.svg';

import deleteIcon from '@/assets/icons/delete.svg';
import { useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { filterSortPaginate } from '@/util';
const { t } = useI18n();

defineOptions({
    layout: AdministrationLayout,
});

const props = defineProps<{
    agents: Array<Agent>;
    maps: Array<Map>;
    visits: number;
    uptime: string;
    totalAgents: number;
    fetchedAgents: string;
    totalMaps: number;
    fetchedMaps: string;
}>();

const uptime = computed(() => {
    //Uptime is a string as carbon date datetime:Y-m-d H:i:s
    const uptimeDate = new Date(props.uptime);
    const now = new Date();
    const diff = now.getTime() - uptimeDate.getTime();

    const seconds = Math.floor(diff / 1000);
    const minutes = Math.floor(seconds / 60);
    const hours = Math.floor(minutes / 60);
    const days = Math.floor(hours / 24);
    const months = Math.floor(days / 30);
    const years = Math.floor(months / 12);

    let uptimeString = '';
    if (years > 0) {
        uptimeString += `${years} ${t('y', { count: years })} `;
    }
    if (months > 0) {
        uptimeString += `${months % 12} ${t('m', { count: months % 12 })} `;
    }
    if (days > 0) {
        uptimeString += `${days % 30} ${t('d', { count: days % 30 })} `;
    }
    if (hours > 0) {
        uptimeString += `${hours % 24} ${t('h', { count: hours % 24 })} `;
    }
    if (minutes > 0) {
        uptimeString += `${minutes % 60} ${t('m', { count: minutes % 60 })} `;
    }
    if (seconds > 0) {
        uptimeString += `${seconds % 60} ${t('s', { count: seconds % 60 })}`;
    }
    return uptimeString.trim() || t('just now');
});

/****** ****** ****** ****** ****** ****** Agents ****** ****** ****** ****** ****** ******/

const agentData = ref({
    page: 1,
    perPage: 10,
    searchQuery: '',
    sortBy: 'displayName',
    sortOrder: 'asc',
    columns: [
        { label: 'DisplayName', value: 'displayName', sortable: true },
        { label: 'Role', value: 'role.displayName', sortable: true},
        { label: 'Icon', value: 'displayIconSmall'},
        { label: 'Active', value: 'active', sortable: true},
        { label: 'Last Updated', value: 'updated_at', sortable: true},
        { label: 'Created At', value: 'created_at', sortable: true},
        { label: 'Actions', value: 'actions' }
    ],
});

const filteredAgents = computed(() =>
  filterSortPaginate(
    props.agents,
    {
        page: agentData.value.page,
        perPage: agentData.value.perPage,
        searchQuery: agentData.value.searchQuery,
        sortBy: agentData.value.sortBy,
        sortOrder: agentData.value.sortOrder as 'asc' | 'desc',
    },
    ['displayName', 'uuid', 'role.displayName']
  )
);

function toggleAgent(agent: Agent) {
    const form = useForm({
        active: !agent.active,
    });

    form.patch(route('agent.update', agent._id), {
        preserveScroll: true,
        onSuccess: () => {
            notify.info(t('Agent status updated successfully!'));
        },
        onError: (errors) => {
            notify.error(t('Failed to update agent status: {errors}', { errors: Object.values(errors).join(', ') }));
        },
    });
}

function deleteAgent(agentId: number) {
    if (confirm(t('Are you sure you want to delete this agent?'))) {
        const form = useForm({});
        form.delete(route('agent.destroy', agentId), {
            preserveScroll: true,
            onSuccess: () => {
                notify.info(t('Agent deleted successfully!'));
            },
            onError: (errors) => {
                notify.error(t('Failed to delete agent: {errors}', { errors: Object.values(errors).join(', ') }));
            },
        });
    }
}

function runFetchAgentsCommand() {
    const form = useForm({});
    form.post(route('admin.fetch.agents'), {
        preserveScroll: true,
        onSuccess: () => {
            notify.info(t('Fetch agents command executed successfully!'));
        },
        onError: (errors) => {
            notify.error(t('Failed to execute fetch agents command: {errors}', { errors: Object.values(errors).join(', ') }));
        },
    });
}


/****** ****** ****** ****** ****** ****** Maps ****** ****** ****** ****** ****** ******/

const mapData = ref({
    page: 1,
    perPage: 10,
    searchQuery: '',
    sortBy: 'displayName',
    sortOrder: 'asc',
    columns: [
        { label: 'DisplayName', value: 'displayName', sortable: true},
        { label: 'Gamemode', value: 'gamemode', sortable: true},
        { label: 'Icon', value: 'displayIcon' },
        { label: 'Active', value: 'active', sortable: true},
        { label: 'Last Updated', value: 'updated_at', sortable: true},
        { label: 'Created At', value: 'created_at', sortable: true},
        { label: 'Actions', value: 'actions' }
    ],
});

const gamemodes = ref([
    { value: 'competitive', label: 'Competitive' },
    { value: 'team-deathmatch', label: 'Team-Deathmatch' },
    { value: 'training', label: 'Training' },
]);

const filteredMaps = computed(() =>
  filterSortPaginate(
    props.maps,
    {
        page: mapData.value.page,
        perPage: mapData.value.perPage,
        searchQuery: mapData.value.searchQuery,
        sortBy: mapData.value.sortBy,
        sortOrder: mapData.value.sortOrder as 'asc' | 'desc',
    },
    ['displayName', 'uuid', 'gamemode']
  )
);

const toggleMap = (map: Map) => {
    const form = useForm({
        active: !map.active,
    });

    form.patch(route('map.update', map._id), {
        preserveScroll: true,
        onSuccess: () => {
            notify.info(t('Map status updated successfully!'));
        },
        onError: (errors) => {
            notify.error(t('Failed to update map status: {errors}', { errors: Object.values(errors).join(', ') }));
        },
    });
};

function updateGamemode(event: Event, map: Map) {
    const target = event.target as HTMLSelectElement;
    const gamemode = target.value;
    if (!gamemode) {
        return;
    }
    map.gamemode = gamemode;

    const form = useForm({
        gamemode: gamemode,
    });

    form.patch(route('map.update', map._id), {
        preserveScroll: true,
        onSuccess: () => {
            notify.info(t('Gamemode updated successfully!'));
        },
        onError: (errors) => {
            notify.error(t('Failed to update gamemode: {errors}', { errors: Object.values(errors).join(', ') }));
        },
    });
}

function deleteMap(mapId: number) {
    if (confirm(t('Are you sure you want to delete this map?'))) {
        const form = useForm({});
        form.delete(route('map.destroy', mapId), {
            preserveScroll: true,
            onSuccess: () => {
                notify.info(t('Map deleted successfully!'));
            },
            onError: (errors) => {
                notify.error(t('Failed to delete map: {errors}', { errors: Object.values(errors).join(', ') }));
            },
        });
    }
}

function runFetchMapsCommand() {
    const form = useForm({});
    form.post(route('admin.fetch.maps'), {
        preserveScroll: true,
        onSuccess: () => {
            notify.info(t('Fetch maps command executed successfully!'));
        },
        onError: (errors) => {
            notify.error(t('Failed to execute fetch maps command: {errors}', { errors: Object.values(errors).join(', ') }));
        },
    });
}

import { useNotifications } from '@/composables/useNotification';
import AdministrationLayout from '@/layouts/AdministrationLayout.vue';
const notify = useNotifications();

</script>
