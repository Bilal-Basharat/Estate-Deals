<template>
    <Head title="My purchases" />

    <div class="mb-6">
        <h1 class="text-2xl font-bold tracking-tight">
            My purchases
            <span v-if="purchases.total" class="text-gray-400">({{ purchases.total }})</span>
        </h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Properties where your offer was accepted.
        </p>
    </div>

    <section v-if="purchases.data.length" class="flex flex-col gap-4">
        <div v-for="offer in purchases.data" :key="offer.id" class="card flex flex-col gap-4 p-4 sm:flex-row">
            <Link
                :href="`/listing/${offer.listing.id}`"
                class="block h-32 w-full shrink-0 overflow-hidden rounded-lg bg-gray-100 sm:w-44 dark:bg-gray-800"
            >
                <img
                    v-if="offer.listing.images?.length"
                    :src="offer.listing.images[0].src"
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
                    <span class="badge-green">Purchased</span>
                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ formatDate(offer.accepted_at) }}</span>
                </div>
                <div class="mt-1 font-semibold">
                    {{ offer.listing.street }} {{ offer.listing.street_nr }}, {{ offer.listing.city }}
                </div>
                <ListingSpace :listing="offer.listing" class="mt-1 text-sm" />
            </div>

            <div class="flex shrink-0 flex-col justify-center gap-1 text-right sm:w-44">
                <div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">You paid</div>
                    <Price :price="offer.amount" class="text-xl font-bold" />
                </div>
                <div class="text-xs text-gray-500 dark:text-gray-400">
                    Asking price was <Price :price="offer.listing.price" />
                    <span :class="saved(offer) > 0 ? 'text-green-600 dark:text-green-400' : ''">
                        <template v-if="saved(offer) > 0">
                            (saved <Price :price="saved(offer)" />)
                        </template>
                    </span>
                </div>
            </div>
        </div>
    </section>

    <EmptyState v-else>
        You haven't purchased any properties yet.
        <template #action>
            <Link href="/listing" class="btn-primary btn-sm">Browse listings</Link>
        </template>
    </EmptyState>

    <div v-if="purchases.data.length" class="my-8 flex justify-center">
        <Pagination :links="purchases.links" />
    </div>
</template>

<script setup>
import { Head, Link } from "@inertiajs/vue3";
import Price from "@/components/Price.vue";
import ListingSpace from "@/components/ListingSpace.vue";
import EmptyState from "@/components/UI/EmptyState.vue";
import Pagination from "@/components/UI/Pagination.vue";

defineProps({
    purchases: Object,
});

const formatDate = (value) =>
    new Date(value).toLocaleDateString("en-US", {
        year: "numeric",
        month: "long",
        day: "numeric",
    });

const saved = (offer) => offer.listing.price - offer.amount;
</script>

<script>
import MainLayout from "@/Pages/Layouts/MainLayout.vue";

export default {
    layout: MainLayout,
};
</script>
