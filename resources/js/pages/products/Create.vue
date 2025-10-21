<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <Head title="Add Product" />

    <div class="p-6">
      <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Create Product</h1>
        <Button @click="submit" :disabled="form.processing">Save</Button>
      </div>

      <form @submit.prevent="submit" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <!-- Left -->
          <div class="space-y-4 md:col-span-2">
            <div>
              <label class="block font-medium mb-1">Name</label>
              <Input v-model="form.name" type="text" required class="w-full border p-2 rounded" />
              <div v-if="form.errors.name" class="text-red-500 text-sm">{{ form.errors.name }}</div>
            </div>

            <div>
              <label class="block font-medium mb-1">Price</label>
              <Input v-model="form.price" type="number" step="0.01" class="w-full border p-2 rounded" />
            </div>

            <div>
              <label class="block font-medium mb-1">Status</label>
              <ToggleSwitch v-model="form.status" />
            </div>

            <PriceVariations v-model:variations="form.variations" />
          </div>

          <!-- Right: Image -->
          <div class="flex items-start justify-center mt-20">
            <ImageUploader />
          </div>
        </div>

        <div>
          <label class="block font-medium mb-1">Description</label>
          <Textarea v-model="form.description" rows="4" class="w-full border p-2 rounded"></Textarea>
        </div>
      </form>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import Button from "@/components/ui/button/Button.vue";
import Input from "@/components/ui/input/Input.vue";
import ToggleSwitch from "@/components/ToggleSwitch.vue";
import PriceVariations from "@/components/PriceVariations.vue";
import ImageUploader from "@/components/ImageUploader.vue";

const breadcrumbs = [
  { title: "Products", href: "/products" },
  { title: "Create", href: "/products/create" },
];

const form = useForm({
  name: "",
  description: "",
  price: "",
  image: "",
  status: true,
  variations: [],
});

function submit() {
  form.post("/products");
}
</script>
