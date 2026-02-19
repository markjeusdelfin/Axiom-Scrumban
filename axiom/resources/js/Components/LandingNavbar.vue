<template>
  <nav class="sticky top-0 z-50 bg-slate-900/80 backdrop-blur-xl border-b border-purple-600/20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-16">
        <!-- Logo -->
        <Link href="/" class="flex items-center space-x-2 group">
          <AxiomLogo size="md" class="group-hover:drop-shadow-xl transition-all" />
          <span class="text-lg font-black text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-purple-400 hidden sm:inline">Axiom</span>
        </Link>

        <!-- Desktop Menu -->
        <div class="hidden md:flex items-center space-x-8">
          <a href="#features" class="text-gray-300 hover:text-cyan-400 font-medium transition-colors duration-300">
            Features
          </a>
          <a href="#pricing" class="text-gray-300 hover:text-cyan-400 font-medium transition-colors duration-300">
            Pricing
          </a>
          <a href="#" class="text-gray-300 hover:text-cyan-400 font-medium transition-colors duration-300">
            Docs
          </a>
        </div>

        <!-- Auth Buttons -->
        <div class="flex items-center space-x-4">
          <Link
            v-if="canLogin"
            href="/login"
            class="text-gray-300 hover:text-white font-medium transition-colors duration-300"
          >
            Sign In
          </Link>
          <Link
            v-if="canRegister"
            href="/register"
            class="inline-flex items-center px-6 py-2 bg-gradient-to-r from-cyan-500 to-purple-600 text-white font-bold rounded-lg hover:shadow-lg hover:shadow-purple-600/50 transition-all duration-300"
          >
            Get Started
          </Link>
        </div>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import AxiomLogo from './AxiomLogo.vue';

defineProps({
  canLogin: {
    type: Boolean,
    default: true,
  },
  canRegister: {
    type: Boolean,
    default: true,
  },
});
</script>
