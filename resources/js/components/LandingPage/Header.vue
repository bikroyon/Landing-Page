<script setup>
import { useToast } from '@/composables/useToast.ts'

const { showToast } = useToast()

function notify() {
  showToast('Success!', 'Your order has been placed.', 'success')
}
</script>

<template>
  <button @click="notify" class="bg-green-500 text-white px-4 py-2 rounded">
    Show Toast
  </button>
</template>
