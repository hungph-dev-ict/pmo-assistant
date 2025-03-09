<template>
    <div>
        <task-add v-if="hasPermissionClient || hasPermissionPm" :projectId="projectId"
            :listAssignee="listAssigneeByProject" :listPriorities="listTaskPriorities" :listStatuses="listTaskStatuses"
            :currentUserId="numberCurrentUserId" :hasPermissionClient="hasPermissionClient"
            :hasPermissionPm="hasPermissionPm" :hasPermissionStaff="hasPermissionStaff"
            @update-task="handleTaskUpdate"></task-add>

        <task-search-box @filter-changed="updatefilters" class="task-search-box"
            v-if="hasPermissionClient || hasPermissionPm || hasPermissionStaff" :tasks="tasks" :filters="filters"
            :listAssignee="listAssigneeByProject" :listStatuses="listTaskStatuses" :listPriorities="listTaskPriorities"
            @updateFilteredTasks="filteredTasks = $event" @blankQuery="handleBlankQuery"
            @updateVisibleColumns="updateVisibleColumns" :taskListEditing="taskListEditing">
        </task-search-box>

        <div class="task-list-container relative" ref="taskListContainer">
            <div v-if="taskListIsLoading" class="overlay">
                <div class="spinner"></div>
                <p>Loading...</p>
            </div>
            <task-list
                v-if="(hasPermissionClient || hasPermissionPm || hasPermissionStaff) && taskListData && taskListData.tasks"
                :filters="filters" :taskListData="taskListData" :projectId="projectId" :blankQuery="blankQuery"
                :visibleColumns="visibleColumns" :listAssignee="listAssigneeByProject" :listStatuses="listTaskStatuses"
                :listPriorities="listTaskPriorities" :hasPermissionClient="hasPermissionClient"
                :hasPermissionPm="hasPermissionPm" :hasPermissionStaff="hasPermissionStaff"
                :currentUserId="numberCurrentUserId" :currentUserAccount="currentUserAccount"
                @update-data="handleTaskUpdate" @task-list-editing="handleTaskListEditing" />
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, computed, nextTick, watch } from "vue";
import axios from "axios";
import TaskAdd from "./TaskAdd.vue";
import TaskSearchBox from "./TaskSearchBox.vue";
import TaskList from "./TaskList.vue";

const props = defineProps({
    projectId: String,
    currentUserId: {
        type: [Number, String], // Có thể là Number hoặc String
        default: 0,
    },
    currentUserAccount: {
        type: String, // Có thể là Number hoặc String
        default: "",
    },
    userRole: {
        type: [Array, String], // Có thể là Array hoặc String
        default: () => [],
    },
});

const filters = ref({
    text: "",
    priority: [],
    assignee: "",
    status: [],
});

const listAssigneeByProject = ref({});
const listTaskStatuses = ref({});
const listTaskPriorities = ref({});

const taskListContainer = ref(null);
const taskListEditing = ref(false);

const userRoles = computed(() => {
    try {
        return JSON.parse(props.userRole); // Chuyển chuỗi JSON thành mảng
    } catch (error) {
        return []; // Trả về mảng rỗng nếu lỗi
    }
});

const hasPermissionClient = computed(() => {
    return userRoles.value.includes("client");
});

const hasPermissionPm = computed(() => {
    return userRoles.value.includes("pm");
});

const hasPermissionStaff = computed(() => {
    return userRoles.value.includes("staff");
});

const numberCurrentUserId = computed(() => {
    return typeof props.currentUserId === "string"
        ? Number(props.currentUserId)
        : props.currentUserId;
});

const taskListData = ref({});
const tasks = ref([]); // Danh sách task gốc
const filteredTasks = ref([]); // Danh sách task đã lọc
const blankQuery = ref(true); // Mặc định là false
const queryParams = ref('');

const taskListIsLoading = ref(false); // Biến kiểm soát trạng thái loading

const fetchTasksByQuery = async (p_filters) => {
    taskListIsLoading.value = true; // Bắt đầu loading

    let apiUrl = computed(() => {
        return userRoles.value.includes("pm") || userRoles.value.includes("client")
            ? `/api/pm/${props.projectId}/list`
            : `/api/staff/${props.projectId}/list`;
    });

    let fullApiUrl = apiUrl.value; // Lấy URL cơ bản

    if (p_filters) {
        queryParams.value = new URLSearchParams(p_filters).toString();
        if (queryParams.value) {
            fullApiUrl += `?${queryParams.value}`; // Gắn query vào URL
        }
    }

    try {
        const { data } = await axios.get(fullApiUrl);
        taskListData.value = {
            ...taskListData.value,
            tasks: [...data.tasks] // Gán lại mảng mới
        };
    } catch (error) {
        console.error("Lỗi khi lấy dữ liệu:", error);
    } finally {
        taskListIsLoading.value = false; // Kết thúc loading
        nextTick(() => {
            if (taskListContainer.value) {
                taskListContainer.value.scrollIntoView({ behavior: "smooth" });
            }
        });
    }
};

// Khi blankQuery = true, reset danh sách task
const handleBlankQuery = (value) => {
    blankQuery.value = value;
    if (value) {
        filteredTasks.value = tasks.value;
    }
};

const visibleColumns = ref([
    "epic_task",
    "priority",
    "assignee",
    "plan_start_date",
    "plan_end_date",
    "plan-effort",
    "actual-effort",
    "action",
]);

const updateVisibleColumns = (columns) => {
    visibleColumns.value = columns;
};

// Khi task được cập nhật, fetch lại danh sách task
const handleTaskUpdate = (loadNew = false) => {
    const urlParams = new URLSearchParams(window.location.search);

    let hasParams = false; // Kiểm tra xem có tham số không
    const urlFilters = {}; // Dùng object tạm để tránh lỗi reactivity khi thay đổi ref trực tiếp

    for (const [key, value] of urlParams.entries()) {
        hasParams = true;

        // Nếu có dấu ",", chuyển thành mảng (tự động chuyển số nếu có)
        if (value.includes(",")) {
            urlFilters[key] = value.split(",").map(val => (isNaN(val) ? val : Number(val)));
        } else {
            // Nếu là số, chuyển thành Number, nếu không giữ nguyên
            urlFilters[key] = isNaN(value) ? value : Number(value);
        }
    }
    // Gán lại vào filters để Vue phản ứng
    filters.value = urlFilters;

    // Gọi API lấy danh sách tasks
    fetchTasksByQuery(hasParams ? filters.value : null);
};

const handleTaskListEditing = (status) => {
    taskListEditing.value = status;
};

const buildQueryParams = (p_filters) => {
    const params = new URLSearchParams(window.location.search);

    Object.keys(p_filters.value).forEach((key) => {
        const value = p_filters.value[key];

        if (
            (Array.isArray(value) && value.length > 0) ||  // Nếu là mảng, chỉ thêm khi có phần tử
            (!Array.isArray(value) && value !== "" && value !== null && value !== undefined) // Nếu không phải mảng, kiểm tra bình thường
        ) {
            params.set(key, Array.isArray(value) ? value.join(",") : value); // Convert mảng thành chuỗi nếu cần
        } else {
            params.delete(key); // Xóa nếu giá trị bị xóa hoặc không hợp lệ
        }
    });


    return params.toString();
};

const updateURL = () => {
    queryParams.value = buildQueryParams(filters);

    // Nếu có query params thì thêm `?`, nếu không thì để trống
    const newURL = queryParams.value ? `?${queryParams.value}` : window.location.pathname;

    window.history.pushState({}, "", newURL);
};

// Khi filters thay đổi, cập nhật URL
watch(filters, () => {
    updateURL();
}, { deep: true });

onMounted(async () => {
    const urlParams = new URLSearchParams(window.location.search);

    let hasParams = false; // Kiểm tra xem có tham số không
    const urlFilters = {}; // Dùng object tạm để tránh lỗi reactivity khi thay đổi ref trực tiếp

    for (const [key, value] of urlParams.entries()) {
        hasParams = true;

        // Nếu có dấu ",", chuyển thành mảng (tự động chuyển số nếu có)
        if (value.includes(",")) {
            urlFilters[key] = value.split(",").map(val => (isNaN(val) ? val : Number(val)));
        } else {
            // Nếu là số, chuyển thành Number, nếu không giữ nguyên
            urlFilters[key] = isNaN(value) ? value : Number(value);
        }
    }
    // Gán lại vào filters để Vue phản ứng
    filters.value = urlFilters;

    // Lấy danh sách members trong dự án
    try {
        const response = await axios.get(`/api/${props.projectId}/getAllMembers`);
        listAssigneeByProject.value = response.data.members;
    } catch (error) {
        console.error("Lỗi khi lấy dữ liệu:", error);
    }

    try {
        const response = await axios.get(`/api/getAllStatuses`);
        listTaskStatuses.value = response.data.statuses;
    } catch (error) {
        console.error("Lỗi khi lấy dữ liệu:", error);
    }

    try {
        const response = await axios.get(`/api/getAllPriorities`);
        listTaskPriorities.value = response.data.priorities;
    } catch (error) {
        console.error("Lỗi khi lấy dữ liệu:", error);
    }
});

// Nhận filters từ TaskSearch
const updatefilters = async (filtersFromSearch) => {
    filters.value = { ...filtersFromSearch };
    updateURL();
    fetchTasksByQuery(filters.value);

};
</script>

<style scoped>
.relative {
    position: relative;
}

.overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    color: white;
    font-weight: bold;
    z-index: 10;
    border-radius: 8px;
}

.spinner {
    width: 40px;
    height: 40px;
    border: 5px solid rgba(255, 255, 255, 0.3);
    border-top-color: white;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin-right: 10px;
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }

    100% {
        transform: rotate(360deg);
    }
}
</style>
