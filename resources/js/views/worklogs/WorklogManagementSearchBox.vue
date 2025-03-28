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

        <div class="card-body">
            <div class="row">
                <!-- Tìm kiếm chung -->
                <div class="col-4">
                    <div class="form-group">
                        <label>Search:</label>
                        <div class="position-relative">
                            <input
                                v-model="searchQuery"
                                type="text"
                                class="form-control"
                                placeholder="Enter Epic/Task Title"
                                @input="
                                    filterWorklogs();
                                    emit('updateSearchQuery', searchQuery);
                                "
                            />
                            <button
                                v-if="searchQuery"
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

                <!-- Tìm kiếm theo Plan Start Date -->
                <div class="col-6">
                    <div class="form-group">
                        <label>Filter Worklog Date:</label>
                        <div class="d-flex align-items-center">
                            <!-- Plan Start Date (From) -->
                            <div
                                class="input-group date mr-2"
                                id="logDatePickerFrom"
                                data-target-input="nearest"
                            >
                                <input
                                    type="text"
                                    id="logDateFrom"
                                    class="form-control datetimepicker-input"
                                    data-target="#logDatePickerFrom"
                                    placeholder="From"
                                />
                                <div
                                    class="input-group-append"
                                    data-target="#logDatePickerFrom"
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
                                id="logDatePickerTo"
                                data-target-input="nearest"
                            >
                                <input
                                    type="text"
                                    id="logDateTo"
                                    class="form-control datetimepicker-input"
                                    data-target="#logDatePickerTo"
                                    placeholder="To"
                                />
                                <div
                                    class="input-group-append"
                                    data-target="#logDatePickerTo"
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

                <!-- Filter by Logged Datetime -->
                <div class="col-6">
                    <div class="form-group">
                        <label>Filter Logged Datetime:</label>
                        <div class="d-flex align-items-center">
                            <!-- Logged Datetime From -->
                            <div
                                class="input-group date mr-2"
                                id="loggedDatetimePickerFrom"
                                data-target-input="nearest"
                            >
                                <input
                                    type="text"
                                    id="loggedDatetimeFrom"
                                    class="form-control datetimepicker-input"
                                    data-target="#loggedDatetimePickerFrom"
                                    placeholder="From"
                                />
                                <div
                                    class="input-group-append"
                                    data-target="#loggedDatetimePickerFrom"
                                    data-toggle="datetimepicker"
                                >
                                    <div class="input-group-text">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Dấu "-" -->
                            <span class="mx-2">-</span>

                            <!-- Logged Datetime To -->
                            <div
                                class="input-group date ml-2"
                                id="loggedDatetimePickerTo"
                                data-target-input="nearest"
                            >
                                <input
                                    type="text"
                                    id="loggedDatetimeTo"
                                    class="form-control datetimepicker-input"
                                    data-target="#loggedDatetimePickerTo"
                                    placeholder="To"
                                />
                                <div
                                    class="input-group-append"
                                    data-target="#loggedDatetimePickerTo"
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

                <div class="col-sm-6">
                    <!-- Select multiple-->
                    <div class="form-group">
                        <label>Select Display Column</label>
                        <select
                            id="selectDisplayColumns"
                            class="select2"
                            multiple="multiple"
                            data-placeholder="Select Columns"
                            style="width: 100%"
                        >
                            <option value="project-name">Project</option>
                            <option value="epic_task">Epic/Task</option>
                            <option value="assignee">Assignee</option>
                            <option value="plan-effort">Plan Effort</option>
                            <option value="actual-effort">Actual Effort</option>
                            <option value="logged-user">Logged User</option>
                            <option value="logged-date">Worklog Date</option>
                            <option value="logged-time">Worklog Time</option>
                            <option value="created-at">Logged Datetime</option>
                            <option value="description">Description</option>
                            <!-- <option value="action">Action</option> -->
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, nextTick } from "vue";
import moment from "moment";

const searchQuery = ref("");
const logDateFrom = ref("");
const logDateTo = ref("");
const loggedDatetimeFrom = ref("");
const loggedDatetimeTo = ref("");

onMounted(() => {
    nextTick(() => {
        if (window.jQuery && $.fn.select2 && $.fn.datetimepicker) {
            // Set default date range (last 7 days) for logged datetime only
            const today = moment();
            const sevenDaysAgo = moment().subtract(7, 'days');
            
            const formattedToday = today.format('YYYY-MM-DD');
            const formattedSevenDaysAgo = sevenDaysAgo.format('YYYY-MM-DD');

            // Initialize date pickers for Logged Date (without default date)
            $("#logDatePickerFrom").datetimepicker({
                format: "YYYY-MM-DD",
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
                useCurrent: false
            });

            $("#logDatePickerTo").datetimepicker({
                format: "YYYY-MM-DD",
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
                useCurrent: false
            });

            // Initialize datetime pickers for Logged Datetime
            $("#loggedDatetimePickerFrom").datetimepicker({
                format: "YYYY-MM-DD HH:mm:ss",
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
                useCurrent: false,
                defaultDate: sevenDaysAgo
            });

            $("#loggedDatetimePickerTo").datetimepicker({
                format: "YYYY-MM-DD HH:mm:ss",
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
                useCurrent: false,
                defaultDate: today
            });

            // Set input values for Logged Datetime only
            $("#loggedDatetimeFrom").val(sevenDaysAgo.format('YYYY-MM-DD HH:mm:ss'));
            $("#loggedDatetimeTo").val(today.format('YYYY-MM-DD HH:mm:ss'));

            // Set reactive variables for logged datetime only
            loggedDatetimeFrom.value = sevenDaysAgo.format('YYYY-MM-DD HH:mm:ss');
            loggedDatetimeTo.value = today.format('YYYY-MM-DD HH:mm:ss');

            // Apply initial filter with default dates
            filterWorklogs();

            // Event handlers for Logged Date changes
            $("#logDatePickerFrom").on("change.datetimepicker", function (e) {
                let newDateFrom = e.date ? e.date.format("YYYY-MM-DD") : "";
                if (newDateFrom) {
                    if (logDateTo.value && newDateFrom > logDateTo.value) {
                        toastr.warning('"From" date cannot be greater than "To" date');
                        return;
                    }
                    logDateFrom.value = newDateFrom;
                    filterWorklogs();
                } else {
                    logDateFrom.value = "";
                    filterWorklogs();
                }
            });

            $("#logDatePickerTo").on("change.datetimepicker", function (e) {
                let newDateTo = e.date ? e.date.format("YYYY-MM-DD") : "";
                if (newDateTo) {
                    if (logDateFrom.value && newDateTo < logDateFrom.value) {
                        toastr.warning('"To" date cannot be less than "From" date');
                        return;
                    }
                    logDateTo.value = newDateTo;
                    filterWorklogs();
                } else {
                    logDateTo.value = "";
                    filterWorklogs();
                }
            });

            // Event handlers for Logged Datetime changes
            $("#loggedDatetimePickerFrom").on("change.datetimepicker", function (e) {
                let newDatetimeFrom = e.date ? e.date.format("YYYY-MM-DD HH:mm:ss") : "";
                if (newDatetimeFrom) {
                    if (loggedDatetimeTo.value && newDatetimeFrom > loggedDatetimeTo.value) {
                        toastr.warning('"From" datetime cannot be greater than "To" datetime');
                        return;
                    }
                    loggedDatetimeFrom.value = newDatetimeFrom;
                    filterWorklogs();
                } else {
                    loggedDatetimeFrom.value = "";
                    filterWorklogs();
                }
            });

            $("#loggedDatetimePickerTo").on("change.datetimepicker", function (e) {
                let newDatetimeTo = e.date ? e.date.format("YYYY-MM-DD HH:mm:ss") : "";
                if (newDatetimeTo) {
                    if (loggedDatetimeFrom.value && newDatetimeTo < loggedDatetimeFrom.value) {
                        toastr.warning('"To" datetime cannot be less than "From" datetime');
                        return;
                    }
                    loggedDatetimeTo.value = newDatetimeTo;
                    filterWorklogs();
                } else {
                    loggedDatetimeTo.value = "";
                    filterWorklogs();
                }
            });

            $("#selectDisplayColumns").select2();

            let defaultColumns = [
                "project-name",
                "epic_task",
                "logged-user",
                "logged-date",
                "logged-time",
                "created-at",
                "description",
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
    worklogs: Array, // Danh sách tasks đã phẳng từ backend
});

const emit = defineEmits([
    "updateFilteredWorklogs",
    "updateSearchQuery",
    "blankQuery",
    "updateVisibleColumns",
]);

const filterWorklogs = () => {
    let filtered = props.worklogs;

    if (searchQuery.value.trim()) {
        const query = searchQuery.value.toLowerCase();
        filtered = filtered.filter(
            (worklog) =>
                worklog.task?.name?.toLowerCase().includes(query) ||
                worklog.description?.toLowerCase().includes(query)
        );
    }

    // Filter by Logged Date
    if (logDateFrom.value) {
        filtered = filtered.filter(
            (worklog) => worklog.log_date >= logDateFrom.value
        );
    }

    if (logDateTo.value) {
        filtered = filtered.filter(
            (worklog) => worklog.log_date <= logDateTo.value
        );
    }

    // Filter by Logged Datetime
    if (loggedDatetimeFrom.value) {
        filtered = filtered.filter(
            (worklog) => moment(worklog.created_at).format('YYYY-MM-DD HH:mm:ss') >= loggedDatetimeFrom.value
        );
    }

    if (loggedDatetimeTo.value) {
        filtered = filtered.filter(
            (worklog) => moment(worklog.created_at).format('YYYY-MM-DD HH:mm:ss') <= loggedDatetimeTo.value
        );
    }

    if (
        searchQuery.value === "" &&
        logDateFrom.value === "" &&
        logDateTo.value === "" &&
        loggedDatetimeFrom.value === "" &&
        loggedDatetimeTo.value === ""
    ) {
        emit("blankQuery", true);
    } else {
        emit("blankQuery", false);
    }

    emit("updateFilteredWorklogs", filtered);
};

const resetSearchTitle = () => {
    searchQuery.value = "";
    filterWorklogs();
};
</script>
