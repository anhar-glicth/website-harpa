<template>
  <nav class="bg-white/90 backdrop-blur-xl border-b border-gray-100 sticky top-0 z-[100] transition-all duration-500">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
      <div class="flex justify-between h-16 md:h-20 items-center">
        <!-- Brand Logo -->
        <div class="flex items-center">
          <router-link to="/" class="flex items-center gap-3 group">
            <div class="bg-blue-600 p-2 md:p-2.5 rounded-xl md:rounded-2xl text-white shadow-lg shadow-blue-100 transform group-hover:rotate-6 transition-transform">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 md:w-6 md:h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
            </div>
            <span class="text-xl md:text-2xl font-black tracking-tighter text-gray-900">Humanity<span class="text-blue-600">Care</span></span>
          </router-link>
        </div>

        <!-- Desktop Navigation -->
        <div class="hidden md:flex items-center gap-8">
          <router-link v-for="link in desktopMenu" :key="link.to" :to="link.to" class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 hover:text-blue-600 transition-colors">
            {{ link.label }}
          </router-link>
          
          <div class="h-6 w-px bg-gray-100 mx-2"></div>

          <template v-if="!isAuthenticated">
            <button @click="openLogin" class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 hover:text-blue-600 transition-colors">Masuk</button>
            <button @click="openRegister" class="bg-blue-600 text-white px-8 py-3.5 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] hover:bg-blue-700 transition shadow-xl shadow-blue-100">Daftar</button>
          </template>
          
          <template v-else>
            <div class="flex items-center gap-4">
              <div class="text-right">
                <p class="text-[10px] font-black uppercase text-gray-900 leading-none mb-1">{{ getUserName }}</p>
                <button @click="handleLogout" class="text-[9px] font-bold text-red-500 uppercase tracking-widest hover:underline">Keluar</button>
              </div>
              <router-link to="/dashboard" class="w-10 h-10 rounded-full bg-blue-50 border-2 border-white shadow-sm flex items-center justify-center text-blue-600 font-black text-xs">
                {{ getUserName?.charAt(0).toUpperCase() }}
              </router-link>
            </div>
          </template>
        </div>

        <!-- Mobile Header (Icons only) -->
        <div class="flex md:hidden items-center gap-4">
          <router-link v-if="isAuthenticated" to="/dashboard" class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 font-black text-xs">
            {{ getUserName?.charAt(0).toUpperCase() }}
          </router-link>
          <button v-else @click="openLogin" class="text-xs font-black uppercase tracking-widest text-blue-600 border border-blue-100 px-4 py-2 rounded-xl">Masuk</button>
        </div>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { useRouter } from 'vue-router';
import { useAuth } from '../store/auth';

const router = useRouter();
const { isAuthenticated, getUserName, clearAuth, openLogin, openRegister } = useAuth();

const desktopMenu = [
  { to: '/', label: 'Jelajah' },
  { to: '/news', label: 'Berita' },
  { to: '/muallaf', label: 'Syahadat' },
  { to: '/about', label: 'Tentang' }
];

const handleLogout = () => {
  clearAuth();
  router.push('/login');
};
</script>