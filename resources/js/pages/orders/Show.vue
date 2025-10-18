<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <Head title="Order Details" />

    <div class="p-4 space-y-4">
      <h1 class="text-2xl font-bold">Order #{{ order.id }}</h1>

      <div class="border p-4 rounded bg-gray-50">
        <h2 class="font-semibold text-lg mb-2">Customer Info</h2>
        <p><strong>Name:</strong> {{ order.customer_name }}</p>
        <p><strong>Phone:</strong> {{ order.customer_phone }}</p>
        <p><strong>Email:</strong> {{ order.customer_email }}</p>
        <p><strong>Address:</strong> {{ order.customer_address }}</p>
      </div>

      <div class="border p-4 rounded bg-gray-50">
        <h2 class="font-semibold text-lg mb-2">Products</h2>
        <table class="min-w-full border rounded">
          <thead class="bg-gray-100">
            <tr>
              <th class="p-2 text-left">Product</th>
              <th class="p-2 text-center">Quantity</th>
              <th class="p-2 text-right">Price</th>
              <th class="p-2 text-right">Subtotal</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in order.items" :key="item.id" class="border-t">
              <td class="p-2">{{ item.product.name }}</td>
              <td class="p-2 text-center">{{ item.quantity }}</td>
              <td class="p-2 text-right">${{ item.price.toFixed(2) }}</td>
              <td class="p-2 text-right">${{ item.subtotal.toFixed(2) }}</td>
            </tr>
          </tbody>
        </table>
      </div>

      <OrderSummary
        :subtotal="order.subtotal"
        :deliveryFee="order.deliveryZone?.fee || 0"
        :discount="order.offer ? (order.offer.discount_type === 'fixed' ? order.offer.discount_value : order.subtotal * order.offer.discount_value / 100) : 0"
      />

      <div class="flex gap-2 mt-4">
        <Link :href="`/orders/${order.id}/edit`">
          <button class="px-4 py-2 bg-blue-600 text-white rounded">Edit</button>
        </Link>
        <button @click="deleteOrder(order.id)" class="px-4 py-2 bg-red-600 text-white rounded">Delete</button>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import OrderSummary from '@/components/CheckoutForm/OrderSummary.vue';

const props = defineProps<{ order: any }>();

const breadcrumbs = [
  { title: 'Orders', href: '/orders' },
  { title: 'View', href: `/orders/${props.order.id}` },
];

const form = useForm({});

function deleteOrder(id: number) {
  if (confirm('Are you sure you want to delete this order?')) {
    form.delete(`/orders/${id}`);
  }
}
</script>
