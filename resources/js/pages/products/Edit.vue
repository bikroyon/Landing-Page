<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <Head title="Edit Product" />

    <div class="p-4 sm:p-6">
      <!-- Header -->
      <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <h1 class="text-2xl font-bold">Edit Product</h1>
        <Button @click="submit" :disabled="form.processing" class="w-full sm:w-auto">
          Update
        </Button>
      </div>

      <form @submit.prevent="submit" class="space-y-6">
        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
          <!-- Left Section -->
          <div class="space-y-4 md:col-span-2">
            <!-- Name -->
            <div>
              <label class="mb-1 block font-medium">Name</label>
              <Input
                v-model="form.name"
                type="text"
                required
                class="w-full rounded border p-2"
              />
              <div v-if="form.errors.name" class="text-sm text-red-500">
                {{ form.errors.name }}
              </div>
            </div>

            <!-- Price + Prev Price + Cost Price + Status -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
              <div>
                <label class="mb-1 block font-medium">Price</label>
                <Input
                  v-model="form.price"
                  type="number"
                  step="0.01"
                  class="w-full rounded border p-2"
                />
              </div>

              <div>
                <label class="mb-1 block font-medium">Previous Price</label>
                <Input
                  v-model="form.prev_price"
                  type="number"
                  step="0.01"
                  class="w-full rounded border p-2"
                />
              </div>

              <div>
                <label class="mb-1 block font-medium">Cost Price</label>
                <Input
                  v-model="form.cost_price"
                  type="number"
                  step="0.01"
                  class="w-full rounded border p-2"
                />
              </div>

              <div>
                <label class="mb-1 block font-medium">Status</label>
                <ToggleSwitch v-model="form.status" />
              </div>
            </div>

            <!-- Variations -->
            <PriceVariations v-model:variations="form.variations" />
          </div>

          <!-- Right Section: Image -->
          <div class="flex justify-center md:justify-start">
            <ImageUploader
              v-model="form.image"
              :existing-url="product.image ? `/${product.image}` : null"
              class="w-full max-w-xs"
            />
          </div>
        </div>

        <!-- Description -->
        <div>
          <label class="mb-1 block font-medium">Description</label>
          <textarea
            v-model="form.description"
            rows="4"
            class="w-full rounded border p-2"
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
  prev_price: product.prev_price || "",
  cost_price: product.cost_price || "",
  image: "", // file will be uploaded if selected
  status: product.status ?? true,
  variations: product.variations || [],
});

function submit() {
  const formData = new FormData();
  formData.append("name", form.name);
  formData.append("description", form.description);
  formData.append("price", form.price || 0);
  formData.append("price", form.prev_price || 0);
  formData.append("price", form.cost_price || 0);
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
