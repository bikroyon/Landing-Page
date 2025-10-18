<template>
  <div>
    <h2 class="text-xl font-bold mb-2">Products</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
      <div v-for="product in products" :key="product.id" class="border p-3 rounded">
        <h3 class="font-semibold">{{ product.name }}</h3>
        <p>{{ product.description }}</p>
        <p class="font-bold mt-2">${{ Number(product.price).toFixed(2) }}</p>

        <div class="mt-2 flex items-center gap-2">
          <button type="button" @click="decrease(product.id)" class="px-2 bg-gray-200 rounded">-</button>
          <span>{{ quantities[product.id] || 0 }}</span>
          <button type="button" @click="increase(product.id)" class="px-2 bg-gray-200 rounded">+</button>
        </div>

        <p v-if="quantities[product.id]" class="mt-1 text-sm text-gray-600">
          Subtotal: ${{ (quantities[product.id] * Number(product.price)).toFixed(2) }}
        </p>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { reactive, defineProps } from 'vue';
import { defineEmits } from 'vue';

const props = defineProps<{
  products: Array<{ id: number; name: string; description: string; price: number }>
}>();

const emits = defineEmits<{
  (e: 'update:items', items: { product_id: number; quantity: number }[]): void
}>();

const quantities = reactive<Record<number, number>>({});

function increase(id: number) {
  quantities[id] = (quantities[id] || 0) + 1;
  emitItems();
}

function decrease(id: number) {
  if ((quantities[id] || 0) > 0) quantities[id]--;
  emitItems();
}

function emitItems() {
  const items = Object.entries(quantities)
    .filter(([_, qty]) => qty > 0)
    .map(([product_id, quantity]) => ({ product_id: Number(product_id), quantity }));
  emits('update:items', items);
}
</script>
