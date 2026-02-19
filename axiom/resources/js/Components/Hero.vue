<template>
  <div class="relative min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950 overflow-hidden pt-20">
    <!-- Animated Background Grid -->
    <div class="absolute inset-0 opacity-5">
      <div class="absolute inset-0 bg-grid-pattern"></div>
    </div>

    <!-- Floating Orbs -->
    <div class="absolute -top-40 -right-40 w-96 h-96 bg-purple-600 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
    <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-cyan-600 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
    <div class="absolute top-1/2 left-1/2 w-96 h-96 bg-pink-600 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-4000"></div>

    <!-- Decorative Lines -->
    <div class="absolute top-20 left-10 w-1 h-32 bg-gradient-to-b from-cyan-500/50 to-transparent rounded-full"></div>
    <div class="absolute bottom-40 right-20 w-1 h-40 bg-gradient-to-t from-purple-500/50 to-transparent rounded-full"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center min-h-screen">
        <!-- Left: Text Content -->
        <div class="space-y-10 z-10">
          <div class="space-y-6">
            <!-- Badge -->
            <div class="inline-block">
              <div class="flex items-center space-x-2 px-4 py-2 rounded-full bg-gradient-to-r from-purple-600/20 to-cyan-600/20 border border-cyan-500/40 backdrop-blur-sm hover:border-cyan-500/60 transition-all duration-300">
                <div class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></div>
                <span class="text-sm text-transparent bg-clip-text bg-gradient-to-r from-cyan-300 to-purple-300 font-bold">
                  ✨ Now Live & Powerful
                </span>
              </div>
            </div>

            <!-- Main Headline -->
            <h1 class="text-6xl lg:text-8xl font-black text-white leading-tight space-y-3 tracking-tight">
              <div class="relative inline-block">
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-300 via-purple-300 to-pink-300 animate-gradient">
                  Next-Gen
                </span>
              </div>
              <div class="relative">Project</div>
              <div class="flex items-center gap-2">
                <span>Management</span>
                <svg class="w-12 h-12 text-cyan-400 animate-float" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
              </div>
            </h1>
          </div>

          <!-- Subtitle with Icon -->
          <div class="flex gap-4">
            <div class="flex-shrink-0">
              <div class="flex items-center justify-center h-12 w-12 rounded-lg bg-gradient-to-br from-cyan-500/20 to-purple-600/20 border border-cyan-500/30">
                <svg class="h-6 w-6 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
              </div>
            </div>
            <p class="text-lg lg:text-xl text-gray-300 leading-relaxed max-w-lg">
              Real-time collaboration, intelligent workflows, and powerful analytics. Built for teams that move fast and think ahead.
            </p>
          </div>

          <!-- Stats with Icons -->
          <div class="grid grid-cols-3 gap-4 py-10 border-y border-white/10">
            <div class="group hover:bg-white/5 p-3 rounded-lg transition-all">
              <div class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-400 group-hover:scale-110 transition-transform">500+</div>
              <div class="text-xs text-gray-400 mt-1 group-hover:text-gray-300">Teams Trust Us</div>
            </div>
            <div class="group hover:bg-white/5 p-3 rounded-lg transition-all">
              <div class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-pink-400 group-hover:scale-110 transition-transform">99.9%</div>
              <div class="text-xs text-gray-400 mt-1 group-hover:text-gray-300">Guaranteed Uptime</div>
            </div>
            <div class="group hover:bg-white/5 p-3 rounded-lg transition-all">
              <div class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-pink-400 to-cyan-400 group-hover:scale-110 transition-transform">24/7</div>
              <div class="text-xs text-gray-400 mt-1 group-hover:text-gray-300">Expert Support</div>
            </div>
          </div>

          <!-- CTA Buttons -->
          <div class="flex flex-wrap gap-4 pt-6">
            <Link
              href="/register"
              class="group relative px-8 py-4 bg-gradient-to-r from-cyan-500 to-purple-600 text-white font-bold rounded-xl hover:shadow-2xl hover:shadow-purple-600/60 transition-all duration-300 overflow-hidden active:scale-95"
            >
              <div class="absolute inset-0 bg-gradient-to-r from-purple-600 to-cyan-500 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
              <span class="relative flex items-center whitespace-nowrap">
                Get Started Free
                <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
              </span>
            </Link>
            <Link
              href="/login"
              class="group px-8 py-4 bg-white/5 border-2 border-white/20 text-white font-bold rounded-xl hover:bg-white/10 hover:border-white/40 transition-all duration-300 backdrop-blur-sm hover:shadow-lg active:scale-95"
            >
              <span class="flex items-center gap-2">
                Sign In
                <svg class="w-4 h-4 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
              </span>
            </Link>
          </div>

          <!-- Trust Badge -->
          <div class="flex items-center gap-3 text-sm text-gray-400">
            <div class="flex -space-x-1">
              <div class="w-6 h-6 bg-gradient-to-br from-cyan-400 to-blue-600 rounded-full border border-white/20"></div>
              <div class="w-6 h-6 bg-gradient-to-br from-purple-400 to-pink-600 rounded-full border border-white/20"></div>
              <div class="w-6 h-6 bg-gradient-to-br from-pink-400 to-red-600 rounded-full border border-white/20"></div>
            </div>
            <span>Join 500+ teams already building the future</span>
          </div>
        </div>

        <!-- Right: Visual Element -->
        <div class="hidden lg:flex justify-center items-center z-10 relative">
          <!-- Floating Cards -->
          <div class="absolute top-10 -right-10 w-40 h-40 bg-gradient-to-br from-cyan-500/10 to-transparent rounded-2xl border border-cyan-500/30 backdrop-blur-md p-4 shadow-xl transform hover:scale-110 transition-transform duration-300 floating-card-1">
            <div class="text-cyan-400 font-bold text-sm mb-2">✓ Real-time Sync</div>
            <div class="text-gray-400 text-xs">Instant updates across all devices</div>
          </div>

          <div class="absolute bottom-20 -left-10 w-40 h-40 bg-gradient-to-br from-purple-500/10 to-transparent rounded-2xl border border-purple-500/30 backdrop-blur-md p-4 shadow-xl transform hover:scale-110 transition-transform duration-300 floating-card-2">
            <div class="text-purple-400 font-bold text-sm mb-2">⚡ Lightning Fast</div>
            <div class="text-gray-400 text-xs">Load times under 1 second</div>
          </div>

          <!-- Main Card Container -->
          <div class="relative w-full h-full max-h-96 max-w-sm">
            <!-- Card -->
            <div class="absolute inset-0 bg-gradient-to-br from-cyan-600/15 via-purple-600/10 to-pink-600/15 rounded-3xl border border-purple-500/30 backdrop-blur-xl shadow-2xl p-8 space-y-6 hover:border-purple-500/50 transition-all duration-300">
              <!-- Header with Window Controls -->
              <div class="flex items-center justify-between">
                <div class="flex space-x-2">
                  <div class="w-3 h-3 rounded-full bg-red-400/80"></div>
                  <div class="w-3 h-3 rounded-full bg-yellow-400/80"></div>
                  <div class="w-3 h-3 rounded-full bg-green-400/80"></div>
                </div>
                <span class="text-xs text-gray-500 font-mono">axiom.dev</span>
              </div>

              <!-- Animated Content -->
              <div class="space-y-4">
                <div class="h-4 bg-gradient-to-r from-cyan-400/40 to-purple-400/40 rounded-lg w-4/5 animate-pulse"></div>
                <div class="h-3 bg-purple-400/20 rounded w-3/5 animate-pulse" style="animation-delay: 0.1s"></div>
                <div class="h-3 bg-cyan-400/20 rounded w-2/3 animate-pulse" style="animation-delay: 0.2s"></div>
              </div>

              <!-- Code Block -->
              <div class="bg-black/50 rounded-xl p-4 font-mono text-xs space-y-2 border border-green-400/20">
                <div>
                  <span class="text-cyan-400">const</span>
                  <span class="text-white"> projects =</span>
                  <span class="text-yellow-400"> await</span>
                  <span class="text-green-400">.connect()</span>
                </div>
                <div>
                  <span class="text-green-400">// 500+ teams managed</span>
                </div>
              </div>

              <!-- Progress Bar with Animation -->
              <div class="space-y-2">
                <div class="flex justify-between text-xs text-gray-400">
                  <span>Performance</span>
                  <span class="text-cyan-400">99.8%</span>
                </div>
                <div class="w-full h-2 bg-gray-700/50 rounded-full overflow-hidden">
                  <div class="h-full bg-gradient-to-r from-green-400 via-cyan-400 to-purple-400 rounded-full" style="width: 99.8%; animation: pulse-width 2s ease-in-out infinite;"></div>
                </div>
              </div>
            </div>
          </div>

          <!-- Floating Badge -->
          <div class="absolute top-0 right-0 bg-gradient-to-r from-emerald-500/20 to-cyan-500/20 border border-emerald-500/40 rounded-full px-4 py-2 text-xs text-emerald-300 font-semibold backdrop-blur-md floating-badge">
            ✨ Enterprise Ready
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { defineProps } from 'vue';
import { Link } from '@inertiajs/vue3';
import AxiomLogo from './AxiomLogo.vue';

defineProps({
  canRegister: {
    type: Boolean,
    default: true,
  },
});
</script>

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

@keyframes pulse-width {
  0%, 100% {
    box-shadow: 0 0 20px rgba(34, 197, 94, 0.5);
  }
  50% {
    box-shadow: 0 0 40px rgba(6, 182, 212, 0.8);
  }
}

@keyframes float {
  0%, 100% {
    transform: translateY(0px);
  }
  50% {
    transform: translateY(-20px);
  }
}

@keyframes gradient-shift {
  0%, 100% {
    background-position: 0% 50%;
  }
  50% {
    background-position: 100% 50%;
  }
}

@keyframes floating-card-1 {
  0%, 100% {
    transform: translateY(0px) rotate(-5deg);
  }
  50% {
    transform: translateY(-30px) rotate(-5deg);
  }
}

@keyframes floating-card-2 {
  0%, 100% {
    transform: translateY(0px) rotate(3deg);
  }
  50% {
    transform: translateY(30px) rotate(3deg);
  }
}

@keyframes floating-badge {
  0%, 100% {
    transform: translateY(0px);
  }
  50% {
    transform: translateY(-10px);
  }
}

.animate-blob {
  animation: blob 7s infinite;
}

.animate-float {
  animation: float 3s ease-in-out infinite;
}

.animate-gradient {
  background-size: 200% 200%;
  animation: gradient-shift 3s ease infinite;
}

.floating-card-1 {
  animation: floating-card-1 4s ease-in-out infinite;
}

.floating-card-2 {
  animation: floating-card-2 5s ease-in-out infinite;
}

.floating-badge {
  animation: floating-badge 3s ease-in-out infinite;
}

.animation-delay-2000 {
  animation-delay: 2s;
}

.animation-delay-4000 {
  animation-delay: 4s;
}

.bg-grid-pattern {
  background-image:
    linear-gradient(rgba(255, 255, 255, 0.03) 1px, transparent 1px),
    linear-gradient(90deg, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
  background-size: 50px 50px;
}
</style>
