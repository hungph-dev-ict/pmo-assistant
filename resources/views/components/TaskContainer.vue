<template>
    <div>
        <task-search-box 
            :tasks="tasks" 
            @updateFilteredTasks="filteredTasks = $event" 
            @updateSearchQuery="searchQuery = $event"
            @updateSelectedAssignee="selectedAssignee = $event" 
            @updateSelectedStatuses="selectedStatuses = $event"
        ></task-search-box>
        
        <task-list 
            :filteredTasks="filteredTasks" 
            :searchQuery="searchQuery"
            :selectedAssignee="selectedAssignee" 
            :selectedStatuses="selectedStatuses"
        ></task-list>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import TaskSearchBox from "./TaskSearchBox.vue";
import TaskList from "./TaskList.vue";

const props = defineProps({ projectId: String });

const tasks = ref([]);
const filteredTasks = ref([]);
const searchQuery = ref("");  
const selectedAssignee = ref(""); 
const selectedStatuses = ref([]); // Thêm biến này để theo dõi trạng thái status

const fetchTasks = async () => {
    try {
        const { data } = await axios.get(`/api/pm/${props.projectId}/tasks`);
        tasks.value = data.tasks;
        filteredTasks.value = data.tasks;
    } catch (error) {
        console.error("Lỗi khi lấy dữ liệu task:", error);
    }
};

onMounted(fetchTasks);
</script>
