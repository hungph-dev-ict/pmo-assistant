<template>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">List Tenant Worklog</h3>
        </div>

        <div class="card-body">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>#</th>
                        <th v-if="isColumnVisible('project-name')" data-column="project-name">
                            Project
                        </th>
                        <th v-if="isColumnVisible('epic_task')" data-column="epic_task">
                            Epic/Task
                        </th>
                        <th v-if="isColumnVisible('log-user')" data-column="log-user">
                            Logged User
                        </th>
                        <th v-if="isColumnVisible('plan-effort')" data-column="priority">
                            Plan Effort
                        </th>
                        <th v-if="isColumnVisible('actual-effort')" data-column="assignee">
                            Actual Effort
                        </th>
                        <th v-if="isColumnVisible('logged-date')" data-column="plan_start_date">
                            Logged Date
                        </th>
                        <th v-if="isColumnVisible('logged-time')" data-column="status">
                            Logged Time
                        </th>
                        <th v-if="isColumnVisible('description')" data-column="status">
                            Description
                        </th>
                        <th v-if="isColumnVisible('action')" class="text-center">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="worklog in visibleWorklogs" :key="worklog.id">
                        <tr class="bg-light">
                            <td>{{ worklog.id }}</td>
                            <td v-if="isColumnVisible('project-name')">
                                {{ worklog.task.project.name }}
                            </td>
                            <td v-if="isColumnVisible('epic_task')">
                                {{ worklog.task.name }}
                            </td>
                            <td v-if="isColumnVisible('log-user')">
                                {{ worklog.task.assignee_user.account }}
                            </td>
                            <td v-if="isColumnVisible('plan-effort')">
                                {{ worklog.task.estimate_effort }}
                            </td>
                            <td v-if="isColumnVisible('actual-effort')">
                                {{ worklog.task.actual_effort }}
                            </td>
                            <td v-if="isColumnVisible('logged-date')">
                                <span v-if="!worklog.isEditing">{{ worklog.log_date }}</span>
                                <div v-else class="input-group date log-date-datepicker" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input"
                                        v-model="worklog.editedLogDate" data-target=".log-date-datepicker" />
                                    <div class="input-group-append" data-target=".log-date-datepicker"
                                        data-toggle="datetimepicker">
                                        <div class="input-group-text">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td v-if="isColumnVisible('logged-time')">
                                <span v-if="!worklog.isEditing">{{ worklog.log_time }}</span>
                                <input v-else type="number" v-model="worklog.editedLogTime" class="form-control" />
                            </td>
                            <td v-if="isColumnVisible('description')">
                                <span v-if="!worklog.isEditing">{{ worklog.description }}</span>
                                <textarea v-else type="text" v-model="worklog.editedDescription"
                                    class="form-control form-control" rows="3"></textarea>
                            </td>
                            <td v-if="isColumnVisible('action')" class="project-actions text-center">
                                <template v-if="!worklog.isEditing">
                                    <a class="btn btn-info btn-sm mr-2" href="#" @click.prevent="editWorklog(worklog)">
                                        <i class="fas fa-pencil-alt"></i> Edit
                                    </a>
                                    <a class="btn btn-danger btn-sm mr-2" href="#" @click="confirmDelete(worklog)">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                </template>

                                <template v-else>
                                    <a class="btn btn-success btn-sm mr-2" href="#"
                                        @click.prevent="updateWorklog(worklog)">
                                        <i class="fas fa-save"></i> Update
                                    </a>
                                    <a class="btn btn-secondary btn-sm" href="#" @click.prevent="cancelEdit(worklog)">
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
import {
    defineProps,
    computed,
    ref,
    nextTick,
    onMounted,
    defineEmits,
} from "vue";
import Swal from "sweetalert2";

const props = defineProps({
    projectId: String,
    filteredWorklogs: Array,
    blankQuery: Boolean,
    visibleColumns: Array,
    listAssignee: Array,
    currentUserId: Number
});

// Tạo danh sách task dưới dạng ref để có thể cập nhật giá trị
const worklogs = ref([]);
const statusList = ref([
    "Not Started",
    "In Progress",
    "Resolved",
    "Feedback",
    "Done",
]);

const selectedTask = ref(null);
const globalIsEditting = ref(false);
const logDate = ref("");
const logTime = ref("");
const logDescription = ref("");

onMounted(() => {
    worklogs.value = props.filteredWorklogs.map((worklog) => ({
        ...worklog,
        isEditing: false,
        editedLogDate: worklog.log_date,
        editedLogTime: worklog.log_time,
        editedDescription: worklog.description
    }));
    globalIsEditting.value = false;
});

const isBlankQuery = computed(() => props.blankQuery ?? true);
const visibleWorklogs = computed(() => {
    let worklogs = props.filteredWorklogs;
    return worklogs;
});

// Kiểm tra xem cột có hiển thị không
const isColumnVisible = (column) => {
    return props.visibleColumns.includes(column);
};

// Hàm bật chế độ edit
const editWorklog = (worklog) => {
    if (globalIsEditting.value) {
        toastr.error("Other worklog edit is in progress. Please cancel it before edit other.");
        return;
    }
    globalIsEditting.value = true;
    worklog.isEditing = true;
    worklog.editedLogDate = worklog.log_date;
    worklog.editedLogTime = worklog.log_time;
    worklog.editedDescription = worklog.description;

    nextTick(initPlugins(worklog));
};

// Hàm kích hoạt select2 và datetimepicker
const initPlugins = (worklog) => {
    nextTick(() => {
        // Khởi động lại datetimepicker cho tất cả các trường ngày tháng
        $(
            ".log-date-datepicker"
        ).datetimepicker({
            format: "YYYY-MM-DD",
            icons: { time: "fa fa-clock", date: "fa fa-calendar" },
        });

        $(".log-date-datepicker").on("change.datetimepicker", function (e) {
            let newPlanStartDate = e.date ? e.date.format("YYYY-MM-DD") : (e.target.value ? e.target.value : "");
            worklog.editedLogDate = newPlanStartDate;
        });
    });
};

// Emit sự kiện update để thông báo lên component cha
const emit = defineEmits(["update-worklog"]);

const updateWorklog = async (worklog) => {
    // Hủy Select2 trước khi cập nhật giao diện
    destroySelect2();

    // Tạo một object mới với dữ liệu đã chỉnh sửa
    const updatedWorklog = {
        ...worklog, // Giữ lại các thuộc tính cũ
        log_date: worklog.editedLogDate,
        log_time: worklog.editedLogTime,
        description: worklog.editedDescription,
        isEditing: false,
    };

    try {
        // Gọi API cập nhật dữ liệu
        const url = `/api/worklog/${worklog.id}/update`;
        await axios.put(url, {
            log_date: updatedWorklog.log_date,
            log_time: updatedWorklog.log_time,
            description: updatedWorklog.description,
        });

        toastr.success("Updated worklog successfully!");
    } catch (error) {
        // Lấy thông tin lỗi từ response
        const errorMessage =
            error.response?.data?.message || "Failed to create task!";
        const errorDetail = error.response?.data?.error || "Unknown error";

        // Hiển thị toastr lỗi với cả message và error detail
        toastr.error(`${errorMessage}: ${errorDetail}`);
    }
    worklog.isEditing = false;
    globalIsEditting.value = false;
    // Emit để component cha xử lý
    emit("update-worklog");
};

const cancelEdit = (worklog) => {
    // Hủy Select2 trước khi cập nhật DOM
    destroySelect2();

    Object.assign(worklog, worklog.originalData); // Khôi phục dữ liệu gốc
    worklog.isEditing = false;
    globalIsEditting.value = false;
};

const destroySelect2 = () => {
    $(".assignee-select").select2("destroy");
    $(".priority-select").select2("destroy");
    $(".status-select").select2("destroy");
};

const confirmDelete = async (worklog) => {
    let warningMessage = "Bạn có chắc chắn muốn xoá worklog này?";
    let textMessage = "Thao tác này không thể hoàn tác."

    const result = await Swal.fire({
        title: warningMessage,
        text: textMessage,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "OK",
        cancelButtonText: "Hủy",
    });

    if (result.isConfirmed) {
        softDelete(worklog.id);
    }
};

const softDelete = async (worklogId) => {
    try {
        const url = `/api/worklog/${worklogId}/destroy`; // API xoá mềm
        await axios.delete(url);
        toastr.success("Worklog deleted successfully!");
        // Emit để component cha xử lý
        emit("update-worklog");
    } catch (error) {
        // Lấy thông tin lỗi từ response
        const errorMessage =
            error.response?.data?.message || "Failed to create worklog!";
        const errorDetail = error.response?.data?.error || "Unknown error";

        // Hiển thị toastr lỗi với cả message và error detail
        toastr.error(`${errorMessage}: ${errorDetail}`);
    }
};
</script>
