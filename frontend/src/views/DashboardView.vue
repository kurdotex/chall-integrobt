<template>
  <div class="max-w-7xl mx-auto px-4 py-8">
    <header class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-10">
      <div>
        <h1 class="text-4xl font-black text-white tracking-tighter uppercase leading-none">Sucursales</h1>
        <p class="text-white/40 font-bold uppercase tracking-[0.2em] text-[10px] mt-2">Listado — {{ pagination.total }} Registros</p>
      </div>
      <div class="flex items-center gap-3">
        <button @click="showAddModal = true" class="px-6 py-3 bg-primary hover:bg-secondary rounded-none font-black transition-all shadow-xl shadow-primary/10 mr-2 uppercase tracking-widest text-[10px] text-dark">
          Nueva Unidad
        </button>
        <router-link :to="{ name: 'profile' }" title="Mi Perfil" class="p-3 bg-white/5 hover:bg-white/10 rounded-none transition-all border border-white/10">
          <User class="w-5 h-5 text-white/80" />
        </router-link>
        <button @click="handleLogout" title="Cerrar Sesión" class="p-3 bg-white/5 hover:bg-white/10 rounded-none transition-all border border-white/10">
          <LogOut class="w-5 h-5 text-white/80" />
        </button>
      </div>
    </header>

    <div class="relative mb-16 group">
      <Search class="absolute left-5 top-1/2 -translate-y-1/2 w-5 h-5 text-white/20 transition-colors group-focus-within:text-primary" />
      <input 
        v-model="branchStore.searchQuery" 
        type="text" 
        placeholder="FILTRAR POR NOMBRE, CIUDAD O PAÍS..." 
        class="w-full pl-14 pr-14 py-6 bg-white/5 border border-white/10 rounded-none text-white outline-none focus:ring-1 focus:ring-primary transition-all font-black tracking-[0.3em] text-xs uppercase" 
        @input="handleSearch"
      >
      <button 
        v-if="branchStore.searchQuery" 
        @click="clearSearch" 
        class="absolute right-5 top-1/2 -translate-y-1/2 p-2 hover:bg-white/10 text-white/40 hover:text-white transition-all rounded-none border border-transparent hover:border-white/10"
        title="Limpiar búsqueda"
      >
        <X class="w-4 h-4" />
      </button>
    </div>

    <div v-if="loading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
      <div v-for="i in 6" :key="i" class="h-64 bg-white/5 animate-pulse rounded-none border border-white/10"></div>
    </div>

    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
      <div v-for="branch in branches" :key="branch.id" class="group relative bg-white/5 p-10 rounded-none border border-white/10 hover:border-primary/40 transition-all hover:bg-white/[0.04] shadow-2xl flex flex-col justify-between overflow-hidden">
        <div class="absolute top-0 right-0 p-4 flex gap-3 z-10">
          <router-link :to="{ name: 'sucursal-weather', params: { id: branch.id } }" title="Monitor de Clima" class="p-2.5 bg-primary/20 hover:bg-primary text-dark rounded-none transition-colors border border-primary/20 shadow-lg">
            <Sun class="w-4 h-4" />
          </router-link>
          <button @click="editBranch(branch)" class="p-2.5 bg-white/5 hover:bg-white/10 rounded-none border border-white/10 transition-colors">
            <Edit2 class="w-4 h-4" />
          </button>
          <button @click="confirmDelete(branch.id)" class="p-2.5 bg-red-500/5 hover:bg-red-500/20 text-red-500 rounded-none border border-red-500/20 transition-colors">
            <Trash2 class="w-4 h-4" />
          </button>
        </div>
        <div class="mb-8">
          <div class="flex items-center gap-2 text-primary text-[10px] font-black uppercase tracking-[0.4em] mb-4">
            <MapPin class="w-4 h-4" />
            {{ branch.pais }}
          </div>
          <h2 class="text-3xl font-black text-white uppercase leading-none mb-2 tracking-tighter">{{ branch.nombre }}</h2>
          <p class="text-white/30 text-[10px] font-black uppercase tracking-[0.2em]">{{ branch.ciudad }}</p>
        </div>
        <div class="flex items-center justify-between pt-8 border-t border-white/5">
          <router-link :to="{ name: 'sucursal-detail', params: { id: branch.id } }" class="text-[10px] font-black text-primary hover:text-white transition-colors uppercase tracking-[0.2em]">ver empleados</router-link>
          <div class="flex items-center gap-2 opacity-20"><Cloud class="w-4 h-4" /><span class="text-xs font-bold uppercase tracking-widest">TELEMETRÍA</span></div>
        </div>
      </div>
    </div>

    <div v-if="!loading && pagination.last_page > 1" class="mt-20 flex items-center justify-center gap-4">
       <button @click="changePage(pagination.current_page - 1)" :disabled="pagination.current_page === 1" class="p-4 bg-white/5 border border-white/10 disabled:opacity-5 hover:bg-white/10 transition-all"><ChevronLeft class="w-6 h-6" /></button>
       <div class="px-10 py-4 bg-white/5 border border-white/10 text-[10px] font-black uppercase tracking-[0.4em]">{{ pagination.current_page }} / {{ pagination.last_page }}</div>
       <button @click="changePage(pagination.current_page + 1)" :disabled="pagination.current_page === pagination.last_page" class="p-4 bg-white/5 border border-white/10 disabled:opacity-5 hover:bg-white/10 transition-all"><ChevronRight class="w-6 h-6" /></button>
    </div>

    <div v-if="showAddModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-dark/95 backdrop-blur-2xl">
        <div class="bg-gray-900 border border-white/10 p-12 rounded-none w-full max-w-2xl shadow-2xl relative">
            <div class="absolute top-0 left-0 w-1.5 h-full bg-primary"></div>
            <h2 class="text-3xl font-black mb-12 text-white uppercase tracking-tighter">{{ form.id ? 'MODIFICAR' : 'REGISTRAR' }} UNIDAD</h2>
            <form @submit.prevent="saveBranch" class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <div class="md:col-span-2"><label class="text-[10px] uppercase tracking-[0.3em] font-black text-white/20 mb-3 block">Nombre</label><input v-model="form.nombre" type="text" required class="w-full bg-white/5 border border-white/10 rounded-none px-5 py-5 text-xl font-black uppercase outline-none focus:ring-1 focus:ring-primary"></div>
                <div><label class="text-[10px] uppercase tracking-[0.3em] font-black text-white/20 mb-3 block">Ciudad</label><input v-model="form.ciudad" @input="form.ciudad = form.ciudad.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '')" type="text" required class="w-full bg-white/5 border border-white/10 rounded-none px-5 py-5 text-lg font-bold uppercase outline-none focus:ring-1 focus:ring-primary"></div>
                <div><label class="text-[10px] uppercase tracking-[0.3em] font-black text-white/20 mb-3 block">País</label><input v-model="form.pais" @input="form.pais = form.pais.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '')" type="text" required class="w-full bg-white/5 border border-white/10 rounded-none px-5 py-5 text-lg font-bold uppercase outline-none focus:ring-1 focus:ring-primary"></div>
                <div><label class="text-[10px] uppercase tracking-[0.3em] font-black text-white/20 mb-3 block">Latitud</label><input v-model="form.latitud" type="number" step="any" required class="w-full bg-white/5 border border-white/10 rounded-none px-5 py-5 text-lg font-bold font-mono outline-none focus:ring-1 focus:ring-primary"></div>
                <div><label class="text-[10px] uppercase tracking-[0.3em] font-black text-white/20 mb-3 block">Longitud</label><input v-model="form.longitud" type="number" step="any" required class="w-full bg-white/5 border border-white/10 rounded-none px-5 py-5 text-lg font-bold font-mono outline-none focus:ring-1 focus:ring-primary"></div>
                <div class="md:col-span-2 flex justify-end gap-6 mt-12"><button type="button" @click="showAddModal = false; resetForm()" class="px-12 py-4 border border-white/10 text-[10px] font-black uppercase">CANCELAR</button><button type="submit" class="px-12 py-4 bg-primary font-black text-[10px] uppercase text-dark shadow-2xl shadow-primary/10">CONFIRMAR</button></div>
            </form>
        </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, reactive } from 'vue';
import type { Sucursal } from '../types';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../store/auth';
import { useToastStore } from '../store/toast';
import { useModalStore } from '../store/modal';
import { useBranchStore } from '../store/branch';
import api from '../api/axios';
import { LogOut, Search, MapPin, Edit2, Trash2, Cloud, Sun, User, ChevronLeft, ChevronRight, X } from 'lucide-vue-next';

const authStore = useAuthStore();
const toast = useToastStore();
const modal = useModalStore();
const branchStore = useBranchStore();
const router = useRouter();
const branches = ref<Sucursal[]>([]);
const loading = ref(true);
const showAddModal = ref(false);

const pagination = reactive({ current_page: branchStore.currentPage, last_page: 1, total: 0 });
const form = reactive({ id: null, nombre: '', ciudad: '', pais: '', latitud: -34.6037, longitud: -58.3816 });

const fetchBranches = async (page = branchStore.currentPage) => {
    loading.value = true;
    try {
        const response = await api.get('/sucursales', { params: { search: branchStore.searchQuery, page } });
        branches.value = response.data.data;
        Object.assign(pagination, response.data.meta);
        branchStore.setPage(pagination.current_page);
    } finally { loading.value = false; }
};

const clearSearch = () => { branchStore.setSearch(''); fetchBranches(1); };

let timer = null;
const handleSearch = () => { clearTimeout(timer); timer = setTimeout(() => { fetchBranches(1); }, 500); };
const changePage = (page) => { if (page < 1 || page > pagination.last_page) return; fetchBranches(page); };
const saveBranch = async () => { try { if (form.id) { await api.put('/sucursales/' + form.id, form); toast.add('ACTUALIZADA'); } else { await api.post('/sucursales', form); toast.add('REGISTRADA'); } showAddModal.value = false; resetForm(); fetchBranches(pagination.current_page); } catch (e) {} };

const confirmDelete = (id: number) => {
  modal.confirm(
    'Eliminar Sucursal',
    '¿Estás seguro que deseas eliminar está Sucursal? Esta acción no se puede deshacer.',
    () => deleteBranch(id)
  );
};

const deleteBranch = async (id: number) => { 
  try { 
    await api.delete('/sucursales/' + id); 
    toast.add('ELIMINADA'); 
    fetchBranches(pagination.current_page); 
  } catch (e) {} 
};

const editBranch = (branch: Sucursal) => { Object.assign(form, branch); showAddModal.value = true; };
const resetForm = () => { Object.assign(form, { id: null, nombre: '', ciudad: '', pais: '', latitud: -34.6037, longitud: -58.3816 }); };
const handleLogout = async () => { await authStore.logout(); router.push({ name: 'login' }); };

onMounted(() => fetchBranches(branchStore.currentPage));
</script>
