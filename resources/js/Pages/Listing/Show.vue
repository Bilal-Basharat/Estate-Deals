<template>
    <div class="mb-4">
        <Link href="/listing" class="btn-ghost btn-sm">← Back to listings</Link>
    </div>

    <!-- Header: address as the page title, price prominent -->
    <div class="mb-5 flex flex-wrap items-end justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold tracking-tight">
                {{ listing.street }} {{ listing.street_nr }}, {{ listing.city }}
            </h1>
            <ListingSpace :listing="listing" class="mt-1 text-sm" />
        </div>
        <div class="text-right">
            <Price :price="listing.price" class="text-3xl font-bold" />
            <div class="text-xs text-gray-500 dark:text-gray-400">
                est. <Price :price="monthlyPayment" /> / month
            </div>
        </div>
    </div>

    <div v-if="listing.sold_at" class="mb-5 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800 dark:border-green-900 dark:bg-green-950/60 dark:text-green-200">
        This property has been sold.
    </div>

    <div class="flex flex-col gap-6 lg:grid lg:grid-cols-12 lg:items-start">
        <!-- Gallery -->
        <div class="lg:col-span-7">
            <div v-if="listing.images.length" class="card overflow-hidden p-2">
                <button
                    type="button"
                    class="group relative block aspect-[4/3] w-full overflow-hidden rounded-lg bg-gray-100 dark:bg-gray-800"
                    aria-label="Open photo gallery"
                    @click="openLightbox(activeIndex)"
                >
                    <img
                        :src="listing.images[activeIndex].src"
                        class="h-full w-full object-cover"
                        alt="Listing photo"
                    />
                    <span class="absolute right-3 bottom-3 flex items-center gap-1.5 rounded-md bg-black/55 px-2.5 py-1 text-xs font-medium text-white backdrop-blur-sm transition-colors group-hover:bg-black/70">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8V5a2 2 0 012-2h3m8 0h3a2 2 0 012 2v3m0 8v3a2 2 0 01-2 2h-3m-8 0H5a2 2 0 01-2-2v-3" />
                        </svg>
                        {{ activeIndex + 1 }} / {{ listing.images.length }} — view fullscreen
                    </span>
                </button>

                <div v-if="listing.images.length > 1" class="mt-2 grid grid-cols-5 gap-2 sm:grid-cols-6">
                    <button
                        v-for="(image, index) in listing.images"
                        :key="image.id"
                        type="button"
                        class="aspect-square overflow-hidden rounded-md border-2 transition-all"
                        :class="index === activeIndex
                            ? 'border-brand-600'
                            : 'border-transparent opacity-70 hover:opacity-100'"
                        :aria-label="`Show photo ${index + 1}`"
                        @click="activeIndex = index"
                    >
                        <img :src="image.src" class="h-full w-full object-cover" alt="" loading="lazy" />
                    </button>
                </div>
            </div>

            <EmptyState v-else>No photos for this listing yet.</EmptyState>

            <!-- Payment calculator moved under the gallery: reference info, not an action -->
            <Box class="mt-4">
                <template #header>Payment calculator</template>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="label">Profit rate: {{ profitRate }}%</label>
                        <input v-model.number="profitRate" type="range" min="0.1" max="100" step="0.1" class="range" />
                    </div>
                    <div>
                        <label class="label">Duration: {{ duration }} years</label>
                        <input v-model.number="duration" type="range" min="3" max="35" step="1" class="range" />
                    </div>
                </div>

                <div class="mt-4 grid grid-cols-2 gap-4 border-t border-gray-100 pt-4 sm:grid-cols-4 dark:border-gray-800">
                    <div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">Monthly</div>
                        <Price :price="monthlyPayment" class="font-semibold" />
                    </div>
                    <div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">Total paid</div>
                        <Price :price="totalPaid" class="font-semibold" />
                    </div>
                    <div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">Principal</div>
                        <Price :price="basisAmount" class="font-semibold" />
                    </div>
                    <div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">Profit</div>
                        <Price :price="totalProfit" class="font-semibold" />
                    </div>
                </div>

                <p v-if="user && !isOwner && !offerMade" class="mt-3 text-xs text-gray-400 dark:text-gray-500">
                    The calculator follows your offer amount as you adjust it.
                </p>
            </Box>
        </div>

        <!-- Sticky action sidebar -->
        <div class="flex flex-col gap-4 lg:sticky lg:top-20 lg:col-span-5">
            <MakeOffer
                v-if="user && !isOwner && !offerMade && !listing.sold_at"
                :listing-id="listing.id"
                :price="listing.price"
                @offer-updated="offer = $event"
            />

            <OfferMade v-if="user && offerMade" :offer-made="offerMade" />

            <Box v-if="user && isOwner">
                <template #header>Your listing</template>
                <p class="text-sm text-gray-600 dark:text-gray-300">
                    This is how buyers see your listing.
                </p>
                <div class="mt-3 flex gap-2">
                    <Link :href="`/realtor/listing/${listing.id}`" class="btn-primary btn-sm flex-1">
                        Manage offers
                    </Link>
                    <Link :href="`/realtor/listing/${listing.id}/edit`" class="btn-outline btn-sm flex-1">
                        Edit
                    </Link>
                </div>
            </Box>

            <Box v-if="!user">
                <template #header>Interested?</template>
                <p class="text-sm text-gray-600 dark:text-gray-300">
                    Log in to make an offer on this property.
                </p>
                <div class="mt-3 flex gap-2">
                    <Link href="/login" class="btn-primary btn-sm flex-1">Log in</Link>
                    <Link href="/user-account/create" class="btn-outline btn-sm flex-1">Register</Link>
                </div>
            </Box>
        </div>
    </div>

    <!-- Lightbox -->
    <Teleport to="body">
        <div
            v-if="lightboxOpen"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/90 p-4"
            role="dialog"
            aria-modal="true"
            aria-label="Photo gallery"
            @click.self="closeLightbox"
        >
            <button
                type="button"
                class="absolute top-4 right-4 rounded-full bg-white/10 p-2 text-white transition-colors hover:bg-white/20"
                aria-label="Close gallery (Esc)"
                @click="closeLightbox"
            >
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" d="M6 6l12 12M18 6L6 18" />
                </svg>
            </button>

            <button
                v-if="listing.images.length > 1"
                type="button"
                class="absolute left-4 rounded-full bg-white/10 p-2 text-white transition-colors hover:bg-white/20"
                aria-label="Previous photo (left arrow)"
                @click="step(-1)"
            >
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </button>

            <img
                :src="listing.images[activeIndex].src"
                class="max-h-full max-w-full rounded-lg object-contain"
                alt="Listing photo fullscreen"
            />

            <button
                v-if="listing.images.length > 1"
                type="button"
                class="absolute right-4 rounded-full bg-white/10 p-2 text-white transition-colors hover:bg-white/20"
                aria-label="Next photo (right arrow)"
                @click="step(1)"
            >
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
            </button>

            <div class="absolute bottom-4 left-1/2 -translate-x-1/2 rounded-md bg-white/10 px-3 py-1 text-sm text-white">
                {{ activeIndex + 1 }} / {{ listing.images.length }}
            </div>
        </div>
    </Teleport>
</template>

<script setup>
import { computed, onMounted, onUnmounted, ref } from "vue";
import { Link, usePage } from "@inertiajs/vue3";
import ListingSpace from "@/components/ListingSpace.vue";
import Price from "@/components/Price.vue";
import Box from "@/components/UI/Box.vue";
import EmptyState from "@/components/UI/EmptyState.vue";
import MakeOffer from "@/Pages/Listing/Show/Components/MakeOffer.vue";
import OfferMade from "@/Pages/Listing/Show/Components/OfferMade.vue";
import { useMonthlyPayments } from "@/Composable/useMonthlyPayment";

const props = defineProps({
    listing: Object,
    offerMade: Object,
});

const profitRate = ref(2.5);
const duration = ref(25);
const offer = ref(props.listing.price);
const activeIndex = ref(0);
const lightboxOpen = ref(false);

// Calculator basis: the user's live offer amount when they're drafting one,
// otherwise the asking price.
const basisAmount = computed(() => offer.value ?? props.listing.price);

const { monthlyPayment, totalPaid, totalProfit } = useMonthlyPayments(
    basisAmount,
    profitRate,
    duration
);

const page = usePage();
const user = computed(() => page.props.user);
const isOwner = computed(() => user.value && user.value.id === props.listing.user_id);

// ---- Lightbox with keyboard navigation ----
const openLightbox = (index) => {
    activeIndex.value = index;
    lightboxOpen.value = true;
};
const closeLightbox = () => (lightboxOpen.value = false);
const step = (direction) => {
    const count = props.listing.images.length;
    activeIndex.value = (activeIndex.value + direction + count) % count;
};

const onKeydown = (event) => {
    if (!lightboxOpen.value) return;
    if (event.key === "Escape") closeLightbox();
    if (event.key === "ArrowLeft") step(-1);
    if (event.key === "ArrowRight") step(1);
};

onMounted(() => window.addEventListener("keydown", onKeydown));
onUnmounted(() => window.removeEventListener("keydown", onKeydown));
</script>

<script>
import MainLayout from "@/Pages/Layouts/MainLayout.vue";

export default {
    layout: MainLayout,
};
</script>
