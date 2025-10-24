<template>
  <div class="mt-4">
    <h2 class="text-xl font-bold mb-2">Delivery Zone</h2>
    <select v-model="form.delivery_zone_id" @change="updateZone" class="border p-2 rounded w-full">
      <option value="">Select Zone</option>
      <option v-for="zone in zones" :key="zone.id" :value="zone.id">
        {{ zone.name }} - {{ Number(zone.delivery_fee).toFixed(2) }} ৳
        <span v-if="zone.free_delivery_min_order"> (Free ≥ {{ Number(zone.free_delivery_min_order).toFixed(2) }} ৳)</span>
      </option>
    </select>
  </div>
</template>

<script setup lang="ts">
import { defineProps } from 'vue';

const props = defineProps<{
  form: {
    delivery_zone_id: number | null;
    delivery_fee?: number;
    delivery_zone_name?: string;
  };
  zones: Array<{ 
    id: number; 
    name: string; 
    delivery_fee: number | string; 
    free_delivery_min_order?: number | string; 
  }>;
  subtotal: number; // current subtotal to calculate free delivery
}>();

const updateZone = () => {
  const zone = props.zones.find(z => z.id === props.form.delivery_zone_id);
  if (!zone) {
    props.form.delivery_fee = 0;
    props.form.delivery_zone_name = '';
    return;
  }

  props.form.delivery_zone_name = zone.name;

  // Convert to number
  const fee = Number(zone.delivery_fee);
  const minOrder = zone.free_delivery_min_order ? Number(zone.free_delivery_min_order) : 0;

  // Check free delivery
  props.form.delivery_fee = (minOrder && props.subtotal >= minOrder) ? 0 : fee;
};
</script>
