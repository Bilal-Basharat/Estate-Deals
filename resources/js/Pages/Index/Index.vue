<template>
    <Head title="Home" />

    <!-- ======================= GUEST ======================= -->
    <template v-if="!user">
        <section class="mx-auto max-w-3xl pt-12 pb-10 text-center sm:pt-20">
            <h1 class="text-4xl font-bold tracking-tight sm:text-5xl">
                Find your next home on
                <span class="text-brand-600">EstateSphere</span>
            </h1>
            <p class="mx-auto mt-4 max-w-xl text-lg text-gray-500 dark:text-gray-400">
                Browse verified listings, estimate your monthly payment, and make
                an offer directly to the owner.
            </p>

            <form class="mx-auto mt-8 flex max-w-xl gap-2" @submit.prevent="search">
                <div class="relative flex-1">
                    <svg class="pointer-events-none absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10.5a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z" />
                    </svg>
                    <input
                        v-model="city"
                        type="search"
                        placeholder="Which city are you looking in?"
                        class="input py-3 pl-9"
                        aria-label="Search listings by city"
                    />
                </div>
                <button type="submit" class="btn-primary px-6">Search</button>
            </form>

            <div v-if="cities.length" class="mt-3 flex flex-wrap items-center justify-center gap-1.5">
                <span class="text-xs text-gray-400 dark:text-gray-500">Popular:</span>
                <button
                    v-for="popularCity in cities"
                    :key="popularCity"
                    type="button"
                    class="rounded-full border border-gray-200 px-3 py-1 text-xs text-gray-600 transition-colors hover:border-brand-300 hover:bg-brand-50 hover:text-brand-700 dark:border-gray-700 dark:text-gray-300 dark:hover:border-brand-700 dark:hover:bg-brand-900/40 dark:hover:text-brand-200"
                    @click="searchCity(popularCity)"
                >
                    {{ popularCity }}
                </button>
            </div>
        </section>

        <section class="mx-auto grid max-w-4xl gap-4 pb-12 sm:grid-cols-3">
            <div class="card p-5">
                <div class="text-sm font-semibold">Transparent pricing</div>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Every listing shows an estimated monthly payment up front.
                </p>
            </div>
            <div class="card p-5">
                <div class="text-sm font-semibold">Direct offers</div>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Make an offer straight to the owner, with no middlemen.
                </p>
            </div>
            <div class="card p-5">
                <div class="text-sm font-semibold">Instant notifications</div>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Owners are notified the moment an offer comes in.
                </p>
            </div>
        </section>
    </template>

    <!-- ======================= LOGGED IN ======================= -->
    <template v-else>
        <section class="pt-4 pb-8">
            <h1 class="text-2xl font-bold tracking-tight">
                Welcome back, {{ user.name }}
            </h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Here's where things stand.
            </p>

            <!--
                Snapshot cards ARE the navigation on this page. No quick-action
                buttons: Browse / New listing / Notifications already live in
                the header on this same screen.
            -->
            <div class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5">
                <Link
                    href="/realtor/listing"
                    class="card p-4 transition-shadow hover:shadow-md"
                    :class="{ 'ring-2 ring-brand-500/40': snapshot.offersReceived }"
                >
                    <div class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                        Offers on your listings
                    </div>
                    <div class="mt-1 text-2xl font-bold" :class="{ 'text-brand-600': snapshot.offersReceived }">
                        {{ snapshot.offersReceived }}
                    </div>
                    <div class="mt-1 text-xs text-gray-400 dark:text-gray-500">
                        {{ snapshot.offersReceived ? "Waiting for your review →" : "All caught up" }}
                    </div>
                </Link>

                <Link href="/realtor/listing" class="card p-4 transition-shadow hover:shadow-md">
                    <div class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                        Your active listings
                    </div>
                    <div class="mt-1 text-2xl font-bold">{{ snapshot.activeListings }}</div>
                    <div class="mt-1 text-xs text-gray-400 dark:text-gray-500">Manage listings →</div>
                </Link>

                <div class="card p-4">
                    <div class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                        Your pending offers
                    </div>
                    <div class="mt-1 text-2xl font-bold">{{ snapshot.offersMade }}</div>
                    <div class="mt-1 text-xs text-gray-400 dark:text-gray-500">
                        Offers you've made as a buyer
                    </div>
                </div>

                <Link href="/purchases" class="card p-4 transition-shadow hover:shadow-md">
                    <div class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                        Properties bought
                    </div>
                    <div class="mt-1 text-2xl font-bold">{{ snapshot.purchases }}</div>
                    <div class="mt-1 text-xs text-gray-400 dark:text-gray-500">View purchase history →</div>
                </Link>

                <Link href="/sales" class="card p-4 transition-shadow hover:shadow-md">
                    <div class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                        Properties sold
                    </div>
                    <div class="mt-1 text-2xl font-bold">{{ snapshot.sales }}</div>
                    <div class="mt-1 text-xs text-gray-400 dark:text-gray-500">View sales history →</div>
                </Link>
            </div>
        </section>
    </template>

    <!-- ======================= LATEST LISTINGS (both) ======================= -->
    <section v-if="latestListings.length" class="pb-16">
        <div class="mb-4 flex items-center justify-between">
            <h2 class="text-lg font-semibold">Fresh on the market</h2>
            <Link href="/listing" class="text-sm font-medium text-brand-600 hover:underline">
                View all listings →
            </Link>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">
            <ListingCard
                v-for="listing in latestListings"
                :key="listing.id"
                :listing="listing"
            />
        </div>

        <div v-if="!user" class="mt-10 text-center">
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Have a property to sell?
            </p>
            <Link href="/user-account/create" class="btn-outline mt-3">
                Create an account and list it free
            </Link>
        </div>
    </section>
</template>

<script setup>
import { computed, ref } from "vue";
import { Head, Link, router, usePage } from "@inertiajs/vue3";
import ListingCard from "@/Pages/Listing/Index/Components/Listing.vue";

defineProps({
    latestListings: { type: Array, default: () => [] },
    cities: { type: Array, default: () => [] },
    snapshot: Object,
});

const page = usePage();
const user = computed(() => page.props.user);

const city = ref("");

const search = () => {
    router.get("/listing", city.value ? { city: city.value } : {});
};

const searchCity = (value) => {
    city.value = value;
    search();
};
</script>

<script>
import MainLayout from "@/Pages/Layouts/MainLayout.vue";

export default {
    layout: MainLayout,
};
</script>
