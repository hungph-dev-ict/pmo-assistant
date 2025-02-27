<template>
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">List Task</h3>
            <div class="card-tools">
                <!-- <button @click="exportTasks" class="btn btn-info btn-sm mr-2">
                    Export to CSV
                </button> -->
                <button
                    type="button"
                    class="btn btn-tool"
                    data-card-widget="collapse"
                    title="Collapse"
                >
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>

        <div class="card-body">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th style="width: 2%">#</th>
                        <th
                            v-if="isColumnVisible('epic_task')"
                            style="width: 26%"
                        >
                            Epic/Task
                        </th>
                        <th
                            v-if="isColumnVisible('priority')"
                            class="text-center"
                            style="width: 6%"
                        >
                            Priority
                        </th>
                        <th
                            v-if="isColumnVisible('assignee')"
                            style="width: 6%"
                        >
                            Assignee
                        </th>
                        <th
                            v-if="isColumnVisible('plan_start_date')"
                            style="width: 8%"
                        >
                            Plan Start Date
                        </th>
                        <th
                            v-if="isColumnVisible('plan_end_date')"
                            style="width: 8%"
                        >
                            Plan End Date
                        </th>
                        <th
                            v-if="isColumnVisible('actual_start_date')"
                            style="width: 8%"
                        >
                            Actual Start Date
                        </th>
                        <th
                            v-if="isColumnVisible('actual_end_date')"
                            style="width: 8%"
                        >
                            Actual End Date
                        </th>
                        <th
                            v-if="isColumnVisible('plan-effort')"
                            style="width: 3%"
                        >
                            Plan Effort
                        </th>
                        <th
                            v-if="isColumnVisible('actual-effort')"
                            style="width: 3%"
                        >
                            Actual Effort
                        </th>
                        <th v-if="isColumnVisible('status')" style="width: 6%">
                            Status
                        </th>
                        <th
                            class="text-center"
                            v-if="isColumnVisible('action')"
                            style="width: 16%"
                        >
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="task in visibleTasks" :key="task.id">
                        <tr class="bg-light">
                            <td>{{ task.id }}</td>
                            <td v-if="isColumnVisible('epic_task')">
                                <span
                                    v-if="
                                        task.type === 'task' &&
                                        isBlankQuery &&
                                        !task.isEditing
                                    "
                                >
                                    └
                                </span>

                                <a
                                    v-if="
                                        !task.isEditing ||
                                        (task.isEditing && hasPermissionStaff)
                                    "
                                    :href="
                                        props.hasPermissionStaff
                                            ? `/staff/${props.projectId}/task/${task.id}`
                                            : `/pm/${props.projectId}/task/${task.id}`
                                    "
                                    class="text-blue-500 hover:underline"
                                >
                                    {{ task.name }}
                                </a>

                                <input
                                    v-else
                                    type="text"
                                    v-model="task.editedName"
                                    class="form-control"
                                />
                            </td>
                            <td
                                v-if="isColumnVisible('priority')"
                                class="text-center"
                            >
                                <PriorityIcon
                                    v-if="
                                        !task.isEditing ||
                                        (task.isEditing && hasPermissionStaff)
                                    "
                                    :priority="task.priority"
                                />
                                <select
                                    v-else
                                    class="form-control priority-select"
                                    v-model="task.editedPriority"
                                >
                                    <option :key="9" :value="'Blocker'">
                                        Blocker
                                    </option>
                                    <option :key="8" :value="'Critical'">
                                        Critical
                                    </option>
                                    <option :key="7" :value="'Highest'">
                                        Highest
                                    </option>

                                    <option :key="6" :value="'Higher'">
                                        Higher
                                    </option>
                                    <option :key="5" :value="'High'">
                                        High
                                    </option>
                                    <option :key="4" :value="'Minor'">
                                        Minor
                                    </option>
                                    <option :key="3" :value="'Low'">Low</option>

                                    <option :key="2" :value="'Lower'">
                                        Lower
                                    </option>
                                    <option :key="1" :value="'Lowest'">
                                        Lowest
                                    </option>
                                    <option :key="0" :value="'Trivial'">
                                        Trivial
                                    </option>
                                </select>
                            </td>
                            <td v-if="isColumnVisible('assignee')">
                                <span
                                    v-if="
                                        !task.isEditing ||
                                        (task.isEditing && hasPermissionStaff)
                                    "
                                >
                                    <strong
                                        v-if="
                                            task.assignee?.account ==
                                            currentUserAccount
                                        "
                                    >
                                        {{ task.assignee?.account || "N/A" }}
                                    </strong>
                                    <template v-else>
                                        {{ task.assignee?.account || "N/A" }}
                                    </template>
                                </span>

                                <select
                                    v-else
                                    class="form-control assignee-select"
                                    v-model="task.editedAssignee"
                                >
                                    <option
                                        v-for="user in listAssignee"
                                        :key="user.id"
                                        :value="user.id"
                                    >
                                        {{ user.account }}
                                    </option>
                                </select>
                            </td>

                            <td v-if="isColumnVisible('plan_start_date')">
                                <span
                                    v-if="
                                        !task.isEditing ||
                                        (task.isEditing && hasPermissionStaff)
                                    "
                                    ><span
                                        :style="{
                                            color: isDelayedStart(
                                                task.plan_start_date,
                                                task.status
                                            )
                                                ? 'red'
                                                : 'inherit',
                                        }"
                                    >
                                        {{ task.plan_start_date }}
                                        <span
                                            v-if="
                                                isDelayedStart(
                                                    task.plan_start_date,
                                                    task.status
                                                )
                                            "
                                            >🔥</span
                                        >
                                    </span></span
                                >
                                <div
                                    v-else
                                    class="input-group date plan-start-datepicker"
                                    data-target-input="nearest"
                                >
                                    <input
                                        type="text"
                                        class="form-control datetimepicker-input"
                                        v-model="task.editedPlanStartDate"
                                        data-target=".plan-start-datepicker"
                                    />
                                    <div
                                        class="input-group-append"
                                        data-target=".plan-start-datepicker"
                                        data-toggle="datetimepicker"
                                    >
                                        <div class="input-group-text">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td v-if="isColumnVisible('plan_end_date')">
                                <span
                                    v-if="
                                        !task.isEditing ||
                                        (task.isEditing && hasPermissionStaff)
                                    "
                                >
                                    <span
                                        :style="{
                                            color: isOverdue(
                                                task.plan_end_date,
                                                task.status
                                            )
                                                ? 'red'
                                                : 'inherit',
                                        }"
                                    >
                                        {{ task.plan_end_date }}
                                        <span
                                            v-if="
                                                isOverdue(
                                                    task.plan_end_date,
                                                    task.status
                                                )
                                            "
                                            >🔥</span
                                        >
                                    </span>
                                </span>
                                <div
                                    v-else
                                    class="input-group date plan-end-datepicker"
                                    data-target-input="nearest"
                                >
                                    <input
                                        type="text"
                                        class="form-control datetimepicker-input"
                                        v-model="task.editedPlanEndDate"
                                        data-target=".plan-end-datepicker"
                                    />
                                    <div
                                        class="input-group-append"
                                        data-target=".plan-end-datepicker"
                                        data-toggle="datetimepicker"
                                    >
                                        <div class="input-group-text">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td v-if="isColumnVisible('actual_start_date')">
                                <span
                                    v-if="
                                        !task.isEditing ||
                                        (task.isEditing && hasPermissionStaff)
                                    "
                                    >{{ task.actual_start_date }}</span
                                >
                                <div
                                    v-else
                                    class="input-group date actual-start-datepicker"
                                    data-target-input="nearest"
                                >
                                    <input
                                        type="text"
                                        class="form-control datetimepicker-input"
                                        v-model="task.editedActualStartDate"
                                        data-target=".actual-start-datepicker"
                                    />
                                    <div
                                        class="input-group-append"
                                        data-target=".actual-start-datepicker"
                                        data-toggle="datetimepicker"
                                    >
                                        <div class="input-group-text">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td v-if="isColumnVisible('actual_end_date')">
                                <span
                                    v-if="
                                        !task.isEditing ||
                                        (task.isEditing && hasPermissionStaff)
                                    "
                                    >{{ task.actual_end_date }}</span
                                >
                                <div
                                    v-else
                                    class="input-group date actual-end-datepicker"
                                    data-target-input="nearest"
                                >
                                    <input
                                        type="text"
                                        class="form-control datetimepicker-input"
                                        v-model="task.editedActualEndDate"
                                        data-target=".actual-end-datepicker"
                                    />
                                    <div
                                        class="input-group-append"
                                        data-target=".actual-end-datepicker"
                                        data-toggle="datetimepicker"
                                    >
                                        <div class="input-group-text">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td v-if="isColumnVisible('plan-effort')">
                                <span
                                    v-if="
                                        !task.isEditing ||
                                        (task.isEditing && hasPermissionStaff)
                                    "
                                    >{{ task.estimate_effort }}</span
                                >
                                <input
                                    v-else
                                    type="number"
                                    v-model="task.editedPlanEffort"
                                    class="form-control no-spinner"
                                />
                            </td>

                            <td v-if="isColumnVisible('actual-effort')">
                                {{ task.actual_effort
                                }}<i
                                    v-if="
                                        task.actual_effort >
                                        task.estimate_effort
                                    "
                                    class="fas fa-exclamation-triangle text-danger ml-2"
                                    title="Actual effort exceeds plan effort"
                                ></i>
                            </td>

                            <td v-if="isColumnVisible('status')">
                                <span
                                    v-if="!task.isEditing"
                                    :class="statusClass(task.status)"
                                    >{{ task.status }}</span
                                >
                                <select
                                    v-else
                                    class="form-control status-select"
                                    v-model="task.editedStatus"
                                >
                                    <option
                                        v-for="status in statusList"
                                        :key="status"
                                        :value="status"
                                    >
                                        {{ status }}
                                    </option>
                                </select>
                            </td>
                            <td
                                v-if="isColumnVisible('action')"
                                class="text-center"
                            >
                                <template v-if="!task.isEditing">
                                    <a
                                        class="btn btn-info btn-sm mr-2"
                                        href="#"
                                        @click.prevent="editTask(task)"
                                    >
                                        <i class="fas fa-pencil-alt"></i> Edit
                                    </a>
                                    <a
                                        v-if="!hasPermissionStaff"
                                        class="btn btn-danger btn-sm mr-2"
                                        href="#"
                                        @click="confirmDelete(task)"
                                    >
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                    <a
                                        class="btn btn-primary btn-sm"
                                        href="#"
                                        @click.prevent="openLogWorkModal(task)"
                                    >
                                        <i class="fas fa-clock"></i> Log Work
                                    </a>
                                </template>

                                <template v-else>
                                    <a
                                        class="btn btn-success btn-sm mr-2"
                                        href="#"
                                        @click.prevent="updateTask(task)"
                                    >
                                        <i class="fas fa-save"></i> Update
                                    </a>
                                    <a
                                        class="btn btn-secondary btn-sm"
                                        href="#"
                                        @click.prevent="cancelEdit(task)"
                                    >
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
    <template v-if="showLogWorkModal">
        <div
            class="modal fade show d-block"
            tabindex="-1"
            :aria-hidden="!showLogWorkModal"
        >
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Log Work</h5>
                        <button
                            type="button"
                            class="close"
                            @click="showLogWorkModal = false"
                        >
                            &times;
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Title -->
                        <div class="form-group">
                            <label>Title</label>
                            <input
                                type="text"
                                class="form-control"
                                :value="selectedTask.name"
                                disabled
                            />
                        </div>

                        <!-- Assignee -->
                        <div class="form-group">
                            <label>Assignee</label>
                            <input
                                type="text"
                                class="form-control"
                                :value="selectedTask.assignee?.account || 'N/A'"
                                disabled
                            />
                        </div>

                        <!-- Estimate Effort -->
                        <div class="form-group">
                            <label>Plan Effort</label>
                            <input
                                type="number"
                                class="form-control"
                                :value="selectedTask.estimate_effort"
                                disabled
                            />
                        </div>

                        <!-- Actual Effort -->
                        <div class="form-group position-relative">
                            <label>Actual Effort</label
                            ><i
                                v-if="
                                    selectedTask.actual_effort >
                                    selectedTask.estimate_effort
                                "
                                class="fas fa-exclamation-triangle text-danger ml-2"
                                title="Actual effort exceeds plan effort"
                            ></i>
                            <div class="d-flex align-items-center">
                                <input
                                    type="number"
                                    class="form-control"
                                    :value="selectedTask.actual_effort"
                                    disabled
                                />
                            </div>
                        </div>

                        <!-- Log Date (Datepicker) -->
                        <div class="form-group">
                            <label for="logDate"
                                >Log Date<span style="color: red"
                                    >*</span
                                ></label
                            >
                            <div
                                class="input-group date"
                                id="logDatePicker"
                                data-target-input="nearest"
                            >
                                <input
                                    type="text"
                                    id="logTime"
                                    v-model="logDate"
                                    class="form-control datetimepicker-input"
                                    data-target="#logDatePicker"
                                />
                                <div
                                    class="input-group-append"
                                    data-target="#logDatePicker"
                                    data-toggle="datetimepicker"
                                >
                                    <div class="input-group-text">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Log Time -->
                        <div class="form-group">
                            <label
                                >Log Time (Hours)<span style="color: red"
                                    >*</span
                                ></label
                            >
                            <input
                                type="number"
                                class="form-control"
                                v-model="logTime"
                                step="0.1"
                                min="0"
                            />
                        </div>

                        <!-- Description -->
                        <div class="form-group">
                            <label>Description</label>
                            <textarea
                                class="form-control"
                                v-model="logDescription"
                                rows="3"
                            ></textarea>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button
                            class="btn btn-secondary"
                            @click="showLogWorkModal = false"
                        >
                            Close
                        </button>
                        <button
                            class="btn btn-primary"
                            @click="submitLogWork(selectedTask.id)"
                        >
                            <span v-if="isLoading">
                                <i class="fas fa-spinner fa-spin"></i> Đang xử
                                lý... </span
                            ><span v-else> Save </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>
</template>

<script setup>
import { computed, ref, nextTick, onMounted, reactive } from "vue";
import Swal from "sweetalert2";
import PriorityIcon from "./PriorityIcon.vue";

const props = defineProps({
    projectId: String,
    filteredTasks: Array,
    blankQuery: Boolean,
    visibleColumns: Array,
    listAssignee: Array,
    hasPermissionStaff: Boolean,
    currentUserId: Number,
    currentUserAccount: String,
});

// Tạo danh sách task dưới dạng ref để có thể cập nhật giá trị
const tasks = ref([]);
const statusList = ref([
    "Not Started",
    "In Progress",
    "Resolved",
    "Feedback",
    "Done",
    "Reopen",
]);

const selectedTask = ref(null);
const showLogWorkModal = ref(false);
const logDate = ref("");
const logTime = ref("");
const logDescription = ref("");
const globalIsEditting = ref(false);
const isLoading = ref(false);

onMounted(() => {
    tasks.value = props.filteredTasks.map((task) => ({
        ...task,
        isEditing: false,
        editedName: task.name,
    }));
    globalIsEditting.value = false;
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
    task.editedAssignee = task.assignee?.id;
    task.editedStatus = task.status;
    task.editedPlanStartDate = task.plan_start_date;
    task.editedPlanEndDate = task.plan_end_date;
    task.editedActualStartDate = task.actual_start_date;
    task.editedActualEndDate = task.actual_end_date;
    task.editedPlanEffort = task.estimate_effort;

    nextTick(initPlugins(task));
};

// Hàm kích hoạt select2 và datetimepicker
const initPlugins = (task) => {
    nextTick(() => {
        // Khởi động lại select2
        $(".assignee-select")
            .select2()
            .on("change", function (e) {
                task.editedAssignee = $(this).val();
            });
        $(".priority-select")
            .select2()
            .on("change", function (e) {
                task.editedPriority = $(this).val();
            });
        $(".status-select")
            .select2()
            .on("change", function (e) {
                task.editedStatus = $(this).val();
            });

        // Khởi động lại datetimepicker cho tất cả các trường ngày tháng
        $(
            ".plan-start-datepicker, .plan-end-datepicker, .actual-start-datepicker, .actual-end-datepicker"
        ).datetimepicker({
            format: "YYYY-MM-DD",
            icons: { time: "fa fa-clock", date: "fa fa-calendar" },
        });

        $(".plan-start-datepicker").on("change.datetimepicker", function (e) {
            let newPlanStartDate = e.date
                ? e.date.format("YYYY-MM-DD")
                : e.target.value
                ? e.target.value
                : "";
            task.editedPlanStartDate = newPlanStartDate;
        });

        $(".plan-end-datepicker").on("change.datetimepicker", function (e) {
            let newPlanEndDate = e.date
                ? e.date.format("YYYY-MM-DD")
                : e.target.value
                ? e.target.value
                : "";
            task.editedPlanEndDate = newPlanEndDate;
        });

        $(".actual-start-datepicker").on("change.datetimepicker", function (e) {
            let newActualStartDate = e.date
                ? e.date.format("YYYY-MM-DD")
                : e.target.value
                ? e.target.value
                : "";
            task.editedActualStartDate = newActualStartDate;
        });

        $(".actual-end-datepicker").on("change.datetimepicker", function (e) {
            let newActualEndDate = e.date
                ? e.date.format("YYYY-MM-DD")
                : e.target.value
                ? e.target.value
                : "";
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
        actual_end_date: task.editedlanActualEndDate,
        estimate_effort: task.editedPlanEffort,
        isEditing: false,
    };

    const priorityMapping = {
        Trivial: 0,
        Lowest: 1,
        Lower: 2,
        Low: 3,
        Minor: 4,
        High: 5,
        Higher: 6,
        Highest: 7,
        Critical: 8,
        Blocker: 9,
    };

    const statusMapping = {
        "Not Started": 0,
        "In Progress": 1,
        Resolved: 2,
        Feedback: 3,
        Done: 4,
        Reopen: 5,
    };

    try {
        // Gọi API cập nhật dữ liệu
        const url = props.hasPermissionStaff
            ? `/api/staff/${props.projectId}/tasks/${task.id}/update`
            : `/api/pm/${props.projectId}/tasks/${task.id}/update`;
        await axios.put(url, {
            name: updatedTask.name,
            priority:
                priorityMapping[updatedTask.priority] ?? updatedTask.priority, // Map priority
            assignee: updatedTask.assignee,
            status: statusMapping[updatedTask.status] ?? updatedTask.status, // Map status            plan_start_date: task.plan_start_date,
            plan_start_date: updatedTask.plan_start_date,
            plan_end_date: updatedTask.plan_end_date,
            actual_start_date: updatedTask.actual_start_date,
            actual_end_date: updatedTask.actual_end_date,
            estimate_effort: updatedTask.estimate_effort,
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
    }
    task.isEditing = false;
    globalIsEditting.value = false;
    // Emit để component cha xử lý
    emit("update-task");
};

const isOverdue = (planEndDate, status) => {
    if (!planEndDate || !status) return false;

    const overdueStatuses = [
        "Not Started",
        "In Progress",
        "Feedback",
        "Reopen",
    ];
    const today = new Date().toISOString().split("T")[0];

    return overdueStatuses.includes(status) && today > planEndDate;
};

const isDelayedStart = (planStartDate, status) => {
    if (!planStartDate || !status) return false;

    const notStartedStatus = "Not Started";
    const today = new Date().toISOString().split("T")[0];

    return status === notStartedStatus && today > planStartDate;
};

const cancelEdit = (task) => {
    // Hủy Select2 trước khi cập nhật DOM
    destroySelect2();

    Object.assign(task, task.originalData); // Khôi phục dữ liệu gốc
    task.isEditing = false;
    globalIsEditting.value = false;
};

const destroySelect2 = () => {
    $(".assignee-select").select2("destroy");
    $(".priority-select").select2("destroy");
    $(".status-select").select2("destroy");
};

const confirmDelete = async (task) => {
    let warningMessage = "Bạn có chắc chắn muốn xoá task này?";

    if (task.type === "epic") {
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
        emit("update-task");
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
    logTime.value = "";
    logDate.value = "";
    logDescription.value = "";

    nextTick(() => {
        $("#logDatePicker").datetimepicker({
            format: "YYYY-MM-DD",
            useCurrent: false,
            allowInputToggle: true,
        });

        $("#logDatePicker").on("change.datetimepicker", (e) => {
            logDate.value = e.date
                ? e.date.format("YYYY-MM-DD")
                : e.target.value
                ? e.target.value
                : "";
        });
    });
};

const submitLogWork = async (taskId) => {
    if (isLoading.value) return;
    isLoading.value = true;
    if (!logDate.value) {
        toastr.error("Please enter a valid log date.");
        isLoading.value = false;
        return;
    }

    if (!logTime.value || logTime.value <= 0) {
        toastr.error("Please enter a valid log time.");
        isLoading.value = false;
        return;
    }

    try {
        const payload = {
            task_id: taskId,
            log_date: logDate.value,
            log_time: logTime.value,
            description: logDescription.value,
        };

        await axios.post(
            `/api/${props.projectId}/tasks/${taskId}/worklog`,
            payload
        );
        toastr.success("Work logged successfully!");

        // Chỉ reset nếu request thành công
        logTime.value = "";
        logDate.value = "";
        logDescription.value = "";

        // Đóng modal
        showLogWorkModal.value = false;
        isLoading.value = false;
    } catch (error) {
        console.log(error);
        // Lấy thông tin lỗi từ response
        const errorMessage =
            error.response?.data?.message || "Failed to create task!";
        const errorDetail = error.response?.data?.error || "Unknown error";

        // Hiển thị toastr lỗi với cả message và error detail
        toastr.error(`${errorMessage}: ${errorDetail}`);
        isLoading.value = false;
    }

    // Đóng modal
    showLogWorkModal.value = false;
    logTime.value = "";
    logDate.value = "";
    logDescription.value = "";
    isLoading.value = false;

    emit("update-task");
};

const statusClass = (status) => {
    switch (status) {
        case "Not Started":
            return "badge badge-secondary"; // Màu xám
        case "In Progress":
            return "badge badge-primary"; // Màu xanh dương
        case "Resolved":
            return "badge badge-success"; // Màu xanh lá
        case "Feedback":
            return "badge badge-warning"; // Màu vàng
        case "Done":
            return "badge badge-dark"; // Màu đen hoặc tím đậm
        case "Reopen":
            return "badge badge-secondary"; // Màu xám
        default:
            return "badge badge-light"; // Màu nhạt cho trạng thái không xác định
    }
};

const filters = reactive({
    status: "0",
    priority: "3",
});

const exportTasks = () => {
    const params = new URLSearchParams(filters).toString();
    window.location.href = `/export-tasks?${params}`;
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
