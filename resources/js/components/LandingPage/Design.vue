<template>
  <div class="font-bangla text-gray-800">
    <!-- Hero Section -->
    <section ref="hero" class="bg-sky-500 text-white py-16 px-4 md:px-16 flex flex-col md:flex-row items-center gap-8">
      <div class="flex-1 text-center md:text-left">
        <h1 class="text-3xl md:text-5xl font-bold mb-4 animate-item">সীমিত সময়ের জন্য – মাত্র ৯৫০ টাকা!</h1>
        <p class="text-lg mb-6 animate-item">ডেনিম প্যান্ট – প্রিমিয়াম মান, স্টাইলিশ ডিজাইন, আরামদায়ক ফিট।</p>
        <button class="bg-midnight-900 hover:bg-midnight-700 px-6 py-3 rounded-lg transition duration-300 animate-item">এখনই অর্ডার করুন</button>
      </div>
      <div class="flex-1 flex justify-center">
        <img src="https://placehold.co/600x400" alt="Denim Product" class="animate-item rounded-lg shadow-lg w-full md:w-4/5" />
      </div>
    </section>

    <!-- Category Section -->
    <section ref="categories" class="bg-white py-16 px-4 md:px-16 text-center">
      <h2 class="text-2xl md:text-3xl font-bold mb-10 text-midnight-900 animate-item">যেকোনো চাহিদার জন্য – আলাদা ডিজাইন ও বাজেট</h2>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="p-6 border rounded-lg shadow hover:shadow-lg transition duration-300 animate-item">
          <img src="https://placehold.co/600x400" alt="Pant 1" class="w-full rounded mb-4" />
          <h3 class="font-bold text-xl mb-2">ব্লু প্যান্ট – কোড ১০২</h3>
          <p class="mb-2">মূল্য: ১০৫০ টাকা</p>
        </div>
        <div class="p-6 border rounded-lg shadow hover:shadow-lg transition duration-300 animate-item">
          <img src="https://placehold.co/600x400" alt="Pant 2" class="w-full rounded mb-4" />
          <h3 class="font-bold text-xl mb-2">ডার্ক প্যান্ট – কোড ১০২</h3>
          <p class="mb-2">মূল্য: ১০৫০ টাকা</p>
        </div>
        <div class="p-6 border rounded-lg shadow hover:shadow-lg transition duration-300 animate-item">
          <img src="https://placehold.co/600x400" alt="Pant 3" class="w-full rounded mb-4" />
          <h3 class="font-bold text-xl mb-2">গ্রে প্যান্ট – কোড ১০২</h3>
          <p class="mb-2">মূল্য: ১০৫০ টাকা</p>
        </div>
      </div>
    </section>

    <!-- Offer Section -->
    <section ref="offer" class="bg-sky-100 py-16 px-4 md:px-16 text-center animate-item">
      <h2 class="text-2xl md:text-3xl font-bold text-midnight-900 mb-4">আজই অর্ডার করলে পাচ্ছেন বিশেষ মূল্যছাড়!</h2>
      <div class="bg-midnight-900 text-white rounded-xl p-8 max-w-3xl mx-auto shadow-lg">
        <p class="text-lg mb-2">সাধারণ মূল্য: ৳৯৫০</p>
        <p class="text-2xl font-bold mb-4">বিশেষ মূল্য: মাত্র ৳৮২০!*</p>
        <p class="text-sm mb-6">(*ডেলিভারি চার্জ প্রযোজ্য)</p>
        <button class="bg-sky-500 hover:bg-sky-600 px-6 py-3 rounded-lg transition duration-300">এখনই অর্ডার করুন</button>
      </div>
    </section>

    <!-- Features Section -->
    <section ref="features" class="bg-white py-16 px-4 md:px-16 text-center">
      <h2 class="text-2xl md:text-3xl font-bold text-midnight-900 mb-10 animate-item">আমাদের প্যান্টের বৈশিষ্ট্য</h2>
      <div class="flex flex-col md:flex-row items-center gap-10">
        <img src="https://placehold.co/600x400" alt="Feature Image" class="w-full md:w-1/2 rounded-lg shadow-lg animate-item" />
        <ul class="text-left text-lg space-y-3">
          <li class="animate-item">✅ আরামদায়ক, মজবুত ও প্রিমিয়াম ডেনিম ফ্যাব্রিক</li>
          <li class="animate-item">✅ আধুনিক কাটিং ও নিখুঁত সেলাই</li>
          <li class="animate-item">✅ কালার ফেড হয় না</li>
          <li class="animate-item">✅ যেকোনো অনুষ্ঠানে মানানসই</li>
          <li class="animate-item">✅ একাধিক ডিজাইন ও সাইজে পাওয়া যায়</li>
        </ul>
      </div>
    </section>

    <!-- Why Choose Us Section -->
    <section ref="why" class="bg-midnight-900 text-white py-16 px-4 md:px-16 text-center">
      <h2 class="text-2xl md:text-3xl font-bold mb-10 animate-item">আমাদের থেকে কেন কিনবেন?</h2>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="p-6 border border-white/20 rounded-lg bg-white/10 backdrop-blur animate-item">
          <h3 class="font-bold text-xl mb-2">💎 প্রিমিয়াম কোয়ালিটি</h3>
          <p>আমাদের প্রোডাক্ট ১০০% মানসম্পন্ন ও পরীক্ষিত।</p>
        </div>
        <div class="p-6 border border-white/20 rounded-lg bg-white/10 backdrop-blur animate-item">
          <h3 class="font-bold text-xl mb-2">🚚 দ্রুত ডেলিভারি</h3>
          <p>দেশের যেকোনো স্থানে দ্রুত পৌঁছে যায়।</p>
        </div>
        <div class="p-6 border border-white/20 rounded-lg bg-white/10 backdrop-blur animate-item">
          <h3 class="font-bold text-xl mb-2">💰 ক্যাশ অন ডেলিভারি</h3>
          <p>প্রোডাক্ট হাতে পেয়ে মূল্য পরিশোধের সুবিধা।</p>
        </div>
      </div>
    </section>
    <!-- Customer Reviews Section -->
<section ref="reviews" class="bg-sky-100 py-16 px-4 md:px-16 text-center">
  <h2 class="text-2xl md:text-3xl font-bold text-midnight-900 mb-10 animate-item">
    আমাদের সন্তুষ্ট ক্রেতাদের মতামত
  </h2>

  <div class="flex flex-col md:flex-row justify-center gap-6">
    <!-- Review Card 1 -->
    <div class="bg-white p-6 rounded-xl shadow-md w-full md:w-1/3 animate-item">
      <div class="flex flex-col items-center">
        <img src="https://placehold.co/600x400" alt="Review Product" class="w-24 h-24 rounded-lg mb-4">
        <h3 class="font-bold text-lg mb-2">Rahul Hossain</h3>
        <p class="text-gray-600 mb-4 text-sm">
          “ডেনিমের কোয়ালিটি সত্যিই অসাধারণ! দাম অনুযায়ী দারুণ একটা পণ্য।”
        </p>
        <div class="flex gap-1 text-yellow-400">
          <span>⭐</span><span>⭐</span><span>⭐</span><span>⭐</span><span>⭐</span>
        </div>
      </div>
    </div>

    <!-- Review Card 2 -->
    <div class="bg-white p-6 rounded-xl shadow-md w-full md:w-1/3 animate-item">
      <div class="flex flex-col items-center">
        <img src="https://placehold.co/600x400" alt="Review Product" class="w-24 h-24 rounded-lg mb-4">
        <h3 class="font-bold text-lg mb-2">Nishat Muna</h3>
        <p class="text-gray-600 mb-4 text-sm">
          “ডেলিভারি অনেক ফাস্ট ছিল, প্যান্টের সেলাইও নিখুঁত। আমি খুবই সন্তুষ্ট!”
        </p>
        <div class="flex gap-1 text-yellow-400">
          <span>⭐</span><span>⭐</span><span>⭐</span><span>⭐</span><span>⭐</span>
        </div>
      </div>
    </div>

    <!-- Review Card 3 -->
    <div class="bg-white p-6 rounded-xl shadow-md w-full md:w-1/3 animate-item">
      <div class="flex flex-col items-center">
        <img src="https://placehold.co/600x400" alt="Review Product" class="w-24 h-24 rounded-lg mb-4">
        <h3 class="font-bold text-lg mb-2">Rakibul Islam</h3>
        <p class="text-gray-600 mb-4 text-sm">
          “আমি ২টা অর্ডার করেছিলাম, দুটোই দারুণ ফিট হয়েছে। আবারও অর্ডার করব।”
        </p>
        <div class="flex gap-1 text-yellow-400">
          <span>⭐</span><span>⭐</span><span>⭐</span><span>⭐</span><span>⭐</span>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Contact / Call to Action Section -->
<section ref="contact" class="bg-white py-16 px-4 md:px-16 text-center">
  <h2 class="text-2xl md:text-3xl font-bold mb-4 animate-item">অর্ডার বা প্রশ্ন?</h2>
  <p class="text-lg mb-6 animate-item">কল করুন বা WhatsApp / Messenger-এ মেসেজ দিন</p>
  <p class="text-gray-600 mb-8 animate-item">
    আমাদের যেকোনো প্রোডাক্ট নিয়ে জানতে চাইলে যোগাযোগ করুন –
  </p>

  <div class="flex flex-col md:flex-row justify-center gap-4 animate-item">
    <a href="https://wa.me/8801XXXXXXXXX" target="_blank" class="bg-green-500 text-white px-6 py-3 rounded-lg flex items-center justify-center gap-2 hover:bg-green-600 transition">
      <i class="fab fa-whatsapp"></i> WhatsApp করুন
    </a>
    <a href="https://m.me/yourpage" target="_blank" class="bg-blue-600 text-white px-6 py-3 rounded-lg flex items-center justify-center gap-2 hover:bg-blue-700 transition">
      <i class="fab fa-facebook-messenger"></i> Messenger মেসেজ করুন
    </a>
    <a href="tel:+8801XXXXXXXXX" class="bg-midnight-900 text-white px-6 py-3 rounded-lg flex items-center justify-center gap-2 hover:bg-midnight-700 transition">
      📞 ফোন দিন
    </a>
  </div>
</section>

  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import gsap from 'gsap'
import { ScrollTrigger } from 'gsap/ScrollTrigger'

gsap.registerPlugin(ScrollTrigger)

onMounted(() => {
  const items = gsap.utils.toArray('.animate-item')

  // set initial hidden state
  items.forEach(i => gsap.set(i, { opacity: 0, y: 36 }))

  // batch animation for performance
  ScrollTrigger.batch('.animate-item', {
    start: 'top 98%', // triggers when element just enters viewport
    onEnter: batch => {
      gsap.to(batch, {
        opacity: 1,
        y: 0,
        duration: 0.8,
        stagger: 0.12,
        ease: 'power2.out'
      })
    },
    onLeaveBack: batch => {
      gsap.to(batch, { opacity: 0, y: 40, duration: 0.6 })
    }
  })
})
</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@400;700&display=swap');

.font-bangla {
  font-family: 'Noto Sans Bengali', sans-serif;
}
.bg-midnight-900 {
  background-color: #1e40af;
}
.bg-midnight-700 {
  background-color: #1e3a8a;
}
</style>
