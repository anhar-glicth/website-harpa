<template>
  <!-- Desktop Sidebar (Tampil hanya di md ke atas) -->
  <aside class="hidden md:flex flex-col w-72 space-y-6 sticky top-28 h-fit">
    <!-- Info Akun -->
    <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm relative overflow-hidden group">
      <div class="absolute -right-4 -top-4 w-24 h-24 bg-green-600/5 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
      <div class="relative z-10 space-y-4 text-center">
        <div class="w-20 h-20 bg-white p-1 rounded-2xl shadow-xl shadow-slate-200/50 mx-auto relative group-hover:scale-105 transition-transform duration-500 overflow-hidden">
           <img 
             v-if="userPhoto" 
             :src="userPhoto" 
             class="w-full h-full object-cover rounded-xl"
             alt="Profile"
           />
           <div v-else class="w-full h-full bg-green-50 text-green-600 flex items-center justify-center text-3xl rounded-xl">
              <i :class="role === 'admin' ? 'fas fa-user-shield' : (role === 'field_worker' || role === 'petugas' ? 'fas fa-id-badge' : 'fas fa-heart')"></i>
           </div>
        </div>
        <div class="space-y-1">
           <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none">{{ role?.replace('_', ' ') || 'User' }}</p>
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
        class="flex items-center gap-4 px-5 py-4 rounded-3xl text-[10px] font-black uppercase tracking-widest transition-all duration-300 group border-2 border-transparent outline-none"
        :class="isActive(link.to) 
          ? 'bg-green-600 border-green-600 text-white shadow-2xl shadow-green-200/50 scale-[1.02]' 
          : 'text-gray-400 hover:bg-white hover:text-green-600 hover:border-green-100 hover:shadow-lg hover:shadow-slate-100'"
      >
        <div :class="['w-9 h-9 rounded-2xl flex items-center justify-center transition-all duration-300', isActive(link.to) ? 'bg-white/20 text-white rotate-12' : 'bg-gray-50 text-gray-400 group-hover:bg-green-50 group-hover:text-green-600 group-hover:rotate-12']">
           <i :class="[link.icon, 'text-[11px]']"></i>
        </div>
        <span class="whitespace-nowrap">{{ link.label }}</span>
      </router-link>

      <button 
        @click="showLogoutModal = true"
        class="w-full flex items-center gap-4 px-5 py-4 rounded-3xl text-[10px] font-black uppercase tracking-widest transition-all duration-300 group border-2 border-transparent text-gray-400 hover:bg-red-50/50 hover:text-red-600 hover:border-red-100 hover:shadow-lg hover:shadow-red-50 mt-2"
      >
        <div class="w-9 h-9 bg-gray-50 text-gray-400 rounded-2xl flex items-center justify-center transition-all duration-300 group-hover:bg-white group-hover:text-red-600 group-hover:rotate-12">
           <i class="fas fa-sign-out-alt text-[11px]"></i>
        </div>
        Keluar Akun
      </button>
    </nav>

    <!-- Modal Konfirmasi Logout Premium -->
    <Teleport to="body">
      <div v-if="showLogoutModal" class="fixed inset-0 z-[200] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-md animate-in fade-in duration-300" @click="!isLoggingOut && (showLogoutModal = false)"></div>
        
        <div class="bg-white w-full max-w-sm rounded-[2.5rem] shadow-2xl relative z-10 overflow-hidden animate-in zoom-in-95 duration-300">
          <div class="p-10 text-center">
            <div class="w-20 h-20 bg-red-50 text-red-500 rounded-[2rem] flex items-center justify-center text-3xl mx-auto mb-6 shadow-sm">
              <i class="fas fa-shield-alt"></i>
            </div>
            
            <h3 class="text-2xl font-black text-gray-900 tracking-tighter mb-2 italic">Konfirmasi <span class="text-red-600">Keamanan</span></h3>
            <p class="text-[11px] text-gray-400 font-bold uppercase tracking-widest leading-relaxed mb-8">
              Apakah Anda yakin ingin mengakhiri sesi ini dan menghapus akses sementara?
            </p>
            
            <div class="flex flex-col gap-3">
              <button 
                @click="handleLogout" 
                :disabled="isLoggingOut"
                class="w-full bg-slate-900 text-white py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-red-600 transition-all flex items-center justify-center gap-3 shadow-lg shadow-slate-200"
              >
                <i v-if="isLoggingOut" class="fas fa-circle-notch animate-spin"></i>
                <span v-else>Ya, Keluar Sekarang</span>
              </button>
              
              <button 
                @click="showLogoutModal = false" 
                :disabled="isLoggingOut"
                class="w-full bg-gray-50 text-gray-400 py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-gray-100 transition-all"
              >
                Batal
              </button>
            </div>
          </div>
          
          <div class="bg-gray-50 py-4 px-6 border-t border-gray-100 flex items-center justify-center gap-2">
            <i class="fas fa-lock text-[8px] text-gray-300"></i>
            <span class="text-[8px] font-black text-gray-300 uppercase tracking-widest">Sesi Enkripsi Terproteksi</span>
          </div>
        </div>
      </div>
    </Teleport>
  </aside>

  <!-- Mobile Bottom Navigation (Tampil hanya di < md) -->
  <nav class="md:hidden fixed bottom-0 left-0 right-0 bg-white/80 backdrop-blur-xl border-t border-gray-100 z-[100] px-4 py-2 flex justify-around items-center shadow-[0_-10px_30px_rgba(0,0,0,0.05)] rounded-t-[2rem]">
    <router-link 
      v-for="link in navigationLinks"
      :key="link.to"
      :to="link.to"
      class="flex flex-col items-center gap-1 p-3 transition-all duration-300"
      :class="isActive(link.to) ? 'text-green-600 scale-110' : 'text-gray-400'"
    >
      <i :class="[link.icon, 'text-lg']"></i>
      <span class="text-[8px] font-black uppercase tracking-widest">{{ link.label.split(' ')[0] }}</span>
      <div v-if="isActive(link.to)" class="w-1 h-1 bg-green-600 rounded-full mt-0.5"></div>
    </router-link>
    <button @click="showLogoutModal = true" class="flex flex-col items-center gap-1 p-3 text-gray-400">
       <i class="fas fa-sign-out-alt text-lg"></i>
       <span class="text-[8px] font-black uppercase tracking-widest">Logout</span>
    </button>
  </nav>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useAuth } from '../store/auth';

const route = useRoute();
const router = useRouter();
const { 
  getUserName: storeUserName, 
  getUserRole: storeUserRole, 
  getUserPhoto: storeUserPhoto,
  clearAuth 
} = useAuth();

const userName = computed(() => storeUserName.value || localStorage.getItem('name') || 'Guest');
const userPhoto = computed(() => storeUserPhoto.value || localStorage.getItem('photo'));
const role = computed(() => (storeUserRole.value || localStorage.getItem('role') || 'user').trim().toLowerCase());

const showLogoutModal = ref(false);
const isLoggingOut = ref(false);

const isActive = (path) => {
  if (path === '/dashboard') return route.path === '/dashboard';
  return route.path.startsWith(path);
};

const handleLogout = async () => {
  isLoggingOut.value = true;
  
  // Simulasi proses pembersihan sesi agar terasa "premium"
  await new Promise(resolve => setTimeout(resolve, 800));
  
  clearAuth();
  sessionStorage.clear();
  
  // Hapus cookie jika ada (opsional)
  document.cookie.split(";").forEach((c) => {
    document.cookie = c
      .replace(/^ +/, "")
      .replace(/=.*/, "=;expires=" + new Date().toUTCString() + ";path=/");
  });

  router.push('/login');
};

const navigationLinks = computed(() => {
  const currentRole = role.value;
  
  const base = [
    { to: '/dashboard/overview', label: 'Ringkasan', icon: 'fas fa-th-large' }
  ];

  if (currentRole === 'admin') {
    base.push({ to: '/dashboard/admin', label: 'CMS Admin', icon: 'fas fa-user-shield' });
  } else if (currentRole === 'field_worker' || currentRole === 'petugas') {
    base.push({ to: '/dashboard/field', label: 'Panel Staff', icon: 'fas fa-briefcase' });
  } else {
    base.push({ to: '/dashboard/user', label: 'Donasi & Riwayat', icon: 'fas fa-heart' });
  }

  // Edit Profil tersedia untuk semua role
  base.push({ to: '/dashboard/profile', label: 'Edit Profil', icon: 'fas fa-user-cog' });

  return base;
});
</script>