<template>
  <div class="max-w-2xl mx-auto p-4">
    <h2 class="text-2xl font-bold text-green-600 mb-6">সচরাচর জিজ্ঞাসা প্রশ্নাবলি</h2>

    <div
      v-for="(faq, index) in faqs"
      :key="index"
      class="border-b border-gray-200 py-3"
    >
      <button
        @click="toggle(index)"
        class="w-full text-left flex justify-between items-center font-medium text-gray-700 hover:text-green-600 transition-colors"
      >
        {{ faq.question }}
        <span
          class="ml-2 transition-transform duration-300"
          :class="{ 'rotate-45': activeIndex === index }"
        >+</span>
      </button>

      <div
        ref="faqRefs"
        class="overflow-hidden"
        :style="{ height: activeIndex === index ? faqHeights[index] + 'px' : '0px' }"
      >
        <p class="mt-2 text-gray-600 opacity-0 transform translate-y-2" :ref="setAnswerRefs">{{ faq.answer }}</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, nextTick, onMounted } from 'vue'
import { gsap } from 'gsap'

const props = defineProps({
  faqs: {
    type: Array,
    required: true,
    default: () => [],
  },
})

const activeIndex = ref(null)
const faqRefs = ref([])
const answerRefs = ref([])
const faqHeights = ref([])

const toggle = async (index) => {
  if (activeIndex.value === index) {
    activeIndex.value = null
  } else {
    activeIndex.value = index
  }
  await nextTick()
  animateHeight()
}

const animateHeight = () => {
  faqRefs.value.forEach((el, idx) => {
    if (!el) return
    const height = el.scrollHeight
    faqHeights.value[idx] = height

    // Height animation
    gsap.to(el, {
      height: activeIndex.value === idx ? height : 0,
      duration: 0.45,
      ease: 'power3.inOut',
    })

    // Text fade + slide animation
    const answerEl = answerRefs.value[idx]
    if (answerEl) {
      if (activeIndex.value === idx) {
        gsap.fromTo(
          answerEl,
          { opacity: 0, y: 10 },
          { opacity: 1, y: 0, duration: 0.4, ease: 'power2.out', delay: 0.1 }
        )
      } else {
        gsap.to(answerEl, {
          opacity: 0,
          y: 10,
          duration: 0.25,
          ease: 'power2.in',
        })
      }
    }
  })
}

const setAnswerRefs = (el) => {
  if (el) answerRefs.value.push(el)
}

onMounted(() => {
  faqRefs.value = faqRefs.value.slice(0, props.faqs.length)
})
</script>

<style scoped>
.rotate-45 {
  transform: rotate(45deg);
}
</style>
