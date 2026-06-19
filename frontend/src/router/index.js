import { createRouter, createWebHistory } from 'vue-router'

import { useAuthStore } from '@/stores/auth'

import DashboardLayout from '../layouts/DashboardLayout.vue'

import LoginPage from '../pages/auth/LoginPage.vue'
import DashboardPage from '../pages/dashboard/DashboardPage.vue'

const routes = [
  /*
  |--------------------------------------------------------------------------
  | Public Routes
  |--------------------------------------------------------------------------
  */

  {
    path: '/login',
    name: 'login',
    component: LoginPage,
    meta: {
      guestOnly: true,
    },
  },

  {
  path: '/register-admin',
  name: 'register-admin',
  component: () =>
    import(
      '@/pages/auth/RegisterAdminPage.vue'
    ),
  },

  /*
  |--------------------------------------------------------------------------
  | Protected Routes
  |--------------------------------------------------------------------------
  */

  {
    path: '/',
    component: DashboardLayout,
    meta: {
      requiresAuth: true,
    },

    children: [
      {
        path: '',
        redirect: '/dashboard',
      },

      {
        path: 'dashboard',
        name: 'dashboard',
        component: DashboardPage,
      },

      {
        path: 'siswa',
        name: 'siswa',
        component: () => import('../pages/siswa/SiswaPage.vue'),
      },

      {
        path: 'guru',
        name: 'guru',
        component: () => import('../pages/guru/GuruPage.vue'),
          meta: {
          requiresAuth: true,
          roles: ['admin']
          }
      },

      {
        path: 'kelas',
        name: 'kelas',
        component: () => import('../pages/kelas/KelasPage.vue'),
      },

      {
        path: 'mapel',
        name: 'mapel',
        component: () => import('../pages/mapel/MapelPage.vue'),
      },

      {
        path: 'pengampu',
        name: 'pengampu',
        component: () => import('../pages/pengampu/PengampuPage.vue'),
      },

      {
        path: 'jadwal',
        name: 'jadwal',
        component: () => import('../pages/jadwal/JadwalPage.vue'),
      },

      {
        path: 'absensi',
        name: 'absensi',
        component: () => import('../pages/absensi/AbsensiPage.vue'),
      },

      {
        path: 'nilai',
        name: 'nilai',
        component: () => import('../pages/nilai/NilaiPage.vue'),
      },
    ],
  },

  /*
  |--------------------------------------------------------------------------
  | Fallback Route
  |--------------------------------------------------------------------------
  */

  {
    path: '/:pathMatch(.*)*',
    redirect: '/dashboard',
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

/*
|--------------------------------------------------------------------------
| Router Guard
|--------------------------------------------------------------------------
*/

router.beforeEach((to, from, next) => {
  const auth = useAuthStore()

  /*
  |--------------------------------------------------------------------------
  | Belum login tapi akses protected route
  |--------------------------------------------------------------------------
  */

  if (to.meta.requiresAuth && !auth.isAuthenticated) {
    return next('/login')
  }

  /*
  |--------------------------------------------------------------------------
  | Sudah login tapi buka login page
  |--------------------------------------------------------------------------
  */

  if (to.meta.guestOnly && auth.isAuthenticated) {
    return next('/dashboard')
  }

  next()
})

export default router