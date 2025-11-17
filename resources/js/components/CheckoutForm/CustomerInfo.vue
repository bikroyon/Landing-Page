<template>
    <div class="mt-4">
        <h2
            class="mb-4 flex items-center gap-2 text-2xl font-extrabold text-gray-700"
        >
            <span class="h-6 w-1 rounded-full bg-green-500"></span>
            {{ title }}
        </h2>
        <div class="flex flex-col gap-2">
            <!-- Name -->
            <div v-if="nameStatus">
                <label class="text-sm font-semibold text-gray-500" v-if="customerInfoLabel"
                    >{{ nameLabel }} *</label
                >
                <input
                    v-model="form.customer_name"
                    @input="clearError('customer_name')"
                    placeholder="আপনার নাম"
                    class="w-full rounded border p-2 text-sm outline-[1px] outline-gray-200 focus:outline-green-600"
                />
                <p
                    v-if="errors.customer_name"
                    class="p-1 text-xs text-rose-500"
                >
                    {{ errors.customer_name }}
                </p>
            </div>

            <!-- Phone -->
            <div v-if="phoneStatus">
                <label class="text-sm font-semibold text-gray-500" v-if="customerInfoLabel"
                    >{{ phoneLabel }} *</label
                >
                <input
                    v-model="form.customer_phone"
                    @input="clearError('customer_phone')"
                    placeholder="ফোন নাম্বার"
                    class="w-full rounded border p-2 text-sm outline-[1px] outline-gray-200 focus:outline-green-600"
                />
                <p
                    v-if="errors.customer_phone"
                    class="p-1 text-xs text-rose-500"
                >
                    {{ errors.customer_phone }}
                </p>
            </div>

            <!-- Email -->
            <div v-if="emailStatus">
                <label
                    class="text-sm font-semibold text-gray-500"
                    v-if="customerInfoLabel"
                    >{{ emailLabel || 'Email' }} label</label
                >
                <input
                    v-model="form.customer_email"
                    @input="clearError('customer_email')"
                    placeholder="আপনার ই-মেইল"
                    class="w-full rounded border p-2 text-sm outline-[1px] outline-gray-200 focus:outline-green-600"
                />
                <p
                    v-if="errors.customer_email"
                    class="p-1 text-xs text-rose-500"
                >
                    {{ errors.customer_email }}
                </p>
            </div>

            <!-- Address -->
            <div v-if="addressStatus">
                <label class="text-sm font-semibold text-gray-500" v-if="customerInfoLabel"
                    >{{ addressLabel }} *</label
                >
                <textarea
                    v-model="form.customer_address"
                    @input="clearError('customer_address')"
                    placeholder="আপনার এড্রেস"
                    class="w-full rounded border p-2 text-sm outline-[1px] outline-gray-200 focus:outline-green-600"
                ></textarea>
                <p
                    v-if="errors.customer_address"
                    class="p-1 text-xs text-red-500"
                >
                    {{ errors.customer_address }}
                </p>
            </div>
    
        </div>
    </div>
</template>

<script setup lang="ts">
import { defineProps } from 'vue';

const props = defineProps({
    customerInfoLabel: Boolean,
    nameLabel: String,
    nameStatus: Boolean,
    emailStatus: Boolean,
    phoneStatus: Boolean,
    addressStatus: Boolean,
    emailLabel: String,
    phoneLabel: String,
    addressLabel: String,
    title: String,
    form: Object,
    errors: Object,
});

// ✅ User edits → clear error immediately
const clearError = (field: string) => {
    if (props.errors && props.errors[field]) {
        props.errors[field] = '';
    }
};
</script>
