import { readonly, ref } from 'vue';

type NotificationType = 'info' | 'warning' | 'error';

interface Notification {
    id: number;
    message: string;
    type: NotificationType;
}

const notifications = ref<Notification[]>([]);
let id = 0;

function showNotification(message: string, type: NotificationType = 'info', timeout = 3000) {
    const notification = { id: ++id, message, type };
    notifications.value.push(notification);
    setTimeout(() => {
        notifications.value = notifications.value.filter((t) => t.id !== notification.id);
    }, timeout);
}

export function useNotifications() {
    return {
        notifications: readonly(notifications),
        info: (msg: string, timeout?: number) => showNotification(msg, 'info', timeout),
        warning: (msg: string, timeout?: number) => showNotification(msg, 'warning', timeout),
        error: (msg: string, timeout?: number) => showNotification(msg, 'error', timeout),
        dismiss: (id: number) => {
            notifications.value = notifications.value.filter((notification) => notification.id !== id);
        },
    };
}
