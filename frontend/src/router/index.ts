import { createRouter, createWebHistory } from 'vue-router'
import type { RouteRecordRaw } from 'vue-router';
import MainLayout from '@/layouts/MainLayout.vue';
import AuthLayout from '@/layouts/AuthLayout.vue';

const routes: Array<RouteRecordRaw> = [
 {
  path: '/',
  redirect: '/login'
 },
  {
    path: '/login',
    component: AuthLayout,
    children: [
      {
        path: '',
        name: 'login',
        component: () => import('@/pages/LoginPage.vue'),
        meta: { title: 'Login' }
       }
    ]
  },
  {
    path: '/',
    component: MainLayout,
    children: [
      {
        path: 'clientes',
        name: 'clientes',
        component: () => import('@/pages/ClientsPage.vue'),
        meta: { title: 'Lista de clientes',  requiresAuth: true },
      },
      {
        path: 'cliente/:id',
        name: 'cliente',
        component: () => import('@/pages/ClientHistorico.vue'),
        meta: { title: 'Histórico do cliente',  requiresAuth: true },
      }
    ],
  },

];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
});

router.isReady().then(() => {
  localStorage.removeItem('vuetify:dynamic-reload')
});

router.beforeEach((to, _from, next) => {
  const isAuthenticated = localStorage.getItem('token');
  if (to.meta.requiresAuth && !isAuthenticated) {
    next({ name: 'login' });
  } else {
    next();
  }
});

export default router;
