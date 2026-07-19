<template>
    <div class="mb-6 flex flex-col gap-3">
        <!-- Search bar -->
        <form class="card flex flex-col gap-3 p-4" @submit.prevent="apply">
            <div class="flex gap-2">
                <div class="relative flex-1">
                    <svg
                        class="pointer-events-none absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 text-gray-400"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10.5a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z" />
                    </svg>
                    <input
                        v-model="form.city"
                        type="search"
                        list="city-suggestions"
                        placeholder="Search by city or street..."
                        class="input pl-9"
                        aria-label="Search by city or street"
                    />
                    <datalist id="city-suggestions">
                        <option v-for="city in cities" :key="city" :value="city" />
                    </datalist>
                </div>

                <button type="submit" class="btn-primary shrink-0">Search</button>

                <button
                    type="button"
                    class="btn-outline shrink-0"
                    :aria-expanded="showFilters"
                    @click="showFilters = !showFilters"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 4h18M6 12h12M10 20h4" />
                    </svg>
                    Filters
                    <span v-if="activeFilterCount" class="badge-red">{{ activeFilterCount }}</span>
                </button>
            </div>

            <!-- Popular cities (Zameen-style quick locations) -->
            <div v-if="cities.length && !form.city" class="flex flex-wrap items-center gap-1.5">
                <span class="text-xs text-gray-400 dark:text-gray-500">Popular:</span>
                <button
                    v-for="city in cities.slice(0, 6)"
                    :key="city"
                    type="button"
                    class="rounded-full border border-gray-200 px-3 py-1 text-xs text-gray-600 transition-colors hover:border-brand-300 hover:bg-brand-50 hover:text-brand-700 dark:border-gray-700 dark:text-gray-300 dark:hover:border-brand-700 dark:hover:bg-brand-900/40 dark:hover:text-brand-200"
                    @click="searchCity(city)"
                >
                    {{ city }}
                </button>
            </div>

            <!-- Expandable filter panel -->
            <div
                v-if="showFilters"
                class="grid grid-cols-1 gap-4 border-t border-gray-100 pt-4 sm:grid-cols-2 lg:grid-cols-4 dark:border-gray-800"
            >
                <div>
                    <span class="label">Price (USD)</span>
                    <div class="flex">
                        <input v-model.number="form.priceFrom" type="number" min="0" placeholder="Min" class="input-left w-full" />
                        <input v-model.number="form.priceTo" type="number" min="0" placeholder="Max" class="input-right w-full" />
                    </div>
                </div>

                <div>
                    <span class="label">Beds</span>
                    <select v-model="form.beds" class="input">
                        <option :value="null">Any</option>
                        <option v-for="n in 5" :key="n" :value="n">{{ n }}</option>
                        <option :value="6">6+</option>
                    </select>
                </div>

                <div>
                    <span class="label">Baths</span>
                    <select v-model="form.baths" class="input">
                        <option :value="null">Any</option>
                        <option v-for="n in 5" :key="n" :value="n">{{ n }}</option>
                        <option :value="6">6+</option>
                    </select>
                </div>

                <div>
                    <span class="label">Area (m²)</span>
                    <div class="flex">
                        <input v-model.number="form.areaFrom" type="number" min="0" placeholder="Min" class="input-left w-full" />
                        <input v-model.number="form.areaTo" type="number" min="0" placeholder="Max" class="input-right w-full" />
                    </div>
                </div>

                <div class="flex items-end gap-2 sm:col-span-2 lg:col-span-4">
                    <button type="submit" class="btn-primary">Apply filters</button>
                    <button type="button" class="btn-outline" @click="clearAll">Reset all</button>
                </div>
            </div>
        </form>

        <!-- Results row: chips + count + sort -->
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="flex flex-wrap items-center gap-2">
                <span class="text-sm text-gray-500 dark:text-gray-400">
                    <span class="font-semibold text-gray-900 dark:text-gray-100">{{ total }}</span>
                    {{ total === 1 ? "listing" : "listings" }}
                </span>

                <!-- Active filter chips: one-tap removal without reopening the panel -->
                <button
                    v-for="chip in chips"
                    :key="chip.key"
                    type="button"
                    class="inline-flex items-center gap-1.5 rounded-full bg-brand-50 px-3 py-1 text-xs font-medium text-brand-700 transition-colors hover:bg-brand-100 dark:bg-brand-900/40 dark:text-brand-200 dark:hover:bg-brand-900/70"
                    :aria-label="`Remove filter: ${chip.label}`"
                    @click="removeChip(chip.key)"
                >
                    {{ chip.label }}
                    <span aria-hidden="true">✕</span>
                </button>

                <button
                    v-if="chips.length > 1"
                    type="button"
                    class="text-xs text-gray-500 underline-offset-2 hover:underline dark:text-gray-400"
                    @click="clearAll"
                >
                    Clear all
                </button>
            </div>

            <div class="flex items-center gap-2">
                <label for="sort" class="text-sm text-gray-500 dark:text-gray-400">Sort</label>
                <select id="sort" v-model="sort" class="input w-44" @change="apply">
                    <option value="">Newest first</option>
                    <option value="price:asc">Price: low to high</option>
                    <option value="price:desc">Price: high to low</option>
                </select>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, reactive, ref } from "vue";
import { router } from "@inertiajs/vue3";
import { pickBy } from "lodash-es";

const props = defineProps({
    filters: Object,
    cities: { type: Array, default: () => [] },
    total: Number,
});

// Initialized from server-confirmed filters so state survives reloads/pagination.
const form = reactive({
    city: props.filters?.city ?? null,
    priceFrom: props.filters?.priceFrom ?? null,
    priceTo: props.filters?.priceTo ?? null,
    beds: props.filters?.beds ?? null,
    baths: props.filters?.baths ?? null,
    areaFrom: props.filters?.areaFrom ?? null,
    areaTo: props.filters?.areaTo ?? null,
});

const sort = ref(
    props.filters?.by ? `${props.filters.by}:${props.filters.order ?? "desc"}` : ""
);

// Panel starts open when a panel-filter is already active.
const showFilters = ref(
    Boolean(form.priceFrom || form.priceTo || form.beds || form.baths || form.areaFrom || form.areaTo)
);

const navigate = () => {
    const [by, order] = sort.value ? sort.value.split(":") : [null, null];

    router.get(
        "/listing",
        pickBy({ ...form, by, order }, (value) => value !== null && value !== ""),
        { preserveState: true, preserveScroll: true }
    );
};

const apply = () => navigate();

const searchCity = (city) => {
    form.city = city;
    navigate();
};

const fmt = (value) => Number(value).toLocaleString("en-US");

// Chips reflect the SERVER-confirmed filters (props), not unsent form edits.
const chipDefs = {
    city: (value) => `Location: ${value}`,
    priceFrom: (value) => `Min $${fmt(value)}`,
    priceTo: (value) => `Max $${fmt(value)}`,
    beds: (value) => `${value}${value >= 6 ? "+" : ""} beds`,
    baths: (value) => `${value}${value >= 6 ? "+" : ""} baths`,
    areaFrom: (value) => `Min ${fmt(value)} m²`,
    areaTo: (value) => `Max ${fmt(value)} m²`,
};

const chips = computed(() =>
    Object.entries(chipDefs)
        .filter(([key]) => props.filters?.[key])
        .map(([key, label]) => ({ key, label: label(props.filters[key]) }))
);

const activeFilterCount = computed(
    () => chips.value.filter((chip) => chip.key !== "city").length
);

const removeChip = (key) => {
    form[key] = null;
    navigate();
};

const clearAll = () => {
    Object.keys(form).forEach((key) => (form[key] = null));
    sort.value = "";
    router.get("/listing", {}, { preserveScroll: true });
};
</script>
