<template>
  <div ref="container" class="fixed inset-0 z-[-1] pointer-events-none"></div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
const container = ref(null);
onMounted(() => {
  // Clean white background only - no 3D animations
  if(container.value) container.value.style.background = '#ffffff';
});
</script>
