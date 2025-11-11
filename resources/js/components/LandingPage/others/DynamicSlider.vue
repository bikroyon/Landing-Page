<template>
  <div class="relative w-full overflow-hidden rounded-lg shadow-lg">
    <div
      class="flex transition-transform duration-700 ease-in-out"
      :style="{ transform: `translateX(-${currentIndex * 100}%)` }"
    >
      <div
        v-for="(slide, index) in slides"
        :key="index"
        :class="['min-w-full flex flex-col md:flex-row items-center justify-between p-4', { 'slide-active': currentIndex === index }]"
      >
        <div class="md:w-1/2 text-center md:text-left px-4">
          <h2 class="text-2xl md:text-4xl font-bold mb-4">{{ slide.title }}</h2>
          <p class="text-lg mb-6">{{ slide.description }}</p>
          <button class="bg-white text-midnight-900 px-6 py-3 rounded-lg font-bold hover:bg-sky-100 transition">
            এখনই অর্ডার করুন
          </button>
        </div>
        <div class="md:w-1/2 flex justify-center mt-6 md:mt-0">
          <img :src="slide.image" class="w-full max-w-md rounded-lg" alt="Slide Image" />
        </div>
      </div>
    </div>

    <!-- Arrows -->
    <button
      v-if="showArrows"
      @click="prevSlide"
      class="absolute top-1/2 left-2 transform -translate-y-1/2 bg-midnight-900 text-white p-2 rounded-full hover:bg-midnight-700"
    >
      &#10094;
    </button>
    <button
      v-if="showArrows"
      @click="nextSlide"
      class="absolute top-1/2 right-2 transform -translate-y-1/2 bg-midnight-900 text-white p-2 rounded-full hover:bg-midnight-700"
    >
      &#10095;
    </button>

    <!-- Dots -->
    <div v-if="showDots" class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex gap-2">
      <span
        v-for="(slide, index) in slides"
        :key="index"
        @click="goToSlide(index)"
        :class="[
          'w-3 h-3 rounded-full cursor-pointer transition',
          currentIndex === index ? 'bg-midnight-900' : 'bg-gray-300',
        ]"
      ></span>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch, onUnmounted } from 'vue'
import gsap from 'gsap'

// Props
const props = defineProps({
  slides: { type: Array, required: true },
  autoSlide: { type: Boolean, default: false },
  autoSlideInterval: { type: Number, default: 3000 },
  showDots: { type: Boolean, default: true },
  showArrows: { type: Boolean, default: true },
})

const currentIndex = ref(0)
let timer = null

const nextSlide = () => {
  currentIndex.value = (currentIndex.value + 1) % props.slides.length
}
const prevSlide = () => {
  currentIndex.value = (currentIndex.value - 1 + props.slides.length) % props.slides.length
}
const goToSlide = (i) => {
  currentIndex.value = i
}

// Auto-slide
onMounted(() => {
  if (props.autoSlide) timer = setInterval(nextSlide, props.autoSlideInterval)
})
onUnmounted(() => clearInterval(timer))

// GSAP Slide Animations
watch(currentIndex, () => {
  gsap.fromTo(
    '.slide-active img',
    { opacity: 0, y: 50 },
    { opacity: 1, y: 0, duration: 0.8, ease: 'power2.out' }
  )
})
</script>

<style>
.bg-midnight-900 {
  background-color: #1e3a8a;
}
.bg-midnight-700 {
  background-color: #1e40af;
}
</style>
