<template>
    <header
        class="fixed top-0 left-0 w-full bg-white shadow-md transition-all duration-300 "
    >
        <div
            class="mx-auto flex items-center justify-between px-4 py-3 max-w-7xl"
        >
            <!-- Logo -->
            <a
                href="#"
                class="flex items-center text-3xl font-bold text-emerald-500"
            >
                <p class="text-emerald-600">বিক্র</p>য়ন
            </a>

            <!-- Desktop Menu -->
            <nav class="hidden md:flex items-center space-x-6">
                <a
                    v-for="item in navItems"
                    :key="item.name"
                    :href="item.href"
                    class="text-slate-600 font-semibold text-base transition-all duration-200 ease-in hover:text-green-500"
                >
                    {{ item.name }}
                </a>

                <!-- Login Button -->
                <a
                    href="#"
                    class="flex items-center gap-2 rounded-lg bg-green-500 px-4 py-2 text-sm font-semibold text-white transition-all duration-200 ease-in hover:bg-green-600 active:scale-95"
                >
                    <p>লগইন করুন</p>
                    <Icon icon="material-symbols:login-rounded" class="h-5 w-5" />
                </a>
            </nav>

            <!-- Mobile Toggle -->
            <button
                @click="toggleMenu"
                class="md:hidden focus:outline-none text-gray-700"
            >
                <Icon
                    v-if="!isMenuOpen"
                    icon="heroicons-outline:menu"
                    class="h-7 w-7"
                />
                <Icon
                    v-else
                    icon="heroicons-outline:x"
                    class="h-7 w-7"
                />
            </button>
        </div>

        <!-- Mobile Menu -->
        <transition name="slide">
            <div
                v-if="isMenuOpen"
                ref="mobileMenu"
                class="md:hidden bg-white border-t border-gray-100 shadow-lg"
            >
                <div
                    ref="menuItems"
                    class="flex flex-col items-center py-5 space-y-4"
                >
                    <a
                        v-for="item in navItems"
                        :key="item.name"
                        :href="item.href"
                        class="menu-item text-slate-600 font-semibold text-base transition-all duration-200 ease-in hover:text-green-500 hover:-translate-y-[1px]"
                    >
                        {{ item.name }}
                    </a>

                    <button
                        class="menu-item rounded-lg bg-green-500 px-6 py-2 text-white font-semibold hover:bg-green-600 active:scale-95 transition-all duration-200"
                    >
                        লগইন করুন
                    </button>
                </div>
            </div>
        </transition>
    </header>
</template>

<script setup>
import { Icon } from '@iconify/vue';
import { gsap } from 'gsap';
import { ref, watch, nextTick } from 'vue';

// Dynamic nav items
const navItems = [
    { name: 'পরিচিতি', href: '#intro' },
    { name: 'পার্থক্য', href: '#difference' },
    { name: 'ফিচারস', href: '#features' },
    { name: 'রিভিউ', href: '#reviews' },
    { name: 'জিজ্ঞাসা', href: '#faq' },
    { name: 'গ্যালারী', href: '#gallery' },
    { name: 'যোগাযোগ', href: '#contact' },
];

const isMenuOpen = ref(false);
const menuItems = ref(null);

const toggleMenu = () => {
    isMenuOpen.value = !isMenuOpen.value;
};

// GSAP animation for smooth open/close
watch(isMenuOpen, async (open) => {
    if (open) {
        await nextTick();
        const items = menuItems.value.querySelectorAll('.menu-item');
        gsap.fromTo(
            items,
            { opacity: 0, y: -10 },
            {
                opacity: 1,
                y: 0,
                duration: 0.4,
                ease: 'power2.out',
                stagger: 0.07,
            },
        );
    } else {
        const items = menuItems.value?.querySelectorAll('.menu-item');
        if (items) {
            gsap.to(items, {
                opacity: 0,
                y: -8,
                duration: 0.25,
                ease: 'power1.in',
                stagger: 0.05,
            });
        }
    }
});
</script>

<style scoped>
/* Mobile slide transition */
.slide-enter-active,
.slide-leave-active {
    transition: all 0.3s ease-in-out;
}
.slide-enter-from,
.slide-leave-to {
    opacity: 0;
    transform: translateY(-0.75rem);
}

</style>
