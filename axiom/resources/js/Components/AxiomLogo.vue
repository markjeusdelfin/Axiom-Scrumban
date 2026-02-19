<script setup>
defineProps({
  size: {
    type: String,
    default: 'md',
  },
});

const sizeMap = {
  sm: { icon: 20, container: 'w-6 h-6' },
  md: { icon: 28, container: 'w-10 h-10' },
  lg: { icon: 36, container: 'w-12 h-12' },
  xl: { icon: 48, container: 'w-16 h-16' },
};
</script>

<template>
  <div :class="sizeMap[size].container" class="flex items-center justify-center relative">
    <!-- Glow effect background -->
    <div class="absolute inset-0 bg-gradient-to-br from-cyan-500 to-purple-600 rounded-lg opacity-20 blur-md"></div>
    
    <!-- Icon background circle -->
    <div class="absolute inset-0 bg-gradient-to-br from-cyan-500 to-purple-600 rounded-lg opacity-10"></div>
    
    <!-- Lucide Layers Icon with gradient -->
    <div class="relative z-10">
      <svg :width="sizeMap[size].icon" :height="sizeMap[size].icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="drop-shadow-lg">
        <defs>
          <linearGradient id="layerGradient" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" style="stop-color: #06b6d4; stop-opacity: 1" />
            <stop offset="100%" style="stop-color: #9333ea; stop-opacity: 1" />
          </linearGradient>
        </defs>
        <!-- Layer 1 (top) -->
        <path d="M3.27 6.96l8.73 5.04 8.73-5.04M3.27 17.04l8.73 5.04 8.73-5.04M3.27 12l8.73 5.04 8.73-5.04" stroke="url(#layerGradient)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
      </svg>
    </div>
  </div>
</template>
