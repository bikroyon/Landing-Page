<template>
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
        <div
             v-for="product in products.filter(p => p.status === true)"
            :key="product.id"
            class="flex overflow-hidden border gap-1 shadow-sm rounded-2xl p-2"
        >
            <!-- Left: Image -->
            <div class="h-32 w-32 flex-shrink-0">
                <img
                    :src="
                        selectedImages[product.id] ||
                        product.image ||
                        'https://placehold.co/150x150'
                    "
                    alt="product"
                    class="h-full w-full object-cover rounded-2xl"
                />
            </div>

            <!-- Right: Info -->
            <div class="flex flex-1 flex-col justify-between p-1">
                <div>
                    <!-- Name -->
                    <h3 class="truncate text-sm font-semibold">
                        {{ product.name }}
                    </h3>

                    <!-- Description (2 lines) -->
                    <p class="mt-1 line-clamp-2 text-xs text-gray-600">
                        {{ product.description }}
                    </p>

                    <!-- Variations -->
                    <div
                        v-if="product.variations?.length"
                        class="mt-1 flex flex-wrap gap-2"
                    >
                        <button
                        type="button"
                            v-for="(v, idx) in product.variations"
                            :key="idx"
                            @click="selectVariant(product.id, idx)"
                            :class="[
                                'rounded border px-1 py-1 text-xs',
                                selectedVariants[product.id] === idx
                                    ? 'border-blue-500 bg-blue-500 text-white'
                                    : 'bg-gray-100 text-gray-800',
                            ]"
                        >
                            {{ v.name }}
                            <span v-if="v.extra_price"
                                >(+{{ v.extra_price }})</span
                            >
                        </button>
                    </div>
                </div>
                <!--Bottom Quantity & Price -->
                <div class="flex items-center justify-between flex-row">
                    <!-- Quantity -->
                    <div class="mt-0 flex items-center gap-2">
                        <button
                            type="button"
                            @click="decrease(product.id)"
                            class="rounded bg-gray-200 px-2 py-1"
                        >
                            -
                        </button>
                        <span>{{ quantities[product.id] || 0 }}</span>
                        <button
                            type="button"
                            @click="increase(product.id)"
                            class="rounded bg-gray-200 px-2 py-1"
                        >
                            +
                        </button>
                    </div>
                    <!-- Price -->
                    <p class="text-right font-bold">
                        {{
                            (
                                quantities[product.id] * getPrice(product) ||
                                getPrice(product)
                            ).toFixed(2)
                        }} ৳
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { defineEmits, defineProps, reactive } from 'vue';

const props = defineProps<{
    products: Array<{
        id: number;
        name: string;
        description: string;
        price: number | string;
        image?: string;
        variations?: Array<{
            name: string;
            extra_price: number;
            image?: string;
        }>;
    }>;
}>();

const emits = defineEmits<{
    (
        e: 'update:items',
        items: { product_id: number; quantity: number }[],
    ): void;
}>();

const quantities = reactive<Record<number, number>>({});
const selectedVariants = reactive<Record<number, number | null>>({});
const selectedImages = reactive<Record<number, string>>({});

// Increase quantity
function increase(id: number) {
    quantities[id] = (quantities[id] || 0) + 1;
    emitItems();
}

// Decrease quantity
function decrease(id: number) {
    if ((quantities[id] || 0) > 0) quantities[id]--;
    emitItems();
}

// Get price including variant
function getPrice(product: any) {
    const basePrice = Number(product.price);
    const variantIndex = selectedVariants[product.id];
    if (variantIndex !== null && product.variations?.[variantIndex]) {
        return (
            basePrice +
            Number(product.variations[variantIndex].extra_price || 0)
        );
    }
    return basePrice;
}

// Select variant button
function selectVariant(productId: number, variantIndex: number) {
    selectedVariants[productId] = variantIndex;
    const product = props.products.find((p) => p.id === productId);
    if (!product) return;

    const variant = product.variations?.[variantIndex];
    if (variant?.image) {
        selectedImages[productId] = variant.image;
    } else {
        selectedImages[productId] = product.image || '';
    }

    // ✅ Update items so subtotal reflects variant change
    emitItems();
}

// Emit selected items
// Emit selected items with variant info
function emitItems() {
    const items = Object.entries(quantities)
        .filter(([_, qty]) => qty > 0)
        .map(([product_id, quantity]) => {
            const pid = Number(product_id);
            const variantIndex = selectedVariants[pid];
            const product = props.products.find((p) => p.id === pid);
            const extra_price =
                variantIndex !== null &&
                product?.variations?.[variantIndex]?.extra_price
                    ? Number(product.variations[variantIndex].extra_price)
                    : 0;

            return {
                product_id: pid,
                quantity,
                variant_index: variantIndex,
                extra_price, // send extra price to parent
            };
        });

    emits('update:items', items);
}
</script>

<style>
/* Tailwind line-clamp for 2 lines */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-box-orient: vertical;
    overflow: hidden;
    -webkit-line-clamp: 2;
}
</style>
