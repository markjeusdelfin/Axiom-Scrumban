<template>
  <section id="features" class="py-20 md:py-32 bg-gradient-to-b from-slate-900 via-slate-800 to-slate-900 relative overflow-hidden">
    <!-- Animated Background Elements -->
    <div class="absolute inset-0 opacity-10">
      <div class="absolute inset-0 bg-grid-pattern"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Section Header -->
      <div class="text-center max-w-3xl mx-auto mb-20">
        <div class="inline-block mb-4">
          <div class="px-4 py-2 rounded-full bg-gradient-to-r from-cyan-600/20 to-purple-600/20 border border-purple-500/30 text-sm text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-purple-400 font-semibold">
            ✨ Powerful Features
          </div>
        </div>
        <h2 class="text-5xl md:text-6xl font-black text-white mb-6 leading-tight">
          Everything You Need to
          <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-purple-400">
            Dominate
          </span>
        </h2>
        <p class="text-lg text-gray-400 leading-relaxed">
          Built with cutting-edge technology for teams that refuse to compromise.
        </p>
      </div>

      <!-- Features Grid -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-20">
        <feature-card
          v-for="feature in features"
          :key="feature.id"
          :title="feature.title"
          :description="feature.description"
          :icon="feature.icon"
          :color="feature.color"
        />
      </div>

      <!-- Two Column Features -->
      <div class="space-y-20">
        <feature-highlight
          v-for="highlight in highlights"
          :key="highlight.id"
          :title="highlight.title"
          :description="highlight.description"
          :points="highlight.points"
          :image-position="highlight.imagePosition"
        />
      </div>
    </div>
  </section>
</template>

<script setup>
import FeatureCard from './FeatureCard.vue';
import FeatureHighlight from './FeatureHighlight.vue';

const features = [
  {
    id: 1,
    title: 'Kanban Board',
    description: 'Visualize workflows with lightning-fast drag-and-drop task management.',
    icon: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
    color: 'from-cyan-500 to-cyan-600',
  },
  {
    id: 2,
    title: 'Real-time Sync',
    description: 'Live updates and instant notifications for seamless team collaboration.',
    icon: 'M12 4.354a4 4 0 110 5.292M15 21H3a2 2 0 01-2-2V5a2 2 0 012-2h18a2 2 0 012 2v14a2 2 0 01-2 2z',
    color: 'from-purple-500 to-purple-600',
  },
  {
    id: 3,
    title: 'Time Tracking',
    description: 'Advanced time tracking for accurate resource planning and billing.',
    icon: 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
    color: 'from-pink-500 to-pink-600',
  },
  {
    id: 4,
    title: 'Smart Dependencies',
    description: 'Manage complex workflows with intelligent dependency tracking.',
    icon: 'M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01',
    color: 'from-orange-500 to-orange-600',
  },
  {
    id: 5,
    title: 'Activity Logs',
    description: 'Complete audit trails for transparency and accountability.',
    icon: 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
    color: 'from-green-500 to-green-600',
  },
  {
    id: 6,
    title: 'AI Notifications',
    description: 'Intelligent alerts that learn your priorities and predict issues.',
    icon: 'M15 17h5l-1.405-1.405A2.032 2.032 0 0120 15.171V11a6 6 0 10-12 0v4.171A2.032 2.032 0 004.405 17H10m6 0v1a3 3 0 11-6 0v-1m6 0H9',
    color: 'from-red-500 to-red-600',
  },
];

const highlights = [
  {
    id: 1,
    title: 'Enterprise-Grade Collaboration',
    description: 'Built from the ground up for teams that move at scale.',
    points: [
      'Multi-team workspace management',
      'Role-based access control (RBAC)',
      'Advanced permission system',
      'Audit logging and compliance',
    ],
    imagePosition: 'left',
  },
  {
    id: 2,
    title: 'Advanced Analytics & Insights',
    description: 'Data-driven decisions with powerful analytics and dashboards.',
    points: [
      'Real-time performance metrics',
      'Predictive analytics engine',
      'Custom report builder',
      'Export and BI integration',
    ],
    imagePosition: 'right',
  },
];
</script>

<style scoped>
.bg-grid-pattern {
  background-image:
    linear-gradient(rgba(255, 255, 255, 0.03) 1px, transparent 1px),
    linear-gradient(90deg, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
  background-size: 50px 50px;
}
</style>
