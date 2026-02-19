<script setup>
import { Link } from '@inertiajs/vue3';
import AxiomLogo from '@/Components/AxiomLogo.vue';
</script>

<template>
    <div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950 flex flex-col items-center justify-center px-4 py-8 relative overflow-hidden">
        <!-- Animated background elements -->
        <div class="absolute top-0 left-0 w-96 h-96 bg-cyan-500/10 rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-purple-600/10 rounded-full blur-3xl translate-x-1/2 translate-y-1/2"></div>
        
        <!-- Logo -->
        <Link href="/" class="mb-8 flex items-center space-x-2 group relative z-10">
            <AxiomLogo size="lg" class="group-hover:drop-shadow-xl transition-all" />
            <span class="text-2xl font-black text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-purple-400">Axiom</span>
        </Link>

        <!-- Card -->
        <div class="w-full max-w-md relative z-10">
            <div class="backdrop-blur-xl bg-white/10 border border-white/20 rounded-2xl px-8 py-8 shadow-2xl">
                <slot />
            </div>
        </div>

        <!-- Footer link -->
        <div class="mt-8 text-center text-gray-400 text-sm relative z-10">
            <Link href="/" class="text-cyan-400 hover:text-cyan-300 transition-colors">Back to home</Link>
        </div>
    </div>
</template>
