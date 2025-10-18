<template>
  <div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Thank You, {{ order.customer_name }}!</h1>
    <p>Your order has been placed successfully.</p>

    <div class="mt-4 border p-4 rounded">
      <h2 class="font-semibold mb-2">Order Details</h2>
      <p><strong>Phone:</strong> {{ order.customer_phone }}</p>
      <p><strong>Address:</strong> {{ order.customer_address }}</p>
      <p><strong>Payment Method:</strong> {{ order.payment_method }}</p>

      <h2 class="font-semibold mt-4 mb-2">Items</h2>
      <ul>
        <li v-for="item in order.items" :key="item.id" class="flex justify-between border-b py-1">
          <span>{{ item.product.name }} x {{ item.quantity }}</span>
          <span>${{ (item.price * item.quantity).toFixed(2) }}</span>
        </li>
      </ul>

      <p class="mt-2 text-lg font-bold">
        Total: ${{ order.items.reduce((sum, i) => sum + i.price * i.quantity, 0).toFixed(2) }}
      </p>
    </div>

    <!-- Single Button -->
    <div class="mt-4">
      <Link :href="buttonUrl">
        <Button>
          {{ userExists ? 'Login & Track My Order' : 'Register & Track My Order' }}
        </Button>
      </Link>
    </div>
  </div>
</template>

<script setup lang="ts">
import { defineProps, computed } from 'vue';
import Button from '@/components/ui/button/Button.vue';
import { Link } from '@inertiajs/vue3';

interface Product { id: number; name: string; price: number; }
interface OrderItem { id: number; product: Product; quantity: number; price: number; }
interface Order {
  id: number;
  customer_name: string;
  customer_email?: string;
  customer_phone: string;
  customer_address: string;
  payment_method: string;
  items: OrderItem[];
}

const props = defineProps<{ order: Order; userExists: boolean }>();
const order = props.order;
const userExists = props.userExists;

// Button URL logic
const buttonUrl = computed(() => {
  if (userExists) {
    // Existing user -> redirect to login with prefilled email
    const params = new URLSearchParams({ email: order.customer_email || '' });
    return `/login?${params.toString()}`;
  } else {
    // New user -> redirect to register with prefilled data
    const params = new URLSearchParams({
      name: order.customer_name,
      email: order.customer_email || '',
      phone: order.customer_phone,
      address: order.customer_address,
    });
    return `/register?${params.toString()}`;
  }
});
</script>
