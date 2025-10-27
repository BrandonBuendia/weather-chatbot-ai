<template>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-gray-900 dark:to-slate-800 p-4 md:p-8 transition-colors duration-300">
        <div class="absolute top-6 right-6 z-10">
            <ThemeToggle />
        </div>

        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-12">
                <h1 class="text-6xl font-bold text-gray-800 dark:text-gray-100 mb-4 animate-fade-in">
                    🌤️ MeteoBot
                </h1>
                <p class="text-xl text-gray-600 dark:text-gray-300 animate-fade-in-delay">
                    Tu asistente meteorológico con inteligencia artificial
                </p>
            </div>

            <!-- New Conversation Button -->
            <div class="mb-12 flex justify-center">
                <button
                    @click="createConversation"
                    class="group relative bg-gradient-to-r from-blue-600 to-indigo-600 dark:from-indigo-600 dark:to-purple-700 hover:from-blue-700 hover:to-indigo-700 dark:hover:from-indigo-700 dark:hover:to-purple-800 text-white font-bold py-4 px-8 rounded-2xl transition-all transform hover:scale-105 hover:shadow-2xl text-lg flex items-center gap-3"
                >
                    <span class="text-2xl group-hover:rotate-180 transition-transform duration-500">✨</span>
                    <span>Iniciar Nueva Conversación</span>
                    <svg class="w-6 h-6 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </button>
            </div>

            <!-- Conversations Grid -->
            <div v-if="conversationsList.length > 0" class="space-y-8">
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 flex items-center gap-3">
                        <svg class="w-8 h-8 text-blue-600 dark:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                        </svg>
                        Conversaciones Recientes
                    </h2>
                    <div class="flex gap-2 items-center">
                        <span class="text-sm text-gray-500 dark:text-gray-400 bg-gray-200 dark:bg-gray-700 px-4 py-2 rounded-full">
                            Mostrando {{ conversationsList.length }} de {{ conversations.total || conversationsList.length }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <a
                        v-for="(conv, index) in conversationsList"
                        :key="conv.id"
                        :href="route('chat.show', conv.id)"
                        :style="{ animationDelay: `${index * 50}ms` }"
                        class="group conversation-card bg-white dark:bg-gray-800 rounded-2xl shadow-lg dark:shadow-gray-900/50 overflow-hidden hover:shadow-2xl dark:hover:shadow-indigo-500/20 transition-all duration-300 transform hover:-translate-y-2 border border-gray-100 dark:border-gray-700 hover:border-blue-300 dark:hover:border-indigo-500"
                    >
                        <!-- Card Header -->
                        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 dark:from-indigo-600 dark:to-purple-700 p-4">
                            <div class="flex items-start justify-between">
                                <div class="flex items-center gap-3 flex-1 min-w-0">
                                    <div class="bg-white/20 backdrop-blur-sm p-2.5 rounded-xl group-hover:scale-110 transition-transform">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                        </svg>
                                    </div>
                                    <h3 class="font-bold text-white truncate flex-1 text-lg">
                                        {{ conv.title }}
                                    </h3>
                                </div>
                                <svg class="w-5 h-5 text-white/60 group-hover:text-white group-hover:translate-x-1 transition-all flex-shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-5 space-y-4">
                            <!-- Last Message Preview -->
                            <div v-if="conv.messages && conv.messages.length > 0" class="space-y-2">
                                <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span>Último mensaje</span>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-300 line-clamp-2 leading-relaxed">
                                    {{ conv.messages[0].content }}
                                </p>
                            </div>
                            <div v-else class="text-sm text-gray-400 dark:text-gray-500 italic">
                                Sin mensajes aún
                            </div>

                            <!-- Footer -->
                            <div class="flex items-center justify-between pt-3 border-t border-gray-100 dark:border-gray-700">
                                <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ formatDate(conv.created_at) }}
                                </div>
                                <div class="flex items-center gap-1.5 text-xs font-medium text-blue-600 dark:text-indigo-400 group-hover:gap-2 transition-all">
                                    <span>Abrir</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Pagination -->
                <Pagination
                    v-if="conversations && conversations.links && conversations.links.length > 0"
                    :links="conversations.links"
                />
            </div>

            <!-- Empty State -->
            <div v-else class="text-center py-20">
                <div class="inline-block p-8 bg-white dark:bg-gray-800 rounded-3xl shadow-xl dark:shadow-gray-900/50 animate-bounce-slow">
                    <div class="text-8xl mb-4">💬</div>
                    <h3 class="text-2xl font-bold text-gray-700 dark:text-gray-200 mb-2">
                        No hay conversaciones aún
                    </h3>
                    <p class="text-gray-500 dark:text-gray-400">
                        ¡Inicia tu primera conversación sobre el clima!
                    </p>
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

const conversationsList = computed(() => {
    // Laravel pagination returns an object with 'data' property
    // If conversations has 'data', it's a paginated response
    if (props.conversations && Array.isArray(props.conversations.data)) {
        return props.conversations.data;
    }
    // Otherwise, it's a direct array (shouldn't happen with pagination)
    return Array.isArray(props.conversations) ? props.conversations : [];
});

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
