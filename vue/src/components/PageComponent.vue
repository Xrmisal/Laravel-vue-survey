<script setup>
    const props = defineProps({
        title: String,

    });
</script>


<template>
    <header class="bg-white shadow-sm">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold tracking-tight text-gray-900">{{ title }}</h1>
            <slot name="header"></slot>
        </div>
    </header>
    <main>
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <slot></slot>
        </div>
    </main>
</template>


<style></style>