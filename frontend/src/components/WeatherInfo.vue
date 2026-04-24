<template>
  <div class='flex flex-col items-center justify-center text-center p-6 bg-white/5 border border-white/10 shadow-inner max-w-sm mx-auto'>
    <div class='relative mb-8'>
      <CloudRain v-if='isRainy' class='w-24 h-24 text-primary animate-bounce' />
      <Cloud v-else-if='isCloudy' class='w-24 h-24 text-white/60' />
      <Sun v-else class='w-24 h-24 text-yellow-400 animate-pulse' />
    </div>
    
    <div class='text-7xl font-black mb-2 tracking-tighter'>{{ weather.temperature }}{{ weather.unit }}</div>
    <p class='text-primary font-black tracking-[0.3em] uppercase text-[10px] mb-8'>{{ weatherDescription }}</p>
    
    <div class='grid grid-cols-2 gap-8 w-full border-t border-white/10 pt-8'>
      <div class='text-left'>
        <span class='block text-[10px] uppercase font-bold text-white/20 tracking-widest mb-1'>Viento</span>
        <span class='text-xl font-bold'>{{ weather.windspeed }} <small class='text-xs opacity-50 font-normal'>km/h</small></span>
      </div>
      <div class='text-left'>
        <span class='block text-[10px] uppercase font-bold text-white/20 tracking-widest mb-1'>Humedad</span>
        <span class='text-xl font-bold uppercase text-white/60 text-xs'>Sincronizada</span>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import type { Weather } from '../types';
import { Cloud, Sun, CloudRain } from 'lucide-vue-next';

const props = defineProps<{ weather: Weather }>();

const isRainy = computed(() => props.weather.weathercode >= 51);
const isCloudy = computed(() => props.weather.weathercode >= 45 && props.weather.weathercode <= 48 || (props.weather.weathercode >= 1 && props.weather.weathercode <= 3));

const weatherDescription = computed(() => {
    const code = props.weather.weathercode;
    if (code === 0) return 'Cielo Despejado';
    if (code >= 1 && code <= 3) return 'Parcialmente Nublado';
    if (code >= 45 && code <= 48) return 'Neblina Densas';
    if (code >= 51 && code <= 67) return 'Precipitaciones';
    if (code >= 71 && code <= 77) return 'Nevadas';
    if (code >= 80 && code <= 82) return 'Lluvia Intensa';
    if (code >= 95) return 'Tormenta Eléctrica';
    return 'Condiciones Variables';
});
</script>
