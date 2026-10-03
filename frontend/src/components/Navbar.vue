<template>
  <nav class="bg-white sticky top-0 z-[100] border-b border-gray-100 shadow-sm">
    <div class="max-w-7xl mx-auto px-6">
      <div class="flex justify-between h-16 items-center">
        <!-- Logo -->
        <router-link to="/" class="flex items-center gap-2">
          <img v-if="siteSettings.inst_logo" :src="siteSettings.inst_logo" class="h-8 w-auto object-contain" />
          <img v-else src="/logo-icon.png" class="h-8 w-auto object-contain" alt="Amal Abadi" />
          <span class="text-lg font-bold text-slate-900">{{ siteSettings.inst_name || 'Amal Abadi' }}</span>
        </router-link>

        <!-- Desktop Menu -->
        <div class="hidden md:flex items-center gap-8">
          <router-link v-for="link in menu" :key="link.to" :to="link.to"
            class="text-sm text-slate-500 hover:text-slate-900 transition-colors font-medium">
            {{ link.label }}
          </router-link>
        </div>

        <!-- Actions & Mobile Toggle -->
        <div class="flex items-center gap-2 sm:gap-3">
          <template v-if="!isAuthenticated">
            <button @click="openLogin" class="text-xs sm:text-sm font-semibold text-green-600 hover:text-green-700 transition-colors px-2 py-1.5">Masuk</button>
            <button @click="openRegister" class="bg-green-600 text-white px-3 sm:px-5 py-2 rounded-lg text-xs sm:text-sm font-semibold hover:bg-green-700 transition-all active:scale-95 shadow-sm">Daftar</button>
          </template>
          <template v-else>
            <router-link to="/dashboard" class="w-8 h-8 rounded-full bg-green-100 border-2 border-green-200 flex items-center justify-center text-green-600 font-bold text-xs overflow-hidden hover:border-green-500 transition-all">
              <img v-if="getUserPhoto" :src="getUserPhoto" class="w-full h-full object-cover" />
              <span v-else>{{ getUserName?.charAt(0).toUpperCase() }}</span>
            </router-link>
            <button @click="handleLogout" class="text-gray-400 hover:text-red-600 transition-colors p-1.5">
              <i class="fas fa-sign-out-alt"></i>
            </button>
          </template>

          <button @click="navOpen = !navOpen" type="button" aria-label="Toggle navigation" class="md:hidden text-slate-700 p-2 rounded-lg hover:bg-slate-100 transition-all focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path v-if="!navOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
              <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>
    </div>
    <!-- Mobile dropdown -->
    <div v-show="navOpen" class="md:hidden bg-white border-t border-slate-100 px-6 py-4 space-y-2.5 shadow-xl">
      <router-link 
        v-for="link in menu" 
        :key="link.to" 
        :to="link.to" 
        @click="navOpen = false"
        class="block py-2 text-sm font-bold text-slate-700 hover:text-green-600 border-b border-slate-50 last:border-none"
      >
        {{ link.label }}
      </router-link>
    </div>
  </nav>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useAuth } from '../store/auth';
import api from '@/api';

const router = useRouter();
const { isAuthenticated, getUserName, getUserPhoto, clearAuth, openLogin, openRegister } = useAuth();
const siteSettings = ref({});
const navOpen = ref(false);

const fetchSettings = async () => {
  try {
    const res = await api.get('/settings');
    siteSettings.value = res.data;
  } catch (e) {
    console.error(e);
  }
};

onMounted(fetchSettings);

const menu = [
  { to: '/', label: 'Beranda' },
  { to: '/news', label: 'Berita' },
  { to: '/campaigns', label: 'Program' },
  { to: '/zakat', label: 'Zakat' },
  { to: '/about', label: 'Tentang Kami' },
  { to: '/contact', label: 'Kontak' },
];

const handleLogout = () => { clearAuth(); router.push('/'); };
</script>