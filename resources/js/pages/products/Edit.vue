<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-6">
      <h1 class="text-2xl font-bold mb-4">Edit Product</h1>

      <form @submit.prevent="submit" class="space-y-4">
        <div>
          <label class="block">Name</label>
          <input v-model="form.name" type="text" class="border p-2 w-full rounded" />
          <span v-if="form.errors.name" class="text-red-500">{{ form.errors.name }}</span>
        </div>

        <div>
          <label class="block">Description</label>
          <textarea v-model="form.description" class="border p-2 w-full rounded"></textarea>
          <span v-if="form.errors.description" class="text-red-500">{{ form.errors.description }}</span>
        </div>

        <div>
          <label class="block">Price</label>
          <input v-model="form.price" type="number" step="0.01" class="border p-2 w-full rounded" />
          <span v-if="form.errors.price" class="text-red-500">{{ form.errors.price }}</span>
        </div>

        <div>
          <label class="block">Stock</label>
          <input v-model="form.stock" type="number" class="border p-2 w-full rounded" />
          <span v-if="form.errors.stock" class="text-red-500">{{ form.errors.stock }}</span>
        </div>

        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded" :disabled="form.processing">
          Update
        </button>
      </form>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { useForm, usePage } from "@inertiajs/vue3";
import AppLayout from "@/layouts/AppLayout.vue";
import { BreadcrumbItem } from "@/types";

const page = usePage();
const product = page.props.product;

const breadcrumbs: BreadcrumbItem[] = [
  { title: "Products", href: "/products" },
  { title: "Edit", href: "#" },
];

const form = useForm({
  name: product.name || "",
  description: product.description || "",
  price: product.price || 0,
  stock: product.stock || 0,
});

function submit() {
  form.put(`/products/${product.id}`);
}
</script>
