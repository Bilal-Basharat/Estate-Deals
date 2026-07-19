<template>
    <div class="card flex flex-wrap items-center justify-between gap-4 p-4">
        <label class="flex cursor-pointer items-center gap-2 text-sm">
            <input
                v-model="filterForm.deleted"
                type="checkbox"
                class="h-4 w-4 rounded border-gray-300 text-brand-600 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-800"
            />
            Include deleted listings
        </label>

        <div class="flex items-center gap-2">
            <label for="sort-by" class="text-sm text-gray-500 dark:text-gray-400">Sort by</label>
            <div class="flex">
                <select id="sort-by" v-model="filterForm.by" class="input-left w-32">
                    <option value="created_at">Date added</option>
                    <option value="price">Price</option>
                </select>
                <select v-model="filterForm.order" class="input-right w-36" aria-label="Sort order">
                    <option
                        v-for="option in sortOptions"
                        :key="option.value"
                        :value="option.value"
                    >
                        {{ option.label }}
                    </option>
                </select>
            </div>
        </div>
    </div>
</template>

<script setup>
import { reactive, watch, computed } from "vue";
import { router } from "@inertiajs/vue3";
import { debounce } from "lodash-es";

const props = defineProps({
    filters: Object,
});

const filterForm = reactive({
    deleted: Boolean(props.filters?.deleted),
    by: props.filters?.by ?? "created_at",
    order: props.filters?.order ?? "desc",
});

watch(
    filterForm,
    debounce(() => {
        router.get("/realtor/listing", filterForm, {
            preserveState: true,
            preserveScroll: true,
        });
    }, 400)
);

const sortLabels = {
    created_at: [
        { label: "Newest first", value: "desc" },
        { label: "Oldest first", value: "asc" },
    ],
    price: [
        { label: "Highest price", value: "desc" },
        { label: "Lowest price", value: "asc" },
    ],
};

const sortOptions = computed(() => sortLabels[filterForm.by]);
</script>
