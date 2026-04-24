<template>
  <div class='max-w-4xl mx-auto px-4 py-8'>
    <div class='mb-8 flex items-center gap-4'>
      <router-link :to="{ name: 'dashboard' }" class='p-2 bg-white/5 hover:bg-white/10 rounded-none transition-all'>
        <ChevronLeft class='w-6 h-6' />
      </router-link>
      <h1 class='text-3xl font-bold'>Mi Perfil</h1>
    </div>

    <div v-if='authStore.user' class='bg-white/5 border border-white/10 p-8 rounded-none shadow-2xl relative overflow-hidden'>
      <div class='absolute top-0 left-0 w-2 h-full bg-primary'></div>
      
      <div class='flex flex-col md:flex-row items-center gap-8'>
        <div class='w-24 h-24 bg-primary flex items-center justify-center text-4xl font-bold text-white'>
          {{ authStore.user.name.charAt(0) }}
        </div>
        
        <div class='space-y-4 flex-1'>
          <div>
            <label class='text-xs uppercase tracking-widest text-white/40 font-bold'>Nombre Completo</label>
            <p class='text-2xl font-semibold'>{{ authStore.user.name }}</p>
          </div>
          <div>
            <label class='text-xs uppercase tracking-widest text-white/40 font-bold'>Correo Electrónico</label>
            <p class='text-xl text-white/80'>{{ authStore.user.email }}</p>
          </div>
          <div class='pt-4 border-t border-white/10 flex items-center gap-2 text-white/40 text-sm'>
            <Calendar class='w-4 h-4' />
            Miembro desde: {{ new Date(authStore.user.created_at).toLocaleDateString() }}
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useAuthStore } from '../store/auth';
import { ChevronLeft, Calendar } from 'lucide-vue-next';
const authStore = useAuthStore();
</script>
