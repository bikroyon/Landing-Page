<template>
    <div>
        <label class="block font-medium mb-1">Price / Variations</label>

        <!-- Base Price -->
        <div class="flex gap-2 items-center mb-3">
            <Input v-model="localPrice" type="number" step="0.01" class="w-full border p-2 rounded"
                placeholder="Base Price" />
            <Button type="button" @click="addVariation">
                + Add Variation
            </Button>
        </div>

        <!-- Variation Rows -->
        <div v-for="(variation, index) in localVariations" :key="index" class="border p-3 rounded mb-3 space-y-2">
            <div class="flex gap-2 items-center">
                <!-- Attribute Name -->
                <Input v-model="variation.name" type="text" size="xs" placeholder="Attribute (e.g. Size M, Color Red)"
                    class="w-1/2 border p-2 rounded" />

                <!-- Extra Price -->
                <Input v-model="variation.extra_price" type="number" step="0.01" placeholder="Extra Price"
                    class="w-1/2 border p-2 rounded" />
            </div>

            <div class="flex gap-2 items-center">
                <!-- SKU -->
                <Input v-model="variation.sku" type="text" placeholder="SKU" class="w-1/3 border p-2 rounded" />

                <!-- Stock -->
                <Input v-model="variation.stock" type="number" min="0" placeholder="Stock"
                    class="w-1/3 border p-2 rounded" />

                <!-- Image Upload -->
                <Input type="file" @change="onFileChange($event, index)" class="w-1/3" />
            </div>

            <!-- Preview Image -->
            <div v-if="variation.imagePreview" class="mt-2">
                <img :src="variation.imagePreview" alt="Variation Image" class="w-20 h-20 object-cover rounded" />
            </div>

            <!-- Remove Button -->
            <button type="button" @click="removeVariation(index)" class="px-2 py-1 bg-red-500 text-white rounded"
                v-if="localVariations.length > 1">
                ✕ Remove
            </button>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, watch, defineProps, defineEmits, onMounted } from "vue";
import Button from "./ui/button/Button.vue";
import Input from "./ui/input/Input.vue";

interface Variation {
    name: string;
    extra_price: string;
    sku: string;
    stock: string;
    image?: File | null;
    imagePreview?: string;
}

const props = defineProps<{
    price: string;
    variations: Variation[];
}>();

const emit = defineEmits<{
    (e: "update:price", value: string): void;
    (e: "update:variations", value: Variation[]): void;
}>();

// Local state
const localPrice = ref(props.price);
const localVariations = ref<Variation[]>(props.variations || []);

// Default one variation on mount
onMounted(() => {
    if (!localVariations.value.length) {
        addVariation();
    }
});

// Sync back to parent
watch(localPrice, (val) => emit("update:price", val));
watch(localVariations, (val) => emit("update:variations", val), { deep: true });

function addVariation() {
    localVariations.value.push({
        name: "",
        extra_price: "",
        sku: "",
        stock: "",
        image: null,
        imagePreview: "",
    });
}

function removeVariation(index: number) {
    localVariations.value.splice(index, 1);
}

function onFileChange(event: Event, index: number) {
    const file = (event.target as HTMLInputElement).files?.[0] || null;
    if (file) {
        localVariations.value[index].image = file;
        localVariations.value[index].imagePreview = URL.createObjectURL(file);
    }
}
</script>
