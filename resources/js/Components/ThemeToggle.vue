<template>
    <button
        @click="handleToggle"
        class="relative w-14 h-14 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 dark:from-indigo-600 dark:to-purple-800 shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-110 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:focus:ring-purple-500"
        aria-label="Toggle theme"
    >
        <div class="absolute inset-0 flex items-center justify-center">
            <transition
                enter-active-class="transition-all duration-500 ease-out"
                enter-from-class="opacity-0 scale-0 rotate-180"
                enter-to-class="opacity-100 scale-100 rotate-0"
                leave-active-class="transition-all duration-500 ease-in"
                leave-from-class="opacity-100 scale-100 rotate-0"
                leave-to-class="opacity-0 scale-0 -rotate-180"
            >
                <SunIcon
                    v-if="!isDark"
                    key="sun"
                    class="w-7 h-7 text-yellow-300"
                />
                <MoonIcon
                    v-else
                    key="moon"
                    class="w-7 h-7 text-gray-100"
                />
            </transition>
        </div>

        <!-- Efecto de ripple -->
        <span class="absolute inset-0 rounded-full bg-white dark:bg-gray-800 opacity-0 group-hover:opacity-20 transition-opacity"></span>
    </button>
</template>

<script setup>
import { useTheme } from '@/composables/useTheme';
import { SunIcon, MoonIcon } from '@heroicons/vue/24/solid';

const { isDark, toggleTheme } = useTheme();

const handleToggle = (event) => {
    toggleTheme(event);
};
</script>

<style scoped>
@keyframes pulse-ring {
    0% {
        transform: scale(0.95);
        opacity: 0.7;
    }
    50% {
        transform: scale(1);
        opacity: 0.5;
    }
    100% {
        transform: scale(0.95);
        opacity: 0.7;
    }
}

button:hover::before {
    animation: pulse-ring 1.5s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}
</style>
# cGFuZ29saW4=