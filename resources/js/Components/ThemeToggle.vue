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
                <svg
                    v-if="!isDark"
                    key="sun"
                    class="w-7 h-7 text-yellow-300"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                >
                    <path
                        fill-rule="evenodd"
                        d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                        clip-rule="evenodd"
                    />
                </svg>
                <svg
                    v-else
                    key="moon"
                    class="w-7 h-7 text-gray-100"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                >
                    <path
                        d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"
                    />
                </svg>
            </transition>
        </div>

        <!-- Efecto de ripple -->
        <span class="absolute inset-0 rounded-full bg-white dark:bg-gray-800 opacity-0 group-hover:opacity-20 transition-opacity"></span>
    </button>
</template>

<script setup>
import { useTheme } from '@/composables/useTheme';

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
