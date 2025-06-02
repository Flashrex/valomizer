<template>
    <Head title="About" />
    <div class="text-primary flex h-full w-full flex-col items-center justify-center gap-8">
        <section class="flex flex-col items-center justify-center p-4">
            <h1 class="text-2xl font-bold">{{ t('About this website') }}</h1>
            <p class="text-sm">{{ t('This website is a tool for finding a random map or agent to play with.') }}</p>
            <br />
            <p class="text-sm">
                {{ t('Data utilized here is provided by') }}
                <a class="link text-valorant" href="https://valorant-api.com" target="_blank">valorant-api.com</a>.
            </p>
        </section>

        <section class="flex flex-col items-center justify-center p-4">
            <h1 class="text-2xl font-bold">{{ t('Buy me a coffee') }}</h1>
            <p class="text-sm">{{ t('If you like this project and want to support me, consider buying me a coffee.') }}</p>
            <a href="https://ko-fi.com/I2I115AQ06" target="_blank">
                <img
                    height="36"
                    style="margin: 1rem; border: 0px; height: 36px"
                    src="https://storage.ko-fi.com/cdn/kofi4.png?v=6"
                    border="0"
                    alt="Buy Me a Coffee at ko-fi.com"
                />
            </a>
        </section>

        <section class="flex flex-col items-center justify-center p-4">
            <h1 class="text-2xl font-bold">{{ t('Contribute') }}</h1>
            <p class="text-sm">
                {{ t('If you want to contribute to this project, you can find the source code on') }}
                <a class="link" href="https://github.com/Flashrex/valorant_randomizer" target="_blank" rel="noopener noreferrer">GitHub</a>.
            </p>
        </section>

        <section class="flex flex-col items-center justify-center p-4">
            <h1 class="text-2xl font-bold">{{ t('Disclaimer') }}</h1>
            <p class="text-sm">{{ t('Valorant is a registered trademark of Riot Games, Inc.') }}</p>
            <p class="text-sm">{{ t('Riot Games, Inc. does not endorse or sponsor this project.') }}</p>
        </section>

        <section class="flex flex-col items-center justify-center p-4">
            <h1 class="text-2xl font-bold">{{ t('Privacy Policy') }}</h1>
            <p class="text-sm">{{ t('This website does not collect any personal data.') }}</p>
        </section>

        <section class="flex flex-col items-center justify-center p-4">
            <h1 class="text-2xl font-bold">{{ t('Contact') }}</h1>
            <p class="text-sm">
                {{ t('If you have any questions or suggestions, feel free to contact me at') }}
                <a class="link text-valorant" href="mailto:flashrexwastaken@gmail.com"> flashrexwastaken@gmail.com </a>
            </p>
        </section>
    </div>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

defineOptions({
    layout: AppLayout,
});
</script>
