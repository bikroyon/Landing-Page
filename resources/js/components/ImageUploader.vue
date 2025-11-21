<template>
  <div>
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
      <!-- Hidden file input -->
      <input
        ref="fileInput"
        type="file"
        accept="image/*"
        class="hidden"
        @change="handleFileSelect"
      />

      <!-- Image preview or placeholder -->
      <div class="absolute inset-0 flex items-center justify-center transition-all">
        <img
          v-if="preview"
          :src="preview"
          class="h-full w-full object-cover transition duration-300 group-hover:blur-sm"
        />
        <div v-else class="text-xs text-gray-500 text-center">
          <Icon name="upload" class="w-6 h-6 opacity-55 mx-auto mb-1" />
          <p>Click or Drop Image</p>
        </div>
      </div>

      <!-- Hover overlay -->
      <div
        class="absolute inset-0 z-10 flex items-center justify-center opacity-0 transition duration-300 group-hover:opacity-70 bg-black/50 text-white"
      >
        <Icon name="upload" class="w-6 h-6 mr-2" />
        <span>Change</span>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, watch, onBeforeUnmount } from "vue";
import Icon from "./Icon.vue";

const props = defineProps({
  modelValue: [File, String, null],
  existingUrl: String,
  label: String,
  type: { type: String, default: "square" },
});

const emit = defineEmits(["update:modelValue"]);
const fileInput = ref<HTMLInputElement | null>(null);
const preview = ref<string | null>(props.existingUrl || null);

watch(
  () => props.modelValue,
  (val) => {
    if (val instanceof File) {
      preview.value = URL.createObjectURL(val);
    } else if (typeof val === "string" && val) {
      preview.value = val.startsWith("/") ? val : `/${val}`;
    } else if (props.existingUrl) {
      preview.value = props.existingUrl.startsWith("/")
        ? props.existingUrl
        : `/${props.existingUrl}`;
    } else {
      preview.value = null;
    }
  },
  { immediate: true }
);

function handleFile(file: File) {
  emit("update:modelValue", file);
  preview.value = URL.createObjectURL(file);
}

function handleFileSelect(e: Event) {
  const file = (e.target as HTMLInputElement).files?.[0];
  if (file) handleFile(file);
}

function handleDrop(e: DragEvent) {
  const file = e.dataTransfer?.files?.[0];
  if (file) handleFile(file);
}

function triggerFileInput() {
  fileInput.value?.click();
}

onBeforeUnmount(() => {
  if (preview.value?.startsWith("blob:")) {
    URL.revokeObjectURL(preview.value);
  }
});
</script>
