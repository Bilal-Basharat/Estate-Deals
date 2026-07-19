<template>
    <div class="mx-auto mt-8 w-full max-w-md">
        <div class="card p-6 text-center sm:p-8">
            <svg class="mx-auto h-12 w-12 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
            </svg>

            <h1 class="mt-4 text-xl font-bold">Verify your email</h1>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                We sent a verification link to your inbox. You need to verify your account before managing listings.
            </p>

            <Link
                as="button"
                method="post"
                href="/email/verification-notification"
                class="btn-primary mt-6"
            >
                Resend verification email
            </Link>
        </div>
    </div>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
</script>

<script>
import MainLayout from "@/Pages/Layouts/MainLayout.vue";

export default {
    layout: MainLayout,
};
</script>
