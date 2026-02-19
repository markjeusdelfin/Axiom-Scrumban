<template>
  <section id="pricing" class="py-20 md:py-32 bg-gradient-to-b from-slate-950 to-slate-900 relative overflow-hidden">
    <!-- Animated Orbs -->
    <div class="absolute top-0 right-1/4 w-96 h-96 bg-purple-600 rounded-full mix-blend-multiply filter blur-3xl opacity-15 animate-blob"></div>
    <div class="absolute bottom-0 left-1/4 w-96 h-96 bg-cyan-600 rounded-full mix-blend-multiply filter blur-3xl opacity-15 animate-blob animation-delay-2000"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Section Header -->
      <div class="text-center max-w-3xl mx-auto mb-20">
        <div class="inline-block mb-6">
          <div class="px-4 py-2 rounded-full bg-gradient-to-r from-cyan-600/20 to-purple-600/20 border border-cyan-500/30 text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-purple-400 font-bold text-sm tracking-wide">
            💎 TRANSPARENT PRICING
          </div>
        </div>
        <h2 class="text-5xl md:text-7xl font-black text-white mb-6 leading-tight tracking-tight">
          Simple, Flexible
          <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-purple-400 block">
            Pricing Plans
          </span>
        </h2>
        <p class="text-xl text-gray-400 max-w-2xl mx-auto">
          Start free and scale as you grow. Choose the perfect plan for your team.
        </p>
      </div>

      <!-- Pricing Cards -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8 lg:gap-6 mb-16">
        <pricing-card
          v-for="plan in plans"
          :key="plan.id"
          :plan="plan"
          :is-popular="plan.id === 2"
        />
      </div>

      <!-- FAQ Note -->
      <div class="text-center space-y-4 mt-16">
        <div class="inline-flex items-center space-x-3 px-6 py-3 rounded-full bg-gradient-to-r from-cyan-600/10 to-purple-600/10 border border-cyan-500/30 backdrop-blur-sm">
          <svg class="w-5 h-5 text-cyan-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M5 2a1 1 0 011 1v1h1a1 1 0 010 2H6v1a1 1 0 01-2 0V6H3a1 1 0 010-2h1V3a1 1 0 011-1zm0 10a1 1 0 011 1v1h1a1 1 0 110 2H6v1a1 1 0 11-2 0v-1H3a1 1 0 110-2h1v-1a1 1 0 011-1zm7-10a1 1 0 01.967.25l.025.041a1 1 0 01-.992 1.457A1 1 0 0012 2zm2.343 2.343a1 1 0 010 1.414l-.02.02a1 1 0 11-1.414-1.414l.02-.02a1 1 0 011.414 0zm0 7.07a1 1 0 010 1.415l-.02.02a1 1 0 11-1.414-1.414l.02-.02a1 1 0 011.414 0z" clip-rule="evenodd"></path>
          </svg>
          <span class="text-sm text-gray-300 font-medium">
            ✓ 14-day free trial • No credit card required • Cancel anytime
          </span>
        </div>
        <p class="text-gray-500 text-sm mt-4">All plans include access to our full feature set during trial period</p>
      </div>
    </div>
  </section>
</template>

<script setup>
import PricingCard from './PricingCard.vue';

const plans = [
  {
    id: 1,
    name: 'Starter',
    price: 'Free',
    description: 'Perfect for solo projects',
    features: [
      'Up to 5 projects',
      'Up to 10 team members',
      'Basic task management',
      'Community support',
    ],
    cta: {
      text: 'Get Started',
      link: '/register',
    },
  },
  {
    id: 2,
    name: 'Professional',
    price: '$29',
    period: 'per month',
    description: 'For growing teams',
    features: [
      'Unlimited projects',
      'Up to 50 team members',
      'Advanced analytics',
      'Priority support',
      'Custom workflows',
      'Time tracking',
      'API access',
    ],
    cta: {
      text: 'Start Free Trial',
      link: '/register',
    },
  },
  {
    id: 3,
    name: 'Enterprise',
    price: 'Custom',
    description: 'For large organizations',
    features: [
      'Unlimited everything',
      'Dedicated support',
      'Advanced security',
      'SSO & SAML',
      'Custom integrations',
      'On-premise option',
      'SLA guarantee',
    ],
    cta: {
      text: 'Contact Sales',
      link: '/contact',
    },
  },
];
</script>

