<template>
    <div class="card card-success">
        <div class="card-header">
            <h3 class="card-title">Worklog Calendar</h3>
            <div class="card-tools">
                <button
                    type="button"
                    class="btn btn-tool"
                    data-card-widget="collapse"
                    title="Collapse"
                >
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
                            <div
                                class="input-group date mr-2"
                                id="filterLogDatePickerFrom"
                                data-target-input="nearest"
                            >
                                <input
                                    type="text"
                                    id="filterLogDateFrom"
                                    class="form-control datetimepicker-input"
                                    data-target="#filterLogDatePickerFrom"
                                    placeholder="From"
                                />
                                <div
                                    class="input-group-append"
                                    data-target="#filterLogDatePickerFrom"
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
                                class="input-group date ml-2 mr-2"
                                id="filterLogDatePickerTo"
                                data-target-input="nearest"
                            >
                                <input
                                    type="text"
                                    id="filterLogDateTo"
                                    class="form-control datetimepicker-input"
                                    data-target="#filterLogDatePickerTo"
                                    placeholder="To"
                                />
                                <div
                                    class="input-group-append"
                                    data-target="#filterLogDatePickerTo"
                                    data-toggle="datetimepicker"
                                >
                                    <div class="input-group-text">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                </div>
                            </div>

                            <button
                                @click="applyFilter"
                                class="btn btn-success ml-2"
                            >
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
                            <th class="fixed-column">Logged Date</th>
                            <th v-for="date in loggedDates" :key="date">
                                {{ getDayOnly(date) }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="fixed-column">Total Worklogs</td>
                            <td
                                v-for="date in loggedDates"
                                :key="date"
                                :class="
                                    getCellClass(
                                        formattedTotalWorklog[date],
                                        date
                                    )
                                "
                            >
                                {{ formattedTotalWorklog[date] }}
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

.bg-warning {
    background-color: #ffeb3b !important;
    /* Màu vàng đậm hơn để cảnh báo */
    color: #333;
    font-weight: bold;
}
</style>

<script setup>
import { computed, ref, onMounted, nextTick, watch } from "vue";
import Swal from "sweetalert2";
import moment from "moment";

const props = defineProps({
    worklogs: Array,
});

// Ngày mặc định là 30 ngày trước
const fromDate = ref(
    new Date(new Date().setDate(new Date().getDate() - 30))
        .toISOString()
        .split("T")[0]
);
const toDate = ref(new Date().toISOString().split("T")[0]);

// Temp values để người dùng chọn ngày mà chưa lọc ngay
const tempFromDate = ref(fromDate.value);
const tempToDate = ref(toDate.value);

// Dữ liệu worklogs đã lọc (chỉ cập nhật khi bấm "Search")
const filteredWorklogs = ref([]);

watch(
    () => props.worklogs,
    (newWorklogs) => {
        filteredWorklogs.value = newWorklogs.filter(
            (worklog) =>
                worklog.log_date >= fromDate.value &&
                worklog.log_date <= toDate.value
        );
    },
    { deep: true, immediate: true }
);

onMounted(() => {
    nextTick(() => {
        if (window.jQuery && $.fn.select2 && $.fn.datetimepicker) {
            // Set default date range (last 30 days)
            const today = moment();
            const thirtyDaysAgo = moment().subtract(30, 'days');
            
            const formattedToday = today.format('YYYY-MM-DD');
            const formattedThirtyDaysAgo = thirtyDaysAgo.format('YYYY-MM-DD');

            // Initialize date pickers
            $("#filterLogDatePickerFrom").datetimepicker({
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
                useCurrent: false,
                defaultDate: thirtyDaysAgo
            });

            $("#filterLogDatePickerTo").datetimepicker({
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
                useCurrent: false,
                defaultDate: today
            });

            // Set input values
            $("#filterLogDateFrom").val(formattedThirtyDaysAgo);
            $("#filterLogDateTo").val(formattedToday);

            // Set reactive variables
            tempFromDate.value = formattedThirtyDaysAgo;
            tempToDate.value = formattedToday;
            fromDate.value = formattedThirtyDaysAgo;
            toDate.value = formattedToday;

            // Apply initial filter with default dates
            applyFilter();

            // Event handlers for date changes
            $("#filterLogDatePickerFrom").on("change.datetimepicker", function (e) {
                let newDateFrom = e.date ? e.date.format("YYYY-MM-DD") : "";
                if (newDateFrom) {
                    tempFromDate.value = newDateFrom;
                } else {
                    tempFromDate.value = "";
                }
            });

            $("#filterLogDatePickerTo").on("change.datetimepicker", function (e) {
                let newDateTo = e.date ? e.date.format("YYYY-MM-DD") : "";
                if (newDateTo) {
                    tempToDate.value = newDateTo;
                } else {
                    tempToDate.value = "";
                }
            });
        }

        // Cuộn xuống ngày cuối cùng sau khi render xong
        scrollToLastDate();
    });
});

const scrollToLastDate = () => {
    nextTick(() => {
        const tableContainer = document.querySelector(".table-responsive");
        if (tableContainer) {
            tableContainer.scrollLeft = tableContainer.scrollWidth;
        }
    });
};

// Hàm áp dụng bộ lọc khi nhấn "Search"
const applyFilter = () => {
    if (
        new Date(tempToDate.value) - new Date(tempFromDate.value) >
        30 * 24 * 60 * 60 * 1000
    ) {
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
        (worklog) =>
            worklog.log_date >= fromDate.value &&
            worklog.log_date <= toDate.value
    );

    nextTick(() => {
        scrollToLastDate(); // Cuộn xuống ngày cuối cùng
    });
};

// Tạo object chứa tổng thời gian log theo ngày
const totalWorklogByDate = computed(() => {
    if (
        !Array.isArray(filteredWorklogs.value) ||
        filteredWorklogs.value.length === 0
    ) {
        return {};
    }

    return filteredWorklogs.value.reduce((acc, worklog) => {
        const date = worklog.log_date;
        const time = parseFloat(worklog.log_time) || 0;

        if (!acc[date]) {
            acc[date] = 0;
        }
        acc[date] += time;

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
const loggedDates = computed(() =>
    generateDateRange(fromDate.value, toDate.value)
);

// Định dạng tổng worklog, đảm bảo ngày không có dữ liệu hiển thị "0.00"
const formattedTotalWorklog = computed(() => {
    const result = {};

    // Đảm bảo tất cả ngày trong loggedDates có giá trị mặc định là "0.00"
    loggedDates.value.forEach((date) => {
        result[date] = totalWorklogByDate.value[date]
            ? totalWorklogByDate.value[date].toFixed(2)
            : "0.00";
    });

    return result;
});

// Hàm chỉ lấy ngày (bỏ tháng/năm)
const getDayOnly = (dateString) => {
    return new Date(dateString).getDate();
};

const getCellClass = (value, date) => {
    const numValue = parseFloat(value);
    const dayOfWeek = new Date(date).getDay(); // Lấy thứ trong tuần (0: Chủ Nhật, 6: Thứ Bảy)

    if ((dayOfWeek === 0 || dayOfWeek === 6) && numValue === 0) {
        return ""; // Không áp dụng nền đỏ cho Thứ 7, Chủ Nhật
    }

    if (numValue == 0) return "bg-red"; // Nếu worklog = 0, nền đỏ nhạt
    if (numValue > 0 && numValue < 8) return "bg-yellow"; // Nếu worklog < 8, nền vàng nhạt
    if (numValue > 8) return "bg-warning";

    return "";
};
</script>
