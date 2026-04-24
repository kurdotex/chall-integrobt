<template>
  <div class="max-w-6xl mx-auto px-4 py-8">
    <div class="mb-10 flex items-center gap-6">
      <router-link :to="{ name: 'dashboard' }" class="p-3 bg-white/5 hover:bg-white/10 rounded-none transition-all border border-white/10">
        <ChevronLeft class="w-6 h-6" />
      </router-link>
      <h1 class="text-4xl font-black tracking-tighter uppercase">Monitor de Unidad</h1>
    </div>

    <div v-if="loading" class="animate-pulse space-y-8">
        <div class="h-64 bg-white/5 rounded-none border border-white/5"></div>
        <div class="h-96 bg-white/5 rounded-none border border-white/5"></div>
    </div>

    <div v-else-if="branch" class="space-y-12">
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 bg-gradient-to-br from-primary/10 to-transparent p-12 rounded-none border border-white/10 shadow-2xl relative overflow-hidden">
          <div class="absolute top-0 left-0 w-2 h-full bg-primary"></div>
          <div class="flex items-center gap-2 text-primary font-black uppercase tracking-[0.2em] text-xs mb-6">
            <MapPin class="w-5 h-5" />
            {{ branch.pais }} / {{ branch.ciudad }}
          </div>
          <h2 class="text-5xl font-black mb-8 uppercase leading-none tracking-tighter">{{ branch.nombre }}</h2>
          <div class="flex gap-12 mt-12">
            <div>
                <span class="block text-white/20 text-[10px] uppercase tracking-[0.4em] font-black mb-3">Coordenada LAT</span>
                <span class="text-2xl font-bold font-mono text-white/90">{{ branch.latitud }}</span>
            </div>
            <div>
                <span class="block text-white/20 text-[10px] uppercase tracking-[0.4em] font-black mb-3">Coordenada LON</span>
                <span class="text-2xl font-bold font-mono text-white/90">{{ branch.longitud }}</span>
            </div>
          </div>
        </div>

        <div v-if="branch.clima" class="bg-white/5 rounded-none border border-white/10 shadow-xl overflow-hidden">
           <div class="bg-white/[0.03] p-4 border-b border-white/10 text-center text-[10px] font-black uppercase tracking-[0.3em] text-white/40">Estado del clima</div>
           <WeatherInfo :weather="branch.clima" />
        </div>
      </div>

      <div class="bg-white/5 rounded-none border border-white/10 overflow-hidden shadow-2xl">
        <div class="p-10 border-b border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-6 bg-white/[0.02]">
          <h3 class="text-2xl font-black flex items-center gap-4 uppercase tracking-tighter">
            <Users class="w-8 h-8 text-primary" />
            Listado de empleados
          </h3>
          <button @click="showEmpModal = true" class="px-8 py-3 bg-primary hover:bg-secondary rounded-none text-xs font-black uppercase tracking-widest transition-all text-dark shadow-xl shadow-primary/10">
            AGREGAR EMPLEADO
          </button>
        </div>

        <div v-if="loadingEmployees" class="p-32 text-center text-white/10 uppercase tracking-[0.5em] font-black animate-pulse">Sincronizando...</div>

        <div v-else class="min-h-[500px]">
          <table class="w-full text-left">
            <thead>
              <tr class="bg-white/5 text-white/20 text-[9px] uppercase tracking-[0.3em] font-black">
                <th class="px-10 py-6">Identificación Personal</th>
                <th class="px-10 py-6">Email Corporativo</th>
                <th class="px-10 py-6 text-right">Operativa</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
              <tr v-for="emp in employees" :key="emp.id" class="hover:bg-white/[0.02] transition-colors group">
                <td class="px-10 py-8 font-black text-lg text-white uppercase tracking-tight">{{ emp.nombre }}</td>
                <td class="px-10 py-8 text-white/30 font-mono text-sm">{{ emp.email }}</td>
                <td class="px-10 py-8 text-right">
                  <div class="flex justify-end gap-4 transition-all">
                    <button @click="editEmployee(emp)" class="p-3 bg-white/5 hover:bg-white/10 rounded-none border border-white/10"><Edit2 class="w-4 h-4" /></button>
                    <button @click="confirmDeleteEmployee(emp.id)" class="p-3 bg-red-500/5 hover:bg-red-500/20 text-red-500 rounded-none border border-red-500/20"><Trash2 class="w-4 h-4" /></button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div v-if="!loadingEmployees && empPagination.last_page > 1" class="p-8 border-t border-white/10 bg-white/[0.01] flex items-center justify-between">
           <p class="text-[10px] text-white/20 font-black uppercase tracking-[0.2em]">REGISTROS: {{ empPagination.total }} ACTIVOS</p>
           <div class="flex gap-3">
              <button @click="changeEmpPage(empPagination.current_page - 1)" :disabled="empPagination.current_page === 1" class="p-3 bg-white/5 border border-white/10"><ChevronLeft class="w-5 h-5" /></button>
              <div class="px-6 py-3 bg-white/10 border border-white/10 text-[10px] font-black uppercase">{{ empPagination.current_page }} / {{ empPagination.last_page }}</div>
              <button @click="changeEmpPage(empPagination.current_page + 1)" :disabled="empPagination.current_page === empPagination.last_page" class="p-3 bg-white/5 border border-white/10"><ChevronRight class="w-5 h-5" /></button>
           </div>
        </div>
      </div>
    </div>

    <div v-if="showEmpModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-dark/95 backdrop-blur-xl">
        <div class="bg-gray-900 border border-white/10 p-12 rounded-none w-full max-w-md shadow-2xl relative">
            <div class="absolute top-0 left-0 w-1.5 h-full bg-primary"></div>
            <h2 class="text-3xl font-black mb-10 text-white uppercase tracking-tighter decoration-primary decoration-4 underline-offset-8 underline">{{ empForm.id ? 'EDITAR' : 'NUEVO' }} RRHH</h2>
            <form @submit.prevent="saveEmployee" class="space-y-8">
                <div><label class="text-[10px] font-black uppercase tracking-[0.3em] text-white/30">Nombre Completo</label><input v-model="empForm.nombre" @input="empForm.nombre = empForm.nombre.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '')" type="text" required class="w-full mt-2 bg-white/5 border border-white/10 rounded-none px-4 py-5 outline-none focus:ring-2 focus:ring-primary text-xl font-bold"></div>
                <div><label class="text-[10px] font-black uppercase tracking-[0.3em] text-white/30">Email Corporativo</label><input v-model="empForm.email" type="email" required class="w-full mt-2 bg-white/5 border border-white/10 rounded-none px-4 py-5 outline-none focus:ring-2 focus:ring-primary text-xl font-bold font-mono"></div>
                <div class="flex justify-end gap-6 mt-12">
                    <button type="button" @click="showEmpModal = false; resetEmpForm()" class="px-10 py-3 rounded-none border border-white/10 hover:bg-white/5 text-xs font-black uppercase">CANCELAR</button>
                    <button type="submit" class="px-10 py-3 rounded-none bg-primary hover:bg-secondary font-black text-xs uppercase text-dark">CONFIRMAR</button>
                </div>
            </form>
        </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, reactive } from 'vue';
import type { Sucursal, Empleado } from '../types';
import { useRoute, useRouter } from 'vue-router';
import { useToastStore } from '../store/toast';
import { useModalStore } from '../store/modal';
import WeatherInfo from '../components/WeatherInfo.vue';
import api from '../api/axios';
import { ChevronLeft, ChevronRight, MapPin, Users, Edit2, Trash2 } from 'lucide-vue-next';

const route = useRoute();
const router = useRouter();
const toast = useToastStore();
const modal = useModalStore();

const branch = ref<Sucursal | null>(null);
const employees = ref<Empleado[]>([]);
const loading = ref(true);
const loadingEmployees = ref(true);
const showEmpModal = ref(false);
const empForm = reactive({ id: null, nombre: '', email: '' });
const empPagination = reactive({ current_page: 1, last_page: 1, total: 0 });

const fetchBranchDetail = async () => {
    loading.value = true;
    try {
        const response = await api.get('/sucursales/' + route.params.id);
        branch.value = response.data.data;
        fetchEmployees();
    } catch (error) { router.push({ name: 'dashboard' }); }
    finally { loading.value = false; }
};

const fetchEmployees = async (page = 1) => {
    loadingEmployees.value = true;
    try {
        const response = await api.get('/sucursales/' + route.params.id + '/empleados', { params: { page } });
        employees.value = response.data.data;
        Object.assign(empPagination, response.data.meta);
    } finally { loadingEmployees.value = false; window.scrollTo({ top: 0, behavior: 'smooth' }); }
};

const changeEmpPage = (page: number) => { if (page < 1 || page > empPagination.last_page) return; fetchEmployees(page); };
const saveEmployee = async () => { 
    try { 
        if (!branch.value) return;
        const data = { ...empForm, sucursal_id: branch.value.id }; 
        if (empForm.id) { await api.put('/empleados/' + empForm.id, data); toast.add('ACTUALIZADO'); } 
        else { await api.post('/empleados', data); toast.add('REGISTRADO'); } 
        showEmpModal.value = false; resetEmpForm(); fetchEmployees(empPagination.current_page); 
    } catch (e) {} 
};

const confirmDeleteEmployee = (id: number) => {
  modal.confirm(
    'Eliminar Empleado',
    '¿Estás seguro de que deseas eliminar este empleado?.',
    () => deleteEmployee(id)
  );
};

const deleteEmployee = async (id: number) => { 
  try { 
    await api.delete('/empleados/' + id); 
    toast.add('ELIMINADO'); 
    fetchEmployees(empPagination.current_page); 
  } catch (e) {} 
};

const editEmployee = (emp: Empleado) => { Object.assign(empForm, emp); showEmpModal.value = true; };
const resetEmpForm = () => { Object.assign(empForm, { id: null, nombre: '', email: '' }); };
onMounted(fetchBranchDetail);
</script>
