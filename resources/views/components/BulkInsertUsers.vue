<template>
    <div class="card card-secondary">
        <div class="card-header">
            <h3 class="card-title">{{ translations?.labels?.bulk_insert || 'Bulk Insert' }}</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" @click="toggleCollapse">
                    <i :class="isCollapsed ? 'fas fa-plus' : 'fas fa-minus'"></i>
                </button>
            </div>
        </div>
        <div v-show="!isCollapsed" class="card-body">
            <form ref="bulkInsertForm" @submit.prevent="submitForm">
                <div v-if="validationErrors.length" class="alert alert-danger">
                    <ul>
                        <li v-for="(error, index) in validationErrors" :key="index">
                            {{ error }}
                        </li>
                    </ul>
                </div>

                <div class="row">
                    <div class="col-md-2" v-for="(field, index) in fields" :key="index">
                        <div class="form-group">
                            <label>{{ field.label }} <span style="color: red">*</span></label>
                            <textarea v-model="formData[field.name]" class="form-control" rows="10"
                                :placeholder="field.placeholder"></textarea>
                        </div>
                    </div>
                </div>

                <div class="form-check">
                    <input type="checkbox" id="autoGeneratePassword" class="form-check-input"
                        v-model="autoGeneratePassword" @change="handleAutoGeneratePassword" />
                    <label class="form-check-label" for="autoGeneratePassword">
                        Auto generate password
                    </label>
                </div>
            </form>
        </div>
        <div class="card-footer">
            <button type="button" class="btn btn-primary" @click="submitForm" :disabled="isLoading">
                <span v-if="isLoading">
                    <i class="fas fa-spinner fa-spin"></i> Đang xử lý...
                </span>
                <span v-else> {{ translations?.labels?.submit || 'Submit'  }} </span>
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref, watch } from "vue";
import axios from "axios";

// Lấy ngôn ngữ hiện tại từ session hoặc mặc định là "en"
const currentLang = ref(window.appLang || "en");

// Khởi tạo translations là một ref
const translations = ref(window.translations?.[currentLang.value] || {});
console.log(translations.value);

// Cập nhật translations khi `currentLang` thay đổi
watch(currentLang, (newLang) => {
    translations.value = window.translations?.[newLang] || {};
});

const props = defineProps({
    submitUrl: { type: String, required: true },
});

const isCollapsed = ref(false);
const autoGeneratePassword = ref(false);
const isLoading = ref(false);
const validationErrors = ref([]);
const formData = ref({
    bi_email: "",
    bi_account: "",
    bi_full_name: "",
    bi_job_position: "",
    bi_password: "",
});

const validJobPositions = [
    "Manager",
    "Project Manager",
    "Bridge System Engineer",
    "Developer",
    "Tester",
    "Comtor",
    "Other",
];

const fields = [
    { name: "bi_email", label: "Email", placeholder: "Enter emails..." },
    { name: "bi_account", label: "Account", placeholder: "Enter accounts..." },
    { name: "bi_full_name", label: t("labels", "full_name", ""), placeholder: "Enter full names..." },
    { name: "bi_job_position", label: "Job Position", placeholder: "Enter job positions..." },
    { name: "bi_password", label: "Password", placeholder: "Enter passwords..." },
];

const toggleCollapse = () => {
    isCollapsed.value = !isCollapsed.value;
};

const generateRandomString = (length = 12) => {
    const characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    return Array.from({ length }, () =>
        characters.charAt(Math.floor(Math.random() * characters.length))
    ).join("");
};

const handleAutoGeneratePassword = () => {
    if (autoGeneratePassword.value) {
        const maxLines = Math.max(
            getLineCount(formData.value.bi_email),
            getLineCount(formData.value.bi_account),
            getLineCount(formData.value.bi_full_name)
        );
        formData.value.bi_password = Array.from(
            { length: maxLines },
            () => generateRandomString(12)
        ).join("\n");
    }
};

const getLineCount = (text) => {
    return text ? text.split("\n").filter((line) => line.trim() !== "").length : 0;
};

const getCleanLines = (text) => {
    return text
        ? text.split("\n").map((line) => line.trim()).filter((line) => line !== "")
        : [];
};

const hasDuplicateLines = (lines) => {
    return new Set(lines).size !== lines.length;
};

const hasIntermediateEmptyLine = (text) => {
    const lines = text.split("\n");
    let foundContent = false;
    for (const line of lines) {
        if (line.trim() === "" && foundContent) return true;
        if (line.trim() !== "") foundContent = true;
    }
    return false;
};

const submitForm = async () => {
    validationErrors.value = [];
    if (isLoading.value) return;
    isLoading.value = true;

    const emails = getCleanLines(formData.value.bi_email);
    const accounts = getCleanLines(formData.value.bi_account);
    const fullNames = getCleanLines(formData.value.bi_full_name);
    const passwords = getCleanLines(formData.value.bi_password);
    const jobPositions = getCleanLines(formData.value.bi_job_position);

    if ([emails, accounts, fullNames, passwords, jobPositions].every(field => field.length === 0)) {
        validationErrors.value.push("⚠️ Bạn chưa nhập dữ liệu vào bất kỳ ô nào.");
        isLoading.value = false;
        return;
    }

    const maxLines = Math.max(emails.length, accounts.length, fullNames.length, passwords.length, jobPositions.length);
    if ([emails, accounts, fullNames, passwords, jobPositions].some(field => field.length !== maxLines)) {
        validationErrors.value.push("⚠️ Số lượng dòng trong tất cả các ô input phải bằng nhau.");
        isLoading.value = false;
    }

    if ([formData.value.bi_email, formData.value.bi_account, formData.value.bi_full_name, formData.value.bi_password, formData.value.bi_job_position].some(hasIntermediateEmptyLine)) {
        validationErrors.value.push("⚠️ Không được có dòng trắng xen giữa trong bất kỳ ô nhập liệu nào.");
        isLoading.value = false;
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    emails.forEach((email, index) => {
        if (!emailRegex.test(email)) {
            // validationErrors.value.push(`⚠️ Email dòng ${index + 1} không hợp lệ: "${email}"`);
            validationErrors.value.push(t("messages", "invalid_email", { index: index + 1, email: email }));
            isLoading.value = false;
        }
    });

    jobPositions.forEach((job, index) => {
        if (!validJobPositions.includes(job)) {
            validationErrors.value.push(`⚠️ Job Position dòng ${index + 1} không hợp lệ: "${job}".`);
            isLoading.value = false;
        }
    });

    if (hasDuplicateLines(emails)) validationErrors.value.push(t("messages", "duplicate_emails", ""));

    if (validationErrors.value.length > 0) return;

    const payload = {
        emails,
        accounts,
        full_names: fullNames,
        passwords,
        job_positions: jobPositions,
    };

    try {
        const response = await axios.post(props.submitUrl, payload);
        toastr.success(response.data.success);
        resetForm();
    } catch (error) {
        toastr.error(error.response?.data?.error || "❌ Có lỗi xảy ra khi gửi dữ liệu.");
    } finally {
        isLoading.value = false;
    }
};

const resetForm = () => {
    Object.keys(formData.value).forEach((key) => (formData.value[key] = ""));
    autoGeneratePassword.value = false;
};
</script>
