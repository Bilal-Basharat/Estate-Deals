<template>
    <div class="card flex flex-col items-center justify-center gap-2 px-6 py-12 text-center">
        <svg class="h-10 w-10 text-gray-300 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.6a1 1 0 00-.9.55l-.9 1.9a1 1 0 01-.9.55H9.3a1 1 0 01-.9-.55l-.9-1.9a1 1 0 00-.9-.55H4" />
        </svg>
        <div class="font-medium text-gray-500 dark:text-gray-400">
            <slot />
        </div>
        <div v-if="$slots.action" class="mt-2">
            <slot name="action" />
        </div>
    </div>
</template>
