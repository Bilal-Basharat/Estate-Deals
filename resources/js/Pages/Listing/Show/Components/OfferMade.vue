<template>
    <Box>
        <template #header>Your offer</template>

        <Price :price="offerMade.amount" class="text-3xl font-bold" />

        <div class="mt-2 flex justify-between text-sm text-gray-500 dark:text-gray-400">
            <span>Made on</span>
            <span class="font-medium">{{ offerMadeOn }}</span>
        </div>

        <div
            v-if="offerMade.accepted_at"
            class="mt-3"
        >
            <span class="badge-green">Accepted</span>
        </div>
    </Box>
</template>

<script setup>
import { computed } from "vue";
import Price from "@/components/Price.vue";
import Box from "@/components/UI/Box.vue";

const props = defineProps({
    offerMade: Object,
});

const offerMadeOn = computed(() =>
    new Date(props.offerMade.created_at).toLocaleDateString("en-US", {
        year: "numeric",
        month: "long",
        day: "numeric",
    })
);
</script>
