<template>
  <div :class="`relative rounded-2xl transition-all duration-500 flex flex-col h-full group backdrop-blur-xl ${isPopular ? 'lg:scale-105 md:ring-2 md:ring-cyan-500/50 bg-gradient-to-br from-cyan-500/10 via-purple-500/5 to-transparent border-2 border-cyan-500/40 shadow-2xl shadow-purple-600/40' : 'bg-gradient-to-br from-white/5 to-white/[0.02] border-2 border-white/10 hover:border-white/20 hover:shadow-2xl hover:shadow-purple-600/20'}`">
    <!-- Popular Badge (outside overflow-hidden container) -->
    <div v-if="isPopular" class="absolute -top-4 left-1/2 transform -translate-x-1/2 z-50">
      <div class="inline-flex items-center space-x-2 bg-gradient-to-r from-cyan-500 to-purple-600 text-white px-5 py-2 rounded-full text-xs font-black uppercase tracking-wider shadow-lg whitespace-nowrap">
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
          <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
        </svg>
        <span>MOST POPULAR</span>
      </div>
    </div>

    <!-- Card Content Wrapper -->
    <div class="relative overflow-hidden rounded-2xl flex-1 flex flex-col">
      <!-- Animated Gradient Border for Popular -->
      <div v-if="isPopular" class="absolute inset-0 bg-gradient-to-r from-cyan-500 via-transparent to-purple-600 opacity-0 group-hover:opacity-20 transition-opacity duration-500 pointer-events-none"></div>

      <!-- Shine Effect -->
      <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white to-transparent opacity-0 group-hover:opacity-5 transition-opacity duration-500 translate-x-full group-hover:translate-x-0 pointer-events-none"></div>

      <!-- Content -->
      <div class="relative p-8 md:p-10 flex-1 flex flex-col z-10">
      <!-- Header -->
      <div class="mb-8">
        <h3 class="text-3xl md:text-2xl font-black text-white mb-2 tracking-tight">
          {{ plan.name }}
        </h3>
        <p class="text-gray-400 text-sm leading-relaxed">{{ plan.description }}</p>
      </div>

      <!-- Price -->
      <div class="mb-10 pb-10 border-b border-white/10">
        <div class="flex items-baseline gap-1">
          <span class="text-5xl md:text-6xl font-black bg-clip-text text-transparent bg-gradient-to-r from-white to-gray-300">{{ plan.price }}</span>
          <div v-if="plan.period" class="flex flex-col">
            <span class="text-gray-400 text-sm font-medium">{{ plan.period }}</span>
          </div>
        </div>
        <p v-if="plan.price === 'Custom'" class="text-gray-500 text-xs mt-3 leading-relaxed">Billed annually with volume discounts. Contact our sales team for details.</p>
      </div>

      <!-- Features -->
      <ul class="space-y-3.5 mb-12 flex-1">
        <li v-for="(feature, index) in plan.features" :key="index" class="flex items-start gap-3 group/item">
          <div class="mt-1 flex-shrink-0">
            <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
            </svg>
          </div>
          <span class="text-gray-300 text-sm leading-relaxed group-hover/item:text-white transition-colors">{{ feature }}</span>
        </li>
      </ul>

      <!-- CTA Button -->
      <Link
        :href="plan.cta.link"
        :class="`w-full py-3.5 px-6 rounded-xl font-black text-center transition-all duration-300 relative overflow-hidden group/btn flex items-center justify-center gap-2 ${isPopular ? 'bg-gradient-to-r from-cyan-500 to-purple-600 text-white hover:shadow-2xl hover:shadow-purple-600/50 hover:scale-105 active:scale-95' : 'bg-white/5 border border-white/20 text-white hover:bg-white/10 hover:border-white/40 hover:shadow-lg'}`"
      >
        <span class="relative z-10 font-bold">{{ plan.cta.text }}</span>
        <svg class="w-4 h-4 relative z-10 group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
        </svg>
      </Link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
  plan: {
    type: Object,
    required: true,
  },
  isPopular: {
    type: Boolean,
    default: false,
  },
});
</script>
