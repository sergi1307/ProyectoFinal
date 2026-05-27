<template>
  <div class="toast-container">
    <TransitionGroup name="toast-anim">
      <div 
        v-for="toast in toasts" 
        :key="toast.id" 
        :class="['toast-item', `toast-${toast.type}`]"
        @click="removeToast(toast.id)"
      >
        {{ toast.message }}
      </div>
    </TransitionGroup>
  </div>
</template>

<script setup>
import { useToast } from '@/composables/useToast';

const { toasts, removeToast } = useToast();
</script>