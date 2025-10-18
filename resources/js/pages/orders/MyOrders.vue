<template>
  <AppLayout>
    <div class="py-10 px-4">
      <h1 class="text-2xl font-semibold mb-6">My Orders</h1>

      <div v-if="orders.data.length" class="space-y-6">
        <div v-for="order in orders.data" :key="order.id" class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-5">
          <div class="flex justify-between items-center border-b pb-3 mb-3">
            <h2 class="text-lg font-semibold">Order #{{ order.id }}</h2>
            <span class="text-sm font-medium px-3 py-1 rounded-full" :class="{
              'bg-green-100 text-green-800': order.status === 'completed',
              'bg-yellow-100 text-yellow-800': order.status === 'pending',
              'bg-gray-100 text-gray-800': order.status === 'processing',
            }">
              {{ order.status }}
            </span>
          </div>

          <div class="space-y-2">
            <p><strong>Name:</strong> {{ order.customer_name }}</p>
            <p><strong>Phone:</strong> {{ order.customer_phone }}</p>
            <p><strong>Total:</strong> ${{ order.total_amount }}</p>
          </div>

          <div class="mt-4">
            <h3 class="font-semibold mb-2">Items:</h3>
            <ul class="divide-y divide-gray-200">
              <li v-for="item in order.items" :key="item.id" class="py-2 flex justify-between">
                <span>{{ item.product?.name ?? 'Unknown Product' }}</span>
                <span>x{{ item.quantity }}</span>
                <span>${{ item.subtotal }}</span>
              </li>
            </ul>
          </div>
        </div>

        <!-- Pagination -->
        <div class="mt-6 flex justify-center">
          <div class="flex items-center space-x-2">
            <button :disabled="!orders.prev_page_url" @click="visit(orders.prev_page_url)"
              class="px-3 py-1 border rounded disabled:opacity-50">
              Prev
            </button>
            <button :disabled="!orders.next_page_url" @click="visit(orders.next_page_url)"
              class="px-3 py-1 border rounded disabled:opacity-50">
              Next
            </button>
          </div>
        </div>
      </div>

      <div v-else class="text-gray-500 text-center mt-10">
        You haven’t placed any orders yet.
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/layouts/AppLayout.vue'
import { router } from '@inertiajs/vue3'
const props = defineProps({
  orders: Object,
})

const visit = (url) => {
  if (url) router.visit(url)
}
</script>
