<template>
  <!-- Back to Top Button -->
  <button
    v-show="showTopButton"
    @click="scrollToTop"
    class="fixed right-6 bottom-6 z-50 h-8 w-8 items-center justify-center rounded-lg bg-green-500 p-2 text-white shadow-lg transition-all duration-300 hover:bg-green-600"
  >
    <Icon icon="flowbite:angle-top-solid"/>
  </button>
</template>

<script setup>
import { Icon } from '@iconify/vue';
import { ref, onMounted, onUnmounted } from 'vue';

const showTopButton = ref(false);

const handleScroll = () => {
  showTopButton.value = window.scrollY > 300;
};

const scrollToTop = () => {
  window.scrollTo({ top: 0, behavior: 'smooth' });
};

onMounted(() => window.addEventListener('scroll', handleScroll));
onUnmounted(() => window.removeEventListener('scroll', handleScroll));
</script>
