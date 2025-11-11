<template>
  <header class="absolute top-0 left-0 w-full bg-transparent z-50">
    <div class="container mx-auto px-4 py-3 flex items-center justify-between">
      <!-- Logo -->
      <div class="text-2xl font-bold text-indigo-600 cursor-pointer">
        MyBrand
      </div>

      <!-- Desktop Menu -->
      <nav class="hidden md:flex items-center space-x-6">
        <a href="#" class="hover:text-indigo-600 transition">Home</a>
        <a href="#" class="hover:text-indigo-600 transition">About</a>
        <a href="#" class="hover:text-indigo-600 transition">Services</a>
        <a href="#" class="hover:text-indigo-600 transition">Contact</a>
        <button class="ml-4 px-4 py-2 bg-indigo-600 text-white rounded-full hover:bg-indigo-700 transition">
          Get Started
        </button>
      </nav>

      <!-- Mobile Menu Button -->
      <button @click="toggleMenu" class="md:hidden focus:outline-none">
        <svg v-if="!isMenuOpen" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
          viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M4 6h16M4 12h16M4 18h16" />
        </svg>
        <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
          viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>

    <!-- Mobile Menu -->
    <transition name="slide">
      <div ref="mobileMenu" v-if="isMenuOpen" class="md:hidden bg-white shadow-lg">
        <div ref="menuItems" class="flex flex-col items-center space-y-4 py-4">
          <a href="#" class="menu-item hover:text-indigo-600 transition">Home</a>
          <a href="#" class="menu-item hover:text-indigo-600 transition">About</a>
          <a href="#" class="menu-item hover:text-indigo-600 transition">Services</a>
          <a href="#" class="menu-item hover:text-indigo-600 transition">Contact</a>
          <button class="menu-item mt-2 px-4 py-2 bg-indigo-600 text-white rounded-full hover:bg-indigo-700 transition">
            Get Started
          </button>
        </div>
      </div>
    </transition>
  </header>
</template>

<script setup>
import { ref, watch, nextTick } from 'vue'
import { gsap } from 'gsap'

const isMenuOpen = ref(false)
const mobileMenu = ref(null)
const menuItems = ref(null)

const toggleMenu = () => {
  isMenuOpen.value = !isMenuOpen.value
}

// Animate menu items with staggered GSAP effect
watch(isMenuOpen, async (newVal) => {
  if (newVal) {
    await nextTick() // Wait for DOM to render
    const items = menuItems.value.querySelectorAll('.menu-item')

    // Fade and slide in each item with staggered delay
    gsap.fromTo(
      items,
      { opacity: 0, y: -20 },
      {
        opacity: 1,
        y: 0,
        duration: 0.5,
        ease: 'power3.out',
        stagger: 0.1, // delay between each item
      }
    )
  }
})
</script>

<style scoped>
.slide-enter-active,
.slide-leave-active {
  transition: all 0.3s ease;
}
.slide-enter-from,
.slide-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}
</style>
