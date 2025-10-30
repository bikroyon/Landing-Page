<template>
    <div class="mt-4 rounded border bg-gray-50 p-4">
        <h2 class="mb-2 text-xl font-bold">{{ title }}</h2>

        <div
            v-for="item in itemsWithDetails"
            :key="item.product_id"
            class="mb-2"
        >
            <div class="flex justify-between">
                <span>{{ item.product_name }} x{{ item.quantity }}</span>
                <span>{{ item.baseTotal.toFixed(2) }} ৳</span>
            </div>

            <div
                v-if="item.variant_name"
                class="ml-4 flex justify-between text-gray-600"
            >
                <span>{{ item.variant_name }} x{{ item.quantity }}</span>
                <span>{{ item.extraTotal.toFixed(2) }} ৳</span>
            </div>
        </div>

        <hr class="my-2" />

        <div class="mb-1 flex justify-between">
            <span>Subtotal</span>
            <span>{{ subtotal.toFixed(2) }} ৳</span>
        </div>

        <div class="mb-1 flex justify-between" v-if="deliveryFee">
            <span>Delivery Fee</span>
            <span>{{ deliveryFee.toFixed(2) }} ৳</span>
        </div>

        <div class="mb-1 flex justify-between text-green-600" v-if="discount">
            <span>Discount</span>
            <span>- {{ discount.toFixed(2) }} ৳</span>
        </div>

        <hr class="my-2" />

        <div class="flex justify-between font-bold">
            <span>Total</span>
            <span>{{ total.toFixed(2) }} ৳</span>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed, defineProps } from 'vue';

const props = defineProps<{
    title?: string;
    items: Array<{
        product_id: number;
        quantity: number;
        variant_index?: number | null;
        extra_price?: number;
    }>;
    products: Array<{
        id: number;
        name: string;
        price: number;
        variations?: Array<{ name: string; extra_price: number }>;
    }>;
    deliveryFee?: number;
    discount?: number;
}>();

// Transform items to include product and variant names, totals
const itemsWithDetails = computed(() => {
    return props.items.map((item) => {
        const product = props.products.find((p) => p.id === item.product_id);
        const variant =
            item.variant_index !== null &&
            product?.variations?.[item.variant_index!]
                ? product.variations[item.variant_index!]
                : null;

        return {
            product_id: item.product_id,
            product_name: product?.name || '',
            variant_name: variant?.name || '',
            quantity: item.quantity,
            baseTotal: product ? Number(product.price) * item.quantity : 0,
            extraTotal: variant
                ? Number(variant.extra_price) * item.quantity
                : 0,
        };
    });
});

const subtotal = computed(() =>
    itemsWithDetails.value.reduce(
        (sum, item) => sum + item.baseTotal + item.extraTotal,
        0,
    ),
);

const total = computed(() =>
    Math.max(
        0,
        subtotal.value + (props.deliveryFee || 0) - (props.discount || 0),
    ),
);
</script>
