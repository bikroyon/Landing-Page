<template>
  <div>
    <label class="flex items-center cursor-pointer">
      <div class="relative">
        <!-- Hidden Input -->
        <input type="checkbox" class="sr-only" :checked="modelValue"
          @change="$emit('update:modelValue', $event.target.checked)" />
        <!-- Line -->
        <div class="block bg-gray-300 w-14 h-8 rounded-full transition"
          :class="modelValue ? 'bg-gray-600' : 'bg-gray-400'"></div>
        <!-- Dot -->
        <div class="dot absolute left-1 top-1 bg-white w-6 h-6 rounded-full transition"
          :class="modelValue ? 'translate-x-6' : 'translate-x-0'"></div>
      </div>
      <div class="ml-3 text-gray-700 text-sm">
        {{ modelValue ? activeLabel : inactiveLabel }}
      </div>
    </label>
  </div>
</template>

<script lang="ts" setup>
defineProps({
  modelValue: Boolean,
  activeLabel: {
    type: String,
    default: 'Active',
  },
  inactiveLabel: {
    type: String,
    default: 'Inactive',
  }
})

defineEmits(['update:modelValue'])
</script>

<style scoped>
.dot {
  will-change: transform;
}
</style>
