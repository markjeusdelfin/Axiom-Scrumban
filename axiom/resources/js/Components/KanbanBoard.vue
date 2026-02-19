<script setup>
import { Link, router } from '@inertiajs/vue3';
import { ref, watch, onMounted, onUnmounted } from 'vue';
import draggable from 'vuedraggable';

const props = defineProps({
    tasks: Array,
    projectId: Number,
});

const columns = ref([
    { id: 'not_started', title: 'To Do', tasks: [] },
    { id: 'in_progress', title: 'In Progress', tasks: [] },
    { id: 'completed', title: 'Done', tasks: [] },
    { id: 'blocked', title: 'Blocked', tasks: [] },
]);

const distributeTasks = () => {
    columns.value.forEach(col => col.tasks = []);
    props.tasks.forEach(task => {
        const col = columns.value.find(c => c.id === task.status);
        if (col) col.tasks.push(task);
    });
};

watch(() => props.tasks, distributeTasks, { immediate: true });

const onDragChange = (evt, status) => {
    if (evt.added) {
        const task = evt.added.element;
        updateStatus(task, status);
    }
};

const updateStatus = (task, newStatus) => {
    router.patch(route('tasks.update-status', task.id), {
        status: newStatus
    }, {
        preserveScroll: true,
        onError: (errors) => {
            alert(errors.message || 'Failed to update status');
            router.reload();
        }
    });
};

// Real-time: listen for task updates via Echo/Reverb
let channel = null;

onMounted(() => {
    if (props.projectId && window.Echo) {
        channel = window.Echo.channel(`project.${props.projectId}`);

        // When a task is moved (status changed)
        channel.listen('.task.moved', (e) => {
            columns.value.forEach(col => {
                const idx = col.tasks.findIndex(t => t.id === e.task_id);
                if (idx !== -1) {
                    const [task] = col.tasks.splice(idx, 1);
                    const newCol = columns.value.find(c => c.id === e.new_status);
                    if (newCol) {
                        task.status = e.new_status;
                        newCol.tasks.push(task);
                    }
                }
            });
        });

        // When a task is updated (title, assignee, etc.)
        channel.listen('.task.updated', (e) => {
            columns.value.forEach(col => {
                const idx = col.tasks.findIndex(t => t.id === e.task.id);
                if (idx !== -1) {
                    col.tasks[idx] = { ...col.tasks[idx], ...e.task };
                }
            });
        });
    }
});

onUnmounted(() => {
    if (props.projectId && window.Echo) {
        window.Echo.leaveChannel(`project.${props.projectId}`);
    }
});
</script>

<template>
    <div class="flex gap-4 h-full overflow-x-auto pb-4">
        <div v-for="column in columns" :key="column.id" class="w-80 flex-shrink-0 bg-gray-100 dark:bg-gray-900 rounded-lg p-4 flex flex-col">
            <h3 class="font-semibold text-gray-700 dark:text-gray-300 mb-4">{{ column.title }} ({{ column.tasks.length }})</h3>
            
            <draggable
                v-model="column.tasks"
                group="tasks"
                item-key="id"
                class="flex-1 space-y-3 overflow-y-auto min-h-[50px]"
                @change="(evt) => onDragChange(evt, column.id)"
            >
                <template #item="{ element }">
                    <div class="bg-white dark:bg-gray-800 p-4 rounded shadow cursor-move border-l-4"
                        :class="{
                            'border-gray-500': element.priority === 'low',
                            'border-blue-500': element.priority === 'medium',
                            'border-orange-500': element.priority === 'high',
                            'border-red-500': element.priority === 'urgent',
                        }"
                    >
                        <h4 class="font-medium text-gray-900 dark:text-gray-100">{{ element.title }}</h4>
                        <div class="text-xs text-gray-500 mt-2 flex justify-between items-center">
                            <span>{{ element.project ? element.project.name : (element.assignee ? element.assignee.name : '') }}</span>
                            <span>{{ element.due_date || '' }}</span>
                        </div>
                        <div class="mt-2 flex justify-end">
                            <Link :href="route('tasks.edit', element.id)" class="text-xs text-indigo-500 hover:underline">Edit</Link>
                        </div>
                    </div>
                </template>
            </draggable>
        </div>
    </div>
</template>
