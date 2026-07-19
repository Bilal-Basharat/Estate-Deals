<template>
    <div class="mx-auto mt-8 w-full max-w-md">
        <div class="card p-6 sm:p-8">
            <h1 class="text-xl font-bold">Log in</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Welcome back. Enter your details to continue.
            </p>

            <form class="mt-6" @submit.prevent="login">
                <div>
                    <label for="email" class="label">Email</label>
                    <input
                        id="email"
                        v-model="form.email"
                        type="email"
                        class="input"
                        autocomplete="email"
                        required
                    />
                    <div v-if="form.errors.email" class="input-error">{{ form.errors.email }}</div>
                </div>

                <div class="mt-4">
                    <div class="flex items-center justify-between">
                        <label for="password" class="label">Password</label>
                        <Link href="/forgot-password" class="text-sm text-brand-600 hover:underline">
                            Forgot password?
                        </Link>
                    </div>
                    <input
                        id="password"
                        v-model="form.password"
                        type="password"
                        class="input"
                        autocomplete="current-password"
                        required
                    />
                    <div v-if="form.errors.password" class="input-error">{{ form.errors.password }}</div>
                </div>

                <button
                    type="submit"
                    class="btn-primary mt-6 w-full"
                    :disabled="form.processing"
                >
                    {{ form.processing ? "Logging in..." : "Log in" }}
                </button>
            </form>
        </div>

        <p class="mt-4 text-center text-sm text-gray-500 dark:text-gray-400">
            Don't have an account?
            <Link href="/user-account/create" class="font-medium text-brand-600 hover:underline">
                Register
            </Link>
        </p>
    </div>
</template>

<script setup>
import { useForm, Link } from "@inertiajs/vue3";

const form = useForm({
    email: null,
    password: null,
});

const login = () => form.post("/login");
</script>

<script>
import MainLayout from "@/Pages/Layouts/MainLayout.vue";

export default {
    layout: MainLayout,
};
</script>
