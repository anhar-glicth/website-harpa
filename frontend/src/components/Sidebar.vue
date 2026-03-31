
<template>
  <aside class="w-full md:w-64 flex flex-col gap-2">
    <!-- Info User -->
    <div class="p-4 bg-white border border-gray-100 rounded-2xl mb-4 shadow-sm">
      <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider mb-1">Status Akun</p>
      <p class="font-extrabold text-gray-800 truncate">{{ userName }}</p>
      <span class="inline-block px-2 py-0.5 bg-blue-50 text-blue-600 rounded text-[10px] font-bold uppercase mt-2">
        {{ userRole.replace('_', ' ') }}
      </span>
    </div>

    <!-- Menu Navigasi Sidebar -->
    <nav class="space-y-1">
      <router-link 
        to="/dashboard" 
        class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all"
        :class="$route.path === '/dashboard' ? 'bg-blue-600 text-white shadow-lg shadow-blue-100' : 'text-gray-500 hover:bg-white hover:text-blue-600 border border-transparent hover:border-gray-100'"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
        Ringkasan
      </router-link>

      <!-- Menu Khusus Admin/Field Worker -->
      <router-link 
        v-if="userRole === 'admin'"
        to="/dashboard/manage" 
        class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all text-gray-500 hover:bg-white hover:text-blue-600 border border-transparent hover:border-gray-100"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/></svg>
        Kelola Kampanye
      </router-link>

      <router-link 
        v-if="userRole === 'field_worker'"
        to="/dashboard/reports" 
        class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all text-gray-500 hover:bg-white hover:text-blue-600 border border-transparent hover:border-gray-100"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
        Laporan Lapangan
      </router-link>
    </nav>
  </aside>
</template>

<script setup>
import { useAuth } from '@/store/auth';

const { getUserName, getUserRole } = useAuth();
const userName = getUserName;
const userRole = getUserRole;
</script>