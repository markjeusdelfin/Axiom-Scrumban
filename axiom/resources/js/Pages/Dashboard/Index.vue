<template>
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Task Monitoring Dashboard
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
          <StatisticsCard 
            title="Total Tasks" 
            :value="statistics.total_tasks"
            subtitle="All tasks in system"
            icon="📋"
            color="blue"
          />
          <StatisticsCard 
            title="In Progress" 
            :value="statistics.in_progress_tasks"
            subtitle="Currently being worked on"
            icon="⚙️"
            color="yellow"
          />
          <StatisticsCard 
            title="Overdue" 
            :value="statistics.overdue_tasks"
            subtitle="Need immediate attention"
            icon="⚠️"
            color="red"
          />
          <StatisticsCard 
            title="Completion Rate" 
            :value="`${statistics.completion_rate}%`"
            subtitle="Overall progress"
            icon="✅"
            color="green"
          />
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
              <h3 class="text-lg font-semibold text-gray-900 mb-4">Tasks by Status</h3>
              <StatusChart :data="statusBreakdown" />
            </div>
          </div>

          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
              <h3 class="text-lg font-semibold text-gray-900 mb-4">Priority Distribution</h3>
              <PriorityChart :data="priorityBreakdown" />
            </div>
          </div>
        </div>

        <!-- Teams Workload -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
          <div class="p-6">
            <div class="flex justify-between items-center mb-6">
              <h3 class="text-lg font-semibold text-gray-900">Team Workload Status</h3>
              <Link href="/dashboard/workload" class="text-blue-600 hover:text-blue-800 text-sm">
                View Details →
              </Link>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
              <div class="text-center p-4 bg-blue-50 rounded-lg">
                <div class="text-2xl font-bold text-blue-600">
                  {{ workloadSummary.total_employees }}
                </div>
                <p class="text-sm text-gray-600">Total Team Members</p>
              </div>
              <div class="text-center p-4 bg-green-50 rounded-lg">
                <div class="text-2xl font-bold text-green-600">
                  {{ workloadSummary.healthy_capacity }}
                </div>
                <p class="text-sm text-gray-600">Healthy Capacity</p>
              </div>
              <div class="text-center p-4 bg-yellow-50 rounded-lg">
                <div class="text-2xl font-bold text-yellow-600">
                  {{ workloadSummary.moderate_capacity }}
                </div>
                <p class="text-sm text-gray-600">Moderate Load</p>
              </div>
              <div class="text-center p-4 bg-red-50 rounded-lg">
                <div class="text-2xl font-bold text-red-600">
                  {{ workloadSummary.critical_capacity }}
                </div>
                <p class="text-sm text-gray-600">Critical Load</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Critical Tasks and Upcoming -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
          <!-- Critical Tasks -->
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
              <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">🚨 Critical Tasks</h3>
                <Link href="/dashboard/tasks?priority=high" class="text-blue-600 hover:text-blue-800 text-sm">
                  View All
                </Link>
              </div>
              <CriticalTasksList :tasks="criticalTasks" />
            </div>
          </div>

          <!-- Upcoming Tasks -->
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
              <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">📅 Upcoming Tasks (Next 7 Days)</h3>
                <Link href="/dashboard/tasks" class="text-blue-600 hover:text-blue-800 text-sm">
                  View All
                </Link>
              </div>
              <UpcomingTasksList :tasks="upcomingTasks" />
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import StatisticsCard from '@/Pages/Dashboard/Components/StatisticsCard.vue';
import StatusChart from '@/Pages/Dashboard/Components/StatusChart.vue';
import PriorityChart from '@/Pages/Dashboard/Components/PriorityChart.vue';
import CriticalTasksList from '@/Pages/Dashboard/Components/CriticalTasksList.vue';
import UpcomingTasksList from '@/Pages/Dashboard/Components/UpcomingTasksList.vue';

const props = defineProps({
  statistics: Object,
  statusBreakdown: Object,
  priorityBreakdown: Object,
  workloadSummary: Object,
  criticalTasks: Array,
  upcomingTasks: Array,
  filterOptions: Object,
});
</script>
