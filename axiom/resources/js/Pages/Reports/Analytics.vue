<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    totalTasks: Number,
    completedTasks: Number,
    overdueTasks: Number,
    byStatus: Object,
    byPriority: Object,
    completionRate: Number,
    recentActivity: Array,
});
</script>

<template>
    <Head title="Analytics" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Analytics & Reports</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

                <!-- Summary Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Total Tasks</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-1">{{ totalTasks }}</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Completed</p>
                        <p class="text-3xl font-bold text-green-600 mt-1">{{ completedTasks }}</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Overdue</p>
                        <p class="text-3xl font-bold text-red-600 mt-1">{{ overdueTasks }}</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Completion Rate</p>
                        <p class="text-3xl font-bold text-indigo-600 mt-1">{{ completionRate }}%</p>
                        <div class="mt-2 bg-gray-200 rounded-full h-2">
                            <div class="bg-indigo-500 h-2 rounded-full" :style="{ width: completionRate + '%' }"></div>
                        </div>
                    </div>
                </div>

                <!-- By Status & Priority -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                        <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-4">Tasks by Status</h3>
                        <div class="space-y-3">
                            <div v-for="(count, status) in byStatus" :key="status" class="flex items-center gap-3">
                                <span class="text-sm text-gray-600 dark:text-gray-400 w-28 capitalize">{{ status.replace('_', ' ') }}</span>
                                <div class="flex-1 bg-gray-200 rounded-full h-3">
                                    <div class="h-3 rounded-full"
                                        :class="{
                                            'bg-gray-400': status === 'not_started',
                                            'bg-yellow-400': status === 'in_progress',
                                            'bg-green-500': status === 'completed',
                                            'bg-red-400': status === 'blocked',
                                        }"
                                        :style="{ width: totalTasks > 0 ? (count / totalTasks * 100) + '%' : '0%' }">
                                    </div>
                                </div>
                                <span class="text-sm font-medium w-8 text-right">{{ count }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                        <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-4">Tasks by Priority</h3>
                        <div class="space-y-3">
                            <div v-for="(count, priority) in byPriority" :key="priority" class="flex items-center gap-3">
                                <span class="text-sm text-gray-600 dark:text-gray-400 w-16 capitalize">{{ priority }}</span>
                                <div class="flex-1 bg-gray-200 rounded-full h-3">
                                    <div class="h-3 rounded-full"
                                        :class="{
                                            'bg-gray-400': priority === 'low',
                                            'bg-blue-400': priority === 'medium',
                                            'bg-orange-400': priority === 'high',
                                            'bg-red-500': priority === 'urgent',
                                        }"
                                        :style="{ width: totalTasks > 0 ? (count / totalTasks * 100) + '%' : '0%' }">
                                    </div>
                                </div>
                                <span class="text-sm font-medium w-8 text-right">{{ count }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                    <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-4">Recently Updated Tasks (Last 7 Days)</h3>
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        <div v-if="recentActivity.length === 0" class="py-4 text-gray-500 text-sm">No recent activity.</div>
                        <div v-for="task in recentActivity" :key="task.id" class="py-3 flex justify-between items-center">
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ task.title }}</p>
                                <p class="text-xs text-gray-500">{{ task.project?.name }} · {{ task.assignee?.name || 'Unassigned' }}</p>
                            </div>
                            <span class="text-xs px-2 py-1 rounded-full"
                                :class="{
                                    'bg-gray-100 text-gray-700': task.status === 'not_started',
                                    'bg-yellow-100 text-yellow-700': task.status === 'in_progress',
                                    'bg-green-100 text-green-700': task.status === 'completed',
                                    'bg-red-100 text-red-700': task.status === 'blocked',
                                }">
                                {{ task.status.replace('_', ' ') }}
                            </span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
