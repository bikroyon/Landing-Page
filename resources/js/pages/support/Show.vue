<script setup>
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
  ticket: Object
});

const statusForm = useForm({ status: props.ticket.status });
const replyForm = useForm({ reply: props.ticket.reply || '' });
</script>

<template>
  <div class="p-6">
    <h1 class="text-xl font-bold mb-4">Manage Ticket</h1>

    <p><b>Subject:</b> {{ ticket.subject }}</p>
    <p><b>User:</b> {{ ticket.user.name }}</p>
    <p><b>Order:</b> #{{ ticket.order_id }}</p>

    <div class="mt-4 border p-3">
      <h3 class="font-bold">Customer Message</h3>
      <p>{{ ticket.message }}</p>
    </div>

    <!-- Admin Reply -->
    <div class="mt-4 border p-3">
      <h3 class="font-bold mb-2">Admin Reply</h3>

      <textarea v-model="replyForm.reply" class="border p-2 w-full"></textarea>

      <button
        class="mt-2 bg-blue-600 text-white px-4 py-2 rounded"
        @click="replyForm.put(`/supports/${ticket.id}/reply`)"
      >
        Save Reply
      </button>
    </div>

    <!-- Change Status -->
    <div class="mt-4">
      <h3 class="font-bold mb-2">Change Status</h3>

      <select v-model="statusForm.status" class="border p-2">
        <option value="open">Open</option>
        <option value="working">Working</option>
        <option value="closed">Closed</option>
      </select>

      <button
        class="ml-2 bg-green-600 text-white px-4 py-2 rounded"
        @click="statusForm.put(`/supports/${ticket.id}/status`)"
      >
        Update
      </button>
    </div>
  </div>
</template>
