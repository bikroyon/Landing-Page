<template>
  <AppLayout>
    <div class="p-6">
      <h1 class="text-2xl font-bold mb-4">{{ offer ? 'Edit Offer' : 'Create Offer' }}</h1>

      <form @submit.prevent="submit">
        <!-- Offer Type -->
        <div class="mb-2">
          <label>Offer Type</label>
          <select v-model="form.offer_type" class="border p-2 rounded w-full">
            <option value="">Select Type</option>
            <option value="coupon">Coupon</option>
            <option value="delivery">Delivery</option>
            <option value="product">Product</option>
            <option value="cart">Cart</option>
          </select>
        </div>

        <!-- Conditional Fields -->
        <div v-if="form.offer_type === 'coupon'" class="mb-2">
          <label>Coupon Code</label>
          <input type="text" v-model="form.code" class="border p-2 rounded w-full" placeholder="Enter coupon code" />
        </div>

        <div v-if="form.offer_type === 'delivery'" class="mb-2">
          <label>Delivery Zone</label>
          <select v-model="form.delivery_zone_id" class="border p-2 rounded w-full">
            <option value="">Select Zone</option>
            <option v-for="zone in deliveryZones" :key="zone.id" :value="zone.id">{{ zone.name }}</option>
          </select>

          <label>Minimum Order Amount</label>
          <input type="number" v-model="form.min_order_amount" class="border p-2 rounded w-full" placeholder="User must spend..." />
        </div>

        <div v-if="form.offer_type === 'product'" class="mb-2">
          <label>Product</label>
          <select v-model="form.product_id" class="border p-2 rounded w-full">
            <option value="">Select Product</option>
            <option v-for="product in products" :key="product.id" :value="product.id">{{ product.name }}</option>
          </select>
        </div>

        <div v-if="form.offer_type === 'cart'" class="mb-2">
          <label>Minimum Cart Amount</label>
          <input type="number" v-model="form.min_order_amount" class="border p-2 rounded w-full" placeholder="Min cart amount..." />
        </div>

        <!-- Common Fields -->
        <div class="mb-2">
          <label>Discount Type</label>
          <select v-model="form.discount_type" class="border p-2 rounded w-full">
            <option value="">Select</option>
            <option value="percentage">Percentage</option>
            <option value="fixed">Fixed</option>
          </select>
        </div>

        <div class="mb-2">
          <label>Discount Value</label>
          <input type="number" v-model="form.discount_value" class="border p-2 rounded w-full" />
        </div>

        <div class="mb-2">
          <label>Max Discount (optional)</label>
          <input type="number" v-model="form.max_discount" class="border p-2 rounded w-full" />
        </div>

        <div class="mb-2">
          <label>Start Date</label>
          <input type="datetime-local" v-model="form.starts_at" class="border p-2 rounded w-full" />
        </div>

        <div class="mb-2">
          <label>End Date</label>
          <input type="datetime-local" v-model="form.expires_at" class="border p-2 rounded w-full" />
        </div>

        <div class="mb-2 flex items-center gap-2">
          <input type="checkbox" v-model="form.active" />
          <label>Active</label>
        </div>

        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">{{ offer ? 'Update' : 'Create' }}</button>
      </form>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const page = usePage();
const offer = page.props.offer;

// If you have preloaded products and zones in controller
const products = page.props.products || [];
const deliveryZones = page.props.deliveryZones || [];

const form = ref({
  offer_type: offer?.offer_type || '',
  code: offer?.code || '',
  discount_type: offer?.discount_type || '',
  discount_value: offer?.discount_value || '',
  max_discount: offer?.max_discount || '',
  min_order_amount: offer?.min_order_amount || 0,
  product_id: offer?.product_id || null,
  delivery_zone_id: offer?.delivery_zone_id || null,
  starts_at: offer?.starts_at || '',
  expires_at: offer?.expires_at || '',
  active: offer?.active ?? true,
});

function submit() {
  if (offer) {
    router.put(`/offers/${offer.id}`, form.value);
  } else {
    router.post('/offers', form.value);
  }
}
</script>
