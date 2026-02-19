<template>
  <div class="space-y-3">
    <div v-if="tasks.length === 0" class="text-center py-8 text-gray-500">
      <p>No critical tasks at the moment</p>
    </div>
    <div v-else>
      <div
        v-for="task in tasks.slice(0, 5)"
        :key="task.id"
        class="p-4 border-l-4 border-red-500 bg-red-50 rounded hover:shadow-md transition"
      >
        <div class="flex justify-between items-start gap-4">
          <div class="flex-1">
            <h4 class="font-semibold text-black">{{ task.title }}</h4>
            <div class="mt-2">
              <span :class="['inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold', statusClass(task.status)]">
                {{ task.status }}
              </span>
            </div>
            <p class="text-sm text-black mt-2">
              {{ task.project_name }} • Assigned to {{ task.assignee_name }}
            </p>
            <p class="text-xs text-black mt-2">
              Due: <span class="font-medium">{{ formatDate(task.due_date) }}</span>
            </p>
          </div>
          <div class="text-right">
            <PriorityBadge :priority="task.priority" />
            <div class="text-sm font-medium mt-2 text-black">{{ task.progress_percent }}%</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import PriorityBadge from './PriorityBadge.vue';

const props = defineProps({
  tasks: Array
});

const formatDate = (dateString) => {
  if (!dateString) return 'No date';
  const date = new Date(dateString);
  const today = new Date();
  const tomorrow = new Date(today);
  tomorrow.setDate(tomorrow.getDate() + 1);

  if (date.toDateString() === today.toDateString()) return 'Today';
  if (date.toDateString() === tomorrow.toDateString()) return 'Tomorrow';

  return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
};

const statusClass = (status) => {
  if (!status) return 'bg-gray-200 text-gray-800';
  const s = String(status).toLowerCase();
  if (s.includes('done') || s.includes('complete')) return 'bg-green-600 text-white';
  if (s.includes('progress') || s.includes('in progress')) return 'bg-yellow-400 text-black';
  if (s.includes('blocked')) return 'bg-red-600 text-white';
  if (s.includes('todo') || s.includes('to do') || s.includes('open')) return 'bg-blue-600 text-white';
  return 'bg-gray-200 text-gray-800';
};
</script>
