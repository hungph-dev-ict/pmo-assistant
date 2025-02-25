<template>
    <div>
        <worklog-management-calendar v-if="worklogs.length > 0" :worklogs="worklogs"></worklog-management-calendar>

        <worklog-search-box :worklogs="worklogs" @updateFilteredWorklogs="filteredWorklogs = $event"
            @blankQuery="handleBlankQuery" @updateVisibleColumns="updateVisibleColumns"></worklog-search-box>
        <div class="relative">
            <div v-if="worklogListIsLoading" class="overlay">
                <div class="spinner"></div>
                <p>Loading...</p>
            </div>
            <worklog-list :filteredWorklogs="filteredWorklogs" :blankQuery="blankQuery" :visibleColumns="visibleColumns"
                @update-worklog="handleWorklogUpdate"></worklog-list>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from "vue";
import axios from "axios";
import WorklogSearchBox from "./WorklogManagementSearchBox.vue";
import WorklogList from "./WorklogManagementList.vue";
import WorklogManagementCalendar from "./WorklogManagementCalendar.vue";

const props = defineProps({});

const worklogs = ref([]); // Danh sách task gốc

const filteredWorklogs = ref([]); // Danh sách task đã lọc
const blankQuery = ref(true); // Mặc định là false

const worklogListIsLoading = ref(false); // Biến kiểm soát trạng thái loading

const currentPath = computed(() => window.location.pathname);

const isPMRoute = computed(() => currentPath.value.includes("pm/"));
const isTenantRoute = computed(() => currentPath.value.includes("tenant/"));

const fetchWorklogs = async () => {
    worklogListIsLoading.value = true; // Bắt đầu loading
    try {
        let data;
        if (isTenantRoute.value) {
            const response = await axios.get("/api/tenant-worklog");
            data = response.data;
        } else if (isPMRoute.value) {
            const pathSegments = window.location.pathname.split("/");
            const projectId = pathSegments[2];

            const response = await axios.get(`/api/project/${projectId}/worklog`);
            data = response.data;
        }

        if (data?.original?.success) {
            const oldFilteredWorklogs = new Set(
                filteredWorklogs.value.map((worklog) => worklog.id)
            ); // Lưu ID của worklogs đã lọc

            worklogs.value = data.original.data;

            // Nếu danh sách filteredWorklogs ban đầu rỗng, giữ toàn bộ worklogs mới
            if (filteredWorklogs.value.length === 0) {
                filteredWorklogs.value = [...worklogs.value];
            } else {
                // Giữ lại danh sách đã lọc trước đó nếu có
                filteredWorklogs.value = worklogs.value.filter((worklog) =>
                    oldFilteredWorklogs.has(worklog.id)
                );
            }
        } else {
            console.error("API trả về lỗi:", data);
        }
    } catch (error) {
        console.error("Lỗi khi lấy dữ liệu worklog:", error);
    } finally {
        worklogListIsLoading.value = false; // Kết thúc loading
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
    "assignee",
    "logged-user",
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
    background: rgba(116, 114, 114, 0.5);
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
