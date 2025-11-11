<script setup>
import { useForm, Link } from "@inertiajs/vue3";

const props = defineProps({
  page: Object,
});

const form = useForm({
  slug: props.page.slug,
  title: props.page.title,
  content: props.page.content,
  meta_title: props.page.meta_title,
  meta_description: props.page.meta_description,
});

const submit = () => form.put(`/pages/${props.page.id}`);
</script>

<template>
  <div class="p-6 max-w-3xl mx-auto">
    <h1 class="text-xl font-bold mb-4">Edit Page</h1>

    <form @submit.prevent="submit" class="space-y-4">
      
      <div>
        <label>Slug</label>
        <input v-model="form.slug" class="w-full border p-2 rounded" />
      </div>

      <div>
        <label>Title</label>
        <input v-model="form.title" class="w-full border p-2 rounded" />
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

      <button class="bg-blue-600 text-white px-4 py-2 rounded">Update</button>
    </form>
  </div>
</template>
