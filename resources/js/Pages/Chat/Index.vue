<template>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center p-4">
        <div class="w-full max-w-2xl">
            <div class="text-center mb-8">
                <h1 class="text-5xl font-bold text-gray-800 mb-3">🌤️ MeteoBot</h1>
                <p class="text-xl text-gray-600">Tu asistente meteorológico con inteligencia artificial</p>
            </div>

            <div class="bg-white rounded-2xl shadow-2xl p-8">
                <button
                    @click="createConversation"
                    class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold py-4 px-6 rounded-xl transition transform hover:scale-105 text-lg"
                >
                    ✨ Iniciar Nueva Conversación
                </button>

                <div v-if="conversations.length > 0" class="mt-8">
                    <h2 class="text-lg font-semibold text-gray-700 mb-4">Conversaciones Recientes</h2>
                    <div class="space-y-3">
                        <a
                            v-for="conv in conversations"
                            :key="conv.id"
                            :href="route('chat.show', conv.id)"
                            class="block bg-gray-50 hover:bg-gray-100 p-4 rounded-lg transition border border-gray-200"
                        >
                            <div class="font-medium text-gray-800">{{ conv.title }}</div>
                            <div class="text-sm text-gray-500 mt-1">
                                {{ formatDate(conv.created_at) }}
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3';

defineProps({
    conversations: {
        type: Array,
        default: () => []
    }
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
