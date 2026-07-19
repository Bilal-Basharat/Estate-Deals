<template>
    <nav class="flex flex-wrap items-center gap-1" aria-label="Pagination">
        <component
            :is="link.url ? Link : 'span'"
            v-for="(link, index) in links"
            :key="index"
            :href="link.url"
            class="min-w-9 rounded-lg px-3 py-2 text-center text-sm font-medium transition-colors"
            :class="linkClasses(link)"
            v-html="link.label"
        />
    </nav>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";

defineProps({
    links: Array,
});

const linkClasses = (link) => {
    if (link.active) {
        return "bg-brand-600 text-white";
    }
    if (!link.url) {
        return "cursor-default text-gray-400 dark:text-gray-600";
    }
    return "text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800";
};
</script>
