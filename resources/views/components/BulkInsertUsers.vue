<template>
    <div class="card card-secondary">
        <div class="card-header">
            <h3 class="card-title">Bulk Insert</h3>
            <div class="card-tools">
                <button
                    type="button"
                    class="btn btn-tool"
                    @click="toggleCollapse"
                >
                    <i
                        :class="isCollapsed ? 'fas fa-plus' : 'fas fa-minus'"
                    ></i>
                </button>
            </div>
        </div>
        <div v-show="!isCollapsed" class="card-body">
            <form ref="bulkInsertForm" @submit.prevent="submitForm">
                <div v-if="validationErrors.length" class="alert alert-danger">
                    <ul>
                        <li
                            v-for="(error, index) in validationErrors"
                            :key="index"
                        >
                            {{ error }}
                        </li>
                    </ul>
                </div>

                <div class="row">
                    <div
                        class="col-md-2"
                        v-for="(field, index) in fields"
                        :key="index"
                    >
                        <div class="form-group">
                            <label>{{ field.label }}</label>
                            <textarea
                                v-model="formData[field.name]"
                                class="form-control"
                                rows="10"
                                :placeholder="field.placeholder"
                            ></textarea>
                        </div>
                    </div>
                </div>
                <div class="form-check">
                    <input
                        type="checkbox"
                        id="autoGeneratePassword"
                        class="form-check-input"
                        v-model="autoGeneratePassword"
                        @change="handleAutoGeneratePassword"
                    />
                    <label class="form-check-label" for="autoGeneratePassword">
                        Auto generate password
                    </label>
                </div>
            </form>
        </div>
        <div class="card-footer">
            <button
                type="button"
                class="btn btn-primary"
                @click="submitForm"
                :disabled="isLoading"
            >
                <span v-if="isLoading">
                    <i class="fas fa-spinner fa-spin"></i> Đang xử lý...
                </span>
                <span v-else> Submit </span>
            </button>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        submitUrl: {
            type: String,
            required: true,
        },
    },
    data() {
        return {
            isCollapsed: false,
            autoGeneratePassword: false,
            isLoading: false,
            validationErrors: [],
            formData: {
                bi_email: "",
                bi_account: "",
                bi_full_name: "",
                bi_job_position: "", // Thêm ô Job Position
                bi_password: "",
            },
            validJobPositions: [
                "Manager",
                "Project Manager",
                "Bridge System Engineer",
                "Developer",
                "Tester",
                "Comtor",
                "Other",
            ],
            fields: [
                {
                    name: "bi_email",
                    label: "Email",
                    placeholder: "Enter emails...",
                },
                {
                    name: "bi_account",
                    label: "Account",
                    placeholder: "Enter accounts...",
                },
                {
                    name: "bi_full_name",
                    label: "Full Name",
                    placeholder: "Enter full names...",
                },
                {
                    name: "bi_job_position",
                    label: "Job Position",
                    placeholder: "Enter job positions...",
                }, // Mới
                {
                    name: "bi_password",
                    label: "Password",
                    placeholder: "Enter passwords...",
                },
            ],
        };
    },
    methods: {
        toggleCollapse() {
            this.isCollapsed = !this.isCollapsed;
        },
        submitForm() {
            this.$refs.bulkInsertForm.submit();
        },
        generateRandomString(length = 12) {
            const characters =
                "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
            return Array.from({ length }, () =>
                characters.charAt(Math.floor(Math.random() * characters.length))
            ).join("");
        },
        handleAutoGeneratePassword() {
            if (this.autoGeneratePassword) {
                const maxLines = Math.max(
                    this.getLineCount(this.formData.bi_email),
                    this.getLineCount(this.formData.bi_account),
                    this.getLineCount(this.formData.bi_full_name)
                );
                this.formData.bi_password = Array.from(
                    { length: maxLines },
                    () => this.generateRandomString(12)
                ).join("\n");
            }
        },
        getLineCount(text) {
            return text
                ? text.split("\n").filter((line) => line.trim() !== "").length
                : 0;
        },
        async submitForm() {
            this.validationErrors = [];
            if (this.isLoading) return; // Tránh bấm liên tục
            this.isLoading = true;

            const emails = this.getCleanLines(this.formData.bi_email);
            const accounts = this.getCleanLines(this.formData.bi_account);
            const fullNames = this.getCleanLines(this.formData.bi_full_name);
            const passwords = this.getCleanLines(this.formData.bi_password);
            const jobPositions = this.getCleanLines(
                this.formData.bi_job_position
            );

            // Kiểm tra nếu tất cả các ô đều trống
            if (
                emails.length === 0 &&
                accounts.length === 0 &&
                fullNames.length === 0 &&
                passwords.length === 0 &&
                jobPositions.length === 0
            ) {
                this.validationErrors.push(
                    "⚠️ Bạn chưa nhập dữ liệu vào bất kỳ ô nào."
                );
                this.isLoading = false; // Tắt trạng thái loading nếu lỗi
                return;
            }

            const maxLines = Math.max(
                emails.length,
                accounts.length,
                fullNames.length,
                passwords.length,
                jobPositions.length
            );
            if (
                [emails, accounts, fullNames, passwords, jobPositions].some(
                    (field) => field.length !== maxLines
                )
            ) {
                this.validationErrors.push(
                    "⚠️ Số lượng dòng trong tất cả các ô input phải bằng nhau."
                );
                this.isLoading = false; // Tắt trạng thái loading nếu lỗi
            }

            if (
                [
                    this.formData.bi_email,
                    this.formData.bi_account,
                    this.formData.bi_full_name,
                    this.formData.bi_password,
                    this.formData.bi_job_position,
                ].some(this.hasIntermediateEmptyLine)
            ) {
                this.validationErrors.push(
                    "⚠️ Không được có dòng trắng xen giữa trong bất kỳ ô nhập liệu nào."
                );
                this.isLoading = false; // Tắt trạng thái loading nếu lỗi
            }

            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            emails.forEach((email, index) => {
                if (!emailRegex.test(email)) {
                    this.validationErrors.push(
                        `⚠️ Email dòng ${index + 1} không hợp lệ: "${email}"`
                    );
                    this.isLoading = false; // Tắt trạng thái loading nếu lỗi
                }
            });

            jobPositions.forEach((job, index) => {
                if (!this.validJobPositions.includes(job)) {
                    this.validationErrors.push(
                        `⚠️ Job Position dòng ${
                            index + 1
                        } không hợp lệ: "${job}". Giá trị hợp lệ: ${this.validJobPositions.join(
                            ", "
                        )}`
                    );
                    this.isLoading = false; // Tắt trạng thái loading nếu lỗi
                }
            });

            if (this.hasDuplicateLines(emails)) {
                this.validationErrors.push(
                    "⚠️ Danh sách email có dòng trùng lặp."
                );
                this.isLoading = false; // Tắt trạng thái loading nếu lỗi
            }

            if (this.hasDuplicateLines(accounts)) {
                this.validationErrors.push(
                    "⚠️ Danh sách account có dòng trùng lặp."
                );
                this.isLoading = false; // Tắt trạng thái loading nếu lỗi
            }

            if (this.validationErrors.length > 0) return;

            const payload = {
                emails,
                accounts,
                full_names: fullNames,
                passwords,
                job_positions: jobPositions,
            };

            try {
                const response = await axios.post(this.submitUrl, payload);

                // Hiển thị Toastr với số lượng user được tạo
                toastr.success(response.data.success);

                // Reset form
                this.resetForm();
            } catch (error) {
                console.error("⚠️ Lỗi khi gửi dữ liệu:", error.response); // Log chi tiết lỗi

                // Hiển thị lỗi nếu có
                toastr.error(
                    error.response?.data?.error ||
                        "❌ Có lỗi xảy ra khi gửi dữ liệu."
                );
            } finally {
                this.isLoading = false; // Tắt trạng thái loading sau khi hoàn thành
            }
        },
        resetForm() {
            Object.keys(this.formData).forEach(
                (key) => (this.formData[key] = "")
            );
            this.autoGeneratePassword = false; // Bỏ check checkbox
        },
        getCleanLines(text) {
            return text
                ? text
                      .split("\n")
                      .map((line) => line.trim())
                      .filter((line) => line !== "")
                : [];
        },
        hasIntermediateEmptyLine(text) {
            const lines = text.split("\n");
            let foundContent = false;
            for (const line of lines) {
                if (line.trim() === "" && foundContent) return true;
                if (line.trim() !== "") foundContent = true;
            }
            return false;
        },
        hasDuplicateLines(lines) {
            return new Set(lines).size !== lines.length;
        },
    },
};
</script>
