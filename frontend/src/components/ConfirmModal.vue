<template>
  <Transition name="fade">
    <div v-if="modal.isOpen" class="fixed inset-0 z-[200] flex items-center justify-center p-4 bg-dark/95 backdrop-blur-3xl">
      <div class="bg-gray-900 border border-white/10 p-10 rounded-none w-full max-w-md shadow-[0_0_100px_rgba(16,185,129,0.1)] relative">
        <div class="absolute top-0 left-0 w-2 h-full bg-red-500"></div>
        
        <h2 class="text-2xl font-black text-white uppercase tracking-tighter mb-4">{{ modal.title }}</h2>
        <p class="text-white/60 text-sm font-medium leading-relaxed mb-10 uppercase tracking-widest text-[11px]">{{ modal.message }}</p>
        
        <div class="flex justify-end gap-4">
          <button 
            @click="modal.close()" 
            class="px-8 py-3 bg-white/5 hover:bg-white/10 text-white text-[10px] font-black uppercase tracking-[0.2em] border border-white/10 transition-all"
          >
            Cancelar
          </button>
          <button 
            @click="handleConfirm" 
            class="px-8 py-3 bg-red-500 hover:bg-red-600 text-white text-[10px] font-black uppercase tracking-[0.2em] shadow-xl shadow-red-500/20 transition-all"
          >
            Confirmar Acción
          </button>
        </div>
      </div>
    </div>
  </Transition>
</template>

<script setup lang="ts">
import { useModalStore } from '../store/modal';

const modal = useModalStore();

const handleConfirm = () => {
  if (modal.onConfirm) {
    modal.onConfirm();
  }
  modal.close();
};
</script>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.3s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>
