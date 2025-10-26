<template>
    <transition
        enter-active-class="transition ease-out duration-300"
        enter-from-class="translate-x-full opacity-0"
        enter-to-class="translate-x-0 opacity-100"
        leave-active-class="transition ease-in duration-200"
        leave-from-class="translate-x-0 opacity-100"
        leave-to-class="translate-x-full opacity-0"
    >
        <div
            v-if="visible"
            :class="[
                'flex items-start gap-3 p-4 rounded-xl shadow-lg border backdrop-blur-sm max-w-md',
                toastClasses
            ]"
        >
            <div class="flex-shrink-0 mt-0.5">
                <component :is="icon" class="w-6 h-6" />
            </div>
            <div class="flex-1 min-w-0">
                <p :class="['text-sm font-medium', textClasses]">{{ message }}</p>
            </div>
            <button
                @click="$emit('close')"
                class="flex-shrink-0 rounded-lg p-1.5 inline-flex items-center justify-center transition-colors hover:bg-white/20 dark:hover:bg-black/20"
            >
                <XMarkIcon class="w-5 h-5" />
            </button>
        </div>
    </transition>
</template>

<script setup>
import { computed } from 'vue';
import {
    CheckCircleIcon,
    XCircleIcon,
    ExclamationTriangleIcon,
    InformationCircleIcon,
    XMarkIcon
} from '@heroicons/vue/24/solid';

const props = defineProps({
    message: {
        type: String,
        required: true,
    },
    type: {
        type: String,
        default: 'info',
        validator: (value) => ['success', 'error', 'warning', 'info'].includes(value),
    },
    visible: {
        type: Boolean,
        default: true,
    },
});

defineEmits(['close']);

const icon = computed(() => {
    const icons = {
        success: CheckCircleIcon,
        error: XCircleIcon,
        warning: ExclamationTriangleIcon,
        info: InformationCircleIcon,
    };
    return icons[props.type];
});

const toastClasses = computed(() => {
    const classes = {
        success: 'bg-green-50/95 dark:bg-green-900/95 border-green-200 dark:border-green-700 text-green-800 dark:text-green-100',
        error: 'bg-red-50/95 dark:bg-red-900/95 border-red-200 dark:border-red-700 text-red-800 dark:text-red-100',
        warning: 'bg-yellow-50/95 dark:bg-yellow-900/95 border-yellow-200 dark:border-yellow-700 text-yellow-800 dark:text-yellow-100',
        info: 'bg-blue-50/95 dark:bg-blue-900/95 border-blue-200 dark:border-blue-700 text-blue-800 dark:text-blue-100',
    };
    return classes[props.type];
});

const textClasses = computed(() => {
    const classes = {
        success: 'text-green-900 dark:text-green-50',
        error: 'text-red-900 dark:text-red-50',
        warning: 'text-yellow-900 dark:text-yellow-50',
        info: 'text-blue-900 dark:text-blue-50',
    };
    return classes[props.type];
});
</script>

# cGFuZ29saW4=
