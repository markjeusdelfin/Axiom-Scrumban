<template>
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        All Tasks
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Filter Panel -->
        <FilterPanel 
          :filterOptions="filterOptions"
          :initialFilters="filters"
          @apply-filters="applyFilters"
        />

        <!-- Tasks Table -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
          <div class="p-6">
            <div class="flex justify-between items-center mb-6">
              <h3 class="text-lg font-semibold text-gray-900">
                Tasks ({{ tasks.length }})
              </h3>
              <div class="text-sm text-gray-600">
                <Link href="/tasks/create" class="text-blue-600 hover:text-blue-800 font-medium">
                  + Create Task
                </Link>
              </div>
            </div>

            <div class="overflow-x-auto">
              <table class="w-full">
                <thead class="bg-gray-50">
                  <tr class="border-b border-gray-200">
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">
                      Title
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">
                      Project
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">
                      Assignee
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">
                      Status
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">
                      Priority
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">
                      Progress
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">
                      Due Date
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr
                    v-for="task in tasks"
                    :key="task.id"
                    class="border-b border-gray-200 hover:bg-gray-50 transition"
                  >
                    <td class="px-6 py-4">
                      <Link :href="`/tasks/${task.id}`" class="text-blue-600 hover:text-blue-800 font-medium">
                        {{ task.title }}
                      </Link>
                      <p v-if="task.is_overdue" class="text-xs text-red-600 mt-1">⚠️ Overdue</p>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                      {{ task.project_name }}
                    </td>
                    <td class="px-6 py-4 text-sm">
                      {{ task.assignee_name }}
                    </td>
                    <td class="px-6 py-4">
                      <StatusBadge :status="task.status" />
                    </td>
                    <td class="px-6 py-4">
                      <PriorityBadge :priority="task.priority" />
                    </td>
                    <td class="px-6 py-4">
                      <div class="w-24">
                        <ProgressBar :percentage="task.progress_percent" />
                      </div>
                    </td>
                    <td class="px-6 py-4 text-sm">
                      <span :class="task.is_overdue ? 'text-red-600 font-semibold' : 'text-gray-600'">
                        {{ formatDate(task.due_date) }}
                      </span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <div v-if="tasks.length === 0" class="text-center py-12">
              <p class="text-gray-500">No tasks found</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { useState } from 'react';
import { Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import FilterPanel from './Components/FilterPanel.vue';
import StatusBadge from './Components/StatusBadge.vue';
import PriorityBadge from './Components/PriorityBadge.vue';
import ProgressBar from './Components/ProgressBar.vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  tasks: Array,
  filterOptions: Object,
  filters: Object,
});

const formatDate = (dateString) => {
  if (!dateString) return 'No date';
  const date = new Date(dateString);
  return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
};

const applyFilters = (filters) => {
  router.get('/dashboard/tasks', filters);
};
</script>
