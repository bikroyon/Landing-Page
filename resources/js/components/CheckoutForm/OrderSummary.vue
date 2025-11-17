<template>
    <div class="mt-4 rounded-2xl border-2 border-dashed border-gray-400 p-4">
        <h2
            class="mb-4 flex items-center gap-2 text-2xl font-extrabold text-gray-700"
        >
            <span class="h-6 w-1 rounded-full bg-green-500"></span>
            {{ title }}
        </h2>

        <div
            v-for="item in itemsWithDetails"
            :key="item.product_id"
            class="mb-4"
        >
            <div class="mb-1 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <img
                        :src="item.product_image"
                        alt=""
                        class="h-20 w-20 rounded-2xl object-cover"
                    />

                    <div class="flex flex-col items-center justify-start gap-1">
                        <div class="flex flex-col">
                            <span class="text-sm font-semibold">{{
                                item.product_name
                            }}</span>
                            <span class="text-xs">{{ item.variant_name }}</span>
                        </div>
                        <!-- Quantity Control -->
                        <NumberFieldRoot
                            :id="'qty-' + item.product_id"
                            :min="1"
                            v-model="item.quantity"
                            @update:model-value="
                                updateQuantity(item.product_id, $event)
                            "
                        >
                            <div
                                class="flex h-7 items-center rounded-lg border"
                            >
                                <NumberFieldDecrement
                                    class="p-1 disabled:opacity-20"
                                    :disabled="item.quantity <= 1"
                                >
                                    <Icon icon="radix-icons:minus" />
                                </NumberFieldDecrement>
                                <NumberFieldInput
                                    class="w-12 bg-transparent p-1 text-center tabular-nums focus:outline-0"
                                />
                                <NumberFieldIncrement
                                    class="p-1 disabled:opacity-20"
                                >
                                    <Icon icon="radix-icons:plus" />
                                </NumberFieldIncrement>
                            </div>
                        </NumberFieldRoot>
                    </div>
                </div>

                <div class="flex flex-col justify-between">
                    <span class="ml-4 font-bold"
                        >{{ item.baseTotal.toFixed(2) }} ৳</span
                    >
                    <div
                        v-if="item.variant_name && item.extraTotal !== 0"
                        class="flex items-end justify-end text-gray-600"
                    >
                        <span class="">{{ item.extraTotal.toFixed(2) }} ৳</span>
                    </div>
                </div>
            </div>
        </div>

        <hr class="my-2" />

        <div
            class="mb-1 flex justify-between border-b-2 border-dashed border-gray-200"
        >
            <span>মোট</span>
            <span>{{ subtotal.toFixed(2) }} ৳</span>
        </div>

        <div
            v-if="
                selectedPaymentMethod?.extra_charge_cod_percentage &&
                itemsWithDetails.length
            "
            class="mb-1 flex justify-between border-b-2 border-dashed border-gray-200 text-orange-600"
        >
            <span>
                ক্যাশ অন ডেলিভারী চার্জ
                {{ selectedPaymentMethod.extra_charge_cod_percentage }}%
            </span>
            <span>{{ codCharge.toFixed(2) }} ৳</span>
        </div>
        <div v-if="deliveryFee"
            class="mb-1 flex justify-between border-b-2 border-dashed border-gray-200"
        >
            <span> ডেলিভারি চার্জ</span>
            <span>{{ deliveryFee.toFixed(2) }} ৳</span>
        </div>

        <div class="flex justify-between font-bold">
            <span>সর্বমোট</span>
            <span>{{ total.toFixed(2) }} ৳</span>
        </div>
    </div>
</template>

<script setup lang="ts">
import { Icon } from '@iconify/vue';
import {
    NumberFieldDecrement,
    NumberFieldIncrement,
    NumberFieldInput,
    NumberFieldRoot,
} from 'reka-ui';
import { computed, defineEmits, defineProps, reactive, watch } from 'vue';

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
        image: string;
        variations?: Array<{ name: string; extra_price: number }>;
    }>;
    deliveryFee?: number;
    discount?: number;
    selectedPaymentMethod?: {
        code: string;
        extra_charge_cod_percentage?: string | number;
    } | null;
}>();

const emits = defineEmits<{
    (e: 'update:items', items: typeof props.items): void;
}>();

// --- Make a reactive copy of items ---
const itemsState = reactive(props.items.map((item) => ({ ...item })));

// Sync with parent if props.items changes
watch(
    () => props.items,
    (newItems) => {
        // update itemsState while preserving reactivity
        newItems.forEach((item, index) => {
            if (itemsState[index]) {
                itemsState[index].quantity = item.quantity;
                itemsState[index].variant_index = item.variant_index;
                itemsState[index].extra_price = item.extra_price;
            } else {
                itemsState.push({ ...item });
            }
        });
        // remove extra items
        if (itemsState.length > newItems.length) {
            itemsState.splice(newItems.length);
        }
    },
    { deep: true, immediate: true },
);

const itemsWithDetails = computed(() =>
    itemsState.map((item) => {
        const product = props.products.find((p) => p.id === item.product_id);
        const variant =
            item.variant_index !== null &&
            product?.variations?.[item.variant_index!]
                ? product.variations[item.variant_index!]
                : null;
        return {
            ...item,
            product_name: product?.name || '',
            product_image: product?.image || '',
            variant_name: variant?.name || '',
            baseTotal: product ? Number(product.price) * item.quantity : 0,
            extraTotal: variant
                ? Number(variant.extra_price) * item.quantity
                : 0,
        };
    }),
);

const subtotal = computed(() =>
    itemsWithDetails.value.reduce(
        (sum, item) => sum + item.baseTotal + item.extraTotal,
        0,
    ),
);

// --- COD charge depends on selectedPaymentMethod passed from parent ---
const codCharge = computed(() => {
    const method = props.selectedPaymentMethod;
    if (method && method.code.toLowerCase() === 'cod') {
        const percent = Number(method.extra_charge_cod_percentage || 0);
        return subtotal.value * (percent / 100);
    }
    return 0;
});

// --- Total including delivery, discount, COD ---
const total = computed(
    () =>
        subtotal.value +
        (props.deliveryFee || 0) +
        codCharge.value -
        (props.discount || 0),
);

const deliveryFee = computed(() => props.deliveryFee || 0);
const discount = props.discount || 0;

// --- Update quantity from NumberField ---
function updateQuantity(product_id: number, value: number) {
    const item = itemsState.find((i) => i.product_id === product_id);
    if (item) item.quantity = value;

    // Emit updated items to parent for full reactivity
    emits(
        'update:items',
        itemsState.map((i) => ({ ...i })),
    );
}
</script>
<style></style>
