<script setup>
import LandingPageLayout from '@/layouts/LandingPageLayout.vue';
import { Head } from '@inertiajs/vue3';
import { computed, reactive } from 'vue';

const props = defineProps({
    page: Object,
    settings: Object,
    user: Object,
});
const store_settings = computed(() => props.settings || []);
// Build FOOTER from settings (important!)
const footer = reactive({
    name: store_settings.value.store_name || '',
    location: store_settings.value.address || '',
    phone: store_settings.value.phone || '',
    email: store_settings.value.email || '',
    description: store_settings.value.store_description || '',
    whatsapp_url: store_settings.value.whatsapp || '',
    messenger_url: store_settings.value.messenger || '',
    facebook_url: store_settings.value.facebook_url || '',
    twitter_url: store_settings.value.twitter_url || '',
    instagram_url: store_settings.value.instagram_url || '',
    tiktok_url: store_settings.value.tiktok_url || '',
    youtube_url: store_settings.value.youtube_url || '',
});

</script>

<template>
    <LandingPageLayout :footer="footer" :user="user">
        <div class="mx-auto max-w-3xl p-6">
            <!-- ✅ Dynamic SEO -->
            <Head>
                <title>{{ page.meta_title ?? page.title }}</title>
                <meta
                    name="description"
                    :content="page.meta_description ?? ''"
                />
            </Head>

            <h1 class="mb-4 text-2xl font-bold">{{ page.title }}</h1>
            <!-- ✅ Render HTML content stored in DB -->
            <div v-html="page.content" class="prose max-w-none"></div>
        </div>
    </LandingPageLayout>
</template>

<style>
/* Optional: Add styling for content formatting */
.prose p {
    margin-bottom: 12px;
}
</style>
