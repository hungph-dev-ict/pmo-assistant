<template>
    <div>
        <div class="row">
            <div class="col-md-8">
                <!-- Box Comment -->
                <div class="card card-widget">
                    <div class="card-header">
                        <h3
                            v-if="!isEditingInfo || !hasPermissionPm"
                            class="card-title"
                            style="
                                display: inline-flex;
                                align-items: center;
                                gap: 5px;
                            "
                        >
                            <PriorityIcon :priority="task.priority" />
                            <strong v-if="task.type == 1">
                                {{ task.parent.name }}
                            </strong>
                            {{ task.name }}
                        </h3>
                        <template v-else>
                            <label>Title</label>
                            <input
                                type="text"
                                v-model="editTask.name"
                                class="form-control"
                                :disabled="isLoading"
                            />
                        </template>

                        <span
                            v-if="!isEditingInfo || !hasPermissionPm"
                            :class="statusClass(task.status)"
                            >{{ task.task_status?.value1 }}</span
                        >
                        <!-- /.user-block -->
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <p
                            v-if="!isEditingInfo || !hasPermissionPm"
                            class="text-muted description"
                        >
                            {{ task.description }}
                        </p>
                        <template v-else>
                            <label>Description</label>
                            <textarea
                                v-model="editTask.description"
                                rows="10"
                                class="form-control"
                                :disabled="isLoading"
                            ></textarea>
                        </template>

                        <br />
                        <div class="text-muted">
                            <p class="text-sm">
                                Components
                                <b class="d-block">{{
                                    task.components ?? "-"
                                }}</b>
                            </p>
                        </div>

                        <br />
                        <div class="text-muted">
                            <p
                                v-if="!isEditingInfo"
                                class="text-sm description"
                            >
                                Memo
                                <b class="d-block">{{ task.memo ?? "-" }}</b>
                            </p>
                            <template v-else>
                                <label>Memo</label>
                                <textarea
                                    rows="3"
                                    class="form-control"
                                    v-model="editTask.memo"
                                    :disabled="isLoading"
                                ></textarea>
                            </template>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
                <div class="card card-widget">
                    <div class="card-header">
                        <h3 class="card-title">Recent Activity</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">Coming Soon...</div>
                    <!-- /.card-body -->
                </div>
            </div>
            <!-- /.col -->

            <div class="col-md-4">
                <!-- Widget: user widget style 2 -->
                <div class="card card-widget widget-user-2 shadow-sm">
                    <div class="card-header">
                        <div class="user-block">
                            <!-- <img src="/images/adminlte/avatar5.png" alt="User Image" class="brand-image img-circle elevation-3" /> -->
                            <span class="username">{{
                                task?.creator?.name +
                                " (" +
                                task?.creator?.account +
                                ")"
                            }}</span>
                            <span class="description">{{
                                task.created_at
                            }}</span>
                        </div>
                        <div class="card-tools">
                            <template v-if="!isEditingInfo">
                                <a
                                    href="#"
                                    class="btn btn-info btn-sm mr-2"
                                    @click.prevent="edit(editTask)"
                                    v-tooltip="'Edit'"
                                >
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <a
                                    v-if="hasPermissionPm"
                                    href="#"
                                    class="btn btn-danger btn-sm mr-2"
                                    @click.prevent="deleteTask(editTask)"
                                    v-tooltip="'Delete'"
                                >
                                    <i class="fas fa-trash"></i>
                                </a>
                                <a
                                    href="#"
                                    class="btn btn-secondary btn-sm"
                                    @click.prevent="copyTaskLink(task)"
                                    v-tooltip="'Share'"
                                >
                                    <i class="fas fa-link"></i>
                                </a>
                            </template>

                            <template v-else>
                                <a
                                    href="#"
                                    class="btn btn-success btn-sm mr-2"
                                    @click.prevent="update(editTask)"
                                    v-tooltip="'Update'"
                                >
                                    <i class="fas fa-save"></i>
                                </a>
                                <a
                                    href="#"
                                    class="btn btn-secondary btn-sm mr-2"
                                    @click.prevent="cancel"
                                    v-tooltip="'Cancel'"
                                >
                                    <i class="fas fa-times"></i>
                                </a>
                            </template>
                        </div>
                    </div>
                    <div class="card-footer p-0">
                        <ul class="nav flex-column">
                            <li v-if="isEditingInfo" class="nav-item">
                                <span class="nav-link">
                                    Status
                                    <select
                                        class="form-control"
                                        v-model="editTask.status"
                                        id="selectStatus"
                                        :disabled="isLoading"
                                    >
                                        <option :value="0">Open</option>
                                        <option :value="1">In Progress</option>
                                        <option :value="2">Resolved</option>
                                        <option :value="3">Feedback</option>
                                        <option :value="4">Done</option>
                                        <option :value="5">Reopen</option>
                                        <option :value="6">Pending</option>
                                        <option :value="7">Canceled</option>
                                    </select>
                                </span>
                            </li>

                            <li
                                v-if="isEditingInfo && hasPermissionPm"
                                class="nav-item"
                            >
                                <span class="nav-link flex items-center gap-2">
                                    Priority
                                    <select
                                        class="form-control"
                                        v-model="editTask.priority"
                                        id="selectPriority"
                                        :disabled="isLoading"
                                    >
                                        <option
                                            v-for="p in priorities"
                                            :key="p.value"
                                            :value="p.value"
                                        >
                                            {{ p.label }}
                                        </option>
                                    </select>
                                </span>
                            </li>

                            <li class="nav-item">
                                <span class="nav-link">
                                    Assignee
                                    <strong
                                        v-if="
                                            !isEditingInfo || !hasPermissionPm
                                        "
                                        class="float-right"
                                        >{{ task.assignee_user?.account }} -
                                        {{ task.assignee_user?.name }}</strong
                                    >
                                    <select
                                        v-else
                                        class="form-control"
                                        id="selectAssignee"
                                        v-model="editTask.assignee"
                                        :disabled="isLoading"
                                    >
                                        <option
                                            v-for="user in listAssignee"
                                            :value="user.id"
                                        >
                                            {{ user.account }}
                                        </option>
                                    </select>
                                </span>
                            </li>
                            <li class="nav-item">
                                <span class="nav-link">
                                    Plan Start Date
                                    <strong
                                        v-if="
                                            !isEditingInfo || !hasPermissionPm
                                        "
                                        class="float-right"
                                        >{{
                                            task.plan_start_date ?? "-"
                                        }}</strong
                                    >

                                    <div
                                        v-else
                                        class="input-group date"
                                        id="dpPlanStartDate"
                                        data-target-input="nearest"
                                    >
                                        <input
                                            type="text"
                                            class="form-control datetimepicker-input"
                                            v-model="editTask.plan_start_date"
                                            data-target="#dpPlanStartDate"
                                            :disabled="isLoading"
                                        />
                                        <div
                                            class="input-group-append"
                                            data-target="#dpPlanStartDate"
                                            data-toggle="datetimepicker"
                                        >
                                            <div class="input-group-text">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                        </div>
                                    </div>
                                </span>
                            </li>

                            <li class="nav-item">
                                <span class="nav-link">
                                    Plan End Date
                                    <strong
                                        v-if="
                                            !isEditingInfo || !hasPermissionPm
                                        "
                                        class="float-right"
                                        >{{ task.plan_end_date ?? "-" }}</strong
                                    >

                                    <div
                                        v-else
                                        class="input-group date"
                                        id="dpPlanEndDate"
                                        data-target-input="nearest"
                                    >
                                        <input
                                            type="text"
                                            class="form-control datetimepicker-input"
                                            v-model="editTask.plan_end_date"
                                            data-target="#dpPlanEndDate"
                                            :disabled="isLoading"
                                        />
                                        <div
                                            class="input-group-append"
                                            data-target="#dpPlanEndDate"
                                            data-toggle="datetimepicker"
                                        >
                                            <div class="input-group-text">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                        </div>
                                    </div>
                                </span>
                            </li>

                            <li class="nav-item">
                                <span class="nav-link">
                                    Actual Start Date
                                    <strong
                                        v-if="
                                            !isEditingInfo || !hasPermissionPm
                                        "
                                        class="float-right"
                                        >{{
                                            task.actual_start_date ?? "-"
                                        }}</strong
                                    >

                                    <div
                                        v-else
                                        class="input-group date"
                                        id="dpActualStartDate"
                                        data-target-input="nearest"
                                    >
                                        <input
                                            type="text"
                                            class="form-control datetimepicker-input"
                                            v-model="editTask.actual_start_date"
                                            data-target="#dpActualStartDate"
                                            :disabled="isLoading"
                                        />
                                        <div
                                            class="input-group-append"
                                            data-target="#dpActualStartDate"
                                            data-toggle="datetimepicker"
                                        >
                                            <div class="input-group-text">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                        </div>
                                    </div>
                                </span>
                            </li>

                            <li class="nav-item">
                                <span class="nav-link">
                                    Actual End Date
                                    <strong
                                        v-if="
                                            !isEditingInfo || !hasPermissionPm
                                        "
                                        class="float-right"
                                        >{{
                                            task.actual_end_date ?? "-"
                                        }}</strong
                                    >

                                    <div
                                        v-else
                                        class="input-group date"
                                        id="dpActualEndDate"
                                        data-target-input="nearest"
                                    >
                                        <input
                                            type="text"
                                            class="form-control datetimepicker-input"
                                            v-model="editTask.actual_end_date"
                                            data-target="#dpActualEndDate"
                                            :disabled="isLoading"
                                        />
                                        <div
                                            class="input-group-append"
                                            data-target="#dpActualEndDate"
                                            data-toggle="datetimepicker"
                                        >
                                            <div class="input-group-text">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                        </div>
                                    </div>
                                </span>
                            </li>

                            <li class="nav-item">
                                <span class="nav-link">
                                    Plan Effort
                                    <strong
                                        v-if="
                                            !isEditingInfo || !hasPermissionPm
                                        "
                                        class="float-right"
                                        >{{ task.plan_effort }}</strong
                                    >
                                    <input
                                        v-else
                                        type="text"
                                        v-model="editTask.plan_effort"
                                        class="form-control"
                                        :disabled="isLoading"
                                    />
                                </span>
                            </li>
                            <li class="nav-item">
                                <span class="nav-link">
                                    Actual Effort
                                    <strong class="float-right">{{
                                        task.actual_effort
                                    }}</strong>
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- /.widget-user -->
                <div class="card card-widget widget-user-2 shadow-sm">
                    <div class="card-header">
                        <h3 class="card-title">Worklog List</h3>
                        <div class="card-tools">
                            <a
                                @click.prevent="openLogWorkModal(task)"
                                class="btn btn-primary btn-sm"
                                v-tooltip="'Log Work'"
                            >
                                <i class="fas fa-clock"></i>
                            </a>
                        </div>
                    </div>
                    <div class="relative">
                        <div v-if="worklogListIsLoading" class="overlay">
                            <div class="spinner"></div>
                            <p>Reloading...</p>
                        </div>
                        <div class="card-footer p-0">
                            <ul class="nav flex-column">
                                <li
                                    class="nav-item"
                                    v-for="worklog in worklogs"
                                    :key="worklog.id"
                                >
                                    <span class="nav-link">
                                        <strong
                                            >{{ worklog.user.name }} ({{
                                                worklog.user.account
                                            }})</strong
                                        >
                                        logged
                                        <strong
                                            >{{ worklog.log_time }}
                                            {{
                                                worklog.log_time === 1
                                                    ? "hour"
                                                    : "hours"
                                            }}</strong
                                        >
                                        at
                                        <strong>{{
                                            formatTime(worklog.created_at)
                                        }}</strong
                                        >.
                                        <span v-if="worklog.description">
                                            Worklog details:
                                            <strong>{{
                                                worklog.description
                                            }}</strong
                                            >.
                                        </span>
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

        <LogWorkModal
            :showModal="showLogWorkModal"
            :task="task"
            :projectId="props.projectId"
            @close="showLogWorkModal = false"
            @update-data="handleWorklogUpdate"
        />
    </div>
</template>

<script setup>
import { ref, onMounted, nextTick, computed } from "vue";
import axios from "axios";
import Swal from "sweetalert2";
import LogWorkModal from "../../components/LogWorkModal.vue";
import { TASK_PRIORITY, TASK_STATUS } from "../../constants/taskConstants";

const props = defineProps({
    task: {
        type: [Array, String], // Có thể là Array hoặc String
        default: () => [],
    },
    listAssignee: {
        type: [Array, String, Object], // Có thể là Array hoặc String
        default: () => [],
    },
    currentUserId: {
        type: [Number, String], // Có thể là Number hoặc String
        default: 0,
    },
    currentUserAccount: {
        type: String, // Có thể là Number hoặc String
        default: "",
    },
    userRole: {
        type: [Array, String], // Có thể là Array hoặc String
        default: () => [],
    },
});

const editTask = ref(null);
const isEditingInfo = ref(false);
const isLoading = ref(false); // Trạng thái loading

const task = ref({}); // Dùng ref để có thể cập nhật sau này
const worklogs = ref([]);

onMounted(() => {
    try {
        task.value = JSON.parse(props.task); // Gán dữ liệu khi mount
        editTask.value = task.value;
        worklogs.value = task.value.worklogs;
    } catch (error) {
        console.error("Lỗi parse JSON:", error);
        task.value = {}; // Tránh lỗi nếu JSON không hợp lệ
    }
});

const listAssignee = computed(() => {
    try {
        return JSON.parse(props.listAssignee); // Chuyển chuỗi JSON thành object
    } catch (error) {
        return {}; // Trả về object rỗng nếu có lỗi
    }
});

const userRoles = computed(() => {
    try {
        return JSON.parse(props.userRole); // Chuyển chuỗi JSON thành mảng
    } catch (error) {
        return []; // Trả về mảng rỗng nếu lỗi
    }
});

const hasPermissionPm = computed(() => {
    return userRoles.value.includes("client") || userRoles.value.includes("pm");
});

const cancel = () => {
    isEditingInfo.value = false;
    destroySelect2();
    editTask.value = { ...task.value }; // Gán lại bản sao mới thay vì tham chiếu
};

const edit = (editTask) => {
    isEditingInfo.value = true;
    nextTick(initPlugins(editTask));
};

// Hàm kích hoạt select2 và datetimepicker
const initPlugins = (editTask) => {
    nextTick(() => {
        // Khởi động lại select2
        $("#selectAssignee")
            .select2()
            .on("select2:open", () => {
                setTimeout(
                    () =>
                        $(
                            ".select2-container--open .select2-search__field"
                        )[0]?.focus(),
                    50
                );
            })
            .on("change", function (e) {
                editTask.assignee = $(this).val();
            });

        $("#selectPriority")
            .select2()
            .on("select2:open", () => {
                setTimeout(
                    () =>
                        $(
                            ".select2-container--open .select2-search__field"
                        )[0]?.focus(),
                    50
                );
            })
            .on("change", function (e) {
                editTask.priority = $(this).val();
            });

        $("#selectStatus")
            .select2()
            .on("select2:open", () => {
                setTimeout(
                    () =>
                        $(
                            ".select2-container--open .select2-search__field"
                        )[0]?.focus(),
                    50
                );
            })
            .on("change", function (e) {
                editTask.status = $(this).val();
            });

        $(
            "#dpPlanStartDate, #dpPlanEndDate, #dpActualStartDate, #dpActualEndDate"
        ).datetimepicker({
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

        $("#dpPlanStartDate").on("change.datetimepicker", function (e) {
            let newDate = e.date
                ? e.date.format("YYYY-MM-DD")
                : e.target.value
                ? e.target.value
                : "";
            editTask.plan_start_date = newDate;
        });

        $("#dpPlanEndDate").on("change.datetimepicker", function (e) {
            let newDate = e.date
                ? e.date.format("YYYY-MM-DD")
                : e.target.value
                ? e.target.value
                : "";
            editTask.plan_end_date = newDate;
        });

        $("#dpActualStartDate").on("change.datetimepicker", function (e) {
            let newDate = e.date
                ? e.date.format("YYYY-MM-DD")
                : e.target.value
                ? e.target.value
                : "";
            editTask.actual_start_date = newDate;
        });

        $("#dpActualEndDate").on("change.datetimepicker", function (e) {
            let newDate = e.date
                ? e.date.format("YYYY-MM-DD")
                : e.target.value
                ? e.target.value
                : "";
            editTask.actual_end_date = newDate;
        });
    });
};

const update = async (editTask) => {
    // Hủy Select2 trước khi cập nhật giao diện
    destroySelect2();

    // Tạo một object mới với dữ liệu đã chỉnh sửa
    const updateData = {
        ...editTask, // Giữ lại các thuộc tính cũ
        name: editTask.name,
        description: editTask.description,
        memo: editTask.memo,
        priority: editTask.priority,
        assignee: editTask.assignee,
        status: editTask.status,
        plan_start_date: editTask.plan_start_date,
        plan_end_date: editTask.plan_end_date,
        actual_start_date: editTask.actual_start_date,
        actual_end_date: editTask.actual_end_date,
        plan_effort: editTask.plan_effort,
    };

    try {
        isLoading.value = true; // Bật trạng thái loading
        // Gọi API cập nhật dữ liệu
        const url = `/api/tasks/${task.value.id}/update`;
        const response = await axios.put(url, updateData);

        toastr.success(response?.data?.message);

        // Cập nhật trực tiếp vào props.task để phản ánh trên giao diện
        task.value = response?.data?.task;

        // Cập nhật lại editTask để giữ đồng bộ
        editTask.value = { ...response?.data?.task };
    } catch (error) {
        // Lấy thông tin lỗi từ response
        const errorMessage =
            error.response?.data?.message || "Failed to create task!";
        const errorDetail = error.response?.data || "Unknown error";

        // Hiển thị toastr lỗi với cả message và error detail
        toastr.error(`${errorMessage}: ${errorDetail}`);
    } finally {
        isLoading.value = false; // Tắt loading dù có lỗi hay không
        isEditingInfo.value = false;
    }
};

const destroySelect2 = () => {
    $("#selectAssignee").select2("destroy");
    $("#selectPriority").select2("destroy");
    $("#selectStatus").select2("destroy");
};

const statusClass = (status) => {
    const value = Number(status);
    switch (value) {
        case 0: // "Open"
            return "float-right badge bg-secondary"; // Màu xám
        case 1: // "In Progress"
            return "float-right badge bg-primary"; // Màu xanh dương
        case 2: // "Resolved"
            return "float-right badge bg-success"; // Màu xanh lá
        case 3: // "Feedback"
            return "float-right badge bg-warning"; // Màu vàng
        case 4: // "Done"
            return "float-right badge bg-dark"; // Màu đen hoặc tím đậm
        case 5: // "Reopen"
            return "float-right badge bg-secondary"; // Màu xám
        case 6: // "Pending"
            return "float-right badge bg-warning"; // Màu cam
        case 7: // "Canceled"
            return "float-right badge bg-danger"; // Màu đỏ
        default:
            return "float-right badge bg-light"; // Màu nhạt cho trạng thái không xác định
    }
};

const priorities = ref([
    { value: TASK_PRIORITY.BLOCKER, label: "Blocker" },
    { value: TASK_PRIORITY.CRITICAL, label: "Critical" },
    { value: TASK_PRIORITY.HIGHEST, label: "Highest" },
    { value: TASK_PRIORITY.HIGHER, label: "Higher" },
    { value: TASK_PRIORITY.HIGH, label: "High" },
    { value: TASK_PRIORITY.MINOR, label: "Minor" },
    { value: TASK_PRIORITY.LOW, label: "Low" },
    { value: TASK_PRIORITY.LOWER, label: "Lower" },
    { value: TASK_PRIORITY.LOWEST, label: "Lowest" },
    { value: TASK_PRIORITY.TRIVIAL, label: "Trivial" },
]);

const deleteTask = async (task) => {
    let warningMessage = "Bạn có chắc chắn muốn xoá task này?";

    if (task.type === 0) {
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
        softDelete(task.id, task.project_id);
    }
};

const softDelete = async (taskId, projectId) => {
    try {
        const url = `/api/pm/${projectId}/tasks/${taskId}/destroy`;
        await axios.delete(url);
        toastr.success("Task deleted successfully!");
        toastr.info("Redirecting...", { timeOut: 2000 });

        setTimeout(() => {
            window.location.href = `/api/pm/${projectId}/tasks`;
        }, 2000);
    } catch (error) {
        // Lấy thông tin lỗi từ response
        const errorMessage =
            error.response?.data?.message || "Failed to create task!";
        const errorDetail = error.response?.data?.error || "Unknown error";

        // Hiển thị toastr lỗi với cả message và error detail
        toastr.error(`${errorMessage}: ${errorDetail}`);
    }
};

const showLogWorkModal = ref(false);
const worklogListIsLoading = ref(false);

const openLogWorkModal = (task) => {
    showLogWorkModal.value = true;
};

const handleWorklogUpdate = async () => {
    worklogListIsLoading.value = true;
    try {
        let url = null;
        const currentUrl = window.location.pathname; // Lấy đường dẫn (VD: "/pm/5/task/70")
        const urlParts = currentUrl.split("/"); // Tách thành mảng: ["", "pm", "5", "task", "70"]

        // Tìm vị trí của projectId (số đầu tiên trong URL sau "pm" hoặc "staff")
        const projectIdIndex = urlParts.findIndex((part) => /^\d+$/.test(part));

        if (projectIdIndex !== -1) {
            const baseUrl = window.location.origin; // Lấy domain (http://localhost:8080)
            // Cắt bỏ "pm" hoặc "staff" và giữ lại "/5/task/70/worklog"
            const newPath = `/api/${urlParts
                .slice(projectIdIndex)
                .join("/")}/worklog`;
            url = `${baseUrl}${newPath}`;
        }

        if (!url) throw new Error("Invalid URL format!");

        const response = await axios.get(url);
        worklogs.value = response.data.worklog; // Sửa lỗi lấy dữ liệu
    } catch (error) {
        // Lấy thông tin lỗi từ response
        const errorMessage =
            error.response?.data?.message || "Failed to update worklog!";
        const errorDetail = error.response?.data?.error || "Unknown error";

        // Hiển thị toastr lỗi với cả message và error detail
        toastr.error(`${errorMessage}: ${errorDetail}`);
    } finally {
        worklogListIsLoading.value = false;
    }
};

const copyTaskLink = (task) => {
    let taskLink = null;
    const currentUrl = window.location.pathname; // Lấy đường dẫn (VD: "/pm/5/task/70")
    const urlParts = currentUrl.split("/"); // Tách thành mảng: ["", "pm", "5", "task", "70"]

    // Tìm vị trí của projectId (số đầu tiên trong URL sau "pm" hoặc "staff")
    const projectIdIndex = urlParts.findIndex((part) => /^\d+$/.test(part));

    if (projectIdIndex !== -1) {
        const baseUrl = window.location.origin; // Lấy domain (http://localhost:8080)
        // Cắt bỏ phần "pm" hoặc "staff" và tạo URL mới
        const newPath = `/${urlParts.slice(projectIdIndex).join("/")}`;
        taskLink = `${baseUrl}${newPath}`;
    }

    navigator.clipboard
        .writeText(taskLink)
        .then(() => {
            toastr.success("Link copied to clipboard!");
        })
        .catch(() => {
            toastr.error("Failed to copy link.");
        });
};

// Hàm định dạng thời gian
import dayjs from "dayjs";

const formatTime = (time) => {
    return dayjs(time).format("YYYY-MM-DD HH:mm:ss");
};

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
</script>

<style scoped>
.description {
    white-space: pre-line;
    /* Giữ nguyên xuống dòng */
}

.relative {
    position: relative;
}

.overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    color: white;
    font-weight: bold;
    z-index: 10;
    border-radius: 8px;
}

.spinner {
    width: 40px;
    height: 40px;
    border: 5px solid rgba(255, 255, 255, 0.3);
    border-top-color: white;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin-right: 10px;
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }

    100% {
        transform: rotate(360deg);
    }
}
</style>
