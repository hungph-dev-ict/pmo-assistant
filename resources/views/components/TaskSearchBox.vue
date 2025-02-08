<template>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Search Box</h3>
        </div>

        <div class="card-body">
            <div class="row">
                <!-- Tìm kiếm chung -->
                <div class="col-4">
                    <div class="form-group">
                        <label>Search:</label>
                        <div class="position-relative">
                            <input v-model="searchQuery" type="text" class="form-control" placeholder="Enter name"
                                @input="
                                    filterTasks();
                                    emit('updateSearchQuery', searchQuery);
                                " />
                            <button v-if="searchQuery" @click="resetSearch"
                                class="btn btn-sm btn-light position-absolute"
                                style="right: 10px; top: 50%; transform: translateY(-50%);">
                                ✖
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Tìm kiếm Assignee -->
                <div class="col-4">
                    <div class="form-group">
                        <label>Search Assignee:</label>
                        <select ref="assigneeSelect" class="form-control select2" style="width: 100%;">
                            <option value="">-- Select Assignee --</option>
                            <option v-for="assignee in uniqueAssignees" :key="assignee" :value="assignee">
                                {{ assignee }}
                            </option>
                        </select>
                    </div>
                </div>

                <!-- Tìm kiếm theo Status (Multiple) -->
                <div class="col-4">
                    <div class="form-group">
                        <label>Search Status:</label>
                        <select ref="statusSelect" class="form-control select2" multiple="multiple"
                            data-placeholder="Select Status" style="width: 100%;">
                            <option v-for="status in uniqueStatuses" :key="status" :value="status">
                                {{ status }}
                            </option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, nextTick } from "vue";

const assigneeSelect = ref(null);
const statusSelect = ref(null);
const searchQuery = ref("");
const selectedAssignee = ref("");
const selectedStatuses = ref([]); // Mảng lưu nhiều status

onMounted(() => {
    nextTick(() => {
        if (window.jQuery && $.fn.select2) {
            // Select2 cho Assignee
            $(assigneeSelect.value).select2({
                placeholder: "Choose an assignee",
                allowClear: true,
            }).on("change", (e) => {
                selectedAssignee.value = e.target.value;
                filterTasks();
                emit("updateSelectedAssignee", selectedAssignee.value);
            });

            // Select2 cho Status (Multiple)
            $(statusSelect.value).select2({
                placeholder: "Select Status",
                allowClear: true,
            }).on("change", (e) => {
                selectedStatuses.value = $(e.target).val() || [];
                filterTasks();
                emit("updateSelectedStatuses", selectedStatuses.value);
            });

        } else {
            console.error("Select2 is not loaded properly.");
        }
    });
});

const props = defineProps({
    tasks: Array, // Danh sách tasks đã phẳng từ backend
});

const emit = defineEmits(["updateFilteredTasks", "updateSearchQuery", "updateSelectedAssignee", "updateSelectedStatuses"]);

const uniqueAssignees = computed(() => {
    return Array.from(new Set(props.tasks.map(task => task.assignee?.account).filter(Boolean)));
});

const uniqueStatuses = computed(() => {
    return Array.from(new Set(props.tasks.map(task => task.status).filter(Boolean)));
});

const filterTasks = () => {
    let filtered = props.tasks;

    if (searchQuery.value.trim()) {
        filtered = filtered.filter(task =>
            task.name.toLowerCase().includes(searchQuery.value.toLowerCase())
        );
    }

    if (selectedAssignee.value) {
        filtered = filtered.filter(task => task.assignee?.account === selectedAssignee.value);
    }

    if (selectedStatuses.value.length > 0) {
        filtered = filtered.filter(task => selectedStatuses.value.includes(task.status));
    }

    emit("updateFilteredTasks", filtered);
};

const resetSearch = () => {
    searchQuery.value = "";
    filterTasks();
    emit("updateSearchQuery", ""); // ✅ Chỉ reset searchQuery, không ảnh hưởng đến assignee/status
};
</script>
