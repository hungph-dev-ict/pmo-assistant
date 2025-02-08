<template>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">List Task</h3>
        </div>

        <div class="card-body p-0">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Epic</th>
                        <th>Task</th>
                        <th>Assignee</th>
                        <th>Plan Start Date</th>
                        <th>Plan End Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="epic in filteredTasks" :key="epic.id">
                        <tr class="bg-light">
                            <td>{{ epic.id }}</td>
                            <td>{{ epic.name }}</td>
                            <td></td>
                            <td>{{ epic.assignee.account }}</td>
                            <td>{{ epic.plan_start_date }}</td>
                            <td>{{ epic.plan_end_date }}</td>
                            <td>{{ epic.status }}</td>
                        </tr>

                        <tr v-for="task in epic.children" :key="task.id">
                            <td>{{ task.id }}</td>
                            <td></td>
                            <td>└ {{ task.name }}</td>
                            <td>{{ task.assignee.account }}</td>
                            <td>{{ task.plan_start_date }}</td>
                            <td>{{ task.plan_end_date }}</td>
                            <td>{{ task.status }}</td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup>
defineProps({
    filteredTasks: Array, // Danh sách đã lọc từ TaskSearchBox.vue
});
</script>
