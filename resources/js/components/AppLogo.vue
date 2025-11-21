<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { usePage } from '@inertiajs/vue3';
const page = usePage();
const settings = page.props.settings;
</script>

<template>
    <div
        class="flex aspect-square p-1 size-8 items-center justify-center rounded-md bg-sidebar-primary text-sidebar-primary-foreground"
    >
        <img :src="settings.logo" alt="">
    </div>
    <div class="ml-1 grid flex-1 text-left text-sm">
        <span class="mb-0.5 truncate leading-tight font-semibold"
            >{{ settings.store_name }}</span
        >
    </div>
</template>
