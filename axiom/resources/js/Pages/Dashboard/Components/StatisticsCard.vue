<template>
  <div class="p-6 bg-gradient-to-br rounded-lg shadow" :class="colorClasses">
    <div class="flex justify-between items-start">
      <div>
        <p class="text-sm font-medium opacity-75">{{ title }}</p>
        <p class="text-3xl font-bold mt-2">{{ value }}</p>
        <p class="text-xs opacity-75 mt-2">{{ subtitle }}</p>
      </div>
      <div class="text-4xl">{{ icon }}</div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  title: String,
  value: [String, Number],
  subtitle: String,
  icon: String,
  color: {
    type: String,
    default: 'blue'
  }
});

const colorClasses = computed(() => {
  const colors = {
    blue: 'from-blue-50 to-blue-100 text-blue-900 border-l-4 border-blue-500',
    yellow: 'from-yellow-50 to-yellow-100 text-yellow-900 border-l-4 border-yellow-500',
    red: 'from-red-50 to-red-100 text-red-900 border-l-4 border-red-500',
    green: 'from-green-50 to-green-100 text-green-900 border-l-4 border-green-500',
  };
  return colors[props.color] || colors.blue;
});
</script>
