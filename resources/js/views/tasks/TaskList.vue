<template>
    <div>
        <div>
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">List Task</h3>
                    <div class="card-tools">
                        <!-- <button @click="exportTasks" class="btn btn-info btn-sm mr-2">
                    Export to CSV
                </button> -->
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>

                <div class="card-body" style="min-height: 80vh;max-height: 80vh; overflow-y: auto">
                    <table class="table table-sm fixed-header-table table-bordered" style="margin-right: 20px">
                        <thead>
                            <tr>
                                <th style="width: 3%" class="text-center">Key</th>
                                <th style="width: 2.5%" class="text-center">Type</th>
                                <th v-if="isColumnVisible('epic_task')" class="text-center" style="width: 25.5%">
                                    Summary
                                </th>
                                <th v-if="isColumnVisible('priority')" class="text-center" style="width: 5%">
                                    Priority
                                </th>
                                <th v-if="isColumnVisible('assignee')" class="text-center" style="width: 6%">
                                    Assignee
                                </th>
                                <th v-if="isColumnVisible('plan_start_date')" style="width: 8%" class="text-center">
                                    Plan Start Date
                                </th>
                                <th v-if="isColumnVisible('plan_end_date')" style="width: 8%" class="text-center">
                                    Plan End Date
                                </th>
                                <th v-if="isColumnVisible('actual_start_date')" style="width: 8%" class="text-center">
                                    Actual Start Date
                                </th>
                                <th v-if="isColumnVisible('actual_end_date')" style="width: 8%" class="text-center">
                                    Actual End Date
                                </th>
                                <th v-if="isColumnVisible('plan-effort')" style="width: 4%" class="text-center">
                                    Plan Effort
                                </th>
                                <th v-if="isColumnVisible('actual-effort')" style="width: 4%" class="text-center">
                                    Actual Effort
                                </th>
                                <th v-if="isColumnVisible('status')" style="width: 6%" class="text-center">
                                    Status
                                </th>
                                <th class="text-center" v-if="isColumnVisible('action')" style="width: 10%">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <template v-for="task in taskListData.tasks" :key="task.id" v-show="
                            task.type !== 'task' ||
                            expandedTasks.includes(task.parent_id)
                        ">
                                <tr class="bg-light">
                                    <td class="text-center">
                                        {{ task.id }}
                                    </td>
                                    <td class="text-center">
                                        <i :class="TASK_ICONS[task.type]"></i>
                                    </td>
                                    <td v-if="isColumnVisible('epic_task')">
                                        <!-- Nút toggle cho Epic -->
                                        <!-- <span v-if="task.type === 0" @click="toggleTask(task.id)"
                                            class="cursor-pointer mr-2">
                                            <i :class="{
                                                'fas fa-chevron-right':
                                                    !expandedTasks.includes(
                                                        task.id
                                                    ),
                                                'fas fa-chevron-down':
                                                    expandedTasks.includes(
                                                        task.id
                                                    ),
                                            }"></i>
                                        </span> -->

                                        <!-- Hiển thị tên task -->
                                        <a v-if="
                                            !task.isEditing ||
                                            (task.isEditing &&
                                                !hasPermissionClient &&
                                                !hasPermissionPm &&
                                                hasPermissionStaff)
                                        " :href="`/${props.projectId}/task/${task.id}`">
                                            {{ task.name }}
                                        </a>

                                        <input v-else type="text" v-model="task.editedName" class="form-control" />
                                    </td>
                                    <td style="width: 5%" v-if="isColumnVisible('priority')" class="text-center">
                                        <PriorityIcon v-if="
                                            !task.isEditing ||
                                            (task.isEditing &&
                                                !hasPermissionClient &&
                                                !hasPermissionPm &&
                                                hasPermissionStaff)
                                        " :priority="task.priority" />
                                        <select v-else class="form-control priority-select"
                                            v-model="task.editedPriority">
                                            <option v-for="[priority, id] in Object.entries(listPriorities)" :key="id"
                                                :value="id">
                                                {{ priority }}
                                            </option>
                                        </select>
                                    </td>
                                    <td style="width: 6%" v-if="isColumnVisible('assignee')" class="text-center">
                                        <span v-if="
                                            !task.isEditing ||
                                            (task.isEditing &&
                                                !hasPermissionClient &&
                                                !hasPermissionPm &&
                                                hasPermissionStaff)
                                        ">
                                            <strong v-if="
                                                task.assignee_user?.account ==
                                                currentUserAccount
                                            ">
                                                {{
                                                    task.assignee_user?.account || "N/A"
                                                }}
                                            </strong>
                                            <template v-else>
                                                {{
                                                    task.assignee_user?.account || "N/A"
                                                }}
                                            </template>
                                        </span>

                                        <select v-else class="form-control assignee-select"
                                            v-model="task.editedAssignee">
                                            <option v-for="[account, id] in Object.entries(listAssignee)" :key="id"
                                                :value="id">
                                                {{ account }}
                                            </option>
                                        </select>
                                    </td>

                                    <td style="width: 8%" class="text-center" v-if="isColumnVisible('plan_start_date')">
                                        <span v-if="
                                            !task.isEditing ||
                                            (task.isEditing &&
                                                !hasPermissionClient &&
                                                !hasPermissionPm &&
                                                hasPermissionStaff)
                                        "><span :style="{
                                            color: isDelayedStart(
                                                task.plan_start_date,
                                                task.status
                                            )
                                                ? 'red'
                                                : 'inherit',
                                        }">
                                                {{ task.plan_start_date }}
                                                <span v-if="
                                                    isDelayedStart(
                                                        task.plan_start_date,
                                                        task.status
                                                    )
                                                ">🔥</span>
                                            </span></span>
                                        <div v-else class="input-group date plan-start-datepicker"
                                            data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input"
                                                v-model="task.editedPlanStartDate"
                                                data-target=".plan-start-datepicker" />
                                            <div class="input-group-append" data-target=".plan-start-datepicker"
                                                data-toggle="datetimepicker">
                                                <div class="input-group-text">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td style="width: 8%" class="text-center" v-if="isColumnVisible('plan_end_date')">
                                        <span v-if="
                                            !task.isEditing ||
                                            (task.isEditing &&
                                                !hasPermissionClient &&
                                                !hasPermissionPm &&
                                                hasPermissionStaff)
                                        ">
                                            <span :style="{
                                                color: isOverdue(
                                                    task.plan_end_date,
                                                    task.status
                                                )
                                                    ? 'red'
                                                    : 'inherit',
                                            }">
                                                {{ task.plan_end_date }}
                                                <span v-if="
                                                    isOverdue(
                                                        task.plan_end_date,
                                                        task.status
                                                    )
                                                ">🔥</span>
                                            </span>
                                        </span>
                                        <div v-else class="input-group date plan-end-datepicker"
                                            data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input"
                                                v-model="task.editedPlanEndDate" data-target=".plan-end-datepicker" />
                                            <div class="input-group-append" data-target=".plan-end-datepicker"
                                                data-toggle="datetimepicker">
                                                <div class="input-group-text">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td style="width: 8%" class="text-center"
                                        v-if="isColumnVisible('actual_start_date')">
                                        <span v-if="
                                            !task.isEditing ||
                                            (task.isEditing &&
                                                !hasPermissionClient &&
                                                !hasPermissionPm &&
                                                !hasPermissionStaff)
                                        ">{{ task.actual_start_date }}</span>
                                        <div v-else class="input-group date actual-start-datepicker"
                                            data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input"
                                                v-model="task.editedActualStartDate"
                                                data-target=".actual-start-datepicker" />
                                            <div class="input-group-append" data-target=".actual-start-datepicker"
                                                data-toggle="datetimepicker">
                                                <div class="input-group-text">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td style="width: 8%" class="text-center" v-if="isColumnVisible('actual_end_date')">
                                        <span v-if="
                                            !task.isEditing ||
                                            (task.isEditing &&
                                                !hasPermissionClient &&
                                                !hasPermissionPm &&
                                                !hasPermissionStaff)
                                        ">{{ task.actual_end_date }}</span>
                                        <div v-else class="input-group date actual-end-datepicker"
                                            data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input"
                                                v-model="task.editedActualEndDate"
                                                data-target=".actual-end-datepicker" />
                                            <div class="input-group-append" data-target=".actual-end-datepicker"
                                                data-toggle="datetimepicker">
                                                <div class="input-group-text">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td style="width: 3%" v-if="isColumnVisible('plan-effort')" class="text-right">
                                        <span v-if="
                                            !task.isEditing ||
                                            (task.isEditing &&
                                                !hasPermissionClient &&
                                                !hasPermissionPm &&
                                                hasPermissionStaff)
                                        ">{{ task.plan_effort }}</span>
                                        <input v-else type="number" v-model="task.editedPlanEffort"
                                            class="form-control no-spinner" />
                                    </td>

                                    <td style="width: 3%" v-if="isColumnVisible('actual-effort')" class="text-right">
                                        {{
                                            Number(task.actual_effort) > 0
                                                ? task.actual_effort
                                                : ""
                                        }}<i v-if="
                                            Number(task.actual_effort) >
                                            Number(task.plan_effort)
                                        " class="fas fa-exclamation-triangle text-danger ml-2"
                                            title="Actual effort exceeds plan effort"></i>
                                    </td>

                                    <td style="width: 6%" v-if="isColumnVisible('status')" class="text-center">
                                        <span v-if="!task.isEditing" :class="statusClass(task.status)">{{
                                            task.task_status?.value1
                                        }}</span>
                                        <select v-else class="form-control status-select" v-model="task.editedStatus">
                                            <option v-for="[status, id] in Object.entries(listStatuses)" :key="id"
                                                :value="id">
                                                {{ status }}
                                            </option>
                                        </select>
                                    </td>
                                    <td style="width: 10%" v-if="isColumnVisible('action')" class="text-center">
                                        <template v-if="!task.isEditing">
                                            <a href="#" class="btn btn-info btn-sm mr-2" @click.prevent="editTask(task)"
                                                v-tooltip="'Edit'">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>
                                            <a v-if="
                                                hasPermissionClient ||
                                                hasPermissionPm
                                            " href="#" class="btn btn-danger btn-sm mr-2" @click="confirmDelete(task)"
                                                v-tooltip="'Delete'">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                            <a href="#" class="btn btn-primary btn-sm mr-2" @click.prevent="
                                                openLogWorkModal(task)
                                                " v-tooltip="'Log Work'">
                                                <i class="fas fa-clock"></i>
                                            </a>
                                            <button class="btn btn-secondary btn-sm" @click="copyTaskLink(task)"
                                                v-tooltip="'Share'">
                                                <i class="fas fa-link"></i>
                                            </button>
                                        </template>

                                        <template v-else>
                                            <a href="#" class="btn btn-success btn-sm mr-2"
                                                @click.prevent="updateTask(task)" v-tooltip="'Update'">
                                                <i class="fas fa-save"></i>
                                            </a>
                                            <a href="#" class="btn btn-secondary btn-sm"
                                                @click.prevent="cancelEdit(task)" v-tooltip="'Discard'">
                                                <i class="fas fa-times"></i>
                                            </a>
                                        </template>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <LogWorkModal :showModal="showLogWorkModal" :task="selectedTask" :projectId="projectId"
            @close="showLogWorkModal = false" @update-data="handleTaskUpdate" />
    </div>
</template>

<script setup>
import { computed, ref, nextTick, onMounted } from "vue";
import Swal from "sweetalert2";
import LogWorkModal from "../../components/LogWorkModal.vue";
import { TASK_ICONS, TASK_TYPES, statusClass } from "../../constants/taskConstants";

const props = defineProps({
    projectId: String,
    taskListData: Object,
    blankQuery: Boolean,
    visibleColumns: Array,
    listAssignee: Object,
    listStatuses: Object,
    listPriorities: Object,
    hasPermissionClient: Boolean,
    hasPermissionPm: Boolean,
    hasPermissionStaff: Boolean,
    currentUserId: Number,
    currentUserAccount: String,
    filtersQuery: Object,
});

const selectedTask = ref(null);
const showLogWorkModal = ref(false);
const globalIsEditting = ref(false);

onMounted(() => {
    globalIsEditting.value = false;
});

// Emit sự kiện update để thông báo lên component cha
const emit = defineEmits(["update-data", "task-list-editing"]);

// Kiểm tra xem cột có hiển thị không
const isColumnVisible = (column) => {
    return props.visibleColumns.includes(column);
};

// Hàm bật chế độ edit
const editTask = (task) => {
    emit('task-list-editing', true);
    if (globalIsEditting.value) {
        toastr.error(
            "Other worklog edit is in progress. Please cancel it before edit other."
        );
        return;
    }
    globalIsEditting.value = true;

    task.isEditing = true;
    task.editedName = task.name;
    task.editedPriority = task.priority;
    task.editedAssignee = task.assignee;
    task.editedStatus = task.status;
    task.editedPlanStartDate = task.plan_start_date;
    task.editedPlanEndDate = task.plan_end_date;
    task.editedActualStartDate = task.actual_start_date;
    task.editedActualEndDate = task.actual_end_date;
    task.editedPlanEffort = task.plan_effort;

    nextTick(initPlugins(task));
};

// Hàm khởi tạo Select2 với các sự kiện chung
const initSelect2 = (selector, updateTaskField) => {
    $(selector)
        .select2()
        .on("change", function () {
            updateTaskField($(this).val());
        })
        .on("select2:open", () => {
            setTimeout(() => {
                $(".select2-container--open .select2-search__field").first().focus();
            }, 50);
        });
};

// Hàm khởi tạo datetimepicker với sự kiện thay đổi giá trị
const initDatePicker = (selector, updateTaskField) => {
    $(selector).datetimepicker({
        format: "YYYY-MM-DD",
        buttons: { showToday: true, showClear: true, showClose: true },
        icons: { today: "fa fa-calendar-day", clear: "fa fa-trash", close: "fa fa-times" },
    });
    $(selector).on("change.datetimepicker", function (e) {
        updateTaskField(e.date ? e.date.format("YYYY-MM-DD") : e.target.value || "");
    });
};

// Hàm kích hoạt toàn bộ plugins
const initPlugins = (task) => {
    nextTick(() => {
        // Khởi tạo Select2
        initSelect2(".assignee-select", (val) => task.editedAssignee = val);
        initSelect2(".priority-select", (val) => task.editedPriority = val);
        initSelect2(".status-select", (val) => task.editedStatus = val);

        // Khởi tạo Datepicker
        initDatePicker(".plan-start-datepicker", (val) => task.editedPlanStartDate = val);
        initDatePicker(".plan-end-datepicker", (val) => task.editedPlanEndDate = val);
        initDatePicker(".actual-start-datepicker", (val) => task.editedActualStartDate = val);
        initDatePicker(".actual-end-datepicker", (val) => task.editedActualEndDate = val);
    });
};

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
        plan_effort: task.editedPlanEffort,
        isEditing: false,
    };

    try {
        // Gọi API cập nhật dữ liệu
        const url = props.hasPermissionStaff
            ? `/api/staff/${props.projectId}/tasks/${task.id}/update`
            : `/api/pm/${props.projectId}/tasks/${task.id}/update`;
        await axios.put(url, {
            name: updatedTask.name,
            priority: updatedTask.priority, // Map priority
            assignee: updatedTask.assignee,
            status: updatedTask.status, // Map status
            plan_start_date: updatedTask.plan_start_date,
            plan_end_date: updatedTask.plan_end_date,
            actual_start_date: updatedTask.actual_start_date,
            actual_end_date: updatedTask.actual_end_date,
            ...(updatedTask.plan_effort !== null && {
                plan_effort: updatedTask.plan_effort,
            }),
        });

        toastr.success("Updated successfully!");
    } catch (error) {
        // Lấy thông tin lỗi từ response
        // const errorMessage =
        //     error.response?.data?.message || "Failed to create task!";
        // const errorDetail = error.response?.data?.error || "Unknown error";

        // Hiển thị toastr lỗi với cả message và error detail
        // toastr.error(`${errorMessage}: ${errorDetail}`);
        toastr.error(
            error.response?.data?.error || "❌ Có lỗi xảy ra khi gửi dữ liệu."
        );
    } finally {
        task.isEditing = false;
        globalIsEditting.value = false;
        emit('task-list-editing', false);
        // Emit để component cha xử lý
        emit("update-data");
    }
};

const isOverdue = (planEndDate, status) => {
    if (!planEndDate || !status) return false;

    const overdueStatuses = ["Open", "In Progress", "Feedback", "Reopen"];
    const today = new Date().toISOString().split("T")[0];

    return overdueStatuses.includes(status) && today > planEndDate;
};

const isDelayedStart = (planStartDate, status) => {
    if (!planStartDate || !status) return false;

    const notStartedStatus = "Open";
    const today = new Date().toISOString().split("T")[0];

    return status === notStartedStatus && today > planStartDate;
};

const cancelEdit = (task) => {
    // Hủy Select2 trước khi cập nhật DOM
    destroySelect2();

    Object.assign(task, task.originalData); // Khôi phục dữ liệu gốc
    task.isEditing = false;
    globalIsEditting.value = false;
    emit('task-list-editing', false);
};

const copyTaskLink = (task) => {
    const taskLink = `${window.location.origin}/${props.projectId}/task/${task.id}`;
    navigator.clipboard
        .writeText(taskLink)
        .then(() => {
            toastr.success("Link copied to clipboard!");
        })
        .catch(() => {
            toastr.error("Failed to copy link.");
        });
};

const destroySelect2 = () => {
    $(".assignee-select").select2("destroy");
    $(".priority-select").select2("destroy");
    $(".status-select").select2("destroy");
};

const confirmDelete = async (task) => {
    let warningMessage = "Bạn có chắc chắn muốn xoá task này?";

    if (task.type === TASK_TYPES.EPIC) {
        warningMessage =
            "⚠️ Task này là một Epic! Nếu bạn xoá nó, tất cả task con cũng sẽ bị xoá. Bạn có chắc chắn muốn tiếp tục?";
    }

    const result = await Swal.fire({
        title: warningMessage,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "OK",
        cancelButtonText: "Hủy",
    });

    if (result.isConfirmed) {
        softDelete(task.id);
    }
};

const softDelete = async (taskId) => {
    try {
        const url = `/api/pm/${props.projectId}/tasks/${taskId}/destroy`; // API xoá mềm
        await axios.delete(url);
        toastr.success("Task deleted successfully!");
        // Emit để component cha xử lý
        emit("update-data");
    } catch (error) {
        // Lấy thông tin lỗi từ response
        const errorMessage =
            error.response?.data?.message || "Failed to create task!";
        const errorDetail = error.response?.data?.error || "Unknown error";

        // Hiển thị toastr lỗi với cả message và error detail
        toastr.error(`${errorMessage}: ${errorDetail}`);
    }
};

const openLogWorkModal = (task) => {
    selectedTask.value = task;
    showLogWorkModal.value = true;
};

const handleTaskUpdate = () => {
    emit("update-data");
};

// const filters = reactive({
//     status: "0",
//     priority: "3",
// });

// const exportTasks = () => {
//     const params = new URLSearchParams(filters).toString();
//     window.location.href = `/export-tasks?${params}`;
// };

import tippy from "tippy.js";
// Directive tùy chỉnh để dùng Tippy.js
const vTooltip = {
    mounted(el, binding) {
        tippy(el, {
            content: binding.value,
            placement: "top",
            animation: "scale",
            theme: "light-border",
        });
    },
};

const expandedTasks = ref([]);

const toggleTask = (taskId) => {
    if (expandedTasks.value.includes(taskId)) {
        expandedTasks.value = expandedTasks.value.filter((id) => id !== taskId);
    } else {
        expandedTasks.value.push(taskId);
    }
};
</script>

<style>
.no-spinner {
    appearance: textfield;
}

.no-spinner::-webkit-inner-spin-button,
.no-spinner::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
</style>

<style scoped>
table tbody tr {
    height: 12px !important;
}

table tbody tr td {
    padding: 1px 1px !important;
    vertical-align: middle;
}

table tbody tr:hover {
    background-color: #b3e0f5 !important;
    transition: background-color 0.2s ease-in-out !important;
}
</style>
