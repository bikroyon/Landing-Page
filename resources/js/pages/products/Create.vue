<template>
  <AppLayout :breadcrumbs="breadcrumbs">

    <Head title="Create New Product" />

    <div class="p-4 py-2">

      <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold mb-4">Add New Product</h1>
        <Button @click="submit" :disabled="form.processing">
          Save
        </Button>
      </div>

      <form @submit.prevent="submit" class="space-y-6">
        <!-- Grid Layout -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <!-- Left: Inputs -->
          <div class="space-y-4 md:col-span-2">
            <!-- Name -->
            <div>
              <label class="block font-medium mb-1">Name</label>
              <Input v-model="form.name" type="text" class="w-full border p-2 rounded" required />
              <div v-if="form.errors.name" class="text-red-500 text-sm">
                {{ form.errors.name }}
              </div>
            </div>


            <!-- Price -->
            <div class="flex justify-between items-center gap-4">
              <div class="w-full">
                <label class="block font-medium mb-1">Product Type</label>
                <select v-model="form.product_type" class="w-full border p-2 rounded">
                  <option value="physical">Physical</option>
                  <option value="digital">Downloadable</option>
                  <option value="service">Service</option>
                </select>
              </div>
              <div>
                <label class="block font-medium mb-1">Status</label>
                <ToggleSwitch v-model="form.status" />
              </div>
            </div>

            <!-- Show conditionally -->
            <div v-if="form.product_type === 'physical'">
              <PriceVariations v-model:price="form.price" v-model:variations="form.variations" />
            </div>

            <div v-if="form.product_type === 'digital'">
              <DownloadableFiles v-model:downloads="form.downloads" />
            </div>

            <div v-if="form.product_type === 'service'">
              <ServiceForm v-model:service="form.service" />
            </div>
          </div>

          <!-- Right: Image -->
          <div class="md:col-span-1 flex items-start mt-20 justify-center">
            <ImageUploader />
          </div>
        </div>

        <!-- Full width: Description -->
        <div>
          <label class="block font-medium mb-1">Description</label>
          <Textarea v-model="form.description" rows="4" class="w-full border p-2 rounded"></Textarea>
          <div v-if="form.errors.description" class="text-red-500 text-sm">
            {{ form.errors.description }}
          </div>
        </div>
      </form>

    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import ImageUploader from "@/components/ImageUploader.vue";
import Input from "@/components/ui/input/Input.vue";
import AppLayout from "@/layouts/AppLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import type { BreadcrumbItem } from "@/types";
import { ref } from "vue";
import ToggleSwitch from "@/components/ToggleSwitch.vue";
import Button from "@/components/ui/button/Button.vue";
import PriceVariations from "@/components/PriceVariations.vue";
import DownloadableFiles from "@/components/DownloadableFiles.vue";
import ServiceForm from "@/components/ServiceForm.vue";

const breadcrumbs: BreadcrumbItem[] = [
  { title: "Products", href: "/products" },
  { title: "Create", href: "/products/create" },
];

const form = useForm({
  name: "",
  description: "",
  price: "",
  image: "",
  status: true,
  product_type: "physical",
  variations: [] as {
    name: string;
    extra_price: string;
    sku: string;
    stock: string;
    image?: File | null;
    imagePreview?: string;
  }[],
  service: {
    type: "course",
    start_date: "",
    end_date: "",
    days_of_week: [],
    time: "",
    duration_per_session: 1,
    max_participants: 1,
    notes: "",
  },
  downloads: [] as {
    name: string;
    file: File | null;
    previewImage?: string;
    extra_price: string,
    external_url: string;
    max_downloads?: number;
    expires_in_days?: number;
    password?: string;
  }[],
});


const showPreview = ref(false);

function submit() {
  form.post("/products");
}
</script>
