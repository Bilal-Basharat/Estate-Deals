<template>
    <div class="mx-auto max-w-2xl">
        <div class="mb-6">
            <h1 class="text-2xl font-bold tracking-tight">Edit listing</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                {{ listing.street }} {{ listing.street_nr }}, {{ listing.city }}
            </p>
        </div>

        <form class="card p-6" @submit.prevent="update">
            <ListingFields :form="form" />

            <div class="mt-6 flex justify-end gap-2">
                <Link href="/realtor/listing" class="btn-outline">Cancel</Link>
                <button type="submit" class="btn-primary" :disabled="form.processing">
                    {{ form.processing ? "Saving..." : "Save changes" }}
                </button>
            </div>
        </form>
    </div>
</template>

<script setup>
import { useForm, Link } from "@inertiajs/vue3";
import ListingFields from "@/Pages/Realtor/Components/ListingFields.vue";

const props = defineProps({
    listing: Object,
});

const form = useForm({
    beds: props.listing.beds,
    baths: props.listing.baths,
    area: props.listing.area,
    city: props.listing.city,
    code: props.listing.code,
    street: props.listing.street,
    street_nr: props.listing.street_nr,
    price: props.listing.price,
});

const update = () => form.put(`/realtor/listing/${props.listing.id}`);
</script>

<script>
import MainLayout from "@/Pages/Layouts/MainLayout.vue";

export default {
    layout: MainLayout,
};
</script>
