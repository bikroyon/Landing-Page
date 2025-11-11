<template>
    <AppLayout>
        <div class="px-4 py-10">
            <h1 class="mb-6 text-2xl font-semibold">My Orders</h1>

            <div v-if="orders.data.length" class="space-y-6">
                <div
                    v-for="order in orders.data"
                    :key="order.id"
                    class="rounded-lg bg-white p-5 shadow-md dark:bg-gray-800"
                >
                    <!-- Order Header -->
                    <div class="mb-3 flex items-center justify-between border-b pb-3">
                        <h2 class="text-lg font-semibold">
                            Order #{{ order.order_number }}
                        </h2>
                        <span
                            class="rounded-full px-3 py-1 text-sm font-medium"
                            :class="statusClasses(order.status)"
                        >
                            {{ order.status }}
                        </span>
                    </div>

                    <!-- Customer Info -->
                    <div class="mb-3 space-y-1">
                        <p><strong>Name:</strong> {{ order.customer_name }}</p>
                        <p><strong>Email:</strong> {{ order.customer_email ?? 'N/A' }}</p>
                        <p><strong>Phone:</strong> {{ order.customer_phone }}</p>
                        <p><strong>Address:</strong> {{ order.customer_address }}</p>
                        <p><strong>Payment:</strong> {{ order.payment_method }}</p>
                        <p><strong>Delivery Zone:</strong> {{ order.delivery_zone }}</p>
                    </div>

                    <!-- Order Items -->
                    <div class="mb-3">
                        <h3 class="mb-2 font-semibold">Items:</h3>
                        <ul class="divide-y divide-gray-200">
                            <li
                                v-for="item in order.items"
                                :key="item.id"
                                class="flex items-center gap-4 py-3"
                            >
                                <!-- Product Image -->
                                <img
                                    v-if="item.product.image"
                                    :src="item.product.image"
                                    alt="Product Image"
                                    class="h-16 w-16 rounded object-cover"
                                />
                                <div class="flex-1">
                                    <p class="font-semibold">{{ item.product.name }}</p>

                                    <!-- Variant info -->
                                    <p v-if="item.variant_index !== null && item.product.variations?.length" class="text-sm text-gray-600">
                                        Variant: {{ item.product.variations[item.variant_index].name }}
                                        <span v-if="item.product.variations[item.variant_index].extra_price">
                                            (+{{ item.product.variations[item.variant_index].extra_price }})
                                        </span>
                                    </p>

                                    <p class="text-sm">Qty: {{ item.quantity }}</p>
                                    <p class="text-sm">Price: ${{ item.price }}</p>
                                    <p class="text-sm font-semibold">Subtotal: ${{ item.subtotal }}</p>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <!-- Totals -->
                    <div class="space-y-1 border-t pt-3">
                        <p><strong>Subtotal:</strong> ${{ order.subtotal }}</p>
                        <p><strong>Delivery Fee:</strong> ${{ order.delivery_fee }}</p>
                        <p><strong>Total Discount:</strong> ${{ order.total_discount }}</p>
                        <p class="text-lg font-bold">Total: ${{ order.total_amount }}</p>
                    </div>

                    <!-- Cancel Order -->
                    <div v-if="order.status === 'pending'" class="mt-3 space-y-2">
                        <textarea
                            v-model="order.note"
                            placeholder="Add a note for cancellation (optional)"
                            class="w-full rounded border px-3 py-2 text-sm"
                        ></textarea>

                        <button
                            @click="cancelOrder(order)"
                            class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700"
                        >
                            Cancel Order
                        </button>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="mt-6 flex justify-center gap-2">
                    <button
                        :disabled="!orders.prev_page_url"
                        @click="visit(orders.prev_page_url)"
                        class="rounded border px-3 py-1 disabled:opacity-50"
                    >
                        Prev
                    </button>
                    <button
                        :disabled="!orders.next_page_url"
                        @click="visit(orders.next_page_url)"
                        class="rounded border px-3 py-1 disabled:opacity-50"
                    >
                        Next
                    </button>
                </div>
            </div>

            <!-- No Orders -->
            <div v-else class="mt-10 text-center text-gray-500">
                You haven’t placed any orders yet.
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    orders: Object,
});

const visit = (url: string) => {
    if (url) router.visit(url);
};

// Cancel order method
const cancelOrder = (order: any) => {
    if (!confirm('Are you sure you want to cancel this order?')) return;

    router.post(`/orders/${order.id}/cancel`, {
        note: order.note || '',
    }, {
        onSuccess: () => {
            alert(`Order #${order.order_number} has been cancelled.`);
            router.reload();
        },
        onError: () => {
            alert('Failed to cancel order.');
        }
    });
};

// Status badge classes
const statusClasses = (status: string) => {
    switch (status) {
        case 'completed':
            return 'bg-green-100 text-green-800';
        case 'pending':
            return 'bg-yellow-100 text-yellow-800';
        case 'processing':
            return 'bg-gray-100 text-gray-800';
        case 'cancelled':
            return 'bg-red-100 text-red-800';
        case 'returned':
            return 'bg-purple-100 text-purple-800';
        case 'refunded':
            return 'bg-pink-100 text-pink-800';
        default:
            return 'bg-gray-200 text-gray-800';
    }
};
</script>
