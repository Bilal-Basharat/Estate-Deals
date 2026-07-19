<template>
    <Link
        :href="`/listing/${listing.id}`"
        class="card group overflow-hidden transition-shadow hover:shadow-md focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-500/40"
    >
        <!-- Image carousel -->
        <div class="relative aspect-[4/3] w-full overflow-hidden bg-gray-100 dark:bg-gray-800">
            <template v-if="images.length">
                <img
                    :src="images[currentIndex].src"
                    class="h-full w-full object-cover"
                    :alt="`Photo ${currentIndex + 1} of ${listing.city} listing`"
                    loading="lazy"
                />

                <!-- Badges -->
                <div class="absolute top-2 left-2 flex gap-1.5">
                    <span
                        v-if="isNew"
                        class="rounded-md bg-brand-600 px-2 py-0.5 text-xs font-semibold text-white shadow"
                    >
                        New
                    </span>
                </div>

                <!-- Photo count -->
                <span
                    class="absolute right-2 bottom-2 flex items-center gap-1 rounded-md bg-black/55 px-2 py-0.5 text-xs font-medium text-white backdrop-blur-sm"
                >
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.9l.9-1.8A2 2 0 018.6 4h6.8a2 2 0 011.8 1.2L18.1 7h.9a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                        <circle cx="12" cy="13" r="3.2" />
                    </svg>
                    {{ currentIndex + 1 }}/{{ images.length }}
                </span>

                <!-- Carousel controls -->
                <template v-if="images.length > 1">
                    <button
                        type="button"
                        class="absolute top-1/2 left-2 -translate-y-1/2 rounded-full bg-white/85 p-1.5 text-gray-700 opacity-0 shadow transition-opacity group-hover:opacity-100 hover:bg-white focus-visible:opacity-100"
                        aria-label="Previous photo"
                        @click.stop.prevent="previous"
                    >
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <button
                        type="button"
                        class="absolute top-1/2 right-2 -translate-y-1/2 rounded-full bg-white/85 p-1.5 text-gray-700 opacity-0 shadow transition-opacity group-hover:opacity-100 hover:bg-white focus-visible:opacity-100"
                        aria-label="Next photo"
                        @click.stop.prevent="next"
                    >
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>

                    <!-- Dots -->
                    <div class="absolute bottom-2 left-1/2 flex -translate-x-1/2 gap-1">
                        <span
                            v-for="(image, index) in images.slice(0, 5)"
                            :key="image.id"
                            class="h-1.5 w-1.5 rounded-full transition-colors"
                            :class="index === Math.min(currentIndex, 4) ? 'bg-white' : 'bg-white/50'"
                        />
                    </div>
                </template>
            </template>

            <div v-else class="flex h-full w-full items-center justify-center text-gray-300 dark:text-gray-600">
                <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9-8 9 8M5 10v10a1 1 0 001 1h4v-6h4v6h4a1 1 0 001-1V10" />
                </svg>
            </div>
        </div>

        <div class="p-4">
            <div class="flex items-baseline justify-between gap-2">
                <Price :price="listing.price" class="text-xl font-bold" />
                <span class="shrink-0 text-xs text-gray-500 dark:text-gray-400">
                    <Price :price="monthlyPayment" /> / mo
                </span>
            </div>
            <ListingSpace :listing="listing" class="mt-2 text-sm" />
            <ListingAddress :listing="listing" class="mt-1 text-sm" />
        </div>
    </Link>
</template>

<script setup>
import { computed, ref } from "vue";
import { Link } from "@inertiajs/vue3";
import ListingAddress from "@/components/ListingAddress.vue";
import ListingSpace from "@/components/ListingSpace.vue";
import Price from "@/components/Price.vue";
import { useMonthlyPayments } from "@/Composable/useMonthlyPayment";

const props = defineProps({
    listing: Object,
});

const { monthlyPayment } = useMonthlyPayments(props.listing.price, 2.5, 25);

const images = computed(() => props.listing.images ?? []);
const currentIndex = ref(0);

const next = () => {
    currentIndex.value = (currentIndex.value + 1) % images.value.length;
};
const previous = () => {
    currentIndex.value =
        (currentIndex.value - 1 + images.value.length) % images.value.length;
};

const isNew = computed(() => {
    const created = new Date(props.listing.created_at);
    const daysOld = (Date.now() - created.getTime()) / 86400000;
    return daysOld <= 7;
});
</script>
