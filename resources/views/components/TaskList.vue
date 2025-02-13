<template>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">List Task</h3>
        </div>

        <div class="card-body">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>#</th>
                        <th v-if="isColumnVisible('epic_task')" data-column="epic_task">
                            Epic/Task
                        </th>
                        <th v-if="isColumnVisible('priority')" data-column="priority">
                            Priority
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
                        <th v-if="isColumnVisible('action')" class="text-center">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="task in visibleTasks" :key="task.id">
                        <tr class="bg-light">
                            <td>{{ task.id }}</td>
                            <td v-if="isColumnVisible('epic_task')">
                                <span v-if="task.type === 'task' && isBlankQuery && !task.isEditing"> └ </span>

                                <span v-if="!task.isEditing">{{ task.name }}</span>

                                <input v-else type="text" v-model="task.editedName"
                                    class="form-control form-control-sm" />
                            </td>
                            <td v-if="isColumnVisible('priority')">
                                <span v-if="!task.isEditing">{{ task.priority }}</span>
                                <select v-else class="form-control form-control-sm priority-select"
                                    v-model="task.editedPriority">
                                    <option :key="0" :value="'On Hold'">On Hold</option>
                                    <option :value="'Low'">Low</option>
                                    <option :value="'Medium'">Medium</option>
                                    <option :value="'High'">High</option>
                                    <option :key="4" :value="'Critical'">Critical</option>
                                </select>
                            </td>
                            <td v-if="isColumnVisible('assignee')">
                                <span v-if="!task.isEditing">{{ task.assignee?.account || "N/A" }}</span>
                                <select v-else class="form-control form-control-sm assignee-select"
                                    v-model="task.editedAssignee">
                                    <option v-for="user in listAssignee" :key="user.id" :value="user.id">
                                        {{ user.account }}
                                    </option>
                                </select>
                            </td>
                            <td v-if="isColumnVisible('plan_start_date')">
                                <span v-if="!task.isEditing">{{ task.plan_start_date }}</span>
                                <div v-else class="input-group date plan-start-datepicker" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input"
                                        v-model="task.editedPlanStartDate" data-target=".plan-start-datepicker" />
                                    <div class="input-group-append" data-target=".plan-start-datepicker"
                                        data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </td>

                            <td v-if="isColumnVisible('plan_end_date')">
                                <span v-if="!task.isEditing">{{ task.plan_end_date }}</span>
                                <div v-else class="input-group date plan-end-datepicker" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input"
                                        v-model="task.editedPlanEndDate" data-target=".plan-end-datepicker" />
                                    <div class="input-group-append" data-target=".plan-end-datepicker"
                                        data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </td>

                            <td v-if="isColumnVisible('actual_start_date')">
                                <span v-if="!task.isEditing">{{ task.actual_start_date }}</span>
                                <div v-else class="input-group date actual-start-datepicker"
                                    data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input"
                                        v-model="task.editedActualStartDate" data-target=".actual-start-datepicker" />
                                    <div class="input-group-append" data-target=".actual-start-datepicker"
                                        data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </td>

                            <td v-if="isColumnVisible('actual_end_date')">
                                <span v-if="!task.isEditing">{{ task.actual_end_date }}</span>
                                <div v-else class="input-group date actual-end-datepicker" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input"
                                        v-model="task.editedActualEndDate" data-target=".actual-end-datepicker" />
                                    <div class="input-group-append" data-target=".actual-end-datepicker"
                                        data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </td>

                            <td v-if="isColumnVisible('status')">
                                <span v-if="!task.isEditing">{{ task.status }}</span>
                                <select v-else class="form-control form-control-sm status-select"
                                    v-model="task.editedStatus">
                                    <option v-for="status in statusList" :key="status" :value="status">
                                        {{ status }}
                                    </option>
                                </select>
                            </td>
                            <td v-if="isColumnVisible('action')" class="project-actions text-center">
                                    <template v-if="!task.isEditing">
                                        <a class="btn btn-info btn-sm mr-2" href="#" @click.prevent="editTask(task)">
                                            <i class="fas fa-pencil-alt"></i> Edit
                                        </a>
                                        <a class="btn btn-danger btn-sm" href="#">
                                            <i class="fas fa-trash"></i> Delete
                                        </a>
                                    </template>

                                    <template v-else>
                                        <a class="btn btn-success btn-sm mr-2" href="#"
                                            @click.prevent="updateTask(task)">
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
import { defineProps, computed, ref, nextTick, onMounted, defineEmits } from "vue";

const props = defineProps({
    projectId: String,
    filteredTasks: Array,
    blankQuery: Boolean,
    visibleColumns: Array,
    listAssignee: Array
});

// Tạo danh sách task dưới dạng ref để có thể cập nhật giá trị
const tasks = ref([]);
const statusList = ref([
    "Not Started",
    "In Progress",
    "Resolved",
    "Feedback",
    "Done"]);

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
    task.editedPriority = task.priority;
    task.editedAssignee = task.assignee?.id;
    task.editedStatus = task.status;
    task.editedPlanStartDate = task.plan_start_date;
    task.editedPlanEndDate = task.plan_end_date;
    task.editedActualStartDate = task.actual_start_date;
    task.editedActualEndDate = task.actual_end_date;

    nextTick(initPlugins(task));
};

// Hàm kích hoạt select2 và datetimepicker
const initPlugins = (task) => {
    nextTick(() => {
        // Khởi động lại select2
        $(".assignee-select").select2().on("change", function (e) {
            task.editedAssignee = $(this).val();
        });
        $(".priority-select").select2().on("change", function (e) {
            task.editedPriority = $(this).val();
        });
        $(".status-select").select2().on("change", function (e) {
            task.editedStatus = $(this).val();
        });

        // Khởi động lại datetimepicker cho tất cả các trường ngày tháng
        $(".plan-start-datepicker, .plan-end-datepicker, .actual-start-datepicker, .actual-end-datepicker").datetimepicker({
            format: "YYYY-MM-DD",
            icons: { time: "fa fa-clock", date: "fa fa-calendar" },
        });

        $(".plan-start-datepicker").on("change.datetimepicker", function (e) {
            let newPlanStartDate = e.date ? e.date.format("YYYY-MM-DD") : "";
            task.editedPlanStartDate = newPlanStartDate;
        });

        $(".plan-end-datepicker").on("change.datetimepicker", function (e) {
            let newPlanEndDate = e.date ? e.date.format("YYYY-MM-DD") : "";
            task.editedPlanEndDate = newPlanEndDate;
        });

        $(".actual-start-datepicker").on("change.datetimepicker", function (e) {
            let newActualStartDate = e.date ? e.date.format("YYYY-MM-DD") : "";
            task.editedActualStartDate = newActualStartDate;
        });

        $(".actual-end-datepicker").on("change.datetimepicker", function (e) {
            let newActualEndDate = e.date ? e.date.format("YYYY-MM-DD") : "";
            task.editedActualEndDate = newActualEndDate;
        });
    });
};

// Emit sự kiện update để thông báo lên component cha
const emit = defineEmits(["update-task"]);

const updateTask = async (task) => {
    // Hủy Select2 trước khi cập nhật giao diện
    destroySelect2();

    // Tạo một object mới với dữ liệu đã chỉnh sửa
    const updatedTask = {
        ...task, // Giữ lại các thuộc tính cũ
        name: task.editedName,
        priority: task.editedPriority,
        assignee: task.editedAssignee,
        status: task.editedStatus,
        plan_start_date: task.editedPlanStartDate,
        plan_end_date: task.editedPlanEndDate,
        actual_start_date: task.editedActualStartDate,
        actual_end_date: task.editedActualEndDate,
        isEditing: false,
    };

    const priorityMapping = {
        "On Hold": 0,
        "Low": 1,
        "Medium": 2,
        "High": 3,
        "Critical": 4
    };

    const statusMapping = {
        "Not Started": 0,
        "In Progress": 1,
        "Resolved": 2,
        "Feedback": 3,
        "Done": 4
    };

    try {
        // Gọi API cập nhật dữ liệu
        await axios.put(`/api/pm/${props.projectId}/tasks/${task.id}/update`, {
            name: updatedTask.name,
            priority: priorityMapping[updatedTask.priority] ?? updatedTask.priority, // Map priority
            assignee: updatedTask.assignee,
            status: statusMapping[updatedTask.status] ?? updatedTask.status, // Map status            plan_start_date: task.plan_start_date,
            plan_start_date: updatedTask.plan_start_date,
            plan_end_date: updatedTask.plan_end_date,
            actual_start_date: updatedTask.actual_start_date,
            actual_end_date: updatedTask.actual_end_date,
        });

        toastr.success("Updated successfully!");
    } catch (error) {
        // Lấy thông tin lỗi từ response
        const errorMessage = error.response?.data?.message || "Failed to create task!";
        const errorDetail = error.response?.data?.error || "Unknown error";

        // Hiển thị toastr lỗi với cả message và error detail
        toastr.error(`${errorMessage}: ${errorDetail}`);
    }
    task.isEditing = false;
    // Emit để component cha xử lý
    emit("update-task");
};

const cancelEdit = (task) => {
    // Hủy Select2 trước khi cập nhật DOM
    destroySelect2();

    Object.assign(task, task.originalData); // Khôi phục dữ liệu gốc
    task.isEditing = false;
};

const destroySelect2 = () => {
    $(".assignee-select").select2("destroy");
    $(".priority-select").select2("destroy");
    $(".status-select").select2("destroy");
};
</script>
