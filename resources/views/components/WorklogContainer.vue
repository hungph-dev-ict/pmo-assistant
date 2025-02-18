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
        const { data } = await axios.get('/api/my-worklog');

        if (data.original.success) {
            const oldFilteredWorklogs = new Set(filteredWorklogs.value.map(worklog => worklog.id)); // Lưu ID của worklogs đã lọc

            worklogs.value = data.original.data;

            // Nếu danh sách filteredWorklogs ban đầu rỗng, giữ toàn bộ worklogs mới
            if (filteredWorklogs.value.length === 0) {
                filteredWorklogs.value = [...worklogs.value];
            } else {
                // Giữ lại danh sách đã lọc trước đó nếu có
                filteredWorklogs.value = worklogs.value.filter(worklog => oldFilteredWorklogs.has(worklog.id));
            }
        } else {
            console.error('API trả về lỗi:', data);
        }
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
