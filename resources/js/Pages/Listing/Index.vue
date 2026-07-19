<template>
    <div class="mb-6 flex flex-col gap-1">
        <h1 class="text-2xl font-bold tracking-tight">Browse listings</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">
            Filter by price, size and rooms to find the right home.
        </p>
    </div>

    <Filters :filters="filters" />

    <section
        v-if="listings.data.length"
        class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3"
    >
        <Listing
            v-for="listing in listings.data"
            :key="listing.id"
            :listing="listing"
        />
    </section>

    <EmptyState v-else>
        No listings match these filters.
        <template #action>
            <Link href="/listing" class="btn-outline btn-sm">Clear filters</Link>
        </template>
    </EmptyState>

    <div v-if="listings.data.length" class="my-8 flex w-full justify-center">
        <Pagination :links="listings.links" />
    </div>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import Listing from "@/Pages/Listing/Index/Components/Listing.vue";
import Filters from "@/Pages/Listing/Index/Components/Filters.vue";
import Pagination from "@/components/UI/Pagination.vue";
import EmptyState from "@/components/UI/EmptyState.vue";

defineProps({
    listings: Object,
    filters: Object,
});
</script>

<script>
import MainLayout from "@/Pages/Layouts/MainLayout.vue";

export default {
    layout: MainLayout,
};
</script>
