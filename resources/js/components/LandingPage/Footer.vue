<template>
    <footer class="bg-gray-900 text-gray-300">
        <div class="mx-auto max-w-6xl">
            <!-- Top Footer Sections -->
            <div class="flex flex-col py-4 md:flex-row md:justify-between">
                <!-- Policies -->
                <div class="flex flex-row gap-4 text-sm">
                    <Link href="/pages/privacy-policy">Privacy Policy</Link>
                    <Link href="/pages/terms-and-conditions">Terms & Conditions</Link>
                    <Link href="/pages/return-and-refund-policy"
                        >Return & Refund Policy</Link
                    >
                </div>

                <!-- Social Media -->
                <div class="flex flex-row">
                    <h3 class="text-sm font-semibold text-white">Follow Us:</h3>
                    <div class="ml-4 flex flex-row gap-4 text-sm">
                        <Link
                            href="https://facebook.com"
                            target="_blank"
                            class="transition hover:text-white"
                            >Facebook</Link
                        >
                        <Link
                            href="https://twitter.com"
                            target="_blank"
                            class="transition hover:text-white"
                            >Twitter</Link
                        >
                        <Link
                            href="https://instagram.com"
                            target="_blank"
                            class="transition hover:text-white"
                            >Instagram</Link
                        >
                    </div>
                </div>
            </div>

            <!-- Bottom Footer -->
            <div class="border-t border-gray-700 py-2 text-center text-xs">
                <p>&copy; 2025 Bikoryon. All rights reserved.</p>
            </div>
        </div>
    </footer>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
</script>
