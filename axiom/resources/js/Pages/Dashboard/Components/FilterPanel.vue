<template>
  <div class="bg-white shadow-sm rounded-lg p-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Filter Tasks</h3>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
      <!-- Status Filter -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
        <select
          v-model="localFilters.status"
          multiple
          class="w-full p-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
        >
          <option v-for="status in filterOptions.statuses" :key="status" :value="status">
            {{ status.replace('_', ' ') }}
          </option>
        </select>
      </div>

      <!-- Priority Filter -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
        <select
          v-model="localFilters.priority"
          multiple
          class="w-full p-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
        >
          <option v-for="priority in filterOptions.priorities" :key="priority" :value="priority">
            {{ priority }}
          </option>
        </select>
      </div>

      <!-- Assignee Filter -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Assignee</label>
        <select
          v-model.number="localFilters.assigned_to"
          class="w-full p-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
        >
          <option value="">All Employees</option>
          <option v-for="employee in filterOptions.employees" :key="employee.id" :value="employee.id">
            {{ employee.name }}
          </option>
        </select>
      </div>

      <!-- Overdue Filter -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Filter</label>
        <label class="flex items-center p-2 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
          <input
            v-model="localFilters.overdue_only"
            type="checkbox"
            class="rounded"
          />
          <span class="ml-2 text-sm text-gray-700">Overdue Only</span>
        </label>
      </div>
    </div>

    <!-- Action Buttons -->
    <div class="mt-4 flex gap-2">
      <button
        @click="handleApplyFilters"
        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
      >
        Apply Filters
      </button>
      <button
        @click="handleResetFilters"
        class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition"
      >
        Reset
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const props = defineProps({
  filterOptions: Object,
  initialFilters: Object,
});

const emit = defineEmits(['apply-filters']);

const localFilters = ref({
  status: props.initialFilters?.status || [],
  priority: props.initialFilters?.priority || [],
  assigned_to: props.initialFilters?.assigned_to || '',
  overdue_only: props.initialFilters?.overdue_only || false,
});

const handleApplyFilters = () => {
  emit('apply-filters', localFilters.value);
};

const handleResetFilters = () => {
  localFilters.value = {
    status: [],
    priority: [],
    assigned_to: '',
    overdue_only: false,
  };
  emit('apply-filters', localFilters.value);
};
</script>
