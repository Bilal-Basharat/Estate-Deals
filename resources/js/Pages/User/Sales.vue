<template>
    <Head title="My sales" />

    <div class="mb-6">
        <h1 class="text-2xl font-bold tracking-tight">
            My sales
            <span v-if="sales.total" class="text-gray-400">({{ sales.total }})</span>
        </h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Listings of yours that sold, with the accepted offer and buyer.
        </p>
    </div>

    <section v-if="sales.data.length" class="flex flex-col gap-4">
        <div v-for="listing in sales.data" :key="listing.id" class="card flex flex-col gap-4 p-4 sm:flex-row">
            <Link
                :href="`/listing/${listing.id}`"
                class="block h-32 w-full shrink-0 overflow-hidden rounded-lg bg-gray-100 sm:w-44 dark:bg-gray-800"
            >
                <img
                    v-if="listing.images?.length"
                    :src="listing.images[0].src"
                    class="h-full w-full object-cover"
                    alt="Property photo"
                    loading="lazy"
                />
                <div v-else class="flex h-full w-full items-center justify-center text-gray-300 dark:text-gray-600">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9-8 9 8M5 10v10a1 1 0 001 1h4v-6h4v6h4a1 1 0 001-1V10" />
                    </svg>
                </div>
            </Link>

            <div class="min-w-0 flex-1">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="badge-green">Sold</span>
                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ formatDate(listing.sold_at) }}</span>
                </div>
                <div class="mt-1 font-semibold">
                    {{ listing.street }} {{ listing.street_nr }}, {{ listing.city }}
                </div>
                <ListingSpace :listing="listing" class="mt-1 text-sm" />
                <div v-if="listing.accepted_offer" class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Buyer: {{ listing.accepted_offer.bidder.name }}
                </div>
            </div>

            <div class="flex shrink-0 flex-col justify-center gap-1 text-right sm:w-44">
                <div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Sold for</div>
                    <Price
                        :price="listing.accepted_offer?.amount ?? listing.price"
                        class="text-xl font-bold"
                    />
                </div>
                <div class="text-xs text-gray-500 dark:text-gray-400">
                    Asking price was <Price :price="listing.price" />
                </div>
            </div>
        </div>
    </section>

    <EmptyState v-else>
        None of your listings have sold yet.
        <template #action>
            <Link href="/realtor/listing" class="btn-primary btn-sm">Go to your listings</Link>
        </template>
    </EmptyState>

    <div v-if="sales.data.length" class="my-8 flex justify-center">
        <Pagination :links="sales.links" />
    </div>
</template>

<script setup>
import { Head, Link } from "@inertiajs/vue3";
import Price from "@/components/Price.vue";
import ListingSpace from "@/components/ListingSpace.vue";
import EmptyState from "@/components/UI/EmptyState.vue";
import Pagination from "@/components/UI/Pagination.vue";

defineProps({
    sales: Object,
});

const formatDate = (value) =>
    new Date(value).toLocaleDateString("en-US", {
        year: "numeric",
        month: "long",
        day: "numeric",
    });
</script>

<script>
import MainLayout from "@/Pages/Layouts/MainLayout.vue";

export default {
    layout: MainLayout,
};
</script>
