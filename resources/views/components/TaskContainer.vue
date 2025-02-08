<template>
    <div>
        <task-search-box :tasks="tasks" @updateFilteredTasks="filteredTasks = $event"></task-search-box>
        <task-list :filteredTasks="filteredTasks"></task-list>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import TaskSearchBox from "./TaskSearchBox.vue";
import TaskList from "./TaskList.vue";

const props = defineProps({ projectId: String });

const tasks = ref([]);
const filteredTasks = ref([]); // Dữ liệu hiển thị sau khi lọc

const fetchTasks = async () => {
    try {
        const { data } = await axios.get(`/api/pm/${props.projectId}/tasks`);
        tasks.value = data.tasks;
        filteredTasks.value = data.tasks; // Ban đầu hiển thị toàn bộ
    } catch (error) {
        console.error("Lỗi khi lấy dữ liệu task:", error);
    }
};

onMounted(fetchTasks);
</script>
