<template>
  <div class="mt-6">
    <!-- Title -->
    <h2
      class="mb-4 text-2xl font-extrabold text-gray-700 flex items-center gap-2"
    >
      <span class="h-6 w-1 rounded-full bg-green-500"></span>
      {{ title }}
    </h2>

    <!-- Zones Grid -->
    <div
      class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4"
    >
      <label
        v-for="zone in zones"
        :key="zone.id"
        class="group relative cursor-pointer overflow-hidden rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition-all duration-300 hover:shadow-lg"
        :class="{
          'border-green-600 shadow-md shadow-green-100 ring-2 ring-green-300':
            form.delivery_zone_id === zone.id,
          'hover:border-green-400': form.delivery_zone_id !== zone.id,
        }"
      >
        <!-- Hidden input -->
        <input
          type="radio"
          :value="zone.id"
          v-model="form.delivery_zone_id"
          class="hidden"
          @change="updateZone"
        />

        <!-- Animated Background Accent -->
        <div
          class="absolute inset-0 opacity-0 transition-all duration-300 group-hover:opacity-10"
          :class="{
            'bg-green-500': form.delivery_zone_id === zone.id,
            'bg-green-100': form.delivery_zone_id !== zone.id,
          }"
        ></div>

        <!-- Zone Info -->
        <div class="relative z-10 flex flex-col items-center">
          <div
            class="text-sm font-bold transition-colors duration-200"
            :class="{
              'text-green-700': form.delivery_zone_id === zone.id,
              'text-gray-700 group-hover:text-green-600':
                form.delivery_zone_id !== zone.id,
            }"
          >
            {{ zone.name }}
          </div>

          <div class="text-sm text-gray-500">
            {{ Number(zone.delivery_fee).toFixed(2) }} ৳
          </div>

          <!-- Free Delivery Info -->
          <div
            v-if="zone.free_delivery_min_order"
            class="mt-2 py-0.5 text-[0.6rem] text-center font-medium text-green-600"
          >
            {{ Number(zone.free_delivery_min_order) }} ৳ এর উপরে অর্ডার করলেই পাচ্ছেন ফ্রি ডেলিভারি।
          </div>
        </div>

        <!-- Selection Glow Animation -->
        <div
          v-if="form.delivery_zone_id === zone.id"
          class="absolute inset-0 animate-pulse rounded-2xl border border-green-400/60 ring-2 ring-green-300/50"
        ></div>
      </label>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed, defineProps, watch } from "vue";

const props = defineProps<{
  form: {
    delivery_zone_id: number | null;
    delivery_fee?: number;
    delivery_zone_name?: string;
  };
  title?: string;
  zones: Array<{
    id: number;
    name: string;
    delivery_fee: number | string;
    free_delivery_min_order?: number | string;
  }>;
  subtotal: number;
}>();

const zone = computed(() =>
  props.zones.find((z) => z.id === props.form.delivery_zone_id)
);

watch(
  [() => props.form.delivery_zone_id, () => props.subtotal],
  () => {
    if (!zone.value) {
      props.form.delivery_fee = 0;
      props.form.delivery_zone_name = "";
      return;
    }

    props.form.delivery_zone_name = zone.value.name;

    const fee = Number(zone.value.delivery_fee);
    const minOrder = zone.value.free_delivery_min_order
      ? Number(zone.value.free_delivery_min_order)
      : 0;

    props.form.delivery_fee = minOrder && props.subtotal >= minOrder ? 0 : fee;
  },
  { immediate: true }
);
</script>

<style scoped>
label {
  transition: transform 0.25s ease, box-shadow 0.25s ease;
}
label:hover {
  transform: translateY(-2px);
}
</style>
