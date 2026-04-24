<template>
  <div class="flex items-center justify-center min-h-screen bg-gradient-to-br from-[#04100b] via-[#064e3b] to-[#04100b] px-4">
    <div class="w-full max-w-md p-8 space-y-6 bg-white/5 backdrop-blur-xl border border-white/10 rounded-none shadow-2xl">
      <div class="text-center">
        <h1 class="text-3xl font-bold tracking-tight text-white">Bienvenido</h1>
        <p class="mt-2 text-white/60">Gestiona tus sucursales y empleados</p>
      </div>
      
      <form class="space-y-4" @submit.prevent="handleLogin">
        <div>
          <label class="block text-sm font-medium text-white/80">Email</label>
          <input 
            v-model="form.email" 
            type="email" 
            required 
            class="w-full px-4 py-3 mt-1 bg-white/5 border border-white/10 rounded-none text-white focus:ring-2 focus:ring-primary focus:border-transparent transition-all outline-none"
            placeholder="jose@example.com"
          >
        </div>
        
        <div>
          <label class="block text-sm font-medium text-white/80">Contraseña</label>
          <input 
            v-model="form.password" 
            type="password" 
            required 
            class="w-full px-4 py-3 mt-1 bg-white/5 border border-white/10 rounded-none text-white focus:ring-2 focus:ring-primary focus:border-transparent transition-all outline-none"
            placeholder="••••••••"
          >
        </div>

        <div v-if="authStore.error" class="p-3 text-sm text-red-400 bg-red-400/10 border border-red-400/20 rounded-none">
          {{ authStore.error }}
        </div>

        <button 
          type="submit" 
          :disabled="authStore.loading"
          class="w-full py-3 font-semibold text-white bg-primary hover:bg-secondary rounded-none transition-all shadow-lg shadow-primary/20 disabled:opacity-50"
        >
          <span v-if="authStore.loading">Cargando...</span>
          <span v-else>Iniciar Sesión</span>
        </button>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
import { reactive } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../store/auth';

const authStore = useAuthStore();
const router = useRouter();

const form = reactive({
  email: '',
  password: ''
});

const handleLogin = async () => {
  const success = await authStore.login(form);
  if (success) {
    router.push({ name: 'dashboard' });
  }
};
</script>
