<template>
    <div class="card card-primary collapsed-card">
        <div class="card-header">
            <h3 class="card-title">Search Box</h3>
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

        <div
            class="card-body"
            :class="{ 'disabled-overlay': props.taskListEditing }"
        >
            <div class="row">
                <!-- Tìm kiếm chung -->
                <div class="col-2">
                    <div class="form-group">
                        <label>Task Title Search:</label>
                        <div class="position-relative">
                            <input
                                v-model="searchText"
                                @keyup.enter="updateSearch"
                                type="text"
                                class="form-control"
                                placeholder="Enter text, press Enter..."
                            />
                            <button
                                v-if="searchText"
                                @click="resetSearchTitle"
                                class="btn btn-sm btn-light position-absolute"
                                style="
                                    right: 10px;
                                    top: 50%;
                                    transform: translateY(-50%);
                                "
                            >
                                ✖
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Tìm kiếm theo Status (Multiple) -->
                <div class="col-2">
                    <div class="form-group">
                        <label>Search Priority:</label>
                        <select
                            ref="prioritySelect"
                            v-model="filtersQuery.priority"
                            class="form-control select2"
                            multiple="multiple"
                            data-placeholder="Select Priority"
                            style="width: 100%"
                        >
                            <option
                                v-for="[priority, id] in Object.entries(
                                    listPriorities
                                )"
                                :key="id"
                                :value="priority"
                            >
                                {{ priority }}
                            </option>
                        </select>
                    </div>
                </div>

                <!-- Tìm kiếm Assignee -->
                <div class="col-2">
                    <div class="form-group position-relative">
                        <label>Search Assignee:</label>
                        <select
                            ref="assigneeSelect"
                            class="form-control select2"
                            data-placeholder="Select Assignee"
                            v-model="filtersQuery.assignee"
                        >
                            <option value="" selected disabled></option>
                            <option
                                v-for="[account, id] in Object.entries(
                                    listAssignee
                                )"
                                :key="id"
                                :value="account"
                            >
                                {{ account }}
                                <span v-if="account == currentUserAccount">
                                    (Me)</span
                                >
                            </option>
                        </select>
                    </div>
                </div>

                <!-- Tìm kiếm theo Status (Multiple) -->
                <div class="col-2">
                    <div class="form-group">
                        <label>Search Status:</label>
                        <select
                            ref="statusSelect"
                            v-model="filtersQuery.status"
                            class="form-control select2"
                            multiple="multiple"
                            data-placeholder="Select Status"
                            style="width: 100%"
                        >
                            <option
                                v-for="[status, id] in Object.entries(
                                    listStatuses
                                )"
                                :key="id"
                                :value="status"
                            >
                                {{ status }}
                            </option>
                        </select>
                    </div>
                </div>

                <div class="col-4">
                    <div class="form-group" style="display: none">
                        <label>Select Display Column</label>
                        <select
                            id="selectDisplayColumns"
                            class="select2"
                            multiple="multiple"
                            data-placeholder="Select Columns"
                            style="width: 100%"
                        >
                            <option value="epic_task">Epic/Task</option>
                            <option value="priority">Priority</option>
                            <option value="assignee">Assignee</option>
                            <option value="plan_start_date">
                                Plan Start Date
                            </option>
                            <option value="plan_end_date">Plan End Date</option>
                            <option value="actual_start_date">
                                Actual Start Date
                            </option>
                            <option value="actual_end_date">
                                Actual End Date
                            </option>
                            <option value="plan-effort">Plan Effort</option>
                            <option value="actual-effort">Actual Effort</option>
                            <option value="status">Status</option>
                            <option value="action">Action</option>
                        </select>
                    </div>
                </div>

                <!-- Tìm kiếm theo Plan Start Date -->
                <div class="col-3">
                    <div class="form-group">
                        <label>Filter Plan Start Date:</label>
                        <div class="d-flex align-items-center">
                            <!-- Plan Start Date (From) -->
                            <div
                                class="input-group date mr-2"
                                id="planStartDatePickerFrom"
                                data-target-input="nearest"
                            >
                                <input
                                    type="text"
                                    id="planStartDateFrom"
                                    class="form-control datetimepicker-input"
                                    data-target="#planStartDatePickerFrom"
                                    placeholder="From"
                                    v-model="filtersQuery.plan_start_date_from"
                                />
                                <div
                                    class="input-group-append"
                                    data-target="#planStartDatePickerFrom"
                                    data-toggle="datetimepicker"
                                >
                                    <div class="input-group-text">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Dấu "-" -->
                            <span class="mx-2">-</span>

                            <!-- Plan Start Date (To) -->
                            <div
                                class="input-group date ml-2"
                                id="planStartDatePickerTo"
                                data-target-input="nearest"
                            >
                                <input
                                    type="text"
                                    id="planStartDateTo"
                                    class="form-control datetimepicker-input"
                                    data-target="#planStartDatePickerTo"
                                    placeholder="To"
                                    v-model="filtersQuery.plan_start_date_to"
                                />
                                <div
                                    class="input-group-append"
                                    data-target="#planStartDatePickerTo"
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

                <!-- Tìm kiếm theo Plan End Date -->
                <div class="col-3">
                    <div class="form-group">
                        <label>Filter Plan End Date:</label>
                        <div class="d-flex align-items-center">
                            <!-- Plan End Date (From) -->
                            <div
                                class="input-group date mr-2"
                                id="planEndDatePickerFrom"
                                data-target-input="nearest"
                            >
                                <input
                                    type="text"
                                    id="planEndDateFrom"
                                    class="form-control datetimepicker-input"
                                    data-target="#planEndDatePickerFrom"
                                    placeholder="From"
                                    v-model="filtersQuery.plan_end_date_from"
                                />
                                <div
                                    class="input-group-append"
                                    data-target="#planEndDatePickerFrom"
                                    data-toggle="datetimepicker"
                                >
                                    <div class="input-group-text">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Dấu "-" -->
                            <span class="mx-2">-</span>

                            <!-- Plan End Date (To) -->
                            <div
                                class="input-group date ml-2"
                                id="planEndDatePickerTo"
                                data-target-input="nearest"
                            >
                                <input
                                    type="text"
                                    id="planEndDateTo"
                                    class="form-control datetimepicker-input"
                                    data-target="#planEndDatePickerTo"
                                    placeholder="To"
                                    v-model="filtersQuery.plan_end_date_to"
                                />
                                <div
                                    class="input-group-append"
                                    data-target="#planEndDatePickerTo"
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

                <!-- Tìm kiếm theo Actual Start Date -->
                <div class="col-3">
                    <div class="form-group">
                        <label>Filter Actual Start Date:</label>
                        <div class="d-flex align-items-center">
                            <!-- Actual Start Date (From) -->
                            <div
                                class="input-group date mr-2"
                                id="actualStartDatePickerFrom"
                                data-target-input="nearest"
                            >
                                <input
                                    type="text"
                                    id="actualStartDateFrom"
                                    class="form-control datetimepicker-input"
                                    data-target="#actualStartDatePickerFrom"
                                    placeholder="From"
                                    v-model="filtersQuery.actual_start_date_from"
                                />
                                <div
                                    class="input-group-append"
                                    data-target="#actualStartDatePickerFrom"
                                    data-toggle="datetimepicker"
                                >
                                    <div class="input-group-text">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Dấu "-" -->
                            <span class="mx-2">-</span>

                            <!-- Actual Start Date (To) -->
                            <div
                                class="input-group date ml-2"
                                id="actualStartDatePickerTo"
                                data-target-input="nearest"
                            >
                                <input
                                    type="text"
                                    id="actualStartDateTo"
                                    class="form-control datetimepicker-input"
                                    data-target="#actualStartDatePickerTo"
                                    placeholder="To"
                                    v-model="filtersQuery.actual_start_date_to"
                                />
                                <div
                                    class="input-group-append"
                                    data-target="#actualStartDatePickerTo"
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

                <!-- Tìm kiếm theo Actual End Date -->
                <div class="col-3">
                    <div class="form-group">
                        <label>Filter Actual End Date:</label>
                        <div class="d-flex align-items-center">
                            <!-- Actual End Date (From) -->
                            <div
                                class="input-group date mr-2"
                                id="actualEndDatePickerFrom"
                                data-target-input="nearest"
                            >
                                <input
                                    type="text"
                                    id="actualEndDateFrom"
                                    class="form-control datetimepicker-input"
                                    data-target="#actualEndDatePickerFrom"
                                    placeholder="From"
                                    v-model="filtersQuery.actual_end_date_from"
                                />
                                <div
                                    class="input-group-append"
                                    data-target="#actualEndDatePickerFrom"
                                    data-toggle="datetimepicker"
                                >
                                    <div class="input-group-text">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Dấu "-" -->
                            <span class="mx-2">-</span>

                            <!-- Actual End Date (To) -->
                            <div
                                class="input-group date ml-2"
                                id="actualEndDatePickerTo"
                                data-target-input="nearest"
                            >
                                <input
                                    type="text"
                                    id="actualEndDateTo"
                                    class="form-control datetimepicker-input"
                                    data-target="#actualEndDatePickerTo"
                                    placeholder="To"
                                    v-model="filtersQuery.actual_end_date_to"
                                />
                                <div
                                    class="input-group-append"
                                    data-target="#actualEndDatePickerTo"
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
            </div>
            <div class="row">
                <div class="col-2">
                    <button
                        @click="setToCurrentUser"
                        :class="{
                            'btn btn-primary active':
                                filtersQuery.assignee === currentUserAccount,
                            'btn btn-outline-primary':
                                filtersQuery.assignee !== currentUserAccount,
                        }"
                        title="Assign to Me"
                    >
                        🙋‍♂️ Assign to Me
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, nextTick, watch } from "vue";

const props = defineProps({
    tasks: Array,
    filters: Object,
    listAssignee: Object,
    listStatuses: Object,
    listPriorities: Object,
    taskListEditing: Boolean,
    currentUserAccount: String,
});

const searchText = ref("");
const prioritySelect = ref([]);
const assigneeSelect = ref(null);
const statusSelect = ref([]);

const planStartDateFrom = ref(""); // Lọc từ ngày bắt đầu
const planStartDateTo = ref(""); // Lọc đến ngày bắt đầu
const planEndDateFrom = ref(""); // Lọc từ ngày kết thúc
const planEndDateTo = ref(""); // Lọc đến ngày kết thúc

const actualStartDateFrom = ref("");
const actualStartDateTo = ref("");
const actualEndDateFrom = ref("");
const actualEndDateTo = ref("");

const filtersQuery = ref({
    text: "",
    priority: [],
    assignee: "",
    status: [],
    plan_start_date_from: "",
    plan_start_date_to: "",
    plan_end_date_from: "",
    plan_end_date_to: "",
    actual_start_date_from: "",
    actual_start_date_to: "",
    actual_end_date_from: "",
    actual_end_date_to: ""
});

// 1️⃣ Đồng bộ filters từ cha xuống con (chỉ cập nhật khi khác)
watch(
    () => props.filters,
    (newFilters) => {
        filtersQuery.value = {
            text: newFilters.text || "",
            priority: Array.isArray(newFilters.priority)
                ? newFilters.priority
                : [],
            assignee: newFilters.assignee || "",
            status: Array.isArray(newFilters.status) ? newFilters.status : [],
            plan_start_date_from: newFilters.plan_start_date_from || "",
            plan_start_date_to: newFilters.plan_start_date_to || "",
            plan_end_date_from: newFilters.plan_end_date_from || "",
            plan_end_date_to: newFilters.plan_end_date_to || "",
            actual_start_date_from: newFilters.actual_start_date_from || "",
            actual_start_date_to: newFilters.actual_start_date_to || "",
            actual_end_date_from: newFilters.actual_end_date_from || "",
            actual_end_date_to: newFilters.actual_end_date_to || ""
        };
    },
    { deep: true }
);

// 2️⃣ Đồng bộ filtersQuery lên cha (chỉ khi có sự thay đổi)
watch(
    filtersQuery,
    (newQuery) => {
        if (JSON.stringify(newQuery) !== JSON.stringify(props.filters)) {
            emit("filter-changed", { ...newQuery });
        }
    },
    { deep: true }
);

// Watch for changes in date filters
watch(
    [
        planStartDateFrom,
        planStartDateTo,
        planEndDateFrom,
        planEndDateTo,
        actualStartDateFrom,
        actualStartDateTo,
        actualEndDateFrom,
        actualEndDateTo
    ],
    ([
        newPlanStartFrom,
        newPlanStartTo,
        newPlanEndFrom,
        newPlanEndTo,
        newActualStartFrom,
        newActualStartTo,
        newActualEndFrom,
        newActualEndTo
    ]) => {
        filtersQuery.value = {
            ...filtersQuery.value,
            plan_start_date_from: newPlanStartFrom,
            plan_start_date_to: newPlanStartTo,
            plan_end_date_from: newPlanEndFrom,
            plan_end_date_to: newPlanEndTo,
            actual_start_date_from: newActualStartFrom,
            actual_start_date_to: newActualStartTo,
            actual_end_date_from: newActualEndFrom,
            actual_end_date_to: newActualEndTo
        };
    }
);

const updateSearch = () => {
    if (searchText.value !== filtersQuery.value.text) {
        filtersQuery.value.text = searchText.value;
    }
};

onMounted(() => {
    nextTick(() => {
        if (window.jQuery && $.fn.select2 && $.fn.datetimepicker) {
            // Hàm khởi tạo Select2
            const initSelect2 = (selector, placeholder, filterKey) => {
                $(selector)
                    .select2({ placeholder, allowClear: true })
                    .on("change", (e) => {
                        filtersQuery.value[filterKey] = $(e.target).val() || [];
                    })
                    .on("select2:open", () => {
                        setTimeout(
                            () =>
                                $(
                                    ".select2-container--open .select2-search__field"
                                )[0]?.focus(),
                            50
                        );
                    });
            };

            initSelect2(prioritySelect.value, "Select Priority", "priority");
            initSelect2(assigneeSelect.value, "Choose an assignee", "assignee");
            initSelect2(statusSelect.value, "Select Status", "status");

            // Hàm khởi tạo DateTimePicker
            const initDatePicker = (selector, model) => {
                $(selector).datetimepicker({
                    format: "YYYY-MM-DD",
                    useCurrent: false,
                    buttons: {
                        showToday: true,
                        showClear: true,
                        showClose: true,
                    },
                    icons: {
                        today: "fa fa-calendar-day",
                        clear: "fa fa-trash",
                        close: "fa fa-times",
                    },
                });

                $(selector).on("change.datetimepicker", (e) => {
                    model.value = e.date ? e.date.format("YYYY-MM-DD") : "";
                });
            };

            initDatePicker("#planStartDatePickerFrom", planStartDateFrom);
            initDatePicker("#planStartDatePickerTo", planStartDateTo);
            initDatePicker("#planEndDatePickerFrom", planEndDateFrom);
            initDatePicker("#planEndDatePickerTo", planEndDateTo);
            initDatePicker("#actualStartDatePickerFrom", actualStartDateFrom);
            initDatePicker("#actualStartDatePickerTo", actualStartDateTo);
            initDatePicker("#actualEndDatePickerFrom", actualEndDateFrom);
            initDatePicker("#actualEndDatePickerTo", actualEndDateTo);

            // Khởi tạo Select2 cho cột hiển thị
            $("#selectDisplayColumns").select2();
            let defaultColumns = [
                "epic_task",
                "priority",
                "assignee",
                "plan_start_date",
                "plan_end_date",
                "actual_start_date",
                "actual_end_date",
                "plan-effort",
                "actual-effort",
                "status",
                "action",
            ];
            $("#selectDisplayColumns").val(defaultColumns).trigger("change");

            $("#selectDisplayColumns").on(
                "select2:select select2:unselect",
                () => {
                    emit(
                        "updateVisibleColumns",
                        $("#selectDisplayColumns").val() || []
                    );
                }
            );

            emit("updateVisibleColumns", defaultColumns);
        } else {
            console.error("Select2 or DateTimePicker is not loaded properly.");
        }
    });
});

// Theo dõi props.filters để cập nhật filtersQuery
// watch(() => props.filters, (newFilters) => {
//     filtersQuery.value = { ...newFilters }; // Gán lại toàn bộ object
// }, { deep: true });

const emit = defineEmits([
    "updateSelectedAssignee",
    "updateFilteredTasks",
    "blankQuery",
    "updateVisibleColumns",
    "updateSearchQuery",
    "updateSelectedStatuses",
    "filter-changed",
]);

const resetSearchTitle = () => {
    searchText.value = "";
    filtersQuery.value.text = "";
    // Reset all date filters
    planStartDateFrom.value = "";
    planStartDateTo.value = "";
    planEndDateFrom.value = "";
    planEndDateTo.value = "";
    actualStartDateFrom.value = "";
    actualStartDateTo.value = "";
    actualEndDateFrom.value = "";
    actualEndDateTo.value = "";
};

// Khi nhấn vào icon, gán assignee thành currentUserAccount
const setToCurrentUser = () => {
    filtersQuery.value.assignee = props.currentUserAccount;
    $(assigneeSelect.value).val(props.currentUserAccount).trigger("change");
};
</script>

<style scoped>
.disabled-overlay {
    position: relative;
    pointer-events: none; /* Chặn tất cả thao tác */
    opacity: 0.6; /* Làm mờ nội dung */
}
</style>
