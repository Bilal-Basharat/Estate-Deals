<template>
    <div class="mx-auto mt-8 w-full max-w-md">
        <div class="card p-6 sm:p-8">
            <h1 class="text-xl font-bold">Choose a new password</h1>

            <form class="mt-6" @submit.prevent="resetPassword">
                <div>
                    <label for="email" class="label">Email</label>
                    <input id="email" v-model="form.email" type="email" class="input" autocomplete="email" required />
                    <div v-if="form.errors.email" class="input-error">{{ form.errors.email }}</div>
                </div>

                <div class="mt-4">
                    <label for="password" class="label">New password</label>
                    <input id="password" v-model="form.password" type="password" class="input" autocomplete="new-password" required />
                    <div v-if="form.errors.password" class="input-error">{{ form.errors.password }}</div>
                </div>

                <div class="mt-4">
                    <label for="password_confirmation" class="label">Confirm new password</label>
                    <input id="password_confirmation" v-model="form.password_confirmation" type="password" class="input" autocomplete="new-password" required />
                    <div v-if="form.errors.password_confirmation" class="input-error">
                        {{ form.errors.password_confirmation }}
                    </div>
                </div>

                <button type="submit" class="btn-primary mt-6 w-full" :disabled="form.processing">
                    {{ form.processing ? "Saving..." : "Reset password" }}
                </button>
            </form>
        </div>
    </div>
</template>

<script setup>
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    token: String,
});

const form = useForm({
    token: props.token,
    email: null,
    password: null,
    password_confirmation: null,
});

const resetPassword = () => form.post("/reset-password");
</script>

<script>
import MainLayout from "@/Pages/Layouts/MainLayout.vue";

export default {
    layout: MainLayout,
};
</script>
