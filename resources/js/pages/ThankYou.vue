<template>
    <LandingPageLayout :user="user" :footer="footer">
        <div class="container mx-auto min-h-[80dvh] p-6">
            <h1 class="mb-4 text-2xl font-bold">
                Thank You! {{ order.customer_name }}
            </h1>
            <p>
                Your Order #{{ order.order_number }} has been placed successfully.
            </p>

            <div class="mt-4 rounded border p-4">
                <h2 class="mb-2 font-semibold">Customer Info</h2>
                <p><strong>Name:</strong> {{ order.customer_name }}</p>
                <p><strong>Phone:</strong> {{ order.customer_phone }}</p>
                <p><strong>Email:</strong> {{ order.customer_email || 'N/A' }}</p>
                <p><strong>Address:</strong> {{ order.customer_address }}</p>

                <h2 class="mt-4 mb-2 font-semibold">Order Info</h2>
                <p><strong>Order ID:</strong> #{{ order.order_number }}</p>
                <p><strong>Payment Method:</strong> {{ order.payment_method }}</p>
                <p><strong>Delivery Zone:</strong> {{ order.delivery_zone }}</p>
                <p><strong>Subtotal:</strong> {{ formatCurrency(order.subtotal) }}</p>
                <p><strong>Delivery Fee:</strong> {{ formatCurrency(order.delivery_fee) }}</p>
                <p><strong>Discount:</strong> {{ formatCurrency(order.total_discount) }}</p>
                <p class="font-bold">
                    <strong>Total:</strong> {{ formatCurrency(order.total_amount) }}
                </p>

                <h2 class="mt-4 mb-2 font-semibold">Items</h2>
                <ul>
                    <li
                        v-for="item in order.items"
                        :key="item.id"
                        class="flex justify-between border-b py-1"
                    >
                        <div>
                            {{ item.product.name }}
                            <span v-if="item.variant_index !== null">
                                ({{ item.product.variations[item.variant_index].name }})
                            </span>
                            <span v-if="item.extra_price && item.extra_price > 0">
                                +{{ formatCurrency(item.extra_price) }}
                            </span>
                        </div>
                        <div>
                            {{ formatCurrency(item.price * item.quantity) }}
                        </div>
                    </li>
                </ul>
            </div>

            <div class="mt-4">
                <Link :href="buttonUrl">
                    <Button>
                        {{ userExists ? 'Login & Track My Order' : 'Register & Track My Order' }}
                    </Button>
                </Link>
            </div>
        </div>
    </LandingPageLayout>
</template>

<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import LandingPageLayout from '@/layouts/LandingPageLayout.vue';
import { Link, usePage } from '@inertiajs/vue3';
import { computed, reactive, onMounted } from 'vue';
import { fbPixel } from '@/helpers/fbPixel';
import type { Setting } from '@/types';
const props = defineProps<{ order: any; userExists: boolean,    settings?: Setting[]; }>();
const page = usePage();
const store_settings = computed(() => props.settings || []);

const order = props.order;
const userExists = props.userExists;

// Build FOOTER from settings (important!)
const footer = reactive({
    name: store_settings.value.store_name || '',
    location: store_settings.value.address || '',
    phone: store_settings.value.phone || '',
    email: store_settings.value.email || '',
    description: store_settings.value.store_description || '',
    whatsapp_url: store_settings.value.whatsapp || '',
    messenger_url: store_settings.value.messenger || '',
    facebook_url: store_settings.value.facebook_url || '',
    twitter_url: store_settings.value.twitter_url || '',
    instagram_url: store_settings.value.instagram_url || '',
    tiktok_url: store_settings.value.tiktok_url || '',
    youtube_url: store_settings.value.youtube_url || '',
});


// User (optional)
const user = page.props.auth?.user || null;

// Format currency
const formatCurrency = (value: number | string) =>
    Number(value).toLocaleString('en-US', {
        style: 'currency',
        currency: 'BDT',
        minimumFractionDigits: 2,
    });

// Button URL
const buttonUrl = computed(() => {
    if (userExists) return `/login?email=${order.customer_email || ''}`;
    return `/register?name=${order.customer_name}&email=${order.customer_email || ''}&phone=${order.customer_phone}&address=${order.customer_address}`;
});

// Facebook Pixel Purchase Event
onMounted(() => {
    fbPixel.track('Purchase', {
        value: Number(order.total_amount),
        currency: 'BDT',
        content_name: `Order #${order.order_number}`,
        contents: order.items.map((item: any) => ({
            id: item.product.id,
            quantity: item.quantity,
            item_price: item.price,
        })),
        num_items: order.items.length,
    });
});
</script>
