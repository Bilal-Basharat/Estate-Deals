<template>
    <div
        class="card p-5"
        :class="{
            'ring-2 ring-brand-500/40': isHighest && !isSold,
            'opacity-60': offer.rejected_at,
        }"
    >
        <div class="flex items-center justify-between gap-2">
            <div class="flex items-center gap-2">
                <span class="text-sm font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                    Offer #{{ offer.id }}
                </span>
                <span v-if="isHighest && !isSold" class="badge-green">Highest offer</span>
            </div>
            <span v-if="offer.accepted_at" class="badge-green">Accepted</span>
            <span v-else-if="offer.rejected_at" class="badge-red">Rejected</span>
            <span v-else class="badge-gray">Pending</span>
        </div>

        <div class="mt-3 flex items-center justify-between gap-4">
            <div>
                <Price :price="offer.amount" class="text-xl font-bold" />
                <div class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">
                    <Price
                        :price="difference"
                        :class="difference < 0 ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400'"
                    />
                    vs asking price
                </div>
                <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    From {{ offer.bidder.name }} on {{ madeOn }}
                </div>
            </div>

            <button
                v-if="!isSold && !offer.rejected_at"
                class="btn-primary btn-sm shrink-0"
                @click="accept"
            >
                Accept offer
            </button>
        </div>
    </div>
</template>

<script setup>
import { computed } from "vue";
import { router } from "@inertiajs/vue3";
import Price from "@/components/Price.vue";

const props = defineProps({
    offer: Object,
    listingPrice: Number,
    isSold: Boolean,
    isHighest: Boolean,
});

const accept = () => {
    const amount = Number(props.offer.amount).toLocaleString("en-US");
    if (
        confirm(
            `Accept the $${amount} offer from ${props.offer.bidder.name}? ` +
            "This marks the listing as sold and rejects all other offers."
        )
    ) {
        router.put(`/realtor/offer/${props.offer.id}/accept`, {}, { preserveScroll: true });
    }
};

const difference = computed(() => props.offer.amount - props.listingPrice);
const madeOn = computed(() =>
    new Date(props.offer.created_at).toLocaleDateString("en-US", {
        year: "numeric",
        month: "short",
        day: "numeric",
    })
);
</script>
