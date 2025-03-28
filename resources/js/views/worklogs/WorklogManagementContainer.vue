<template>
    <div>
        <div class="calendar-container" v-if="worklogs.length > 0">
            <worklog-management-calendar :key="leaveRequests.length"  :worklogs="worklogs" :leaveRequests="leaveRequests"></worklog-management-calendar>
        </div>

        <worklog-search-box :worklogs="worklogs" @updateFilteredWorklogs="filteredWorklogs = $event"
            @blankQuery="handleBlankQuery" @updateVisibleColumns="updateVisibleColumns"></worklog-search-box>
        <div class="relative list-container">
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
const leaveRequests = ref([]); // Danh sách leaveRequests gốc
const blankQuery = ref(true); // Mặc định là false
const worklogListIsLoading = ref(false); // Biến kiểm soát trạng thái loading

const currentPath = computed(() => window.location.pathname);
const isPMRoute = computed(() => currentPath.value.includes("pm/"));
const isTenantRoute = computed(() => currentPath.value.includes("tenant/"));

// Add onMounted hook to fetch initial data
onMounted(async () => {
    await fetchWorklogs();
    await fetchLeaveRequests();
});

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
            worklogs.value = data.original.data;
            filteredWorklogs.value = [...worklogs.value]; // Initialize filtered worklogs with all worklogs
        } else {
            console.error("API trả về lỗi:", data);
            toastr.error("Failed to fetch worklogs");
        }
    } catch (error) {
        console.error("Lỗi khi lấy dữ liệu worklog:", error);
        toastr.error("Failed to fetch worklogs");
    } finally {
        worklogListIsLoading.value = false; // Kết thúc loading
    }
};

const fetchLeaveRequests = async () => {
    worklogListIsLoading.value = true; // Bắt đầu loading
    try {
        let data;
        if (isTenantRoute.value) {
            const response = await axios.get("/api/tenant-leave-request");
            data = response.data;
        } else if (isPMRoute.value) {
            const pathSegments = window.location.pathname.split("/");
            const projectId = pathSegments[2];
            const response = await axios.get(`/api/project/${projectId}/leave-request`);
            data = response.data;
        }

        if (data?.original?.success) {
            leaveRequests.value = [...data.original.data];
        } else {
            console.error("API trả về lỗi:", data);
            toastr.error("Failed to fetch leave requests");
        }
    } catch (error) {
        console.error("Lỗi khi lấy dữ liệu leave requests:", error);
        toastr.error("Failed to fetch leave requests");
    } finally {
        worklogListIsLoading.value = false; // Kết thúc loading
    }
};

// Khi blankQuery = true, reset danh sách task
const handleBlankQuery = (value) => {
    blankQuery.value = value;
    if (value) {
        filteredWorklogs.value = [...worklogs.value];
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

const handleWorklogUpdate = async () => {
    await fetchWorklogs();
    await fetchLeaveRequests();
};
</script>

<style scoped>
.relative {
    position: relative;
}

.calendar-container {
    max-height: 600px;
    overflow-y: auto;
    margin-bottom: 20px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.list-container {
    max-height: calc(100vh - 400px);
    overflow-y: auto;
    border: 1px solid #ddd;
    border-radius: 4px;
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

/* Custom scrollbar styles */
.calendar-container::-webkit-scrollbar,
.list-container::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

.calendar-container::-webkit-scrollbar-track,
.list-container::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

.calendar-container::-webkit-scrollbar-thumb,
.list-container::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 4px;
}

.calendar-container::-webkit-scrollbar-thumb:hover,
.list-container::-webkit-scrollbar-thumb:hover {
    background: #555;
}
</style>
