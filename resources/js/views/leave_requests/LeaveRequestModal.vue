<template>
    <div
        v-if="showModal"
        class="modal fade show d-block"
        tabindex="-1"
        :aria-hidden="!showModal"
    >
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Leave Request</h5>
                    <button type="button" class="close" @click="closeModal">
                        &times;
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Leave Type -->
                    <div class="form-group">
                        <label>Leave Type <span style="color: red">*</span></label>
                        <select v-model="leaveType" class="form-control custom-select">
                            <option v-for="type in leaveTypes" :key="type.id" :value="type.key">
                            {{ type.value1 }}
                            </option>
                        </select>
                    </div>

                    <!-- Leave Date (Datepicker) -->
                    <div class="form-group">
                        <label>Leave Date <span style="color: red">*</span></label>
                        <div class="input-group date" id="leaveDatePicker" data-target-input="nearest">
                            <input 
                                type="text" 
                                v-model="leaveDate" 
                                class="form-control datetimepicker-input" 
                                data-target="#leaveDatePicker"
                            />
                            <div class="input-group-append" data-target="#leaveDatePicker" data-toggle="datetimepicker">
                                <div class="input-group-text">
                                    <i class="fa fa-calendar"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" v-show="leaveType==2">
                        <label>Leave Time Range <span style="color: red">*</span>  <small class="text-muted">(00:00 - 23:59)</small></label>
                        <div class="d-flex">
                            <!-- Thời gian bắt đầu -->
                            <div class="input-group date" data-target-input="nearest" id="leaveStartTimePicker">
                                <input 
                                    type="text" 
                                    class="form-control datetimepicker-input" 
                                    v-model="leaveStartTime" 
                                    data-target="#leaveStartTimePicker" 
                                />
                                <div class="input-group-append" data-target="#leaveStartTimePicker" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-clock"></i></div>
                                </div>
                            </div>

                            <span class="mx-2"> - </span>

                            <!-- Thời gian kết thúc -->
                            <div class="input-group date" data-target-input="nearest" id="leaveEndTimePicker">
                                <input 
                                    type="text" 
                                    class="form-control datetimepicker-input" 
                                    v-model="leaveEndTime" 
                                    data-target="#leaveEndTimePicker" 
                                />
                                <div class="input-group-append" data-target="#leaveEndTimePicker" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-clock"></i></div>
                                </div>
                            </div>
                        </div>
                        
                    </div>

                    <!-- Leave Approvers -->
                    <div class="form-group">
                        <label>Leave Approvers <span style="color: red">*</span></label>
                        <div class="select2-purple">
                            <select v-model="selectedLeaveApprovers" class="select2" multiple="multiple" data-placeholder="" data-dropdown-css-class="select2-purple" style="width: 100%;">
                                <option v-for="approver in leaveApprovers" :key="approver.id" :value="approver.id">
                                    {{ approver.name }}
                                </option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Leave Reason -->
                    <div class="form-group">
                        <label>Leave Reason</label>
                        <textarea
                            class="form-control"
                            v-model="leaveReason"
                            rows="3"
                        ></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" @click="closeModal">
                        Close
                    </button>
                    <button
                        class="btn btn-primary"
                        @click="submitLeaveRequest()"
                    >
                        <span v-if="isLoading"
                            ><i class="fas fa-spinner fa-spin"></i>
                            Processing...</span
                        >
                        <span v-else> Save </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, defineProps, defineEmits, nextTick, watch, onMounted } from "vue";
import axios from "axios";
const props = defineProps({
    showModal: Boolean,
    currentUserId: {
        type: [Number, String], // Có thể là Number hoặc String
        default: 0,
    },
});

// Lấy ngôn ngữ hiện tại từ session hoặc mặc định là "en"
const currentLang = ref(window.appLang || "en");

// Khởi tạo translations là một ref
const translations = ref(window.translations?.[currentLang.value] || {});

// Cập nhật translations khi `currentLang` thay đổi
watch(currentLang, (newLang) => {
    translations.value = window.translations?.[newLang] || {};
});

const emit = defineEmits(["close", "submit"]);
const leaveType = ref("");
const leaveDate = ref("");
const leaveStartTime = ref("");
const leaveEndTime = ref("");
const leaveReason = ref("");
const leaveTypes = ref("");
const leaveApprovers = ref("");
const selectedLeaveApprovers = ref([]);
const isLoading = ref(false);

onMounted( async () => {
    try {
        const {data} = await axios.get("/api/leave-request/types");
        if (data.original.success) {
            leaveTypes.value = data.original.data;
        }
    } catch (error) {
        console.error("Lỗi khi lấy danh sách leave request types:", error);
    }
    try {
        const {data} = await axios.get("/api/leave-request/approvers");
        if (data.original.success) {
            leaveApprovers.value = data.original.data;
        }
    } catch (error) {
        console.error("Lỗi khi lấy danh sách leave request approvers:", error);
    }
});
const closeModal = () => {
    emit("close");
};
const openLeaveRequestModal = () => {
    leaveType.value = "";
    leaveStartTime.value = "";
    leaveEndTime.value = "";
    leaveDate.value = "";
    leaveReason.value = "";

    nextTick(() => {

        $(".select2").select2().on("change", function () {
            selectedLeaveApprovers.value = $(this).val() || []; // Cập nhật giá trị khi chọn
        });

        $("#leaveDatePicker").datetimepicker({
            format: "YYYY-MM-DD",
            allowInputToggle: true,
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

        $("#leaveDatePicker").on("change.datetimepicker", (e) => {
            leaveDate.value = e.date
                ? e.date.format("YYYY-MM-DD")
                : e.target.value
                ? e.target.value
                : "";
        });

        $("#leaveStartTimePicker").datetimepicker({
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

        $("#leaveStartTimePicker").on("change.datetimepicker", (e) => {
            leaveStartTime.value = e.date
                ? e.date.format("HH:mm")
                : e.target.value
                ? e.target.value
                : "";
        });

        $("#leaveEndTimePicker").datetimepicker({
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

        $("#leaveEndTimePicker").on("change.datetimepicker", (e) => {
            leaveEndTime.value = e.date
                ? e.date.format("HH:mm")
                : e.target.value
                ? e.target.value
                : "";
        });

    });
};

// Theo dõi khi `showModal` thay đổi để reset data và khởi tạo datetimepicker
watch(
    () => props.showModal,
    (newVal) => {
        if (newVal) openLeaveRequestModal();
    }
);

const submitLeaveRequest = async () => {
    if (isLoading.value) return;
    isLoading.value = true;

    if (!leaveType.value) {
        toastr.error("Please enter a valid leave type.");
        isLoading.value = false;
        return;
    }

    if (!leaveDate.value) {
        toastr.error("Please enter a valid leave date.");
        isLoading.value = false;
        return;
    }

    if (leaveType.value==2 && !leaveStartTime.value) {
        toastr.error("Please enter a valid leave start time.");
        isLoading.value = false;
        return;
    }

    if (leaveType.value==2 && !leaveEndTime.value) {
        toastr.error("Please enter a valid leave end time.");
        isLoading.value = false;
        return;
    }

    if (!selectedLeaveApprovers.value || selectedLeaveApprovers.value.length === 0) {
        toastr.error("Please select at least one approver.");
        isLoading.value = false;
        return;
    }

    try {
        const payload = {
            leave_user: props.currentUserId,
            leave_type: leaveType.value,
            leave_date: leaveDate.value,
            leave_start_time: leaveStartTime.value,
            leave_end_time: leaveEndTime.value,
            leave_reason: leaveReason.value,
            leave_approvers: selectedLeaveApprovers.value,
        };

        await axios.post(
            `/api/leave-request/store`,
            payload
        );
        toastr.success("Create leave request successfully!");

        // Reset data nếu request thành công
        leaveType.value = "";
        leaveStartTime.value = "";
        leaveEndTime.value = "";
        leaveDate.value = "";
        leaveReason.value = "";
        selectedLeaveApprovers.value = [];

        // Đóng modal
        emit("close");
        emit("submit");
    } catch (error) {
        console.log(error);
        const errorMessage =
            error.response?.data?.message || "Failed to create leave request!";
        const errorDetail = error.response?.data?.error || "Unknown error";
        toastr.error(`${errorMessage}: ${errorDetail}`);
    } finally {
        isLoading.value = false;
    }
};
</script>

