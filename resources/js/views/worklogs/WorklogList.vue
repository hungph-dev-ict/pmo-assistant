<template>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">List My Worklog</h3>
        </div>

        <div class="card-body">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th
                            v-if="isColumnVisible('project-name')"
                            style="width: 12%"
                        >
                            Project
                        </th>
                        <th
                            v-if="isColumnVisible('epic_task')"
                            style="width: 27%"
                        >
                            Epic/Task
                        </th>
                        <th
                            v-if="isColumnVisible('plan-effort')"
                            style="width: 5%"
                        >
                            Plan Effort
                        </th>
                        <th
                            v-if="isColumnVisible('actual-effort')"
                            style="width: 5%"
                        >
                            Actual Effort
                        </th>
                        <th
                            v-if="isColumnVisible('logged-date')"
                            style="width: 8%"
                        >
                            Logged Date
                        </th>
                        <th
                            v-if="isColumnVisible('logged-time')"
                            style="width: 5%"
                        >
                            Logged Time
                        </th>
                        <th
                            v-if="isColumnVisible('description')"
                            style="width: 23%"
                        >
                            Description
                        </th>
                        <th
                            class="text-center"
                            v-if="isColumnVisible('action')"
                            style="width: 15%"
                        >
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <template
                        v-for="worklog in visibleWorklogs"
                        :key="worklog.id"
                    >
                        <tr class="bg-light">
                            <td v-if="isColumnVisible('project-name')">
                                <a
                                    v-if="worklog.task?.project?.id"
                                    :href="`/pm/${worklog.task.project.id}/task`"
                                    class="text-blue-500 hover:underline"
                                >
                                    {{ worklog.task?.project?.name || 'N/A' }}
                                </a>
                                <span v-else>{{ worklog.task?.project?.name || 'N/A' }}</span>
                            </td>
                            <td v-if="isColumnVisible('epic_task')">
                                <a
                                    v-if="worklog.task?.project?.id && worklog.task?.id"
                                    :href="`/${worklog.task.project.id}/task/${worklog.task.id}`"
                                    class="text-blue-500 hover:underline"
                                >
                                    {{ worklog.task?.name || 'N/A' }}
                                </a>
                                <span v-else>{{ worklog.task?.name || 'N/A' }}</span>
                            </td>
                            <td v-if="isColumnVisible('plan-effort')">
                                {{ worklog.task?.plan_effort || 'N/A' }}
                            </td>
                            <td v-if="isColumnVisible('actual-effort')">
                                {{ worklog.task?.actual_effort || 'N/A' }}
                            </td>
                            <td v-if="isColumnVisible('logged-date')">
                                <span v-if="!worklog.isEditing">{{
                                    worklog.log_date || 'N/A'
                                }}</span>
                                <div
                                    v-else
                                    class="input-group date log-date-datepicker"
                                    data-target-input="nearest"
                                >
                                    <input
                                        type="text"
                                        class="form-control datetimepicker-input"
                                        v-model="worklog.editedLogDate"
                                        data-target=".log-date-datepicker"
                                    />
                                    <div
                                        class="input-group-append"
                                        data-target=".log-date-datepicker"
                                        data-toggle="datetimepicker"
                                    >
                                        <div class="input-group-text">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td v-if="isColumnVisible('logged-time')">
                                <span v-if="!worklog.isEditing">{{
                                    worklog.log_time || 'N/A'
                                }}</span>
                                <input
                                    v-else
                                    type="number"
                                    v-model="worklog.editedLogTime"
                                    class="form-control"
                                />
                            </td>
                            <td v-if="isColumnVisible('description')">
                                <span v-if="!worklog.isEditing">{{
                                    worklog.description || 'N/A'
                                }}</span>
                                <textarea
                                    v-else
                                    type="text"
                                    v-model="worklog.editedDescription"
                                    class="form-control form-control"
                                    rows="3"
                                ></textarea>
                            </td>
                            <td
                                v-if="isColumnVisible('action')"
                                class="text-center"
                            >
                                <template v-if="!worklog.isEditing">
                                   <!--  <a
                                        class="btn btn-info btn-sm mr-2"
                                        href="#"
                                        @click.prevent="editWorklog(worklog)"
                                    >
                                        <i class="fas fa-pencil-alt"></i> Edit
                                    </a> -->
                                    <a
                                        class="btn btn-danger btn-sm mr-2"
                                        href="#"
                                        @click="confirmDelete(worklog)"
                                    >
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                </template>

                                <template v-else>
                                    <a
                                        class="btn btn-success btn-sm mr-2"
                                        href="#"
                                        @click.prevent="updateWorklog(worklog)"
                                    >
                                        <i class="fas fa-save"></i> Update
                                    </a>
                                    <a
                                        class="btn btn-secondary btn-sm"
                                        href="#"
                                        @click.prevent="cancelEdit(worklog)"
                                    >
                                        <i class="fas fa-times"></i> Cancel
                                    </a>
                                </template>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup>
import { computed, ref, nextTick } from "vue";
import Swal from "sweetalert2";

const props = defineProps({
    filteredWorklogs: Array,
    blankQuery: Boolean,
    visibleColumns: Array,
});

const globalIsEditting = ref(false);

const isBlankQuery = computed(() => props.blankQuery ?? true);
const visibleWorklogs = computed(() => props.filteredWorklogs);

const isColumnVisible = (column) => {
    return props.visibleColumns.includes(column);
};

const editWorklog = (worklog) => {
    if (globalIsEditting.value) {
        toastr.error(
            "Other worklog edit is in progress. Please cancel it before edit other."
        );
        return;
    }
    globalIsEditting.value = true;
    worklog.isEditing = true;
    worklog.editedLogDate = worklog.log_date;
    worklog.editedLogTime = worklog.log_time;
    worklog.editedDescription = worklog.description;

    nextTick(initPlugins(worklog));
};

const initPlugins = (worklog) => {
    nextTick(() => {
        $(".log-date-datepicker").datetimepicker({
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
        });

        $(".log-date-datepicker").on("change.datetimepicker", function (e) {
            let newDate = e.date ? e.date.format("YYYY-MM-DD") : e.target.value || "";
            worklog.editedLogDate = newDate;
        });
    });
};

const emit = defineEmits(["update-worklog"]);

const updateWorklog = async (worklog) => {
    try {
        const url = `/api/worklog/${worklog.id}/update`;
        await axios.put(url, {
            log_date: worklog.editedLogDate,
            log_time: worklog.editedLogTime,
            description: worklog.editedDescription,
        });

        toastr.success("Updated worklog successfully!");
        worklog.isEditing = false;
        globalIsEditting.value = false;
        emit("update-worklog");
    } catch (error) {
        const errorMessage = error.response?.data?.message || "Failed to update worklog!";
        const errorDetail = error.response?.data?.error || "Unknown error";
        toastr.error(`${errorMessage}: ${errorDetail}`);
    }
};

const cancelEdit = (worklog) => {
    Object.assign(worklog, worklog.originalData);
    worklog.isEditing = false;
    globalIsEditting.value = false;
};

const confirmDelete = async (worklog) => {
    const result = await Swal.fire({
        title: "Bạn có chắc chắn muốn xoá worklog này?",
        text: "Thao tác này không thể hoàn tác.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "OK",
        cancelButtonText: "Hủy",
    });

    if (result.isConfirmed) {
        softDelete(worklog.id);
    }
};

const softDelete = async (worklogId) => {
    try {
        const url = `/api/worklog/${worklogId}/destroy`;
        await axios.delete(url);
        toastr.success("Worklog deleted successfully!");
        emit("update-worklog");
    } catch (error) {
        const errorMessage = error.response?.data?.message || "Failed to delete worklog!";
        const errorDetail = error.response?.data?.error || "Unknown error";
        toastr.error(`${errorMessage}: ${errorDetail}`);
    }
};
</script>
