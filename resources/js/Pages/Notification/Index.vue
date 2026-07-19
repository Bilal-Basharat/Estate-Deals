<template>
    <h1 class="mb-6 text-2xl font-bold tracking-tight">Notifications</h1>

    <section v-if="notifications.data.length" class="card divide-y divide-gray-100 dark:divide-gray-800">
        <div
            v-for="notification in notifications.data"
            :key="notification.id"
            class="flex items-center justify-between gap-4 px-5 py-4"
            :class="{ 'opacity-60': notification.read_at }"
        >
            <div class="flex items-start gap-3 text-sm text-gray-700 dark:text-gray-300">
                <span
                    class="mt-1.5 h-2 w-2 shrink-0 rounded-full"
                    :class="notification.read_at ? 'bg-gray-300 dark:bg-gray-600' : 'bg-brand-600'"
                />
                <span v-if="notification.type === 'App\\Notifications\\OfferMade'">
                    New offer of
                    <Price :price="notification.data.amount" class="font-semibold" />
                    on your
                    <Link
                        :href="`/realtor/listing/${notification.data.listing_id}`"
                        class="font-medium text-brand-600 hover:underline"
                    >
                        listing
                    </Link>
                </span>
                <span v-else>You have a new notification.</span>
            </div>

            <Link
                v-if="!notification.read_at"
                :href="`/notification/${notification.id}/mark`"
                method="put"
                as="button"
                class="btn-outline btn-sm shrink-0"
                preserve-scroll
            >
                Mark as read
            </Link>
        </div>
    </section>

    <EmptyState v-else>No notifications yet. Offers on your listings will show up here.</EmptyState>

    <section v-if="notifications.data.length" class="my-8 flex justify-center">
        <Pagination :links="notifications.links" />
    </section>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import EmptyState from "@/components/UI/EmptyState.vue";
import Pagination from "@/components/UI/Pagination.vue";
import Price from "@/components/Price.vue";

defineProps({
    notifications: Object,
});
</script>

<script>
import MainLayout from "@/Pages/Layouts/MainLayout.vue";

export default {
    layout: MainLayout,
};
</script>
