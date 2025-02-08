<template>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Search Box</h3>
        </div>

        <div class="card-body">
            <div class="row">
                <!-- Tìm kiếm Epic -->
                <div class="col-6">
                    <div class="form-group">
                        <label>Search Epic:</label>
                        <div class="position-relative">
                            <input
                                v-model="searchEpic"
                                type="text"
                                class="form-control"
                                placeholder="Enter epic name"
                                @input="filterTasks"
                            />
                            <button
                                v-if="searchEpic"
                                @click="resetEpic"
                                class="btn btn-sm btn-light position-absolute"
                                style="
                                    right: 10px;
                                    top: 50%;
                                    transform: translateY(-50%);
                                "
                            >
                                ✖
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Tìm kiếm Task -->
                <div class="col-6">
                    <div class="form-group">
                        <label>Search Task:</label>
                        <div class="position-relative">
                            <input
                                v-model="searchTask"
                                type="text"
                                class="form-control"
                                placeholder="Enter task name"
                                @input="filterTasks"
                            />
                            <button
                                v-if="searchTask"
                                @click="resetTask"
                                class="btn btn-sm btn-light position-absolute"
                                style="
                                    right: 10px;
                                    top: 50%;
                                    transform: translateY(-50%);
                                "
                            >
                                ✖
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, watch } from "vue";

const props = defineProps({
    tasks: Array, // Danh sách tasks từ component cha
});

const emit = defineEmits(["updateFilteredTasks"]);

const searchEpic = ref("");
const searchTask = ref("");

const filterTasks = () => {
    let filtered = props.tasks;

    if (searchEpic.value.trim()) {
        // Lọc theo Epic (hiển thị Epic và tất cả task của nó)
        filtered = props.tasks.filter((epic) =>
            epic.name.toLowerCase().includes(searchEpic.value.toLowerCase())
        );
    }

    if (searchTask.value.trim()) {
        // Lọc theo Task (hiển thị Epic chứa task đó + task phù hợp)
        filtered = props.tasks
            .map((epic) => {
                const matchingTasks = epic.children.filter((task) =>
                    task.name
                        .toLowerCase()
                        .includes(searchTask.value.toLowerCase())
                );
                if (matchingTasks.length > 0) {
                    return { ...epic, children: matchingTasks };
                }
                return null;
            })
            .filter((epic) => epic !== null);
    }

    emit("updateFilteredTasks", filtered);
};

const resetEpic = () => {
    searchEpic.value = "";
    filterTasks();
};

const resetTask = () => {
    searchTask.value = "";
    filterTasks();
};
</script>
