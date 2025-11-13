<template>
  <div class="p-4">
    <lightgallery
      :settings="settings"
      :onInit="onInit"
      :onBeforeSlide="onBeforeSlide"
      class="grid grid-cols-2 md:grid-cols-4 gap-4"
    >
      <a
        v-for="item in images"
        :key="item.id"
        :href="item.src"
        :data-lg-size="item.size"
        class="block overflow-hidden rounded-lg"
      >
        <div class="relative w-full pb-full"> <!-- square ratio -->
          <img
            :src="item.thumb"
            alt="Gallery Image"
            class="absolute inset-0 w-full h-full object-cover transform transition-transform duration-200 hover:scale-105"
          />
        </div>
      </a>
    </lightgallery>
  </div>
</template>

<script setup lang="ts">
import Lightgallery from 'lightgallery/vue';
import lgThumbnail from 'lightgallery/plugins/thumbnail';
import lgZoom from 'lightgallery/plugins/zoom';
import lgAutoplay from 'lightgallery/plugins/autoplay';
import 'lightgallery/css/lightgallery.css';
import 'lightgallery/css/lg-thumbnail.css';
import 'lightgallery/css/lg-zoom.css';

let lightGallery: any = null;

// Props
defineProps<{
  images: { id: number; src: string; thumb: string; size: string }[];
}>();

// Plugins
const plugins = [lgThumbnail, lgZoom, lgAutoplay];

// LightGallery settings (download disabled)
const settings = {
  speed: 500,
  mode: 'lg-fade',
  plugins: plugins,
  thumbnail: true,
  autoplay: false,
  zoom: true,
  download: false, // disables download button
};

const onInit = (detail: any) => {
  lightGallery = detail.instance;
};

const onBeforeSlide = (detail: any) => {
  console.log('Before Slide:', detail.index, detail.prevIndex);
};
</script>

<style scoped>
.pb-full {
  padding-bottom: 100%;
}
</style>
