<template>
  <div class="mesh-gradient min-h-screen text-slate-800 antialiased font-sans">
    <transition name="fade">
      <div v-if="errorMessage" class="error-toast font-bold">
        <span>{{ errorMessage }}</span>
        <button @click="errorMessage = ''" class="ml-4 underline text-xs">Dismiss</button>
      </div>
    </transition>

    <transition name="fade">
      <div id="app-loader" v-if="loading">
        <div class="flex flex-col items-center gap-4">
          <div class="w-12 h-12 border-4 border-indigo-600 border-t-transparent rounded-full animate-spin"></div>
          <p class="text-sm font-bold text-slate-500">Loading Task Monitoring Dashboard...</p>
        </div>
      </div>
    </transition>

    <div class="max-w-[1800px] mx-auto p-4 md:p-10">
      <header class="mb-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
          <div>
            <h1 class="text-4xl font-extrabold text-slate-900 tracking-tight">Task Monitoring Dashboard</h1>
            <p class="text-slate-500 font-medium mt-2">Real-time overview of team tasks and workload distribution</p>
          </div>
          <button 
            @click="syncWithPostgres" 
            :disabled="loading"
            class="bg-blue-600 text-white px-6 py-3 rounded-xl text-sm font-bold hover:bg-blue-700 active:scale-95 transition-all flex items-center gap-2 disabled:opacity-50"
          >
            <svg class="w-4 h-4" :class="{'animate-spin': loading}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            <span>{{ loading ? 'Syncing...' : 'Refresh Data' }}</span>
          </button>
        </div>
      </header>

      <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="metric-card">
          <p class="metric-label">Total Tasks</p>
          <p class="metric-value">{{ totalTasks }}</p>
        </div>
        <div class="metric-card">
          <p class="metric-label">Team Utilization</p>
          <p class="metric-value">{{ stats.utilization || 0 }}<span class="text-lg">%</span></p>
        </div>
        <div class="metric-card">
          <p class="metric-label">Overdue Tasks</p>
          <p class="metric-value text-red-600">{{ stats.lateTasks || 0 }}</p>
        </div>
        <div class="metric-card">
          <p class="metric-label">In Progress (Est.)</p>
          <p class="metric-value text-amber-600">{{ inProgressEstimate }}</p>
        </div>
      </div>

      <transition name="slide">
        <div v-if="showBottleneckAlert && filterState.showBottlenecks" class="bottleneck-alert">
          <div class="flex items-start gap-3">
            <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
            </svg>
            <div>
              <h3 class="font-bold text-red-800">⚠ Team Member Overloaded</h3>
              <p class="text-sm text-red-700 mt-1">{{ bottleneckMessage }}</p>
            </div>
          </div>
        </div>
      </transition>

      <div class="mb-8 bg-white border border-slate-200 rounded-lg p-6">
        <h3 class="font-bold text-slate-900 mb-4">Filters & Sorting</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Filter by Status</label>
            <div class="flex gap-2 flex-wrap">
              <button 
                v-for="status in ['all', 'todo', 'progress', 'done']" 
                :key="status"
                @click="filterState.status = status"
                :class="['filter-btn', { active: filterState.status === status }]"
              >
                {{ status.toUpperCase() }}
              </button>
            </div>
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Sort by Workload</label>
            <div class="flex gap-2 flex-wrap">
              <button @click="filterState.sort = 'asc'" :class="['filter-btn', { active: filterState.sort === 'asc' }]">Low to High</button>
              <button @click="filterState.sort = 'desc'" :class="['filter-btn', { active: filterState.sort === 'desc' }]">High to Low</button>
            </div>
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Analysis</label>
            <button 
              @click="filterState.showBottlenecks = !filterState.showBottlenecks" 
              class="filter-btn w-full md:w-auto" 
              :class="{ active: filterState.showBottlenecks }"
            >
              Highlight Overloaded Members
            </button>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-12 gap-8 mb-8">
        <div class="col-span-12 lg:col-span-8 space-y-6">
          <div class="bento-card p-8 rounded-2xl">
            <h3 class="text-lg font-bold text-slate-900 mb-4">Sprint Momentum (Last 6 Sprints)</h3>
            <div ref="momentumChartRef" class="h-[300px]"></div>
          </div>

          <div class="bento-card p-8 rounded-2xl">
            <h3 class="text-lg font-bold text-slate-900 mb-4">Task Priority Distribution</h3>
            <div ref="priorityChartRef" class="h-[250px]"></div>
          </div>
        </div>

        <div class="col-span-12 lg:col-span-4 bento-card p-8 rounded-2xl">
          <h3 class="text-lg font-bold text-slate-900 mb-6">Team Workload Overview</h3>
          <div class="space-y-6">
            <div v-if="team.length === 0" class="text-slate-400 text-sm italic">No team data found.</div>
            <div v-for="member in sortedTeam" :key="member.id" class="pb-6 border-b border-slate-200 last:border-0 last:pb-0">
              <div class="flex justify-between items-center mb-2">
                <div class="flex items-center gap-3 flex-grow">
                  <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-sm">
                    {{ member.name.charAt(0) }}
                  </div>
                  <div>
                    <p class="font-semibold text-slate-900">{{ member.name }}</p>
                    <p class="text-xs text-slate-500">{{ member.task_count || 0 }} tasks</p>
                  </div>
                </div>
                <div class="flex flex-col items-end gap-1">
                  <span :class="['text-lg font-bold', getLoadColorClass(member.load)]">
                    {{ Math.round(member.load) }}%
                  </span>
                  <span v-if="member.load >= 90" class="text-xs font-bold text-red-600 bg-red-100 px-2 py-1 rounded">🚨 Overloaded</span>
                  <span v-else-if="member.load >= 75" class="text-xs font-bold text-amber-600 bg-amber-100 px-2 py-1 rounded">⚠️ High Load</span>
                </div>
              </div>
              <div class="workload-indicator">
                <div :class="['workload-bar', getWorkloadBarClass(member.load)]" :style="{ width: member.load + '%' }"></div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="bento-card p-8 rounded-2xl">
        <h3 class="text-lg font-bold text-slate-900 mb-4">Tasks at Risk (Overdue)</h3>
        <div class="divide-y divide-slate-200">
          <div v-if="overdueTasks.length === 0" class="text-slate-400 text-sm italic p-4">No overdue tasks found.</div>
          <div v-for="t in overdueTasks" :key="t.id" class="p-4 hover:bg-slate-50 transition-colors">
            <div class="font-semibold text-slate-900">{{ t.title }}</div>
            <div class="text-sm text-slate-500">Assigned to: {{ t.assignee_name || 'Unassigned' }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import ApexCharts from 'apexcharts';

// State
const loading = ref(true);
const errorMessage = ref('');
const team = ref([]);
const stats = ref({});
const overdueTasks = ref([]);
const filterState = ref({ status: 'all', sort: 'desc', showBottlenecks: false });

// Chart Refs
const momentumChartRef = ref(null);
const priorityChartRef = ref(null);
let momentumChart = null;
let priorityChart = null;

// Computed Properties
const totalTasks = computed(() => {
  return team.value.reduce((sum, m) => sum + (parseInt(m.task_count) || 0), 0);
});

const inProgressEstimate = computed(() => {
  if (team.value.length === 0) return 0;
  const avgLoad = team.value.reduce((s, m) => s + (m.load || 0), 0) / team.value.length;
  // Based on your original dashboard.js logic:
  return Math.round(totalTasks.value * (avgLoad / 100) * 0.6) || 0;
});

const sortedTeam = computed(() => {
  return [...team.value].sort((a, b) => {
    return filterState.value.sort === 'desc' ? b.load - a.load : a.load - b.load;
  });
});

const bottleneckMessage = computed(() => {
  const overloaded = team.value.filter(m => m.load >= 90);
  if (overloaded.length === 0) return '';
  const names = overloaded.map(m => m.name).join(', ');
  return `${names} ${overloaded.length === 1 ? 'is' : 'are'} overwhelmed (${overloaded.map(m => m.task_count || 0).join(', ')} tasks each).`;
});

const showBottleneckAlert = computed(() => team.value.some(m => m.load >= 90));

// Logic Methods
const getLoadColorClass = (load) => {
  if (load >= 90) return 'text-red-600';
  if (load >= 75) return 'text-amber-600';
  return 'text-green-600';
};

const getWorkloadBarClass = (load) => {
  if (load >= 90) return 'critical';
  if (load >= 75) return 'warning';
  return '';
};

// Data Fetching
async function syncWithPostgres() {
  loading.value = true;
  errorMessage.value = '';
  
  try {
    const response = await fetch('/scrumban/dashboard-summary', {
      headers: { 
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest' 
      },
      credentials: 'same-origin'
    });

    if (!response.ok) throw new Error(`HTTP ${response.status}`);
    
    const data = await response.json();
    
    // Validation logic from dashboard.js
    if (!data.success) throw new Error(data.error || 'Server reported failure');

    team.value = Array.isArray(data.team) ? data.team : [];
    stats.value = data.stats || {};
    overdueTasks.value = data.overdue || [];

    updateCharts();
  } catch (err) {
    console.error('[Dashboard] Sync error:', err);
    errorMessage.value = 'Failed to load dashboard data: ' + err.message;
  } finally {
    loading.value = false;
  }
}

// Chart Management
function initCharts() {
  if (momentumChartRef.value) {
    momentumChart = new ApexCharts(momentumChartRef.value, {
      chart: { type: 'area', height: 300, toolbar: { show: false }, fontFamily: 'inherit' },
      series: [{ name: 'Tasks', data: [0, 0, 0, 0, 0, 0] }],
      colors: ['#3b82f6'],
      stroke: { curve: 'smooth', width: 2 },
      fill: { type: 'gradient', gradient: { opacityFrom: 0.5, opacityTo: 0.1 } },
      xaxis: { categories: ['S1', 'S2', 'S3', 'S4', 'S5', 'S6'] },
      yaxis: { title: { text: 'Number of Tasks' } }
    });
    momentumChart.render();
  }

  if (priorityChartRef.value) {
    priorityChart = new ApexCharts(priorityChartRef.value, {
      chart: { type: 'donut', height: 250, toolbar: { show: false }, fontFamily: 'inherit' },
      series: [0, 0, 0],
      labels: ['High Priority', 'Medium Priority', 'Low Priority'],
      colors: ['#ef4444', '#f59e0b', '#3b82f6']
    });
    priorityChart.render();
  }
}

function updateCharts() {
  const momentum = Array.isArray(stats.value.momentum) ? stats.value.momentum : [0, 0, 0, 0, 0, 0];
  const priorityData = Array.isArray(stats.value.priorityData) ? stats.value.priorityData : [0, 0, 0];

  if (momentumChart) momentumChart.updateSeries([{ data: momentum.slice(-6) }]);
  if (priorityChart) priorityChart.updateSeries(priorityData);
}

onMounted(() => {
  initCharts();
  syncWithPostgres();
});
</script>

<style>
/* Global-style transitions for the dashboard */
.fade-enter-active, .fade-leave-active { transition: opacity 0.5s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }

.slide-enter-active, .slide-leave-active { transition: all 0.3s ease-out; }
.slide-enter-from, .slide-leave-to { transform: translateY(-10px); opacity: 0; }

/* Critical Overrides for CSS from dashboard.css */
.error-toast { display: flex !important; align-items: center; justify-content: space-between; }
</style>