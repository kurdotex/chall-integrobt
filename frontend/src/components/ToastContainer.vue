<template>
  <div class='fixed bottom-5 right-5 z-[100] flex flex-col gap-3'>
    <TransitionGroup name='toast'>
      <div 
        v-for='toast in toastStore.toasts' 
        :key='toast.id'
        class='px-6 py-4 bg-gray-900 border-l-4 shadow-2xl flex items-center justify-between min-w-[300px]'
        :class='{
          "border-primary": toast.type === "success",
          "border-red-500": toast.type === "error",
          "border-yellow-500": toast.type === "warning"
        }'
      >
        <span class='text-white font-medium'>{{ toast.message }}</span>
        <button @click='toastStore.remove(toast.id)' class='ml-4 text-white/40 hover:text-white'>&times;</button>
      </div>
    </TransitionGroup>
  </div>
</template>

<script setup lang="ts">
import { useToastStore } from '../store/toast';
const toastStore = useToastStore();
</script>

<style scoped>
.toast-enter-active, .toast-leave-active { transition: all 0.3s ease; }
.toast-enter-from { opacity: 0; transform: translateX(30px); }
.toast-leave-to { opacity: 0; transform: translateX(30px); }
</style>
