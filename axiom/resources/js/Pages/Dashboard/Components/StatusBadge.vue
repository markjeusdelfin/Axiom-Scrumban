<template>
  <span
    class="px-3 py-1 rounded-full text-xs font-semibold whitespace-nowrap"
    :class="statusClass"
  >
    {{ statusLabel }}
  </span>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  status: String
});

const statusClass = computed(() => {
  const classes = {
    not_started: 'bg-gray-200 text-gray-800',
    in_progress: 'bg-blue-200 text-blue-800',
    blocked: 'bg-red-200 text-red-800',
    completed: 'bg-green-200 text-green-800',
  };
  return classes[props.status] || classes.not_started;
});

const statusLabel = computed(() => {
  const labels = {
    not_started: 'Not Started',
    in_progress: 'In Progress',
    blocked: 'Blocked',
    completed: 'Completed',
  };
  return labels[props.status] || props.status;
});
</script>
