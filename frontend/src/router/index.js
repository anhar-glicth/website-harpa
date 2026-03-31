import { createRouter, createWebHistory } from 'vue-router';

// Impor halaman-halaman utama
import Home from '../views/Home.vue';
import Login from '../views/Login.vue';
import Register from '../views/Register.vue';
import CampaignDetails from '../views/CampaignDetails.vue';

// Gunakan impor dinamis untuk dashboard agar lebih ringan
const UserDashboard = () => import('../views/dashboard/User.vue');
const AdminDashboard = () => import('../views/dashboard/Admin.vue');
const FieldDashboard = () => import('../views/dashboard/Field.vue');

const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home
  },
  {
    path: '/campaigns/:id',
    name: 'CampaignDetails',
    component: CampaignDetails
  },
  {
    path: '/login',
    name: 'Login',
    component: Login,
    meta: { guestOnly: true }
  },
  {
    path: '/register',
    name: 'Register',
    component: Register,
    meta: { guestOnly: true }
  },
  // RUTE DASHBOARD UMUM (Bisa digunakan untuk pengalihan)
  {
    path: '/dashboard',
    name: 'Dashboard',
    meta: { requiresAuth: true },
    beforeEnter: (to, from, next) => {
      const role = localStorage.getItem('role');
      if (role === 'admin') next('/dashboard/admin');
      else if (role === 'field_worker') next('/dashboard/field');
      else next('/dashboard/user');
    }
  },
  // RUTE SPESIFIK BERDASARKAN ROLE
  {
    path: '/dashboard/user',
    name: 'UserDashboard',
    component: UserDashboard,
    meta: { requiresAuth: true, role: 'user' }
  },
  {
    path: '/dashboard/field',
    name: 'FieldDashboard',
    component: FieldDashboard,
    meta: { requiresAuth: true, role: 'field_worker' }
  },
  {
    path: '/dashboard/admin',
    name: 'AdminDashboard',
    component: AdminDashboard,
    meta: { requiresAuth: true, role: 'admin' }
  }
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes
});

/**
 * Penjaga Navigasi (Navigation Guard) Terintegrasi
 * Mengecek Login dan Hak Akses (Role)
 */
router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('token');
  const userRole = localStorage.getItem('role');
  const isAuthenticated = !!token;

  // 1. Cek jika rute butuh login tapi user belum login
  if (to.meta.requiresAuth && !isAuthenticated) {
    return next('/login');
  }

  // 2. Cek jika rute khusus tamu (login/register) tapi user sudah login
  if (to.meta.guestOnly && isAuthenticated) {
    return next('/dashboard');
  }

  // 3. Cek Proteksi Role (Mencegah User masuk ke Admin)
  if (to.meta.role && to.meta.role !== userRole) {
    // Jika role tidak cocok, lempar ke dashboard masing-masing
    return next('/dashboard');
  }

  next();
});

export default router;