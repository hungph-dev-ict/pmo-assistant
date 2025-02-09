<template>
    <div>
        <task-search-box
            :tasks="tasks"
            @updateFilteredTasks="filteredTasks = $event"
            @blankQuery="handleBlankQuery"
            @updateVisibleColumns="updateVisibleColumns"
        ></task-search-box>

        <task-list
            :filteredTasks="filteredTasks"
            :blankQuery="blankQuery"
            :visibleColumns="visibleColumns"
        ></task-list>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import TaskSearchBox from "./TaskSearchBox.vue";
import TaskList from "./TaskList.vue";

const props = defineProps({ projectId: String });

const tasks = ref([]); // Danh sách task gốc
const filteredTasks = ref([]); // Danh sách task đã lọc
const blankQuery = ref(true); // Mặc định là false

const fetchTasks = async () => {
    try {
        const { data } = await axios.get(`/api/pm/${props.projectId}/tasks`);
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
    "assignee",
    "plan_start_date",
    "plan_end_date",
]);

const updateVisibleColumns = (columns) => {
    visibleColumns.value = columns;
};

onMounted(fetchTasks);
</script>
