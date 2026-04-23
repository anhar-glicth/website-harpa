<template>
  <!-- Desktop Sidebar (Tampil hanya di md ke atas) -->
  <aside class="hidden md:flex flex-col w-72 space-y-6 sticky top-28 h-fit">
    <!-- Info Akun -->
    <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm relative overflow-hidden group">
      <div class="absolute -right-4 -top-4 w-24 h-24 bg-blue-600/5 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
      <div class="relative z-10 space-y-4 text-center">
        <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-2xl mx-auto shadow-sm">
           <i :class="userRole === 'admin' ? 'fas fa-user-shield' : (userRole === 'field_worker' ? 'fas fa-id-badge' : 'fas fa-heart')"></i>
        </div>
        <div class="space-y-1">
           <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none">{{ userRole?.replace('_', ' ') || 'User' }}</p>
           <p class="text-lg font-black text-gray-900 tracking-tighter truncate">{{ userName }}</p>
        </div>
      </div>
    </div>

    <!-- Menu Navigasi Sidebar -->
    <nav class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden p-3 space-y-1">
      <p class="text-[9px] font-black text-gray-300 uppercase tracking-widest pl-5 py-3">Navigasi Utama</p>
      <router-link 
        v-for="link in navigationLinks"
        :key="link.to"
        :to="link.to"
        class="flex items-center gap-4 px-5 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all group border-2 border-transparent"
        :class="isActive(link.to) 
          ? 'bg-blue-600 border-blue-600 text-white shadow-xl shadow-blue-100' 
          : 'text-gray-400 hover:bg-gray-50 hover:text-gray-900 hover:border-gray-100'"
      >
        <div :class="['w-8 h-8 rounded-lg flex items-center justify-center transition-all', isActive(link.to) ? 'bg-white/20 text-white' : 'bg-gray-50 text-gray-400 group-hover:bg-white']">
           <i :class="[link.icon, 'text-[10px]']"></i>
        </div>
        {{ link.label }}
      </router-link>

      <button 
        @click="handleLogout"
        class="w-full flex items-center gap-4 px-5 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all group border-2 border-transparent text-gray-400 hover:bg-red-50 hover:text-red-600 hover:border-red-100 mt-2"
      >
        <div class="w-8 h-8 bg-gray-50 text-gray-400 rounded-lg flex items-center justify-center group-hover:bg-white group-hover:text-red-600">
           <i class="fas fa-sign-out-alt text-[10px]"></i>
        </div>
        Keluar Akun
      </button>
    </nav>
  </aside>
</template>

<script setup>
import { computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';

const route = useRoute();
const router = useRouter();
const userName = computed(() => localStorage.getItem('name') || 'Guest');
const userRole = computed(() => localStorage.getItem('role'));

const isActive = (path) => route.path === path;

const handleLogout = () => {
  localStorage.clear();
  router.push('/login');
};

const navigationLinks = computed(() => {
  const base = [
    { to: '/dashboard', label: 'Ringkasan', icon: 'fas fa-th-large' }
  ];

  if (userRole.value === 'admin') {
    base.push({ to: '/dashboard/admin', label: 'CMS Admin', icon: 'fas fa-user-shield' });
  } else if (userRole.value === 'field_worker') {
    base.push({ to: '/dashboard/field', label: 'Panel Staff', icon: 'fas fa-briefcase' });
  } else {
    base.push({ to: '/dashboard/user', label: 'Profil Saya', icon: 'fas fa-user-circle' });
  }

  return base;
});
</script>