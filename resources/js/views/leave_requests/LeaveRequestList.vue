<template>
    <div class="card" :class="isClientOrPm ? 'card-primary' : 'card-success'">
        <div class="card-header d-flex align-items-center" style="display: flex; justify-content: space-between;">
            <div>
                <h3 v-if="isClientOrPm" class="card-title">Leave Requests Management</h3>
                <h3 v-else class="card-title">My Leave Requests</h3>
            </div>

            <div v-if="isClientOrPm"  class="ml-auto">           
                <button @click.prevent="updateLeaveRequestStatus(true)" class="btn btn-success ml-auto"><i class="fas fa-check-circle"></i> Approve</button>
                <span class="mx-1"></span>
                <button @click.prevent="updateLeaveRequestStatus(false)" class="btn btn-danger ml-auto"><i class="fas fa-times-circle"></i> Decline</button>
            </div>
            <button v-else @click="openLeaveRequestModal()" class="btn btn-primary  ml-auto"><i class="fas fa-plus-circle"></i> Create New Leave Request</button>
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                <i class="fas fa-minus"></i>
            </button>
        </div>

        <div class="card-body">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th
                            v-if="isClientOrPm"
                            style="width: 3%"
                        >
                            <input
                                type="checkbox"
                                v-model="selectAll"
                                @change="toggleSelectAll"
                            />
                        </th>
                        <th
                            v-if="isColumnVisible('leave-user') && isClientOrPm"
                            style="width: 13%"
                        >
                            User
                        </th>
                        <th
                            v-if="isColumnVisible('leave-type')"
                            style="width: 13%"
                        >
                            Leave Type
                        </th>
                        <th
                            v-if="isColumnVisible('leave-date')"
                            style="width: 12%"
                        >
                            Leave Date
                        </th>
                        <th
                            v-if="isColumnVisible('leave-time')"
                            style="width: 17%"
                        >
                            Leave Time
                        </th>
                        <th
                            v-if="isColumnVisible('leave-reason')"
                            style="width: 15%"
                        >
                            Leave Reason
                        </th>
                        <th
                            v-if="isColumnVisible('leave-approver') && !isClientOrPm"
                            style="width: 15%"
                        >
                            Leave Approvers
                        </th>
                        <th
                            v-if="isColumnVisible('leave-status') && !globalIsEditting"
                            style="width: 12%"
                        >
                            Leave Status
                        </th>
                        <th
                            class="text-center"
                            v-if="isColumnVisible('action')"
                            style="width: 8%"
                        >
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <template v-if="visibleLeaveRequests.length > 0">
                        <template
                            v-for="leaveRequest in visibleLeaveRequests"
                            :key="leaveRequest.id"
                        >
                            <tr class="bg-light">
                                <td v-if="isClientOrPm">
                                    <input type="checkbox" v-model="selectedLeaveRequests" :value="leaveRequest.id" :disabled="leaveRequest.leave_status != 0"/>
                                </td>
                                <td v-if="isColumnVisible('leave-user') && isClientOrPm"
                                :style="leaveRequest.user.id == props.currentUserId ? 'font-weight: bold;' : ''">
                                    {{ leaveRequest.user.name }}
                                </td>
                                <td v-if="isColumnVisible('leave-type')">                                
                                    <span v-if="!leaveRequest.isEditing">
                                        {{ leaveRequest.leave_request_type.value1 }}
                                    </span>
                                    <select v-else v-model="leaveRequest.editedLeaveType" class="form-control custom-select">
                                        <option v-for="type in leaveTypes" :key="type.id" :value="type.key">
                                        {{ type.value1 }}
                                        </option>
                                    </select>
                                </td>
                                <td v-if="isColumnVisible('leave-date')">
                                    <span v-if="!leaveRequest.isEditing">{{
                                        leaveRequest.leave_date
                                    }}</span>
                                    <div
                                        v-else
                                        class="input-group date leave-date-datepicker"
                                        data-target-input="nearest"
                                    >
                                        <input
                                            type="text"
                                            class="form-control datetimepicker-input"
                                            v-model="leaveRequest.editedLeaveDate"
                                            data-target=".leave-date-datepicker"
                                        />
                                        <div
                                            class="input-group-append"
                                            data-target=".leave-date-datepicker"
                                            data-toggle="datetimepicker"
                                        >
                                            <div class="input-group-text">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                
                                <td v-if="isColumnVisible('leave-time')">
                                    <!-- Hiển thị thời gian khi không chỉnh sửa -->
                                    <span v-if="!leaveRequest.isEditing">
                                        {{ leaveRequest.leave_start_time }} - {{ leaveRequest.leave_end_time }}
                                    </span>

                                    <div v-else class="d-flex">
                                        <span v-if="leaveRequest.editedLeaveType!=2">
                                            -
                                        </span>

                                        <!-- Hiển thị input chỉnh sửa khi đang ở chế độ chỉnh sửa -->
                                        <div :style="{ visibility: leaveRequest.editedLeaveType==2 ? 'visible' : 'hidden' }"  class="d-flex">
                                            <!-- Thời gian bắt đầu -->
                                            <div class="input-group date leave-start-datepicker" data-target-input="nearest">
                                                <input 
                                                    type="text" 
                                                    class="form-control datetimepicker-input" 
                                                    v-model="leaveRequest.editedLeaveStartTime" 
                                                    data-target=".leave-start-datepicker" 
                                                />
                                                <div class="input-group-append" data-target=".leave-start-datepicker" data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa fa-clock"></i></div>
                                                </div>
                                            </div>

                                            <span class="mx-1"></span>

                                            <!-- Thời gian kết thúc -->
                                            <div class="input-group date leave-end-datepicker" data-target-input="nearest">
                                                <input 
                                                    type="text" 
                                                    class="form-control datetimepicker-input" 
                                                    v-model="leaveRequest.editedLeaveEndTime" 
                                                    data-target=".leave-end-datepicker" 
                                                />
                                                <div class="input-group-append" data-target=".leave-end-datepicker" data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa fa-clock"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                </td>
                                <td v-if="isColumnVisible('leave-reason')">
                                    <span v-if="!leaveRequest.isEditing">{{
                                        leaveRequest.leave_reason
                                    }}</span>
                                    <textarea
                                        v-else
                                        type="text"
                                        v-model="leaveRequest.editedLeaveReason"
                                        class="form-control form-control"
                                        rows="3"
                                    ></textarea>
                                </td>                            
                                <td v-if="isColumnVisible('leave-approver') && !isClientOrPm">
                                    <li v-if="!leaveRequest.isEditing" v-for="approver in leaveRequest.approvers" :key="approver.id">
                                        {{ approver.name }}
                                    </li>
                                    <div v-else class="form-group">
                                        <div class="select2-purple">
                                            <select v-model="leaveRequest.selectedLeaveApprovers" class="select2" multiple="multiple" data-placeholder="" data-dropdown-css-class="select2-purple" style="width: 100%;">
                                                <option v-for="approver in leaveApprovers" :key="approver.id" :value="approver.id">
                                                    {{ approver.name }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </td>
                                <td v-if="isColumnVisible('leave-status') && !globalIsEditting"
                                :style="{ 
                                    color: leaveRequest.leave_status === 0 ? '#FFD700' :
                                        leaveRequest.leave_status === 1 ? '#198754' :
                                        leaveRequest.leave_status === 2 ? '#dc3545' : 'inherit',
                                    fontWeight: 'bold'
                                }">
                                    {{ leaveRequest.leave_request_status.value1 }}
                                    <span v-if="leaveRequest.leave_approver" style="display: block; font-size: 12px;">
                                        <span style="font-weight: normal; font-size: 12px;">By</span> {{ leaveRequest.approver.name }}
                                        
                                    </span>                                
                                    <span v-if="leaveRequest.approved_at" style="display: block; font-weight: normal; font-size: 12px;">
                                        At {{ convertToVNTime(leaveRequest.approved_at) }}
                                    </span>
                                </td>
                                <td
                                    v-if="isColumnVisible('action')"
                                    class="text-center"
                                >
                                    <template v-if="!leaveRequest.isEditing">
                                        <a
                                            class="btn btn-info btn-sm mr-2"
                                            @click.prevent="editLeaveRequest(leaveRequest)" v-tooltip="'Edit'"
                                            :style="leaveRequest.leave_status != 0 ? 'pointer-events: none; opacity: 0.5; cursor: not-allowed;' : ''"
                                        >
                                            <i class="fas fa-pencil-alt"></i> 
                                        </a>
                                        <a
                                            class="btn btn-danger btn-sm mr-2"
                                            @click="confirmDelete(leaveRequest)" v-tooltip="'Delete'"
                                            :style="leaveRequest.leave_status != 0 ? 'pointer-events: none; opacity: 0.5; cursor: not-allowed;' : ''"
                                        >
                                            <i class="fas fa-trash"></i> 
                                        </a>
                                    </template>

                                    <template v-else>
                                        <a
                                            class="btn btn-success btn-sm mr-2"
                                            href="#"
                                            @click.prevent="updateLeaveRequest(leaveRequest)"  v-tooltip="'Update'"
                                        >
                                            <i class="fas fa-save"></i> 
                                        </a>
                                        <a
                                            class="btn btn-secondary btn-sm"
                                            href="#"
                                            @click.prevent="cancelEdit(leaveRequest)"  v-tooltip="'Cancel'"
                                        >
                                            <i class="fas fa-times"></i> 
                                        </a>
                                    </template>
                                </td>
                            </tr>
                        </template>
                    </template>
                    <tr v-else>
                        <td :colspan="getTotalColumns" class="text-center text-success" style="width: 100%;">
                            <div class="card-body text-center text-success py-2">
                                <p class="mb-0">🎉 Congratulations! It looks like you've completed all your tasks.</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <LeaveRequestModal :showModal="showLeaveRequestModal" 
            :currentUserId="props.currentUserId" @close="showLeaveRequestModal = false" @submit="emitLeaveRequestModal" />
    </div>
</template>

<script setup>
import { computed, ref, nextTick, onMounted, watch } from "vue";
import Swal from "sweetalert2";
import LeaveRequestModal from "./LeaveRequestModal.vue";
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
const props = defineProps({
    isClientOrPm: Boolean,
    userRole: {
        type: [Array, String], // Có thể là Array hoặc String
        default: () => [],
    },
    currentUserId: {
        type: [Number, String], // Có thể là Number hoặc String
        default: 0,
    },
    filteredLeaveRequests: Array,
    blankQuery: Boolean,
    visibleColumns: Array,
});

const leaveRequests = ref([]);

const globalIsEditting = ref(false);
const leaveApprovers = ref("");
const leaveTypes = ref("");
const showLeaveRequestModal = ref(false);
const selectedLeaveRequests = ref([]);
const selectAll = ref(false);
onMounted(async() => {
    leaveRequests.value = props.filteredLeaveRequests.map((leaveRequest) => ({
        ...leaveRequest,
        isEditing: false,
        editedLeaveType: leaveRequest.leave_type,
        editedLeaveDate: leaveRequest.leave_date,
        editedLeaveStartTime: leaveRequest.leave_start_time,
        editedLeaveEndTime: leaveRequest.leave_end_time,
        editedLeaveReason: leaveRequest.leave_reason,
        selectedLeaveApprovers: leaveRequest.approvers.map((approver) => approver.id),
    }));
    globalIsEditting.value = false;

    
    try {
        const {data} = await axios.get("/api/leave-request/approvers");
        if (data.original.success) {
            leaveApprovers.value = data.original.data;
        }
    } catch (error) {
        console.error("Lỗi khi lấy danh sách leave request approvers:", error);
    }

        try {
        const {data} = await axios.get("/api/leave-request/types");
        if (data.original.success) {
            leaveTypes.value = data.original.data;
        }
    } catch (error) {
        console.error("Lỗi khi lấy danh sách leave request types:", error);
    }
});

const toggleSelectAll = () => {
    if (selectAll.value) {
        selectedLeaveRequests.value = props.filteredLeaveRequests.filter(lr => lr.leave_status == 0).map(lr => lr.id);
    } else {
        selectedLeaveRequests.value = [];
    }
};
const isBlankQuery = computed(() => props.blankQuery ?? true);
const visibleLeaveRequests = computed(() => {
    let leaveRequests = props.filteredLeaveRequests;
    return leaveRequests;
});

// Kiểm tra xem cột có hiển thị không
const isColumnVisible = (column) => {
    return props.visibleColumns.includes(column);
};

// Hàm bật chế độ edit
const editLeaveRequest = (leaveRequest) => {

    if (globalIsEditting.value) {
        toastr.error(
            "Other leave request edit is in progress. Please cancel it before edit other."
        );
        return;
    }
    globalIsEditting.value = true;
    leaveRequest.isEditing = true;
    leaveRequest.editedLeaveType = leaveRequest.leave_type;
    leaveRequest.editedLeaveDate = leaveRequest.leave_date;
    leaveRequest.editedLeaveStartTime = leaveRequest.leave_start_time;
    leaveRequest.editedLeaveEndTime = leaveRequest.leave_end_time;
    leaveRequest.editedLeaveReason = leaveRequest.leave_reason;
    leaveRequest.selectedLeaveApprovers = leaveRequest.approvers.map((approver) => approver.id),
    nextTick(initPlugins(leaveRequest));
};

// Hàm kích hoạt select2 và datetimepicker
const initPlugins = (leaveRequest) => {
    nextTick(() => {

        $(".select2").select2().on("change", function () {
            leaveRequest.selectedLeaveApprovers = $(this).val() || []; // Cập nhật giá trị khi chọn
        });
        // Khởi động lại datetimepicker cho tất cả các trường ngày tháng
        $(".leave-date-datepicker").datetimepicker({
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

        $(".leave-date-datepicker").on("change.datetimepicker", function (e) {
            let newDate = e.date
                ? e.date.format("YYYY-MM-DD")
                : e.target.value
                ? e.target.value
                : "";
            leaveRequest.editedLeaveDate = newDate;
        });

        $(".leave-start-datepicker").datetimepicker({
            format: "HH:mm", // Chỉ hiển thị giờ & phút
            useCurrent: false, // Không tự động chọn giờ hiện tại
            showClose: true, // Hiển thị nút đóng
            showClear: true, // Hiển thị nút xóa
            icons: {
                time: "fa fa-clock", // Icon đồng hồ
                clear: "fa fa-trash",
                close: "fa fa-times"
            }
        });

        $(".leave-start-datepicker").on("change.datetimepicker", function (e) {
            let newLeaveTime = e.date 
                ? e.date.format("HH:mm")  // Lấy giờ & phút
                : e.target.value 
                ? e.target.value 
                : "";
            
            leaveRequest.editedLeaveStartTime = newLeaveTime;
        });

        $(".leave-end-datepicker").datetimepicker({
            format: "HH:mm", // Chỉ hiển thị giờ & phút
            useCurrent: false, // Không tự động chọn giờ hiện tại
            showClose: true, // Hiển thị nút đóng
            showClear: true, // Hiển thị nút xóa
            icons: {
                time: "fa fa-clock", // Icon đồng hồ
                clear: "fa fa-trash",
                close: "fa fa-times"
            }
        });

        $(".leave-end-datepicker").on("change.datetimepicker", function (e) {
            let newLeaveTime = e.date 
                ? e.date.format("HH:mm")  // Lấy giờ & phút
                : e.target.value 
                ? e.target.value 
                : "";
            
            leaveRequest.editedLeaveEndTime = newLeaveTime;
        });

        
    });
};

// Emit sự kiện update để thông báo lên component cha
const emit = defineEmits(["update-leave-request"]);

const updateLeaveRequest = async (leaveRequest) => {
    // Hủy Select2 trước khi cập nhật giao diện
    destroySelect2();

    if (!leaveRequest.editedLeaveType) {
        toastr.error("Please enter a valid leave type.");
        return;
    }

    if (!leaveRequest.editedLeaveDate) {
        toastr.error("Please enter a valid leave date.");
        return;
    }

    if (leaveRequest.editedLeaveType == 2 && !leaveRequest.editedLeaveStartTime) {
        toastr.error("Please enter a valid leave start time.");
        return;
    }

    if (leaveRequest.editedLeaveType == 2 && !leaveRequest.editedLeaveEndTime) {
        toastr.error("Please enter a valid leave end time.");
        return;
    }

    if (!leaveRequest.selectedLeaveApprovers || leaveRequest.selectedLeaveApprovers.length === 0) {
        toastr.error("Please select at least one approver.");
        return;
    }

    // Tạo một object mới với dữ liệu đã chỉnh sửa
    const updateLeaveRequest = {
        ...leaveRequest, // Giữ lại các thuộc tính cũ
        leave_type: leaveRequest.editedLeaveType,
        leave_date: leaveRequest.editedLeaveDate,
        leave_start_time: leaveRequest.editedLeaveType == 2 ? leaveRequest.editedLeaveStartTime : null,
        leave_end_time: leaveRequest.editedLeaveType == 2 ? leaveRequest.editedLeaveEndTime : null,
        leave_reason: leaveRequest.editedLeaveReason,
        leave_approvers: leaveRequest.selectedLeaveApprovers,
        isEditing: false,
    };

    try {
        // Gọi API cập nhật dữ liệu
        const url = `/api/leave-request/${leaveRequest.id}/update`;
        await axios.put(url, {
            leave_type: updateLeaveRequest.leave_type,
            leave_date: updateLeaveRequest.leave_date,
            leave_start_time: updateLeaveRequest.leave_start_time,
            leave_end_time: updateLeaveRequest.leave_end_time,
            leave_reason: updateLeaveRequest.leave_reason,
            leave_approvers: updateLeaveRequest.leave_approvers,
        });

        toastr.success("Updated leave request successfully!");
    } catch (error) {
        // Lấy thông tin lỗi từ response
        const errorMessage =
            error.response?.data?.message || "Failed to update leave request!";
        const errorDetail = error.response?.data?.error || "Unknown error";

        // Hiển thị toastr lỗi với cả message và error detail
        toastr.error(`${errorMessage}: ${errorDetail}`);
    }
    leaveRequest.isEditing = false;
    globalIsEditting.value = false;
    // Emit để component cha xử lý
    emit("update-leave-request");
};

const updateLeaveRequestStatus = async (isApproved) => {
    // Hủy Select2 trước khi cập nhật giao diện
    destroySelect2();

    if (!selectedLeaveRequests.value || selectedLeaveRequests.value.length === 0) {
        toastr.error("Please select at least one leave request.");
        return;
    }

    try {
        // Gọi API cập nhật dữ liệu
        const url = `/api/leave-request/update-status`;
        const payload = {
            list_leave_id: selectedLeaveRequests.value, // Mảng ID các đơn nghỉ
            leave_status: isApproved ? 1 : 2,
        };
        console.log(payload)

        await axios.put(url, {
            list_leave_id: selectedLeaveRequests.value, // Mảng ID các đơn nghỉ
            leave_status: isApproved ? 1 : 2,
        });

        toastr.success("Updated leave request status successfully!");        
        selectedLeaveRequests.value = [];
    } catch (error) {
        // Lấy thông tin lỗi từ response
        const errorMessage =
            error.response?.data?.message || "Failed to update leave request status!";
        const errorDetail = error.response?.data?.error || "Unknown error";

        // Hiển thị toastr lỗi với cả message và error detail
        toastr.error(`${errorMessage}: ${errorDetail}`);
    }
    // Emit để component cha xử lý
    emit("update-leave-request");
};

const cancelEdit = (leaveRequest) => {
    // Hủy Select2 trước khi cập nhật DOM
    destroySelect2();

    Object.assign(leaveRequest, leaveRequest.originalData); // Khôi phục dữ liệu gốc
    leaveRequest.isEditing = false;
    globalIsEditting.value = false;
};

const destroySelect2 = () => {
    $(".assignee-select").select2("destroy");
    $(".priority-select").select2("destroy");
    $(".status-select").select2("destroy");
};

const confirmDelete = async (leaveRequest) => {
    let warningMessage = "Bạn có chắc chắn muốn xoá yêu cầu này?";
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
        softDelete(leaveRequest.id);
    }
};

const softDelete = async (leaveRequestId) => {
    try {
        const url = `/api/leave-request/${leaveRequestId}/destroy`; // API xoá mềm
        await axios.delete(url);
        toastr.success("Leave request deleted successfully!");
        // Emit để component cha xử lý
        emit("update-leave-request");
    } catch (error) {
        // Lấy thông tin lỗi từ response
        const errorMessage =
            error.response?.data?.message || "Failed to delete leave request!";
        const errorDetail = error.response?.data?.error || "Unknown error";

        // Hiển thị toastr lỗi với cả message và error detail
        toastr.error(`${errorMessage}: ${errorDetail}`);
    }
};

const openLeaveRequestModal = () => {
    showLeaveRequestModal.value = true;
};

const emitLeaveRequestModal = () => {
    
    emit("update-leave-request");
    console.log("Emit")
};

const convertToVNTime = (datetime) => {
    if (!datetime) return '';
    
    // Chuyển đổi về đối tượng Date
    let date = new Date(datetime + 'Z'); // Thêm 'Z' để đảm bảo là UTC

    // Chuyển về múi giờ Việt Nam (GMT+7)
    return date.toLocaleString('vi-VN', { timeZone: 'Asia/Ho_Chi_Minh' });
};
</script>
