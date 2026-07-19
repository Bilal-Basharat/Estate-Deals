<template>
    <div class="mx-auto max-w-2xl">
        <div class="mb-6">
            <h1 class="text-2xl font-bold tracking-tight">New listing</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Fill in the property details. You can add photos after saving.
            </p>
        </div>

        <form class="card p-6" @submit.prevent="create">
            <ListingFields :form="form" />

            <div class="mt-6 flex justify-end gap-2">
                <Link href="/realtor/listing" class="btn-outline">Cancel</Link>
                <button type="submit" class="btn-primary" :disabled="form.processing">
                    {{ form.processing ? "Saving..." : "Create listing" }}
                </button>
            </div>
        </form>
    </div>
</template>

<script setup>
import { useForm, Link } from "@inertiajs/vue3";
import ListingFields from "@/Pages/Realtor/Components/ListingFields.vue";

const form = useForm({
    beds: null,
    baths: null,
    area: null,
    city: null,
    code: null,
    street: null,
    street_nr: null,
    price: null,
});

const create = () => form.post("/realtor/listing");
</script>

<script>
import MainLayout from "@/Pages/Layouts/MainLayout.vue";

export default {
    layout: MainLayout,
};
</script>
