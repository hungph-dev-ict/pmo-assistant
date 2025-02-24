<template>
    <div class="row">
        <div class="col-md-8">
            <!-- Box Comment -->
            <div class="card card-widget">
                <div class="card-header">
                    <h3 v-if="!isEditingInfo || !hasPermissionPm" class="card-title"> <span v-if="props.task.type == 1"> {{
                        props.task.parent.name }} </span> / {{
                                props.task.name }}</h3>
                    <template v-else>
                        <label>Title</label>
                        <input type="text" v-model="editTask.name" class="form-control" />
                    </template>
                    <span v-if="!isEditingInfo || !hasPermissionPm" :class="statusClass(props.task.status)">{{
                        props.task.task_status.value1
                    }}</span>
                    <!-- /.user-block -->
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <p v-if="!isEditingInfo || !hasPermissionPm" class="text-muted description"> {{ props.task.description }}</p>
                    <template v-else>
                        <label>Description</label>
                        <textarea v-model="editTask.description" rows="10" class="form-control"></textarea>
                    </template>

                    <br>
                    <div class="text-muted">
                        <p class="text-sm">Components
                            <b class="d-block">{{ $props.task.components ?? '-' }}</b>
                        </p>
                    </div>

                    <br>
                    <div class="text-muted">
                        <p v-if="!isEditingInfo" class="text-sm description">Memo
                            <b class="d-block">{{ $props.task.memo ?? '-' }}</b>
                        </p>
                        <template v-else>
                            <label>Memo</label>
                            <textarea rows="3" class="form-control" v-model="editTask.memo"></textarea>
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
                        <span class="username">{{ props.task.creator.name + ' (' + props.task.creator.account + ')'
                            }}</span>
                        <span class="description">{{ props.task.created_at }}</span>
                    </div>
                    <div class="card-tools">
                        <template v-if="!isEditingInfo">
                            <button @click="edit(editTask)" class="btn btn-info btn-sm mr-2">
                                Edit
                            </button>
                            <button v-if="hasPermissionPm" @click="deleteTask(editTask) " class="btn btn-danger btn-sm mr-2">
                                Delete
                            </button>
                        </template>
                        <template v-else>
                            <button @click="update(editTask)" class="btn btn-success btn-sm mr-2">
                                Update
                            </button>
                            <button @click="cancel" class="btn btn-secondary btn-sm mr-2">
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
                                <select class="form-control" v-model="editTask.status" id="selectStatus">
                                    <option :value="0">
                                        Open
                                    </option>
                                    <option :value="1">In Progress</option>
                                    <option :value="2">Resolved</option>
                                    <option :value="3">Feedback</option>
                                    <option :value="4">
                                        Done
                                    </option>
                                    <option :value="5">
                                        Reopen
                                    </option>
                                </select>
                            </span>
                        </li>

                        <li class="nav-item">
                            <span class="nav-link">
                                Priority <strong v-if="!isEditingInfo || !hasPermissionPm" :class="priorityClass(props.task.priority)">
                                    {{
                                        props.task.task_priority.value1
                                    }}</strong>
                                <select v-else class="form-control" v-model="editTask.priority" id="selectPriority">
                                    <option :value="0">
                                        Pending
                                    </option>
                                    <option :value="1">Low</option>
                                    <option :value="2">Medium</option>
                                    <option :value="3">High</option>
                                    <option :value="4">
                                        Critical
                                    </option>
                                </select>
                            </span>
                        </li>
                        <li class="nav-item">
                            <span class="nav-link">
                                Assignee <strong v-if="!isEditingInfo || !hasPermissionPm" class="float-right">{{
                                    props.task.assignee_user?.account
                                }} - {{ props.task.assignee_user?.name }}</strong>
                                <select v-else class="form-control" id="selectAssignee" v-model="editTask.assignee">
                                    <option v-for="user in listAssignee" :value="user.id">
                                        {{ user.account }}
                                    </option>
                                </select>
                            </span>
                        </li>
                        <li class="nav-item">
                            <span class="nav-link">
                                Plan Start Date
                                <strong v-if="!isEditingInfo || !hasPermissionPm" class="float-right">{{
                                    props.task.plan_start_date ?? "-"
                                }}</strong>

                                <div v-else class="input-group date" id="dpPlanStartDate" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input"
                                        v-model="editTask.plan_start_date" data-target="#dpPlanStartDate" />
                                    <div class="input-group-append" data-target="#dpPlanStartDate"
                                        data-toggle="datetimepicker">
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
                                <strong v-if="!isEditingInfo || !hasPermissionPm" class="float-right">{{
                                    props.task.plan_end_date ?? "-"
                                }}</strong>

                                <div v-else class="input-group date" id="dpPlanEndDate" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input"
                                        v-model="editTask.plan_end_date" data-target="#dpPlanEndDate" />
                                    <div class="input-group-append" data-target="#dpPlanEndDate"
                                        data-toggle="datetimepicker">
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
                                <strong v-if="!isEditingInfo || !hasPermissionPm" class="float-right">{{
                                    props.task.actual_start_date ?? "-"
                                }}</strong>

                                <div v-else class="input-group date" id="dpActualStartDate" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input"
                                        v-model="editTask.actual_start_date" data-target="#dpActualStartDate" />
                                    <div class="input-group-append" data-target="#dpActualStartDate"
                                        data-toggle="datetimepicker">
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
                                <strong v-if="!isEditingInfo || !hasPermissionPm" class="float-right">{{
                                    props.task.actual_end_date ?? "-"
                                }}</strong>

                                <div v-else class="input-group date" id="dpActualEndDate" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input"
                                        v-model="editTask.actual_end_date" data-target="#dpActualEndDate" />
                                    <div class="input-group-append" data-target="#dpActualEndDate"
                                        data-toggle="datetimepicker">
                                        <div class="input-group-text">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                            </span>
                        </li>

                        <li class="nav-item">
                            <span class="nav-link">
                                Plan Effort <strong v-if="!isEditingInfo || !hasPermissionPm" class="float-right">{{
                                    props.task.estimate_effort
                                }}</strong>
                                <input v-else type="text" v-model="editTask.estimate_effort" class="form-control" />
                            </span>
                        </li>
                        <li class="nav-item">
                            <span class="nav-link">
                                Actual Effort <strong class="float-right">{{
                                    props.task.actual_effort
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
</template>

<script setup>
import { ref, onMounted, nextTick, computed } from "vue";
import axios from "axios";
import Swal from "sweetalert2";

const props = defineProps({
    task: Object,
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

const userRoles = computed(() => {
    try {
        return JSON.parse(props.userRole); // Chuyển chuỗi JSON thành mảng
    } catch (error) {
        return []; // Trả về mảng rỗng nếu lỗi
    }
});

const hasPermissionPm = computed(() => {
    return userRoles.value.includes("pm");
});

const editTask = ref(null);
const isEditingInfo = ref(false);

const cancel = () => {
    isEditingInfo.value = false;
    destroySelect2();
    editTask.value = { ...props.task }; // Gán lại bản sao mới thay vì tham chiếu
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
        estimate_effort: editTask.estimate_effort,
    };

    try {
        // Gọi API cập nhật dữ liệu
        const url = `/api/tasks/${props.task.id}/update`;
        const response = await axios.put(url, updateData);

        toastr.success(response?.data?.message);

        // Cập nhật trực tiếp vào props.task để phản ánh trên giao diện
        Object.assign(props.task, response?.data?.task);

        // Cập nhật lại editTask để giữ đồng bộ
        editTask.value = { ...response?.data?.task };
    } catch (error) {
        // Lấy thông tin lỗi từ response
        const errorMessage =
            error.response?.data?.message || "Failed to create task!";
        const errorDetail = error.response?.data || "Unknown error";

        // Hiển thị toastr lỗi với cả message và error detail
        toastr.error(`${errorMessage}: ${errorDetail}`);
    }
    isEditingInfo.value = false;
};

const destroySelect2 = () => {
    $("#selectAssignee").select2("destroy");
    $("#selectPriority").select2("destroy");
    $("#selectStatus").select2("destroy");
};

const statusClass = (status) => {
    const value = Number(status);
    switch (value) {
        case 0:
            return "float-right badge bg-secondary"; // Màu xám
        case 1:
            return "float-right badge bg-primary"; // Màu xanh dương
        case 2:
            return "float-right badge bg-success"; // Màu xanh lá
        case 3:
            return "float-right badge bg-warning"; // Màu vàng
        case 4:
            return "float-right badge bg-primary"; // Màu đen hoặc tím đậm
        case 5:
            return "float-right badge bg-secondary"; // Màu đen hoặc tím đậm
        default:
            return "float-right badge bg-light"; // Màu nhạt cho trạng thái không xác định
    }
};

const priorityClass = (priority) => {
    const value = Number(priority);
    switch (value) {
        case 0:
            return "float-right badge bg-secondary"; // Màu xám - Đang tạm dừng
        case 1:
            return "float-right badge bg-primary"; // Màu xanh dương - Thấp
        case 2:
            return "float-right badge bg-warning"; // Màu vàng - Trung bình
        case 3:
            return "float-right badge bg-danger"; // Màu đỏ - Cao
        case 4:
            return "float-right badge bg-dark"; // Màu đen - Cực kỳ quan trọng
        default:
            return "float-right badge bg-light"; // Màu nhạt cho trạng thái không xác định
    }
};

const deleteTask = async (task) => {
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
    } catch (error) {
        // Lấy thông tin lỗi từ response
        const errorMessage =
            error.response?.data?.message || "Failed to create task!";
        const errorDetail = error.response?.data?.error || "Unknown error";

        // Hiển thị toastr lỗi với cả message và error detail
        toastr.error(`${errorMessage}: ${errorDetail}`);
    }
};

onMounted(() => {
    editTask.value = { ...props.task };
});
</script>

<style>
.description {
    white-space: pre-line;
    /* Giữ nguyên xuống dòng */
}
</style>
