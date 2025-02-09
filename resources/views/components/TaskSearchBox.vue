<template>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Search Box</h3>
        </div>

        <div class="card-body">
            <div class="row">
                <!-- Tìm kiếm chung -->
                <div class="col-4">
                    <div class="form-group">
                        <label>Search:</label>
                        <div class="position-relative">
                            <input v-model="searchQuery" type="text" class="form-control" placeholder="Enter name"
                                @input="
                                    filterTasks();
                                emit('updateSearchQuery', searchQuery);
                                " />
                            <button v-if="searchQuery" @click="resetSearchTitle"
                                class="btn btn-sm btn-light position-absolute" style="
                                    right: 10px;
                                    top: 50%;
                                    transform: translateY(-50%);
                                ">
                                ✖
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Tìm kiếm Assignee -->
                <div class="col-4">
                    <div class="form-group">
                        <label>Search Assignee:</label>
                        <select ref="assigneeSelect" class="form-control select2" style="width: 100%">
                            <option value="" selected disabled></option>
                            <option v-for="assignee in uniqueAssignees" :key="assignee" :value="assignee">
                                {{ assignee }}
                            </option>
                        </select>
                    </div>
                </div>

                <!-- Tìm kiếm theo Status (Multiple) -->
                <div class="col-4">
                    <div class="form-group">
                        <label>Search Status:</label>
                        <select ref="statusSelect" class="form-control select2" multiple="multiple"
                            data-placeholder="Select Status" style="width: 100%">
                            <option v-for="status in uniqueStatuses" :key="status" :value="status">
                                {{ status }}
                            </option>
                        </select>
                    </div>
                </div>

                <!-- Tìm kiếm theo Plan Start Date -->
                <div class="col-6">
                    <div class="form-group">
                        <label>Filter Plan Start Date:</label>
                        <div class="d-flex align-items-center">
                            <!-- Plan Start Date (From) -->
                            <div class="input-group date mr-2" id="planStartDatePickerFrom" data-target-input="nearest">
                                <input type="text" id="planStartDateFrom" class="form-control datetimepicker-input"
                                    data-target="#planStartDatePickerFrom" placeholder="From" />
                                <div class="input-group-append" data-target="#planStartDatePickerFrom"
                                    data-toggle="datetimepicker">
                                    <div class="input-group-text">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Dấu "-" -->
                            <span class="mx-2">-</span>

                            <!-- Plan Start Date (To) -->
                            <div class="input-group date ml-2" id="planStartDatePickerTo" data-target-input="nearest">
                                <input type="text" id="planStartDateTo" class="form-control datetimepicker-input"
                                    data-target="#planStartDatePickerTo" placeholder="To" />
                                <div class="input-group-append" data-target="#planStartDatePickerTo"
                                    data-toggle="datetimepicker">
                                    <div class="input-group-text">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tìm kiếm theo Plan End Date -->
                <div class="col-6">
                    <div class="form-group">
                        <label>Filter Plan End Date:</label>
                        <div class="d-flex align-items-center">
                            <!-- Plan End Date (From) -->
                            <div class="input-group date mr-2" id="planEndDatePickerFrom" data-target-input="nearest">
                                <input type="text" id="planEndDateFrom" class="form-control datetimepicker-input"
                                    data-target="#planEndDatePickerFrom" placeholder="From" />
                                <div class="input-group-append" data-target="#planEndDatePickerFrom"
                                    data-toggle="datetimepicker">
                                    <div class="input-group-text">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Dấu "-" -->
                            <span class="mx-2">-</span>

                            <!-- Plan End Date (To) -->
                            <div class="input-group date ml-2" id="planEndDatePickerTo" data-target-input="nearest">
                                <input type="text" id="planEndDateTo" class="form-control datetimepicker-input"
                                    data-target="#planEndDatePickerTo" placeholder="To" />
                                <div class="input-group-append" data-target="#planEndDatePickerTo"
                                    data-toggle="datetimepicker">
                                    <div class="input-group-text">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tìm kiếm theo Actual Start Date -->
                <div class="col-6">
                    <div class="form-group">
                        <label>Filter Actual Start Date:</label>
                        <div class="d-flex align-items-center">
                            <!-- Actual Start Date (From) -->
                            <div class="input-group date mr-2" id="actualStartDatePickerFrom"
                                data-target-input="nearest">
                                <input type="text" id="actualStartDateFrom" class="form-control datetimepicker-input"
                                    data-target="#actualStartDatePickerFrom" placeholder="From" />
                                <div class="input-group-append" data-target="#actualStartDatePickerFrom"
                                    data-toggle="datetimepicker">
                                    <div class="input-group-text">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Dấu "-" -->
                            <span class="mx-2">-</span>

                            <!-- Actual Start Date (To) -->
                            <div class="input-group date ml-2" id="actualStartDatePickerTo" data-target-input="nearest">
                                <input type="text" id="actualStartDateTo" class="form-control datetimepicker-input"
                                    data-target="#actualStartDatePickerTo" placeholder="To" />
                                <div class="input-group-append" data-target="#actualStartDatePickerTo"
                                    data-toggle="datetimepicker">
                                    <div class="input-group-text">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tìm kiếm theo Actual End Date -->
                <div class="col-6">
                    <div class="form-group">
                        <label>Filter Actual End Date:</label>
                        <div class="d-flex align-items-center">
                            <!-- Actual End Date (From) -->
                            <div class="input-group date mr-2" id="actualEndDatePickerFrom" data-target-input="nearest">
                                <input type="text" id="actualEndDateFrom" class="form-control datetimepicker-input"
                                    data-target="#actualEndDatePickerFrom" placeholder="From" />
                                <div class="input-group-append" data-target="#actualEndDatePickerFrom"
                                    data-toggle="datetimepicker">
                                    <div class="input-group-text">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Dấu "-" -->
                            <span class="mx-2">-</span>

                            <!-- Actual End Date (To) -->
                            <div class="input-group date ml-2" id="actualEndDatePickerTo" data-target-input="nearest">
                                <input type="text" id="actualEndDateTo" class="form-control datetimepicker-input"
                                    data-target="#actualEndDatePickerTo" placeholder="To" />
                                <div class="input-group-append" data-target="#actualEndDatePickerTo"
                                    data-toggle="datetimepicker">
                                    <div class="input-group-text">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6">
                    <!-- Select multiple-->
                    <div class="form-group">
                        <label>Select Display Column</label>
                        <select id="selectDisplayColumns" class="select2" multiple="multiple"
                            data-placeholder="Select Columns" style="width: 100%">
                            <option value="epic_task">Epic/Task</option>
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
                            <option value="status">Status</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, nextTick } from "vue";

const assigneeSelect = ref(null);
const statusSelect = ref(null);
const searchQuery = ref("");
const selectedAssignee = ref("");
const selectedStatuses = ref([]); // Mảng lưu nhiều status

const planStartDateFrom = ref(""); // Lọc từ ngày bắt đầu
const planStartDateTo = ref(""); // Lọc đến ngày bắt đầu
const planEndDateFrom = ref(""); // Lọc từ ngày kết thúc
const planEndDateTo = ref(""); // Lọc đến ngày kết thúc

const actualStartDateFrom = ref("");
const actualStartDateTo = ref("");
const actualEndDateFrom = ref("");
const actualEndDateTo = ref("");

const selectColumns = ref(null);

onMounted(() => {
    nextTick(() => {
        if (window.jQuery && $.fn.select2 && $.fn.datetimepicker) {
            // Select2 cho Assignee
            $(assigneeSelect.value)
                .select2({
                    placeholder: "Choose an assignee",
                    allowClear: true,
                })
                .on("change", (e) => {
                    selectedAssignee.value = e.target.value;
                    filterTasks();
                    emit("updateSelectedAssignee", selectedAssignee.value);
                });

            // Select2 cho Status (Multiple)
            $(statusSelect.value)
                .select2({
                    placeholder: "Select Status",
                    allowClear: true,
                })
                .on("change", (e) => {
                    selectedStatuses.value = $(e.target).val() || [];
                    filterTasks();
                    emit("updateSelectedStatuses", selectedStatuses.value);
                });

            // Date picker for Plan Start Date (From)
            $("#planStartDatePickerFrom").datetimepicker({
                format: "YYYY-MM-DD",
                useCurrent: false,
            });
            $("#planStartDatePickerFrom").on(
                "change.datetimepicker",
                function (e) {
                    let newPlanStartDateFrom = e.date
                        ? e.date.format("YYYY-MM-DD")
                        : "";
                    if (newPlanStartDateFrom) {
                        planStartDateFrom.value = newPlanStartDateFrom;
                        filterTasks();
                    } else {
                        planStartDateFrom.value = "";
                        filterTasks();
                    }
                }
            );

            // Date picker for Plan Start Date (To)
            $("#planStartDatePickerTo").datetimepicker({
                format: "YYYY-MM-DD",
                useCurrent: false,
            });
            $("#planStartDatePickerTo").on(
                "change.datetimepicker",
                function (e) {
                    let newPlanStartDateTo = e.date
                        ? e.date.format("YYYY-MM-DD")
                        : "";
                    if (newPlanStartDateTo) {
                        planStartDateTo.value = newPlanStartDateTo;
                        filterTasks();
                    } else {
                        planStartDateTo.value = "";
                        filterTasks();
                    }
                }
            );

            // Date picker for Plan End Date (From)
            $("#planEndDatePickerFrom").datetimepicker({
                format: "YYYY-MM-DD",
                useCurrent: false,
            });
            $("#planEndDatePickerFrom").on(
                "change.datetimepicker",
                function (e) {
                    let newPlanEndDateFrom = e.date
                        ? e.date.format("YYYY-MM-DD")
                        : "";
                    if (newPlanEndDateFrom) {
                        planEndDateFrom.value = newPlanEndDateFrom;
                        filterTasks();
                    } else {
                        planEndDateFrom.value = "";
                        filterTasks();
                    }
                }
            );

            // Date picker for Plan End Date (To)
            $("#planEndDatePickerTo").datetimepicker({
                format: "YYYY-MM-DD",
                useCurrent: false,
            });
            $("#planEndDatePickerTo").on("change.datetimepicker", function (e) {
                let newPlanEndDateTo = e.date
                    ? e.date.format("YYYY-MM-DD")
                    : "";
                if (newPlanEndDateTo) {
                    planEndDateTo.value = newPlanEndDateTo;
                    filterTasks();
                } else {
                    planEndDateTo.value = "";
                    filterTasks();
                }
            });

            // Date picker for Actual Start Date (From)
            $("#actualStartDatePickerFrom").datetimepicker({
                format: "YYYY-MM-DD",
                useCurrent: false,
            });
            $("#actualStartDatePickerFrom").on(
                "change.datetimepicker",
                function (e) {
                    let newActualStartDateFrom = e.date
                        ? e.date.format("YYYY-MM-DD")
                        : "";
                    if (newActualStartDateFrom) {
                        actualStartDateFrom.value = newActualStartDateFrom;
                        filterTasks();
                    } else {
                        actualStartDateFrom.value = "";
                        filterTasks();
                    }
                }
            );

            // Date picker for Actual Start Date (To)
            $("#actualStartDatePickerTo").datetimepicker({
                format: "YYYY-MM-DD",
                useCurrent: false,
            });
            $("#actualStartDatePickerTo").on(
                "change.datetimepicker",
                function (e) {
                    let newActualStartDateTo = e.date
                        ? e.date.format("YYYY-MM-DD")
                        : "";
                    if (newActualStartDateTo) {
                        actualStartDateTo.value = newActualStartDateTo;
                        filterTasks();
                    } else {
                        actualStartDateTo.value = "";
                        filterTasks();
                    }
                }
            );

            // Date picker for Actual End Date (From)
            $("#actualEndDatePickerFrom").datetimepicker({
                format: "YYYY-MM-DD",
                useCurrent: false,
            });
            $("#actualEndDatePickerFrom").on(
                "change.datetimepicker",
                function (e) {
                    let newActualEndDateFrom = e.date
                        ? e.date.format("YYYY-MM-DD")
                        : "";
                    if (newActualEndDateFrom) {
                        actualEndDateFrom.value = newActualEndDateFrom;
                        filterTasks();
                    } else {
                        actualEndDateFrom.value = "";
                        filterTasks();
                    }
                }
            );

            // Date picker for Actual End Date (To)
            $("#actualEndDatePickerTo").datetimepicker({
                format: "YYYY-MM-DD",
                useCurrent: false,
            });
            $("#actualEndDatePickerTo").on(
                "change.datetimepicker",
                function (e) {
                    let newActualEndDateTo = e.date
                        ? e.date.format("YYYY-MM-DD")
                        : "";
                    if (newActualEndDateTo) {
                        actualEndDateTo.value = newActualEndDateTo;
                        filterTasks();
                    } else {
                        actualEndDateTo.value = "";
                        filterTasks();
                    }
                }
            );

            $("#selectDisplayColumns").select2();

            let defaultColumns = [
                "epic_task",
                "assignee",
                "plan_start_date",
                "plan_end_date",
                "status"
            ];
            $("#selectDisplayColumns").val(defaultColumns).trigger("change");

            // Bắt sự kiện change từ select2
            $("#selectDisplayColumns").on(
                "select2:select select2:unselect",
                function () {
                    let selectedColumns = $(this).val() || [];
                    emit("updateVisibleColumns", selectedColumns);
                }
            );

            // Gửi danh sách mặc định khi component được mount
            emit("updateVisibleColumns", defaultColumns);
        } else {
            console.error("Select2 is not loaded properly.");
        }
    });
});

const props = defineProps({
    tasks: Array, // Danh sách tasks đã phẳng từ backend
});

const emit = defineEmits([
    "updateFilteredTasks",
    "blankQuery",
    "updateVisibleColumns",
]);

const uniqueAssignees = computed(() => {
    return Array.from(
        new Set(
            props.tasks.map((task) => task.assignee?.account).filter(Boolean)
        )
    );
});

const uniqueStatuses = computed(() => {
    return Array.from(
        new Set(props.tasks.map((task) => task.status).filter(Boolean))
    );
});

const filterTasks = () => {
    let filtered = props.tasks;

    if (searchQuery.value.trim()) {
        filtered = filtered.filter((task) =>
            task.name.toLowerCase().includes(searchQuery.value.toLowerCase())
        );
    }

    if (selectedAssignee.value) {
        filtered = filtered.filter(
            (task) => task.assignee?.account === selectedAssignee.value
        );
    }

    if (selectedStatuses.value.length > 0) {
        filtered = filtered.filter((task) =>
            selectedStatuses.value.includes(task.status)
        );
    }

    if (planStartDateFrom.value) {
        filtered = filtered.filter(
            (task) => task.plan_start_date >= planStartDateFrom.value
        );
    }

    if (planStartDateTo.value) {
        filtered = filtered.filter(
            (task) => task.plan_start_date <= planStartDateTo.value
        );
    }

    if (planEndDateFrom.value) {
        filtered = filtered.filter(
            (task) => task.plan_end_date >= planEndDateFrom.value
        );
    }

    if (planEndDateTo.value) {
        filtered = filtered.filter(
            (task) => task.plan_end_date <= planEndDateTo.value
        );
    }

    if (actualStartDateFrom.value) {
        filtered = filtered.filter(
            (task) => task.actual_start_date >= actualStartDateFrom.value
        );
    }
    if (actualStartDateTo.value) {
        filtered = filtered.filter(
            (task) => task.actual_start_date <= actualStartDateTo.value
        );
    }
    if (actualEndDateFrom.value) {
        filtered = filtered.filter(
            (task) => task.actual_end_date >= actualEndDateFrom.value
        );
    }
    if (actualEndDateTo.value) {
        filtered = filtered.filter(
            (task) => task.actual_end_date <= actualEndDateTo.value
        );
    }

    if (
        searchQuery.value === "" &&
        selectedAssignee.value === "" &&
        selectedStatuses.value.length === 0 &&
        planStartDateFrom.value === "" &&
        planStartDateTo.value === "" &&
        planEndDateFrom.value === "" &&
        planEndDateTo.value === "" &&
        actualStartDateFrom.value === "" &&
        actualStartDateTo.value === "" &&
        actualEndDateFrom.value === "" &&
        actualEndDateTo.value === ""
    ) {
        emit("blankQuery", true);
    } else {
        emit("blankQuery", false);
    }

    emit("updateFilteredTasks", filtered);
};

const resetSearchTitle = () => {
    searchQuery.value = "";
    filterTasks();
};
</script>
