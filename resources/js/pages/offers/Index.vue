<script setup>
import { Link, usePage } from '@inertiajs/vue3';

const props = defineProps({
  offers: Object
});
</script>

<template>
  <div class="p-6">
    <div class="flex justify-between items-center mb-4">
      <h1 class="text-2xl font-semibold">Offers</h1>
      <Link href="/offers/create" class="bg-blue-600 text-white px-4 py-2 rounded">+ New Offer</Link>
    </div>

    <table class="w-full border">
      <thead>
        <tr class="bg-gray-100 text-left">
          <th class="p-2">Code</th>
          <th class="p-2">Type</th>
          <th class="p-2">Discount</th>
          <th class="p-2">Status</th>
          <th class="p-2">Action</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="offer in offers.data" :key="offer.id" class="border-b">
          <td class="p-2">{{ offer.code || '—' }}</td>
          <td class="p-2 capitalize">{{ offer.offer_type }}</td>
          <td class="p-2">
            {{ offer.discount_value }}
            {{ offer.discount_type === 'percentage' ? '%' : '৳' }}
          </td>
          <td class="p-2">
            <span :class="offer.active ? 'text-green-600' : 'text-red-600'">
              {{ offer.active ? 'Active' : 'Inactive' }}
            </span>
          </td>
          <td class="p-2">
            <Link :href="`/offers/${offer.id}/edit`" class="text-blue-600">Edit</Link>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
