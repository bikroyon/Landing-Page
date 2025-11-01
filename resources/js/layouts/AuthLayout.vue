<script setup lang="ts">
import ToastContainer from '@/components/ToastContainer.vue';
import AuthLayout from '@/layouts/auth/AuthSimpleLayout.vue';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <AuthLayout :title="title" :description="description">
        <ToastContainer />
        <slot />
    </AuthLayout>
</template>
