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
                            <PriorityIcon
                                :priority="priorityMap[task.priority]"
                            />
                            <span v-if="task.type == 1">
                                {{ task.parent.name }} /
                            </span>
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
            </div>
            <!-- /.col -->

            <div class="col-md-4">
                <!-- Widget: user widget style 2 -->
                <div class="card card-widget widget-user-2 shadow-sm">
                    <div class="card-header">
                        <div class="user-block">
                            <!-- <img class="img-circle" src="../dist/img/user1-128x128.jpg" alt="User Image">
                        <img src="{{ Vite::asset('resources/images/adminlte/pmo-a_main.png') }}" alt="PMO Assistant Logo"
            class="brand-image img-circle elevation-3"> -->
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
                                <button
                                    @click="edit(editTask)"
                                    class="btn btn-info btn-sm mr-2"
                                >
                                    Edit
                                </button>
                                <button
                                    v-if="hasPermissionPm"
                                    @click="deleteTask(editTask)"
                                    class="btn btn-danger btn-sm mr-2"
                                >
                                    Delete
                                </button>
                            </template>
                            <template v-else>
                                <button
                                    @click="update(editTask)"
                                    class="btn btn-success btn-sm mr-2"
                                >
                                    <span v-if="isLoading">
                                        <i class="fas fa-spinner fa-spin"></i>
                                        Updating...
                                    </span>
                                    <span v-else> Update</span>
                                </button>
                                <button
                                    @click="cancel"
                                    class="btn btn-secondary btn-sm mr-2"
                                >
                                    Cancel
                                </button>
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
                                        <option :value="9">🚧 Blocker</option>
                                        <option :value="8">🔥 Critical</option>
                                        <option :value="7">🔴 Highest</option>
                                        <option :value="6">🟠 Higher</option>
                                        <option :value="5">🟡 High</option>
                                        <option :value="4">🟢 Minor</option>
                                        <option :value="3">🔵 Low</option>
                                        <option :value="2">⚪ Lower</option>
                                        <option :value="1">⚫ Lowest</option>
                                        <option :value="0">🟣 Trivial</option>
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
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

        <div class="row">
            <div class="col-md-8">
                <!-- Box Comment -->
                <div class="card card-widget">
                    <div class="card-header">
                        <h3 class="card-title">Recent Activity</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">Coming Soon...</div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->

            <div class="col-md-4">
                <!-- Widget: user widget style 2 -->
                <div class="card card-widget widget-user-2 shadow-sm">
                    <div class="card-header">
                        <h3 class="card-title">Worklog List</h3>
                        <!-- <div class="card-tools">
                            <button @click.prevent="openLogWorkModal(task)" class="btn btn-primary btn-sm">
                                Log Work
                            </button>
                        </div> -->
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
                                    v-for="worklog in task.worklogs"
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
                                        <strong>{{
                                            worklog.description
                                                ? "Worklog details: " +
                                                  worklog.description +
                                                  "."
                                                : ""
                                        }}</strong>
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- /.widget-user -->
            </div>
            <!-- /.col -->
        </div>
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

const priorityMap = {
    0: "Trivial",
    1: "Lowest",
    2: "Lower",
    3: "Low",
    4: "Minor",
    5: "High",
    6: "Higher",
    7: "Highest",
    8: "Critical",
    9: "Blocker",
};

const props = defineProps({
    task: {
        type: [Array, String], // Có thể là Array hoặc String
        default: () => [],
    },
    listAssignee: {
        type: [Array, String], // Có thể là Array hoặc String
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

onMounted(() => {
    try {
        task.value = JSON.parse(props.task); // Gán dữ liệu khi mount
        editTask.value = task.value;
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
            .on("change", function (e) {
                editTask.assignee = $(this).val();
            });

        $("#selectPriority")
            .select2()
            .on("change", function (e) {
                editTask.priority = $(this).val();
            });

        $("#selectStatus")
            .select2()
            .on("change", function (e) {
                editTask.status = $(this).val();
            });

        $(
            "#dpPlanStartDate, #dpPlanEndDate, #dpActualStartDate, #dpActualEndDate"
        ).datetimepicker({
            format: "YYYY-MM-DD",
            icons: { time: "fa fa-clock", date: "fa fa-calendar" },
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

const deleteTask = async (task) => {
    let warningMessage = "Bạn có chắc chắn muốn xoá task này?";

    if (task.value.type === "epic") {
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
        softDelete(task.value.id);
    }
};

const softDelete = async (taskId) => {
    try {
        const url = `/api/pm/${props.projectId}/tasks/${taskId}/destroy`; // API xoá mềm
        await axios.delete(url);
        toastr.success("Task deleted successfully!");
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

const handleWorklogUpdate = () => {
    worklogListIsLoading.value = true;
};

// Hàm định dạng thời gian
import dayjs from "dayjs";

const formatTime = (time) => {
    return dayjs(time).format("YYYY-MM-DD HH:mm:ss");
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
