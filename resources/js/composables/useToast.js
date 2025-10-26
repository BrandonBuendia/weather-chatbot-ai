import { ref } from 'vue';

const toasts = ref([]);
let idCounter = 0;

export function useToast() {
    const addToast = (message, type = 'info', duration = 5000) => {
        const id = idCounter++;
        const toast = {
            id,
            message,
            type,
            visible: true,
        };

        toasts.value.push(toast);

        if (duration > 0) {
            setTimeout(() => {
                removeToast(id);
            }, duration);
        }

        return id;
    };

    const removeToast = (id) => {
        const index = toasts.value.findIndex(t => t.id === id);
        if (index > -1) {
            toasts.value[index].visible = false;
            setTimeout(() => {
                toasts.value.splice(index, 1);
            }, 300);
        }
    };

    const success = (message, duration = 5000) => {
        return addToast(message, 'success', duration);
    };

    const error = (message, duration = 5000) => {
        return addToast(message, 'error', duration);
    };

    const warning = (message, duration = 5000) => {
        return addToast(message, 'warning', duration);
    };

    const info = (message, duration = 5000) => {
        return addToast(message, 'info', duration);
    };

    return {
        toasts,
        addToast,
        removeToast,
        success,
        error,
        warning,
        info,
    };
}

// cGFuZ29saW4=
