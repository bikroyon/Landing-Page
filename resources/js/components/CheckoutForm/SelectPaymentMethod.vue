<template>
  <div class="mt-4">
    <h2 class="text-xl font-bold mb-2"> {{ title }} </h2>

    <!-- Payment options -->
    <div class="flex flex-wrap gap-3">
      <label
        v-for="method in paymentMethods"
        :key="method.id"
        class="cursor-pointer border rounded-lg px-4 py-2 transition-all duration-150"
        :class="{
          'bg-blue-600 text-white border-blue-600': form.payment_method_id === method.id,
          'hover:border-blue-500': form.payment_method_id !== method.id
        }"
      >
        <input
          type="radio"
          :value="method.id"
          v-model="form.payment_method_id"
          class="hidden"
        />
        <div class="text-center font-semibold">
          {{ method.name }}
        </div>
      </label>
    </div>

    <!-- Conditional Section (non-COD methods) -->
    <div
      v-if="selectedMethod && selectedMethod.name.toLowerCase() !== 'cash on delivery'"
      class="mt-4 p-4 border rounded-lg bg-gray-50"
    >
      <h3 class="text-lg font-semibold mb-2">
        Pay with {{ selectedMethod.name }}
      </h3>

      <!-- Example QR Image -->
      <div class="flex flex-col items-center mb-4">
        <img
          src="https://placehold.co/600x400"
          alt="QR Code"
          class="w-40 h-40 object-contain mb-2"
        />
        <p class="text-sm text-gray-600">
          Scan this QR to pay using {{ selectedMethod.name }}
        </p>
      </div>

      <!-- Transaction Inputs -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        <div>
          <label class="block text-sm font-medium mb-1">Transaction ID</label>
          <input
            type="text"
            v-model="form.transaction_id"
            placeholder="Enter Transaction ID"
            class="w-full border rounded p-2"
          />
        </div>

        <div>
          <label class="block text-sm font-medium mb-1">Mobile Number</label>
          <input
            type="text"
            v-model="form.payment_mobile"
            placeholder="Enter your payment number"
            class="w-full border rounded p-2"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed, defineProps } from 'vue';

const props = defineProps<{
  title?: string;
  form: {
    payment_method_id: number | null;
    transaction_id?: string;
    payment_mobile?: string;
  };
  paymentMethods: Array<{ id: number; name: string }>;
}>();

// Find currently selected method
const selectedMethod = computed(() =>
  props.paymentMethods.find((m) => m.id === props.form.payment_method_id)
);
</script>

<style scoped>
label {
  min-width: 120px;
  text-align: center;
}
</style>
