import { createRouter, createWebHistory } from 'vue-router';

// Impor halaman-halaman utama
import Home from '../views/Home.vue';
import Landing3D from '../views/Landing3D.vue';
import Login from '../views/Login.vue';
import Register from '../views/Register.vue';
import CampaignDetails from '../views/CampaignDetails.vue';
import About from '../views/About.vue';
import Help from '../views/Help.vue';
import Contact from '../views/Contact.vue';
import News from '../views/News.vue';
import Zakat from '../views/Zakat.vue';
import Volunteer from '../views/Volunteer.vue';
import ForgotPassword from '../views/ForgotPassword.vue';
import ResetPassword from '../views/ResetPassword.vue';

// Gunakan impor dinamis untuk dashboard agar lebih ringan
const Overview = () => import('../views/dashboard/Overview.vue');
const UserDashboard = () => import('../views/dashboard/User.vue');
const AdminDashboard = () => import('../views/dashboard/Admin.vue');
const FieldDashboard = () => import('../views/dashboard/Field.vue');
const ReportDistribution = () => import('../views/dashboard/ReportDistribution.vue');

const routes = [
  {
    path: '/',
    name: 'Home',
    component: Landing3D
  },
  {
    path: '/classic',
    name: 'ClassicHome',
    component: Home
  },
  {
    path: '/campaigns',
    name: 'Campaigns',
    component: Home
  },
  {
    path: '/campaigns/:id',
    name: 'CampaignDetails',
    component: CampaignDetails
  },
  {
    path: '/about',
    name: 'About',
    component: About
  },
  {
    path: '/help',
    name: 'Help',
    component: Help
  },
  {
    path: '/contact',
    name: 'Contact',
    component: Contact
  },

  {
    path: '/news',
    name: 'News',
    component: News
  },
  {
    path: '/zakat',
    name: 'Zakat',
    component: Zakat
  },
  {
    path: '/volunteer',
    name: 'Volunteer',
    component: Volunteer
  },
  {
    path: '/receipt/:id',
    name: 'Receipt',
    component: () => import('../views/Receipt.vue'),
    meta: { requiresAuth: true }
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
  {
    path: '/forgot-password',
    name: 'ForgotPassword',
    component: ForgotPassword,
    meta: { guestOnly: true }
  },
  {
    path: '/reset-password',
    name: 'ResetPassword',
    component: ResetPassword,
    meta: { guestOnly: true }
  },
  // LAYOUT DASHBOARD (Nested Routes)
  {
    path: '/dashboard',
    component: () => import('../layouts/DashboardLayout.vue'),
    meta: { requiresAuth: true },
    children: [
      {
        path: '',
        name: 'Dashboard',
        beforeEnter: (to, from, next) => {
          next('/dashboard/overview');
        }
      },
      {
        path: 'overview',
        name: 'Overview',
        component: Overview
      },
      {
        path: 'user',
        name: 'UserDashboard',
        component: UserDashboard,
        meta: { role: 'user' }
      },
      {
        path: 'field',
        name: 'FieldDashboard',
        component: FieldDashboard,
        meta: { role: 'field_worker' }
      },
      {
        path: 'report-distribution',
        name: 'ReportDistribution',
        component: ReportDistribution,
        meta: { role: 'field_worker' }
      },
      {
        path: 'admin',
        name: 'AdminDashboard',
        component: AdminDashboard,
        meta: { role: 'admin' }
      },
      {
        path: 'admin/worker/:id',
        name: 'WorkerDetail',
        component: () => import('../views/dashboard/WorkerDetail.vue'),
        meta: { role: 'admin' }
      },
      {
        path: 'profile',
        name: 'Profile',
        component: () => import('../views/Profile.vue')
      }
    ]
  },
  {
    path: '/profile',
    redirect: '/dashboard/profile'
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
  const userRole = localStorage.getItem('role')?.toLowerCase().trim();
  const isAuthenticated = !!token;

  console.log(`[Router] Navigating to: ${to.path}`, { 
    isAuthenticated, 
    userRole, 
    requiredRole: to.meta.role 
  });

  // 1. Cek jika rute butuh login tapi user belum login
  if (to.meta.requiresAuth && !isAuthenticated) {
    console.warn('[Router] Unauthenticated access to protected route. Redirecting to login.');
    return next('/login');
  }

  // 2. Cek jika rute khusus tamu (login/register) tapi user sudah login
  if (to.meta.guestOnly && isAuthenticated) {
    return next('/dashboard');
  }

  // 3. Cek Proteksi Role (Mencegah User masuk ke Admin)
  if (to.meta.role && to.meta.role.toLowerCase() !== userRole) {
    console.warn(`[Router] Role mismatch. Required: ${to.meta.role}, Actual: ${userRole}. Redirecting to dashboard.`);
    // Jika role tidak cocok, lempar ke dashboard masing-masing
    return next('/dashboard');
  }

  next();
});

export default router;