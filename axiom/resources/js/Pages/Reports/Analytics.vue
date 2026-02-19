<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { onMounted, ref, reactive, watch, computed } from 'vue';
import Chart from 'chart.js/auto';
import ApexCharts from 'apexcharts';

const props = defineProps({
    totalTasks: Number,
    completedTasks: Number,
    overdueTasks: Number,
    byStatus: Object,
    byPriority: Object,
    completionRate: Number,
    recentActivity: Array,
    teamMembers: { type: Array, default: () => [] },
    teamStats: { type: Object, default: () => ({ utilization: 0, lateTasks: 0 }) },
    projectsCompletion: { type: Array, default: () => [] }
});

const loading = ref(false);
const errorMessage = ref('');
const chartsReady = ref(false);
const chartInstances = {};

const filters = reactive({
    statusFilter: 'all',
    sortBy: 'updated',
    showBottlenecks: false
});

const filteredActivity = ref([]);

// Computed Properties
const bottleneckMembers = computed(() => {
    return (props.teamMembers || []).filter(member => (member.workload || 0) >= 85);
});

const showBottleneckAlert = computed(() => {
    return bottleneckMembers.value.length > 0 && filters.showBottlenecks;
});

const bottleneckMessage = computed(() => {
    if (bottleneckMembers.value.length === 0) return '';
    const names = bottleneckMembers.value.map(m => m.name).join(', ');
    return `${names} ${bottleneckMembers.value.length === 1 ? 'is' : 'are'} overwhelmed with ${bottleneckMembers.value.map(m => m.task_count || 0).join(', ')} tasks each.`;
});

const sortedTeamMembers = computed(() => {
    const sorted = [...(props.teamMembers || [])];
    if (filters.sortBy === 'workload_desc') {
        return sorted.sort((a, b) => (b.workload || 0) - (a.workload || 0));
    } else if (filters.sortBy === 'workload_asc') {
        return sorted.sort((a, b) => (a.workload || 0) - (b.workload || 0));
    }
    return sorted;
});

const getLoadColorClass = (load) => {
    if (load >= 90) return 'text-red-600';
    if (load >= 75) return 'text-amber-600';
    return 'text-green-600';
};

const getWorkloadBarClass = (load) => {
    if (load >= 90) return 'bg-red-500';
    if (load >= 75) return 'bg-amber-500';
    return 'bg-green-500';
};

watch([filters], () => {
    updateActivityList();
}, { deep: true });

onMounted(() => {
    initializeCharts();
    updateActivityList();
});

const updateActivityList = () => {
    let filtered = [...(props.recentActivity || [])];

    // Apply status filter
    if (filters.statusFilter !== 'all') {
        filtered = filtered.filter(task => task.status === filters.statusFilter);
    }

    // Apply sorting
    if (filters.sortBy === 'updated') {
        filtered.sort((a, b) => new Date(b.updated_at) - new Date(a.updated_at));
    } else if (filters.sortBy === 'status') {
        filtered.sort((a, b) => a.status.localeCompare(b.status));
    } else if (filters.sortBy === 'project') {
        filtered.sort((a, b) => (a.project?.name || '').localeCompare(b.project?.name || ''));
    }

    filteredActivity.value = filtered.slice(0, 10);
};

const initializeCharts = () => {
    // Destroy existing charts
    Object.values(chartInstances).forEach(chart => {
        if (chart) chart.destroy();
    });

    setTimeout(() => {
        // Status Chart
        const statusCanvas = document.getElementById('statusChart');
        if (statusCanvas && props.byStatus && Object.keys(props.byStatus).length > 0) {
            const statusCtx = statusCanvas.getContext('2d');
            chartInstances.status = new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: Object.keys(props.byStatus).map(s => s.replace('_', ' ')),
                    datasets: [{
                        data: Object.values(props.byStatus),
                        backgroundColor: ['#64748b', '#06b6d4', '#f97316', '#22c55e'],
                        borderColor: '#ffffff',
                        borderWidth: 3,
                        hoverBorderWidth: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: { 
                        legend: { display: false }
                    }
                }
            });
        }

        // Priority Chart - rendered with ApexCharts (horizontal bar)
        const priorityEl = document.getElementById('priorityChart');
        if (priorityEl && props.byPriority && Object.keys(props.byPriority).length > 0) {
            const keys = Object.keys(props.byPriority);
            const labels = keys.map(p => p.charAt(0).toUpperCase() + p.slice(1) + ' Priority');
            const values = Object.values(props.byPriority);
            const colors = keys.map(k => {
                const key = String(k).toLowerCase();
                if (key.includes('urgent')) return '#ef4444';
                if (key.includes('high')) return '#ec4899';
                if (key.includes('medium') || key.includes('med')) return '#8b5cf6';
                if (key.includes('low')) return '#6366f1';
                return '#94a3b8';
            });

            const options = {
                chart: { type: 'bar', height: '260', toolbar: { show: false } },
                plotOptions: { bar: { horizontal: true, barHeight: '48', borderRadius: 8, distributed: true } },
                dataLabels: { enabled: false },
                series: [{ name: 'Number of Tasks', data: values }],
                xaxis: { categories: labels, labels: { style: { colors: '#94a3b8' } } },
                yaxis: { labels: { style: { colors: '#94a3b8' } } },
                colors: colors,
                legend: { show: false },
                grid: { borderColor: 'rgba(255,255,255,0.03)' },
                tooltip: { y: { formatter: (val) => `${val} tasks` } }
            };

            if (chartInstances.priority && typeof chartInstances.priority.destroy === 'function') {
                try { chartInstances.priority.destroy(); } catch(e) {}
            }

            chartInstances.priority = new ApexCharts(priorityEl, options);
            // update priorityLegend colors to match chart colors
            // legend colors are computed from data (urgent -> red, others -> light)
            chartInstances.priority.render();
        }

        // Progress Chart removed (Completion donut hidden per user request)

        chartsReady.value = true;
    }, 100);
};

const statusColors = ['#64748b', '#06b6d4', '#f97316', '#22c55e'];

const statusLegend = computed(() => {
    const keys = Object.keys(props.byStatus || {});
    return keys.map((k, i) => ({
        key: k,
        label: k.replace('_', ' '),
        color: statusColors[i % statusColors.length],
        value: props.byStatus ? props.byStatus[k] : 0
    }));
});

const priorityLegend = computed(() => {
    const map = props.byPriority || {};
    const keys = Object.keys(map);
    return keys.map((k, i) => {
        const key = String(k).toLowerCase();
        let color = '#94a3b8';
        if (key.includes('urgent')) color = '#ef4444';
        else if (key.includes('high')) color = '#ec4899';
        else if (key.includes('medium') || key.includes('med')) color = '#8b5cf6';
        else if (key.includes('low')) color = '#6366f1';

        return {
            key: k,
            label: k.charAt(0).toUpperCase() + k.slice(1),
            color,
            value: map[k]
        };
    });
});
</script>

<template>
    <Head title="Task Monitoring Dashboard" />
    <AuthenticatedLayout>
            <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 page-content">
            
            <!-- Error Toast -->
            <transition name="fade">
                <div v-if="errorMessage" class="fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center gap-3 z-50">
                    <span>{{ errorMessage }}</span>
                    <button @click="errorMessage = ''" class="text-sm underline">Dismiss</button>
                </div>
            </transition>

            <!-- Loading State -->
            <transition name="fade">
                <div v-if="loading" class="fixed inset-0 bg-black/30 flex items-center justify-center z-50">
                    <div class="bg-white dark:bg-slate-800 rounded-xl p-8 flex flex-col items-center gap-4">
                        <div class="w-12 h-12 border-4 border-indigo-600 border-t-transparent rounded-full animate-spin"></div>
                        <p class="text-sm font-bold text-slate-600 dark:text-slate-300">Loading Task Monitoring Dashboard...</p>
                    </div>
                </div>
            </transition>

            <div class="max-w-[1800px] mx-auto px-4 md:px-10 py-8">
                <!-- Header -->
                <header class="mb-10">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                        <div>
                            <h1 class="text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight">📊 Task Monitoring Dashboard</h1>
                            <p class="text-slate-600 dark:text-slate-400 font-medium mt-2">Real-time overview of team tasks and workload distribution</p>
                        </div>
                        
                    </div>
                </header>

                <!-- Metrics Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                    <div class="rounded-xl shadow-sm p-6 border border-[#0f1724] bg-[#071426] text-cyan-200 hover:shadow-lg transition-shadow">
                        <p class="text-xs uppercase tracking-wide font-mono text-cyan-400 font-bold">TOTAL</p>
                        <p class="text-3xl font-bold font-mono text-white mt-2">{{ totalTasks }}</p>
                        <p class="text-xs text-slate-500 mt-2">All tasks in system</p>
                    </div>
                    <div class="rounded-xl shadow-sm p-6 border border-[#0f1724] bg-[#071426] text-cyan-200 hover:shadow-lg transition-shadow">
                        <p class="text-xs uppercase tracking-wide font-mono text-green-300 font-bold">COMPLETED</p>
                        <p class="text-3xl font-bold font-mono text-white mt-2">{{ completedTasks }}</p>
                        <p class="text-xs text-slate-500 mt-2">Tasks finished</p>
                    </div>
                    <div class="rounded-xl shadow-sm p-6 border border-[#0f1724] bg-[#071426] text-cyan-200 hover:shadow-lg transition-shadow">
                        <p class="text-xs uppercase tracking-wide font-mono text-red-400 font-bold">OVERDUE</p>
                        <p class="text-3xl font-bold font-mono text-red-400 mt-2">{{ overdueTasks }}</p>
                        <p class="text-xs text-slate-500 mt-2">Bottlenecks - Need attention</p>
                    </div>
                    <div class="rounded-xl shadow-sm p-6 border border-[#0f1724] bg-[#071426] text-cyan-200 hover:shadow-lg transition-shadow">
                        <p class="text-xs uppercase tracking-wide font-mono text-indigo-300 font-bold">COMPLETION</p>
                        <p class="text-3xl font-bold font-mono text-white mt-2">{{ completionRate }}%</p>
                        <div class="mt-3 bg-slate-800 rounded-full h-2">
                            <div class="bg-cyan-500 h-2 rounded-full transition-all duration-300" :style="{ width: completionRate + '%' }"></div>
                        </div>
                    </div>
                </div>

                <!-- Bottleneck Alert -->
                <transition name="slide">
                    <div v-if="showBottleneckAlert" class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl shadow-sm p-6 mb-8">
                        <div class="flex items-start gap-4">
                            <svg class="w-6 h-6 text-red-600 dark:text-red-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                            <div>
                                <h3 class="font-bold text-red-900 dark:text-red-200 mb-1">⚠️ Team Member Overloaded</h3>
                                <p class="text-sm text-red-800 dark:text-red-300">{{ bottleneckMessage }}</p>
                            </div>
                        </div>
                    </div>
                </transition>

                <!-- Filters Section -->
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-6 mb-8 border border-slate-200 dark:border-slate-700">
                    <h3 class="font-bold text-slate-900 dark:text-white mb-4">🔍 Filters & Sorting</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-3">Filter by Status</label>
                            <select v-model="filters.statusFilter" class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white text-sm font-medium focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="all">All Status</option>
                                <option value="not_started">Not Started</option>
                                <option value="in_progress">In Progress</option>
                                <option value="blocked">Blocked</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-3">Sort by Workload</label>
                            <select v-model="filters.sortBy" class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white text-sm font-medium focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="updated">Recently Updated</option>
                                <option value="status">By Status</option>
                                <option value="project">By Project</option>
                                <option value="workload_desc">High Workload First</option>
                                <option value="workload_asc">Low Workload First</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-3">Analysis</label>
                            <button 
                                @click="filters.showBottlenecks = !filters.showBottlenecks" 
                                :class="['w-full px-4 py-2 rounded-lg text-sm font-bold transition-all', filters.showBottlenecks ? 'bg-red-600 text-white' : 'bg-slate-200 dark:bg-slate-700 text-slate-900 dark:text-white']"
                            >
                                {{ filters.showBottlenecks ? '✓ Showing Bottlenecks' : 'Highlight Bottlenecks' }}
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Main Content Grid -->
                <div class="grid grid-cols-12 gap-8 mb-8">
                    <!-- Charts Section -->
                    <div class="col-span-12 lg:col-span-8 space-y-6">
                        <!-- Section Header -->
                        <div class="bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500 rounded-xl shadow-lg p-8 text-white">
                            <h3 class="text-2xl font-extrabold mb-2">📊 Visual Work Distribution</h3>
                            <p class="text-blue-100 text-sm">Real-time visualization of task status, priorities, and team progress</p>
                        </div>

                        <!-- Charts Grid -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Task Status Distribution -->
                            <div class="relative priority-card rounded-2xl shadow-md p-6 border border-[#0f1724] bg-[#071426] text-cyan-200 hover:shadow-lg transition-shadow">
                                <div class="flex items-center justify-between mb-1">
                                    <h4 class="font-bold font-mono text-cyan-200 text-sm">Task Status</h4>
                                    <div class="chart-legend-top">
                                        <div v-for="s in statusLegend" :key="s.key" class="flex items-center gap-2 ml-3">
                                            <span class="legend-swatch" :style="{ background: s.color, boxShadow: '0 8px 20px ' + (s.color + '33') }"></span>
                                            <span class="text-xs text-slate-300">{{ s.label }} <span class="text-xs text-slate-400">({{ s.value }})</span></span>
                                        </div>
                                    </div>
                                </div>
                                <p class="text-xs text-slate-400 mb-4">Task progression across all statuses</p>
                                <div style="position: relative; height: 260px;">
                                    <canvas id="statusChart"></canvas>
                                </div>
                                <!-- legend moved to top-right -->
                            </div>

                            <!-- Priority Distribution -->
                            <div class="relative rounded-2xl shadow-md p-6 border border-[#0f1724] bg-[#071426] text-cyan-200 hover:shadow-lg transition-shadow">
                                <div class="flex items-center justify-between mb-1">
                                    <h4 class="font-bold font-mono text-cyan-200 text-sm">Priority Breakdown</h4>
                                    <div class="chart-legend-top">
                                        <div v-for="p in priorityLegend" :key="p.key" class="flex items-center gap-2 ml-3">
                                            <span class="legend-swatch" :style="{ background: p.color, boxShadow: '0 8px 20px ' + (p.color + '33') }"></span>
                                            <span class="text-xs text-slate-300">{{ p.label }} <span class="text-xs text-slate-400">({{ p.value }})</span></span>
                                        </div>
                                    </div>
                                </div>
                                <p class="text-xs text-slate-400 mb-4">High-priority items needing focus</p>
                                <div style="position: relative; height: 260px;">
                                    <div id="priorityChart"></div>
                                </div>
                                <!-- legend moved to top-right -->
                            </div>

                            <!-- Completion Progress removed per request -->
                        </div>

                        <!-- Chart Legend & Info -->
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-slate-800 dark:to-slate-700 rounded-xl p-6 border border-blue-200 dark:border-slate-600">
                            <div class="grid grid-cols-3 gap-4">
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-slate-900 dark:text-white">{{ totalTasks }}</div>
                                    <p class="text-xs text-slate-600 dark:text-slate-400 mt-1">Total Tasks Tracked</p>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ completedTasks }}</div>
                                    <p class="text-xs text-slate-600 dark:text-slate-400 mt-1">Tasks Completed</p>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-red-600 dark:text-red-400">{{ overdueTasks }}</div>
                                    <p class="text-xs text-slate-600 dark:text-slate-400 mt-1">Overdue Tasks</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Project Completion + Team Workload -->
                    <div class="col-span-12 lg:col-span-4 flex flex-col gap-6">
                        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-6 border border-slate-200 dark:border-slate-700">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-bold text-slate-900 dark:text-white">🏁 Project Completion</h3>
                                <p class="text-sm text-slate-500 dark:text-slate-400">Completion rate per project (click to open)</p>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-4">
                                <div v-if="(projectsCompletion || []).length === 0" class="col-span-full text-slate-400 text-sm italic p-4">No project completion data available.</div>

                                <div v-for="proj in projectsCompletion" :key="proj.id" class="rounded-2xl p-3 bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-slate-700 hover:shadow-md transition-shadow">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="min-w-0">
                                            <Link :href="`/projects/${proj.id}`" class="font-semibold text-slate-900 dark:text-white truncate hover:underline">{{ proj.name }}</Link>
                                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ proj.completed_tasks }} completed • {{ proj.total_tasks }} total</p>
                                        </div>
                                        <div class="flex flex-col items-end">
                                            <div :class="['px-2 py-1 rounded-full text-xs font-bold', proj.completion_rate >= 75 ? 'bg-green-100 text-green-700' : proj.completion_rate >= 50 ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700']">
                                                {{ proj.completion_rate }}%
                                            </div>
                                        </div>
                                    </div>
                                    <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-2.5 mt-3 overflow-hidden">
                                        <div :class="['h-2.5 rounded-full transition-all', proj.completion_rate >= 75 ? 'bg-green-500' : proj.completion_rate >= 50 ? 'bg-amber-500' : 'bg-red-500']" :style="{ width: (proj.completion_rate || 0) + '%' }"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-6 border border-slate-200 dark:border-slate-700 h-fit">
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-6">👥 Team Workload Overview</h3>
                            <div class="space-y-6">
                                <div v-if="(teamMembers || []).length === 0" class="text-slate-400 text-sm italic text-center py-8">No team data found.</div>
                                <div v-for="member in sortedTeamMembers" :key="member.id" class="pb-6 border-b border-slate-200 dark:border-slate-700 last:border-0 last:pb-0">
                                    <div class="flex justify-between items-center mb-3">
                                        <div class="flex items-center gap-3 flex-grow min-w-0">
                                            <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                                                {{ (member.name || 'U').charAt(0).toUpperCase() }}
                                            </div>
                                            <div class="min-w-0 flex-grow">
                                                <p class="font-semibold text-slate-900 dark:text-white truncate">{{ member.name }}</p>
                                                <p class="text-xs text-slate-500 dark:text-slate-400">{{ member.task_count || 0 }} tasks</p>
                                            </div>
                                        </div>
                                        <div class="flex flex-col items-end gap-1 ml-2">
                                            <span :class="['text-lg font-bold', getLoadColorClass(member.workload || 0)]">
                                                {{ Math.round(member.workload || 0) }}%
                                            </span>
                                            <span v-if="(member.workload || 0) >= 90" class="text-xs font-bold text-red-600 dark:text-red-400 bg-red-100 dark:bg-red-900/30 px-2 py-1 rounded whitespace-nowrap">🚨 Overloaded</span>
                                            <span v-else-if="(member.workload || 0) >= 75" class="text-xs font-bold text-amber-600 dark:text-amber-400 bg-amber-100 dark:bg-amber-900/30 px-2 py-1 rounded whitespace-nowrap">⚠️ High Load</span>
                                        </div>
                                    </div>
                                    <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-2.5 overflow-hidden">
                                        <div :class="['h-2.5 rounded-full transition-all', getWorkloadBarClass(member.workload || 0)]" :style="{ width: Math.min(100, member.workload || 0) + '%' }"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity Section -->
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-8 border border-slate-200 dark:border-slate-700">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-6">📝 Recent Activity (Last 7 Days)</h3>
                    
                    <div v-if="filteredActivity.length === 0" class="text-slate-400 text-sm italic text-center py-8">No activities found with current filters.</div>
                    
                    <div v-else class="divide-y divide-slate-200 dark:divide-slate-700">
                        <div v-for="task in filteredActivity" :key="task.id" class="py-4 first:pt-0 last:pb-0 hover:bg-slate-50 dark:hover:bg-slate-700/50 px-4 -mx-4 rounded transition-colors">
                            <div class="flex justify-between items-start gap-4">
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-slate-900 dark:text-white truncate">{{ task.title }}</p>
                                    <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">
                                        <span class="font-medium">Project:</span> {{ task.project?.name || 'Unassigned' }}
                                        <span class="mx-2">•</span>
                                        <span class="font-medium">Assigned:</span> {{ task.assignee?.name || 'Unassigned' }}
                                    </p>
                                    <p class="text-xs text-slate-500 dark:text-slate-500 mt-2">Updated: {{ new Date(task.updated_at).toLocaleDateString() }}</p>
                                </div>
                                <span class="px-3 py-1 rounded-full text-xs font-bold whitespace-nowrap"
                                    :class="{
                                        'bg-slate-200 text-slate-800 dark:bg-slate-700 dark:text-slate-200': task.status === 'not_started',
                                        'bg-green-200 text-green-800 dark:bg-green-900/50 dark:text-green-300': task.status === 'in_progress',
                                        'bg-orange-200 text-orange-800 dark:bg-orange-900/50 dark:text-orange-300': task.status === 'blocked',
                                        'bg-green-200 text-green-800 dark:bg-green-900/50 dark:text-green-300': task.status === 'completed',
                                    }">
                                    {{ task.status.replace('_', ' ').toUpperCase() }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Overdue Tasks Section -->
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-8 border border-slate-200 dark:border-slate-700 mt-8">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-6">🚨 Tasks at Risk (Overdue)</h3>
                    <div class="divide-y divide-slate-200 dark:divide-slate-700">
                        <div v-if="!recentActivity || recentActivity.filter(t => t.status !== 'completed' && new Date(t.due_date) < new Date()).length === 0" class="text-slate-400 text-sm italic p-4 text-center">No overdue tasks found.</div>
                        <div v-for="t in (recentActivity || []).filter(task => task.status !== 'completed' && task.due_date && new Date(task.due_date) < new Date()).slice(0, 10)" :key="t.id" class="p-4 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                            <div class="font-semibold text-slate-900 dark:text-white">{{ t.title }}</div>
                            <div class="text-sm text-slate-600 dark:text-slate-400 mt-1">Assigned: {{ t.assignee_name || 'Unassigned' }}</div>
                            <div class="text-xs text-red-600 dark:text-red-400 mt-2">Due: {{ new Date(t.due_date).toLocaleDateString() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style>
.fade-enter-active, .fade-leave-active { transition: opacity 0.5s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }

.slide-enter-active, .slide-leave-active { transition: all 0.3s ease-out; }
.slide-enter-from, .slide-leave-to { transform: translateY(-10px); opacity: 0; }

/* Tech style helpers */
.neon-chip { display: inline-block; padding: 6px 10px; border-radius: 999px; font-size: 12px; background: rgba(99,102,241,0.08); color: #c7d2fe; border: 1px solid rgba(99,102,241,0.18); box-shadow: 0 4px 18px rgba(99,102,241,0.06); font-weight:700 }
.neon-chip.neon-urgent { background: rgba(236,72,153,0.08); color: #fecaca; border-color: rgba(236,72,153,0.18); box-shadow: 0 6px 20px rgba(236,72,153,0.06) }
.tech-subtle { color: rgba(148,163,184,0.85) }
.font-console { font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, 'Roboto Mono', 'Courier New', monospace }

/* compact mode styles removed */

/* Legend top-right and glow */
.chart-legend-top { display: flex; gap: 0.6rem; align-items: center }
.legend-swatch { width: 14px; height: 12px; border-radius: 4px; border: 1px solid rgba(255,255,255,0.06); display: inline-block }
.chart-legend-top .text-xs { white-space: nowrap }

/* Report page text contrast adjustments */
.page-content { color: #cbd5e1 }
.page-content .text-slate-900 { color: #eef6ff !important }
.page-content .text-slate-600 { color: #9fb0bf !important }
.page-content .text-slate-500 { color: #94a3b8 !important }
.page-content .text-slate-400 { color: #7f8b96 !important }
.page-content .text-slate-300 { color: #7f9aae !important }
.page-content p, .page-content span, .page-content li { color: inherit }

/* Priority card hover fixes: ensure labels and axis text remain readable */
.priority-card:hover { color: inherit }
.priority-card:hover .text-sm, .priority-card:hover .text-xs, .priority-card:hover .chart-legend-top .text-xs { color: #000 !important }
.priority-card:hover .apexcharts-xaxis-label, .priority-card:hover .apexcharts-yaxis-label { fill: #000 !important }
.priority-card:hover .apexcharts-tooltip text, .priority-card:hover .apexcharts-tooltip .apexcharts-tooltip-text { color: #000 !important }
.priority-card:hover .apexcharts-tooltip { color: #000 !important }
</style>
