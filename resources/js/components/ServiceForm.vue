<template>
  <div class="space-y-4 border p-4 rounded bg-gray-50">
    <h2 class="text-lg font-semibold mb-2">Service Details</h2>
        <!-- Base Price -->
        <div class="flex gap-2 items-center mb-3">
            <Input v-model="price" type="number" step="0.01" class="w-full border p-2 rounded"
                placeholder="Base Price" />
        </div>
    <!-- Service Type -->
    <div>
      <label class="block font-medium mb-1">Service Type</label>
      <select v-model="localService.type" class="w-full border p-2 rounded">
        <option value="course">Course</option>
        <option value="consultation">Consultation</option>
        <option value="workshop">Workshop</option>
      </select>
    </div>

    <!-- Start Date -->
    <div>
      <label class="block font-medium mb-1">Start Date</label>
      <input type="date" v-model="localService.start_date" class="w-full border p-2 rounded" />
    </div>

    <!-- End Date (optional for one-time sessions) -->
    <div v-if="localService.type === 'course' || localService.type === 'workshop'">
      <label class="block font-medium mb-1">End Date</label>
      <input type="date" v-model="localService.end_date" class="w-full border p-2 rounded" />
    </div>

    <!-- Days of the Week -->
    <div v-if="localService.type === 'course'">
      <label class="block font-medium mb-1">Days of Week</label>
      <div class="flex gap-2 flex-wrap">
        <label v-for="day in weekDays" :key="day" class="flex items-center gap-1">
          <input type="checkbox" :value="day" v-model="localService.days_of_week" />
          <span>{{ day }}</span>
        </label>
      </div>
    </div>

    <!-- Time of Day -->
    <div>
      <label class="block font-medium mb-1">Time (per session)</label>
      <input type="time" v-model="localService.time" class="w-full border p-2 rounded" />
    </div>

    <!-- Duration -->
    <div>
      <label class="block font-medium mb-1">Duration (hours)</label>
      <input type="number" min="0.5" step="0.5" v-model="localService.duration_per_session" class="w-full border p-2 rounded" />
    </div>

    <!-- Max Participants -->
    <div>
      <label class="block font-medium mb-1">Max Participants</label>
      <input type="number" min="1" v-model="localService.max_participants" class="w-full border p-2 rounded" />
    </div>

    <!-- Notes / Instructions -->
    <div>
      <label class="block font-medium mb-1">Notes / Requirements</label>
      <textarea v-model="localService.notes" rows="3" class="w-full border p-2 rounded"></textarea>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, watch, defineProps, defineEmits } from "vue";

interface Service {
  type: string;
  start_date: string;
  end_date: string;
  days_of_week: string[];
  time: string;
  duration_per_session: number;
  max_participants: number;
  notes: string;
}

const props = defineProps<{ service: Service }>();
const emit = defineEmits<{ (e: "update:service", value: Service): void }>();

const weekDays = ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"];

const localService = ref<Service>({ ...props.service });

// Watch local changes and emit to parent
watch(localService, (val) => emit("update:service", val), { deep: true });
</script>
