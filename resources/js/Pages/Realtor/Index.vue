<template>
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold tracking-tight">Dashboard</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Your listings, offers, and sales at a glance.
            </p>
        </div>
        <Link href="/realtor/listing/create" class="btn-primary">+ New listing</Link>
    </div>

    <!-- Stats -->
    <section class="mb-6 grid grid-cols-2 gap-3 lg:grid-cols-4">
        <div class="card p-4">
            <div class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                Active listings
            </div>
            <div class="mt-1 text-2xl font-bold">{{ stats.activeListings }}</div>
        </div>

        <div class="card p-4" :class="{ 'ring-2 ring-brand-500/40': stats.pendingOffers }">
            <div class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                Pending offers
            </div>
            <div class="mt-1 text-2xl font-bold" :class="{ 'text-brand-600': stats.pendingOffers }">
                {{ stats.pendingOffers }}
            </div>
        </div>

        <div class="card p-4">
            <div class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                Sold
            </div>
            <div class="mt-1 text-2xl font-bold">{{ stats.soldListings }}</div>
        </div>

        <div class="card p-4">
            <div class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                Total sold value
            </div>
            <div class="mt-1 text-2xl font-bold">
                <Price :price="stats.soldValue" />
            </div>
        </div>
    </section>

    <RealtorFilter :filters="filters" class="mb-6" />

    <section v-if="listings.data.length" class="flex flex-col gap-4">
        <Listing
            v-for="listing in listings.data"
            :key="listing.id"
            :listing="listing"
        />
    </section>

    <EmptyState v-else>
        You haven't created any listings yet.
        <template #action>
            <Link href="/realtor/listing/create" class="btn-primary btn-sm">Create your first listing</Link>
        </template>
    </EmptyState>

    <div v-if="listings.data.length" class="my-8 flex justify-center">
        <Pagination :links="listings.links" />
    </div>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import Listing from "@/Pages/Realtor/Index/Components/Listing.vue";
import RealtorFilter from "@/Pages/Realtor/Index/Components/RealtorFilter.vue";
import Pagination from "@/components/UI/Pagination.vue";
import EmptyState from "@/components/UI/EmptyState.vue";
import Price from "@/components/Price.vue";

defineProps({
    listings: Object,
    filters: Object,
    stats: Object,
});
</script>

<script>
import MainLayout from "@/Pages/Layouts/MainLayout.vue";

export default {
    layout: MainLayout,
};
</script>
