<template>
    <div class="font-bangla mx-auto mt-12 max-w-7xl px-4 text-gray-800">
        <!-- Hero Section -->
        <section class="font-bangla bg-white py-12 text-gray-800 md:px-10">
            <div
                class="container mx-auto grid grid-cols-1 items-center gap-10 lg:grid-cols-2"
            >
                <!-- Left: Text Content -->
                <div>
                    <h2
                        class="mb-4 text-3xl font-extrabold text-green-600 md:text-4xl"
                    >
                        খুলনার বিখ্যাত চুইঝাল!
                    </h2>
                    <p class="mb-6 leading-relaxed text-gray-600">
                        দৈনিক একঘেয়েমি রান্না খেতে বিরক্ত? রান্নায় এমন কিছু
                        ব্যবহার করতে চান যেন রান্নার স্বাদ হয় আরো স্পেশাল?
                        প্রতিদিনের রান্নায় ব্যবহার করুন “চুইঝাল”। ঝাঁঝালো ঝাঁঝ
                        যুক্ত এই মসলা রান্নায় মিশে দারুন স্বাদ তৈরি করে। যেকোন
                        রান্নায় যোগ করে এক অনন্য ফ্লেভার। বিভিন্ন মুখরোচক
                        খাবারকে আরো বেশি মুখরোচক করে তোলে এই চুইঝাল। যেমন
                        রান্নায় ব্যবহার করা যায়:
                    </p>

                    <!-- Bullet List Grid -->
                    <div
                        class="mb-8 grid grid-cols-2 gap-x-6 gap-y-3 text-gray-500 md:grid-cols-3"
                    >
                        <div
                            v-for="(item, index) in items"
                            :key="index"
                            class="flex items-center gap-2"
                        >
                            <Icon
                                icon="mdi:check-circle-outline"
                                class="text-lg text-green-500"
                            />
                            <span class="text-base">{{ item }}</span>
                        </div>
                    </div>

                    <!-- Rating -->
                    <div class="mb-2 flex items-center gap-2">
                        <Icon
                            v-for="i in 5"
                            :key="i"
                            icon="mdi:star"
                            class="text-2xl text-yellow-400"
                        />
                    </div>
                    <p class="mb-6 text-gray-500">
                        ৩০,০০০ হাজারেরও অধিক গ্রাহকের কাছে আমরা চুই ঝাল পৌঁছে
                        দিয়েছি!
                    </p>

                    <!-- Buttons -->
                    <div class="flex flex-wrap items-center gap-3">
                        <button
                            class="flex items-center gap-2 rounded-lg bg-green-500 px-5 py-2 font-semibold text-white shadow transition-all duration-200 hover:bg-green-600 active:scale-95"
                        >
                            <Icon icon="mdi:cart-outline" class="text-xl" />
                            অর্ডার করুন
                        </button>
                        <button
                            class="flex h-10 w-10 items-center justify-center rounded-lg border border-gray-300 transition-all duration-200 hover:bg-gray-100"
                        >
                            <Icon
                                icon="mdi:whatsapp"
                                class="text-xl text-green-500"
                            />
                        </button>
                        <button
                            class="flex h-10 w-10 items-center justify-center rounded-lg border border-gray-300 transition-all duration-200 hover:bg-gray-100"
                        >
                            <Icon
                                icon="mdi:phone"
                                class="text-xl text-slate-500"
                            />
                        </button>
                    </div>
                </div>

                <!-- Right: Embedded Video -->
                <div class="flex w-full justify-center lg:justify-end">
                    <div
                        class="w-full max-w-sm overflow-hidden rounded-lg shadow-md md:max-w-md"
                    >
                        <iframe
                            class="aspect-video w-full rounded-lg"
                            src="https://www.facebook.com/plugins/video.php?href=https://www.facebook.com/EcoEats/videos/969340888168729/"
                            allowfullscreen
                        ></iframe>
                    </div>
                </div>
            </div>
        </section>

        <!-- Category Section -->
        <section class="font-bangla bg-white px-4 py-12 text-gray-800 md:px-10">
            <!-- Title -->
            <div
                class="container mx-auto mb-10 flex flex-col items-center text-center"
            >
                <h2 class="text-3xl font-extrabold text-green-600 md:text-4xl">
                    চুইঝালের পার্থক্যসমূহ
                </h2>
            </div>

            <!-- Comparison Layout -->
            <div
                class="container mx-auto grid grid-cols-1 items-stretch gap-6 lg:grid-cols-[200px_1fr_200px]"
            >
                <!-- Left Image -->
                <div class="flex justify-center">
                    <img
                        src="https://static.wixstatic.com/media/e5857f_127664150acd43aa84f5f5e802f6600b~mv2.jpg/v1/fill/w_980,h_735,al_c,q_85,usm_0.66_1.00_0.01,enc_avif,quality_auto/e5857f_127664150acd43aa84f5f5e802f6600b~mv2.jpg"
                        alt="গাছ চুইঝাল"
                        class="h-full max-h-[100%] w-full rounded-2xl object-cover shadow-md"
                    />
                </div>

                <!-- Center Table -->
                <div class="overflow-x-auto">
                    <!-- Table Header -->
                    <div
                        class="grid grid-cols-2 gap-8 border-b border-gray-200 text-center text-lg font-bold text-green-600"
                    >
                        <div class="py-3 text-right">গাছ চুইঝাল</div>
                        <div class="py-3 text-left">এটো চুইঝাল</div>
                    </div>

                    <!-- Table Body -->
                    <div>
                        <div
                            v-for="(row, index) in differences"
                            :key="index"
                            class="grid grid-cols-2 border-b border-gray-200 last:border-b-0"
                        >
                            <div
                                class="p-4 text-right text-sm text-gray-700 md:text-base"
                            >
                                {{ row.left }}
                            </div>
                            <div
                                class="p-4 text-left text-sm text-gray-700 md:text-base"
                            >
                                {{ row.right }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Image -->
                <div class="flex justify-center">
                    <img
                        src="https://static.wixstatic.com/media/e5857f_44de26b0d1f0415c8141041a2d19cd41~mv2.jpg/v1/fill/w_480,h_360,al_c,q_80,usm_0.66_1.00_0.01,enc_avif,quality_auto/e5857f_44de26b0d1f0415c8141041a2d19cd41~mv2.jpg"
                        alt="এটো চুইঝাল"
                        class="h-full max-h-[100%] w-full rounded-2xl object-cover shadow-md"
                    />
                </div>
            </div>
        </section>

        <!-- Offer Section -->

        <!-- Main Heading -->
        <header class="mb-12 text-center">
            <h1 class="mb-2 text-3xl font-bold text-[#4CAF50] sm:text-4xl">
                কেন আমরাই সেরা
            </h1>
        </header>

        <!-- Feature Grid Section -->
        <section
            class="mx-auto grid max-w-6xl grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-4"
        >
            <!-- Card 1: Authentic Product -->
            <div
                class="flex flex-col items-center rounded-xl bg-white p-6 text-center shadow-lg transition duration-300 hover:scale-[1.02] hover:shadow-xl"
            >
                <div
                    class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-[#E8F5E9] p-3"
                >
                    <!-- Icon: Badge/Check for Authentic Product -->
                    <svg
                        class="h-8 w-8 text-[#4CAF50]"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.108c-.407-.075-.824-.13-1.242-.164C20.307 3.3 19.338 2.5 18 2.5c-1.338 0-2.307.8-2.617 2.164c-.418.034-.835.089-1.242.164-3.81 1.25-6.852 4.095-8.591 7.425C5.176 13.9 5 15.15 5 16.5c0 1.35.176 2.6.55 3.903 1.739 3.33 4.78 6.175 8.591 7.425 3.81 1.25 7.62-1.595 8.591-7.425C22.824 19.1 23 17.85 23 16.5c0-1.35-.176-2.6-.55-3.903-1.739-3.33-4.78-6.175-8.591-7.425z"
                        ></path>
                    </svg>
                </div>
                <h3 class="mb-2 text-xl font-semibold text-gray-800">
                    অথেন্টিক প্রোডাক্ট
                </h3>
                <p class="text-sm text-gray-600">
                    আমাদের প্রতিটি প্যাকেজ সেরা হাতে তৈরি এবং অথেন্টিকতা নিশ্চিত
                    করে।
                </p>
            </div>

            <!-- Card 2: Premium Quality -->
            <div
                class="flex flex-col items-center rounded-xl bg-white p-6 text-center shadow-lg transition duration-300 hover:scale-[1.02] hover:shadow-xl"
            >
                <div
                    class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-[#E8F5E9] p-3"
                >
                    <!-- Icon: Crown for Premium Quality -->
                    <svg
                        class="h-8 w-8 text-[#4CAF50]"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M11 19v-5.25c0-1.83 1.67-3.32 3.5-3.32h.5c1.83 0 3.5 1.49 3.5 3.32V19m-8.5-12.75l2-3 2 3h-4zM2 19h20"
                        ></path>
                    </svg>
                </div>
                <h3 class="mb-2 text-xl font-semibold text-gray-800">
                    প্রিমিয়াম কোয়ালিটি
                </h3>
                <p class="text-sm text-gray-600">
                    ভেজালমুক্ত এবং পরিষ্কার, আমরা নির্দিষ্ট মানদণ্ডে প্রিমিয়াম
                    কোয়ালিটি নিশ্চিত করি।
                </p>
            </div>

            <!-- Card 3: Refund Policy -->
            <div
                class="flex flex-col items-center rounded-xl bg-white p-6 text-center shadow-lg transition duration-300 hover:scale-[1.02] hover:shadow-xl"
            >
                <div
                    class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-[#E8F5E9] p-3"
                >
                    <!-- Icon: Refresh/Return for Refund Policy -->
                    <svg
                        class="h-8 w-8 text-[#4CAF50]"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m-15.356-2A8.001 8.001 0 0019.418 15m0 0H15"
                        ></path>
                    </svg>
                </div>
                <h3 class="mb-2 text-xl font-semibold text-gray-800">
                    রিফান্ড পলিসি
                </h3>
                <p class="text-sm text-gray-600">
                    ডেলিভারি বা অন্য কোনো সমস্যা হলে সহজে টাকা রিফান্ড করার
                    সুবিধা রয়েছে।
                </p>
            </div>

            <!-- Card 4: Cash on Delivery -->
            <div
                class="flex flex-col items-center rounded-xl bg-white p-6 text-center shadow-lg transition duration-300 hover:scale-[1.02] hover:shadow-xl"
            >
                <div
                    class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-[#E8F5E9] p-3"
                >
                    <!-- Icon: Truck for Cash on Delivery -->
                    <svg
                        class="h-8 w-8 text-[#4CAF50]"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22ZM12 18C15.3137 18 18 15.3137 18 12C18 8.68629 15.3137 6 12 6C8.68629 6 6 8.68629 6 12C6 15.3137 8.68629 18 12 18ZM10 12V10H14V12H10Z"
                        ></path>
                    </svg>
                </div>
                <h3 class="mb-2 text-xl font-semibold text-gray-800">
                    ক্যাশ অন ডেলিভারি
                </h3>
                <p class="text-sm text-gray-600">
                    আমাদের পন্য হাতে পেয়ে তারপর টাকা পরিশোধ করার সুযোগ পাচ্ছেন।
                </p>
            </div>
        </section>

        <!-- Placeholder Banner Section (Mimics the image bottom) -->
        <section class="mt-16 overflow-hidden rounded-xl bg-white shadow-lg">
            <div
                class="relative flex items-center justify-center py-10 md:py-16 lg:py-20"
                style="
                    background-color: #f7f7f7;
                    background-image: url(&quot;data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><path fill='%23ECECEC' d='M0 0h100v100H0z'/><path fill='%23F5F5F5' d='M0 0h50v50H0z'/><path fill='%23E0E0E0' d='M50 50h50v50H50z'/></svg>&quot;);
                    background-size: 20px 20px;
                "
            >
                <!-- Left Image Placeholder (Mimics the food/art design) -->
                <div
                    class="absolute top-0 left-0 hidden h-full w-1/4 items-center justify-center overflow-hidden md:flex"
                >
                    <div
                        class="h-48 w-48 translate-x-[-50%] rotate-12 transform rounded-full bg-orange-500 opacity-60"
                    ></div>
                </div>

                <!-- Quote Text -->
                <p
                    class="quote-font z-10 px-4 text-center text-3xl font-extrabold text-gray-900 md:text-5xl"
                >
                    "ভাল লাগে তাই ভাল্লাগে"
                </p>

                <!-- Right Image Placeholder (Mimics the product basket design) -->
                <div
                    class="absolute top-0 right-0 hidden h-full w-1/4 items-center justify-center overflow-hidden md:flex"
                >
                    <div
                        class="h-48 w-48 translate-x-[50%] rotate-[-12deg] transform rounded-full bg-green-500 opacity-60"
                    ></div>
                </div>
            </div>
        </section>

        <section>
            <h2 class="mb-12 text-center text-3xl font-bold text-green-500">
                কাস্টমার রিভিউ
            </h2>
            <ReviewsSlider :slides="reviews" />
        </section>

        <section>
            <Faq :faqs="faqs" />
        </section>

        <section>
            <h2 class="mb-12 text-center text-3xl font-bold text-green-500">
                চুইঝাল গ্যালারী
            </h2>
            <Gallery :images="images" />
        </section>
        <BackToTop />
    </div>
</template>

<script setup>
import { Icon } from '@iconify/vue';
import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { onMounted } from 'vue';
import BackToTop from './others/BackToTop.vue';
import Faq from './others/Faq.vue';
import Gallery from './others/Gallery.vue';
import ReviewsSlider from './others/ReviewsSlider.vue';
const items = [
    'গরুর মাংস',
    'হাঁসের মাংস',
    'মাছ',
    'নিরামিষ',
    'খাসির মাংস',
    'মুড়ে ঘন্ট',
    'সবজি',
    'হালিম',
    'মুরগির মাংস',
    'ঝুড়ি তরকারি',
    'খিচুড়ি',
    'চটপটি',
];
const differences = [
    {
        left: 'চুইঝাল গাছের কাণ্ডকে গাছ চুইঝাল বলে। স্থানীয়দের ভাষায় একে খাঁটি চুই বলা হয়।',
        right: 'চুইঝাল গাছের গোড়া বা মোটা অংশকে এটো চুইঝাল বলে।',
    },
    {
        left: 'গাছ চুইঝাল সাধারণত রান্নার সময় গলে যায় না, তাই আকারে অক্ষত থাকে।',
        right: 'এটো চুইঝালে ফাইবার কম থাকায় এটি রান্নায় গলে গিয়ে ফ্লেভার দেয়।',
    },
    {
        left: 'ঝাঁঝালো স্বাদযুক্ত এবং রান্নায় গলে না গিয়ে অতিরিক্ত ঘ্রাণ দেয়।',
        right: 'তুলনামূলক কম ঝাঁঝালো এবং রান্নায় গলে ঘ্রাণ দেয়।',
    },
    {
        left: 'সাধারণত এক্সপ্রেসিভ মাংস, খিচুড়ি, চটপটি ইত্যাদিতে ব্যবহৃত।',
        right: 'যেকোন মাংস, ডাল বা ঝোলে ব্যবহার করা যায়।',
    },
    {
        left: 'মাড়ু গাছের গাছ চুইঝাল সবচেয়ে বেশি ঝাঁঝালো স্বাদের হয়।',
        right: 'মাঠ বা নরম মাটির চুইঝাল ঝাঁঝে কম তীব্র হয়।',
    },
];

const reviews = [
    {
        text: 'দুই বার অর্ডার করেছি, কোয়ালিটি একদম চমৎকার!',
        name: 'আলম আহমেদ হাদী',
        location: 'ঢাকা',
        image: 'https://i.pravatar.cc/80?img=1',
        from: 'facebook',
        reviewsUrl: '',
    },
    {
        text: 'খুব দ্রুত ডেলিভারি পেয়েছি, ধন্যবাদ!',
        name: 'সাজ্জাদ হোসেন',
        location: 'চট্টগ্রাম',
        image: 'https://i.pravatar.cc/80?img=2',
        from: 'facebook',
        reviewsUrl: '',
    },
    {
        text: 'প্রোডাক্টের মান এবং প্যাকেজিং দারুণ!',
        name: 'সৈকত রায়',
        location: 'রাজশাহী',
        image: 'https://i.pravatar.cc/80?img=3',
        from: 'website',
        reviewsUrl: '',
    },
    {
        text: 'সবসময়ই সন্তুষ্ট, আবারও অর্ডার করব।',
        name: 'রুবিনা আক্তার',
        location: 'খুলনা',
        image: 'https://i.pravatar.cc/80?img=4',
        from: 'website',
        reviewsUrl: '',
    },
    {
        text: 'দাম অনুযায়ী অসাধারণ প্রোডাক্ট।',
        name: 'রাশেদুল ইসলাম',
        location: 'সিলেট',
        image: 'https://i.pravatar.cc/80?img=5',
        from: 'facebook',
        reviewsUrl: '',
    },
];
const images = [
    {
        id: 1,
        src: 'https://picsum.photos/id/1015/1200/1200',
        thumb: 'https://picsum.photos/id/1015/300/300',
        size: '1200-1200',
    },
    {
        id: 2,
        src: 'https://picsum.photos/id/1025/1200/1200',
        thumb: 'https://picsum.photos/id/1025/300/300',
        size: '1200-1200',
    },
    {
        id: 3,
        src: 'https://picsum.photos/id/1035/1200/1200',
        thumb: 'https://picsum.photos/id/1035/300/300',
        size: '1200-1200',
    },
    {
        id: 4,
        src: 'https://picsum.photos/id/1045/1200/1200',
        thumb: 'https://picsum.photos/id/1045/300/300',
        size: '1200-1200',
    },
    {
        id: 5,
        src: 'https://picsum.photos/id/1055/1200/1200',
        thumb: 'https://picsum.photos/id/1055/300/300',
        size: '1200-1200',
    },
    {
        id: 6,
        src: 'https://picsum.photos/id/1065/1200/1200',
        thumb: 'https://picsum.photos/id/1065/300/300',
        size: '1200-1200',
    },
    {
        id: 7,
        src: 'https://picsum.photos/id/1075/1200/1200',
        thumb: 'https://picsum.photos/id/1075/300/300',
        size: '1200-1200',
    },
    {
        id: 8,
        src: 'https://picsum.photos/id/1085/1200/1200',
        thumb: 'https://picsum.photos/id/1085/300/300',
        size: '1200-1200',
    },
];
const faqs = [
    { question: 'চুই খাল কি?', answer: 'চুই খাল হলো একটি উদাহরণ উত্তর।' },
    { question: 'চুই খাল কিভাবে কাটবে?', answer: 'এটি সহজে কাটা যায়।' },
];
gsap.registerPlugin(ScrollTrigger);

onMounted(() => {
    const items = gsap.utils.toArray('.animate-item');

    // set initial hidden state
    items.forEach((i) => gsap.set(i, { opacity: 0, y: 36 }));

    // batch animation for performance
    ScrollTrigger.batch('.animate-item', {
        start: 'top 98%', // triggers when element just enters viewport
        onEnter: (batch) => {
            gsap.to(batch, {
                opacity: 1,
                y: 0,
                duration: 0.8,
                stagger: 0.12,
                ease: 'power2.out',
            });
        },
        onLeaveBack: (batch) => {
            gsap.to(batch, { opacity: 0, y: 40, duration: 0.6 });
        },
    });
});
</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap');

.font-bangla {
    font-family: 'Hind Siliguri', sans-serif;
}

.bg-midnight-900 {
    background-color: #1e40af;
}
.bg-midnight-700 {
    background-color: #1e3a8a;
}
</style>
