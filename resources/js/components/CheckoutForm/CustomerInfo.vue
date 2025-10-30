<template>
  <div class="mt-4">
    <h2 class="text-xl font-bold mb-2">{{ title }}</h2>
    <div class="flex flex-col gap-2">
      <input v-model="form.customer_name" placeholder="Name" class="border p-2 rounded" required />
      <input v-model="form.customer_phone" placeholder="Phone" class="border p-2 rounded" required />
      <input v-model="form.customer_email" placeholder="Email" class="border p-2 rounded" required />
      <textarea v-model="form.customer_address" placeholder="Address" class="border p-2 rounded" required></textarea>
    </div>
  </div>
</template>

<script setup lang="ts">
import { defineProps } from 'vue';

const props = defineProps<{
  form: {
    customer_name: string,
    customer_phone: string,
    customer_email: string,
    customer_address: string,
  }
  title?: string;
}>();
</script>
