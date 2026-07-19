<template>
    <div class="flex items-center gap-3 text-gray-700 dark:text-gray-300">
        <span class="flex items-center gap-1">
            <span class="font-semibold">{{ listing.beds }}</span>
            <span class="text-sm text-gray-500 dark:text-gray-400">beds</span>
        </span>
        <span class="text-gray-300 dark:text-gray-600">·</span>
        <span class="flex items-center gap-1">
            <span class="font-semibold">{{ listing.baths }}</span>
            <span class="text-sm text-gray-500 dark:text-gray-400">baths</span>
        </span>
        <span class="text-gray-300 dark:text-gray-600">·</span>
        <span class="flex items-center gap-1">
            <span class="font-semibold">{{ listing.area }}</span>
            <span class="text-sm text-gray-500 dark:text-gray-400">m²</span>
        </span>
    </div>
</template>

<script setup>
defineProps({
    listing: Object,
});
</script>
