<template>
    <div class="w-[60vw] mx-auto">
        <section id="cards" class="p-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <Card class="flex items-center justify-between gap-4">
                    <div class="flex flex-col items-start">
                        <p>{{ t('Total Visits') }}</p>
                        <h2 class="text-xl font-bold text-valorant">1337</h2>
                    </div>
                    <img
                        :src="eyeIcon"
                        alt="visits icon"
                        class="w-16 h-16"
                    >
                </Card>
                <Card class="flex items-center justify-between gap-4">
                    <div class="flex flex-col items-start">
                        <p>{{ t('Uptime') }}</p>
                        <h2 class="text-xl font-bold text-valorant">24d 15h 33m</h2>
                    </div>
                    <img
                        :src="timerIcon"
                        alt="uptime icon"
                        class="w-16 h-16"
                    >
                </Card>
                <Card class="flex items-center justify-between gap-4">
                    <div class="flex flex-col items-start">
                        <p>{{ t('Agents') }}</p>
                        <h2 class="text-xl font-bold text-valorant">{{ agents.length }}</h2>
                        <span class="text-xs">Last updated: 24.05.2025 13:37</span>
                    </div>
                    <img
                        :src="agentIcon"
                        alt="agents icon"
                        class="w-16 h-16 inverted"
                    >
                </Card>
                <Card class="flex items-center justify-between gap-4">
                    <div class="flex flex-col items-start">
                        <p>{{ t('Maps') }}</p>
                        <h2 class="text-xl font-bold text-valorant">17 Maps</h2>
                        <span class="text-xs">Last updated: 24.05.2025 13:37</span>
                    </div>
                    <img
                        :src="mapIcon"
                        alt="maps icon"
                        class="w-16 h-16 inverted"
                    >
                </Card>
            </div>
        </section>

        <section id="agents" class="p-4">
            <Table 
                name="Agents"
                :page="agentData.page"
                :perPage="agentData.perPage" 
                :columns="agentData.columns" 
                :total="agentData.searchQuery ? filteredAgents.length : agents.length"
                :search="['UUID', 'Name', 'Role']"
                @update:page="agentData.page = $event"
                @search="agentData.searchQuery = $event"
            >
                
                <template #body>
                    <tr v-for="agent in filteredAgents" :key="agent.uuid" class="divide-x divide-gray-300">
                        <td class="text-left p-1">{{ agent.displayName }}</td>
                        <td class="text-left p-1">{{ agent.role.displayName }}</td>
                        <td class="px-1 text-center align-middle">
                            <img :src="agent.displayIconSmall" :alt="agent.displayName" class="w-8 h-8 inline-block">
                        </td>
                        <td class="text-left p-1 flex items-center justify-center">
                            <form @submit.prevent="toggleAgent(agent)">
                                <input
                                    type="checkbox"
                                    :checked="agent.active"
                                    @change="toggleAgent(agent)"
                                    class="h-4 w-4"
                                >
                            </form>
                        </td>
                        <td class="text-left p-1">{{ agent.updated_at }}</td>
                        <td class="text-left p-1">{{ agent.created_at }}</td>
                        <td class="flex items-center gap-2 justify-center p-1">
                            <Button :color="'danger'" @click="deleteAgent(agent.id)">
                                <template #pre>
                                    <img :src="deleteIcon" alt="delete icon" class="w-4 h-4">
                                </template>
                                {{ t('Delete') }}
                            </Button>
                        </td>
                    </tr>
                </template>
            </Table>
        </section>

        <section id="maps" class="p-4">
            <Table 
                name="Maps"
                :page="mapData.page"
                :perPage="mapData.perPage" 
                :columns="mapData.columns" 
                :total="mapData.searchQuery ? filteredMaps.length : maps.length"
                :search="['UUID', 'Name', 'Gamemode']"
                @update:page="mapData.page = $event"
                @search="mapData.searchQuery = $event"
            >
                
                <template #body>
                    <tr v-for="map in filteredMaps" :key="map.uuid" class="divide-x divide-gray-300">
                        <td class="text-left p-1">{{ map.displayName }}</td>
                        <td class="text-left p-1">
                            <select 
                                v-model="map.gamemode" 
                                class="border rounded p-1" 
                                @change="updateGamemode($event, map)">
                                <option v-for="mode in gamemodes" :key="mode.value" :value="mode.value" class="text-secondary">
                                    {{ mode.label }}
                                </option>
                            </select>
                        </td>
                        <td class="px-1 text-center align-middle">
                            <img :src="map.displayIcon" :alt="map.displayName" class="w-8 h-8 inline-block">
                        </td>
                        <td class="text-left p-1 flex items-center justify-center">
                            <form @submit.prevent="toggleMap(map)">
                                <input
                                    type="checkbox"
                                    :checked="map.active"
                                    @change="toggleMap(map)"
                                    class="h-4 w-4"
                                >
                            </form>
                        </td>
                        <td class="text-left p-1">{{ map.updated_at }}</td>
                        <td class="text-left p-1">{{ map.created_at }}</td>
                        <td class="flex items-center gap-2 justify-center p-1">
                            <Button :color="'danger'" @click="deleteMap(map.id)">
                                <template #pre>
                                    <img :src="deleteIcon" alt="delete icon" class="w-4 h-4">
                                </template>
                                {{ t('Delete') }}
                            </Button>
                        </td>
                    </tr>
                </template>
            </Table>
        </section>
    </div>
</template>

<script setup lang="ts">
import Card from '@/components/Card.vue';
import Button from '@/components/Button.vue';
import Table from '@/components/Table.vue';
import { useI18n } from 'vue-i18n';
import { Agent, Map } from '@/types';

import eyeIcon from '@/assets/icons/eye.svg';
import timerIcon from '@/assets/icons/timer.svg';
import agentIcon from '@/assets/icons/agent.svg';
import mapIcon from '@/assets/icons/map.svg';

import deleteIcon from '@/assets/icons/delete.svg';
import { useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
const { t } = useI18n();

const props = defineProps<{
    agents: Array<Agent>;
    maps: Array<Map>;
}>();

/****** ****** ****** ****** ****** ****** Agents ****** ****** ****** ****** ****** ******/

const agentData = ref({
    page: 1,
    perPage: 10,
    searchQuery: '',
    sortBy: 'displayName',
    sortOrder: 'asc',
    columns: [
        'DisplayName',
        'Role',
        'DisplayIconSmall',
        'Active',
        'Last Updated',
        'Created At',
        'Actions',
    ],
})

const filteredAgents = computed(() => {

    const query = agentData.value.searchQuery.toLowerCase();
    const filtered = props.agents.filter(agent => {
        return agent.displayName.toLowerCase().includes(query) ||
               agent.uuid.toLowerCase().includes(query) ||
               agent.role.displayName.toLowerCase().includes(query);
    });

    if (filtered.length === 0) {
        return [];
    }

    // Sort the filtered agents
    filtered.sort((a: Agent, b: Agent) => {
        const key = agentData.value.sortBy as keyof Agent;
        const aValue = a[key];
        const bValue = b[key];

        if (aValue < bValue) {
            return agentData.value.sortOrder === 'asc' ? -1 : 1;
        } else if (aValue > bValue) {
            return agentData.value.sortOrder === 'asc' ? 1 : -1;
        }
        return 0;
    });

    const start = (agentData.value.page-1) * agentData.value.perPage;
    return filtered.slice(start, start + agentData.value.perPage);
});

function toggleAgent(agent: Agent) {
  const form = useForm({
    active: !agent.active,
  })

  form.patch(route('agent.update', agent.id), {
    preserveScroll: true,
  })
}

function deleteAgent(agentId: number) {
  if (confirm(t('Are you sure you want to delete this agent?'))) {
    const form = useForm({});
    form.delete(route('agent.destroy', agentId), {
      preserveScroll: true,
      onSuccess: () => {
        // Optionally, you can show a success message or perform other actions
      },
    });
  }
}

/****** ****** ****** ****** ****** ****** Maps ****** ****** ****** ****** ****** ******/

const mapData = ref({
    page: 1,
    perPage: 10,
    searchQuery: '',
    sortBy: 'displayName',
    sortOrder: 'asc',
    columns: [
        'DisplayName',
        'Gamemode',
        'DisplayIcon',
        'Active',
        'Last Updated',
        'Created At',
        'Actions',
    ],
})

const gamemodes = ref([
    { value: 'competitive', label: 'Competitive' },
    { value: 'team-deathmatch', label: 'Team-Deathmatch' },
    { value: 'training', label: 'Training' },
]);

const filteredMaps = computed(() => {

    const query = mapData.value.searchQuery.toLowerCase();
    const filtered = props.maps.filter(map => {
        return map.displayName.toLowerCase().includes(query) ||
               map.uuid.toLowerCase().includes(query) ||
               map.gamemode.toLowerCase().includes(query);
    });

    if (filtered.length === 0) {
        return [];
    }

    filtered.sort((a: Map, b: Map) => {
        const key = mapData.value.sortBy as keyof Map;
        const aValue = a[key];
        const bValue = b[key];

        if (aValue < bValue) {
            return mapData.value.sortOrder === 'asc' ? -1 : 1;
        } else if (aValue > bValue) {
            return mapData.value.sortOrder === 'asc' ? 1 : -1;
        }
        return 0;
    });

    const start = (mapData.value.page-1) * mapData.value.perPage;
    return filtered.slice(start, start + mapData.value.perPage);
});


const toggleMap = (map: Map) => {
  const form = useForm({
    active: !map.active,
  })

  form.patch(route('map.update', map.id), {
    preserveScroll: true,
  })
}

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

  form.patch(route('map.update', map.id), {
    preserveScroll: true,
  });
}

function deleteMap(mapId: number) {
  if (confirm(t('Are you sure you want to delete this map?'))) {
    const form = useForm({});
    form.delete(route('map.destroy', mapId), {
      preserveScroll: true,
      onSuccess: () => {
        // Optionally, you can show a success message or perform other actions
      },
    });
  }
}

</script>