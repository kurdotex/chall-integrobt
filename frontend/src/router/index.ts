import { createRouter, createWebHistory, type RouteRecordRaw } from 'vue-router';
import { useAuthStore } from '../store/auth';

const routes: RouteRecordRaw[] = [
    { path: '/profile', name: 'profile', component: () => import('../views/ProfileView.vue'), meta: { auth: true } },
    {
        path: '/login',
        name: 'login',
        component: () => import('../views/LoginView.vue'),
        meta: { guest: true }
    },
    {
        path: '/',
        name: 'dashboard',
        component: () => import('../views/DashboardView.vue'),
        meta: { auth: true }
    },
    {
        path: '/sucursales/:id',
        name: 'sucursal-detail',
        component: () => import('../views/SucursalDetailView.vue'),
        meta: { auth: true }
    },
    {
        path: '/sucursales/:id/clima',
        name: 'sucursal-weather',
        component: () => import('../views/SucursalWeatherView.vue'),
        meta: { auth: true }
    },
    
    {
        path: '/:pathMatch(.*)*',
        redirect: { name: 'dashboard' }
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

router.beforeEach(async (to) => {
    const authStore = useAuthStore();
    
    if (to.meta.auth && !authStore.isAuthenticated) {
        return { name: 'login' };
    }
    
    if (to.meta.guest && authStore.isAuthenticated) {
        return { name: 'dashboard' };
    }

    if (authStore.isAuthenticated && !authStore.user) {
        try {
            await authStore.fetchUser();
        } catch (e) {
            await authStore.logout();
            return { name: 'login' };
        }
    }
    
    return true;
});

export default router;
