<template>
  <div class="min-h-screen bg-dark text-white w-full selection:bg-primary/30 selection:text-primary">
    <router-view v-slot="{ Component }">
      <transition name="page" mode="out-in">
        <component :is="Component" />
      </transition>
    </router-view>
    <ToastContainer />
    <ConfirmModal />
  </div>
</template>

<script setup lang="ts">
import ToastContainer from './components/ToastContainer.vue';
import ConfirmModal from './components/ConfirmModal.vue';
</script>

<style>
.page-enter-active,
.page-leave-active {
  transition: opacity 0.2s ease;
}
.page-enter-from,
.page-leave-to {
  opacity: 0;
}
</style>
