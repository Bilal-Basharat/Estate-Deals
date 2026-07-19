import { computed, toValue } from "vue";

/**
 * Simple flat-profit payment model:
 * total cost = price * (1 + rate), spread evenly over the duration.
 * Accepts refs or plain values for every argument.
 */
export const useMonthlyPayments = (total, profitRate, duration) => {
    const monthlyPayment = computed(() => {
        const price = Number(toValue(total)) || 0;
        const rate = (Number(toValue(profitRate)) || 0) / 100;
        const months = (Number(toValue(duration)) || 1) * 12;

        return (price * (1 + rate)) / months;
    });

    const totalPaid = computed(
        () => (Number(toValue(duration)) || 0) * 12 * monthlyPayment.value
    );

    const totalProfit = computed(
        () => totalPaid.value - (Number(toValue(total)) || 0)
    );

    return { monthlyPayment, totalPaid, totalProfit };
};
