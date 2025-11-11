<script setup>
import { useForm, Link } from "@inertiajs/vue3";

const form = useForm({
  slug: "",
  title: "",
  content: "",
  meta_title: "",
  meta_description: "",
});

const submit = () => form.post("/pages");
</script>

<template>
  <div class="p-6 max-w-3xl mx-auto">
    <h1 class="text-xl font-bold mb-4">Create New Page</h1>

    <form @submit.prevent="submit" class="space-y-4">
      
      <div>
        <label>Slug</label>
        <input v-model="form.slug" class="w-full border p-2 rounded" placeholder="privacy-policy" />
        <div class="text-red-600 text-sm" v-if="form.errors.slug">{{ form.errors.slug }}</div>
      </div>

      <div>
        <label>Title</label>
        <input v-model="form.title" class="w-full border p-2 rounded" />
        <div class="text-red-600 text-sm" v-if="form.errors.title">{{ form.errors.title }}</div>
      </div>

      <div>
        <label>Content</label>
        <textarea v-model="form.content" class="w-full border p-2 rounded" rows="6"></textarea>
      </div>

      <div>
        <label>Meta Title</label>
        <input v-model="form.meta_title" class="w-full border p-2 rounded" />
      </div>

      <div>
        <label>Meta Description</label>
        <textarea v-model="form.meta_description" class="w-full border p-2 rounded" rows="3"></textarea>
      </div>

      <button class="bg-blue-600 text-white px-4 py-2 rounded">Save</button>
    </form>
  </div>
</template>
