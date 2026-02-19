<template>
  <span
    class="px-3 py-1 rounded-full text-xs font-semibold"
    :class="priorityClass"
  >
    {{ priorityLabel }}
  </span>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  priority: String
});

const priorityClass = computed(() => {
  const classes = {
    high: 'bg-red-200 text-red-800',
    medium: 'bg-yellow-200 text-yellow-800',
    low: 'bg-green-200 text-green-800',
  };
  return classes[props.priority] || classes.medium;
});

const priorityLabel = computed(() => {
  return props.priority?.charAt(0).toUpperCase() + props.priority?.slice(1) || '';
});
</script>
