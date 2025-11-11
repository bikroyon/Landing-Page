<script setup>
import { Head } from '@inertiajs/vue3';

const props = defineProps({
  page: Object
});
</script>

<template>
  <div class="p-6 max-w-3xl mx-auto">

    <!-- ✅ Dynamic SEO -->
    <Head>
      <title>{{ page.meta_title ?? page.title }}</title>
      <meta name="description" :content="page.meta_description ?? ''" />
    </Head>

    <h1 class="text-2xl font-bold mb-4">{{ page.title }}</h1>

    <!-- ✅ Render HTML content stored in DB -->
    <div v-html="page.content" class="prose max-w-none"></div>

  </div>
</template>

<style>
/* Optional: Add styling for content formatting */
.prose p {
  margin-bottom: 12px;
}
</style>
