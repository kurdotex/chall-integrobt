<template>
  <div class="max-w-4xl mx-auto px-4 py-8">
    <div class="mb-10 flex items-center gap-6">
      <router-link :to="{ name: 'dashboard' }" class="p-3 bg-white/5 hover:bg-white/10 rounded-none transition-all border border-white/10">
        <ChevronLeft class="w-6 h-6" />
      </router-link>
      <h1 class="text-4xl font-black tracking-tighter uppercase">Monitor Climático</h1>
    </div>

    <div v-if="loading" class="animate-pulse space-y-8">
        <div class="h-64 bg-white/5 rounded-none border border-white/5"></div>
    </div>

    <div v-else-if="branch" class="space-y-8">
      <div class="bg-gradient-to-br from-primary/20 to-transparent p-12 rounded-none border border-white/10 relative overflow-hidden shadow-2xl">
        <div class="absolute top-0 left-0 w-2 h-full bg-primary"></div>
        <div class="flex items-center gap-2 text-primary font-black uppercase tracking-[0.2em] text-xs mb-4">
          <MapPin class="w-5 h-5" />
          {{ branch.pais }} / {{ branch.ciudad }}
        </div>
        <h2 class="text-5xl font-black mb-0 uppercase leading-none tracking-tighter">{{ branch.nombre }}</h2>
      </div>

      <div class="bg-gray-900 border border-white/10 shadow-2xl overflow-hidden p-10">
         <div class="text-center mb-8">
            <p class="text-white/20 text-[10px] uppercase tracking-[0.5em] font-black mb-2">Telemetría en Tiempo Real</p>
            <div class="h-px bg-white/5 w-24 mx-auto"></div>
         </div>
         <WeatherInfo v-if="branch.clima" :weather="branch.clima" />
         <div v-else class="text-center py-20 p-10 bg-white/5 border border-dashed border-white/10 text-white/20 uppercase font-black">
            Error de conexión con satélite
         </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import type { Sucursal } from '../types';
import { useRoute, useRouter } from 'vue-router';
import { useToastStore } from '../store/toast';
import WeatherInfo from '../components/WeatherInfo.vue';
import api from '../api/axios';
import { ChevronLeft, MapPin } from 'lucide-vue-next';

const route = useRoute();
const router = useRouter();
const toast = useToastStore();
const branch = ref<Sucursal | null>(null);
const loading = ref(true);

const fetchBranchWeather = async () => {
    loading.value = true;
    try {
        const response = await api.get('/sucursales/' + route.params.id);
        branch.value = response.data.data;
    } catch (error) {
        router.push({ name: 'dashboard' });
    } finally {
        loading.value = false;
    }
};

onMounted(fetchBranchWeather);
</script>
