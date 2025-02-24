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
                <div class="col-md-6">
                    <div class="form-group col-md-8">
                        <label for="fileInput"
                            >Select CSV File
                            <span style="color: red">*</span></label
                        >
                        <div class="input-group">
                            <div class="custom-file">
                                <input
                                    type="file"
                                    class="custom-file-input"
                                    id="fileInput"
                                    @change="handleFileChange"
                                />
                                <label
                                    class="custom-file-label"
                                    for="fileInput"
                                    >{{ fileName }}</label
                                >
                            </div>
                        </div>
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
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Example CSV File:</label>
                        <div>
                            <a
                                href="https://drive.google.com/file/d/1j_dsdKesD3Fvo2Sq_JGg2LccXFBQQlTl/view?usp=sharing"
                                target="_blank"
                                class="btn btn-primary"
                            >
                                Download
                            </a>
                        </div>
                        <p style="margin-top: 10px">
                            Please ensure your CSV follows the required format.
                        </p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Example Excel File:</label>
                        <div>
                            <a
                                href="https://docs.google.com/spreadsheets/d/1C9ylEDUAl27nu4qwQDD9ZUbOHxnXsKrr/edit?usp=sharing&ouid=115586820982859937661&rtpof=true&sd=true"
                                target="_blank"
                                class="btn btn-primary"
                            >
                                Download
                            </a>
                        </div>
                        <p style="margin-top: 10px">
                            Please make sure to convert your Excel file to CSV before importing.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <span v-if="isLoading">
                <i class="fas fa-spinner fa-spin"></i> Processing...
            </span>
            <button
                v-else
                type="button"
                class="btn btn-primary"
                @click="submitFile"
                :disabled="!selectedFile || isLoading"
            >
                Submit Tasks
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from "vue";
import axios from "axios";
import toastr from "toastr";

const selectedFile = ref(null);
const fileName = ref("Choose file");
const validationErrors = ref([]);
const tasks = ref([]);
const isLoading = ref(false);
const epicMap = ref({}); // Lưu tên Epic -> ID đã tạo

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

// Emit sự kiện update để thông báo lên component cha
const emit = defineEmits(["update-task"]);

// Chuyển đổi listAssignee thành object { account: id }
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

// Map priority và status sang số
const statusMap = {
    Open: 0,
    "In Progress": 1,
    Resolved: 2,
    Feedback: 3,
    Done: 4,
    Reopen: 5,
};
const priorityMap = { "Pending": 0, Low: 1, Medium: 2, High: 3, Critical: 4 };

const handleFileChange = (event) => {
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
    reader.onload = (e) => processCsv(e.target.result);
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
    const requiredHeaders = ["epic", "task", "assignee", "priority"];

    if (!requiredHeaders.every((h) => headers.includes(h))) {
        validationErrors.value.push(
            `⚠️ CSV file must contain: ${requiredHeaders.join(", ")}.`
        );
        return;
    }

    lines.slice(1).forEach((row, index) => {
        const lineNumber = index + 2;
        const rowData = Object.fromEntries(
            headers.map((header, i) => [header, row[i] || null])
        );

        const { epic, task, assignee, priority } = rowData;
        const status = rowData.status || "Open"; // Nếu không có, mặc định là "Open"
        const planStartDate = rowData.plan_start_date || null;
        const planEndDate = rowData.plan_end_date || null;

        // Kiểm tra dữ liệu hợp lệ
        if (!epic || !assignee || !priority) {
            validationErrors.value.push(
                `⚠️ Line ${lineNumber} is missing required fields (epic, assignee, priority).`
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

        const assigneeId = parsedListAssignee.value[assignee];

        if (epic && !task) {
            // Nếu chỉ có Epic, tạo Epic
            tasks.value.push({
                name: epic,
                type: 0,
                parent_id: null,
                assignee: assigneeId,
                priority: priorityMap[priority],
                status: statusMap[status],
                plan_start_date: planStartDate,
                plan_end_date: planEndDate,
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
                plan_start_date: planStartDate,
                plan_end_date: planEndDate,
                created_by: props.currentUserId,
            });
        }
    });
};

const submitFile = async () => {
    if (isLoading.value) return;
    isLoading.value = true;

    try {
        // 1️⃣ Gửi danh sách Epic trước
        for (const task of tasks.value.filter((t) => t.type === 0)) {
            const response = await axios.post(
                `/api/pm/${props.projectId}/tasks/store`,
                {
                    project_id: props.projectId,
                    name: task.name,
                    type: "epic",
                    parent_id: null,
                    assignee: task.assignee,
                    priority: task.priority,
                    status: task.status,
                    plan_start_date: task.plan_start_date,
                    plan_end_date: task.plan_end_date,
                    created_by: task.created_by,
                }
            );

            epicMap.value[task.name] = response.data.task.id;
        }

        // 2️⃣ Gửi danh sách Task sau khi có Epic ID
        for (const task of tasks.value.filter((t) => t.type === 1)) {
            const epicId = epicMap.value[task.parent_name];

            if (!epicId) {
                toastr.error(
                    `⚠️ Epic "${task.parent_name}" not found, cannot create task "${task.name}".`
                );
                continue;
            }

            await axios.post(`/api/pm/${props.projectId}/tasks/store`, {
                project_id: props.projectId,
                name: task.name,
                type: "task",
                parent_id: epicId,
                assignee: task.assignee,
                priority: task.priority,
                status: task.status,
                plan_start_date: task.plan_start_date,
                plan_end_date: task.plan_end_date,
                created_by: task.created_by,
            });
        }

        toastr.success("Task created successfully!");

        // Emit để component cha xử lý
        emit("update-task");
    } catch (error) {
        toastr.error("Lỗi khi upload dữ liệu!");
        console.error(error);
    } finally {
        isLoading.value = false;
    }
};
</script>

<style scoped>
.custom-file-input {
    cursor: pointer;
}
</style>
