<template>
    <div class="card card-success">
        <div class="card-header">
            <h3 class="card-title">Tenant Worklog Calendar</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>

        <div class="card-body">
            <div class="row">

                <!-- Tìm kiếm theo Plan Start Date -->
                <div class="col-6">
                    <div class="form-group">
                        <label>Filter Logged Date:</label>
                        <div class="d-flex align-items-center">
                            <!-- Plan Start Date (From) -->
                            <div class="input-group date mr-2" id="filterLogDatePickerFrom" data-target-input="nearest">
                                <input type="text" id="filterLogDateFrom" class="form-control datetimepicker-input"
                                    data-target="#filterLogDatePickerFrom" placeholder="From" />
                                <div class="input-group-append" data-target="#filterLogDatePickerFrom"
                                    data-toggle="datetimepicker">
                                    <div class="input-group-text">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Dấu "-" -->
                            <span class="mx-2">-</span>

                            <!-- Plan Start Date (To) -->
                            <div class="input-group date ml-2 mr-2" id="filterLogDatePickerTo"
                                data-target-input="nearest">
                                <input type="text" id="filterLogDateTo" class="form-control datetimepicker-input"
                                    data-target="#filterLogDatePickerTo" placeholder="To" />
                                <div class="input-group-append" data-target="#filterLogDatePickerTo"
                                    data-toggle="datetimepicker">
                                    <div class="input-group-text">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                </div>
                            </div>

                            <button @click="applyFilter" class="btn btn-success ml-2">
                                🔍
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row table-responsive">
                <table class="table-sm custom-table">
                    <thead>
                        <tr>
                            <th class="fixed-column">Assignee</th>
                            <th v-for="date in loggedDates" :key="date">{{ getDayOnly(date) }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(worklogs, userId) in totalWorklogByUserAndDate" :key="userId">
                            <td class="fixed-column">{{ getUserAccount(userId) }}</td>
                            <td v-for="date in loggedDates" :key="date"
                                :class="getCellClass(worklogs[date] ? worklogs[date].toFixed(2) : '0.00')">
                                {{ worklogs[date] ? worklogs[date].toFixed(2) : "0.00" }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Đảm bảo có scroll ngang nếu bảng rộng */
.table-responsive {
    width: 100%;
    overflow-x: auto;
}

/* Đảm bảo bảng không bị co hẹp */
.custom-table {
    table-layout: fixed;
    border-collapse: collapse;
}

/* Cố định độ rộng cột */
.fixed-column {
    min-width: 120px;
    /* Cột đầu tiên rộng hơn */
    white-space: nowrap;
    text-align: center;
    background-color: #f8f9fa;
}

/* Định dạng cột dữ liệu */
.custom-table th,
.custom-table td {
    width: 60px;
    white-space: nowrap;
    text-align: center;
    padding: 8px;
    border: 1px solid #ddd;
}

.bg-red {
    background-color: #ffcccc !important;
    /* Đỏ nhạt */
}

.bg-yellow {
    background-color: #fff3cd !important;
    /* Vàng nhạt */
}
</style>

<script setup>
import {  computed, ref, onMounted, nextTick } from "vue";
import Swal from "sweetalert2";

const props = defineProps({
    worklogs: Array,
});

// Ngày mặc định là 30 ngày trước
const fromDate = ref(new Date(new Date().setDate(new Date().getDate() - 30)).toISOString().split("T")[0]);
const toDate = ref(new Date().toISOString().split("T")[0]);

// Temp values để người dùng chọn ngày mà chưa lọc ngay
const tempFromDate = ref(fromDate.value);
const tempToDate = ref(toDate.value);

// Dữ liệu worklogs đã lọc (chỉ cập nhật khi bấm "Search")
const filteredWorklogs = ref([]);

onMounted(() => {
    nextTick(() => {
        if (window.jQuery && $.fn.select2 && $.fn.datetimepicker) {
            $("#filterLogDatePickerFrom").datetimepicker({
                format: "YYYY-MM-DD",
                useCurrent: false,
            });
            $("#filterLogDatePickerFrom").on(
                "change.datetimepicker",
                function (e) {
                    let newPlanStartDateFrom = e.date
                        ? e.date.format("YYYY-MM-DD")
                        : "";
                    if (newPlanStartDateFrom) {
                        tempFromDate.value = newPlanStartDateFrom;
                    } else {
                        tempFromDate.value = "";
                    }
                }
            );

            // Date picker for Plan Start Date (To)
            $("#filterLogDatePickerTo").datetimepicker({
                format: "YYYY-MM-DD",
                useCurrent: false,
            });
            $("#filterLogDatePickerTo").on(
                "change.datetimepicker",
                function (e) {
                    let newPlanStartDateTo = e.date
                        ? e.date.format("YYYY-MM-DD")
                        : "";
                    if (newPlanStartDateTo) {
                        tempToDate.value = newPlanStartDateTo;
                    } else {
                        tempToDate.value = "";
                    }
                }
            );
        }
    })
});

// Hàm áp dụng bộ lọc khi nhấn "Search"
const applyFilter = () => {
    if (new Date(tempToDate.value) - new Date(tempFromDate.value) > 30 * 24 * 60 * 60 * 1000) {
        Swal.fire({
            icon: "error",
            title: "Invalid Date Range",
            text: "Please select a date range within 30 days.",
        });
        return;
    }

    fromDate.value = tempFromDate.value;
    toDate.value = tempToDate.value;

    filteredWorklogs.value = props.worklogs.filter(
        (worklog) => worklog.log_date >= fromDate.value && worklog.log_date <= toDate.value
    );
};

const totalWorklogByUserAndDate = computed(() => {
    if (!Array.isArray(filteredWorklogs.value) || filteredWorklogs.value.length === 0) {
        return {};
    }

    return filteredWorklogs.value.reduce((acc, worklog) => {
        const userId = worklog.task?.assignee_user?.id || 'unknown'; // Nếu không có user, gán 'unknown'
        const date = worklog.log_date;
        const time = parseFloat(worklog.log_time) || 0;

        if (!acc[userId]) {
            acc[userId] = {};
        }
        if (!acc[userId][date]) {
            acc[userId][date] = 0;
        }
        acc[userId][date] += time;

        return acc;
    }, {});
});

// Hàm tạo danh sách ngày từ fromDate đến toDate
const generateDateRange = (start, end) => {
    const dates = [];
    let currentDate = new Date(start);
    const endDate = new Date(end);

    while (currentDate <= endDate) {
        dates.push(currentDate.toISOString().split("T")[0]); // Lưu dưới dạng YYYY-MM-DD
        currentDate.setDate(currentDate.getDate() + 1);
    }

    return dates;
};

// Danh sách ngày đầy đủ trong khoảng tìm kiếm
const loggedDates = computed(() => generateDateRange(fromDate.value, toDate.value));

// Định dạng tổng worklog, đảm bảo ngày không có dữ liệu hiển thị "0.00"
const formattedTotalWorklog = computed(() => {
    const result = {};

    loggedDates.value.forEach(date => {
        let total = 0;

        Object.values(totalWorklogByUserAndDate.value).forEach(userWorklogs => {
            total += userWorklogs[date] || 0;
        });

        result[date] = total.toFixed(2);
    });

    return result;
});


const getUserAccount = (userId) => {
    const user = filteredWorklogs.value.find(w => w.task?.assignee_user?.id == userId);
    return user?.task?.assignee_user?.account || 'Unknown';
};

// Hàm chỉ lấy ngày (bỏ tháng/năm)
const getDayOnly = (dateString) => {
    return new Date(dateString).getDate();
};

// Áp dụng bộ lọc ngay lần đầu tiên khi component mount
applyFilter();

const getCellClass = (value) => {
    const numValue = parseFloat(value);
    if (numValue == 0) return "bg-red"; // Nếu worklog = 0, nền đỏ nhạt
    if (numValue > 0 && numValue < 8) return "bg-yellow"; // Nếu worklog < 8, nền vàng nhạt
    return "";
};
</script>
