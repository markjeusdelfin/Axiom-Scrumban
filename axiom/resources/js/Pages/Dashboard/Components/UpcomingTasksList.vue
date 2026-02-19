<template>
  <div class="space-y-3">
    <div v-if="tasks.length === 0" class="text-center py-8 text-gray-500">
      <p>No upcoming tasks</p>
    </div>
    <div v-else>
      <div
        v-for="task in tasks.slice(0, 5)"
        :key="task.id"
        class="p-4 border-l-4 border-blue-400 bg-blue-50 rounded hover:shadow-md transition"
      >
        <div class="flex justify-between items-start gap-4">
          <div class="flex-1">
            <h4 class="font-semibold text-gray-900">{{ task.title }}</h4>
            <p class="text-sm text-gray-600 mt-1">
              {{ task.project_name }} • {{ task.assignee_name }}
            </p>
            <p class="text-xs text-gray-500 mt-2">
              Due: <span class="font-medium">{{ formatDate(task.due_date) }}</span>
            </p>
          </div>
          <div class="text-right">
            <PriorityBadge :priority="task.priority" />
            <div class="w-16 mt-2">
              <ProgressBar :percentage="task.progress_percent" />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import PriorityBadge from './PriorityBadge.vue';
import ProgressBar from './ProgressBar.vue';

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
</script>
