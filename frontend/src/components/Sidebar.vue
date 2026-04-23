<template>
  <!-- Desktop Sidebar (Tampil hanya di md ke atas) -->
  <aside class="hidden md:flex flex-col w-64 min-w-[16rem] space-y-6 sticky top-28 h-fit">
    <!-- Info Akun -->
    <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm relative overflow-hidden group">
      <div class="absolute -right-4 -top-4 w-16 h-16 bg-blue-600/5 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
      <div class="relative z-10 space-y-3">
        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none">Petugas Aktif</p>
        <p class="text-xl font-black text-gray-900 tracking-tighter truncate">{{ userName }}</p>
        <div class="inline-flex px-3 py-1 bg-blue-600 text-white rounded-full text-[9px] font-black uppercase tracking-widest shadow-lg shadow-blue-100">
          {{ userRole?.replace('_', ' ') || 'User' }}
        </div>
      </div>
    </div>

    <!-- Menu Navigasi Sidebar -->
    <nav class="space-y-2">
      <router-link 
        v-for="link in navigationLinks"
        :key="link.to"
        :to="link.to"
        class="flex items-center gap-4 px-6 py-4 rounded-2xl text-sm font-black uppercase tracking-widest transition-all group border-2 border-transparent"
        :class="isActive(link.to) 
          ? 'bg-white border-blue-600 text-blue-600 shadow-xl shadow-blue-100/50' 
          : 'text-gray-400 hover:bg-white hover:text-gray-900 hover:border-gray-100'"
      >
        <i :class="[link.icon, 'w-5 text-center transition-transform group-hover:scale-110']"></i>
        {{ link.label }}
      </router-link>
    </nav>
  </aside>
</template>

<script setup>
import { computed, markRaw } from 'vue';
import { useRoute } from 'vue-router';

const route = useRoute();
const userName = computed(() => localStorage.getItem('name'));
const userRole = computed(() => localStorage.getItem('role'));

const isActive = (path) => route.path === path;

const navigationLinks = computed(() => {
  const base = [
    { to: '/dashboard', label: 'Dashboard', icon: 'fas fa-th-large' }
  ];

  if (userRole.value === 'admin') {
    base.push({ to: '/dashboard/admin', label: 'Admin', icon: 'fas fa-user-shield' });
  } else if (userRole.value === 'field_worker') {
    base.push({ to: '/dashboard/field', label: 'Panel', icon: 'fas fa-briefcase' });
  } else {
    base.push({ to: '/dashboard/user', label: 'Profil', icon: 'fas fa-user-circle' });
  }

  return base;
});
</script>