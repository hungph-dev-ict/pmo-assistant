<template>
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
Epic coding,,Blocker,PhamPH,Open,Main epic for coding,Initial phase,2024-03-01,2024-05-01,,
Epic coding,Task 1,High,HoangHT,In Progress,Coding frontend,,2024-03-02,2024-03-15,,
Epic coding,Task 2,Higher,NhatHP,In Progress,Coding frontend,,2024-04-02,2024-04-15,,
Epic database,,Critical,,Open,Main epic for project B,Second phase,2024-04-01,2024-05-01,,
Epic database,Task 3,Low,MinhTH,Open,Set up database,,2024-04-05,2024-04-20,,
        </pre>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="fileInput">Select <span style="color: red">UTF-8</span> CSV
                    File <span style="color: red">*</span></label>
                <div class="input-group">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="fileInput" accept=".csv" :disabled="isLoading"
                            @change="handleFileChange" ref="fileInputRef"/>
                        <label class="custom-file-label" for="fileInput">{{ fileName }}</label>
                    </div>
                </div>
                <p v-if="csvMessage" style="color: green">
                    {{ csvMessage }}
                </p>
                <ul v-if="validationErrors.length">
                    <li v-for="(error, index) in validationErrors" :key="index" style="color: red">
                        {{ error }}
                    </li>
                </ul>
            </div>
            <span v-if="isLoading">
                <i class="fas fa-spinner fa-spin"></i> Processing... ({{
                    processedRecords
                }}/{{ totalRecords }})
            </span>
            <button v-else type="button" class="btn btn-primary" @click="submitFile" :disabled="!selectedFile ||
                isLoading ||
                validationErrors.length > 0
                ">
                Submit Tasks
            </button>
            <hr />
            <div class="form-group">
                <label>Example CSV File:</label>
                <div>
                    <a href="https://drive.google.com/uc?export=download&id=1j_dsdKesD3Fvo2Sq_JGg2LccXFBQQlTl"
                        class="btn btn-primary" download>
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
                    <a href="https://drive.google.com/uc?export=download&id=1C9ylEDUAl27nu4qwQDD9ZUbOHxnXsKrr"
                        class="btn btn-primary" download>
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
const fileInputRef = ref(null);

const props = defineProps({
    projectId: {
        type: [String, Number],
        required: true,
    },
    listAssignee: Object,
    listPriorities: Object,
    listStatuses: Object,
    currentUserId: {
        type: [String, Number],
        required: true,
    },
});

const emit = defineEmits(["update-task"]);

const handleFileChange = (event) => {
    const file = event.target.files[0];

    if (!file) {
        resetFileInput();
        return;
    }

    if (!file.name.endsWith(".csv")) {
        toastr.error("Only CSV files are allowed!");
        resetFileInput();
        return;
    }

    fileName.value = file.name;
    selectedFile.value = file;

    const reader = new FileReader();
    reader.onload = (e) => processCsv(e.target.result);
    reader.readAsText(file);
};

const resetFileInput = () => {
    fileName.value = "Choose file";
    selectedFile.value = null;

    if (fileInputRef.value) {
        fileInputRef.value.value = ""; // Reset input file
    }
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

        if (!props.listPriorities.hasOwnProperty(priority)) {
            validationErrors.value.push(
                `⚠️ Invalid priority at line ${lineNumber}: "${priority}".`
            );
            return;
        }

        if (!props.listStatuses.hasOwnProperty(status)) {
            validationErrors.value.push(
                `⚠️ Invalid status at line ${lineNumber}: "${status}".`
            );
            return;
        }

        if (!props.listAssignee.hasOwnProperty(assignee)) {
            validationErrors.value.push(
                `⚠️ Assignee "${assignee}" at line ${lineNumber} not found.`
            );
            return;
        }

        if (validationErrors.value.length == 0) {
            csvMessage.value = `The CSV file is valid. It will import ${totalRecords.value} tasks.`;
        }

        let assigneeId = assignee != null ? props.listAssignee[assignee] : props.currentUserId;

        if (epic && !task) {
            // Nếu chỉ có Epic, tạo Epic
            tasks.value.push({
                name: epic,
                type: 0,
                parent_id: null,
                assignee: assigneeId,
                priority: priority,
                status: status,
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
                priority: priority,
                status: status,
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
                return;
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
</script>

<style scoped>
.custom-file-input {
    cursor: pointer;
}
</style>
