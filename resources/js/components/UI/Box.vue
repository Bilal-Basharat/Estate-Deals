<template>
    <div class="card p-5">
        <div v-if="$slots.header" class="mb-3 text-sm font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
            <slot name="header" />
        </div>
        <slot />
    </div>
</template>
