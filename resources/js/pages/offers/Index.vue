<template>
  <AppLayout>
    <div class="p-6">
      <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Offers</h1>
        <Link href="/marketing/offers/create" class="px-4 py-2 bg-green-600 text-white rounded">Create Offer</Link>
      </div>

      <table class="w-full border">
        <thead>
          <tr class="bg-gray-900 text-white">
            <th>ID</th>
            <th>Type</th>
            <th>Code</th>
            <th>Discount</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="offer in offers.data" :key="offer.id" class="border-t">
            <td>{{ offer.id }}</td>
            <td>{{ offer.offer_type }}</td>
            <td>{{ offer.code || '-' }}</td>
            <td>{{ offer.discount_type }} {{ offer.discount_value }}</td>
            <td class="flex gap-2">
              <Link :href="`/marketing/offers/${offer.id}/edit`" class="px-2 py-1 bg-blue-600 text-white rounded">Edit</Link>
              <button @click="deleteOffer(offer)" class="px-2 py-1 bg-red-600 text-white rounded">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Pagination -->
      <div class="mt-4 flex justify-end gap-2">
        <button :disabled="!offers.prev_page_url" @click="changePage(offers.current_page - 1)">Prev</button>
        <span>Page {{ offers.current_page }} of {{ offers.last_page }}</span>
        <button :disabled="!offers.next_page_url" @click="changePage(offers.current_page + 1)">Next</button>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { usePage, router, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const page = usePage();
const offers = ref(page.props.offers);

function changePage(pageNumber: number) {
  router.get('/offers', { page: pageNumber }, { preserveState: true, replace: true });
}

function deleteOffer(offer: any) {
  if (!confirm('Are you sure you want to delete this offer?')) return;

  router.delete(`/offers/${offer.id}`, {}, {
    onSuccess: () => {
      offers.value.data = offers.value.data.filter((o: any) => o.id !== offer.id);
    }
  });
}
</script>
