<template>
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Team Workload & Capacity
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Capacity Summary -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
          <StatisticsCard 
            title="Team Members" 
            :value="capacitySummary.total_employees"
            icon="👥"
            color="blue"
          />
          <StatisticsCard 
            title="Healthy Capacity" 
            :value="capacitySummary.healthy_capacity"
            icon="✅"
            color="green"
          />
          <StatisticsCard 
            title="Moderate Load" 
            :value="capacitySummary.moderate_capacity"
            icon="⚖️"
            color="yellow"
          />
          <StatisticsCard 
            title="Critical Load" 
            :value="capacitySummary.critical_capacity"
            icon="🚨"
            color="red"
          />
        </div>

        <!-- Alerts for Bottlenecks -->
        <div v-if="bottlenecks.length > 0" class="bg-red-50 border border-red-200 rounded-lg p-6 mb-8">
          <h3 class="text-lg font-semibold text-red-900 mb-4">⚠️ Bottleneck Alert</h3>
          <div class="space-y-2">
            <p v-for="bottleneck in bottlenecks" :key="bottleneck.id" class="text-sm text-red-800">
              <strong>{{ bottleneck.name }}</strong> has {{ bottleneck.active_tasks }} active tasks and {{ bottleneck.overdue_tasks }} overdue - Status: <span class="font-semibold">{{ bottleneck.bottleneck_level }}</span>
            </p>
          </div>
        </div>

        <!-- Employee Workload Table -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Employee Workload Details</h3>
            
            <div class="grid grid-cols-1 gap-4">
              <EmployeeWorkloadRow 
                v-for="employee in employeeWorkloads"
                :key="employee.id"
                :employee="employee"
              />
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import StatisticsCard from './Components/StatisticsCard.vue';
import EmployeeWorkloadRow from './Components/EmployeeWorkloadRow.vue';

defineProps({
  employeeWorkloads: Array,
  bottlenecks: Array,
  capacitySummary: Object,
  filterOptions: Object,
});
</script>
