<template>
    <div>
            <task-add v-if="hasPermissionClient || hasPermissionPm" :projectId="projectId"
            :listAssignee="parsedListAssignee" :currentUserId="numberCurrentUserId"
            :hasPermissionClient="hasPermissionClient" :hasPermissionPm="hasPermissionPm"
            :hasPermissionStaff="hasPermissionStaff" @update-task="handleTaskUpdate"></task-add>

        <task-search-box @filter-changed="updateFiltersQuery" class="task-search-box"
            v-if="hasPermissionClient || hasPermissionPm || hasPermissionStaff" :tasks="tasks"
            @updateFilteredTasks="filteredTasks = $event" @blankQuery="handleBlankQuery"
            @updateVisibleColumns="updateVisibleColumns">
        </task-search-box>

        <div class="task-list-container relative" ref="taskListContainer">
            <div v-if="taskListIsLoading" class="overlay">
                <div class="spinner"></div>
                <p>Loading...</p>
            </div>
            <task-list :filtersQuery="filtersQuery" v-if="
                hasPermissionClient || hasPermissionPm || hasPermissionStaff
            " :projectId="projectId" :filteredTasks="filteredTasks" :blankQuery="blankQuery"
                :visibleColumns="visibleColumns" :listAssignee="parsedListAssignee"
                :hasPermissionClient="hasPermissionClient" :hasPermissionPm="hasPermissionPm"
                :hasPermissionStaff="hasPermissionStaff" :currentUserId="numberCurrentUserId"
                :currentUserAccount="currentUserAccount" @update-data="handleTaskUpdate" />
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
    listAssignee: {
        type: [Array, String], // Có thể là Array hoặc String
        default: () => [],
    },
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

const taskListContainer = ref(null);

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

const parsedListAssignee = computed(() => {
    return typeof props.listAssignee === "string"
        ? JSON.parse(props.listAssignee)
        : props.listAssignee;
});

const numberCurrentUserId = computed(() => {
    return typeof props.currentUserId === "string"
        ? Number(props.currentUserId)
        : props.currentUserId;
});

const tasks = ref([]); // Danh sách task gốc
const filteredTasks = ref([]); // Danh sách task đã lọc
const blankQuery = ref(true); // Mặc định là false

const taskListIsLoading = ref(false); // Biến kiểm soát trạng thái loading

const fetchTasks = async (loadNew = false) => {
    taskListIsLoading.value = true; // Bắt đầu loading
    try {
        const url = hasPermissionStaff.value
            ? `/api/staff/${props.projectId}/tasks`
            : `/api/pm/${props.projectId}/tasks`;
        const { data } = await axios.get(url);

        const oldFilteredTasks = new Set(
            filteredTasks.value.map((task) => task.id)
        ); // Lưu ID của tasks đã lọc

        tasks.value = data.tasks;

        // Nếu danh sách filteredTasks ban đầu rỗng, giữ toàn bộ tasks mới
        if (filteredTasks.value.length === 0 || loadNew) {
            filteredTasks.value = [...tasks.value];
        } else {
            // Giữ lại danh sách đã lọc trước đó nếu có
            filteredTasks.value = tasks.value.filter((task) =>
                oldFilteredTasks.has(task.id)
            );
        }
    } catch (error) {
        console.error("Lỗi khi lấy dữ liệu task:", error);
    } finally {
        taskListIsLoading.value = false; // Kết thúc loading
        nextTick(() => {
            if (taskListContainer.value && loadNew) {
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
    fetchTasks(loadNew);
};

// onMounted(fetchTasks);

const filtersQuery = ref({
    statusId: [],
    search: "",
});

const updateURL = () => {
    const queryParams = new URLSearchParams(filtersQuery.value).toString();
    window.history.pushState({}, "", `?${queryParams}`);
};

// Khi filters thay đổi, cập nhật URL
watch(filtersQuery, () => {
    updateURL();
}, { deep: true });

// Khi trang load, lấy query từ URL
onMounted(() => {
    const urlParams = new URLSearchParams(window.location.search);
    for (const [key, value] of urlParams.entries()) {
        if (key === "statusId") {
            filtersQuery.value[key] = value.split(",").map(Number); // Convert thành array number
        } else {
            filtersQuery.value[key] = value; // Gán trực tiếp nếu không phải statusId
        }
    }

    // 🔹 Nếu không có filter nào -> Lấy toàn bộ records
    const isFiltersEmpty = computed(() => {
        return JSON.stringify(filtersQuery.value) === JSON.stringify({ statusId: [], search: "" });
    });
    console.log(isFiltersEmpty.value);
    if (isFiltersEmpty.value) {
        fetchTasks(true);
    }
});

// Nhận filters từ TaskSearch
const updateFiltersQuery = (newFiltersQuery) => {
    filtersQuery.value = { ...newFiltersQuery };
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
