<template>
  <AppLayout title="My Reviews">
    <div class="mx-auto max-w-5xl py-6">
      <h1 class="mb-6 text-2xl font-bold">My Product Reviews</h1>

      <!-- No completed orders -->
      <div v-if="!completedOrders.length" class="py-10 text-center text-gray-500">
        <p>You have no completed orders yet.</p>
        <p class="text-sm">You can only review products after completing an order.</p>
      </div>

      <!-- Completed Orders List -->
      <div v-else>
        <div v-for="order in completedOrders" :key="order.id" class="mb-8 rounded-xl border border-gray-100 bg-white p-4 shadow">
          <h2 class="mb-3 text-lg font-semibold">
            Order #{{ order.id }} • <span class="text-green-600">{{ order.status }}</span>
          </h2>

          <div v-for="item in order.items" :key="item.id" class="mt-4 border-t pt-4">

            <div class="flex items-start gap-4">
              <img v-if="item.product.image" :src="item.product.image" class="h-20 w-20 rounded-lg border object-cover" />

              <div class="flex-1">
                <h3 class="font-semibold text-gray-800">{{ item.product.name }}</h3>
                <p class="mb-2 text-sm text-gray-500">Quantity: {{ item.quantity }}</p>

                <!-- Display existing review or edit form -->
                <div v-if="reviewsState[reviewKey(item.product.id, order.id)] && !editingKey[item.product.id + '-' + order.id]">
                  <!-- Details view -->
                  <div class="rounded-lg bg-gray-50 p-3">
                    <div class="flex items-center gap-2">
                      <span class="font-semibold">Your Rating:</span>
                      <div class="flex gap-1">
                        <StarIcon
                          v-for="n in 5"
                          :key="n"
                          class="h-5 w-5"
                          :class="n <= (reviewsState[reviewKey(item.product.id, order.id)]?.rating || 0)
                            ? 'text-yellow-400'
                            : 'text-gray-300'"
                        />
                      </div>
                    </div>
                    <p class="mt-1 text-gray-700">{{ reviewsState[reviewKey(item.product.id, order.id)]?.comment || 'No comment.' }}</p>
                    <button @click="startEdit(item.product.id, order.id)" class="mt-2 text-sm text-blue-600 hover:underline">
                      Edit Review
                    </button>
                  </div>
                </div>

                <!-- Edit / New Review Form -->
                <div v-else>
                  <div class="flex items-center gap-2">
                    <span class="font-semibold">Your Rating:</span>
                    <div class="flex gap-1">
                      <StarIcon
                        v-for="n in 5"
                        :key="n"
                        class="h-6 w-6 cursor-pointer transition"
                        :class="n <= (reviewsState[reviewKey(item.product.id, order.id)]?.rating || 0)
                          ? 'text-yellow-400'
                          : 'text-gray-300 hover:text-yellow-400'"
                        @click="setRating(item.product.id, order.id, n)"
                      />
                    </div>
                  </div>

                  <textarea
                    v-model="reviewsState[reviewKey(item.product.id, order.id)].comment"
                    placeholder="Write your review..."
                    rows="3"
                    class="mt-3 w-full rounded-lg border p-2 focus:ring focus:ring-blue-200"
                  ></textarea>

                  <button
                    @click="submitReview(item.product, order.id)"
                    class="mt-3 rounded bg-blue-600 px-4 py-2 text-white transition hover:bg-blue-700"
                    :disabled="!(reviewsState[reviewKey(item.product.id, order.id)]?.rating > 0)"
                  >
                    {{ editingKey[item.product.id + '-' + order.id] ? 'Update Review' : 'Submit Review' }}
                  </button>
                  <button
                    v-if="editingKey[item.product.id + '-' + order.id]"
                    @click="cancelEdit(item.product.id, order.id)"
                    class="mt-3 ml-2 rounded border px-4 py-2 text-gray-700 hover:bg-gray-100"
                  >
                    Cancel
                  </button>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { usePage, useForm } from '@inertiajs/vue3';
import { ref, reactive } from 'vue';
import { StarIcon } from 'lucide-vue-next';

const { props } = usePage();
const completedOrders = props.orders || [];

// State to store review details keyed by productId-orderId
const reviewsState = reactive({});

// Track which review is currently in edit mode
const editingKey = reactive({});

// Utility function for keys
const reviewKey = (productId, orderId) => `${productId}-${orderId}`;

// Initialize reviewsState from existing reviews
completedOrders.forEach(order => {
  order.items.forEach(item => {
    const key = reviewKey(item.product.id, order.id);
    const existingReview = item.product.reviews.find(r => r.order_id === order.id);
    reviewsState[key] = {
      rating: existingReview?.rating || 0,
      comment: existingReview?.comment || '',
      id: existingReview?.id || null,
    };
  });
});

// Set rating
const setRating = (productId, orderId, rating) => {
  const key = reviewKey(productId, orderId);
  if (!reviewsState[key]) reviewsState[key] = { rating: 0, comment: '', id: null };
  reviewsState[key].rating = rating;
};

// Start editing
const startEdit = (productId, orderId) => {
  editingKey[productId + '-' + orderId] = true;
};

// Cancel editing
const cancelEdit = (productId, orderId) => {
  const key = reviewKey(productId, orderId);
  editingKey[productId + '-' + orderId] = false;
  // Reset to previous state
  const order = completedOrders.find(o => o.items.some(i => i.product.id === productId));
  const item = order.items.find(i => i.product.id === productId);
  const existingReview = item.product.reviews.find(r => r.order_id === order.id);
  reviewsState[key].rating = existingReview?.rating || 0;
  reviewsState[key].comment = existingReview?.comment || '';
};

// Submit or update review
const submitReview = (product, orderId) => {
  const key = reviewKey(product.id, orderId);
  const form = useForm({ ...reviewsState[key], order_id: orderId });

  const url = reviewsState[key].id ? `/reviews/${reviewsState[key].id}` : `/reviews/${product.id}`;
  const method = reviewsState[key].id ? 'put' : 'post';

  form[method](url, {
    preserveScroll: true,
    onSuccess: (page) => {
      // Update local state after success
      reviewsState[key].id = form.data.id || reviewsState[key].id;
      editingKey[product.id + '-' + orderId] = false;
    },
  });
};
</script>
