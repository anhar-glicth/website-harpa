<template>
  <div class="w-full h-full">
    <Doughnut :data="chartData" :options="chartOptions" />
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { Doughnut } from 'vue-chartjs';
import {
  Chart as ChartJS,
  ArcElement,
  Tooltip,
  Legend
} from 'chart.js';

ChartJS.register(ArcElement, Tooltip, Legend);

const props = defineProps({
  data: {
    type: Array,
    default: () => []
  }
});

const chartData = computed(() => ({
  labels: props.data.map(d => d.name),
  datasets: [
    {
      backgroundColor: [
        '#16a34a',
        '#2563eb',
        '#d97706',
        '#7c3aed',
        '#db2777',
        '#475569'
      ],
      hoverOffset: 15,
      borderWidth: 0,
      data: props.data.map(d => d.value)
    }
  ]
}));

const chartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  cutout: '75%',
  plugins: {
    legend: {
      position: 'bottom',
      labels: {
        usePointStyle: true,
        pointStyle: 'circle',
        padding: 20,
        font: { size: 10, weight: 'bold' },
        color: '#64748b'
      }
    },
    tooltip: {
      backgroundColor: '#1e293b',
      titleFont: { size: 10, weight: 'bold' },
      bodyFont: { size: 12, weight: 'bold' },
      padding: 12,
      cornerRadius: 12,
      callbacks: {
        label: (context) => ` Rp ${context.raw.toLocaleString()}`
      }
    }
  }
};
</script>
