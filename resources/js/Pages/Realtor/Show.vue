<template>
    <div class="mb-4">
        <Link href="/realtor/listing" class="btn-ghost btn-sm">← Back to dashboard</Link>
    </div>

    <section class="flex flex-col-reverse gap-6 md:grid md:grid-cols-12">
        <div class="md:col-span-7">
            <div class="mb-3 flex items-center justify-between">
                <h2 class="text-lg font-semibold">
                    Offers
                    <span v-if="listing.offers.length" class="text-gray-400">
                        ({{ listing.offers.length }})
                    </span>
                </h2>
                <span v-if="pendingCount" class="badge-red">{{ pendingCount }} pending</span>
            </div>

            <EmptyState v-if="!listing.offers.length">
                No offers on this listing yet.
            </EmptyState>

            <div v-else class="flex flex-col gap-3">
                <Offer
                    v-for="offer in sortedOffers"
                    :key="offer.id"
                    :offer="offer"
                    :listing-price="listing.price"
                    :is-sold="listing.sold_at !== null"
                    :is-highest="offer.id === highestPendingId"
                />
            </div>
        </div>

        <div class="md:col-span-5">
            <Box>
                <template #header>Listing</template>

                <div
                    v-if="listing.images?.length"
                    class="mb-3 aspect-[4/3] overflow-hidden rounded-lg bg-gray-100 dark:bg-gray-800"
                >
                    <img :src="listing.images[0].src" class="h-full w-full object-cover" alt="Listing photo" />
                </div>

                <div class="mb-2 flex items-center gap-2">
                    <span v-if="listing.sold_at" class="badge-green">Sold</span>
                    <span v-else class="badge-gray">Active</span>
                </div>

                <Price :price="listing.price" class="text-2xl font-bold" />
                <ListingSpace :listing="listing" class="mt-2 text-sm" />
                <ListingAddress :listing="listing" class="mt-1 text-sm" />

                <div class="mt-4 flex gap-2 border-t border-gray-100 pt-4 dark:border-gray-800">
                    <Link :href="`/listing/${listing.id}`" class="btn-outline btn-sm flex-1">
                        Public view
                    </Link>
                    <Link :href="`/realtor/listing/${listing.id}/edit`" class="btn-outline btn-sm flex-1">
                        Edit
                    </Link>
                </div>
            </Box>
        </div>
    </section>
</template>

<script setup>
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";
import Box from "@/components/UI/Box.vue";
import EmptyState from "@/components/UI/EmptyState.vue";
import Price from "@/components/Price.vue";
import ListingAddress from "@/components/ListingAddress.vue";
import ListingSpace from "@/components/ListingSpace.vue";
import Offer from "@/Pages/Realtor/Show/Components/Offer.vue";

const props = defineProps({
    listing: Object,
});

const isPending = (offer) => !offer.accepted_at && !offer.rejected_at;

const pendingCount = computed(() => props.listing.offers.filter(isPending).length);

// Pending offers first (highest amount on top), then accepted, then rejected.
const sortedOffers = computed(() =>
    [...props.listing.offers].sort((a, b) => {
        const rank = (offer) => (offer.accepted_at ? 1 : offer.rejected_at ? 2 : 0);
        return rank(a) - rank(b) || b.amount - a.amount;
    })
);

const highestPendingId = computed(() => {
    const pending = props.listing.offers.filter(isPending);
    if (!pending.length) return null;
    return pending.reduce((best, offer) => (offer.amount > best.amount ? offer : best)).id;
});
</script>

<script>
import MainLayout from "@/Pages/Layouts/MainLayout.vue";

export default {
    layout: MainLayout,
};
</script>
