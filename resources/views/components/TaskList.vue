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
                        <th
                            v-if="isColumnVisible('epic_task')"
                            data-column="epic_task"
                        >
                            Epic/Task
                        </th>
                        <th
                            v-if="isColumnVisible('assignee')"
                            data-column="assignee"
                        >
                            Assignee
                        </th>
                        <th
                            v-if="isColumnVisible('plan_start_date')"
                            data-column="plan_start_date"
                        >
                            Plan Start Date
                        </th>
                        <th
                            v-if="isColumnVisible('plan_end_date')"
                            data-column="plan_end_date"
                        >
                            Plan End Date
                        </th>
                        <th
                            v-if="isColumnVisible('actual_start_date')"
                            data-column="actual_start_date"
                        >
                            Actual Start Date
                        </th>
                        <th
                            v-if="isColumnVisible('actual_end_date')"
                            data-column="actual_end_date"
                        >
                            Actual End Date
                        </th>
                        <th
                            v-if="isColumnVisible('status')"
                            data-column="status"
                        >
                            Status
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="task in visibleTasks" :key="task.id">
                        <tr class="bg-light">
                            <td>{{ task.id }}</td>
                            <td v-if="isColumnVisible('epic_task')">
                                <span
                                    v-if="task.type === 'task' && isBlankQuery"
                                >
                                    └
                                </span>
                                {{ task.name }}
                            </td>
                            <td v-if="isColumnVisible('assignee')">
                                {{ task.assignee?.account || "N/A" }}
                            </td>
                            <td v-if="isColumnVisible('plan_start_date')">
                                {{ task.plan_start_date }}
                            </td>
                            <td v-if="isColumnVisible('plan_end_date')">
                                {{ task.plan_end_date }}
                            </td>
                            <td v-if="isColumnVisible('actual_start_date')">
                                {{ task.actual_start_date }}
                            </td>
                            <td v-if="isColumnVisible('actual_end_date')">
                                {{ task.actual_end_date }}
                            </td>
                            <td v-if="isColumnVisible('status')">
                                {{ task.status }}
                            </td>
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
    filteredTasks: Array,
    blankQuery: Boolean,
    visibleColumns: Array, // Nhận danh sách cột hiển thị từ component cha
});

const isBlankQuery = computed(() => props.blankQuery ?? true);

const visibleTasks = computed(() => {
    let tasks = props.filteredTasks;
    return tasks;
});

// Hàm kiểm tra cột có hiển thị không
const isColumnVisible = (column) => {
    return props.visibleColumns.includes(column);
};
</script>
