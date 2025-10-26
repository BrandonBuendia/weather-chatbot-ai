<template>
    <div v-if="links.length > 3" class="flex items-center justify-center gap-1 mt-6">
        <template v-for="(link, index) in links" :key="index">
            <component
                :is="link.url ? 'Link' : 'span'"
                :href="link.url"
                preserve-scroll
                :class="[
                    'px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200',
                    link.active
                        ? 'bg-blue-600 dark:bg-indigo-600 text-white shadow-md'
                        : link.url
                        ? 'bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600 border border-gray-300 dark:border-gray-600'
                        : 'bg-gray-100 dark:bg-gray-800 text-gray-400 dark:text-gray-500 cursor-not-allowed',
                ]"
                v-html="link.label"
            />
        </template>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    links: {
        type: Array,
        required: true,
    },
});
</script>

# cGFuZ29saW4=
