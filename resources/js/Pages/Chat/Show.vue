<template>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-gray-900 dark:to-slate-800 flex items-center justify-center p-4 transition-colors duration-300">
        <div class="w-full max-w-4xl bg-white dark:bg-gray-800 rounded-2xl shadow-2xl dark:shadow-gray-900/50 overflow-hidden flex flex-col" style="height: 90vh;">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 dark:from-indigo-700 dark:to-purple-800 text-white px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <h1 class="text-2xl font-bold">🌤️ MeteoBot</h1>
                        <p class="text-blue-100 dark:text-indigo-200 text-sm">Tu asistente meteorológico inteligente</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <ThemeToggle />
                        <button
                            @click="createNewConversation"
                            class="bg-white/20 hover:bg-white/30 dark:bg-white/10 dark:hover:bg-white/20 transition px-4 py-2 rounded-lg text-sm font-medium"
                        >
                            + Nueva Conversación
                        </button>
                    </div>
                </div>
            </div>

            <!-- Messages Container -->
            <div ref="messagesContainer" class="flex-1 overflow-y-auto p-6 space-y-4 bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
                <div v-if="conversation.messages.length === 0" class="text-center py-20">
                    <div class="text-6xl mb-4">🌍</div>
                    <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200 mb-2">
                        ¡Hola! Soy MeteoBot
                    </h2>
                    <p class="text-gray-500 dark:text-gray-400">
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
                            'max-w-xl rounded-2xl px-5 py-3 shadow-md transition-all duration-200',
                            message.role === 'user'
                                ? 'bg-blue-600 dark:bg-indigo-600 text-white rounded-br-none'
                                : 'bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 rounded-bl-none border border-gray-200 dark:border-gray-700'
                        ]"
                    >
                        <div v-html="formatMessage(message.content)" class="prose prose-sm dark:prose-invert max-w-none"></div>
                        <div
                            :class="[
                                'text-xs mt-2',
                                message.role === 'user' ? 'text-blue-100 dark:text-indigo-200' : 'text-gray-400 dark:text-gray-500'
                            ]"
                        >
                            {{ formatTime(message.created_at) }}
                        </div>
                    </div>
                </div>

                <!-- Typing indicator -->
                <div v-if="loading" class="flex justify-start">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl rounded-bl-none px-5 py-4 shadow-md border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center space-x-3">
                            <div class="flex space-x-1.5">
                                <div class="w-2.5 h-2.5 bg-blue-500 dark:bg-indigo-500 rounded-full animate-bounce" style="animation-delay: 0ms"></div>
                                <div class="w-2.5 h-2.5 bg-blue-500 dark:bg-indigo-500 rounded-full animate-bounce" style="animation-delay: 150ms"></div>
                                <div class="w-2.5 h-2.5 bg-blue-500 dark:bg-indigo-500 rounded-full animate-bounce" style="animation-delay: 300ms"></div>
                            </div>
                            <span class="text-sm text-gray-500 dark:text-gray-400 italic">MeteoBot está escribiendo...</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Input Area -->
            <div class="border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-4 transition-colors duration-300">
                <form @submit.prevent="sendMessage" class="flex space-x-3">
                    <input
                        v-model="messageInput"
                        type="text"
                        :disabled="loading"
                        placeholder="Pregunta sobre el clima en cualquier ciudad..."
                        class="flex-1 rounded-full border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:border-blue-500 dark:focus:border-indigo-500 focus:ring focus:ring-blue-200 dark:focus:ring-indigo-900 focus:ring-opacity-50 px-6 py-3 disabled:bg-gray-100 dark:disabled:bg-gray-800 disabled:cursor-not-allowed transition-colors"
                    />
                    <button
                        type="submit"
                        :disabled="!messageInput.trim() || loading"
                        class="bg-blue-600 hover:bg-blue-700 dark:bg-indigo-600 dark:hover:bg-indigo-700 text-white rounded-full px-6 py-3 font-semibold transition-all disabled:bg-gray-300 dark:disabled:bg-gray-700 disabled:cursor-not-allowed flex items-center justify-center w-32"
                    >
                        <span v-if="!loading">Enviar</span>
                        <PaperAirplaneIcon v-if="!loading" class="h-5 w-5 ms-2" />
                        <span v-if="loading">Enviando...</span>
                    </button>
                </form>
            </div>

        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, nextTick, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import axios from 'axios';
import ThemeToggle from '@/Components/ThemeToggle.vue';
import { PaperAirplaneIcon } from '@heroicons/vue/24/solid';
import { useToast } from '@/composables/useToast';

const props = defineProps({
    conversation: {
        type: Object,
        required: true
    }
});

const { success, error: showError } = useToast();
const messageInput = ref('');
const loading = ref(false);
const messagesContainer = ref(null);

const sendMessage = async () => {
    if (!messageInput.value.trim() || loading.value) return;

    const content = messageInput.value;
    messageInput.value = '';
    loading.value = true;

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
        showError('Error al enviar el mensaje. Por favor, intenta nuevamente.');
        messageInput.value = content;
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
