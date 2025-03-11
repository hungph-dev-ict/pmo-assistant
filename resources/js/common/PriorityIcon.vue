<template>
    <div class="flex items-center justify-center w-6 h-6">
        <!-- Trivial - Hình tròn xám -->
        <svg
            v-if="priority === TASK_PRIORITY.TRIVIAL"
            width="25"
            height="25"
            viewBox="0 0 24 24"
        >
            <circle
                cx="12"
                cy="12"
                r="8"
                stroke="gray"
                stroke-width="2"
                fill="none"
            />
        </svg>

        <!-- Lowest - Ba mũi tên xuống màu xanh -->
        <svg
            v-else-if="priority === TASK_PRIORITY.LOWEST"
            width="25"
            height="25"
            viewBox="0 0 24 24"
        >
            <polyline
                points="6,4 12,10 18,4"
                stroke="blue"
                stroke-width="2"
                fill="none"
            />
            <polyline
                points="6,9 12,15 18,9"
                stroke="blue"
                stroke-width="2"
                fill="none"
            />
            <polyline
                points="6,14 12,20 18,14"
                stroke="blue"
                stroke-width="2"
                fill="none"
            />
        </svg>

        <!-- Lower - Hai mũi tên xuống màu xanh -->
        <svg
            v-else-if="priority === TASK_PRIORITY.LOWER"
            width="25"
            height="25"
            viewBox="0 0 24 24"
        >
            <polyline
                points="6,6 12,12 18,6"
                stroke="blue"
                stroke-width="2"
                fill="none"
            />
            <polyline
                points="6,12 12,18 18,12"
                stroke="blue"
                stroke-width="2"
                fill="none"
            />
        </svg>

        <!-- Low - Một mũi tên xuống màu xanh -->
        <svg
            v-else-if="priority === TASK_PRIORITY.LOW"
            width="25"
            height="25"
            viewBox="0 0 24 24"
        >
            <polyline
                points="6,8 12,16 18,8"
                stroke="blue"
                stroke-width="2"
                fill="none"
            />
        </svg>

        <!-- Minor - Hai dấu '=' màu vàng -->
        <svg
            v-else-if="priority === TASK_PRIORITY.MINOR"
            width="25"
            height="25"
            viewBox="0 0 24 24"
        >
            <line
                x1="6"
                y1="10"
                x2="18"
                y2="10"
                stroke="goldenrod"
                stroke-width="2"
            />
            <line
                x1="6"
                y1="14"
                x2="18"
                y2="14"
                stroke="goldenrod"
                stroke-width="2"
            />
        </svg>

        <!-- High - Một mũi tên lên màu đỏ -->
        <svg
            v-else-if="priority === TASK_PRIORITY.HIGH"
            width="25"
            height="25"
            viewBox="0 0 24 24"
        >
            <polyline
                points="6,16 12,8 18,16"
                stroke="red"
                stroke-width="2"
                fill="none"
            />
        </svg>

        <!-- Higher - Hai mũi tên lên màu đỏ -->
        <svg
            v-else-if="priority === TASK_PRIORITY.HIGHER"
            width="25"
            height="25"
            viewBox="0 0 24 24"
        >
            <polyline
                points="6,16 12,10 18,16"
                stroke="red"
                stroke-width="2"
                fill="none"
            />
            <polyline
                points="6,10 12,4 18,10"
                stroke="red"
                stroke-width="2"
                fill="none"
            />
        </svg>

        <!-- Highest - Ba mũi tên lên màu đỏ -->
        <svg
            v-else-if="priority === TASK_PRIORITY.HIGHEST"
            width="25"
            height="25"
            viewBox="0 0 24 24"
        >
            <polyline
                points="6,20 12,14 18,20"
                stroke="red"
                stroke-width="2"
                fill="none"
            />
            <polyline
                points="6,14 12,8 18,14"
                stroke="red"
                stroke-width="2"
                fill="none"
            />
            <polyline
                points="6,8 12,2 18,8"
                stroke="red"
                stroke-width="2"
                fill="none"
            />
        </svg>

        <!-- Critical - Biểu tượng lá chắn đỏ -->
        <svg
            v-else-if="priority === TASK_PRIORITY.CRITICAL"
            width="25"
            height="25"
            viewBox="0 0 24 24"
        >
            <polygon points="6,6 18,6 18,18 12,22 6,18" fill="red" />
        </svg>

        <!-- Blocker - Hình tròn đỏ với dấu '-' -->
        <svg
            v-else-if="priority === TASK_PRIORITY.BLOCKER"
            width="25"
            height="25"
            viewBox="0 0 24 24"
        >
            <circle cx="12" cy="12" r="8" fill="red" />
            <line
                x1="7"
                y1="12"
                x2="17"
                y2="12"
                stroke="white"
                stroke-width="2"
            />
        </svg>
    </div>
</template>

<script setup>
import { TASK_PRIORITY } from "../constants/taskConstants";

defineProps({
    priority: Number,
});
</script>
