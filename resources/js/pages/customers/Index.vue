<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-6">
      <h1 class="text-2xl font-bold mb-6">Customers</h1>

      <!-- Search -->
      <div class="mb-4 flex gap-2">
        <input
          v-model="filters.search"
          @keyup.enter="applySearch"
          type="text"
          placeholder="Search by name, email or phone"
          class="border p-2 rounded w-full"
        />
        <button @click="applySearch" class="px-4 py-2 bg-blue-600 text-white rounded">Search</button>
      </div>

      <!-- Table -->
      <table class="w-full border">
        <thead>
          <tr class="bg-gray-900 text-white">
            <th class="p-2">#</th>
            <th class="p-2">Name</th>
            <th class="p-2">Email</th>
            <th class="p-2">Phone</th>
            <th class="p-2">Address</th>
            <th class="p-2">Status</th>
            <th class="p-2">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="customer in customers.data" :key="customer.id" class="border-t">
            <td class="p-2">{{ customer.id }}</td>
            <td class="p-2">{{ customer.name }}</td>
            <td class="p-2">{{ customer.email }}</td>
            <td class="p-2">{{ customer.phone || '-' }}</td>
            <td class="p-2">{{ customer.address || '-' }}</td>
            <td class="p-2">
              <span
                :class="customer.is_banned ? 'bg-red-200 text-red-800 px-2 py-1 rounded text-xs' : 'bg-green-200 text-green-800 px-2 py-1 rounded text-xs'"
              >
                {{ customer.is_banned ? 'Banned' : 'Active' }}
              </span>
            </td>
            <td class="p-2 flex gap-2">
              <button
                @click="toggleBan(customer)"
                :class="customer.is_banned ? 'bg-green-600' : 'bg-red-600'"
                class="text-white px-2 py-1 rounded"
              >
                {{ customer.is_banned ? 'Unban' : 'Ban' }}
              </button>
              <button
                @click="deleteCustomer(customer)"
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
          :disabled="!customers.prev_page_url"
          @click="changePage(customers.current_page - 1)"
          class="px-3 py-1 bg-gray-300 rounded disabled:opacity-50"
        >
          Prev
        </button>
        <span>Page {{ customers.current_page }} of {{ customers.last_page }}</span>
        <button
          :disabled="!customers.next_page_url"
          @click="changePage(customers.current_page + 1)"
          class="px-3 py-1 bg-gray-300 rounded disabled:opacity-50"
        >
          Next
        </button>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { ref } from "vue";
import { usePage, router } from "@inertiajs/vue3";
import AppLayout from "@/layouts/AppLayout.vue";
import { BreadcrumbItem } from "@/types";

const page = usePage();
const customers = ref(page.props.customers);

const filters = ref({
  search: page.props.filters?.search || "",
});

const breadcrumbs: BreadcrumbItem[] = [
  { title: "Customers", href: "/customers" },
];

// Apply search
function applySearch() {
  router.get("/customers", filters.value, { preserveState: true, replace: true });
}

// Change page
function changePage(pageNumber: number) {
  router.get("/customers", { ...filters.value, page: pageNumber }, { preserveState: true, replace: true });
}

// Toggle ban/unban
function toggleBan(customer: any) {
  router.post(`/customers/${customer.id}/toggle-ban`, {}, {
    onSuccess: () => {
      customer.is_banned = !customer.is_banned;
    },
  });
}

// Delete customer
function deleteCustomer(customer: any) {
  if (confirm(`Are you sure you want to delete ${customer.name}?`)) {
    router.delete(`/customers/${customer.id}`, {
      onSuccess: () => {
        // Remove deleted customer from table
        customers.value.data = customers.value.data.filter((c: any) => c.id !== customer.id);
      },
    });
  }
}
</script>
