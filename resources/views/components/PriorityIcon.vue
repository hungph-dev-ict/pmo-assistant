<template>
  <div class="flex items-center">
    <!-- Trivial - Hình tròn xám -->
    <svg v-if="priority === 'Trivial'" width="24" height="24" viewBox="0 0 24 24">
      <circle cx="12" cy="12" r="10" stroke="gray" stroke-width="3" fill="none" />
    </svg>

    <!-- Lowest - Ba mũi tên xuống màu xanh -->
    <svg v-else-if="priority === 'Lowest'" width="24" height="24" viewBox="0 0 24 24">
      <polyline points="4,6 12,14 20,6" stroke="blue" stroke-width="3" fill="none" />
      <polyline points="4,10 12,18 20,10" stroke="blue" stroke-width="3" fill="none" />
      <polyline points="4,14 12,22 20,14" stroke="blue" stroke-width="3" fill="none" />
    </svg>

    <!-- Lower - Hai mũi tên xuống màu xanh -->
    <svg v-else-if="priority === 'Lower'" width="24" height="24" viewBox="0 0 24 24">
      <polyline points="4,8 12,16 20,8" stroke="blue" stroke-width="3" fill="none" />
      <polyline points="4,12 12,20 20,12" stroke="blue" stroke-width="3" fill="none" />
    </svg>

    <!-- Low - Một mũi tên xuống màu xanh -->
    <svg v-else-if="priority === 'Low'" width="24" height="24" viewBox="0 0 24 24">
      <polyline points="4,12 12,20 20,12" stroke="blue" stroke-width="3" fill="none" />
    </svg>

    <!-- Minor - Hai dấu '=' màu vàng -->
    <svg v-else-if="priority === 'Minor'" width="24" height="24" viewBox="0 0 24 24">
      <line x1="4" y1="10" x2="20" y2="10" stroke="goldenrod" stroke-width="3" />
      <line x1="4" y1="14" x2="20" y2="14" stroke="goldenrod" stroke-width="3" />
    </svg>

    <!-- High - Một mũi tên lên màu đỏ -->
    <svg v-else-if="priority === 'High'" width="24" height="24" viewBox="0 0 24 24">
      <polyline points="4,16 12,8 20,16" stroke="red" stroke-width="3" fill="none" />
    </svg>

    <!-- Higher - Hai mũi tên lên màu đỏ -->
    <svg v-else-if="priority === 'Higher'" width="24" height="24" viewBox="0 0 24 24">
      <polyline points="4,16 12,8 20,16" stroke="red" stroke-width="3" fill="none" />
      <polyline points="4,12 12,4 20,12" stroke="red" stroke-width="3" fill="none" />
    </svg>

    <!-- Highest - Ba mũi tên lên màu đỏ -->
    <svg v-else-if="priority === 'Highest'" width="24" height="24" viewBox="0 0 24 24">
      <polyline points="4,16 12,8 20,16" stroke="red" stroke-width="3" fill="none" />
      <polyline points="4,12 12,4 20,12" stroke="red" stroke-width="3" fill="none" />
      <polyline points="4,8 12,0 20,8" stroke="red" stroke-width="3" fill="none" />
    </svg>

    <!-- Critical - Biểu tượng lá chắn đỏ -->
    <svg v-else-if="priority === 'Critical'" width="24" height="24" viewBox="0 0 24 24">
      <polygon points="4,4 20,4 20,20 12,24 4,20" fill="red" />
    </svg>

    <!-- Blocker - Hình tròn đỏ với dấu '-' -->
    <svg v-else-if="priority === 'Blocker'" width="24" height="24" viewBox="0 0 24 24">
      <circle cx="12" cy="12" r="10" fill="red" />
      <line x1="6" y1="12" x2="18" y2="12" stroke="white" stroke-width="3" />
    </svg>
  </div>
</template>

<script setup>
defineProps({
  priority: String,
});
</script>
