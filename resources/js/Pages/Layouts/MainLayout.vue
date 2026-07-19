<template>
    <div class="flex min-h-screen flex-col">
        <!-- Skip link: first tab stop, jumps keyboard users past the nav -->
        <a
            href="#main-content"
            class="sr-only focus:not-sr-only focus:absolute focus:top-2 focus:left-2 focus:z-50 focus:rounded-lg focus:bg-brand-600 focus:px-4 focus:py-2 focus:text-white"
        >
            Skip to content
        </a>

        <header
            class="sticky top-0 z-40 border-b border-gray-200 bg-white/90 backdrop-blur dark:border-gray-800 dark:bg-gray-900/90"
        >
            <div class="container mx-auto px-4">
                <nav class="flex h-16 items-center justify-between gap-4" aria-label="Main navigation">
                    <Link href="/" class="flex items-center gap-2" aria-label="EstateSphere home">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-brand-600 text-white">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9-8 9 8M5 10v10a1 1 0 001 1h4v-6h4v6h4a1 1 0 001-1V10" />
                            </svg>
                        </span>
                        <span class="text-lg font-bold tracking-tight text-gray-900 dark:text-white">
                            Estate<span class="text-brand-600">Sphere</span>
                        </span>
                    </Link>

                    <!-- Desktop nav -->
                    <div class="hidden items-center gap-1 md:flex">
                        <Link
                            href="/listing"
                            class="btn-ghost btn-sm"
                            :class="{ 'text-brand-700 dark:text-brand-300': isActive('/listing') }"
                            :aria-current="isActive('/listing') ? 'page' : undefined"
                        >
                            Browse listings
                        </Link>

                        <template v-if="loggedUser">
                            <Link
                                href="/realtor/listing"
                                class="btn-ghost btn-sm"
                                :class="{ 'text-brand-700 dark:text-brand-300': isActive('/realtor/listing') }"
                                :aria-current="isActive('/realtor/listing') ? 'page' : undefined"
                            >
                                My listings
                            </Link>

                            <Link
                                href="/notification"
                                class="btn-ghost relative"
                                :aria-label="notificationCount
                                    ? `Notifications, ${notificationCount} unread`
                                    : 'Notifications'"
                            >
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2a2 2 0 01-.6 1.4L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                <span
                                    v-if="notificationCount"
                                    class="absolute -top-0.5 -right-0.5 flex h-5 min-w-5 items-center justify-center rounded-full bg-red-600 px-1 text-xs font-semibold text-white"
                                    aria-hidden="true"
                                >
                                    {{ notificationLabel }}
                                </span>
                            </Link>

                            <Link href="/realtor/listing/create" class="btn-primary btn-sm ml-2">
                                + New listing
                            </Link>

                            <div class="ml-3 flex items-center gap-3 border-l border-gray-200 pl-3 dark:border-gray-700">
                                <span class="max-w-32 truncate text-sm text-gray-600 dark:text-gray-300">
                                    {{ loggedUser.name }}
                                </span>
                                <button class="btn-outline btn-sm" @click="logout">Log out</button>
                            </div>
                        </template>

                        <template v-else>
                            <Link href="/login" class="btn-outline btn-sm ml-2">Log in</Link>
                            <Link href="/user-account/create" class="btn-primary btn-sm">Register</Link>
                        </template>
                    </div>

                    <!-- Mobile toggle -->
                    <button
                        class="btn-ghost md:hidden"
                        :aria-expanded="mobileOpen"
                        aria-controls="mobile-menu"
                        aria-label="Toggle menu"
                        @click="mobileOpen = !mobileOpen"
                    >
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path v-if="!mobileOpen" stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16" />
                            <path v-else stroke-linecap="round" d="M6 6l12 12M18 6L6 18" />
                        </svg>
                    </button>
                </nav>

                <!-- Mobile nav -->
                <div
                    v-if="mobileOpen"
                    id="mobile-menu"
                    class="flex flex-col gap-1 border-t border-gray-200 py-3 md:hidden dark:border-gray-800"
                >
                    <Link href="/listing" class="btn-ghost justify-start">Browse listings</Link>
                    <template v-if="loggedUser">
                        <Link href="/realtor/listing" class="btn-ghost justify-start">My listings</Link>
                        <Link href="/notification" class="btn-ghost justify-start">
                            Notifications
                            <span v-if="notificationCount" class="badge-red">{{ notificationLabel }}</span>
                        </Link>
                        <Link href="/realtor/listing/create" class="btn-primary mt-1">+ New listing</Link>
                        <button class="btn-outline mt-1" @click="logout">Log out</button>
                    </template>
                    <template v-else>
                        <Link href="/login" class="btn-outline mt-1">Log in</Link>
                        <Link href="/user-account/create" class="btn-primary mt-1">Register</Link>
                    </template>
                </div>
            </div>
        </header>

        <main id="main-content" class="container mx-auto w-full flex-1 px-4 py-6 page-enter">
            <slot />
        </main>

        <footer class="border-t border-gray-200 py-6 dark:border-gray-800">
            <div class="container mx-auto px-4 text-center text-sm text-gray-500 dark:text-gray-400">
                EstateSphere — find your next home.
            </div>
        </footer>

        <!-- Toast notifications -->
        <Toasts />

        <!-- Back to top -->
        <button
            v-if="showBackToTop"
            type="button"
            class="fixed right-4 bottom-4 z-40 rounded-full bg-brand-600 p-3 text-white shadow-lg transition-colors hover:bg-brand-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-500/40"
            aria-label="Back to top"
            @click="scrollTop"
        >
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7" />
            </svg>
        </button>
    </div>
</template>

<script setup>
import { Link, usePage, useForm } from "@inertiajs/vue3";
import { computed, onMounted, onUnmounted, ref, watch } from "vue";
import Toasts from "@/components/UI/Toasts.vue";
import { useToasts } from "@/Composable/useToasts";

const page = usePage();
const form = useForm({});
const mobileOpen = ref(false);
const { success, error } = useToasts();

const logout = () => form.post("/logout");

const loggedUser = computed(() => page.props.user);

const notificationCount = computed(() => loggedUser.value?.notificationCount ?? 0);
const notificationLabel = computed(() =>
    notificationCount.value > 9 ? "9+" : notificationCount.value
);

const isActive = (path) => page.url.startsWith(path);

// Flash -> toasts. Watching page.url means the same message re-fires
// on each navigation that carries it, while in-place prop updates
// (e.g. filter changes) don't duplicate it.
watch(
    () => [page.url, page.props.flash],
    ([, flash]) => {
        if (flash?.message) success(flash.message);
        if (flash?.error) error(flash.error);
    },
    { immediate: true }
);

// Close the mobile menu after navigating.
watch(() => page.url, () => (mobileOpen.value = false));

// ---- Back to top ----
const showBackToTop = ref(false);
const onScroll = () => (showBackToTop.value = window.scrollY > 600);
const scrollTop = () => window.scrollTo({ top: 0, behavior: "smooth" });

onMounted(() => window.addEventListener("scroll", onScroll, { passive: true }));
onUnmounted(() => window.removeEventListener("scroll", onScroll));
</script>