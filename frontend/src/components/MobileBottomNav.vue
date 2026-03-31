<script setup>
import { computed } from 'vue';
import { useRoute } from 'vue-router';

// Menggunakan hook route untuk memantau rute aktif di HP
const route = useRoute();
const isLoggedIn = computed(() => !!localStorage.getItem('token'));
const userRole = computed(() => localStorage.getItem('role'));

// Menentukan tautan navigasi berdasarkan status login dan rute aktif
const navLinks = computed(() => {
  const path = route.path || '/';
  const isDashboard = path.startsWith('/dashboard');

  if (isDashboard) {
    const dashboardBase = [
      { to: '/dashboard', label: 'Ringkasan', type: 'dashboard' },
      { to: '/', label: 'Website', type: 'home' }
    ];
    if (userRole.value === 'admin') dashboardBase.splice(1, 0, { to: '/dashboard/admin', label: 'Admin', type: 'admin' });
    if (userRole.value === 'field_worker') dashboardBase.splice(1, 0, { to: '/dashboard/field', label: 'Panel', type: 'panel' });
    return dashboardBase;
  }

  return [
    { to: '/', label: 'Beranda', type: 'home' },
    { to: '/about', label: 'Tentang', type: 'info' },
    { to: '/help', label: 'Bantuan', type: 'help' },
    { to: isLoggedIn.value ? '/dashboard' : '/login', label: isLoggedIn.value ? 'Akun' : 'Masuk', type: 'profile' }
  ];
});
</script>

<template>
  <!-- Bilah Navigasi Bawah (Hanya muncul di Layar HP) -->
  <nav class="md:hidden fixed bottom-0 left-0 right-0 z-[110] bg-white/95 backdrop-blur-2xl border-t border-gray-100 px-2 py-3 flex justify-around items-center shadow-[0_-10px_40px_rgba(0,0,0,0.08)] pb-safe">
    <router-link 
      v-for="link in navLinks"
      :key="link.to"
      :to="link.to"
      class="flex flex-col items-center gap-1.5 transition-all text-[10px] font-black uppercase tracking-tighter w-full"
      :class="route.path === link.to ? 'text-blue-600' : 'text-slate-400'"
    >
      <div :class="['p-2.5 rounded-2xl transition-all duration-300', route.path === link.to ? 'bg-blue-50' : '']">
        <!-- Home SVG -->
        <svg v-if="link.type === 'home'" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        
        <!-- Dashboard SVG -->
        <svg v-else-if="link.type === 'dashboard' || link.type === 'admin' || link.type === 'panel'" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
        
        <!-- Profile SVG -->
        <svg v-else-if="link.type === 'profile'" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        
        <!-- Info/Site SVG -->
        <svg v-else xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
      </div>
      <span class="truncate max-w-[64px] font-black">{{ link.label }}</span>
    </router-link>
  </nav>
</template>

<style scoped>
/* Memastikan area bawah aman untuk pengguna iPhone dengan gesture bar */
.pb-safe {
  padding-bottom: env(safe-area-inset-bottom, 0.75rem);
}
</style>
