import { defineStore } from 'pinia';
import api from '../api/axios';
import type { AuthState } from '../types';

export const useAuthStore = defineStore('auth', {
  state: () => {
    const storedUser = localStorage.getItem('user');
    let user = null;
    
    try {
      if (storedUser && storedUser !== 'undefined') {
        user = JSON.parse(storedUser);
      }
    } catch (e) {
      user = null;
      localStorage.removeItem('user');
    }

    return {
      user,
      token: localStorage.getItem('token'),
      loading: false,
      error: null as string | null
    };
  },
  getters: {
    isAuthenticated: (state): boolean => !!state.token
  },
  actions: {
    async login(credentials: any): Promise<boolean> {
      this.loading = true;
      this.error = null;
      try {
        const response = await api.post('/login', credentials);
        this.token = response.data.data.access_token;
        localStorage.setItem('token', this.token || '');
        
        await this.fetchUser();
        
        return true;
      } catch (err: any) {
        this.error = 'Credenciales inválidas o error de conexión';
        return false;
      } finally {
        this.loading = false;
      }
    },
    async logout() {
      try {
        await api.post('/logout');
      } catch (e) {
      } finally {
        this.token = null;
        this.user = null;
        localStorage.removeItem('token');
        localStorage.removeItem('user');
      }
    },
    async fetchUser() {
      try {
        const response = await api.get('/me');
        this.user = response.data.data;
        localStorage.setItem('user', JSON.stringify(this.user));
      } catch (e) {
        this.token = null;
        this.user = null;
        localStorage.removeItem('token');
        localStorage.removeItem('user');
      }
    }
  }
});
