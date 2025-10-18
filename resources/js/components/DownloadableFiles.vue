<template>
  <div class="space-y-4 border p-4 rounded bg-gray-50">
    <h2 class="text-lg font-semibold mb-2">Downloadable Files</h2>
    <!-- Base Price -->
    <div class="flex gap-2 items-center mb-3">
      <Input v-model="localPrice" type="number" step="0.01" class="w-full border p-2 rounded"
        placeholder="Base Price" />
      <Button type="button" @click="addFile">
        + Add File
      </Button>
    </div>
    <!-- File Rows -->
    <div v-for="(file, index) in localDownloads" :key="index" class="border p-2 rounded flex flex-col gap-2">
      <div class="flex gap-2 flex-wrap items-center">
        <!-- File Name -->
        <input v-model="file.name" type="text" placeholder="File Name" class="w-full md:w-1/4 border p-2 rounded" />

        <!-- File Upload -->
        <input type="file" @change="onFileChange($event, index)" class="w-full md:w-1/4 border p-2 rounded" />

        <!-- Preview Image Upload -->
        <input type="file" accept="image/*" @change="onPreviewChange($event, index)"
          class="w-full md:w-1/4 border p-2 rounded" />

        <!-- Remove Button -->
        <button type="button" @click="removeFile(index)" class="px-2 py-1 bg-red-500 text-white rounded"
          v-if="localDownloads.length > 1">
          ✕
        </button>
      </div>

      <!-- Preview Image -->
      <div v-if="file.previewImage" class="w-32 h-32 border rounded overflow-hidden">
        <img :src="file.previewImage" class="w-full h-full object-cover" />
      </div>

      <!-- Additional Options -->
      <div class="flex gap-2 flex-wrap">
        <input v-model="file.external_url" type="text" placeholder="External URL (optional)"
          class="w-full md:w-1/4 border p-2 rounded" />

        <input v-model.number="file.max_downloads" type="number" min="1" placeholder="Max Downloads"
          class="w-full md:w-1/4 border p-2 rounded" />

        <input v-model.number="file.expires_in_days" type="number" min="0" placeholder="Expires in days (0 = no expiry)"
          class="w-full md:w-1/4 border p-2 rounded" />

        <input v-model="file.password" type="text" placeholder="Optional Password"
          class="w-full md:w-1/4 border p-2 rounded" />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, watch, defineProps, defineEmits, onMounted } from "vue";
import Button from "./ui/button/Button.vue";
import Input from "./ui/input/Input.vue";

interface Download {
  name: string;
  file: File | null;
  previewImage?: string; // Base64 or object URL
  external_url: string;
  max_downloads?: number;
  expires_in_days?: number;
  password?: string;
}

const props = defineProps<{ downloads: Download[] }>();
const emit = defineEmits<{ (e: "update:downloads", value: Download[]): void }>();

const localDownloads = ref<Download[]>([]);

// Initialize with default row
onMounted(() => {
  if (!props.downloads || props.downloads.length === 0) {
    addFile();
  } else {
    localDownloads.value = [...props.downloads];
  }
});

// Watch local changes and emit to parent
watch(localDownloads, (val) => emit("update:downloads", val), { deep: true });

// Add new file row
function addFile() {
  localDownloads.value.push({
    name: "",
    file: null,
    previewImage: "",
    external_url: "",
    max_downloads: undefined,
    expires_in_days: undefined,
    password: "",
  });
}

// Remove file row
function removeFile(index: number) {
  localDownloads.value.splice(index, 1);
}

// Handle file upload
function onFileChange(event: Event, index: number) {
  const file = (event.target as HTMLInputElement).files?.[0] || null;
  if (file) localDownloads.value[index].file = file;
}

// Handle preview image upload
function onPreviewChange(event: Event, index: number) {
  const imgFile = (event.target as HTMLInputElement).files?.[0] || null;
  if (imgFile) {
    const reader = new FileReader();
    reader.onload = () => {
      localDownloads.value[index].previewImage = reader.result as string;
    };
    reader.readAsDataURL(imgFile);
  }
}
</script>
