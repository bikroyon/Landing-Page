<template>
    <LandingPageLayout>
        <LandingPageDesign />
        <div class="container mx-auto p-6">
            <div>
                <h2 class="mb-4 text-2xl font-bold">
                    {{ store_settings.products_title }}
                </h2>
            </div>
            <form @submit.prevent="submitOrder" class="space-y-4">
                <!-- Select Products -->
                <SelectProducts
                    :products="products"
                    v-model:items="form.items"
                />

                <div class="flex flex-row justify-between gap-4">
                    <div class="w-7/12">
                        <!-- Customer Info -->
                        <CustomerInfo
                            :title="store_settings.customer_info_title"
                            :label="store_settings.customer_info_label"
                            :nameLabel="store_settings.customer_info_name_label"
                            :emailLabel="
                                store_settings.customer_info_email_label
                            "
                            :phoneLabel="
                                store_settings.customer_info_phone_label
                            "
                            :addressLabel="
                                store_settings.customer_info_address_label
                            "
                            :form="form"
                            :errors="form.errors"
                            :readonly="!!user"
                        />

                        <!-- Delivery Zone -->
                        <SelectDeliveryZone
                            :title="store_settings.delivery_zone_title"
                            :zones="deliveryZones"
                            :form="form"
                            :errors="form.errors"
                            :subtotal="subtotal"
                        />

                        <div class="flex flex-col">
                            <label for="additional_note">
                                {{ store_settings.additional_note_title }}
                            </label>
                            <textarea
                                name="additional_note"
                                v-model="form.additional_note"
                                id=""
                            ></textarea>
                        </div>
                    </div>
                    <div class="w-5/12">
                        <!-- Order Summary -->
                        <OrderSummary
                            :title="store_settings.order_summary_title"
                            :items="form.items"
                            :products="products"
                            :deliveryFee="form.delivery_fee || 0"
                            :discount="discount"
                        />
                        <!-- Payment Method -->
                        <SelectPaymentMethod
                            :title="store_settings.payment_title"
                            :paymentMethods="paymentMethods"
                            :form="form"
                        />

                        <!-- Submit Button -->
                        <button
                            type="submit"
                            class="mt-4 rounded bg-blue-600 px-4 py-2 text-white"
                            :disabled="!hasSelectedItems || form.processing"
                        >
                            <span v-if="form.processing">Placing Order...</span>
                            <span v-else>{{
                                store_settings.submit_button
                            }}</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </LandingPageLayout>
</template>

<script setup lang="ts">
import CustomerInfo from '@/components/CheckoutForm/CustomerInfo.vue';
import OrderSummary from '@/components/CheckoutForm/OrderSummary.vue';
import SelectDeliveryZone from '@/components/CheckoutForm/SelectDeliveryZone.vue';
import SelectPaymentMethod from '@/components/CheckoutForm/SelectPaymentMethod.vue';
import SelectProducts from '@/components/CheckoutForm/SelectProducts.vue';
import LandingPageDesign from '@/components/LandingPage/Design.vue';
import { useToast } from '@/Composables/useToast';
import { fbPixel } from '@/helpers/fbPixel';
import LandingPageLayout from '@/layouts/LandingPageLayout.vue';
import type { DeliveryZone, PaymentMethod, Product, Setting } from '@/types';
import { useForm, usePage } from '@inertiajs/vue3';
import { computed, onMounted, ref } from 'vue';
const { showToast } = useToast();

onMounted(() => {
    if (form.items.length) {
        fbPixel.track('InitiateCheckout', {
            content_ids: form.items.map((i) => i.product_id),
            content_type: 'product',
            value: subtotal.value,
            currency: 'BDT',
            num_items: form.items.length,
        });
    }
});

const page = usePage();
const settings = page.props.settings;
const props = defineProps<{
    user?: {
        name: string;
        email: string;
        phone: string;
        address: string;
    } | null;
    settings?: Setting[];
    products?: Product[];
    deliveryZones?: DeliveryZone[];
    paymentMethods?: PaymentMethod[];
    offers?: any[];
}>();

const user = props.user || null;
const store_settings = computed(() => props.settings || []);
const products = computed(() => props.products || []);
const deliveryZones = computed(() => props.deliveryZones || []);
const paymentMethods = computed(() => props.paymentMethods || []);

const form = useForm({
    customer_name: user?.name || '',
    customer_phone: user?.phone || '',
    customer_email: user?.email || '',
    customer_address: user?.address || '',
    items: [] as { product_id: number; quantity: number }[],
    delivery_zone_id: null as number | null,
    payment_method_id: null as number | null,
    additional_note: '',
    transaction_id: '',
    transaction_mobile_number: '',
});

const discount = ref(0);

const subtotal = computed(() =>
    form.items.reduce((sum, item) => {
        const product = products.value.find((p) => p.id === item.product_id);
        const basePrice = product ? Number(product.price) : 0;
        const totalPrice =
            (basePrice + (item.extra_price || 0)) * item.quantity;
        return sum + totalPrice;
    }, 0),
);

const hasSelectedItems = computed(() => form.items.length > 0);

// Submit order
function submitOrder() {
    if (!hasSelectedItems.value) {
        showToast(
            'দুঃক্ষিত!',
            'অর্ডার করতে সর্বনিম্ন ১টি প্রোডাক্ট সিলেক্ট করতে হবে।',
            'error',
        );
        return;
    }

    // Fire InitiateCheckout
    fbPixel.track('InitiateCheckout', {
        value: subtotal.value,
        currency: 'BDT',
        content_ids: form.items.map((i) => i.product_id),
        content_type: 'product',
        num_items: form.items.length,
    });

    // Inside your submitOrder() function
    fbPixel.track('AddToCart', {
        content_ids: form.items.map((i) => i.product_id),
        content_type: 'product',
        value: subtotal.value,
        currency: 'BDT',
        num_items: form.items.length,
    });

    // Submit the order
    form.post('/order', {
        onSuccess: () => {
            showToast(
                'ধন্যবাদ!',
                'আপনার অর্ডারটি সফলভাবে গ্রহন করা হয়েছে।',
                'success',
            );
            form.reset();
        },
        onError: () => {
            showToast(
                'দুঃক্ষিত!',
                'কোথাও কিছু ভুল হয়েছে, দয়া করে চেক করুন।',
                'error',
            );
        },
    });
}
</script>
