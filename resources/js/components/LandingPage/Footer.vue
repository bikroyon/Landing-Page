<template>
    <footer class="bg-gray-900 text-gray-300">
        <!-- Help Section -->
        <div class="bg-white pb-10">
            <div
                class="max-w-7xl rounded-4xl bg-teal-500 px-6 mx-10 py-10 text-center"
            >
                <h2 class="mb-3 text-2xl font-bold text-gray-800">
                    সাহায্য প্রয়োজন?
                </h2>
                <p class="mx-auto mb-6 max-w-xl text-gray-600">
                    যেকোনো ডেলিভারি ও অর্ডারসংক্রান্ত সমস্যার জন্য আমাদের
                    হেল্পলাইনে কল করুন, অথবা আমাদের ফেসবুক পেজে মেসেজ দিন। আমরা
                    আছি সকাল ১০ টা থেকে রাত ৮ টা পর্যন্ত।
                </p>

                <div class="flex justify-center gap-4">
                    <Link v-if="footer.phone"
                        :href="`tel:${ footer.phone }`"
                        class="flex items-center gap-2 rounded-full bg-green-500 px-5 py-2 text-white transition hover:bg-green-600"
                    >
                        <i class="fas fa-phone"></i> কল করুন
                    </Link>
                    <Link v-if="footer.messenger_url"
                        :href="footer.messenger_url"
                        class="flex items-center gap-2 rounded-full bg-green-500 px-5 py-2 text-white transition hover:bg-green-600"
                    >
                        <i class="fas fa-phone"></i> কল করুন
                    </Link>
                    <Link v-if="footer.whatsapp_url"
                         :href="footer.whatsapp_url"
                        class="flex items-center gap-2 rounded-full bg-green-500 px-5 py-2 text-white transition hover:bg-green-600"
                    >
                        <i class="fas fa-phone"></i> কল করুন
                    </Link>
                </div>
            </div>
        </div>

        <!-- Main Footer -->
        <div
            class="mx-auto grid max-w-6xl grid-cols-1 gap-10 px-6 py-10 md:grid-cols-3"
        >
            <!-- About Section -->
            <div>
                <h3 class="mb-2 text-lg font-semibold text-green-400">
                    ইকোইটস
                </h3>
                <p class="text-sm leading-relaxed">
                    {{ footer.description }}
                </p>
            </div>
            <!-- Social Media -->
            <div>
                <h3 class="mb-3 text-lg font-semibold text-white">
                    দ্রুত লিঙ্কসমূহ
                </h3>
                <ul class="space-y-2 text-sm">
                    <li>
                        <Link
                            href="/pages/privacy-policy"
                            class="transition hover:text-green-400"
                            >গোপনীয়তা নীতি</Link
                        >
                    </li>
                    <li>
                        <Link
                            href="/pages/terms-and-conditions"
                            class="transition hover:text-green-400"
                            >অ্যাপ ব্যবহারের শর্তাবলী</Link
                        >
                    </li>
                    <li>
                        <Link
                            href="/pages/return-and-refund-policy"
                            class="transition hover:text-green-400"
                            >রিটার্ন ও রিফান্ড নীতি</Link
                        >
                    </li>
                </ul>

            </div>

            <!-- Contact Info -->
            <div>
                <h3 class="mb-3 text-lg font-semibold text-white">
                    যোগাযোগ & সামাজিক মাধ্যম
                </h3>
                <ul class="space-y-2 text-sm">
                    <li>📍 {{ footer.location }}</li>
                    <li>📧 {{ footer.email }}</li>
                    <li>📞 {{ footer.phone }}</li>
                </ul>
                <div class="flex space-x-4 text-lg">
                    <Link
                        v-if="footer.facebook_url"
                        :href="footer.facebook_url"
                        target="_blank"
                        class="transition hover:text-green-400"
                    >
                        <Icon icon="ic:baseline-facebook" />
                    </Link>
                    <Link
                        v-if="footer.instagram_url"
                        :href="footer.instagram_url"
                        target="_blank"
                        class="transition hover:text-green-400"
                    >
                        <Icon icon="mdi:instagram" />
                    </Link>
                    <Link
                        v-if="footer.tiktok_url"
                        :href="footer.tiktok_url"
                        target="_blank"
                        class="transition hover:text-green-400"
                    >
                        <Icon icon="streamline-flex:tiktok-logo-remix" />
                    </Link>
                    <Link
                        v-if="footer.twitter_url"
                        :href="footer.twitter_url"
                        target="_blank"
                        class="transition hover:text-green-400"
                    >
                        <Icon icon="prime:twitter" />
                    </Link>
                    <Link
                        v-if="footer.youtube_url"
                        :href="footer.youtube_url"
                        target="_blank"
                        class="transition hover:text-green-400"
                    >
                        <Icon icon="hugeicons:youtube" />
                    </Link>
                </div>
            </div>
        </div>

        <!-- Bottom Footer -->
        <div
            class="border-t border-gray-700 py-4 text-center text-xs text-gray-400"
        >
            <p>© 2020–2025 {{ footer.name }}. All Rights Reserved.</p>
            <p class="mt-1">
                Loved by
                <a href="#" class="text-green-400 hover:underline"
                    >Bikroyon.com</a
                >
            </p>
        </div>
    </footer>
</template>

<script setup lang="ts">
import { Icon } from '@iconify/vue';
import { Link } from '@inertiajs/vue3';


defineProps({
  footer: {
    type: Object,
    required: true,
  }
});


</script>
