<template>
    <div>
        <upload-file-create-tasks
            v-if="!hasPermissionStaff"
            :projectId="projectId"
            :listAssignee="parsedListAssignee"
            :currentUserId="numberCurrentUserId"
            @update-task="handleTaskUpdate"
        ></upload-file-create-tasks>
        <task-add
            v-if="!hasPermissionStaff"
            :projectId="projectId"
            :listAssignee="parsedListAssignee"
            :currentUserId="numberCurrentUserId"
            @update-task="handleTaskUpdate"
        ></task-add>
        
        <task-search-box
            :tasks="tasks"
            @updateFilteredTasks="filteredTasks = $event"
            @blankQuery="handleBlankQuery"
            @updateVisibleColumns="updateVisibleColumns"
        ></task-search-box>

        <task-list
            :projectId="projectId"
            :filteredTasks="filteredTasks"
            :blankQuery="blankQuery"
            :visibleColumns="visibleColumns"
            :listAssignee="parsedListAssignee"
            :hasPermissionStaff="hasPermissionStaff"
            :currentUserId="numberCurrentUserId"
            @update-task="handleTaskUpdate"
        ></task-list>
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from "vue";
import axios from "axios";
import TaskAdd from "./TaskAdd.vue";
import TaskSearchBox from "./TaskSearchBox.vue";
import TaskList from "./TaskList.vue";
import UploadFileCreateTasks from "./UploadFileCreateTasks.vue";

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
    userRole: {
        type: [Array, String], // Có thể là Array hoặc String
        default: () => [],
    },
});

const userRoles = computed(() => {
    try {
        return JSON.parse(props.userRole); // Chuyển chuỗi JSON thành mảng
    } catch (error) {
        return []; // Trả về mảng rỗng nếu lỗi
    }
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

const fetchTasks = async () => {
    try {
        const url = hasPermissionStaff.value
            ? `/api/staff/${props.projectId}/tasks`
            : `/api/pm/${props.projectId}/tasks`;
        const { data } = await axios.get(url);
        tasks.value = data.tasks;
        filteredTasks.value = data.tasks;
    } catch (error) {
        console.error("Lỗi khi lấy dữ liệu task:", error);
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
const handleTaskUpdate = async () => {
    await fetchTasks();
};

onMounted(fetchTasks);
</script>
