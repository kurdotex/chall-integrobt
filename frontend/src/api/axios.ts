import axios, { type InternalAxiosRequestConfig, type AxiosResponse } from 'axios';
import { useToastStore } from '../store/toast';
import { useAuthStore } from '../store/auth';

const instance = axios.create({
  baseURL: import.meta.env.VITE_API_URL || 'http://localhost:8080/api',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  }
});

instance.interceptors.request.use((config: InternalAxiosRequestConfig) => {
  const token = localStorage.getItem('token');
  if (token && config.headers) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

instance.interceptors.response.use(
  (response: AxiosResponse) => response,
  (error) => {
    const toast = useToastStore();
    const auth = useAuthStore();

    if (error.response) {
      const status = error.response.status;
      const apiMessage = error.response.data?.message || error.response.data?.error || 'Error desconocido';
      const displayMessage = `[${status}] ${apiMessage}`;

      switch (status) {
        case 401:
          toast.add(displayMessage, 'error');
          auth.logout();
          break;
        case 404:
          toast.add(displayMessage, 'warning');
          break;
        case 422:
          toast.add(displayMessage, 'warning');
          break;
        default:
          toast.add(displayMessage, 'error');
      }
    } else {
      toast.add('ERR_CONNECTION: Sin acceso al Satélite', 'error');
    }
    
    return Promise.reject(error);
  }
);

export default instance;
