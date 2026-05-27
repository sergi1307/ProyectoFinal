<script setup>
defineProps({
  text: {
    type: String,
    default: ''
  }
});
</script>

<template>
  <div class="spinner-container">
    <div class="loading-spinner"></div>
    <p v-if="text" class="loading-text">{{ text }}</p>
  </div>
</template>

<style lang="scss" scoped>
@import "@/assets/scss/loadingSpinner"; 
</style>