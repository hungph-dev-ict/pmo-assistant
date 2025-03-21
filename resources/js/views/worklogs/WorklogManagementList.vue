<template>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title" v-if="isTenantRoute">List Tenant Worklog</h3>
            <h3 class="card-title" v-if="isPMRoute">List Project Worklog</h3>
            <div class="card-tools">
                <button
                    type="button"
                    class="btn btn-success btn-sm"
                    @click="exportToExcel"
                >
                    <i class="fas fa-file-excel"></i> Export to Excel
                </button>
            </div>
        </div>

        <div class="card-body">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th
                            v-if="isColumnVisible('project-name')"
                            style="width: 12%"
                        >
                            Project
                        </th>
                        <th
                            v-if="isColumnVisible('epic_task')"
                            style="width: 22%"
                        >
                            Epic/Task
                        </th>
                        <th
                            v-if="isColumnVisible('assignee')"
                            style="width: 10%"
                        >
                            Assignee
                        </th>
                        <th
                            v-if="isColumnVisible('plan-effort')"
                            style="width: 5%"
                        >
                            Plan Effort
                        </th>
                        <th
                            v-if="isColumnVisible('actual-effort')"
                            style="width: 5%"
                        >
                            Actual Effort
                        </th>
                        <th
                            v-if="isColumnVisible('logged-user')"
                            style="width: 10%"
                        >
                            Logged User
                        </th>
                        <th
                            v-if="isColumnVisible('logged-date')"
                            style="width: 8%"
                        >
                            Worklog Date
                        </th>
                        <th
                            v-if="isColumnVisible('logged-time')"
                            style="width: 5%"
                        >
                            Worklog Time
                        </th>
                        <th
                            v-if="isColumnVisible('created-at')"
                            style="width: 15%"
                        >
                            Logged Datetime
                        </th>
                        <th
                            v-if="isColumnVisible('description')"
                            style="width: 23%"
                        >
                            Description
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <template
                        v-for="worklog in visibleWorklogs"
                        :key="worklog.id"
                    >
                        <tr class="bg-light">
                            <td v-if="isColumnVisible('project-name')">
                                <a
                                    v-if="worklog.task?.project?.id"
                                    :href="`/pm/${worklog.task.project.id}/task`"
                                    class="text-blue-500 hover:underline"
                                >
                                    {{ worklog.task?.project?.name || 'N/A' }}
                                </a>
                                <span v-else>{{ worklog.task?.project?.name || 'N/A' }}</span>
                            </td>
                            <td v-if="isColumnVisible('epic_task')">
                                <a
                                    v-if="worklog.task?.project?.id && worklog.task?.id"
                                    :href="`/${worklog.task.project.id}/task/${worklog.task.id}`"
                                    class="text-blue-500 hover:underline"
                                >
                                    {{ worklog.task?.name || 'N/A' }}
                                </a>
                                <span v-else>{{ worklog.task?.name || 'N/A' }}</span>
                            </td>
                            <td v-if="isColumnVisible('assignee')">
                                {{ worklog.task?.assignee_user?.account || 'N/A' }}
                            </td>
                            <td v-if="isColumnVisible('plan-effort')">
                                {{ worklog.task?.plan_effort || 'N/A' }}
                            </td>
                            <td v-if="isColumnVisible('actual-effort')">
                                {{ worklog.task?.actual_effort || 'N/A' }}
                            </td>
                            <td v-if="isColumnVisible('logged-user')">
                                {{ worklog.user?.account || 'N/A' }}
                            </td>
                            <td v-if="isColumnVisible('logged-date')">
                                <span v-if="!worklog.isEditing">{{
                                    worklog.log_date
                                }}</span>
                                <div
                                    v-else
                                    class="input-group date log-date-datepicker"
                                    data-target-input="nearest"
                                >
                                    <input
                                        type="text"
                                        class="form-control datetimepicker-input"
                                        v-model="worklog.editedLogDate"
                                        data-target=".log-date-datepicker"
                                    />
                                    <div
                                        class="input-group-append"
                                        data-target=".log-date-datepicker"
                                        data-toggle="datetimepicker"
                                    >
                                        <div class="input-group-text">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td v-if="isColumnVisible('logged-time')">
                                <span v-if="!worklog.isEditing">{{
                                    worklog.log_time
                                }}</span>
                                <input
                                    v-else
                                    type="number"
                                    v-model="worklog.editedLogTime"
                                    class="form-control"
                                />
                            </td>
                            <td v-if="isColumnVisible('created-at')">
                                {{ worklog.created_at ? moment(worklog.created_at).format('YYYY-MM-DD HH:mm:ss') : 'N/A' }}
                            </td>
                            <td v-if="isColumnVisible('description')">
                                <span v-if="!worklog.isEditing">{{
                                    worklog.description
                                }}</span>
                                <textarea
                                    v-else
                                    type="text"
                                    v-model="worklog.editedDescription"
                                    class="form-control form-control"
                                    rows="3"
                                ></textarea>
                            </td>
                            <td
                                v-if="isColumnVisible('action')"
                                class="text-center"
                            >
                                <template v-if="!worklog.isEditing">
                                    <a
                                        class="btn btn-info btn-sm mr-2"
                                        href="#"
                                        @click.prevent="editWorklog(worklog)"
                                    >
                                        <i class="fas fa-pencil-alt"></i> Edit
                                    </a>
                                    <a
                                        class="btn btn-danger btn-sm mr-2"
                                        href="#"
                                        @click="confirmDelete(worklog)"
                                    >
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                </template>

                                <template v-else>
                                    <a
                                        class="btn btn-success btn-sm mr-2"
                                        href="#"
                                        @click.prevent="updateWorklog(worklog)"
                                    >
                                        <i class="fas fa-save"></i> Update
                                    </a>
                                    <a
                                        class="btn btn-secondary btn-sm"
                                        href="#"
                                        @click.prevent="cancelEdit(worklog)"
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
</template>

<script setup>
import { computed, ref, nextTick, onMounted } from "vue";
import Swal from "sweetalert2";
import * as XLSX from 'xlsx';
import moment from 'moment';

const props = defineProps({
    projectId: String,
    filteredWorklogs: Array,
    blankQuery: Boolean,
    visibleColumns: Array,
    listAssignee: Array,
    currentUserId: Number,
});

// Tạo danh sách task dưới dạng ref để có thể cập nhật giá trị
const worklogs = ref([]);

const globalIsEditting = ref(false);

const currentPath = computed(() => window.location.pathname);

const isPMRoute = computed(() => currentPath.value.includes("/pm/"));
const isTenantRoute = computed(() => currentPath.value.includes("/tenant/"));

onMounted(() => {
    worklogs.value = props.filteredWorklogs.map((worklog) => ({
        ...worklog,
        isEditing: false,
        editedLogDate: worklog.log_date,
        editedLogTime: worklog.log_time,
        editedDescription: worklog.description,
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
        toastr.error(
            "Other worklog edit is in progress. Please cancel it before edit other."
        );
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
        $(".log-date-datepicker").datetimepicker({
            format: "YYYY-MM-DD",
            buttons: {
                showToday: true, // Hiển thị nút "Today"
                showClear: true, // (Tùy chọn) Hiển thị nút "Clear"
                showClose: true, // (Tùy chọn) Hiển thị nút "Close"
            },
            icons: {
                today: "fa fa-calendar-day", // Sử dụng FontAwesome icon
                clear: "fa fa-trash",
                close: "fa fa-times",
            },
        });

        $(".log-date-datepicker").on("change.datetimepicker", function (e) {
            let newPlanStartDate = e.date
                ? e.date.format("YYYY-MM-DD")
                : e.target.value
                ? e.target.value
                : "";
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
    let textMessage = "Thao tác này không thể hoàn tác.";

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

// Function to export table to Excel
const exportToExcel = () => {
    try {
        // Check if there's data to export
        if (!props.filteredWorklogs || props.filteredWorklogs.length === 0) {
            toastr.warning('No data available to export');
            return;
        }

        // Create workbook
        const wb = XLSX.utils.book_new();
        
        // Prepare data for export
        const exportData = props.filteredWorklogs.map(worklog => {
            const row = {};
            
            if (isColumnVisible('project-name')) {
                row['Project'] = worklog.task?.project?.name || 'N/A';
            }
            if (isColumnVisible('epic_task')) {
                row['Epic/Task'] = worklog.task?.name || 'N/A';
            }
            if (isColumnVisible('assignee')) {
                row['Assignee'] = worklog.task?.assignee_user?.account || 'N/A';
            }
            if (isColumnVisible('plan-effort')) {
                row['Plan Effort'] = worklog.task?.plan_effort || 'N/A';
            }
            if (isColumnVisible('actual-effort')) {
                row['Actual Effort'] = worklog.task?.actual_effort || 'N/A';
            }
            if (isColumnVisible('logged-user')) {
                row['Logged User'] = worklog.user?.account || 'N/A';
            }
            if (isColumnVisible('logged-date')) {
                row['Logged Date'] = worklog.log_date || 'N/A';
            }
            if (isColumnVisible('logged-time')) {
                row['Logged Time'] = worklog.log_time || 'N/A';
            }
            if (isColumnVisible('created-at')) {
                row['Logged Datetime'] = worklog.created_at ? moment(worklog.created_at).format('YYYY-MM-DD HH:mm:ss') : 'N/A';
            }
            if (isColumnVisible('description')) {
                row['Description'] = worklog.description || 'N/A';
            }
            
            return row;
        });

        // Create worksheet
        const ws = XLSX.utils.json_to_sheet(exportData);
        
        // Set column widths
        const colWidths = [
            { wch: 20 }, // Project
            { wch: 40 }, // Epic/Task
            { wch: 15 }, // Assignee
            { wch: 10 }, // Plan Effort
            { wch: 10 }, // Actual Effort
            { wch: 15 }, // Logged User
            { wch: 12 }, // Logged Date
            { wch: 10 }, // Logged Time
            { wch: 15 }, // Logged Datetime
            { wch: 50 }, // Description
        ];
        ws['!cols'] = colWidths;
        
        // Add worksheet to workbook
        XLSX.utils.book_append_sheet(wb, ws, "Worklogs");
        
        // Generate Excel file
        const excelBuffer = XLSX.write(wb, { bookType: 'xlsx', type: 'array' });
        const blob = new Blob([excelBuffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
        
        // Create download link
        const url = window.URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', `worklogs_${new Date().toISOString().split('T')[0]}.xlsx`);
        
        // Trigger download
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        window.URL.revokeObjectURL(url);
        
        // Show success message
        toastr.success('Worklogs exported successfully!');
    } catch (error) {
        console.error('Error exporting to Excel:', error);
        toastr.error('Failed to export worklogs. Please try again.');
    }
};
</script>
