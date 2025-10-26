<template>
    <teleport to="body">
        <div class="fixed top-4 right-4 z-50 flex flex-col gap-3 pointer-events-none">
            <div
                v-for="toast in toasts"
                :key="toast.id"
                class="pointer-events-auto"
            >
                <Toast
                    :message="toast.message"
                    :type="toast.type"
                    :visible="toast.visible"
                    @close="removeToast(toast.id)"
                />
            </div>
        </div>
    </teleport>
</template>

<script setup>
import { useToast } from '@/composables/useToast';
import Toast from '@/Components/Toast.vue';

const { toasts, removeToast } = useToast();
</script>

# cGFuZ29saW4=
