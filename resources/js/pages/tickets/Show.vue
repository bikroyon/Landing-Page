<script setup>
const props = defineProps({
    ticket: Object
});
</script>

<template>
  <div class="p-6">
    <h1 class="text-xl font-bold mb-2">{{ ticket.subject }}</h1>
    <p><b>Order:</b> #{{ ticket.order_id }}</p>
    <p><b>Status:</b> {{ ticket.status }}</p>

    <div class="mt-4 border p-3">
      <h3 class="font-bold mb-2">Your Message</h3>
      <p>{{ ticket.message }}</p>
    </div>

    <div class="mt-4 border p-3" v-if="ticket.reply">
      <h3 class="font-bold mb-2">Admin Reply</h3>
      <p>{{ ticket.reply }}</p>
    </div>
  </div>
</template>
