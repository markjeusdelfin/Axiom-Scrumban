<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
  <section class="relative py-20 md:py-32 bg-gradient-to-r from-slate-900 via-purple-900 to-slate-900 overflow-hidden">
    <!-- Animated Orbs -->
    <div class="absolute -top-40 -right-40 w-80 h-80 bg-purple-600 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
    <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-cyan-600 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>

    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
      <div class="inline-block mb-6 px-4 py-2 rounded-full bg-gradient-to-r from-cyan-600/20 to-purple-600/20 border border-purple-500/30">
        <span class="text-sm text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-purple-400 font-semibold">
          🚀 Ready to Launch?
        </span>
      </div>

      <h2 class="text-5xl md:text-6xl font-black text-white mb-6 leading-tight">
        The Future of Project
        <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-purple-400">
          Management Starts Here
        </span>
      </h2>
      <p class="text-xl text-gray-300 mb-12 leading-relaxed max-w-2xl mx-auto">
        Join hundreds of teams transforming their workflows. Start for free, upgrade when you're ready.
      </p>

      <!-- CTA Buttons -->
      <div class="flex flex-col sm:flex-row justify-center gap-4 mb-12">
        <Link
          href="/register"
          class="group relative px-8 py-4 bg-gradient-to-r from-cyan-500 to-purple-600 text-white font-black rounded-xl hover:shadow-2xl hover:shadow-purple-600/50 transition-all duration-300 overflow-hidden"
        >
          <div class="absolute inset-0 bg-gradient-to-r from-purple-600 to-cyan-500 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
          <span class="relative flex items-center justify-center">
            Start Building Free
            <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
            </svg>
          </span>
        </Link>
        <Link
          href="/login"
          class="px-8 py-4 bg-white/10 border border-white/20 text-white font-black rounded-xl hover:bg-white/15 hover:border-white/40 transition-all duration-300 backdrop-blur-sm flex items-center justify-center"
        >
          Sign In
        </Link>
      </div>

      <!-- Trust Message -->
      <div class="flex flex-col sm:flex-row items-center justify-center gap-6 text-sm text-gray-300">
        <div class="flex items-center gap-2">
          <svg class="w-5 h-5 text-cyan-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M5 13a3 3 0 105.119 2.821A3.571 3.571 0 0113.5 13h1.5a1 1 0 100-2h-1.5A5 5 0 005 13zm12-1h-1.5a3.571 3.571 0 00-2.381 2.821A3 3 0 1017 12z" clip-rule="evenodd"></path>
          </svg>
          <span>500+ Teams Active</span>
        </div>
        <div class="w-1 h-1 bg-gray-600 rounded-full hidden sm:block"></div>
        <div class="flex items-center gap-2">
          <svg class="w-5 h-5 text-purple-400" fill="currentColor" viewBox="0 0 20 20">
            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
          </svg>
          <span>Rated 4.9/5</span>
        </div>
      </div>
    </div>
  </section>
</template>

<style scoped>
@keyframes blob {
  0%, 100% {
    transform: translate(0, 0) scale(1);
  }
  33% {
    transform: translate(30px, -50px) scale(1.1);
  }
  66% {
    transform: translate(-20px, 20px) scale(0.9);
  }
}

.animate-blob {
  animation: blob 7s infinite;
}

.animation-delay-2000 {
  animation-delay: 2s;
}
</style>
