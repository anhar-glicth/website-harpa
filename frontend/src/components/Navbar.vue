<template>
  <nav class="bg-white sticky top-0 z-[100] border-b border-gray-100 shadow-sm">
    <div class="max-w-7xl mx-auto px-6">
      <div class="flex justify-between h-16 items-center">
        <!-- Logo -->
        <router-link to="/" class="flex items-center gap-2">
          <svg class="w-7 h-7 text-green-600" viewBox="0 0 24 24" fill="currentColor"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
          <span class="text-lg font-bold text-slate-900">Humanity<span class="text-green-600">Care</span></span>
        </router-link>

        <!-- Desktop Menu -->
        <div class="hidden md:flex items-center gap-8">
          <router-link v-for="link in menu" :key="link.to" :to="link.to"
            class="text-sm text-slate-500 hover:text-slate-900 transition-colors font-medium">
            {{ link.label }}
          </router-link>
        </div>

        <!-- Actions -->
        <div class="flex items-center gap-3">
          <template v-if="!isAuthenticated">
            <button @click="openLogin" class="text-sm font-semibold text-green-600 hover:text-green-700 transition-colors">Masuk</button>
            <button @click="openRegister" class="bg-green-600 text-white px-5 py-2 rounded-lg text-sm font-semibold hover:bg-green-700 transition-all">Daftar</button>
          </template>
          <template v-else>
            <router-link to="/dashboard" class="w-8 h-8 rounded-full bg-green-100 border-2 border-green-200 flex items-center justify-center text-green-600 font-bold text-xs overflow-hidden hover:border-green-500 transition-all">
              <img v-if="getUserPhoto" :src="getUserPhoto" class="w-full h-full object-cover" />
              <span v-else>{{ getUserName?.charAt(0).toUpperCase() }}</span>
            </router-link>
          </template>
        </div>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { useRouter } from 'vue-router';
import { useAuth } from '../store/auth';

const router = useRouter();
const { isAuthenticated, getUserName, getUserPhoto, clearAuth, openLogin, openRegister } = useAuth();

const menu = [
  { to: '/', label: 'Beranda' },
  { to: '/news', label: 'Berita' },
  { to: '/campaigns', label: 'Program' },
  { to: '/zakat', label: 'Zakat' },
  { to: '/about', label: 'Tentang Kami' },
];

const handleLogout = () => { clearAuth(); router.push('/'); };
</script>