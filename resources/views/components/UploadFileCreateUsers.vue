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
            <span v-if="isLoading">
                <i class="fas fa-spinner fa-spin"></i> Đang xử lý...
            </span>
            <span v-else> <button type="button" class="btn btn-primary" @click="submitFile(users)"
                    :disabled="!selectedFile || isLoading">
                    Submit
                </button> </span>

        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import axios from "axios";
import toastr from "toastr";

const selectedFile = ref(null);
const fileName = ref("Choose file");
const isCollapsed = ref(false);
const validationErrors = ref([]);
const users = ref([]);
const isLoading = ref(false);

const props = defineProps({
    submitUrl: {
        type: String,
        required: true,
    },
});

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
    users.value = [];

    const lines = csvText.trim().split("\n").map((line) => line.split(",").map((cell) => cell.trim()));
    if (lines.length < 2) {
        validationErrors.value.push("⚠️ File CSV phải có ít nhất 1 dòng dữ liệu.");
        return;
    }

    const headers = lines[0];
    const requiredHeaders = ["email", "account", "full_name", "job_position", "password"];
    if (!arraysEqual(headers, requiredHeaders)) {
        validationErrors.value.push("⚠️ Headers CSV không hợp lệ! Phải là: email, account, full_name, job_position, password.");
        return;
    }

    const emails = new Set();
    const accounts = new Set();

    lines.slice(1).forEach((row, index) => {
        const lineNumber = index + 2;
        if (row.length !== requiredHeaders.length) {
            validationErrors.value.push(`⚠️ Dòng ${lineNumber} không đủ ${requiredHeaders.length} cột.`);
            return;
        }

        const [email, account, fullName, jobPosition, password] = row;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!emailRegex.test(email)) {
            validationErrors.value.push(`⚠️ Email dòng ${lineNumber} không hợp lệ: "${email}".`);
        }

        if (emails.has(email)) {
            validationErrors.value.push(`⚠️ Email dòng ${lineNumber} bị trùng.`);
        }
        if (accounts.has(account)) {
            validationErrors.value.push(`⚠️ Account dòng ${lineNumber} bị trùng.`);
        }

        emails.add(email);
        accounts.add(account);
        users.value.push({ email, account, full_name: fullName, job_position: jobPosition, password });
    });
};

const submitFile = async (userList) => {
    if (!Array.isArray(userList) || userList.length === 0) {
        toastr.error("No valid users to submit!");
        return;
    }

    if (isLoading.value) return; // Tránh spam submit
    isLoading.value = true;

    try {
        const payload = {
            emails: userList.map((u) => u.email),
            accounts: userList.map((u) => u.account),
            full_names: userList.map((u) => u.full_name),
            job_positions: userList.map((u) => u.job_position),
            passwords: userList.map((u) => u.password),
        };

        const response = await axios.post(props.submitUrl, payload);
        toastr.success(response.data.success || "Upload successful!");
    } catch (error) {
        toastr.error(error.response?.data?.error || "Upload failed!");
    } finally {
        isLoading.value = false;
    }
};

const arraysEqual = (arr1, arr2) => arr1.length === arr2.length && arr1.every((val, index) => val === arr2[index]);
</script>

<style scoped>
.custom-file-input {
    cursor: pointer;
}
</style>
