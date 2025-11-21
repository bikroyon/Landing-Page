<script setup lang="ts">
import ToastContainer from '@/components/ToastContainer.vue';
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import type { BreadcrumbItemType } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
const page = usePage();
const settings = page.props.settings;
interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}
withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
<Head>
    <link rel="icon" type="image/png" :href="settings.favicon" />
</Head>
<AppLayout :breadcrumbs="breadcrumbs">
    <ToastContainer />
    <slot />
</AppLayout>

</template>
