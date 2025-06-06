<script setup lang="ts">
import { onMounted, ref, watch } from 'vue';
import type { Map } from '@/types';
import Errors from '@/components/Error.vue';
import Button from './Button.vue';
import { v4 as uuidv4 } from 'uuid';
import { useI18n } from 'vue-i18n';
const { t } = useI18n();

import selectedImageSrc from '@/assets/icons/selected.png';
import notSelectedImageSrc from '@/assets/icons/not_selected.png';

const props = defineProps({
    maps: {
        type: Array as () => Map[],
        default: () => []
    }
});

const errors = ref<string[]>([]);

const isLoading = ref<boolean>(false);
const mapItems = ref<Map[]>([]);
const spinning = ref<boolean>(false);

const spinButton = ref<HTMLElement>();
const isButtonVisible = ref<boolean>(true);

const selectedImage = ref(new Image());
const notSelectedImage = ref(new Image());

var optionExcludeMaps = ref<boolean>(JSON.parse(localStorage.getItem("optionExcludeMaps") || "false"));
var usedMaps = JSON.parse(localStorage.getItem("usedMaps") || "[]");

const data = ref({
    cardCount: 6,
    width: 220, //px
    containerWidth: 0, //px
    gap: 25, //px
    transitionDuration: 5000, //ms
    maxMoveSpeed: 50, //px
    minMoveSpeed: 2.5, //px
})

/** Initialization */
onMounted(async () => {
    selectedImage.value.src = selectedImageSrc
    notSelectedImage.value.src = notSelectedImageSrc;

    await loadMaps();

    updateDimensions();
    addMapItems();

    window.addEventListener('resize', () => {
        if (spinning.value) return;
        if (updateDimensions()) addMapItems();
    });
})

async function loadMaps() {

    props.maps.forEach(map => {
        map.selected = optionExcludeMaps.value ? !usedMaps.includes(map.displayName) : true;
        map.current = false;
    });
}

function shuffleArray<T>(array: T[]): void {
    let currentIndex = array.length;
    let temporaryValue: T;
    let randomIndex: number;

    // While there remain elements to shuffle...
    while (0 !== currentIndex) {
        // Pick a remaining element...
        randomIndex = Math.floor(Math.random() * currentIndex);
        currentIndex -= 1;

        // And swap it with the current element.
        temporaryValue = array[currentIndex];
        array[currentIndex] = array[randomIndex];
        array[randomIndex] = temporaryValue;
    }
}

const addMapItems = () => {
    if (props.maps.length === 0) return;
    mapItems.value = [];

    const activeMaps = props.maps.filter(map => map.selected);
    let cards = [] as Map[];

    if (activeMaps.length === 0) {
        for (let i = 0; i < data.value.cardCount; i++) {
            cards.push({ ...props.maps[0] });
        }
    } else {
        const randomMaps = [];
        for (let i = 0; i < data.value.cardCount; i++) {
            const randomIndex = Math.floor(Math.random() * activeMaps.length);
            randomMaps.push({ ...activeMaps[randomIndex] });
        }
        cards = randomMaps;
        shuffleArray(cards);
    }

    resetMaps(cards);

    mapItems.value = [...cards];
}

const resetMaps = (maps: Map[]) => {
    if (!maps) return;

    maps.forEach((map, index) => {

        map.current = index === Math.floor((data.value.cardCount-1) / 2);
        map.key = generateKey();
        map.left = data.value.gap * index + (data.value.width * index);

        map.hidden = false;
    });
}

/** Selection */
const onSpin = (e: MouseEvent) => {
    if (!mapItems.value) return;

    if (!props.maps.some(map => map.selected)) {
        errors.value = ['Please select at least one map.'];
        return;
    }

    spinning.value = true;
    errors.value = [];

    isButtonVisible.value = false;

    let ending = false;
    let moveSpeed = data.value.minMoveSpeed;
    let lastTime = 0;
    let finished = false;

    const animate = (timestamp: number) => {
        if (finished) return;
        const deltaTime = timestamp - lastTime;
        const fpsInterval = 1000 / 60; // 60 fps

        if (deltaTime > fpsInterval) {
            lastTime = timestamp - (deltaTime % fpsInterval);

            mapItems.value?.forEach((map, index) => {
                if (update(map, ending, moveSpeed)) finished = true;
            });

            mapItems.value = mapItems.value.filter(map => !map.hidden);

            if (!ending) {
                if (moveSpeed < data.value.maxMoveSpeed) moveSpeed += 0.1;
            } else {
                if (moveSpeed >= data.value.minMoveSpeed) moveSpeed -= 0.1;
            }
        }

        if (!finished || moveSpeed > data.value.minMoveSpeed) {
            requestAnimationFrame(animate);
        } else {
            isButtonVisible.value = true;
            spinning.value = false;
        }
    };

    requestAnimationFrame(animate);

    setTimeout(() => {
        ending = true;
    }, data.value.transitionDuration);
};

const update = (map: Map, ending: boolean, moveSpeed: number): boolean => {
    if (map.hidden || map.left === undefined) return false;

    if (ending) {
        if (map.current && isCentered(map) && moveSpeed <= data.value.minMoveSpeed) {
            if (optionExcludeMaps.value) {
                if (!usedMaps.includes(map.displayName)) {
                    usedMaps.push(map.displayName);
                    localStorage.setItem("usedMaps", JSON.stringify(usedMaps));
                }

                const mapRef = props.maps.find(m => m.displayName === map.displayName);
                if (mapRef) mapRef.selected = false;
            }

            isButtonVisible.value = true;

            const activeMaps = mapItems.value?.filter(m => !m.hidden).sort((a, b) => (a.left ?? 0) - (b.left ?? 0));
            resetMaps(activeMaps);

            return true;
        }
    }
    map.left = map.left ? map.left - moveSpeed : -1000;

    const isInCenter = isInCenterArea(map);

    if (map.current != isInCenter) {
        map.current = isInCenter;
    }

    if (map.left <= -data.value.width) {
        hideMap(map);
        addMap(nextMap());
    }
    return false;
}

const mapSelect = (map: Map) => {
    if (spinning.value) return;
    map.selected = !map.selected;
    if (optionExcludeMaps.value) {
        usedMaps = props.maps.filter(map => !map.selected).map(map => map.displayName);
        localStorage.setItem("usedMaps", JSON.stringify(usedMaps));
    }
}

const isInCenterArea = (map: Map) => {
    if (!map.left) return false;

    const cardMid = map.left + data.value.width / 2;

    const min = (data.value.containerWidth / 2) - (data.value.width / 2);
    const max = (data.value.containerWidth / 2) + (data.value.width / 2);

    return cardMid > min && cardMid < max;
}

const isCentered = (map: Map) => {
    if (!map.left) return false;

    const cardMid = map.left + data.value.width / 2;

    const min = data.value.containerWidth / 2 - 10;
    const max = data.value.containerWidth / 2 + 10;

    return cardMid >= min && cardMid <= max;
}

const highestX = () => {
    const itemWithHighestLeft = mapItems.value.reduce((highest, item) => {
        return ((highest.left ?? 0) > (item.left ?? 0)) ? highest : item;
    });

    return itemWithHighestLeft.left ?? 0;
}

const nextMap = () => {
    const activeMaps = props.maps.filter(map => map.selected);
    return { ...activeMaps[Math.floor(Math.random() * activeMaps.length)] };
}

const hideMap = (map: Map) => {
    if (!mapItems.value) return;

    map.left = -data.value.width;
    map.hidden = true;
}

const addMap = (next: Map) => {
    if (next) {
        next.key = generateKey();
        next.left = highestX() + data.value.width + data.value.gap;
        next.hidden = false;
        mapItems.value = [...mapItems.value, next];
    }
}

const selectAll = () => {
    if (spinning.value) return;
    props.maps.forEach(map => map.selected = true);
    if (optionExcludeMaps.value) {
        usedMaps = [];
        localStorage.setItem("usedMaps", JSON.stringify(usedMaps));
    }
}

const deselectAll = () => {
    if (spinning.value) return;
    props.maps.forEach(map => map.selected = false);
    if (optionExcludeMaps.value) {
        usedMaps = props.maps.map(map => map.displayName);
        localStorage.setItem("usedMaps", JSON.stringify(usedMaps));
    }
}

const generateKey = () => {
    return uuidv4();
}

const updateDimensions = () => {

    let reloadMaps = false;

    const newCardCount = window.innerWidth < 1024 ? 4 : data.value.cardCount;
    if (data.value.cardCount !== newCardCount) {
        data.value.cardCount = newCardCount;
        reloadMaps = true;
    }

    data.value.containerWidth = (data.value.width * (data.value.cardCount-1)) + (data.value.gap * (data.value.cardCount -2 ));

    return reloadMaps;
}

watch(optionExcludeMaps, () => {
    if (optionExcludeMaps.value) {
        usedMaps = props.maps.filter(map => !map.selected).map(map => map.displayName);
    } else {
        usedMaps = [];
    }
    localStorage.setItem("usedMaps", JSON.stringify(usedMaps));
    localStorage.setItem("optionExcludeMaps", JSON.stringify(optionExcludeMaps.value));
})
</script>

<template>
    <div class="w-[90%] relative rounded-md gap-2 p-2 flex flex-col justify-center items-center">
        <div 
            v-if="!isLoading" 
            class="relative m-4 h-[40vh] flex flex-col justify-center items-center overflow-hidden" 
            :style="{ width: `${data.containerWidth}px` }"
        >
            <div class="absolute left-0 top-[5%] h-[90%] transition-transform duration-500 ease-in-out">
                <div 
                    v-for="map in mapItems" 
                    class="absolute flex flex-col justify-center items-center h-full rounded-2xl overflow-hidden transition-transform duration-100 ease-in-out" 
                    :class="map.current ? '[transform:scale(1.05)]' : ''"
                    :style="{ width: `${data.width}px`, left: `${map.left}px` }" :key="map.key ?? map.uuid">
                    <img class="w-full h-full object-cover" :src="map.listViewIconTall">
                    <p class="m-0 absolute text-valorant text-2xl font-bold top-8 [text-shadow:2px_2px_4px_#fd455774]">{{ map.displayName }}</p>
                    <transition name="fade">
                        <Button 
                            v-if="map.current && isButtonVisible" 
                            class="absolute mx-auto bottom-8 z-10 flex justify-center items-center"  
                            ref="spinButton"
                            @click="onSpin"
                            color="valorant"
                        >
                                <span class="mx-4">{{ t('Spin') }}</span>
                        </Button>
                    </transition>
                </div>

            </div>
        </div>


        <div class="w-1/2 m-4 flex flex-col justify-center items-center gap-4">
            <div class="flex flex-wrap justify-center items-center gap-2">
                <div 
                    v-for="map in maps" :key="map.key ?? map.uuid" 
                    class="relative rounded-md px-4 py-0.5 select-none group" 
                    :class="map.selected ? 'bg-valorant' : 'bg-muted'"
                    @click="mapSelect(map)"
                >
                    <p class="text-sm">{{ map.displayName }}</p>
                    <span
                        class="pointer-events-none bg-accent text-white text-center px-2 py-1 rounded-md absolute z-10 bottom-full left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity mb-1 min-w-max">
                        {{ map.selected ? "Click to disable" : "Click to enable" }}
                    </span>
                    <!-- <span class="hidden w-[120px] bg-black text-white text-center p-1 rounded absolute bottom-full left-1/2 -translate-x-1/2 tooltip z-10 transition-opacity duration-300 ease-in-out">
                            {{ map.selected ? "Click to disable" : "Click to enable" }}
                    </span> -->
                </div>
                <Errors v-if="errors" :errors="errors" />
            </div>
            <div class="flex justify-center items-center gap-4">
                <Button color="valorant" :disabled="spinning" @click="selectAll">{{ t('Select All') }}</Button>
                <Button color="valorant" :disabled="spinning" @click="deselectAll">{{ t('De-select All') }}</Button>
            </div>
            <div class="flex cursor-pointer" @click="optionExcludeMaps = !optionExcludeMaps">
                <img v-if="optionExcludeMaps" class="w-6 h-6" :src="selectedImage.src" alt="selected">
                <img v-else class="w-6 h-6" :src="notSelectedImage.src" alt="not_selected">
                <label>{{ t('Exclude rolled map from future rolls') }}</label>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>