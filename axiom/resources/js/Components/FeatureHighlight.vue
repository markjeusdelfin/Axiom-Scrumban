<template>
  <div :class="`grid grid-cols-1 lg:grid-cols-2 gap-16 items-center ${imagePosition === 'left' ? 'lg:grid-flow-col-dense' : ''}`">
    <!-- Text Content -->
    <div :class="{ 'lg:col-start-2': imagePosition === 'left' }">
      <h3 class="text-4xl lg:text-5xl font-black text-white mb-6 leading-tight">
        {{ title }}
      </h3>
      <p class="text-lg text-gray-400 mb-8 leading-relaxed">
        {{ description }}
      </p>
      
      <!-- Points List -->
      <ul class="space-y-4">
        <li v-for="(point, index) in points" :key="index" class="flex items-start group cursor-pointer">
          <div class="flex-shrink-0 h-6 w-6 flex items-center justify-center rounded-lg bg-gradient-to-br from-cyan-500 to-purple-600 group-hover:scale-110 transition-transform duration-300 mt-0.5">
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
            </svg>
          </div>
          <span class="text-gray-300 font-semibold ml-4 group-hover:text-transparent group-hover:bg-clip-text group-hover:bg-gradient-to-r group-hover:from-cyan-400 group-hover:to-purple-400 transition-all duration-300">
            {{ point }}
          </span>
        </li>
      </ul>
    </div>

    <!-- Visual Element -->
    <div :class="{ 'lg:col-start-1': imagePosition === 'left' }">
      <div class="relative bg-gradient-to-br from-cyan-600/10 to-purple-600/10 rounded-2xl p-8 min-h-80 flex items-center justify-center border border-purple-600/20 backdrop-blur-sm group hover:border-cyan-500/30 transition-all duration-300">
        <!-- Glow Effect -->
        <div class="absolute inset-0 opacity-0 group-hover:opacity-100 bg-gradient-to-br from-cyan-600/5 to-purple-600/5 rounded-2xl transition-opacity duration-300"></div>

        <!-- Icon -->
        <div class="relative text-center">
          <div class="flex justify-center mb-6">
            <div class="relative">
              <div class="w-20 h-20 bg-gradient-to-br from-cyan-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-2xl animate-pulse">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
              </div>
              <div class="absolute -inset-2 bg-gradient-to-br from-cyan-500 to-purple-600 rounded-2xl opacity-0 group-hover:opacity-20 blur-xl transition-opacity duration-300"></div>
            </div>
          </div>
          <p class="text-gray-400 font-semibold">{{ title }}</p>
        </div>
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
  points: {
    type: Array,
    required: true,
  },
  imagePosition: {
    type: String,
    default: 'right',
    validator: (value) => ['left', 'right'].includes(value),
  },
});
</script>
