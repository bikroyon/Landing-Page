<template>
    <Head>
        <title>{{ settings.store_name }} - {{ settings.store_tagline }}</title>
        <link rel="icon" type="image/jpg" :href="settings.store_favicon" />
        <meta name="description" :content="settings.meta_description || ''" />
    </Head>
    <div>
        <LandingPageHeader/>
        <slot />
        <LandingPageFooter/>
    </div>
</template>
<script setup lang="ts">
import LandingPageFooter from '@/components/LandingPage/Footer.vue';
import LandingPageHeader from '@/components/LandingPage/Header.vue';
import { Head, usePage } from '@inertiajs/vue3';


const page = usePage();
const settings = page.props.settings;
</script>
