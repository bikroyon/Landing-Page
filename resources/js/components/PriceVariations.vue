<template>
    <div>
        <label class="mb-1 block font-medium">Variations</label>

        <!-- Add Button -->
        <div class="mb-3 flex items-center gap-2">
            <Button class="w-full" type="button" @click="addVariation">
                + Add Variation
            </Button>
        </div>

        <!-- Variation Rows -->
        <div
            v-for="(variation, index) in localVariations"
            :key="index"
            class="mb-3 relative rounded border p-3 space-y-2"
        >
            <div class="flex flex-row items-center gap-3">
                <!-- Inputs -->
                <div class="flex-1 space-y-2">
                    <div class="flex items-center gap-2">
                        <Input
                            v-model="variation.name"
                            type="text"
                            placeholder="Attribute (e.g. Size M, Color Red)"
                            class="w-1/2 rounded border p-2"
                        />
                        <Input
                            v-model="variation.extra_price"
                            type="number"
                            step="0.01"
                            placeholder="Extra Price"
                            class="w-1/2 rounded border p-2"
                        />
                    </div>

                    <div class="flex items-center gap-2">
                        <Input
                            v-model="variation.sku"
                            type="text"
                            placeholder="SKU"
                            class="w-1/2 rounded border p-2"
                        />
                        <Input
                            v-model="variation.stock"
                            type="number"
                            min="0"
                            placeholder="Stock"
                            class="w-1/2 rounded border p-2"
                        />
                    </div>
                </div>

                <!-- Image Upload -->
                <div class="w-[90px]">
                    <ImageUploader
                        v-model="variation.image"
                        :existing-url="variation.imagePreview"
                    />
                </div>
            </div>

            <!-- Remove Button -->
            <button
                type="button"
                @click="removeVariation(index)"
                class="absolute top-1 right-1 rounded bg-red-500 px-2 py-1 text-white text-xs"
                v-if="localVariations.length > 1"
            >
                <Icon name="trash" class="h-4 w-4" />
            </button>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted, watch } from 'vue';
import ImageUploader from './ImageUploader.vue';
import Button from './ui/button/Button.vue';
import Input from './ui/input/Input.vue';
import Icon from './Icon.vue';

interface Variation {
    name: string;
    extra_price: string | number;
    sku: string;
    stock: string | number;
    image?: File | null;
    imagePreview?: string | null;
}

const props = defineProps<{
    variations: Variation[];
}>();

const emit = defineEmits<{
    (e: 'update:variations', value: Variation[]): void;
}>();

const localVariations = ref<Variation[]>(props.variations || []);

onMounted(() => {
    if (!localVariations.value.length) {
        addVariation();
    }
});

watch(localVariations, (val) => emit('update:variations', val), { deep: true });

function addVariation() {
    localVariations.value.push({
        name: '',
        extra_price: '',
        sku: '',
        stock: '',
        image: null,
        imagePreview: null,
    });
}

function removeVariation(index: number) {
    localVariations.value.splice(index, 1);
}
</script>
