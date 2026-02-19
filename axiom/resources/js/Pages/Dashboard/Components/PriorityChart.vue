<template>
  <div>
    <canvas id="priorityChart"></canvas>
  </div>
</template>

<script setup>
import { onMounted } from 'vue';
import Chart from 'chart.js/auto';

const props = defineProps({
  data: Object
});

const priorityColors = {
  high: '#ef4444',
  medium: '#f59e0b',
  low: '#10b981'
};

onMounted(() => {
  const ctx = document.getElementById('priorityChart').getContext('2d');
  
  const labels = Object.keys(props.data).map(key => key.charAt(0).toUpperCase() + key.slice(1));
  const values = Object.values(props.data);
  const colors = Object.keys(props.data).map(key => priorityColors[key] || '#6b7280');

  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: labels,
      datasets: [
        {
          label: 'Tasks',
          data: values,
          backgroundColor: colors,
          borderColor: colors.map(c => c + 'dd'),
          borderWidth: 1,
          borderRadius: 4
        }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: true,
      plugins: {
        legend: {
          display: false
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            stepSize: 1
          }
        }
      }
    }
  });
});
</script>
