<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { useI18n } from 'vue-i18n';

const { locale } = useI18n();

const menuVisible = ref(false);

const languages = ref({
    us: 'English',
    de: 'Deutsch',
    fr: 'Français',
    es: 'Español',
    it: 'Italiano',
    fi: 'Suomi',
});

function selectLanguage(code: string) {
    locale.value = code;

    localStorage.setItem('locale', code);
}

function toggleMenu() {
    menuVisible.value = !menuVisible.value;
}

onMounted(() => {
    const storedLocale = localStorage.getItem('locale');

    if (storedLocale) {
        locale.value = storedLocale;
    }

    document.addEventListener('click', (event) => {
        const target = event.target as HTMLElement;
        if (!target.closest('.select-menu') && !target.closest('.language-selector')) {
            menuVisible.value = false;
        }
    });
});

</script>

<template>
    <div
        class="language-selector fixed top-4 right-4 z-50 flex justify-center items-center cursor-pointer rounded-full p-2 hover:bg-card"
        :class="{ 'bg-card': menuVisible }"
        @click="toggleMenu"
    >
        <img class="w-6 aspect-square brightness-75" src="../assets/icons/translate.svg" alt="translate icon">
    </div>

    <transition name="fade">
        <div class="fixed top-16 right-4 z-50 bg-card rounded-md shadow-lg transition-opacity transition-transform duration-500 ease-in-out" v-if="menuVisible">
            <ul class="list-none p-0 m-0">
                <li 
                    v-for="(language, code) in languages" 
                    :key="code" 
                    class="p-2 cursor:pointer hover:bg-muted flex items-center gap-2"
                    :class="[code === locale ? `selected` : ``]"
                    @click="selectLanguage(code)"
                >
                    <span class="fi" :class="`fi-${code}`"></span> 
                    <span>{{ language }}</span>
                </li>
            </ul>
        </div>
    </transition>
</template>

<style scoped>


.active {
    background-color: var(--color-background-soft);
}

.selected {
    background-color: var(--color-background);
}

.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s ease, transform 0.5s ease;
}

.fade-enter,
.fade-leave-to {
    opacity: 0;
    transform: translateY(-10px);
}
</style>