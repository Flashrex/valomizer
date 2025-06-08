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
        class="language-selector hover:bg-card fixed top-4 right-4 z-50 flex cursor-pointer items-center justify-center rounded-full p-2"
        :class="{ 'bg-card': menuVisible }"
        @click="toggleMenu"
    >
        <img class="aspect-square w-6 brightness-75" src="../assets/icons/translate.svg" alt="translate icon" />
    </div>

    <transition name="fade">
        <div
            class="bg-card fixed top-16 right-4 z-50 rounded-md shadow-lg transition-opacity transition-transform duration-500 ease-in-out"
            v-if="menuVisible"
        >
            <ul class="m-0 list-none p-0">
                <li
                    v-for="(language, code) in languages"
                    :key="code"
                    class="cursor:pointer hover:bg-background flex items-center gap-2 p-2"
                    :class="[code === locale ? `font-bold text-valorant` : `bg-card`]"
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

.fade-enter-active,
.fade-leave-active {
    transition:
        opacity 0.5s ease,
        transform 0.5s ease;
}

.fade-enter,
.fade-leave-to {
    opacity: 0;
    transform: translateY(-10px);
}
</style>
