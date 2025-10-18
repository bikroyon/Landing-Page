<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-6">
      <h1 class="text-2xl font-bold mb-6">Reviews</h1>

      <!-- Search -->
      <div class="mb-4 flex gap-2">
        <input
          v-model="filters.search"
          @keyup.enter="applySearch"
          type="text"
          placeholder="Search by user or product"
          class="border p-2 rounded w-full"
        />
        <button @click="applySearch" class="px-4 py-2 bg-blue-600 text-white rounded">Search</button>
      </div>

      <!-- Reviews Table -->
      <table class="w-full border">
        <thead>
          <tr class="bg-gray-900 text-white">
            <th class="p-2">#</th>
            <th class="p-2">User</th>
            <th class="p-2">Product</th>
            <th class="p-2">Rating</th>
            <th class="p-2">Comment</th>
            <th class="p-2">Published</th>
            <th class="p-2">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="review in reviews.data" :key="review.id" class="border-t">
            <td class="p-2">{{ review.id }}</td>
            <td class="p-2">{{ review.user.name }}</td>
            <td class="p-2">{{ review.product.name }}</td>
            <td class="p-2">{{ review.rating }}</td>
            <td class="p-2">{{ review.comment || '-' }}</td>
            <td class="p-2">
              <span
                :class="review.is_published ? 'bg-green-200 text-green-800 px-2 py-1 rounded text-xs' : 'bg-red-200 text-red-800 px-2 py-1 rounded text-xs'"
              >
                {{ review.is_published ? 'Published' : 'Unpublished' }}
              </span>
            </td>
            <td class="p-2 flex gap-2">
              <button
                @click="togglePublish(review)"
                :class="review.is_published ? 'bg-red-600' : 'bg-green-600'"
                class="text-white px-2 py-1 rounded"
              >
                {{ review.is_published ? 'Unpublish' : 'Publish' }}
              </button>
              <button
                @click="deleteReview(review)"
                class="bg-gray-600 text-white px-2 py-1 rounded"
              >
                Delete
              </button>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Pagination -->
      <div class="mt-4 flex justify-end gap-2">
        <button
          :disabled="!reviews.prev_page_url"
          @click="changePage(reviews.current_page - 1)"
          class="px-3 py-1 bg-gray-300 rounded disabled:opacity-50"
        >
          Prev
        </button>
        <span>Page {{ reviews.current_page }} of {{ reviews.last_page }}</span>
        <button
          :disabled="!reviews.next_page_url"
          @click="changePage(reviews.current_page + 1)"
          class="px-3 py-1 bg-gray-300 rounded disabled:opacity-50"
        >
          Next
        </button>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';

const page = usePage();
const reviews = ref(page.props.reviews);

const filters = ref({
  search: page.props.filters?.search || '',
});

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Reviews', href: '/admin/reviews' },
];

// Apply search
function applySearch() {
  router.get('/admin/reviews', filters.value, { preserveState: true, replace: true });
}

// Change page
function changePage(pageNumber: number) {
  router.get('/admin/reviews', { ...filters.value, page: pageNumber }, { preserveState: true, replace: true });
}

// Toggle publish/unpublish
function togglePublish(review: any) {
  router.post(`/admin/reviews/${review.id}/toggle-publish`, {}, {
    onSuccess: () => {
      review.is_published = !review.is_published;
    },
  });
}

// Delete review
function deleteReview(review: any) {
  if (!confirm('Are you sure you want to delete this review?')) return;

  router.delete(`/admin/reviews/${review.id}`, {}, {
    onSuccess: () => {
      reviews.value.data = reviews.value.data.filter((r: any) => r.id !== review.id);
    },
  });
}
</script>
