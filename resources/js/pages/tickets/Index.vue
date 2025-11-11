<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    tickets: Array
});
</script>

<template>
  <div class="p-6">
    <div class="flex justify-between mb-4">
      <h1 class="text-xl font-bold">My Tickets</h1>
      <Link href="/tickets/create" class="bg-blue-500 text-white px-4 py-2 rounded">New Ticket</Link>
    </div>

    <table class="w-full border">
      <thead>
        <tr class="bg-gray-100">
          <th class="p-2">Subject</th>
          <th class="p-2">Order</th>
          <th class="p-2">Status</th>
          <th class="p-2">Action</th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="t in tickets" :key="t.id">
          <td class="p-2">{{ t.subject }}</td>
          <td class="p-2">#{{ t.order_id }}</td>
          <td class="p-2 capitalize">{{ t.status }}</td>
          <td class="p-2">
            <Link :href="`/tickets/${t.id}`" class="text-blue-600 underline">View</Link>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
