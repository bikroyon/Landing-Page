<template>
  <AppLayout title="Orders">
    <div class="container mx-auto p-6">
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Orders</h1>

        <!-- Search -->
        <form @submit.prevent="searchOrders" class="flex gap-2">
          <input
            type="text"
            v-model="search"
            placeholder="Search by name, email, or phone..."
            class="border rounded px-3 py-2 w-64"
          />
          <button
            type="submit"
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
          >
            Search
          </button>
        </form>
      </div>

      <!-- Orders List -->
      <div v-if="orders.data.length" class="space-y-6">
        <div
          v-for="order in orders.data"
          :key="order.id"
          class="p-4 border rounded-lg shadow-sm bg-white dark:bg-gray-800"
        >
          <div class="flex justify-between items-center mb-2">
            <h2 class="text-lg font-semibold">Order #{{ order.id }}</h2>
            <span
              class="text-sm px-2 py-1 rounded-full capitalize"
              :class="{
                'bg-yellow-100 text-yellow-800': order.status === 'pending',
                'bg-green-100 text-green-800': order.status === 'completed',
              }"
            >
              {{ order.status }}
            </span>
          </div>

          <div class="text-sm text-gray-600 mb-3">
            <p><strong>Customer:</strong> {{ order.customer_name }}</p>
            <p><strong>Phone:</strong> {{ order.customer_phone }}</p>
            <p><strong>Email:</strong> {{ order.customer_email }}</p>
            <p><strong>Address:</strong> {{ order.customer_address }}</p>
            <p><strong>Total Amount:</strong> {{ formatCurrency(order.total_amount) }}</p>
          </div>

          <!-- Order Items -->
          <table class="w-full text-left border-t mt-3">
            <thead>
              <tr class="border-b">
                <th class="py-2 px-2">Product</th>
                <th class="py-2 px-2">Type</th>
                <th class="py-2 px-2">Quantity</th>
                <th class="py-2 px-2">Price</th>
                <th class="py-2 px-2">Subtotal</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="item in order.items || []"
                :key="item.id"
                class="border-b"
              >
                <td class="py-2 px-2 flex items-center gap-2">
                  <img
                    v-if="item.product?.image"
                    :src="`/storage/${item.product.image}`"
                    alt=""
                    class="w-10 h-10 rounded"
                  />
                  {{ item.product?.name }}
                </td>
                <td class="py-2 px-2">{{ item.product?.product_type }}</td>
                <td class="py-2 px-2">{{ item.quantity }}</td>
                <td class="py-2 px-2">{{ formatCurrency(item.price) }}</td>
                <td class="py-2 px-2">{{ formatCurrency(item.subtotal) }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Pagination -->
      <div v-if="orders.links.length > 3" class="flex justify-center mt-6 space-x-2">
        <button
          v-for="(link, index) in orders.links"
          :key="index"
          :disabled="!link.url"
          @click="visit(link.url)"
          v-html="link.label"
          class="px-3 py-1 border rounded text-sm"
          :class="{
            'bg-blue-600 text-white': link.active,
            'text-gray-500': !link.url,
          }"
        ></button>
      </div>

      <div v-else class="text-center text-gray-500 mt-10">
        No orders found.
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { router } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps({
  orders: Object,
  filters: Object,
})

const search = ref(props.filters.search || '')

function searchOrders() {
  router.get(route('orders.index'), { search: search.value }, { preserveState: true })
}

function visit(url) {
  if (url) router.visit(url)
}

function formatCurrency(value) {
  return '৳' + Number(value).toFixed(2)
}
</script>
