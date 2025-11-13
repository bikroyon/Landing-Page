<template>
    <AppLayout title="Orders">
        <Head title="All Orders" />
        <div class="container mx-auto p-4 py-2">
            <!-- Header -->
            <div class="mb-6">
                <div>
                    <h1 class="mb-4 text-2xl font-bold">Orders</h1>
                </div>

                <div class="flex justify-between">
                    <form @submit.prevent="searchOrders" class="flex gap-2">
                        <Input
                            v-model="search"
                            @keyup.enter="searchOrders"
                            type="text"
                            placeholder="Search..."
                            class="w-64 rounded border px-3 py-2"
                        />
                        <Button type="submit">
                            <Icon icon="iconamoon:search-bold" />
                        </Button>
                    </form>
                    <div class="flex gap-2">
                        <Button
                            >Export
                            <Icon icon="lucide:printer" />
                        </Button>
                        <Link href="/">
                            <Button>
                                Make an Order
                                <Icon icon="lucide:plus" />
                            </Button>
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto rounded text-xs shadow">
                <table class="w-full table-auto text-left">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="px-2 py-3">#</th>
                            <th class="px-2 py-3">Product</th>
                            <th class="px-2 py-3">Order ID</th>
                            <th class="px-2 py-3">Customer</th>
                            <th class="px-2 py-3">Phone</th>
                            <th class="px-2 py-3">Total</th>
                            <th class="px-2 py-3">Status</th>
                            <th class="px-2 py-3">Time</th>
                            <th class="px-2 py-3">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr
                            v-for="(order, index) in orders.data"
                            :key="order.id"
                            class="border-b"
                        >
                            <td class="px-2 py-3">
                                {{
                                    index +
                                    1 +
                                    (orders.current_page - 1) * orders.per_page
                                }}
                            </td>
                            <td class="flex w-44 items-center gap-3 px-2 py-3">
                                <!-- ✅ Product Image -->
                                <img
                                    :src="order.items?.[0]?.product?.image"
                                    class="h-10 w-10 rounded object-cover"
                                    alt="Image"
                                />

                                <div class="text-xs">
                                    <!-- ✅ Product Name -->
                                    <div class="truncate font-semibold">
                                        {{ order.items?.[0]?.product?.name }}
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <!-- ✅ Variant (only if exists) -->
                                        <div
                                            class="text-xs text-gray-500"
                                            v-if="
                                                order.items?.[0]
                                                    ?.variant_index !== null &&
                                                order.items?.[0]?.product
                                                    ?.variations?.[
                                                    order.items[0].variant_index
                                                ]
                                            "
                                        >
                                            Variant:
                                            {{
                                                order.items[0].product
                                                    .variations[
                                                    order.items[0].variant_index
                                                ].name
                                            }}
                                        </div>

                                        <!-- ❗ If no variant -->
                                        <div
                                            class="text-xs text-gray-400"
                                            v-else
                                        >
                                            No Variant
                                        </div>
                                        <p>
                                            X {{ order.items?.[0]?.quantity }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            <td class="px-2 py-3 font-semibold">
                                #{{ order.order_number }}
                            </td>

                            <td class="w-32 truncate px-2 py-3">
                                {{ order.customer_name }}
                            </td>
                            <td class="px-2 py-2">
                                <p>{{ order.customer_phone }}</p>
                                <div class="flex flex-row gap-1">
                                    <!--Send Email-->
                                    <a
                                        v-if="order.customer_phone"
                                        :href="`https://wa.me/${order.customer_phone}`"
                                        target="_blank"
                                        class=""
                                    >
                                        <Button size="xs" class="h-7 p-1.5"
                                            ><Icon icon="ic:baseline-whatsapp"
                                        /></Button>
                                    </a>
                                    <!--Call on Phone-->
                                    <a
                                        v-if="order.customer_phone"
                                        :href="`tel:${order.customer_phone}`"
                                        class=""
                                    >
                                        <Button size="xs" class="h-7 p-1.5"
                                            ><Icon icon="ic:baseline-phone" />
                                        </Button>
                                    </a>
                                    <!--Send Email-->
                                    <a
                                        v-if="order.customer_email"
                                        :href="`mailto:${order.customer_email}`"
                                        class="text-red-600 underline"
                                    >
                                        <Button size="xs" class="h-7 p-1.5"
                                            ><Icon icon="ic:baseline-email" />
                                        </Button>
                                    </a>
                                </div>
                            </td>
                            <td class="px-2 py-3 font-bold">
                                ৳{{ order.total_amount }}
                            </td>

                            <!-- ✅ RekaUI SELECT DROPDOWN -->
                            <td class="px-2 py-3">
                                <SelectRoot
                                    v-model="order.status"
                                    @update:modelValue="updateStatus(order)"
                                >
                                    <SelectTrigger
                                        class="inline-flex h-[30px] min-w-[120px] items-center justify-between gap-[5px] rounded-lg border bg-white px-3 text-xs text-gray-700 shadow-sm outline-none hover:bg-gray-50"
                                    >
                                        <SelectValue
                                            placeholder="Select status..."
                                        />
                                        <Icon
                                            icon="radix-icons:chevron-down"
                                            class="h-3.5 w-3.5"
                                        />
                                    </SelectTrigger>

                                    <SelectPortal>
                                        <SelectContent
                                            class="z-[100] min-w-[140px] rounded-lg border bg-white shadow-sm"
                                            :side-offset="5"
                                        >
                                            <SelectViewport class="p-[5px]">
                                                <SelectGroup>
                                                    <SelectItem
                                                        v-for="(
                                                            st, i
                                                        ) in statusOptions"
                                                        :key="i"
                                                        :value="st"
                                                        class="relative flex h-[25px] cursor-pointer items-center rounded-[3px] pr-[35px] pl-[25px] text-xs leading-none text-gray-700 select-none data-[highlighted]:bg-gray-200 data-[highlighted]:text-black"
                                                    >
                                                        <SelectItemIndicator
                                                            class="absolute left-0 inline-flex w-[25px] items-center justify-center"
                                                        >
                                                            <Icon
                                                                icon="radix-icons:check"
                                                            />
                                                        </SelectItemIndicator>

                                                        <SelectItemText>{{
                                                            st
                                                        }}</SelectItemText>
                                                    </SelectItem>
                                                </SelectGroup>
                                            </SelectViewport>
                                        </SelectContent>
                                    </SelectPortal>
                                </SelectRoot>
                            </td>

                            <td class="px-2 py-3 text-xs">
                                {{
                                    new Date(order.created_at).toLocaleString()
                                }}
                            </td>

                            <td class="px-2 py-3">
                                <div class="flex gap-2">
                                    <Link :href="`/fraud-checker?phone=${ order.customer_phone }`">
                                        <Button
                                            size="xs"
                                        >
                                            <Icon
                                                icon="solar:shield-user-bold"
                                                class="h-4 w-4"
                                            />
                                        </Button>
                                    </Link>
                                    <Button size="xs" @click="openOrder(order)">
                                        <Icon
                                            icon="lucide:eye"
                                            class="h-4 w-4"
                                        />
                                    </Button>
                                    <Button size="xs" @click="openOrder(order)">
                                        <Icon
                                            icon="lucide:edit-2"
                                            class="h-4 w-4"
                                        />
                                    </Button>
                                    <Button
                                        @click="openDeleteDialog(order.id)"
                                        class=""
                                        variant="destructive"
                                        size="xs"
                                    >
                                        <Icon
                                            icon="lucide:trash-2"
                                            class="h-4 w-4"
                                        />
                                    </Button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6 flex justify-center">
                <nav class="flex gap-1">
                    <template v-for="(link, i) in orders.links" :key="i">
                        <Button
                            v-if="link.url"
                            @click="goTo(link.url)"
                            v-html="link.label"
                            :class="[
                                'rounded border px-3 py-1',
                                link.active
                                    ? 'text-white' // Active page
                                    : 'bg-white text-gray-700 hover:bg-gray-100',
                            ]"
                        ></Button>

                        <!-- No URL = disabled (Previous/Next) -->
                        <span
                            v-else
                            v-html="link.label"
                            class="px-3 py-1 text-gray-400"
                        ></span>
                    </template>
                </nav>
            </div>
        </div>

        <!-- ✅ ORDER DETAILS POPUP -->
        <DialogRoot :open="showDialog" @update:open="showDialog = $event">
            <DialogPortal>
                <DialogOverlay class="fixed inset-0 z-30 bg-black/50" />

                <DialogContent
                    class="fixed top-1/2 left-1/2 z-[100] max-h-[85vh] w-[95vw] max-w-[600px] -translate-x-1/2 -translate-y-1/2 overflow-y-auto rounded-xl bg-white p-6 shadow-xl"
                >
                    <DialogTitle class="text-xl font-bold">
                        Order #{{ selectedOrder?.order_number }}
                    </DialogTitle>

                    <DialogDescription class="mb-4 text-gray-500">
                        Full order details
                    </DialogDescription>

                    <div v-if="selectedOrder">
                        <!-- ✅ Customer Info -->
                        <div class="mb-4 space-y-1">
                            <p>
                                <strong>Name:</strong>
                                {{ selectedOrder.customer_name }}
                            </p>
                            <p>
                                <strong>Phone:</strong>
                                {{ selectedOrder.customer_phone }}
                            </p>
                            <p>
                                <strong>Address:</strong>
                                {{ selectedOrder.address }}
                            </p>
                        </div>

                        <!-- ✅ Product Items -->
                        <div class="mb-4 rounded-lg border p-3">
                            <h3 class="mb-2 font-semibold">Items:</h3>

                            <div
                                v-for="item in selectedOrder.items"
                                :key="item.id"
                                class="border-b py-2"
                            >
                                <p class="font-semibold">
                                    {{ item.product.name }}
                                </p>

                                <!-- ✅ Variant if exists -->
                                <p
                                    v-if="item.variant"
                                    class="text-sm text-gray-600"
                                >
                                    Variant: {{ item.variant }}
                                </p>

                                <p class="text-sm">Qty: {{ item.quantity }}</p>
                                <p class="text-sm">Price: ৳{{ item.price }}</p>
                            </div>
                        </div>

                        <!-- ✅ Totals -->
                        <div class="space-y-1">
                            <p>
                                <strong>Subtotal:</strong> ৳{{
                                    selectedOrder.subtotal
                                }}
                            </p>
                            <p>
                                <strong>Shipping:</strong> ৳{{
                                    selectedOrder.delivery_charge
                                }}
                            </p>
                            <p class="text-lg font-bold">
                                Total: ৳{{ selectedOrder.total_amount }}
                            </p>
                        </div>

                        <!-- ✅ Cancel Note if exists -->
                        <div
                            v-if="selectedOrder.note"
                            class="mt-4 rounded bg-yellow-100 p-3"
                        >
                            <h4 class="font-semibold">Cancel Note:</h4>
                            <p class="text-sm text-gray-700">
                                {{ selectedOrder.note }}
                            </p>
                        </div>
                    </div>

                    <DialogClose
                        class="absolute top-3 right-3 text-gray-600 hover:text-black"
                    >
                        <Icon icon="lucide:x" class="h-5 w-5" />
                    </DialogClose>
                </DialogContent>
            </DialogPortal>
        </DialogRoot>
        <!-- ✅ DELETE CONFIRMATION MODAL -->
        <DialogRoot
            :open="showDeleteDialog"
            @update:open="showDeleteDialog = $event"
        >
            <DialogPortal>
                <DialogOverlay class="fixed inset-0 z-30 bg-black/50" />

                <DialogContent
                    class="fixed top-1/2 left-1/2 z-[100] w-[90vw] max-w-[400px] -translate-x-1/2 -translate-y-1/2 rounded-xl bg-white p-6 shadow-xl"
                >
                    <DialogTitle class="text-lg font-bold text-red-600">
                        Confirm Delete
                    </DialogTitle>

                    <DialogDescription class="mb-4 text-gray-600">
                        Are you sure you want to delete this order? This action
                        cannot be undone.
                    </DialogDescription>

                    <div class="mt-6 flex justify-end gap-3">
                        <DialogClose>
                            <button class="rounded bg-gray-200 px-4 py-2">
                                Cancel
                            </button>
                        </DialogClose>

                        <button
                            @click="confirmDelete"
                            class="rounded bg-red-600 px-4 py-2 text-white"
                        >
                            Delete
                        </button>
                    </div>

                    <DialogClose
                        class="absolute top-3 right-3 text-gray-600 hover:text-black"
                    >
                        <Icon icon="lucide:x" class="h-4 w-4" />
                    </DialogClose>
                </DialogContent>
            </DialogPortal>
        </DialogRoot>
    </AppLayout>
</template>
<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';
import { useToast } from '@/Composables/useToast';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Icon } from '@iconify/vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import {
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogOverlay,
    DialogPortal,
    DialogRoot,
    DialogTitle,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectItemIndicator,
    SelectItemText,
    SelectPortal,
    SelectRoot,
    SelectTrigger,
    SelectValue,
    SelectViewport,
} from 'reka-ui';
import { ref } from 'vue';

const showDialog = ref(false);
const selectedOrder = ref(null);

function openOrder(order) {
    selectedOrder.value = order;
    showDialog.value = true;
}

const { showToast } = useToast();

const props = defineProps({
    orders: Object,
    filters: Object,
});

// ✅ using form for update
const form = useForm({
    status: '',
});

const search = ref(props.filters.search || '');

// ✅ Status list
const statusOptions = [
    'pending',
    'confirmed',
    'processing',
    'packaging',
    'shared_to_courier',
    'delivered',
    'completed',
    'cancelled',
    'returned',
    'refunded',
];

// ✅ Search
function searchOrders() {
    router.get('/orders', { search: search.value }, { preserveState: true });
}

// ✅ Pagination
function goTo(url) {
    if (url) router.get(url, {}, { preserveState: true });
}

const showDeleteDialog = ref(false);
const deleteId = ref(null);

function openDeleteDialog(id) {
    deleteId.value = id;
    showDeleteDialog.value = true;
}

function confirmDelete() {
    if (!deleteId.value) return;

    form.delete(`/orders/${deleteId.value}`, {
        preserveScroll: true,

        onSuccess: () => {
            showToast('Deleted', 'Order deleted successfully!', 'success');
            showDeleteDialog.value = false;
        },

        onError: () => {
            showToast('Error', 'Failed to delete order.', 'error');
        },
    });
}

// ✅ Update status using useForm()
function updateStatus(order) {
    form.status = order.status;

    form.put(`/orders/${order.order_number}/status`, {
        preserveScroll: true,

        onSuccess: () => {
            showToast(
                'Success',
                `Order #${order.order_number} updated!`,
                'success',
            );
        },

        onError: () => {
            showToast('Error', 'Failed to update order.', 'error');
        },
    });
}
</script>
