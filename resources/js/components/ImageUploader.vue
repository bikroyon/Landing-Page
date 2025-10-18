<template>
    <Label v-if="label">{{ label }}</Label>
    <div
        class="group relative aspect-square w-full cursor-pointer overflow-hidden rounded-lg border-2 border-dashed border-gray-500 transition duration-300"
        @dragover.prevent
        @drop.prevent="handleDrop"
        @click="triggerFileInput"
        :class="{
            'aspect-video': type === 'video',
            'aspect-square': type === 'square',
        }"
    >
        <!-- File input (fully hidden) -->
        <input ref="fileInput" type="file" accept="image/*" class="hidden" @change="handleFileSelect" />

        <!-- Image or Placeholder -->
        <div class="absolute inset-0 flex items-center justify-center transition-all">
            <img v-if="preview" :src="preview" class="h-full w-full object-cover transition duration-300 group-hover:blur-sm" />
            <div v-else class="text-sm"> <Icon name="upload" class="w-6 h-6 opacity-55"/> </div>
        </div>

        <!-- Hover overlay -->
        <div class="absolute inset-0 z-10 flex items-center justify-center opacity-0 transition duration-300 group-hover:opacity-50">
            <Icon name="upload" class="w-6 h-6" />
        </div>
    </div>
</template>

<script lang="ts" setup>
import { onBeforeUnmount, ref, watch } from 'vue';
import Icon from './Icon.vue';

const props = defineProps({
    modelValue: String, // now store Base64 instead of File
    existingUrl: String,
    label: String,
    type: String,
});
const emit = defineEmits(['update:modelValue']);

const fileInput = ref(null);
const preview = ref<string | null>(props.existingUrl || null);

watch(
    () => props.modelValue,
    (newVal) => {
        // if it's base64, just use it directly
        if (typeof newVal === 'string' && newVal.startsWith('data:image')) {
            preview.value = newVal;
        }
    },
    { immediate: true },
);

function handleFile(file) {
    const reader = new FileReader();
    reader.onload = (e) => {
        const base64 = e.target?.result;
        preview.value = base64;
        emit('update:modelValue', base64); // emit Base64 string
    };
    reader.readAsDataURL(file);
}

function handleFileSelect(e) {
    const file = e.target.files[0];
    if (file) handleFile(file);
}

function handleDrop(e) {
    const file = e.dataTransfer.files[0];
    if (file) handleFile(file);
}

function triggerFileInput() {
    fileInput.value?.click();
}

onBeforeUnmount(() => {
    if (preview.value && preview.value.startsWith('blob:')) {
        URL.revokeObjectURL(preview.value);
    }
});
</script>
