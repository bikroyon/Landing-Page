<template>
  <div class="w-full h-[300px]">
    <Line :data="chartData" :options="chartOptions" />
  </div>
</template>

<script lang="ts" setup>
import { computed } from 'vue'
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend
} from 'chart.js'
import { Line } from 'vue-chartjs'

ChartJS.register(
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend
)

// Props
const props = defineProps<{
  isWeekly?: boolean  // true = last 7 days, false = yearly
  weeklyData?: number[]
  yearlyData?: number[]
  weeklyLabels?: string[]
  yearlyLabels?: string[]
}>()

// Default weekly/yearly data
const defaultWeeklyLabels = props.weeklyLabels || ['Mon','Tue','Wed','Thu','Fri','Sat','Sun']
const defaultYearlyLabels = props.yearlyLabels || ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec']
const defaultWeeklyData = props.weeklyData || [20, 35, 40, 55, 70, 50, 60]
const defaultYearlyData = props.yearlyData || [20, 35, 40, 55, 70, 90, 120, 110, 95, 80, 60, 40]

// Computed chart data
const chartData = computed(() => {
  if (props.isWeekly) {
    return {
      labels: defaultWeeklyLabels,
      datasets: [
        {
          label: 'Weekly Sales',
          data: defaultWeeklyData,
          borderColor: '#000000',
          backgroundColor: 'rgba(16, 185, 129, 0.2)',
          borderWidth: 2,
          pointRadius: 3,
          tension: 0.4
        }
      ]
    }
  } else {
    return {
      labels: defaultYearlyLabels,
      datasets: [
        {
          label: 'Yearly Sales',
          data: defaultYearlyData,
          borderColor: '#000000',
          backgroundColor: 'rgba(16, 185, 129, 0.2)',
          borderWidth: 2,
          pointRadius: 3,
          tension: 0.4
        }
      ]
    }
  }
})

// Chart options
const chartOptions = computed(() => ({
  responsive: true,
  maintainAspectRatio: false,
  scales: {
    x: {
      grid: { display: false, color: '#E5E7EB', lineWidth: 1, borderDash: [4, 4] },
      ticks: { color: '#6B7280' }
    },
    y: {
      grid: { display: false, color: '#D1D5DB', lineWidth: 1.5, borderDash: [3, 3] },
      ticks: { color: '#6B7280' }
    }
  }
}))
</script>
