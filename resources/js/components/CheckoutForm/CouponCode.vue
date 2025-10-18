<template>
  <div class="mt-4">
    <h2 class="text-xl font-bold mb-2">Coupon</h2>
    <input v-model="form.coupon_code" placeholder="Enter coupon code" class="border p-2 rounded w-full" />
    <p v-if="discount" class="text-green-600 mt-1">Discount Applied: ${{ discount.toFixed(2) }}</p>
  </div>
</template>

<script setup lang="ts">
import { defineProps } from 'vue';

const props = defineProps<{
  form: { coupon_code: string },
  discount: number
}>();
</script>
