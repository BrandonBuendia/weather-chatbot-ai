<template>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center p-4">
        <div class="w-full max-w-4xl bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col" style="height: 90vh;">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold">🌤️ MeteoBot</h1>
                        <p class="text-blue-100 text-sm">Tu asistente meteorológico inteligente</p>
                    </div>
                    <button
                        @click="createNewConversation"
                        class="bg-white/20 hover:bg-white/30 transition px-4 py-2 rounded-lg text-sm font-medium"
                    >
                        + Nueva Conversación
                    </button>
                </div>
            </div>

            <!-- Messages Container -->
            <div ref="messagesContainer" class="flex-1 overflow-y-auto p-6 space-y-4 bg-gray-50">
                <div v-if="conversation.messages.length === 0" class="text-center py-20">
                    <div class="text-6xl mb-4">🌍</div>
                    <h2 class="text-2xl font-semibold text-gray-700 mb-2">
                        ¡Hola! Soy MeteoBot
                    </h2>
                    <p class="text-gray-500">
                        Pregúntame sobre el clima en cualquier ciudad del mundo
                    </p>
                </div>

                <div
                    v-for="message in conversation.messages"
                    :key="message.id"
                    :class="[
                        'flex',
                        message.role === 'user' ? 'justify-end' : 'justify-start'
                    ]"
                >
                    <div
                        :class="[
                            'max-w-xl rounded-2xl px-5 py-3 shadow-md',
                            message.role === 'user'
                                ? 'bg-blue-600 text-white rounded-br-none'
                                : 'bg-white text-gray-800 rounded-bl-none border border-gray-200'
                        ]"
                    >
                        <div v-html="formatMessage(message.content)" class="prose prose-sm max-w-none"></div>
                        <div
                            :class="[
                                'text-xs mt-2',
                                message.role === 'user' ? 'text-blue-100' : 'text-gray-400'
                            ]"
                        >
                            {{ formatTime(message.created_at) }}
                        </div>
                    </div>
                </div>

                <!-- Loading indicator -->
                <div v-if="loading" class="flex justify-start">
                    <div class="bg-white rounded-2xl rounded-bl-none px-5 py-3 shadow-md border border-gray-200">
                        <div class="flex space-x-2">
                            <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0ms"></div>
                            <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 150ms"></div>
                            <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 300ms"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Input Area -->
            <div class="border-t border-gray-200 bg-white p-4">
                <form @submit.prevent="sendMessage" class="flex space-x-3">
                    <input
                        v-model="messageInput"
                        type="text"
                        :disabled="loading"
                        placeholder="Pregunta sobre el clima en cualquier ciudad..."
                        class="flex-1 rounded-full border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 px-6 py-3 disabled:bg-gray-100 disabled:cursor-not-allowed"
                    />
                    <button
                        type="submit"
                        :disabled="!messageInput.trim() || loading"
                        class="bg-blue-600 hover:bg-blue-700 text-white rounded-full px-8 py-3 font-semibold transition disabled:bg-gray-300 disabled:cursor-not-allowed flex items-center space-x-2"
                    >
                        <span>Enviar</span>
                        <span>📤</span>
                    </button>
                </form>
            </div>

            <!-- Error notification -->
            <div
                v-if="error"
                class="absolute top-20 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg animate-pulse"
            >
                {{ error }}
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, nextTick, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import axios from 'axios';

const props = defineProps({
    conversation: {
        type: Object,
        required: true
    }
});

const messageInput = ref('');
const loading = ref(false);
const error = ref('');
const messagesContainer = ref(null);

const sendMessage = async () => {
    if (!messageInput.value.trim() || loading.value) return;

    const content = messageInput.value;
    messageInput.value = '';
    loading.value = true;
    error.value = '';

    try {
        const response = await axios.post(
            route('chat.messages.store', props.conversation.id),
            { content }
        );

        if (response.data.success) {
            props.conversation.messages = response.data.conversation.messages;
            await nextTick();
            scrollToBottom();
        }
    } catch (err) {
        error.value = 'Error al enviar el mensaje. Por favor, intenta nuevamente.';
        messageInput.value = content;
        setTimeout(() => error.value = '', 5000);
    } finally {
        loading.value = false;
    }
};

const createNewConversation = () => {
    router.post(route('chat.store'));
};

const formatMessage = (content) => {
    let formatted = content
        .replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>')
        .replace(/\*(.+?)\*/g, '<em>$1</em>')
        .replace(/\n/g, '<br>');

    return formatted;
};

const formatTime = (datetime) => {
    const date = new Date(datetime);
    return date.toLocaleTimeString('es-ES', {
        hour: '2-digit',
        minute: '2-digit'
    });
};

const scrollToBottom = () => {
    if (messagesContainer.value) {
        messagesContainer.value.scrollTop = messagesContainer.value.scrollHeight;
    }
};

onMounted(() => {
    scrollToBottom();
});

watch(() => props.conversation.messages.length, () => {
    nextTick(() => scrollToBottom());
});
</script>

# cGFuZ29saW4=
