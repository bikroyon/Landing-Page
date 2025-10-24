<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <Head title="Edit Product" />

    <div class="p-6">
      <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Edit Product</h1>
        <Button @click="submit" :disabled="form.processing">Update</Button>
      </div>

      <form @submit.prevent="submit" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <!-- Left -->
          <div class="space-y-4 md:col-span-2">
            <!-- Name -->
            <div>
              <label class="block font-medium mb-1">Name</label>
              <Input v-model="form.name" type="text" required class="w-full border p-2 rounded" />
              <div v-if="form.errors.name" class="text-red-500 text-sm">{{ form.errors.name }}</div>
            </div>

            <!-- Price -->
            <div>
              <label class="block font-medium mb-1">Price</label>
              <Input v-model="form.price" type="number" step="0.01" class="w-full border p-2 rounded" />
            </div>

            <!-- Status -->
            <div>
              <label class="block font-medium mb-1">Status</label>
              <ToggleSwitch v-model="form.status" />
            </div>

            <!-- Variations -->
            <PriceVariations v-model:variations="form.variations" />
          </div>

          <!-- Right: Image -->
          <div class="flex items-start justify-center mt-10">
            <ImageUploader
              v-model="form.image"
              :existing-url="product.image ? `/${product.image}` : null"
              label="Product Image"
            />
          </div>
        </div>

        <!-- Description -->
        <div>
          <label class="block font-medium mb-1">Description</label>
          <textarea
            v-model="form.description"
            rows="4"
            class="w-full border p-2 rounded"
          ></textarea>
        </div>
      </form>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import { Head, useForm, router } from "@inertiajs/vue3";
import Button from "@/components/ui/button/Button.vue";
import Input from "@/components/ui/input/Input.vue";
import ToggleSwitch from "@/components/ToggleSwitch.vue";
import PriceVariations from "@/components/PriceVariations.vue";
import ImageUploader from "@/components/ImageUploader.vue";

const { product } = defineProps({
  product: {
    type: Object,
    required: true,
  },
});

const breadcrumbs = [
  { title: "Products", href: "/products" },
  { title: "Edit", href: `/products/${product.id}/edit` },
];

const form = useForm({
  name: product.name || "",
  description: product.description || "",
  price: product.price || "",
  image: "", // file will be uploaded if selected
  status: product.status ?? true,
  variations: product.variations || [],
});

function submit() {
  const formData = new FormData();
  formData.append("name", form.name);
  formData.append("description", form.description);
  formData.append("price", form.price || 0);
  formData.append("status", form.status ? 1 : 0);
  formData.append("variations", JSON.stringify(form.variations));

  // Add image only if changed
  if (form.image instanceof File) {
    formData.append("image", form.image);
  }

  // IMPORTANT: Tell Laravel this is PUT
  formData.append("_method", "PUT");

  router.post(`/products/${product.id}`, formData, {
    forceFormData: true,
    onSuccess: () => {
      console.log("Product updated!");
    },
  });
}

</script>
