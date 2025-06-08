<template>
    <div class="fixed top-4 right-4 z-50 flex flex-col">
        <TransitionGroup name="slide" tag="div">
            <div
                v-for="notification in notifications"
                :key="notification.id"
                class="text-primary bg-accent relative mt-2 rounded-lg px-8 py-4 shadow"
            >
                <div
                    class="absolute top-0 left-0 h-full w-4 rounded-l"
                    :class="[
                        notification.type === 'info' && 'bg-blue-500',
                        notification.type === 'warning' && 'bg-yellow-500',
                        notification.type === 'error' && 'bg-red-500',
                    ]"
                ></div>
                <button
                    class="text-primary absolute top-0.5 right-2 cursor-pointer hover:text-gray-300 focus:outline-none"
                    @click="dismiss(notification.id)"
                >
                    &times;
                </button>
                <div class="ml-4 flex items-center justify-center gap-4">
                    <img :src="icon(notification.type)" alt="nofiction icon" class="h-10 w-10" />
                    <div class="flex flex-col items-start justify-center gap-2">
                        <h3 class="font-bold">{{ notification.type.charAt(0).toUpperCase() + notification.type.slice(1) }}</h3>
                        <p class="text-sm">{{ notification.message }}</p>
                    </div>
                </div>
            </div>
        </TransitionGroup>
    </div>
</template>

<script setup lang="ts">
import { useNotifications } from '@/composables/useNotification';
const { notifications, dismiss } = useNotifications();

import errorIcon from '@/assets/icons/dangerous.svg';
import infoIcon from '@/assets/icons/info.svg';
import successIcon from '@/assets/icons/verified.svg';
import warningIcon from '@/assets/icons/warning.svg';

const icon = (type: string) => {
    switch (type) {
        case 'error':
            return errorIcon;
        case 'info':
            return infoIcon;
        case 'warning':
            return warningIcon;
        case 'success':
            return successIcon;
        default:
            return '';
    }
};
</script>

<style scoped>
.slide-enter-from,
.slide-leave-to {
    transform: translateX(120%);
    opacity: 0;
}
.slide-enter-active,
.slide-leave-active {
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}
.slide-enter-to,
.slide-leave-from {
    transform: translateX(0);
    opacity: 1;
}
</style>
