<template>
    <div class="card card-success collapsed-card">
        <div class="card-header">
            <h3 class="card-title">Upload Tasks via CSV</h3>
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
                <div class="col-md-9">
                    <!-- CSV Import Guide -->
                    <div class="card mt-3">
                        <div class="card-header">
                            <h5>📌 CSV Import Guide</h5>
                        </div>
                        <div class="card-body">
                            <p><strong>General Requirements:</strong></p>
                            <ul>
                                <li>
                                    The file
                                    <strong>must be a UTF-8 encoded CSV</strong>
                                </li>
                                <li><strong>epic</strong> is required</li>
                                <li><strong>task</strong> is optional.</li>
                                <ul>
                                    <li>
                                        If left blank, it will be recognized as
                                        an <strong>epic</strong>
                                    </li>
                                    <li>
                                        Otherwise, it will be considered a
                                        <strong>task</strong>
                                    </li>
                                </ul>
                                <li>
                                    <strong>priority</strong> is required and
                                    must be one of the following (ordered from
                                    highest to lowest):
                                </li>
                                <ul>
                                    <li>
                                        Blocker, Critical, Highest, Higher,
                                        High, Minor, Low, Lower, Lowest, Trivial
                                    </li>
                                </ul>
                                <li>
                                    <strong>status</strong> is optional and must
                                    be one of the following:
                                </li>
                                <ul>
                                    <li>
                                        Open, In Progress, Resolved, Feedback,
                                        Done, Reopen, Pending, Canceled
                                    </li>
                                </ul>
                                <li>
                                    <strong>assignee</strong> is optional and
                                    must be a project member’s account
                                </li>
                                <li>The following fields are optional:</li>
                                <ul>
                                    <li>
                                        description, memo, plan_start_date,
                                        plan_end_date, actual_start_date,
                                        actual_end_date
                                    </li>
                                </ul>
                            </ul>

                            <label><strong>Example CSV File:</strong></label>
                            <pre class="bg-light p-3">
epic,task,priority,assignee,status,description,memo,plan_start_date,plan_end_date,actual_start_date,actual_end_date
Project A,,Blocker,john.doe@example.com,Open,Main epic for project A,Initial phase,2024-03-01,2024-04-01,,
Project A,Task 1,High,jane.doe@example.com,In Progress,Design the UI,,2024-03-02,2024-03-15,,
Project A,Task 2,Low,john.doe@example.com,Open,Set up database,,2024-03-05,2024-03-20,,
Project B,,Critical,,Open,Main epic for project B,Second phase,2024-04-01,2024-05-01,,
        </pre
                            >
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="fileInput"
                            >Select <span style="color: red">UTF-8</span> CSV
                            File <span style="color: red">*</span></label
                        >
                        <div class="input-group">
                            <div class="custom-file">
                                <input
                                    type="file"
                                    class="custom-file-input"
                                    id="fileInput"
                                    accept=".csv"
                                    :disabled="isLoading"
                                    @change="handleFileChange"
                                />
                                <label
                                    class="custom-file-label"
                                    for="fileInput"
                                    >{{ fileName }}</label
                                >
                            </div>
                        </div>
                        <p v-if="csvMessage" style="color: green">
                            {{ csvMessage }}
                        </p>
                        <ul v-if="validationErrors.length">
                            <li
                                v-for="(error, index) in validationErrors"
                                :key="index"
                                style="color: red"
                            >
                                {{ error }}
                            </li>
                        </ul>
                    </div>
                    <span v-if="isLoading">
                        <i class="fas fa-spinner fa-spin"></i> Processing... ({{
                            processedRecords
                        }}/{{ totalRecords }})
                    </span>
                    <button
                        v-else
                        type="button"
                        class="btn btn-primary"
                        @click="submitFile"
                        :disabled="
                            !selectedFile ||
                            isLoading ||
                            validationErrors.length > 0
                        "
                    >
                        Submit Tasks
                    </button>
                    <hr />
                    <div class="form-group">
                        <label>Example CSV File:</label>
                        <div>
                            <a
                                href="https://drive.google.com/uc?export=download&id=1j_dsdKesD3Fvo2Sq_JGg2LccXFBQQlTl"
                                class="btn btn-primary"
                                download
                            >
                                Download
                            </a>
                        </div>
                        <p style="margin-top: 10px">
                            Please ensure your CSV follows the required format.
                        </p>
                    </div>

                    <div class="form-group">
                        <label>Example Excel File:</label>
                        <div>
                            <a
                                href="https://drive.google.com/uc?export=download&id=1C9ylEDUAl27nu4qwQDD9ZUbOHxnXsKrr"
                                class="btn btn-primary"
                                download
                            >
                                Download
                            </a>
                        </div>
                        <p style="margin-top: 10px">
                            Please make sure to convert your Excel file to UTF-8
                            CSV before importing.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from "vue";
import axios from "axios";
import toastr from "toastr";
import dayjs from "dayjs";

const selectedFile = ref(null);
const fileName = ref("Choose file");
const validationErrors = ref([]);
const tasks = ref([]);
const isLoading = ref(false);
const processedRecords = ref(0);
const totalRecords = ref(0);
const csvMessage = ref("");
const epicMap = ref({});

// Map priority và status sang số
const statusMap = {
    Open: 0,
    "In Progress": 1,
    Resolved: 2,
    Feedback: 3,
    Done: 4,
    Reopen: 5,
    Pending: 6,
    Canceled: 7,
};
const priorityMap = {
    Trivial: 0,
    Lowest: 1,
    Lower: 2,
    Low: 3,
    Minor: 4,
    High: 5,
    Higher: 6,
    Highest: 7,
    Critical: 8,
    Blocker: 9,
};

const props = defineProps({
    projectId: {
        type: [String, Number],
        required: true,
    },
    listAssignee: {
        type: [String, Array],
        required: true,
    },
    currentUserId: {
        type: [String, Number],
        required: true,
    },
});

const emit = defineEmits(["update-task"]);

const parsedListAssignee = computed(() => {
    const list =
        typeof props.listAssignee === "string"
            ? JSON.parse(props.listAssignee)
            : props.listAssignee;
    return list.reduce((acc, user) => {
        acc[user.account] = user.id;
        return acc;
    }, {});
});

const handleFileChange = () => {
    const file = event.target.files[0];
    if (!file) {
        fileName.value = "Choose file";
        selectedFile.value = null;
        return;
    }

    if (!file.name.endsWith(".csv")) {
        toastr.error("Only CSV files are allowed!");
        fileName.value = "Choose file";
        selectedFile.value = null;
        event.target.value = "";
        return;
    }

    fileName.value = file.name;
    selectedFile.value = file;

    const reader = new FileReader();
    reader.onload = (e) => processCsv(e.target.result, event);
    reader.readAsText(file);
};

const processCsv = (csvText) => {
    validationErrors.value = [];
    tasks.value = [];
    epicMap.value = {};

    const lines = csvText
        .trim()
        .split("\n")
        .map((line) => line.split(",").map((cell) => cell.trim()));
    if (lines.length < 2) {
        validationErrors.value.push(
            "⚠️ CSV file must have at least one data row."
        );
        return;
    }

    const headers = lines[0];
    const requiredHeaders = ["epic", "task", "priority"];

    if (!requiredHeaders.every((h) => headers.includes(h))) {
        validationErrors.value.push(
            `⚠️ CSV file must contain: ${requiredHeaders.join(", ")}.`
        );
        return;
    }

    totalRecords.value = lines.length - 1;

    lines.slice(1).forEach((row, index) => {
        const lineNumber = index + 2;
        const rowData = Object.fromEntries(
            headers.map((header, i) => [header, row[i] || null])
        );

        const { epic, task, priority } = rowData;

        const assignee = rowData.assignee || null;
        const status = rowData.status || "Open"; // Nếu không có, mặc định là "Open"
        const description = rowData.description || null;
        const memo = rowData.memo || null;
        const planStartDate = rowData.plan_start_date || null;
        const actualStartDate = rowData.actual_start_date || null;
        const planEndDate = rowData.plan_end_date || null;
        const actualEndDate = rowData.actual_end_date || null;

        // Kiểm tra dữ liệu hợp lệ
        if (!epic || !priority) {
            validationErrors.value.push(
                `⚠️ Line ${lineNumber} is missing required fields (epic, priority).`
            );
            return;
        }

        if (!priorityMap.hasOwnProperty(priority)) {
            validationErrors.value.push(
                `⚠️ Invalid priority at line ${lineNumber}: "${priority}".`
            );
            return;
        }

        if (!statusMap.hasOwnProperty(status)) {
            validationErrors.value.push(
                `⚠️ Invalid status at line ${lineNumber}: "${status}".`
            );
            return;
        }

        if (!parsedListAssignee.value.hasOwnProperty(assignee)) {
            validationErrors.value.push(
                `⚠️ Assignee "${assignee}" at line ${lineNumber} not found.`
            );
            return;
        }

        if (validationErrors.value.length == 0) {
            csvMessage.value = `The CSV file is valid. It will import ${totalRecords.value} tasks.`;
        }

        let assigneeId;
        if (assignee != null) {
            assigneeId = parsedListAssignee.value[assignee];
        } else {
            assigneeId = props.currentUserId;
        }

        if (epic && !task) {
            // Nếu chỉ có Epic, tạo Epic
            tasks.value.push({
                name: epic,
                type: 0,
                parent_id: null,
                assignee: assigneeId,
                priority: priorityMap[priority],
                status: statusMap[status],
                description: description,
                memo: memo,
                plan_start_date: planStartDate,
                plan_end_date: planEndDate,
                actual_start_date: actualStartDate,
                actual_end_date: actualEndDate,
                created_by: props.currentUserId,
            });
        } else if (epic && task) {
            // Nếu có cả Epic và Task, kiểm tra xem Epic đã tồn tại chưa
            tasks.value.push({
                name: task,
                type: 1,
                parent_name: epic, // Giữ lại tên Epic để sau này tìm ID
                parent_id: null,
                assignee: assigneeId,
                priority: priorityMap[priority],
                status: statusMap[status],
                description: description,
                memo: memo,
                plan_start_date: planStartDate,
                plan_end_date: planEndDate,
                actual_start_date: actualStartDate,
                actual_end_date: actualEndDate,
                created_by: props.currentUserId,
            });
        }
    });
};

const submitFile = async () => {
    if (isLoading.value) return;
    isLoading.value = true;
    processedRecords.value = 0;

    try {
        // 1️⃣ Gửi danh sách Epic trước và lưu vào epicMap
        const epicMap = {};

        for (const task of tasks.value.filter((t) => t.type === 0)) {
            const requestData = {
                project_id: props.projectId,
                name: task.name,
                type: "epic",
                parent_id: null,
                assignee: task.assignee,
                priority: task.priority,
                status: task.status,
                description: task.description,
                memo: task.memo,
                created_by: task.created_by,
            };

            if (task.plan_start_date) {
                requestData.plan_start_date = dayjs(
                    task.plan_start_date,
                    "M/D/YYYY"
                ).format("YYYY-MM-DD");
            }

            if (task.plan_end_date) {
                requestData.plan_end_date = dayjs(
                    task.plan_end_date,
                    "M/D/YYYY"
                ).format("YYYY-MM-DD");
            }

            if (task.actual_start_date) {
                requestData.actual_start_date = dayjs(
                    task.actual_start_date,
                    "M/D/YYYY"
                ).format("YYYY-MM-DD");
            }

            if (task.actual_end_date) {
                requestData.actual_end_date = dayjs(
                    task.actual_end_date,
                    "M/D/YYYY"
                ).format("YYYY-MM-DD");
            }

            const response = await axios.post(
                `/api/pm/${props.projectId}/tasks/store`,
                requestData
            );
            processedRecords.value++;

            // Lưu Epic ID theo tên để dùng sau
            epicMap[task.name] = response.data.task.id;
        }

        // 2️⃣ Gửi danh sách Task sau khi có Epic ID
        for (const task of tasks.value.filter((t) => t.type === 1)) {
            const epicId = epicMap[task.parent_name];

            if (!epicId) {
                toastr.error(
                    `⚠️ Epic "${task.parent_name}" not found, cannot create task "${task.name}".`
                );
                continue;
            }

            const requestData = {
                project_id: props.projectId,
                name: task.name,
                type: "task",
                parent_id: epicId,
                assignee: task.assignee,
                priority: task.priority,
                status: task.status,
                description: task.description,
                memo: task.memo,
                created_by: task.created_by,
            };

            if (task.plan_start_date) {
                requestData.plan_start_date = dayjs(
                    task.plan_start_date,
                    "M/D/YYYY"
                ).format("YYYY-MM-DD");
            }

            if (task.plan_end_date) {
                requestData.plan_end_date = dayjs(
                    task.plan_end_date,
                    "M/D/YYYY"
                ).format("YYYY-MM-DD");
            }

            if (task.actual_start_date) {
                requestData.actual_start_date = dayjs(
                    task.actual_start_date,
                    "M/D/YYYY"
                ).format("YYYY-MM-DD");
            }

            if (task.actual_end_date) {
                requestData.actual_end_date = dayjs(
                    task.actual_end_date,
                    "M/D/YYYY"
                ).format("YYYY-MM-DD");
            }

            await axios.post(
                `/api/pm/${props.projectId}/tasks/store`,
                requestData
            );
            processedRecords.value++;
        }
        toastr.success(processedRecords.value + " tasks created successfully!");

        // Emit để component cha xử lý
        emit("update-task", true);
    } catch (error) {
        // Lấy thông tin lỗi từ response
        const errorMessage =
            error.response?.data?.message || "Failed to create task!";
        const errorDetail = error.response?.data?.error || "Unknown error";

        // Hiển thị toastr lỗi với cả message và error detail
        toastr.error(`${errorMessage}: ${errorDetail}`);
    } finally {
        isLoading.value = false;
        csvMessage.value = "";
        resetFileInput();
    }
};

const resetFileInput = (event) => {
    fileName.value = "Choose file";
    selectedFile.value = null;
};
</script>

<style scoped>
.custom-file-input {
    cursor: pointer;
}
</style>
