<template>
    <div>
        <div class="row">
            <div class="col-12">
                <!-- Default box -->
                <div class="card card-success collapsed-card">
                    <div class="card-header">
                        <h3 class="card-title">Add New Task</h3>
                        <div class="card-tools">
                            <button
                                type="button"
                                class="btn btn-tool"
                                data-card-widget="collapse"
                                title="Collapse"
                            >
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-10">
                                <div class="form-group">
                                    <label
                                        >Task Type<span style="color: red"
                                            >*</span
                                        ></label
                                    >
                                    <select
                                        ref="typeSelect"
                                        class="form-control select2"
                                        style="width: 100%"
                                    >
                                        <option value="" selected disabled>
                                            -- Select Task Type --
                                        </option>
                                        <option value="epic">Epic</option>
                                        <option value="task">Task</option>
                                    </select>
                                </div>

                                <!-- Nếu chọn Epic, hiển thị ô nhập Epic Title -->
                                <div
                                    v-if="selectedTaskType === 'epic'"
                                    class="form-group"
                                >
                                    <label
                                        >Epic Title<span style="color: red"
                                            >*</span
                                        ></label
                                    >

                                    <div>
                                        <input
                                            type="text"
                                            v-model="epicTitle"
                                            class="form-control"
                                            placeholder="Enter Epic Title"
                                        />
                                    </div>
                                </div>
                                <!-- Nếu chọn Task, hiển thị Select Epic + Task Title -->
                                <div v-if="selectedTaskType === 'task'">
                                    <div class="form-group">
                                        <label
                                            >Select Epic<span style="color: red"
                                                >*</span
                                            ></label
                                        >
                                        <div v-if="epicListIsLoading">
                                            <i
                                                class="fas fa-spinner fa-spin"
                                            ></i>
                                            Loading epics...
                                        </div>
                                        <div v-else>
                                            <select
                                                v-if="
                                                    selectedTaskType === 'task'
                                                "
                                                v-model="selectedEpic"
                                                class="form-control select2"
                                                ref="epicSelect"
                                            >
                                                <option
                                                    value=""
                                                    selected
                                                    disabled
                                                >
                                                    Select an Epic
                                                </option>
                                                <option
                                                    v-for="epic in epicList"
                                                    :key="epic.id"
                                                    :value="epic.id"
                                                >
                                                    {{ epic.name }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label
                                            >Task Title<span style="color: red"
                                                >*</span
                                            ></label
                                        >
                                        <input
                                            type="text"
                                            v-model="taskTitle"
                                            class="form-control"
                                            placeholder="Enter Task Title"
                                        />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea
                                        v-model="taskDescription"
                                        rows="7"
                                        class="form-control"
                                    ></textarea>
                                </div>

                                <div class="form-group">
                                    <label>Memo</label>
                                    <textarea
                                        v-model="taskMemo"
                                        rows="2"
                                        class="form-control"
                                    ></textarea>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="assignee">Assignee</label>
                                    <select
                                        id="assignee"
                                        v-model="selectedAssignee"
                                        name="assignee"
                                        class="form-control select2"
                                        style="width: 100%"
                                        ref="assigneeSelect"
                                    >
                                        <option value="">Assignee</option>
                                        <option
                                            v-for="assignee in parsedListAssignee"
                                            :key="assignee.id"
                                            :value="assignee.id"
                                        >
                                            {{ assignee.account }}
                                        </option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="priority"
                                        >Priority<span style="color: red"
                                            >*</span
                                        ></label
                                    >
                                    <select
                                        id="priority"
                                        name="priority"
                                        v-model="selectedPriority"
                                        class="form-control select2"
                                        style="width: 100%"
                                        ref="prioritySelect"
                                    >
                                        <option value="" selected disabled>
                                            Select a Priority
                                        </option>
                                        <option value="0">Trivial</option>
                                        <option value="1">Lowest</option>
                                        <option value="2">Lower</option>
                                        <option value="3">Low</option>
                                        <option value="4">Minor</option>
                                        <option value="5">High</option>
                                        <option value="6">Higher</option>
                                        <option value="7">Highest</option>
                                        <option value="8">Critical</option>
                                        <option value="9">Blocker</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Plan Effort (Hours)</label>
                                    <input
                                        type="number"
                                        v-model="estimateEffort"
                                        class="form-control"
                                    />
                                </div>
                                <div class="form-group">
                                    <label for="planStartDate"
                                        >Plan Start Date</label
                                    >
                                    <div
                                        class="input-group date"
                                        id="planStartDatePicker"
                                        data-target-input="nearest"
                                    >
                                        <input
                                            type="text"
                                            id="planStartDate"
                                            v-model="planStartDate"
                                            class="form-control datetimepicker-input"
                                            data-target="#planStartDatePicker"
                                        />
                                        <div
                                            class="input-group-append"
                                            data-target="#planStartDatePicker"
                                            data-toggle="datetimepicker"
                                        >
                                            <div class="input-group-text">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="planEndDate"
                                        >Plan End Date</label
                                    >
                                    <div
                                        class="input-group date"
                                        id="planEndDatePicker"
                                        data-target-input="nearest"
                                    >
                                        <input
                                            type="text"
                                            id="planEndDate"
                                            v-model="planEndDate"
                                            class="form-control datetimepicker-input"
                                            data-target="#planEndDatePicker"
                                        />
                                        <div
                                            class="input-group-append"
                                            data-target="#planEndDatePicker"
                                            data-toggle="datetimepicker"
                                        >
                                            <div class="input-group-text">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="actualStartDate"
                                        >Actual Start Date</label
                                    >
                                    <div
                                        class="input-group date"
                                        id="actualStartDatePicker"
                                        data-target-input="nearest"
                                    >
                                        <input
                                            type="text"
                                            id="actualStartDate"
                                            v-model="actualStartDate"
                                            class="form-control datetimepicker-input"
                                            data-target="#actualStartDatePicker"
                                        />
                                        <div
                                            class="input-group-append"
                                            data-target="#actualStartDatePicker"
                                            data-toggle="datetimepicker"
                                        >
                                            <div class="input-group-text">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="actualEndDate"
                                        >Actual End Date</label
                                    >
                                    <div
                                        class="input-group date"
                                        id="actualEndDatePicker"
                                        data-target-input="nearest"
                                    >
                                        <input
                                            type="text"
                                            id="actualEndDate"
                                            v-model="actualEndDate"
                                            class="form-control datetimepicker-input"
                                            data-target="#actualEndDatePicker"
                                        />
                                        <div
                                            class="input-group-append"
                                            data-target="#actualEndDatePicker"
                                            data-toggle="datetimepicker"
                                        >
                                            <div class="input-group-text">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <!-- <input type="submit" value="Add Task" class="btn btn-success float-right"> -->
                                <button
                                    class="btn btn-success"
                                    @click="handleSubmit()"
                                >
                                    <span v-if="isLoading">
                                        <i class="fas fa-spinner fa-spin"></i>
                                        Đang xử lý... </span
                                    ><span v-else> Save </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, nextTick } from "vue";
import axios from "axios";

const props = defineProps({
    projectId: String,
    listAssignee: {
        type: [Array, String], // Có thể là Array hoặc String
        default: () => [],
    },
    currentUserId: Number,
});

const parsedListAssignee = computed(() => {
    return typeof props.listAssignee === "string"
        ? JSON.parse(props.listAssignee)
        : props.listAssignee;
});

const taskTitle = ref("");
const taskDescription = ref("");
const taskMemo = ref("");

const epicTitle = ref("");
const estimateEffort = ref(null);
// const actualEffort = ref(null);
const planStartDate = ref("");
const planEndDate = ref("");
const actualStartDate = ref("");
const actualEndDate = ref("");

const selectedTaskType = ref(""); // Loại task (epic/task)
const selectedEpic = ref("");
const selectedAssignee = ref("");
const selectedPriority = ref("");

const epicList = ref([]); // Danh sách epic từ backend
const typeSelect = ref(null); // Thẻ select task type
const epicSelect = ref(null); // Thẻ select epic
const assigneeSelect = ref(null); // Thẻ select epic
const prioritySelect = ref(null); // Thẻ select epic

const isLoading = ref(false);
const epicListIsLoading = ref(false);

const fetchEpics = async () => {
    epicListIsLoading.value = true; // Bắt đầu loading
    try {
        const { data } = await axios.get(`/api/pm/${props.projectId}/epics`);
        epicList.value = data;
    } catch (error) {
        console.error("Lỗi khi lấy danh sách Epics:", error);
    } finally {
        epicListIsLoading.value = false; // Kết thúc loading
        await nextTick(() => {
            $(epicSelect.value)
                .select2({
                    placeholder: "Choose an epic",
                    allowClear: true,
                })
                .on("change", handleEpicChange);
        });
    }
};

const handleTaskTypeChange = async (e) => {
    selectedTaskType.value = e.target.value;

    if (selectedTaskType.value === "task") {
        await fetchEpics();
    }
};

const handleEpicChange = async (e) => {
    selectedEpic.value = e.target.value;
};

const handleAssigneeChange = async (e) => {
    selectedAssignee.value = e.target.value;
};

const handlePriorityChange = async (e) => {
    selectedPriority.value = e.target.value;
};

// Emit sự kiện update để thông báo lên component cha
const emit = defineEmits(["update-task"]);

// Gửi API lưu task
const handleSubmit = async () => {
    if (isLoading.value) return;
    isLoading.value = true;
    try {
        const payload = {
            name:
                selectedTaskType.value === "task"
                    ? taskTitle.value
                    : epicTitle.value, // Tên task
            type: selectedTaskType.value, // Loại task (epic/task)
            parent_id:
                selectedTaskType.value === "task" ? selectedEpic.value : null, // Epic ID nếu là task
            assignee: selectedAssignee.value, // Người được giao
            priority: selectedPriority.value, // Mức độ ưu tiên
            plan_effort: estimateEffort.value, // Kế hoạch effort
            description: taskDescription.value,
            memo: taskMemo.value,
            plan_start_date: planStartDate.value, // Ngày bắt đầu dự kiến
            plan_end_date: planEndDate.value, // Ngày kết thúc dự kiến
            actual_start_date: actualStartDate.value, // Ngày bắt đầu thực tế
            actual_end_date: actualEndDate.value, // Ngày kết thúc thực tế
            project_id: props.projectId, // ID dự án
            status: 0, // Mặc định status = 1
            progress: 0, // Tiến độ ban đầu = 0
            created_by: props.currentUserId, // Người tạo task
        };

        await axios.post(`/api/pm/${props.projectId}/tasks/store`, payload);
        toastr.success("Task created successfully!");

        // Emit để component cha xử lý
        emit("update-task", true);
        isLoading.value = false;
    } catch (error) {
        // Lấy thông tin lỗi từ response
        const errorMessage =
            error.response?.data?.message || "Failed to create task!";
        const errorDetail = error.response?.data?.error || "Unknown error";

        // Hiển thị toastr lỗi với cả message và error detail
        toastr.error(`${errorMessage}: ${errorDetail}`);
        isLoading.value = false;
    }
};

onMounted(() => {
    nextTick(() => {
        $(typeSelect.value)
            .select2({
                placeholder: "Choose a task type",
                allowClear: true,
            })
            .on("change", handleTaskTypeChange); // Gán function async vào đây

        $(assigneeSelect.value)
            .select2({
                placeholder: "Choose an assignee",
                allowClear: true,
            })
            .on("change", handleAssigneeChange);

        $(prioritySelect.value)
            .select2({
                placeholder: "Choose a priority",
                allowClear: true,
            })
            .on("change", handlePriorityChange);

        $("#planStartDatePicker").datetimepicker({
            format: "YYYY-MM-DD",
            useCurrent: false,
            allowInputToggle: true,
        });

        $("#planStartDatePicker").on("change.datetimepicker", function (e) {
            let newPlanStartDate = e.date
                ? e.date.format("YYYY-MM-DD")
                : e.target.value
                ? e.target.value
                : "";
            planStartDate.value = newPlanStartDate;
        });

        $("#planEndDatePicker").datetimepicker({
            format: "YYYY-MM-DD",
            useCurrent: false,
            allowInputToggle: true,
        });

        $("#planEndDatePicker").on("change.datetimepicker", function (e) {
            let newPlanEndDate = e.date
                ? e.date.format("YYYY-MM-DD")
                : e.target.value
                ? e.target.value
                : "";
            planEndDate.value = newPlanEndDate;
        });

        $("#actualStartDatePicker").datetimepicker({
            format: "YYYY-MM-DD",
            useCurrent: false,
            allowInputToggle: true,
        });

        $("#actualStartDatePicker").on("change.datetimepicker", function (e) {
            let newActualStartDate = e.date
                ? e.date.format("YYYY-MM-DD")
                : e.target.value
                ? e.target.value
                : "";
            actualStartDate.value = newActualStartDate;
        });

        $("#actualEndDatePicker").datetimepicker({
            format: "YYYY-MM-DD",
            useCurrent: false,
            allowInputToggle: true,
        });

        $("#actualEndDatePicker").on("change.datetimepicker", function (e) {
            let newActualEndDate = e.date
                ? e.date.format("YYYY-MM-DD")
                : e.target.value
                ? e.target.value
                : "";
            actualEndDate.value = newActualEndDate;
        });
    });
});
</script>
