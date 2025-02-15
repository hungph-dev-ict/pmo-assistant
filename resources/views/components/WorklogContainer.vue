<template>
    <div>
        <worklog-calendar v-if="worklogs.length > 0" :worklogs="worklogs"></worklog-calendar>

        <worklog-search-box :worklogs="worklogs" @updateFilteredWorklogs="filteredWorklogs = $event"
            @blankQuery="handleBlankQuery" @updateVisibleColumns="updateVisibleColumns"></worklog-search-box>

        <worklog-list :filteredWorklogs="filteredWorklogs" :blankQuery="blankQuery" :visibleColumns="visibleColumns"
            @update-worklog="handleWorklogUpdate"></worklog-list>

    </div>
</template>

<script setup>
import { ref, onMounted, watch } from "vue";
import axios from "axios";
import WorklogSearchBox from "./WorklogSearchBox.vue";
import WorklogList from "./WorklogList.vue";
import WorklogCalendar from "./WorklogCalendar.vue";

const props = defineProps({});

const worklogs = ref([]); // Danh sách task gốc

const filteredWorklogs = ref([]); // Danh sách task đã lọc
const blankQuery = ref(true); // Mặc định là false

const fetchWorklogs = async () => {
    try {
        axios.get('/api/tenant-worklog')
            .then(response => {
                if (response.data.original.success) {
                    worklogs.value = response.data.original.data; // Lấy danh sách worklogs
                    filteredWorklogs.value = response.data.original.data;
                } else {
                    console.error('API trả về lỗi:', response.data);
                }
            })
            .catch(error => {
                console.error('Lỗi khi gọi API:', error);
            });
    } catch (error) {
        console.error("Lỗi khi lấy dữ liệu worklog:", error);
    }
};

// Khi blankQuery = true, reset danh sách task
const handleBlankQuery = (value) => {
    blankQuery.value = value;
    if (value) {
        filteredWorklogs.value = worklogs.value;
    }
};

const visibleColumns = ref([
    "project-name",
    "epic_task",
    "logged-date",
    "logged-time",
    "description",
    "action",
]);

const updateVisibleColumns = (columns) => {
    visibleColumns.value = columns;
};

// Khi task được cập nhật, fetch lại danh sách task
const handleWorklogUpdate = async () => {
    await fetchWorklogs();
};

onMounted(fetchWorklogs);
</script>
