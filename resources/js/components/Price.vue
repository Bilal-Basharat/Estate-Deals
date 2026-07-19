<template>
    <span>{{ formattedPrice }}</span>
</template>

<script setup>
import { computed } from "vue";

const props = defineProps({
    price: [String, Number],
});

const formattedPrice = computed(() => {
    const value = Number(props.price);
    if (!Number.isFinite(value)) return "—";
    return value.toLocaleString("en-US", {
        style: "currency",
        currency: "USD",
        maximumFractionDigits: 0,
    });
});
</script>
