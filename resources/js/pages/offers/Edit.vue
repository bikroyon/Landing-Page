<script setup>
import { useForm, Link } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
  offer: Object,
  products: Array
})

// Prepare form with existing offer data
const form = useForm({
  offer_type: props.offer.offer_type || 'product',
  code: props.offer.code || '',
  discount_type: props.offer.discount_type || 'percentage',
  discount_value: props.offer.discount_value || '',
  min_order_amount: props.offer.min_order_amount || '',
  max_discount: props.offer.max_discount || '',
  product_id: props.offer.product_id ? JSON.parse(props.offer.product_id) : [],
  usage_limit: props.offer.usage_limit || '',
  starts_at: props.offer.starts_at ? props.offer.starts_at.replace(' ', 'T') : '',
  expires_at: props.offer.expires_at ? props.offer.expires_at.replace(' ', 'T') : '',
  active: props.offer.active
})

const isPercentage = computed(() => form.discount_type === 'percentage')

const submit = () => {
  form.put(route('offers.update', props.offer.id))
}
</script>

<template>
  <div class="p-6 max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-semibold">Edit Offer</h1>
      <Link href="/offers" class="text-blue-600 hover:underline">← Back</Link>
    </div>

    <form @submit.prevent="submit" class="space-y-5 bg-white p-6 rounded-lg shadow">
      <!-- Offer Type -->
      <div>
        <label class="block font-medium mb-1">Offer Type</label>
        <select v-model="form.offer_type" class="w-full border rounded p-2">
          <option value="coupon">Coupon</option>
          <option value="product">Product</option>
          <option value="cart">Cart</option>
        </select>
      </div>

      <!-- Code -->
      <div v-if="form.offer_type !== 'product'">
        <label class="block font-medium mb-1">Offer Code</label>
        <input v-model="form.code" class="w-full border rounded p-2" placeholder="e.g. SAVE20" />
      </div>

      <!-- Discount Type -->
      <div>
        <label class="block font-medium mb-1">Discount Type</label>
        <select v-model="form.discount_type" class="w-full border rounded p-2">
          <option value="percentage">Percentage</option>
          <option value="fixed">Fixed</option>
        </select>
      </div>

      <!-- Discount Value -->
      <div>
        <label class="block font-medium mb-1">
          Discount Value ({{ isPercentage ? '%' : '৳' }})
        </label>
        <input
          v-model="form.discount_value"
          type="number"
          step="0.01"
          class="w-full border rounded p-2"
        />
      </div>

      <!-- Min / Max -->
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="block font-medium mb-1">Min Order Amount</label>
          <input v-model="form.min_order_amount" type="number" class="w-full border rounded p-2" />
        </div>
        <div>
          <label class="block font-medium mb-1">Max Discount</label>
          <input v-model="form.max_discount" type="number" class="w-full border rounded p-2" />
        </div>
      </div>

      <!-- Product Selection -->
      <div>
        <label class="block font-medium mb-2">Apply to Products</label>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-2 border rounded p-3 max-h-60 overflow-y-auto">
          <label
            v-for="p in products"
            :key="p.id"
            class="flex items-center space-x-2 border rounded p-2 hover:bg-gray-50"
          >
            <input type="checkbox" :value="p.id" v-model="form.product_id" />
            <span>{{ p.name }} — ৳{{ p.price }}</span>
          </label>
        </div>
      </div>

      <!-- Usage Limit -->
      <div>
        <label class="block font-medium mb-1">Usage Limit</label>
        <input v-model="form.usage_limit" type="number" class="w-full border rounded p-2" />
      </div>

      <!-- Dates -->
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="block font-medium mb-1">Starts At</label>
          <input v-model="form.starts_at" type="datetime-local" class="w-full border rounded p-2" />
        </div>
        <div>
          <label class="block font-medium mb-1">Expires At</label>
          <input v-model="form.expires_at" type="datetime-local" class="w-full border rounded p-2" />
        </div>
      </div>

      <!-- Active -->
      <div class="flex items-center space-x-2">
        <input type="checkbox" v-model="form.active" />
        <span>Active</span>
      </div>

      <!-- Submit -->
      <div class="flex justify-end">
        <button
          type="submit"
          :disabled="form.processing"
          class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded transition"
        >
          {{ form.processing ? 'Updating...' : 'Update Offer' }}
        </button>
      </div>
    </form>
  </div>
</template>