<template>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-gray-900 dark:to-slate-800 flex items-center justify-center p-4 transition-colors duration-300">
        <div class="w-full max-w-2xl">
            <div class="absolute top-6 right-6">
                <ThemeToggle />
            </div>

            <div class="text-center mb-8">
                <h1 class="text-5xl font-bold text-gray-800 dark:text-gray-100 mb-3">🌤️ MeteoBot</h1>
                <p class="text-xl text-gray-600 dark:text-gray-300">Tu asistente meteorológico con inteligencia artificial</p>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl dark:shadow-gray-900/50 p-8 transition-colors duration-300">
                <button
                    @click="createConversation"
                    class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 dark:from-indigo-600 dark:to-purple-700 hover:from-blue-700 hover:to-indigo-700 dark:hover:from-indigo-700 dark:hover:to-purple-800 text-white font-bold py-4 px-6 rounded-xl transition-all transform hover:scale-105 text-lg"
                >
                    ✨ Iniciar Nueva Conversación
                </button>

                <div v-if="conversationsList.length > 0" class="mt-8">
                    <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4">Conversaciones Recientes</h2>
                    <div class="space-y-3">
                        <a
                            v-for="conv in conversationsList"
                            :key="conv.id"
                            :href="route('chat.show', conv.id)"
                            class="block bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 p-4 rounded-lg transition-all border border-gray-200 dark:border-gray-600"
                        >
                            <div class="font-medium text-gray-800 dark:text-gray-100">{{ conv.title }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                {{ formatDate(conv.created_at) }}
                            </div>
                        </a>
                    </div>

                    <!-- Pagination -->
                    <Pagination v-if="conversations.links" :links="conversations.links" />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3';
import { computed } from 'vue';
import ThemeToggle from '@/Components/ThemeToggle.vue';
import Pagination from '@/Components/Pagination.vue';

const props = defineProps({
    conversations: {
        type: Object,
        default: () => ({ data: [], links: [] })
    }
});

const conversationsList = computed(() => props.conversations.data || props.conversations);

const createConversation = () => {
    router.post(route('chat.store'));
};

const formatDate = (datetime) => {
    const date = new Date(datetime);
    return date.toLocaleDateString('es-ES', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    });
};
</script>

# cGFuZ29saW4=
