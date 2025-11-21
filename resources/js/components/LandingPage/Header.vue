<template>
    <header
        class="fixed top-0 left-0 w-full bg-white shadow-md transition-all duration-300 z-50"
    >
        <div
            class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3"
        >
            <!-- Logo -->
            <Link
                href="/"
                class="flex items-center text-3xl font-bold text-emerald-500"
            >
                <p class="text-emerald-600">বিক্র</p>য়ন
            </Link>

            <!-- Desktop Menu -->
            <nav class="hidden items-center space-x-6 md:flex">
                <!-- Nav Items -->
                <a
                    v-for="item in navItems"
                    :key="item.name"
                    :href="item.href"
                    class="text-base font-semibold text-slate-600 transition-all duration-200 hover:text-green-500"
                >
                    {{ item.name }}
                </a>

                <!-- Right Section -->
                <div class="flex items-center gap-4">
                    <!-- Login -->
                    <Link
                        v-if="!user"
                        href="/login"
                        class="flex items-center gap-2 rounded-lg bg-green-500 px-4 py-2 text-sm font-semibold text-white transition-all duration-200 hover:bg-green-600 active:scale-95"
                    >
                        লগইন করুন
                        <Icon icon="material-symbols:login-rounded" class="h-5 w-5" />
                    </Link>

                    <!-- User Dropdown -->
                    <DropdownMenu v-else>
                        <!-- Button -->
                        <DropdownMenuTrigger as-child>
                            <button
                                class="flex items-center gap-2 rounded-lg bg-gray-100 px-3 py-1 shadow-sm"
                            >
                                <img
                                    :src="user.image || '/user.png'"
                                    @error="(e) => (e.target.src = '/user.png')"
                                    class="h-8 w-8 rounded-full border-2 border-green-500 object-cover"
                                />
                                <span class="font-semibold text-gray-700">
                                    {{ user.name }}
                                </span>
                                <Icon
                                    icon="heroicons-solid:chevron-down"
                                    class="w-4 h-4 text-gray-600"
                                />
                            </button>
                        </DropdownMenuTrigger>

                        <!-- Content -->
                        <DropdownMenuContent align="end" class="w-56">
                            <DropdownMenuLabel class="p-0 font-normal">
                                <div class="flex items-center gap-2 px-2 py-2">
                                    <img
                                        :src="user.image || '/user.png'"
                                        class="h-9 w-9 rounded-full border object-cover"
                                    />
                                    <div class="flex flex-col">
                                        <span class="font-semibold text-gray-700">
                                            {{ user.name }}
                                        </span>
                                        <span class="text-sm text-gray-500">
                                            {{ user.email || user.phone || 'No contact info' }}
                                        </span>
                                    </div>
                                </div>
                            </DropdownMenuLabel>

                            <DropdownMenuSeparator />

                            <DropdownMenuGroup>
                                <DropdownMenuItem as-child>
                                    <Link href="/dashboard" class="flex items-center gap-2">
                                        <Icon icon="mdi:view-dashboard" class="w-4 h-4" />
                                        Dashboard
                                    </Link>
                                </DropdownMenuItem>

                                <DropdownMenuItem as-child>
                                    <Link href="/profile" class="flex items-center gap-2">
                                        <Icon icon="mdi:cog" class="w-4 h-4" />
                                        Settings
                                    </Link>
                                </DropdownMenuItem>
                            </DropdownMenuGroup>

                            <DropdownMenuSeparator />

                            <DropdownMenuItem as-child>
                                <Link
                                    :href="logout()"
                                    @click="handleLogout"
                                    class="flex w-full items-center gap-2 text-red-600"
                                >
                                    <Icon icon="mdi:logout" class="w-4 h-4" />
                                    Log out
                                </Link>
                            </DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenu>
                </div>
            </nav>

            <!-- Mobile Menu Button -->
            <button
                @click="toggleMenu"
                class="text-gray-700 focus:outline-none md:hidden"
            >
                <Icon
                    v-if="!isMenuOpen"
                    icon="heroicons-outline:menu"
                    class="h-7 w-7"
                />
                <Icon v-else icon="heroicons-outline:x" class="h-7 w-7" />
            </button>
        </div>

        <!-- Mobile Menu -->
        <transition name="slide">
            <div
                v-if="isMenuOpen"
                ref="mobileMenu"
                class="border-t border-gray-100 bg-white shadow-lg md:hidden"
            >
                <div
                    ref="menuItems"
                    class="flex flex-col items-center space-y-4 py-5"
                >
                    <a
                        v-for="item in navItems"
                        :key="item.name"
                        :href="item.href"
                        class="menu-item text-base font-semibold text-slate-600 transition-all hover:text-green-500"
                    >
                        {{ item.name }}
                    </a>

                    <button
                        v-if="!user"
                        class="menu-item rounded-lg bg-green-500 px-6 py-2 font-semibold text-white transition-all hover:bg-green-600 active:scale-95"
                    >
                        লগইন করুন
                    </button>

                    <UserMenuMobile v-else :user="user" />
                </div>
            </div>
        </transition>
    </header>
</template>

<script setup lang="ts">
import {
    DropdownMenu,
    DropdownMenuTrigger,
    DropdownMenuContent,
    DropdownMenuGroup,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
} from '@/components/ui/dropdown-menu';

import { Icon } from '@iconify/vue';
import { Link, router } from '@inertiajs/vue3';
import { gsap } from 'gsap';
import { nextTick, ref, watch } from 'vue';
import { logout } from '@/routes';

type User = {
    name: string;
    email?: string | null;
    phone?: string | null;
    image?: string | null;
};

const props = defineProps<{ user?: User | null }>();

const user = props.user || null;

const handleLogout = () => {
    router.flushAll();
};

// Menu items
const navItems = [
    { name: 'পরিচিতি', href: '#intro' },
    { name: 'পার্থক্য', href: '#difference' },
    { name: 'ফিচারস', href: '#features' },
    { name: 'রিভিউ', href: '#reviews' },
    { name: 'জিজ্ঞাসা', href: '#faq' },
    { name: 'গ্যালারী', href: '#gallery' },
    { name: 'যোগাযোগ', href: '#contact' },
];

// Mobile menu
const isMenuOpen = ref(false);
const menuItems = ref(null);

const toggleMenu = () => {
    isMenuOpen.value = !isMenuOpen.value;
};

// GSAP animation
watch(isMenuOpen, async (open) => {
    if (open) {
        await nextTick();
        const items = menuItems.value.querySelectorAll('.menu-item');
        gsap.fromTo(items, { opacity: 0, y: -10 }, {
            opacity: 1,
            y: 0,
            duration: 0.4,
            ease: 'power2.out',
            stagger: 0.07,
        });
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
