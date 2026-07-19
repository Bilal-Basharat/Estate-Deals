<template>
    <div class="flex items-center gap-1.5 text-gray-500 dark:text-gray-400">
        <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
        </svg>
        <span class="truncate">{{ addressLine }}</span>
    </div>
</template>

<script setup>
import { computed } from "vue";

const props = defineProps({
    listing: Object,
});

const addressLine = computed(() =>
    [
        [props.listing.street, props.listing.street_nr].filter(Boolean).join(" "),
        props.listing.city,
        props.listing.state,
    ]
        .filter(Boolean)
        .join(", ")
);
</script>
