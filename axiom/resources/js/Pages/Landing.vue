<template>
  <div class="min-h-screen bg-slate-950">
    <!-- Navigation Bar -->
    <landing-navbar :can-login="canLogin" :can-register="canRegister" />

    <!-- Hero Section -->
    <hero :can-register="canRegister" />

    <!-- Features Section -->
    <features />

    <!-- Pricing Section -->
    <pricing-plans />

    <!-- Call to Action Section -->
    <call-to-action />

    <!-- Footer -->
    <footer />
  </div>
</template>

<script setup>
import { defineProps } from 'vue';
import LandingNavbar from '@/Components/LandingNavbar.vue';
import Hero from '@/Components/Hero.vue';
import Features from '@/Components/Features.vue';
import PricingPlans from '@/Components/PricingPlans.vue';
import CallToAction from '@/Components/CallToAction.vue';
import Footer from '@/Components/Footer.vue';

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

<style scoped>
html {
  scroll-behavior: smooth;
}
</style>
