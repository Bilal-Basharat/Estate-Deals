<template>
    <div class="mx-auto mt-8 w-full max-w-md">
        <div class="card p-6 sm:p-8">
            <h1 class="text-xl font-bold">Create your account</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Register to list properties and make offers.
            </p>

            <form class="mt-6" @submit.prevent="register">
                <div>
                    <label for="name" class="label">Name</label>
                    <input id="name" v-model="form.name" type="text" class="input" autocomplete="name" required />
                    <div v-if="form.errors.name" class="input-error">{{ form.errors.name }}</div>
                </div>

                <div class="mt-4">
                    <label for="email" class="label">Email</label>
                    <input id="email" v-model="form.email" type="email" class="input" autocomplete="email" required />
                    <div v-if="form.errors.email" class="input-error">{{ form.errors.email }}</div>
                </div>

                <div class="mt-4">
                    <label for="password" class="label">Password</label>
                    <input id="password" v-model="form.password" type="password" class="input" autocomplete="new-password" required />
                    <div v-if="form.errors.password" class="input-error">{{ form.errors.password }}</div>
                </div>

                <div class="mt-4">
                    <label for="password_confirmation" class="label">Confirm password</label>
                    <input id="password_confirmation" v-model="form.password_confirmation" type="password" class="input" autocomplete="new-password" required />
                    <div v-if="form.errors.password_confirmation" class="input-error">
                        {{ form.errors.password_confirmation }}
                    </div>
                </div>

                <button type="submit" class="btn-primary mt-6 w-full" :disabled="form.processing">
                    {{ form.processing ? "Creating account..." : "Create account" }}
                </button>
            </form>
        </div>

        <p class="mt-4 text-center text-sm text-gray-500 dark:text-gray-400">
            Already have an account?
            <Link href="/login" class="font-medium text-brand-600 hover:underline">Log in</Link>
        </p>
    </div>
</template>

<script setup>
import { useForm, Link } from "@inertiajs/vue3";

const form = useForm({
    name: null,
    email: null,
    password: null,
    password_confirmation: null,
});

const register = () => form.post("/user-account");
</script>

<script>
import MainLayout from "@/Pages/Layouts/MainLayout.vue";

export default {
    layout: MainLayout,
};
</script>
