<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <Head title="Edit Product" />

    <div class="p-6">
      <div class="mb-4 flex items-center justify-between">
        <h1 class="text-2xl font-bold">Edit Product</h1>
        <Button @click="submit" :disabled="form.processing">
          Update
        </Button>
      </div>

      <form @submit.prevent="submit">
        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
          <!-- Left -->
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

            <!-- Price + Prev Price + Status -->
            <div class="flex items-center justify-between gap-2">
              <div class="w-full">
                <label class="mb-1 block font-medium">Price</label>
                <Input
                  v-model="form.price"
                  type="number"
                  step="0.01"
                  class="w-full rounded border p-2"
                />
              </div>

              <div class="w-full">
                <label class="mb-1 block font-medium">Previous Price</label>
                <Input
                  v-model="form.prev_price"
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

          <!-- Right: Image -->
          <div class="flex items-center justify-center">
            <ImageUploader
              v-model="form.image"
              :existing-url="product.image ? `/${product.image}` : null"
              class="w-full"
            />
          </div>
        </div>

        <!-- Description -->
        <div class="mt-4">
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
