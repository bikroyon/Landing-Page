<template>
  <div class="mt-4">
    <h2 class="text-xl font-bold mb-2">Payment Method</h2>
    <select v-model="form.payment_method_id" class="border p-2 rounded w-full">
      <option value="">Select Payment</option>
      <option v-for="method in paymentMethods" :key="method.id" :value="method.id">{{ method.name }}</option>
    </select>
  </div>
</template>

<script setup lang="ts">
import { defineProps } from 'vue';

const props = defineProps<{
  form: { payment_method_id: number | null },
  paymentMethods: Array<{ id: number, name: string }>
}>();
</script>
