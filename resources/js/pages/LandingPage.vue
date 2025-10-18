<template>
  <div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Our Products</h1>

    <form @submit.prevent="submitOrder" class="space-y-4">
      <!-- Select Products -->
      <SelectProducts :products="products" v-model:items="form.items" />

      <!-- Customer Info -->
      <CustomerInfo :form="form" :readonly="!!user" />

      <!-- Delivery Zone -->
      <SelectDeliveryZone :zones="deliveryZones" :form="form" />

      <!-- Payment Method -->
      <SelectPaymentMethod :paymentMethods="paymentMethods" :form="form" />

      <!-- Coupon -->
      <CouponCode :form="form" :discount="discount" :offers="offers" />

      <!-- Order Summary -->
      <OrderSummary :subtotal="subtotal" :deliveryFee="deliveryFee" :discount="discount" />

      <!-- Submit Button -->
      <button
        type="submit"
        class="mt-4 px-4 py-2 bg-blue-600 text-white rounded"
        :disabled="!hasSelectedItems || form.processing"
      >
        <span v-if="form.processing">Placing Order...</span>
        <span v-else>Place Order</span>
      </button>
    </form>
  </div>
</template>

<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';
import SelectProducts from '@/components/CheckoutForm/SelectProducts.vue';
import CustomerInfo from '@/components/CheckoutForm/CustomerInfo.vue';
import SelectDeliveryZone from '@/components/CheckoutForm/SelectDeliveryZone.vue';
import SelectPaymentMethod from '@/components/CheckoutForm/SelectPaymentMethod.vue';
import CouponCode from '@/components/CheckoutForm/CouponCode.vue';
import OrderSummary from '@/components/CheckoutForm/OrderSummary.vue';
import type { Product, DeliveryZone, PaymentMethod } from '@/types';

const props = defineProps<{
  user?: { name: string; email: string; phone: string; address: string } | null;
  products?: Product[];
  deliveryZones?: DeliveryZone[];
  paymentMethods?: PaymentMethod[];
  offers?: any[];
}>();

const user = props.user || null;
const products = computed(() => props.products || []);
const deliveryZones = computed(() => props.deliveryZones || []);
const paymentMethods = computed(() => props.paymentMethods || []);
const offers = computed(() => props.offers || []);

const form = useForm({
  customer_name: user?.name || '',
  customer_phone: user?.phone || '',
  customer_email: user?.email || '',
  customer_address: user?.address || '',
  items: [] as { product_id: number; quantity: number }[],
  delivery_zone_id: null as number | null,
  payment_method_id: null as number | null,
  coupon_code: '',
});

const discount = ref(0);

const subtotal = computed(() =>
  form.items.reduce((sum, item) => {
    const product = products.value.find(p => p.id === item.product_id);
    return sum + (product?.price || 0) * item.quantity;
  }, 0)
);

const deliveryFee = computed(() => {
  const zone = deliveryZones.value.find(z => z.id === form.delivery_zone_id);
  return zone ? zone.fee : 0;
});

const hasSelectedItems = computed(() => form.items.length > 0);

// Watch coupon changes
watch(() => form.coupon_code, (code) => {
  const offer = offers.value.find(o => o.code === code);
  if (offer) {
    discount.value = offer.discount_type === 'fixed'
      ? offer.discount_value
      : subtotal.value * offer.discount_value / 100;
    if (offer.max_discount) discount.value = Math.min(discount.value, offer.max_discount);
  } else {
    discount.value = 0;
  }
});

// Submit order
function submitOrder() {
  if (!hasSelectedItems.value) {
    alert('Select at least one product.');
    return;
  }

  form.post('/order', {
    onError: (errors) => {
      console.error(errors);
    },
    onSuccess: (page) => {
      // Optionally redirect to thank you page
      console.log('Order placed successfully');
    }
  });
}
</script>
