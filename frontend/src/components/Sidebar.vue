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
        <component :is="link.icon" class="w-5 h-5 transition-transform group-hover:scale-110" />
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

// Icons
const LayoutIcon = { template: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>' };
const CampaignIcon = { template: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>' };
const UserIcon = { template: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="7" r="4"/><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/></svg>' };

const navigationLinks = computed(() => {
  const base = [
    { to: '/dashboard', label: 'Dashboard', icon: markRaw(LayoutIcon) }
  ];

  if (userRole.value === 'admin') {
    base.push({ to: '/dashboard/admin', label: 'Admin', icon: markRaw(UserIcon) });
  } else if (userRole.value === 'field_worker') {
    base.push({ to: '/dashboard/field', label: 'Panel', icon: markRaw(CampaignIcon) });
  } else {
    base.push({ to: '/dashboard/user', label: 'Profil', icon: markRaw(UserIcon) });
  }

  return base;
});
</script>