<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Add Product" />

        <div class="p-4 sm:p-6">
            <!-- Header -->
            <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <h1 class="text-2xl font-bold">Create Product</h1>
                <Button @click="submit" :disabled="form.processing" class="w-full sm:w-auto">
                    Save <Icon icon="ic:round-save" class="ml-1"/>
                </Button>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                    <!-- Left -->
                    <div class="space-y-4 md:col-span-2">
                        <!-- Product Name -->
                        <div>
                            <label class="mb-1 block font-medium">Name</label>
                            <Input
                                v-model="form.name"
                                type="text"
                                required
                                class="w-full rounded border p-2"
                            />
                            <div v-if="form.errors.name" class="text-sm text-red-500">
                                {{ form.errors.name }}
                            </div>
                        </div>

                        <!-- Prices & Status -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                            <div>
                                <label class="mb-1 block font-medium">Price</label>
                                <Input v-model="form.price" type="number" step="0.01" class="w-full rounded border p-2" />
                            </div>
                            <div>
                                <label class="mb-1 block font-medium">Previous Price</label>
                                <Input v-model="form.prev_price" type="number" step="0.01" class="w-full rounded border p-2" />
                            </div>
                            <div>
                                <label class="mb-1 block font-medium">Cost Price</label>
                                <Input v-model="form.cost_price" type="number" step="0.01" class="w-full rounded border p-2" />
                            </div>
                            <div>
                                <label class="mb-1 block font-medium">Status</label>
                                <ToggleSwitch v-model="form.status" />
                            </div>
                        </div>

                        <!-- Price Variations -->
                        <PriceVariations v-model:variations="form.variations" />
                    </div>

                    <!-- Right: Image Uploader -->
                    <div class="flex justify-center md:justify-start">
                        <ImageUploader v-model="form.image" class="w-full max-w-xs" />
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label class="mb-1 block font-medium">Description</label>
                    <textarea
                        v-model="form.description"
                        rows="4"
                        class="w-full rounded border p-2"
                    ></textarea>
                </div>
            </form>
        </div>
    </AppLayout>
</template>


<script setup lang="ts">
import ImageUploader from '@/components/ImageUploader.vue';
import PriceVariations from '@/components/PriceVariations.vue';
import ToggleSwitch from '@/components/ToggleSwitch.vue';
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Icon } from '@iconify/vue';
import { Head, router, useForm } from '@inertiajs/vue3';

const breadcrumbs = [
    { title: 'Products', href: '/products' },
    { title: 'Create', href: '/products/create' },
];

const form = useForm({
    name: '',
    description: '',
    price: '',
    prev_price: '',
    cost_price: '',
    image: '',
    status: true,
    variations: [],
});

function submit() {
    const formData = new FormData();

    formData.append('name', form.name);
    formData.append('description', form.description);
    formData.append('price', form.price || 0);
    formData.append('price', form.prev_price || 0);
    formData.append('price', form.cost_price || 0);
    formData.append('status', form.status ? 1 : 0);

    // Add variations JSON + images
    const variationsData = form.variations.map((v, i) => {
        const { image, ...info } = v;
        if (image instanceof File) {
            formData.append(`variation_images[${i}]`, image);
        }
        return info;
    });
    formData.append('variations', JSON.stringify(variationsData));

    if (form.image instanceof File) {
        formData.append('image', form.image);
    }

    router.post('/products', formData);
}
</script>
