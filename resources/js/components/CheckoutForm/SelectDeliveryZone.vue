<template>
  <div class="mt-4">
    <h2 class="text-xl font-bold mb-2">Delivery Zone</h2>
    <select v-model="form.delivery_zone_id" class="border p-2 rounded w-full">
      <option value="">Select Zone</option>
      <option v-for="zone in zones" :key="zone.id" :value="zone.id">{{ zone.name }} - ${{ zone.fee }}</option>
    </select>
  </div>
</template>

<script setup lang="ts">
import { defineProps } from 'vue';

const props = defineProps<{
  form: { delivery_zone_id: number | null },
  zones: Array<{ id: number, name: string, fee: number }>
}>();
</script>
