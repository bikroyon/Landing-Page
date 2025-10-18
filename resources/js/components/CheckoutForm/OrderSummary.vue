<template>
  <div class="mt-4 p-4 border rounded bg-gray-50">
    <h2 class="text-xl font-bold mb-2">Order Summary</h2>
    <div class="flex justify-between mb-1">
      <span>Subtotal</span>
      <span>${{ subtotal.toFixed(2) }}</span>
    </div>
    <div class="flex justify-between mb-1" v-if="deliveryFee">
      <span>Delivery Fee</span>
      <span>${{ deliveryFee.toFixed(2) }}</span>
    </div>
    <div class="flex justify-between mb-1 text-green-600" v-if="discount">
      <span>Discount</span>
      <span>- ${{ discount.toFixed(2) }}</span>
    </div>
    <hr class="my-2" />
    <div class="flex justify-between font-bold">
      <span>Total</span>
      <span>${{ total.toFixed(2) }}</span>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed, defineProps } from 'vue';

const props = defineProps<{
  subtotal: number,
  deliveryFee?: number,
  discount?: number
}>();

const total = computed(() => Math.max(0, props.subtotal + (props.deliveryFee || 0) - (props.discount || 0)));
</script>
