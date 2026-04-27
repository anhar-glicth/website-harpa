<template>
  <div class="w-full h-full">
    <Line :data="chartData" :options="chartOptions" />
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { Line } from 'vue-chartjs';
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend,
  Filler
} from 'chart.js';

ChartJS.register(
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend,
  Filler
);

const props = defineProps({
  data: {
    type: Array,
    default: () => []
  }
});

const chartData = computed(() => ({
  labels: props.data.map(d => d.month),
  datasets: [
    {
      label: 'Total Donasi (Rp)',
      backgroundColor: 'rgba(22, 163, 74, 0.1)',
      borderColor: '#16a34a',
      pointBackgroundColor: '#16a34a',
      pointBorderColor: '#fff',
      pointHoverBackgroundColor: '#fff',
      pointHoverBorderColor: '#16a34a',
      fill: true,
      tension: 0.4,
      data: props.data.map(d => d.total),
      borderWidth: 3,
      pointRadius: 4,
      pointHoverRadius: 6
    }
  ]
}));

const chartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      display: false
    },
    tooltip: {
      backgroundColor: '#1e293b',
      titleFont: { size: 10, weight: 'bold' },
      bodyFont: { size: 12, weight: 'bold' },
      padding: 12,
      cornerRadius: 12,
      displayColors: false,
      callbacks: {
        label: (context) => `Rp ${context.raw.toLocaleString()}`
      }
    }
  },
  scales: {
    y: {
      beginAtZero: true,
      grid: {
        display: true,
        color: 'rgba(0,0,0,0.02)',
        drawBorder: false
      },
      ticks: {
        font: { size: 10, weight: 'bold' },
        color: '#94a3b8',
        callback: (value) => value >= 1000000 ? (value / 1000000) + 'jt' : value.toLocaleString()
      }
    },
    x: {
      grid: {
        display: false
      },
      ticks: {
        font: { size: 10, weight: 'bold' },
        color: '#94a3b8'
      }
    }
  }
};
</script>
