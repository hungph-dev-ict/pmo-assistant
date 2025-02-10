<template>
    <div class="card card-success">
        <div class="card-header">
            <h3 class="card-title">By Excel, CSV File</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" @click="toggleCollapse">
                    <i :class="isCollapsed ? 'fas fa-plus' : 'fas fa-minus'"></i>
                </button>
            </div>
        </div>

        <div class="card-body" v-if="!isCollapsed">
            <div class="form-group">
                <label for="fileInput">File input <span style="color: red">*</span></label>
                <div class="input-group">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="fileInput" @change="handleFileChange" />
                        <label class="custom-file-label" for="fileInput">{{ fileName }}</label>
                    </div>
                </div>
                <ul v-if="validationErrors.length">
                    <li v-for="(error, index) in validationErrors" :key="index" style="color: red">
                        {{ error }}
                    </li>
                </ul>
            </div>
        </div>

        <div class="card-footer">
            <button type="button" class="btn btn-primary" @click="submitFile" :disabled="!selectedFile">
                Submit
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import toastr from "toastr";

const selectedFile = ref(null);
const fileName = ref("Choose file");
const isCollapsed = ref(false);
const validationErrors = ref([]);

const validJobPositions = ["Developer", "BA", "PM", "QA"]; // Danh sách job hợp lệ

const toggleCollapse = () => {
    isCollapsed.value = !isCollapsed.value;
};

const handleFileChange = (event) => {
    const file = event.target.files[0];
    if (!file) {
        fileName.value = "Choose file";
        selectedFile.value = null;
        return;
    }

    // Kiểm tra định dạng file
    if (!file.name.endsWith(".csv")) {
        toastr.error("Only CSV files are allowed!");
        fileName.value = "Choose file";
        selectedFile.value = null;
        event.target.value = ""; // Reset input file
        return;
    }

    fileName.value = file.name;
    selectedFile.value = file;

    // Đọc file để kiểm tra nội dung
    const reader = new FileReader();
    reader.onload = (e) => validateCsv(e.target.result);
    reader.readAsText(file);
};

const validateCsv = (csvText) => {
    validationErrors.value = [];
    const lines = csvText.trim().split("\n").map((line) => line.split(",").map((cell) => cell.trim()));

    if (lines.length < 2) {
        validationErrors.value.push("⚠️ File CSV phải có ít nhất 1 dòng dữ liệu.");
        return;
    }

    // Kiểm tra headers
    const headers = lines[0];
    const requiredHeaders = ["email", "account", "full_name", "job_position", "password"];
    if (!arraysEqual(headers, requiredHeaders)) {
        validationErrors.value.push("⚠️ Headers CSV không hợp lệ! Phải là: email, account, full_name, job_position, password.");
        return;
    }

    // Kiểm tra dữ liệu từng dòng
    const emails = [];
    const accounts = [];

    lines.slice(1).forEach((row, index) => {
        const lineNumber = index + 2; // Vì index 0 là headers
        if (row.length !== requiredHeaders.length) {
            validationErrors.value.push(`⚠️ Dòng ${lineNumber} không đủ ${requiredHeaders.length} cột.`);
            return;
        }

        const [email, account, fullName, jobPosition, password] = row;

        // Kiểm tra email hợp lệ
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            validationErrors.value.push(`⚠️ Email dòng ${lineNumber} không hợp lệ: "${email}".`);
        }

        // Kiểm tra job position hợp lệ
        if (!validJobPositions.includes(jobPosition)) {
            validationErrors.value.push(`⚠️ Job Position dòng ${lineNumber} không hợp lệ: "${jobPosition}". Giá trị hợp lệ: ${validJobPositions.join(", ")}`);
        }

        // Kiểm tra trùng lặp email & account
        if (emails.includes(email)) {
            validationErrors.value.push(`⚠️ Email dòng ${lineNumber} bị trùng.`);
        }
        if (accounts.includes(account)) {
            validationErrors.value.push(`⚠️ Account dòng ${lineNumber} bị trùng.`);
        }

        emails.push(email);
        accounts.push(account);
    });
};

const arraysEqual = (arr1, arr2) => {
    return arr1.length === arr2.length && arr1.every((val, index) => val === arr2[index]);
};

const submitFile = async () => {
    if (!selectedFile.value) {
        toastr.error("Please select a file before submitting.");
        return;
    }

    const formData = new FormData();
    formData.append("file", selectedFile.value);

    try {
        console.log("File is ready to be uploaded");
        // Gửi file lên server (bỏ comment nếu cần)
        // const response = await axios.post("/api/import-file", formData, {
        //     headers: { "Content-Type": "multipart/form-data" },
        // });
        // toastr.success(response.data.message);
    } catch (error) {
        toastr.error(error.response?.data?.message || "File upload failed!");
    }
};
</script>

<style scoped>
.custom-file-input {
    cursor: pointer;
}
</style>
