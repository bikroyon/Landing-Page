<template>
  <AppLayout :breadcrumbs="breadcrumbs">

    <Head title="All Products" />
    <div class="p-4 py-2">
      <div>
        <h1 class="text-2xl font-bold mb-4">Products</h1>
      </div>

      <!-- Search -->
      <div class="flex justify-between">
        <form @submit.prevent="searchProducts" class="mb-4 flex gap-2">
          <Input type="text" v-model="search" placeholder="Search products..." class="border p-2 rounded w-64" />
          <Button type="submit" class="">
            Search
          </Button>
        </form>

        <div class="flex gap-2">
          <Button>Export
            <Icon name="Printer" />
          </Button>
          <Link href="/products/create">
          <Button>Add New Product
            <Icon name="Plus" />
          </Button>
          </Link>
        </div>
      </div>

      <!-- Products Table -->
      <div class="overflow-x-auto">
        <table class="min-w-full border rounded-lg">
          <thead class="bg-gray-100">
            <tr>
              <th class="p-2 text-center">ID</th>
              <th class="p-2 text-left">Product</th>
              <th class="p-2 text-left">Product Type</th>
              <th class="p-2 text-left">Price</th>
              <th class="p-2 text-left">Status</th>
              <th class="p-2 text-center">Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="product in products.data" :key="product.id" class="border-t hover:bg-gray-50 transition">
              <td class="p-2 text-sm text-center">{{ product.id }}</td>
              <td class="p-2 flex items-center gap-2">
                <img src="https://placehold.co/600x400" alt="product" class="w-10 h-10 rounded object-cover" />
                <div class="flex flex-col">
                  <span class="font-medium truncate w-40 sm:w-60">
                    {{ product.name || 'Product Name' }}
                  </span>
                  <span class="text-xs text-gray-500 truncate w-40 sm:w-60">
                    {{ product.description || 'Short description...' }}
                  </span>
                </div>
              </td>
                      <td class="p-2 text-sm">{{ product.product_type }}</td>
              <td class="p-2 text-sm">${{ Number(product.price).toFixed(2) }}</td>
      
              <td class="p-2 text-sm">{{ product.statusa }}</td>
              <td class="p-2 text-center">
                <!-- Edit -->
                <Link :href="`/products/${product.id}/edit`">
                <Button size="sm" variant="outline" class="space-x-2">
                  <Icon name="Edit" />
                </Button>
                </Link>

                <!-- Delete -->
                <Button size="sm" class="ml-2" variant="destructive" @click="deleteProduct(product.id)">
                  <Icon name="Trash2" />
                </Button>
                <!-- View -->
                <Button size="sm" class="ml-2">
                  <Icon name="Eye" />
                </Button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Pagination -->
    <div class="p-4 flex justify-center">
      <Component :is="products.links.length ? 'nav' : 'div'" class="flex gap-1">
        <template v-for="(link, key) in products.links" :key="key">
          <Button v-if="link.url" @click="router.visit(link.url)" v-html="link.label" :class="[
            'px-3 py-1 border rounded',
            link.active ? 'text-white' : 'bg-white text-gray-700 hover:bg-gray-100'
          ]" />
          <span v-else v-html="link.label" class="px-3 py-1 text-gray-400" />
        </template>
      </Component>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { ref } from "vue";
import { router, usePage, useForm, Link, Head } from "@inertiajs/vue3";
import AppLayout from "@/layouts/AppLayout.vue";
import { BreadcrumbItem } from "@/types";
import Input from "@/components/ui/input/Input.vue";
import Button from "@/components/ui/button/Button.vue";
import Icon from "@/components/Icon.vue";

const breadcrumbs: BreadcrumbItem[] = [
  { title: "Products", href: "/products" },
];

const page = usePage();
const products = page.props.products;
const filters = page.props.filters;

const search = ref(filters.search || "");

// Delete form
const form = useForm({});

function deleteProduct(id: number) {
  if (confirm("Are you sure you want to delete this product?")) {
    form.delete(`/products/${id}`, {
      preserveState: false, // force refresh page props
    });
  }
}


// Search
function searchProducts() {
  router.get("/products", { search: search.value }, { preserveState: true });
}

// Pagination
function goTo(url: string | null) {
  if (url) {
    router.get(url, {}, { preserveState: true });
  }
}
</script>
