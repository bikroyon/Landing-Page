<template>
    <div class="mt-4">
        <h2 class="mb-2 text-xl font-bold">{{ title }}</h2>

        <div class="flex flex-col gap-2">

            <!-- Name -->
            <div>
                <label v-if="label">{{ nameLabel }} *</label>
                <input
                    v-model="form.customer_name"
                    @input="clearError('customer_name')"
                    placeholder="Name"
                    class="rounded border p-2 w-full"
                />
                <p v-if="errors.customer_name" class="text-red-500 text-sm">
                    {{ errors.customer_name }}
                </p>
            </div>

            <!-- Phone -->
            <div>
                <label v-if="label">{{ phoneLabel }} *</label>
                <input
                    v-model="form.customer_phone"
                    @input="clearError('customer_phone')"
                    placeholder="Phone"
                    class="rounded border p-2 w-full"
                />
                <p v-if="errors.customer_phone" class="text-red-500 text-sm">
                    {{ errors.customer_phone }}
                </p>
            </div>

            <!-- Email -->
            <div>
                <label v-if="label">{{ emailLabel || 'Email' }}</label>
                <input
                    v-model="form.customer_email"
                    @input="clearError('customer_email')"
                    placeholder="Email"
                    class="rounded border p-2 w-full"
                />
                <p v-if="errors.customer_email" class="text-red-500 text-sm">
                    {{ errors.customer_email }}
                </p>
            </div>

            <!-- Address -->
            <div>
                <label v-if="label">{{ addressLabel }} *</label>
                <textarea
                    v-model="form.customer_address"
                    @input="clearError('customer_address')"
                    placeholder="Address"
                    class="rounded border p-2 w-full"
                ></textarea>
                <p v-if="errors.customer_address" class="text-red-500 text-sm">
                    {{ errors.customer_address }}
                </p>
            </div>

        </div>
    </div>
</template>

<script setup lang="ts">
import { defineProps } from 'vue';

const props = defineProps({
    label: Boolean,
    nameLabel: String,
    emailLabel: String,
    phoneLabel: String,
    addressLabel: String,
    title: String,
    form: Object,
    errors: Object   // ✅ New
});

// ✅ User edits → clear error immediately
const clearError = (field: string) => {
    if (props.errors && props.errors[field]) {
        props.errors[field] = "";
    }
};
</script>
