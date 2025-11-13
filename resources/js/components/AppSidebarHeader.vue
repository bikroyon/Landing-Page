<script setup lang="ts">
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import { SidebarTrigger } from '@/components/ui/sidebar';
import type { BreadcrumbItemType } from '@/types';
import Button from './ui/button/Button.vue';
import Icon from './Icon.vue';
import { Link } from '@inertiajs/vue3';

withDefaults(
    defineProps<{
        breadcrumbs?: BreadcrumbItemType[];
    }>(),
    {
        breadcrumbs: () => [],
    },
);
const goBack = () => {
  if (window.history.length > 1) {
    window.history.back()
  } else {
    // fallback (e.g. direct entry)
    window.location.href = '/'
  }
}
</script>

<template>
    <header
        class="flex justify-between h-16 shrink-0 items-center gap-2 border-b border-sidebar-border/70 px-6 transition-[width,height] ease-linear group-has-data-[collapsible=icon]/sidebar-wrapper:h-12 md:px-4"
    >
        <div class="flex items-center gap-2">
            <SidebarTrigger class="-ml-1" />
            <template v-if="breadcrumbs && breadcrumbs.length > 0">
                <Breadcrumbs :breadcrumbs="breadcrumbs" />
            </template>
        </div>
        <div class="flex gap-2">
            <Link href="/"><Button variant="secondary" > <Icon name="Eye"/> </Button></Link>
            <Button variant="default" @click="goBack" > <Icon name="CircleArrowLeft"/> </Button>
        </div>
    </header>
</template>
