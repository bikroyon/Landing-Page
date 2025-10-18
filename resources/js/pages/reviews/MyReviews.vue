<template>
  <AppLayout title="My Reviews">
    <div class="max-w-5xl mx-auto py-6">
      <h1 class="text-2xl font-bold mb-6">My Product Reviews</h1>

      <!-- No completed orders -->
      <div v-if="!completedOrders.length" class="text-gray-500 text-center py-10">
        <p>You have no completed orders yet.</p>
        <p class="text-sm">You can only review products after completing an order.</p>
      </div>

      <!-- List of completed orders -->
      <div v-else>
        <div
          v-for="order in completedOrders"
          :key="order.id"
          class="mb-8 bg-white rounded-xl shadow p-4 border border-gray-100"
        >
          <h2 class="text-lg font-semibold mb-3">
            Order #{{ order.id }} • <span class="text-green-600">{{ order.status }}</span>
          </h2>

          <div v-for="item in order.items" :key="item.id" class="border-t pt-4 mt-4">
            <!-- Initialize review object -->
            <div v-if="!newReview[item.product.id]">{{ initReview(item.product.id) }}</div>

            <div class="flex items-start gap-4">
              <img
                v-if="item.product.image"
                :src="item.product.image"
                alt="Product Image"
                class="w-20 h-20 object-cover rounded-lg border"
              />
              <div class="flex-1">
                <h3 class="font-semibold text-gray-800">{{ item.product.name }}</h3>
                <p class="text-sm text-gray-500 mb-2">Quantity: {{ item.quantity }}</p>

                <!-- Existing Review -->
                <div v-if="item.product.review">
                  <div class="bg-gray-50 p-3 rounded-lg">
                    <div class="flex items-center gap-2">
                      <span class="font-semibold">Your Rating:</span>
                      <div class="flex gap-1">
                        <StarIcon
                          v-for="n in 5"
                          :key="n"
                          class="w-5 h-5"
                          :class="n <= item.product.review.rating ? 'text-yellow-400' : 'text-gray-300'"
                        />
                      </div>
                    </div>
                    <p class="text-gray-700 mt-1">{{ item.product.review.comment || 'No comment.' }}</p>
                    <button
                      @click="editReview(item.product)"
                      class="text-blue-600 text-sm mt-2 hover:underline"
                    >
                      Edit Review
                    </button>
                  </div>
                </div>

                <!-- New Review -->
                <div v-else class="mt-2">
                  <div class="flex items-center gap-2">
                    <span class="font-semibold">Your Rating:</span>
                    <div class="flex gap-1">
                      <StarIcon
                        v-for="n in 5"
                        :key="n"
                        class="w-6 h-6 cursor-pointer transition"
                        :class="n <= (newReview[item.product.id]?.rating || 0)
                          ? 'text-yellow-400'
                          : 'text-gray-300 hover:text-yellow-400'"
                        @click="setRating(item.product.id, n)"
                      />
                    </div>
                  </div>

                  <textarea
                    v-model="newReview[item.product.id].comment"
                    placeholder="Write your review..."
                    class="w-full mt-3 border rounded-lg p-2 focus:ring focus:ring-blue-200"
                    rows="3"
                  ></textarea>

                  <button
                    @click="submitReview(item.product)"
                    class="mt-3 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition"
                    :disabled="!newReview[item.product.id].rating"
                  >
                    Submit Review
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Edit Review Modal -->
      <div v-if="editingProduct" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-30">
        <div class="bg-white p-6 rounded-xl w-full max-w-md">
          <h3 class="text-lg font-semibold mb-3">Edit Your Review</h3>
          <div class="flex gap-1 mb-4">
            <StarIcon
              v-for="n in 5"
              :key="n"
              class="w-6 h-6 cursor-pointer"
              :class="n <= editData.rating ? 'text-yellow-400' : 'text-gray-300 hover:text-yellow-400'"
              @click="editData.rating = n"
            />
          </div>
          <textarea
            v-model="editData.comment"
            rows="4"
            class="w-full border rounded p-2 mb-4"
          ></textarea>
          <div class="flex justify-end gap-2">
            <button @click="editingProduct = null" class="px-4 py-2 rounded border">Cancel</button>
            <button @click="updateReview" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
              Update Review
            </button>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import { StarIcon } from 'lucide-vue-next'
import AppLayout from '@/Layouts/AppLayout.vue'

const { props } = usePage()
const completedOrders = props.orders || []

// Reactive objects for new reviews
const newReview = ref({})
const editingProduct = ref(null)
const editData = ref({ rating: 0, comment: '' })

// Ensure newReview object exists
const initReview = (productId) => {
  if (!newReview.value[productId]) {
    newReview.value[productId] = { rating: 0, comment: '' }
  }
}

// Set rating safely
const setRating = (productId, rating) => {
  if (!newReview.value[productId]) newReview.value[productId] = { rating: 0, comment: '' }
  newReview.value[productId].rating = rating
}

// Submit new review
const submitReview = (product) => {
  router.post(`/reviews/${product.id}`, newReview.value[product.id], {
    preserveScroll: true,
    onSuccess: () => {
      newReview.value[product.id] = { rating: 0, comment: '' }
    },
    onError: (err) => {
      alert(err.message || 'Error submitting review.')
    },
  })
}

// Edit review
const editReview = (product) => {
  editingProduct.value = product
  editData.value = {
    rating: product.review.rating,
    comment: product.review.comment || '',
  }
}

// Update review
const updateReview = () => {
  router.post(`/reviews/${editingProduct.value.review.id}`, editData.value, {
    preserveScroll: true,
    onSuccess: () => {
      editingProduct.value = null
    },
    onError: (err) => {
      alert(err.message || 'Error updating review.')
    },
  })
}
</script>
