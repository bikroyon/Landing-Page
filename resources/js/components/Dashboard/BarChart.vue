<template>
  <div class="w-full h-[120px]">
    <Bar :data="chartData" :options="chartOptions" />
  </div>
</template>

<script lang="ts" setup>
import { computed } from 'vue'
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  BarElement,
  CategoryScale,
  LinearScale
} from 'chart.js'
import { Bar } from 'vue-chartjs'

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)

// Props: dynamic time slots and percentages
const props = defineProps<{
  times?: string[]        // Labels like "8 AM - 11 AM"
  values?: number[]       // Percentages like 38, 31
  label?: string
  color?: string
}>();

const times = props.times || ['8 AM - 11 AM', '12 PM - 3 PM', '4 PM - 6 PM', '7 PM - 10 PM']
const values = props.values || [38, 31, 31, 22]

// Computed chart data
const chartData = computed(() => ({
  labels: times,
  datasets: [
    {
      label: props.label || 'Orders (%)',
      data: values,
      backgroundColor: props.color || '#22c55e',
      borderRadius: 6,
      barPercentage: 0.6
    }
  ]
}))

// Chart options — hide right side values (y-axis)
const chartOptions = computed(() => ({
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: { display: false },
    tooltip: { enabled: true }
  },
  scales: {
    x: {
      grid: { display: false },
      ticks: { color: '#374151', font: { size: 12 } }
    },
    y: {
      grid: { display: false },   // Hide horizontal grid lines
      beginAtZero: true,
      max: 100
    }
  }
}))
</script>
