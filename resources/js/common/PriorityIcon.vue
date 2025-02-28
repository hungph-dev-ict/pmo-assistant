<template>
  <div class="flex items-center">
    <!-- Trivial - Hình tròn xám -->
    <svg v-if="priority === 'Trivial'" width="20" height="20" viewBox="0 0 24 24">
      <circle cx="12" cy="13" r="8" stroke="gray" stroke-width="2" fill="none" />
    </svg>

    <!-- Lowest - Ba mũi tên xuống màu xanh -->
    <svg v-else-if="priority === 'Lowest'" width="20" height="20" viewBox="0 0 24 24">
      <polyline points="5,6 12,13 19,6" stroke="blue" stroke-width="2" fill="none" />
      <polyline points="5,11 12,18 19,11" stroke="blue" stroke-width="2" fill="none" />
      <polyline points="5,16 12,23 19,16" stroke="blue" stroke-width="2" fill="none" />
    </svg>

    <!-- Lower - Hai mũi tên xuống màu xanh -->
    <svg v-else-if="priority === 'Lower'" width="20" height="20" viewBox="0 0 24 24">
      <polyline points="5,8 12,15 19,8" stroke="blue" stroke-width="2" fill="none" />
      <polyline points="5,14 12,21 19,14" stroke="blue" stroke-width="2" fill="none" />
    </svg>

    <!-- Low - Một mũi tên xuống màu xanh -->
    <svg v-else-if="priority === 'Low'" width="20" height="20" viewBox="0 0 24 24">
      <polyline points="5,13 12,20 19,13" stroke="blue" stroke-width="2" fill="none" />
    </svg>

    <!-- Minor - Hai dấu '=' màu vàng -->
    <svg v-else-if="priority === 'Minor'" width="20" height="20" viewBox="0 0 24 24">
      <line x1="5" y1="11" x2="19" y2="11" stroke="goldenrod" stroke-width="2" />
      <line x1="5" y1="15" x2="19" y2="15" stroke="goldenrod" stroke-width="2" />
    </svg>

    <!-- High - Một mũi tên lên màu đỏ (Lùi xuống 1px) -->
    <svg v-else-if="priority === 'High'" width="20" height="20" viewBox="0 0 24 24">
      <polyline points="5,18 12,11 19,18" stroke="red" stroke-width="2" fill="none" />
    </svg>

    <!-- Higher - Hai mũi tên lên màu đỏ (Lùi xuống 2px) -->
    <svg v-else-if="priority === 'Higher'" width="20" height="20" viewBox="0 0 24 24">
      <polyline points="5,18 12,11 19,18" stroke="red" stroke-width="2" fill="none" />
      <polyline points="5,12 12,5 19,12" stroke="red" stroke-width="2" fill="none" />
    </svg>

    <!-- Highest - Ba mũi tên lên màu đỏ (Lùi xuống 3px) -->
    <svg v-else-if="priority === 'Highest'" width="20" height="20" viewBox="0 0 24 24">
      <polyline points="5,19 12,12 19,19" stroke="red" stroke-width="2" fill="none" />
      <polyline points="5,13 12,6 19,13" stroke="red" stroke-width="2" fill="none" />
      <polyline points="5,7 12,1 19,7" stroke="red" stroke-width="2" fill="none" />
    </svg>

    <!-- Critical - Biểu tượng lá chắn đỏ -->
    <svg v-else-if="priority === 'Critical'" width="20" height="20" viewBox="0 0 24 24">
      <polygon points="5,6 19,6 19,19 12,23 5,19" fill="red" />
    </svg>

    <!-- Blocker - Hình tròn đỏ với dấu '-' -->
    <svg v-else-if="priority === 'Blocker'" width="20" height="20" viewBox="0 0 24 24">
      <circle cx="12" cy="13" r="8" fill="red" />
      <line x1="7" y1="13" x2="17" y2="13" stroke="white" stroke-width="2" />
    </svg>
  </div>
</template>

<script setup>
defineProps({
  priority: String,
});
</script>
