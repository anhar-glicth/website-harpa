<script setup>
import { computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useAuth } from '../store/auth';

const route = useRoute();
const router = useRouter();
const { isAuthenticated, getUserRole, openLogin } = useAuth();

const navLinks = computed(() => {
  const path = route.path || '/';
  const isDashboard = path.startsWith('/dashboard');

  if (isDashboard) {
    const dashboardBase = [
      { to: '/dashboard', label: 'Ringkasan', type: 'dashboard' },
      { to: '/', label: 'Website', type: 'home' }
    ];
    if (getUserRole.value === 'admin') dashboardBase.splice(1, 0, { to: '/dashboard/admin', label: 'Admin', type: 'admin' });
    if (getUserRole.value === 'field_worker') dashboardBase.splice(1, 0, { to: '/dashboard/field', label: 'Panel', type: 'panel' });
    return dashboardBase;
  }

  return [
    { to: '/', label: 'Beranda', type: 'home' },
    { to: '/news', label: 'Berita', type: 'news' },
    { to: isAuthenticated.value ? '/dashboard' : null, label: isAuthenticated.value ? 'Akun' : 'Masuk', type: 'profile', action: !isAuthenticated.value ? 'login' : null }
  ];
});

const handleNavClick = (link, event) => {
  if (link.action === 'login') {
    event.preventDefault();
    openLogin();
  } else if (link.to) {
    router.push(link.to);
  }
};
</script>

<template>
  <nav class="md:hidden fixed bottom-0 left-0 right-0 z-[110] bg-white/95 backdrop-blur-2xl border-t border-gray-100 px-2 py-3 flex justify-around items-center shadow-[0_-10px_40px_rgba(0,0,0,0.08)] pb-safe">
    <button 
      v-for="link in navLinks"
      :key="link.label"
      @click="handleNavClick(link, $event)"
      class="flex flex-col items-center gap-1.5 transition-all text-[10px] font-black uppercase tracking-tighter w-full outline-none"
      :class="route.path === link.to ? 'text-blue-600' : 'text-slate-400'"
    >
      <div :class="['p-2.5 rounded-2xl transition-all duration-300', route.path === link.to ? 'bg-blue-50' : '']">
        <svg v-if="link.type === 'home'" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        <svg v-else-if="link.type === 'dashboard' || link.type === 'admin' || link.type === 'panel'" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
        <svg v-else-if="link.type === 'news'" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/><path d="M18 14h-8"/><path d="M15 18h-5"/><path d="M10 6h8v4h-8V6Z"/></svg>
        <svg v-else-if="link.type === 'profile'" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        <svg v-else xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
      </div>
      <span class="truncate max-w-[64px] font-black tracking-widest">{{ link.label }}</span>
    </button>
  </nav>
</template>

<style scoped>
.pb-safe { 
  padding-bottom: calc(0.75rem + env(safe-area-inset-bottom, 0px)); 
}
</style>
