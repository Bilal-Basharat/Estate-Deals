<template>
    <div class="card flex flex-col gap-4 p-4 sm:flex-row">
        <!-- Thumbnail -->
        <Link
            :href="`/listing/${listing.id}`"
            class="block h-32 w-full shrink-0 overflow-hidden rounded-lg bg-gray-100 sm:w-44 dark:bg-gray-800"
            :class="{ 'opacity-50': listing.deleted_at }"
        >
            <img
                v-if="listing.images?.length"
                :src="listing.images[0].src"
                class="h-full w-full object-cover"
                alt="Listing photo"
                loading="lazy"
            />
            <div v-else class="flex h-full w-full items-center justify-center text-gray-300 dark:text-gray-600">
                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9-8 9 8M5 10v10a1 1 0 001 1h4v-6h4v6h4a1 1 0 001-1V10" />
                </svg>
            </div>
        </Link>

        <!-- Details -->
        <div class="min-w-0 flex-1" :class="{ 'opacity-50': listing.deleted_at }">
            <div class="flex flex-wrap items-center gap-2">
                <Price :price="listing.price" class="text-xl font-bold" />
                <span v-if="listing.sold_at" class="badge-green">Sold</span>
                <span v-else-if="!listing.deleted_at" class="badge-gray">Active</span>
                <span v-if="listing.deleted_at" class="badge-red">Deleted</span>
            </div>
            <ListingSpace :listing="listing" class="mt-1 text-sm" />
            <ListingAddress :listing="listing" class="mt-1 text-sm" />

            <div class="mt-2 flex flex-wrap gap-3 text-xs text-gray-500 dark:text-gray-400">
                <span>{{ listing.images_count }} photos</span>
                <span>{{ listing.offers_count }} offers total</span>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex shrink-0 flex-col justify-between gap-2 sm:w-48">
            <Link
                :href="`/realtor/listing/${listing.id}`"
                class="relative"
                :class="listing.pending_offers_count ? 'btn-primary btn-sm' : 'btn-outline btn-sm'"
            >
                {{ listing.pending_offers_count
                    ? `Review ${listing.pending_offers_count} pending ${listing.pending_offers_count === 1 ? "offer" : "offers"}`
                    : "View offers" }}
            </Link>

            <div class="flex gap-2">
                <Link
                    class="btn-outline btn-sm flex-1"
                    :href="`/realtor/listing/${listing.id}/image/create`"
                >
                    Photos
                </Link>
                <Link
                    class="btn-outline btn-sm flex-1"
                    :href="`/realtor/listing/${listing.id}/edit`"
                >
                    Edit
                </Link>
            </div>

            <Link
                v-if="listing.deleted_at"
                class="btn-outline btn-sm"
                :href="`/realtor/listing/${listing.id}/restore`"
                method="put"
                as="button"
                preserve-scroll
            >
                Restore
            </Link>
            <button v-else class="btn-danger btn-sm" @click="destroy">
                Delete
            </button>
        </div>
    </div>
</template>

<script setup>
import { Link, router } from "@inertiajs/vue3";
import ListingAddress from "@/components/ListingAddress.vue";
import ListingSpace from "@/components/ListingSpace.vue";
import Price from "@/components/Price.vue";

const props = defineProps({
    listing: Object,
});

const destroy = () => {
    if (confirm("Delete this listing? You can restore it later using the Deleted filter.")) {
        router.delete(`/realtor/listing/${props.listing.id}`, {
            preserveScroll: true,
        });
    }
};
</script>
