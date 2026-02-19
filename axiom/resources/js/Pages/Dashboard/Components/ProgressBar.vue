<template>
  <div class="w-full">
    <div class="bg-gray-200 rounded-full h-2 overflow-hidden">
      <div
        class="h-full bg-gradient-to-r from-blue-500 to-blue-600 rounded-full transition-all duration-300"
        :style="{ width: `${percentage}%` }"
      ></div>
    </div>
    <p class="text-xs text-gray-600 mt-1">{{ percentage }}%</p>
  </div>
</template>

<script setup>
const props = defineProps({
  percentage: {
    type: Number,
    default: 0
  }
});
</script>
