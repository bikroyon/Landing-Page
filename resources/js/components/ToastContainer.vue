<script setup lang="ts">
import { useToast } from '@/composables/useToast';
import { ToastClose, ToastDescription, ToastProvider, ToastRoot, ToastTitle, ToastViewport } from 'reka-ui';

const { toasts } = useToast();

function getToastClasses(type: string) {
  switch (type) {
    case 'success':
      return 'bg-teal-600 border-teal-500';
    case 'error':
      return 'bg-rose-600 border-red-500';
    case 'info':
      return 'bg-sky-600 border-sky-500';
    default:
      return 'bg-gray-600 border-gray-500';
  }
}
</script>

<template>
  <ToastProvider>
    <ToastRoot
      v-for="toast in toasts"
      :key="toast.id"
      :class="`mb-2 flex flex-row items-center justify-between gap-2 rounded-xl border p-4 shadow-md ${getToastClasses(toast.type)}`"
    >
      <div>
        <ToastTitle class="text-white">{{ toast.title }}</ToastTitle>
        <ToastDescription class="text-white text-xs">{{ toast.description }}</ToastDescription>
      </div>

      <ToastClose as-child>
        <button class="text-sm font-bold text-white" aria-label="Close">✕</button>
      </ToastClose>
    </ToastRoot>

    <ToastViewport class="fixed right-4 top-4 sm:min-w-md" />
  </ToastProvider>
</template>