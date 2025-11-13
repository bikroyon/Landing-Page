<template>
  <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
    <div
      v-for="product in products.filter(p => p.status === true)"
      :key="product.id"
      @click="toggleSelect(product.id)"
      :class="[
        'relative flex overflow-hidden border gap-1 rounded-2xl p-2 cursor-pointer transition-all',
        selectedProducts[product.id] ? 'border-green-600 shadow-lg' : 'border-gray-200 shadow-sm',
      ]"
    >
      <!-- Check Mark -->
      <div
        v-if="selectedProducts[product.id]"
        class="absolute top-2 right-2 rounded-full bg-green-600 p-1 text-white"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
        </svg>
      </div>

      <!-- Left: Image -->
      <div class="h-32 w-32 flex-shrink-0">
        <img
          :src="selectedImages[product.id] || product.image || 'https://placehold.co/150x150'"
          alt="product"
          class="h-full w-full object-cover rounded-2xl"
        />
      </div>

      <!-- Right: Info -->
      <div class="flex flex-1 flex-col justify-between p-1">
        <div>
          <!-- Name -->
          <h3
            :class="[
              'truncate text-sm font-semibold',
              selectedProducts[product.id] ? 'text-green-600' : 'text-gray-800'
            ]"
          >
            {{ product.name }}
          </h3>

          <!-- Description -->
          <p class="mt-1 line-clamp-2 text-xs text-gray-600">
            {{ product.description }}
          </p>

          <!-- Variations -->
          <div v-if="product.variations?.length" class="mt-1 flex flex-wrap gap-2">
            <button
              type="button"
              v-for="(v, idx) in product.variations"
              :key="idx"
              @click.stop="selectVariant(product.id, idx)"
              :class="[
                'rounded border px-1 py-1 text-xs',
                selectedVariants[product.id] === idx
                  ? 'border-green-500 bg-green-600 text-white'
                  : 'bg-gray-100 text-gray-800'
              ]"
            >
              {{ v.name }}
              <span v-if="v.extra_price">(+{{ v.extra_price }})</span>
            </button>
          </div>
        </div>

        <!-- Price -->
        <div class="mt-2 flex justify-end gap-2 items-center">
          <p class="text-right font-bold">
            {{ getPrice(product) }} ৳
          </p>
          <p
            class="text-right font-bold line-through text-gray-400 text-sm"
            v-if="product.prev_price && product.prev_price > product.price"
          >
            {{ product.prev_price }} ৳
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { defineProps, defineEmits, reactive } from 'vue';

const props = defineProps<{
  products: Array<{
    id: number;
    name: string;
    description: string;
    price: number | string;
    prev_price?: number | string;
    image?: string;
    variations?: Array<{
      name: string;
      extra_price: number;
      image?: string;
    }>;
    status?: boolean;
  }>;
}>();

const emits = defineEmits<{
  (e: 'update:items', items: { product_id: number; quantity: number; variant_index?: number; extra_price?: number }[]): void;
}>();

const selectedProducts = reactive<Record<number, boolean>>({});
const selectedVariants = reactive<Record<number, number | null>>({});
const selectedImages = reactive<Record<number, string>>({});

// Toggle product selection
function toggleSelect(productId: number) {
  const product = props.products.find(p => p.id === productId);
  if (!product) return;

  if (selectedProducts[productId]) {
    // Unselect product
    selectedProducts[productId] = false;
    selectedVariants[productId] = null; // reset variant
    selectedImages[productId] = ''; // reset image
    emitItems();
  } else {
    // Select product
    selectedProducts[productId] = true;

    // If product has variants, select first variant always when selecting
    if (product.variations?.length) {
      selectVariant(productId, 0);
    } else {
      emitItems();
    }
  }
}




// Select variant button
function selectVariant(productId: number, variantIndex: number) {
  const product = props.products.find(p => p.id === productId);
  if (!product) return;

  // Select the product as well
  selectedProducts[productId] = true;

  // Set selected variant
  selectedVariants[productId] = variantIndex;

  // Update image
  const variant = product.variations?.[variantIndex];
  selectedImages[productId] = variant?.image || product.image || '';

  emitItems();
}

// Get price including variant
function getPrice(product: any) {
  const variantIndex = selectedVariants[product.id];
  const basePrice = Number(product.price);
  if (variantIndex !== null && product.variations?.[variantIndex])
    return basePrice + Number(product.variations[variantIndex].extra_price || 0);
  return basePrice;
}

// Emit selected items
function emitItems() {
  const items = Object.entries(selectedProducts)
    .filter(([_, selected]) => selected)
    .map(([product_id]) => {
      const pid = Number(product_id);
      const variantIndex = selectedVariants[pid];
      const product = props.products.find((p) => p.id === pid);
      const extra_price =
        variantIndex !== null && product?.variations?.[variantIndex]?.extra_price
          ? Number(product.variations[variantIndex].extra_price)
          : 0;

      return {
        product_id: pid,
        quantity: 1,
        variant_index: variantIndex,
        extra_price,
      };
    });

  emits('update:items', items);
}
</script>

<style>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-box-orient: vertical;
  overflow: hidden;
  -webkit-line-clamp: 2;
}
</style>
