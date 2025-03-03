<template>
    <div v-if="showModal" class="modal fade show d-block" tabindex="-1" :aria-hidden="!showModal">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Log Work</h5>
                    <button type="button" class="close" @click="closeModal">&times;</button>
                </div>
                <div class="modal-body">
                    <!-- Title -->
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" class="form-control" :value="task?.name || 'N/A'" disabled />
                    </div>

                    <!-- Assignee -->
                    <div class="form-group">
                        <label>Assignee</label>
                        <input type="text" class="form-control" :value="task?.assignee?.account || 'N/A'" disabled />
                    </div>

                    <!-- Plan Effort -->
                    <div class="form-group">
                        <label>Plan Effort</label>
                        <input type="number" class="form-control" :value="task?.plan_effort || 0" disabled />
                    </div>

                    <!-- Actual Effort -->
                    <div class="form-group position-relative">
                        <label>Actual Effort</label>
                        <i v-if="task?.actual_effort > task?.actual_effort" class="fas fa-exclamation-triangle text-danger ml-2" title="Actual effort exceeds plan effort"></i>
                        <input type="number" class="form-control" :value="task?.actual_effort || 0" disabled />
                    </div>

                    <!-- Log Date (Datepicker) -->
                    <div class="form-group">
                        <label>Log Date <span style="color: red">*</span></label>
                        <div class="input-group date" id="logDatePicker" data-target-input="nearest">
                            <input type="text" v-model="logDate" class="form-control datetimepicker-input" data-target="#logDatePicker" />
                            <div class="input-group-append" data-target="#logDatePicker" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>

                    <!-- Log Time -->
                    <div class="form-group">
                        <label>Log Time (Hours) <span style="color: red">*</span></label>
                        <input type="number" class="form-control" v-model="logTime" step="0.1" min="0" />
                    </div>

                    <!-- Description -->
                    <div class="form-group">
                        <label>Description</label>
                        <textarea class="form-control" v-model="logDescription" rows="3"></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" @click="closeModal">Close</button>
                    <button class="btn btn-primary" @click="submitLogWork(task?.id)">
                        <span v-if="isLoading"><i class="fas fa-spinner fa-spin"></i> Processing...</span>
                        <span v-else> Save </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, defineProps, defineEmits, nextTick, watch } from "vue";
import axios from "axios";

const props = defineProps({
    showModal: Boolean,
    task: Object,
    projectId: [Number, String],
});

const emit = defineEmits(["close", "submit", "update-data"]);

const logDate = ref("");
const logTime = ref("");
const logDescription = ref("");
const isLoading = ref(false);

const closeModal = () => {
    emit("close");
};

const openLogWorkModal = () => {
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

// Theo dõi khi `showModal` thay đổi để reset data và khởi tạo datetimepicker
watch(() => props.showModal, (newVal) => {
    if (newVal) openLogWorkModal();
});

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

        await axios.post(`/api/${props.projectId}/tasks/${taskId}/worklog`, payload);
        toastr.success("Work logged successfully!");

        // Reset data nếu request thành công
        logTime.value = "";
        logDate.value = "";
        logDescription.value = "";

        // Đóng modal
        emit("close");
        emit("update-data");
    } catch (error) {
        console.log(error);
        const errorMessage = error.response?.data?.message || "Failed to create task!";
        const errorDetail = error.response?.data?.error || "Unknown error";
        toastr.error(`${errorMessage}: ${errorDetail}`);
    } finally {
        isLoading.value = false;
    }
};
</script>
