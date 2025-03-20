<template>
    <div>
        <div class="relative">
            <div v-if="leaveRequestListIsLoading" class="overlay">
                <div class="spinner"></div>
                <p>Loading...</p>
            </div>
            <leave-request-list
                v-if="isClientOrPm"
                :isClientOrPm="true"
                :userRole="props.userRole"
                :currentUserId="props.currentUserId"
                :filteredLeaveRequests="filteredLeaveRequestsManagement"
                :blankQuery="blankQuery"
                :visibleColumns="visibleColumns"
                @update-leave-request="handleLeaveRequestUpdate"
            ></leave-request-list>
            <leave-request-list
                :userRole="props.userRole"
                :isClientOrPm="false"
                :currentUserId="props.currentUserId"
                :filteredLeaveRequests="filteredLeaveRequests"
                :blankQuery="blankQuery"
                :visibleColumns="visibleColumns"
                @update-leave-request="handleLeaveRequestUpdate"
            ></leave-request-list>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, watch } from "vue";
import axios from "axios";
import LeaveRequestList from "./LeaveRequestList.vue";
const props = defineProps({    
    userRole: {
        type: [Array, String], // Có thể là Array hoặc String
        default: () => [],
    },
    currentUserId: {
        type: [Number, String], // Có thể là Number hoặc String
        default: 0,
    },
});

const isClientOrPm = props.userRole.includes("client") || props.userRole.includes("pm");

const leaveRequests = ref([]); 

const filteredLeaveRequests = ref([]); 

const leaveRequestsManagement = ref([]); 

const filteredLeaveRequestsManagement = ref([]); 

const blankQuery = ref(true); // Mặc định là false

const leaveRequestListIsLoading = ref(false);


const leaveRequestListManagementIsLoading = ref(false); 

const fetchLeaveRequests = async () => {
    leaveRequestListIsLoading.value = true; // Bắt đầu loading
    try {
        const { data } = await axios.get("/api/leave-request");
        if (data.original.success) {
            leaveRequests.value = data.original.data;
            filteredLeaveRequests.value = [...leaveRequests.value];
        } else {
            console.error("API trả về lỗi:", data);
        }
    } catch (error) {
        console.error("Lỗi khi lấy dữ liệu leave request:", error);
    } finally {
        leaveRequestListIsLoading.value = false; // Kết thúc loading
    }
};

const fetchLeaveRequestsManagement = async () => {
    leaveRequestListManagementIsLoading.value = true; // Bắt đầu loading
    try {
        const { data } = await axios.get("/api/leave-request-management");
        if (data.original.success) {
            leaveRequestsManagement.value = data.original.data;
            filteredLeaveRequestsManagement.value = [...leaveRequestsManagement.value];
        } else {
            console.error("API trả về lỗi:", data);
        }
    } catch (error) {
        console.error("Lỗi khi lấy dữ liệu leave request:", error);
    } finally {
        leaveRequestListManagementIsLoading.value = false; // Kết thúc loading
    }
};

// Khi blankQuery = true, reset danh sách task
const handleBlankQuery = (value) => {
    blankQuery.value = value;
    if (value) {
        filteredLeaveRequests.value = leaveRequests.value;
        filteredLeaveRequestsManagement.value = leaveRequestsManagement.value;
    }
};

const visibleColumns = ref([
    "leave-user",
    "leave-type",
    "leave-date",
    "leave-time",
    "leave-reason",
    "leave-approver",
    "leave-status",
    "action",
]);

const updateVisibleColumns = (columns) => {
    visibleColumns.value = columns;
};

// Khi task được cập nhật, fetch lại danh sách task
const handleLeaveRequestUpdate = async () => {
    await fetchLeaveRequests();
    if(isClientOrPm){
        await fetchLeaveRequestsManagement();
    }
};

onMounted(() => {
    fetchLeaveRequests();
    if(isClientOrPm){
        fetchLeaveRequestsManagement();
    }; 
});
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
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
