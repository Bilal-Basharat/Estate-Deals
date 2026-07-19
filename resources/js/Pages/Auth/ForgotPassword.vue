<template>
    <div class="mx-auto mt-8 w-full max-w-md">
        <div class="card p-6 sm:p-8">
            <h1 class="text-xl font-bold">Reset your password</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Enter your email and we'll send you a reset link.
            </p>

            <form class="mt-6" @submit.prevent="sendPasswordLink">
                <label for="email" class="label">Email</label>
                <input id="email" v-model="form.email" type="email" class="input" autocomplete="email" required />
                <div v-if="form.errors.email" class="input-error">{{ form.errors.email }}</div>

                <button type="submit" class="btn-primary mt-6 w-full" :disabled="form.processing">
                    {{ form.processing ? "Sending..." : "Send reset link" }}
                </button>
            </form>
        </div>

        <p class="mt-4 text-center text-sm text-gray-500 dark:text-gray-400">
            Remembered it?
            <Link href="/login" class="font-medium text-brand-600 hover:underline">Back to log in</Link>
        </p>
    </div>
</template>

<script setup>
import { useForm, Link } from "@inertiajs/vue3";

const form = useForm({
    email: null,
});

const sendPasswordLink = () => form.post("/forgot-password");
</script>

<script>
import MainLayout from "@/Pages/Layouts/MainLayout.vue";

export default {
    layout: MainLayout,
};
</script>
