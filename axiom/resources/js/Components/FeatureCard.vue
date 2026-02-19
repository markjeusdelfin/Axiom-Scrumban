<template>
  <div class="group relative overflow-hidden rounded-2xl p-6 transition-all duration-500">
    <!-- Background Gradient -->
    <div class="absolute inset-0 bg-gradient-to-br from-slate-800 to-slate-900 border border-purple-600/20"></div>
    
    <!-- Hover Effect Background -->
    <div class="absolute inset-0 bg-gradient-to-br from-purple-600/10 to-cyan-600/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>

    <!-- Content -->
    <div class="relative space-y-4">
      <!-- Icon Container -->
      <div :class="`w-12 h-12 rounded-xl bg-gradient-to-br ${color} p-2.5 group-hover:scale-110 transition-transform duration-500 shadow-lg group-hover:shadow-xl`">
        <svg class="w-full h-full text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path :d="icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
        </svg>
      </div>

      <!-- Title -->
      <h3 class="text-xl font-bold text-white group-hover:text-transparent group-hover:bg-clip-text group-hover:bg-gradient-to-r group-hover:from-cyan-400 group-hover:to-purple-400 transition-all duration-300">
        {{ title }}
      </h3>

      <!-- Description -->
      <p class="text-gray-400 leading-relaxed group-hover:text-gray-300 transition-colors duration-300">
        {{ description }}
      </p>

      <!-- Arrow -->
      <div class="flex items-center text-cyan-400 font-semibold pt-2 group-hover:translate-x-2 transition-transform duration-300">
        <span class="text-sm">Learn more</span>
        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  title: {
    type: String,
    required: true,
  },
  description: {
    type: String,
    required: true,
  },
  icon: {
    type: String,
    required: true,
  },
  color: {
    type: String,
    default: 'from-indigo-500 to-indigo-600',
  },
});
</script>
