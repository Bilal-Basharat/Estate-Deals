<template>
    <div class="mb-4">
        <Link href="/realtor/listing" class="btn-ghost btn-sm">← Back to your listings</Link>
    </div>

    <Box class="mb-4">
        <template #header>Upload photos</template>

        <form @submit.prevent="upload">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <input
                    ref="fileInput"
                    class="image-input"
                    type="file"
                    accept="image/*"
                    multiple
                    @change="addFiles"
                />

                <div class="flex gap-2">
                    <button
                        type="submit"
                        class="btn-primary"
                        :disabled="!canUpload || form.processing"
                    >
                        {{ form.processing ? "Uploading..." : `Upload ${form.images.length || ""}` }}
                    </button>
                    <button type="button" class="btn-outline" :disabled="!canUpload" @click="reset">
                        Clear selection
                    </button>
                </div>
            </div>

            <div v-if="form.progress" class="mt-3 h-2 w-full overflow-hidden rounded-full bg-gray-200 dark:bg-gray-700">
                <div
                    class="h-full bg-brand-600 transition-all"
                    :style="{ width: form.progress.percentage + '%' }"
                />
            </div>

            <div v-if="firstError" class="input-error">{{ firstError }}</div>
        </form>
    </Box>

    <Box v-if="listing.images.length">
        <template #header>Current photos ({{ listing.images.length }})</template>

        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3">
            <div
                v-for="image in listing.images"
                :key="image.id"
                class="group relative aspect-[4/3] overflow-hidden rounded-lg bg-gray-100 dark:bg-gray-800"
            >
                <img :src="image.src" class="h-full w-full object-cover" alt="Listing photo" />

                <button
                    type="button"
                    class="absolute top-2 right-2 rounded-full bg-black/40 p-1.5 text-white opacity-0 backdrop-blur-sm transition-opacity duration-200 group-hover:opacity-100 hover:bg-black/60 focus-visible:opacity-100"
                    aria-label="Delete photo"
                    @click="deleteImage(image.id)"
                >
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                        />
                    </svg>
                </button>
            </div>
        </div>
    </Box>

    <EmptyState v-else>No photos yet. Upload some above to make the listing stand out.</EmptyState>
</template>

<script setup>
import { computed, ref } from "vue";
import { Link, router, useForm } from "@inertiajs/vue3";
import Box from "@/components/UI/Box.vue";
import EmptyState from "@/components/UI/EmptyState.vue";

const props = defineProps({
    listing: Object,
});

const fileInput = ref(null);

const form = useForm({
    images: [],
});

// Inertia serializes File objects automatically; forceFormData guarantees multipart.
const upload = () => {
    form.post(`/realtor/listing/${props.listing.id}/image`, {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => reset(),
    });
};

const addFiles = (event) => {
    form.images = [...event.target.files];
};

const canUpload = computed(() => form.images.length > 0);

const firstError = computed(() => Object.values(form.errors)[0] ?? null);

const reset = () => {
    form.reset("images");
    form.clearErrors();
    if (fileInput.value) fileInput.value.value = "";
};

const deleteImage = (imageId) => {
    if (confirm("Delete this photo?")) {
        router.delete(`/realtor/listing/${props.listing.id}/image/${imageId}`, {
            preserveScroll: true,
        });
    }
};
</script>

<script>
import MainLayout from "@/Pages/Layouts/MainLayout.vue";

export default {
    layout: MainLayout,
};
</script>
