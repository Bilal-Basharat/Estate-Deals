<template>
    <!-- aria-live polite: screen readers announce new toasts without interrupting -->
    <div
        class="pointer-events-none fixed top-20 right-4 z-50 flex w-full max-w-sm flex-col gap-2"
        aria-live="polite"
        aria-atomic="false"
    >
        <TransitionGroup
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="translate-y-1 opacity-0"
            enter-to-class="translate-y-0 opacity-100"
            leave-active-class="transition duration-150 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-for="toast in toasts"
                :key="toast.id"
                class="pointer-events-auto flex items-start gap-3 rounded-lg border p-3.5 shadow-lg backdrop-blur"
                :class="toast.type === 'error'
                    ? 'border-red-200 bg-red-50/95 text-red-800 dark:border-red-900 dark:bg-red-950/90 dark:text-red-200'
                    : 'border-green-200 bg-green-50/95 text-green-800 dark:border-green-900 dark:bg-green-950/90 dark:text-green-200'"
                role="status"
            >
                <svg
                    v-if="toast.type === 'success'"
                    class="mt-0.5 h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                <svg
                    v-else
                    class="mt-0.5 h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M12 3l9.5 16.5H2.5L12 3z" />
                </svg>

                <p class="flex-1 text-sm">{{ toast.message }}</p>

                <button
                    type="button"
                    class="shrink-0 opacity-60 transition-opacity hover:opacity-100"
                    aria-label="Dismiss notification"
                    @click="dismiss(toast.id)"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" d="M6 6l12 12M18 6L6 18" />
                    </svg>
                </button>
            </div>
        </TransitionGroup>
    </div>
</template>

<script setup>
import { useToasts } from "@/Composable/useToasts";

const { toasts, dismiss } = useToasts();
</script>
