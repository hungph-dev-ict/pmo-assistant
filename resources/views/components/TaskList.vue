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
                        <th v-if="isColumnVisible('epic_task')" data-column="epic_task">
                            Epic/Task
                        </th>
                        <th v-if="isColumnVisible('assignee')" data-column="assignee">
                            Assignee
                        </th>
                        <th v-if="isColumnVisible('plan_start_date')" data-column="plan_start_date">
                            Plan Start Date
                        </th>
                        <th v-if="isColumnVisible('plan_end_date')" data-column="plan_end_date">
                            Plan End Date
                        </th>
                        <th v-if="isColumnVisible('actual_start_date')" data-column="actual_start_date">
                            Actual Start Date
                        </th>
                        <th v-if="isColumnVisible('actual_end_date')" data-column="actual_end_date">
                            Actual End Date
                        </th>
                        <th v-if="isColumnVisible('status')" data-column="status">
                            Status
                        </th>
                        <th v-if="isColumnVisible('action')">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="task in visibleTasks" :key="task.id">
                        <tr class="bg-light">
                            <td>{{ task.id }}</td>
                            <td v-if="isColumnVisible('epic_task')">
                                <span v-if="task.type === 'task' && isBlankQuery"> └ </span>

                                <span v-if="!task.isEditing">{{ task.name }}</span>

                                <input v-else type="text" v-model="task.editedName"
                                    class="form-control form-control-sm" />
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
                            <td v-if="isColumnVisible('action')">
                                <template v-if="!task.isEditing">
                                    <a class="btn btn-info btn-sm" href="#" @click.prevent="editTask(task)">
                                        <i class="fas fa-pencil-alt"></i> Edit
                                    </a>
                                    <a class="btn btn-danger btn-sm" href="#">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                </template>

                                <template v-else>
                                    <a class="btn btn-success btn-sm" href="#" @click.prevent="updateTask(task)">
                                        <i class="fas fa-save"></i> Update
                                    </a>
                                    <a class="btn btn-secondary btn-sm" href="#" @click.prevent="cancelEdit(task)">
                                        <i class="fas fa-times"></i> Cancel
                                    </a>
                                </template>
                            </td>

                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup>
import { defineProps, computed, ref, onMounted, defineEmits } from "vue";

const props = defineProps({
    filteredTasks: Array,
    blankQuery: Boolean,
    visibleColumns: Array,
});

// Tạo danh sách task dưới dạng ref để có thể cập nhật giá trị
const tasks = ref([]);

onMounted(() => {
    tasks.value = props.filteredTasks.map(task => ({
        ...task,
        isEditing: false,
        editedName: task.name,
    }));
});

const isBlankQuery = computed(() => props.blankQuery ?? true);
const visibleTasks = computed(() => {
    let tasks = props.filteredTasks;
    return tasks;
});

// Kiểm tra xem cột có hiển thị không
const isColumnVisible = (column) => {
    return props.visibleColumns.includes(column);
};

// Hàm bật chế độ edit
const editTask = (task) => {
    task.isEditing = true;
    task.editedName = task.name;
};

// Emit sự kiện update để thông báo lên component cha
const emit = defineEmits(["update-task"]);

// Hàm cập nhật task
const updateTask = (task) => {
    task.name = task.editedName;
    task.isEditing = false;

    // Emit để component cha xử lý cập nhật vào database
    emit("update-task", task);

    toastr.success("Updated successfully!");
};

const cancelEdit = (task) => {
    Object.assign(task, task.originalData); // Khôi phục dữ liệu gốc
    task.isEditing = false;
};
</script>
