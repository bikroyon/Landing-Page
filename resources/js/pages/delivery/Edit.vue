<script setup>
import { Head, router } from '@inertiajs/vue3'
import { reactive } from 'vue'

const props = defineProps({
  zone: Object,
})

const form = reactive({
  name: props.zone.name,
  region: props.zone.region,
  area: props.zone.area,
  delivery_fee: props.zone.delivery_fee,
  free_delivery_min_order: props.zone.free_delivery_min_order,
  status: props.zone.status,
})

const submit = () => {
  // Use URL directly instead of route()
  router.put(`/delivery-zones/${props.zone.id}`, form)
}
</script>

<template>
  <Head title="Edit Delivery Zone" />

  <div class="p-6 max-w-lg mx-auto bg-white shadow rounded">
    <h1 class="text-2xl font-bold mb-4">Edit Delivery Zone</h1>

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

      <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update</button>
    </form>
  </div>
</template>
