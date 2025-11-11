<script setup>
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    orders: Array
});

const form = useForm({
    order_id: '',
    subject: '',
    category: '',
    message: '',
});
</script>

<template>
  <div class="p-6">
    <h1 class="text-xl font-bold mb-4">Create Support Ticket</h1>

    <form @submit.prevent="form.post('/tickets')">
      <label>Order</label>
      <select v-model="form.order_id" class="border p-2 w-full mb-4">
        <option value="">Select order</option>
        <option v-for="o in orders" :value="o.id">Order #{{ o.id }}</option>
      </select>

      <label>Subject</label>
      <input v-model="form.subject" class="border p-2 w-full mb-4" />

      <label>Category</label>
      <input v-model="form.category" class="border p-2 w-full mb-4" />

      <label>Message</label>
      <textarea v-model="form.message" class="border p-2 w-full mb-4"></textarea>

      <button class="bg-blue-600 text-white px-5 py-2 rounded">Submit</button>
    </form>
  </div>
</template>
