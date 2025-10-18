<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <Head title="Edit Order" />

    <div class="p-4 space-y-4">
      <h1 class="text-2xl font-bold mb-4">Edit Order #{{ order.id }}</h1>

      <form @submit.prevent="submitOrder" class="space-y-4">
        <!-- Select Products & Quantities -->
        <SelectProducts
          :products="products"
          v-model:items="form.items"
        />

        <!-- Customer Info -->
        <CustomerInfo :form="form" />

        <!-- Delivery Zone -->
        <SelectDeliveryZone :zones="deliveryZones" :form="form" />

        <!-- Payment Method -->
        <SelectPaymentMethod :paymentMethods="paymentMethods" :form="form" />

        <!-- Coupon -->
        <CouponCode :form="form" :discount="discount" />

        <!-- Order Summary -->
        <OrderSummary :subtotal="subtotal" :deliveryFee="deliveryFee" :discount="discount" />

        <!-- Submit Button -->
        <button
          type="submit"
          class="mt-4 px-4 py-2 bg-blue-600 text-white rounded"
          :disabled="form.processing"
        >
          <span v-if="form.processing">Updating Order...</span>
          <span v-else>Update Order</span>
        </button>
      </form>

      <div v-if="form.success" class="mt-4 p-2 bg-green-200 rounded">
        {{ form.success }}
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { reactive, computed, ref, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import SelectProducts from '@/components/CheckoutForm/SelectProducts.vue';
import CustomerInfo from '@/components/CheckoutForm/CustomerInfo.vue';
import SelectDeliveryZone from '@/components/CheckoutForm/SelectDeliveryZone.vue';
import SelectPaymentMethod from '@/components/CheckoutForm/SelectPaymentMethod.vue';
import CouponCode from '@/components/CheckoutForm/CouponCode.vue';
import OrderSummary from '@/components/CheckoutForm/OrderSummary.vue';

const props = defineProps<{
  order: any,
  products: any[],
  deliveryZones: any[],
  paymentMethods: any[],
  offers: any[],
}>();

const breadcrumbs = [
  { title: 'Orders', href: '/orders' },
  { title: 'Edit', href: `/orders/${props.order.id}/edit` },
];

const form = useForm({
  customer_name: props.order.customer_name,
  customer_phone: props.order.customer_phone,
  customer_email: props.order.customer_email,
  customer_address: props.order.customer_address,
  items: props.order.items.map((item: any) => ({
    product_id: item.product_id,
    quantity: item.quantity,
  })),
  delivery_zone_id: props.order.delivery_zone_id,
  payment_method_id: props.order.payment_method_id,
  coupon_code: props.order.offer?.code || '',
  success: null,
  processing: false,
});

const discount = ref(props.order.offer?.discount_value || 0);

const deliveryFee = computed(() => {
  const zone = props.deliveryZones.find(z => z.id === form.delivery_zone_id);
  return zone ? zone.fee : 0;
});

const subtotal = computed(() =>
  form.items.reduce((sum, item) => {
    const product = props.products.find(p => p.id === item.product_id);
    return sum + (product?.price || 0) * item.quantity;
  }, 0)
);

watch(() => form.coupon_code, (code) => {
  const offer = props.offers.find(o => o.code === code);
  if (offer) {
    discount.value = offer.discount_type === 'fixed' ? offer.discount_value : subtotal.value * offer.discount_value / 100;
    if (offer.max_discount) discount.value = Math.min(discount.value, offer.max_discount);
  } else discount.value = 0;
});

function submitOrder() {
  form.put(`/orders/${props.order.id}`, {
    onSuccess: () => {
      form.success = 'Order updated successfully!';
    },
  });
}
</script>
