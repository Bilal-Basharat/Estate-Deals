<template>
    <Box>
        <template #header>Make an offer</template>

        <form @submit.prevent="makeOffer">
            <label class="label" for="offer-amount">Your offer</label>
            <input
                id="offer-amount"
                v-model.number="form.amount"
                type="number"
                class="input"
                :min="min"
                :max="max"
                step="1000"
            />

            <input
                v-model.number="form.amount"
                type="range"
                :min="min"
                :max="max"
                step="10000"
                class="range mt-3"
                aria-label="Offer amount slider"
            />

            <div class="mt-3 flex justify-between text-sm text-gray-500 dark:text-gray-400">
                <span>Difference from asking price</span>
                <Price
                    :price="difference"
                    class="font-medium"
                    :class="difference < 0 ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400'"
                />
            </div>

            <div v-if="form.errors.amount" class="input-error">{{ form.errors.amount }}</div>

            <button
                type="submit"
                class="btn-primary mt-4 w-full"
                :disabled="form.processing"
            >
                {{ form.processing ? "Sending offer..." : "Make offer" }}
            </button>
        </form>
    </Box>
</template>

<script setup>
import { computed, watch } from "vue";
import { useForm } from "@inertiajs/vue3";
import { debounce } from "lodash-es";
import Price from "@/components/Price.vue";
import Box from "@/components/UI/Box.vue";

const props = defineProps({
    listingId: Number,
    price: Number,
});

const emit = defineEmits(["offerUpdated"]);

const form = useForm({
    amount: props.price,
});

// Debounce belongs on the handler; the wait is its second argument.
watch(
    () => form.amount,
    debounce((value) => emit("offerUpdated", value), 200)
);

const makeOffer = () => {
    form.post(`/listing/${props.listingId}/offer`, {
        preserveScroll: true,
        preserveState: true,
    });
};

const difference = computed(() => form.amount - props.price);
const min = computed(() => Math.round(props.price / 2));
const max = computed(() => props.price * 2);
</script>
