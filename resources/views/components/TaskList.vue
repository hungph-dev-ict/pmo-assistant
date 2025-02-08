<template>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">List Task</h3>
        </div>

        <div class="card-body p-0">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Epic/Task</th>
                        <th>Assignee</th>
                        <th>Plan Start Date</th>
                        <th>Plan End Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="task in visibleTasks" :key="task.id">
                        <tr class="bg-light">
                            <td>{{ task.id }}</td>
                            <td>
                                <span
                                    v-if="task.type === 'task' && (!searchQuery || !searchQuery.trim()) && !selectedAssignee">└</span>
                                {{ task.name }}
                            </td>
                            <td>{{ task.assignee?.account || 'N/A' }}</td>
                            <td>{{ task.plan_start_date }}</td>
                            <td>{{ task.plan_end_date }}</td>
                            <td>{{ task.status }}</td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup>
import { defineProps, computed } from "vue";

const props = defineProps({
    filteredTasks: Array, // Danh sách đã lọc từ TaskSearchBox.vue
    searchQuery: String, // Nhận searchQuery từ component cha
    selectedAssignee: String, // Nhận selectedAssignee từ component cha
    selectedStatuses: Array, // Nhận selectedStatuses từ component cha
});

const visibleTasks = computed(() => {
    let tasks = props.filteredTasks;

    // Lọc theo trạng thái nếu có selectedStatuses
    if (props.selectedStatuses.length > 0) {
        tasks = tasks.filter(task => props.selectedStatuses.includes(task.status));
    }

    return tasks;
});
</script>
