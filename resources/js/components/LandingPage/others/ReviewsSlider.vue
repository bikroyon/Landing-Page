<template>
  <div class="relative w-full overflow-hidden">
    <!-- SLIDER WRAPPER -->
    <div
      class="flex transition-transform duration-700 ease-in-out"
      :style="{ transform: `translateX(-${currentIndex * (100 / slidesPerView)}%)` }"
    >
      <div
        v-for="(review, index) in slides"
        :key="index"
        class="p-4 flex-shrink-0"
        :class="{
          'w-full': slidesPerView === 1,
          'w-1/2': slidesPerView === 2,
          'w-1/3': slidesPerView === 3
        }"
      >
        <div
          :class="[
            'h-full flex flex-col justify-between bg-gray-50 rounded-2xl p-6 text-center',
            { 'slide-active': currentIndex === index }
          ]"
        >
          <!-- Review Content -->
          <div>
            <div class="text-yellow-400 text-lg mb-2">★★★★★</div>
            <p class="text-gray-700 text-base mb-6 leading-relaxed">
              "{{ review.text }}"
            </p>
          </div>

          <!-- Reviewer Info -->
          <div class="flex flex-col items-center mt-auto">
            <img
              :src="review.image"
              class="w-14 h-14 rounded-full object-cover mb-3"
              alt="Reviewer"
            />
            <h4 class="font-semibold text-gray-900">{{ review.name }}</h4>
            <p class="text-sm text-gray-500">{{ review.location }}</p>
            <p
              class="text-xs mt-1"
              :class="review.from === 'facebook' ? 'text-blue-500' : 'text-green-600'"
            >
              {{ review.from === 'facebook' ? 'Facebook Review' : 'Website Review' }}
            </p>
          </div>

          <!-- Optional "Read More" if reviewsUrl -->
          <div v-if="review.reviewsUrl" class="mt-4">
            <a
              :href="review.reviewsUrl"
              target="_blank"
              class="text-midnight-900 font-semibold text-sm hover:underline"
            >
              Read full review →
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- ARROWS -->
    <button
      v-if="showArrows"
      @click="prevSlide"
      class="absolute top-1/2 left-2 transform -translate-y-1/2 bg-green-600 text-white p-2 rounded-full hover:bg-midnight-700 z-10"
    >
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
      </svg>
    </button>

    <button
      v-if="showArrows"
      @click="nextSlide"
      class="absolute top-1/2 right-2 transform -translate-y-1/2 bg-green-600 text-white p-2 rounded-full hover:bg-midnight-700 z-10"
    >
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
      </svg>
    </button>

    <!-- DOTS -->
    <div
      v-if="showDots"
      class="absolute bottom-6 left-1/2 transform -translate-x-1/2 flex gap-1.5"
    >
      <span
        v-for="(dot, index) in totalPages"
        :key="index"
        @click="goToSlide(index)"
        :class="[ 
          'w-2 h-2 rounded-full cursor-pointer transition',
          currentIndex === index ? 'bg-green-700' : 'bg-gray-300'
        ]"
      ></span>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, watch } from 'vue'
import gsap from 'gsap'

// Props
const props = defineProps({
  slides: { type: Array, required: true },
  autoSlide: { type: Boolean, default: true },
  autoSlideInterval: { type: Number, default: 4000 },
  showDots: { type: Boolean, default: true },
  showArrows: { type: Boolean, default: true },
})

// State
const currentIndex = ref(0)
const slidesPerView = ref(1)
const totalPages = ref(0)
let timer = null

// Update slides per view based on window size
const updateSlidesPerView = () => {
  const w = window.innerWidth
  if (w >= 1024) slidesPerView.value = 3
  else if (w >= 768) slidesPerView.value = 2
  else slidesPerView.value = 1

  totalPages.value = Math.ceil(props.slides.length / slidesPerView.value)
}

const nextSlide = () => {
  currentIndex.value = (currentIndex.value + 1) % totalPages.value
}
const prevSlide = () => {
  currentIndex.value = (currentIndex.value - 1 + totalPages.value) % totalPages.value
}
const goToSlide = (i) => {
  currentIndex.value = i
}

// Auto-slide
onMounted(() => {
  updateSlidesPerView()
  window.addEventListener('resize', updateSlidesPerView)
  if (props.autoSlide) timer = setInterval(nextSlide, props.autoSlideInterval)
})
onUnmounted(() => {
  clearInterval(timer)
  window.removeEventListener('resize', updateSlidesPerView)
})

// GSAP Slide Animations
watch(currentIndex, () => {
  gsap.fromTo(
    '.slide-active img',
    { opacity: 0, y: 30 },
    { opacity: 1, y: 0, duration: 0.8, ease: 'power2.out' }
  )
})
</script>

<style>
</style>
