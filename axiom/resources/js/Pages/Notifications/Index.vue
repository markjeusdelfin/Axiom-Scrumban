<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    notifications: Object,
    unreadCount: Number,
});

const markAllForm = useForm({});
const markAll = () => markAllForm.post(route('notifications.read-all'));
</script>

<template>
    <Head title="Notifications" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Notifications</h2>
                <button v-if="unreadCount > 0" @click="markAll" class="text-sm text-indigo-600 hover:underline">Mark all as read</button>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg divide-y divide-gray-200 dark:divide-gray-700">
                    <div v-if="notifications.data.length === 0" class="p-6 text-gray-500 text-center">
                        No notifications yet.
                    </div>
                    <div v-for="n in notifications.data" :key="n.id"
                        class="p-4 flex items-start gap-3"
                        :class="n.read_at ? '' : 'bg-indigo-50 dark:bg-indigo-900/20'"
                    >
                        <div class="flex-shrink-0 mt-1">
                            <span v-if="n.data.type === 'overdue'" class="text-red-500">⚠️</span>
                            <span v-else class="text-yellow-500">🔔</span>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-900 dark:text-gray-100">{{ n.data.message }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ n.created_at }}</p>
                        </div>
                        <Link v-if="!n.read_at" :href="route('notifications.read', n.id)" method="post" as="button"
                            class="text-xs text-indigo-600 hover:underline flex-shrink-0">
                            Mark read
                        </Link>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="mt-4 flex justify-center gap-2" v-if="notifications.last_page > 1">
                    <Link v-for="link in notifications.links" :key="link.label"
                        :href="link.url || '#'"
                        class="px-3 py-1 text-sm rounded border"
                        :class="link.active ? 'bg-indigo-600 text-white border-indigo-600' : 'border-gray-300 text-gray-600 hover:bg-gray-50'"
                        v-html="link.label"
                    />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
