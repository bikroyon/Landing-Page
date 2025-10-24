<script setup>
import { Head, router } from '@inertiajs/vue3'
import { reactive } from 'vue'

const form = reactive({
  name: '',
  region: '',
  area: '',
  delivery_fee: 0,
  free_delivery_min_order: null,
  status: true,
})

const submit = () => {
  router.post('/delivery-zones', form)
}
</script>

<template>
  <Head title="Create Delivery Zone" />

  <div class="p-6 max-w-lg mx-auto bg-white shadow rounded">
    <h1 class="text-2xl font-bold mb-4">Add New Delivery Zone</h1>

    <form @submit.prevent="submit" class="space-y-4">
      <div>
        <label class="block font-medium">Name</label>
        <input v-model="form.name" type="text" class="w-full border rounded px-3 py-2" />
      </div>

      <div>
        <label class="block font-medium">Region</label>
        <input v-model="form.region" type="text" class="w-full border rounded px-3 py-2" />
      </div>

      <div>
        <label class="block font-medium">Area</label>
        <input v-model="form.area" type="text" class="w-full border rounded px-3 py-2" />
      </div>

      <div>
        <label class="block font-medium">Delivery Fee</label>
        <input v-model.number="form.delivery_fee" type="number" min="0" class="w-full border rounded px-3 py-2" />
      </div>

      <div>
        <label class="block font-medium">Free Delivery Min Order</label>
        <input v-model.number="form.free_delivery_min_order" type="number" min="0" class="w-full border rounded px-3 py-2" />
      </div>

      <div>
        <label class="block font-medium">Status</label>
        <select v-model="form.status" class="w-full border rounded px-3 py-2">
          <option :value="true">Active</option>
          <option :value="false">Inactive</option>
        </select>
      </div>

      <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Create</button>
    </form>
  </div>
</template>
