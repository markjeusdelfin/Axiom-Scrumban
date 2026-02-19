<script setup>
import AxiomLogo from './AxiomLogo.vue';
</script>

<template>
  <footer class="bg-gradient-to-b from-slate-950 to-slate-900 text-gray-400 border-t border-cyan-500/10 relative overflow-hidden">
    <!-- Gradient background -->
    <div class="absolute inset-0 bg-gradient-to-r from-cyan-500/5 via-transparent to-purple-600/5 pointer-events-none"></div>
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
      <!-- Main Grid -->
      <div class="grid grid-cols-1 md:grid-cols-5 gap-12 mb-16">
        <!-- Brand -->
        <div class="md:col-span-1">
          <a href="/" class="flex items-center space-x-2 mb-6 hover:opacity-80 transition-opacity group">
            <AxiomLogo size="md" class="group-hover:drop-shadow-lg transition-all" />
            <span class="text-lg font-black text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-purple-400">Axiom</span>
          </a>
          <p class="text-sm text-gray-500 leading-relaxed mb-6">Professional project management platform for modern teams.</p>
          <!-- Social Links Mobile -->
          <div class="flex space-x-4 md:hidden">
            <a href="#" class="text-gray-500 hover:text-cyan-400 transition-colors">
              <span class="sr-only">Twitter</span>
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M6.29 18.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0020 3.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 01.8 7.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 010 16.407a11.616 11.616 0 006.29 1.84"></path>
              </svg>
            </a>
            <a href="#" class="text-gray-500 hover:text-cyan-400 transition-colors">
              <span class="sr-only">GitHub</span>
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 0C4.477 0 0 4.484 0 10.017c0 4.425 2.865 8.18 6.839 9.49.5.092.682-.217.682-.482 0-.237-.008-.868-.013-1.703-2.782.603-3.369-1.343-3.369-1.343-.454-1.156-1.11-1.463-1.11-1.463-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.543 2.914 1.186.092-.923.35-1.543.636-1.898-2.22-.253-4.555-1.11-4.555-4.943 0-1.091.39-1.984 1.029-2.683-.103-.253-.447-1.27.098-2.647 0 0 .84-.269 2.75 1.025A9.578 9.578 0 0110 4.817c.85.004 1.705.114 2.504.336 1.909-1.294 2.747-1.025 2.747-1.025.546 1.377.203 2.394.1 2.647.64.699 1.028 1.592 1.028 2.683 0 3.842-2.339 4.687-4.566 4.935.359.309.678.919.678 1.852 0 1.336-.012 2.415-.012 2.743 0 .267.18.578.688.48C17.137 18.195 20 14.44 20 10.017 20 4.484 15.522 0 10 0z" clip-rule="evenodd"></path>
              </svg>
            </a>
          </div>
        </div>

        <!-- Product -->
        <div>
          <h3 class="text-white font-bold mb-6 text-sm tracking-wide uppercase">Product</h3>
          <ul class="space-y-3 text-sm">
            <li><a href="/#features" class="text-gray-500 hover:text-cyan-400 transition-colors duration-200">Features</a></li>
            <li><a href="/#pricing" class="text-gray-500 hover:text-cyan-400 transition-colors duration-200">Pricing</a></li>
            <li><a href="/security" class="text-gray-500 hover:text-cyan-400 transition-colors duration-200">Security</a></li>
            <li><a href="/roadmap" class="text-gray-500 hover:text-cyan-400 transition-colors duration-200">Roadmap</a></li>
          </ul>
        </div>

        <!-- Company -->
        <div>
          <h3 class="text-white font-bold mb-6 text-sm tracking-wide uppercase">Company</h3>
          <ul class="space-y-3 text-sm">
            <li><a href="/about" class="text-gray-500 hover:text-cyan-400 transition-colors duration-200">About</a></li>
            <li><a href="/blog" class="text-gray-500 hover:text-cyan-400 transition-colors duration-200">Blog</a></li>
            <li><a href="/careers" class="text-gray-500 hover:text-cyan-400 transition-colors duration-200">Careers</a></li>
            <li><a href="/contact" class="text-gray-500 hover:text-cyan-400 transition-colors duration-200">Contact</a></li>
          </ul>
        </div>

        <!-- Resources -->
        <div>
          <h3 class="text-white font-bold mb-6 text-sm tracking-wide uppercase">Resources</h3>
          <ul class="space-y-3 text-sm">
            <li><a href="/docs" class="text-gray-500 hover:text-cyan-400 transition-colors duration-200">Documentation</a></li>
            <li><a href="/api" class="text-gray-500 hover:text-cyan-400 transition-colors duration-200">API Reference</a></li>
            <li><a href="/support" class="text-gray-500 hover:text-cyan-400 transition-colors duration-200">Support</a></li>
            <li><a href="/status" class="text-gray-500 hover:text-cyan-400 transition-colors duration-200">Status</a></li>
          </ul>
        </div>

        <!-- Legal -->
        <div>
          <h3 class="text-white font-bold mb-6 text-sm tracking-wide uppercase">Legal</h3>
          <ul class="space-y-3 text-sm">
            <li><a href="/privacy" class="text-gray-500 hover:text-cyan-400 transition-colors duration-200">Privacy</a></li>
            <li><a href="/terms" class="text-gray-500 hover:text-cyan-400 transition-colors duration-200">Terms</a></li>
            <li><a href="/cookies" class="text-gray-500 hover:text-cyan-400 transition-colors duration-200">Cookies</a></li>
            <li><a href="/compliance" class="text-gray-500 hover:text-cyan-400 transition-colors duration-200">Compliance</a></li>
          </ul>
        </div>
      </div>

      <!-- Bottom Section -->
      <div class="border-t border-white/10 pt-8 flex flex-col md:flex-row justify-between items-center gap-6">
        <div class="text-center md:text-left">
          <p class="text-sm text-gray-500">&copy; 2026 Axiom. All rights reserved.</p>
          <p class="text-xs text-gray-600 mt-1">Built for teams that move fast.</p>
        </div>
        
        <!-- Social Links Desktop -->
        <div class="hidden md:flex space-x-6">
          <a href="#" class="text-gray-500 hover:text-cyan-400 transition-colors duration-200 group">
            <span class="sr-only">Twitter</span>
            <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20">
              <path d="M6.29 18.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0020 3.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 01.8 7.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 010 16.407a11.616 11.616 0 006.29 1.84"></path>
            </svg>
          </a>
          <a href="#" class="text-gray-500 hover:text-cyan-400 transition-colors duration-200 group">
            <span class="sr-only">GitHub</span>
            <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 0C4.477 0 0 4.484 0 10.017c0 4.425 2.865 8.18 6.839 9.49.5.092.682-.217.682-.482 0-.237-.008-.868-.013-1.703-2.782.603-3.369-1.343-3.369-1.343-.454-1.156-1.11-1.463-1.11-1.463-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.543 2.914 1.186.092-.923.35-1.543.636-1.898-2.22-.253-4.555-1.11-4.555-4.943 0-1.091.39-1.984 1.029-2.683-.103-.253-.447-1.27.098-2.647 0 0 .84-.269 2.75 1.025A9.578 9.578 0 0110 4.817c.85.004 1.705.114 2.504.336 1.909-1.294 2.747-1.025 2.747-1.025.546 1.377.203 2.394.1 2.647.64.699 1.028 1.592 1.028 2.683 0 3.842-2.339 4.687-4.566 4.935.359.309.678.919.678 1.852 0 1.336-.012 2.415-.012 2.743 0 .267.18.578.688.48C17.137 18.195 20 14.44 20 10.017 20 4.484 15.522 0 10 0z" clip-rule="evenodd"></path>
            </svg>
          </a>
        </div>
      </div>
    </div>
  </footer>
</template>
