<template>
  <main class="space-y-8 w-full pb-24 lg:pb-0">
    
    <!-- Header: Welcome Overview -->
    <header class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm flex flex-col md:flex-row justify-between items-center gap-6">
      <div class="space-y-1 text-center md:text-left">
        <h1 class="text-3xl font-black text-gray-900 tracking-tighter uppercase italic">
          Overview <span class="text-green-600">Ringkasan</span>
        </h1>
        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Pantau kontribusi dan dampak kebaikan Anda</p>
      </div>
      <div class="flex gap-3">
         <div class="px-6 py-4 bg-slate-50 rounded-2xl text-center">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Status Akun</p>
            <p class="text-sm font-black text-green-600 uppercase">Aktif & Terverifikasi</p>
         </div>
      </div>
    </header>

    <!-- PERSONAL IMPACT STATS (Copied & Enhanced from User.vue) -->
    <section class="bg-gradient-to-br from-green-600 to-emerald-700 p-8 md:p-12 rounded-[3rem] text-white relative overflow-hidden shadow-2xl shadow-green-200">
      <div class="absolute inset-0 opacity-10">
        <div class="absolute w-64 h-64 rounded-full border-4 border-white -top-10 -right-10"></div>
        <div class="absolute w-40 h-40 rounded-full border-4 border-white bottom-5 right-32"></div>
      </div>
      <div class="relative z-10 mb-8">
        <p class="text-[10px] font-black uppercase tracking-[0.3em] text-green-200">Dampak Nyata Kebaikan Anda</p>
        <h2 class="text-3xl md:text-5xl font-black tracking-tighter mt-2">Your <span class="text-green-200">Impact</span></h2>
      </div>

      <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8 relative z-10">
        <div v-for="stat in impactStats" :key="stat.label" class="bg-white/10 backdrop-blur-md p-6 rounded-[2rem] border border-white/10 hover:bg-white/20 transition-all group">
          <p class="text-3xl md:text-4xl font-black mb-1 group-hover:scale-110 transition-transform">{{ stat.value }}</p>
          <p class="text-[8px] md:text-[9px] font-black uppercase tracking-widest text-green-100 opacity-80">{{ stat.label }}</p>
          <div class="mt-4 text-2xl">{{ stat.icon }}</div>
        </div>
      </div>
    </section>

    <!-- BADGES & ACHIEVEMENTS -->
    <section class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm p-8 md:p-12 overflow-hidden relative">
       <div class="flex justify-between items-center mb-10">
          <div>
            <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Apresiasi & Penghargaan</p>
            <h3 class="text-2xl font-black text-gray-900 tracking-tighter">Koleksi <span class="text-green-600">Badge</span> Anda</h3>
          </div>
          <div class="w-12 h-12 bg-green-50 rounded-2xl flex items-center justify-center text-green-600">
             <i class="fas fa-award text-xl"></i>
          </div>
       </div>

       <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-6">
          <div v-for="badge in badges" :key="badge.name" 
            class="flex flex-col items-center gap-4 group cursor-help"
            :class="badge.unlocked ? 'opacity-100' : 'opacity-20 grayscale'"
          >
             <div class="relative">
                <div class="w-20 h-20 rounded-full bg-slate-50 flex items-center justify-center text-4xl group-hover:scale-110 transition-transform shadow-inner">
                   {{ badge.icon }}
                </div>
                <div v-if="!badge.unlocked" class="absolute -top-1 -right-1 w-6 h-6 bg-gray-200 rounded-full flex items-center justify-center text-[8px] text-gray-500">
                   <i class="fas fa-lock"></i>
                </div>
             </div>
             <div class="text-center">
                <p class="text-[9px] font-black uppercase tracking-widest text-gray-900">{{ badge.name }}</p>
                <p class="text-[8px] text-gray-400 font-bold mt-0.5">{{ badge.desc }}</p>
             </div>
          </div>
       </div>
    </section>

    <!-- RECENT ACTIVITY FEED (SIMULATED) -->
    <section class="grid lg:grid-cols-2 gap-8">
       <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm p-8">
          <div class="flex items-center justify-between mb-6">
             <h4 class="font-black text-slate-900 uppercase italic tracking-tighter">Warta <span class="text-green-600">Terbaru</span></h4>
             <router-link to="/news" class="text-[9px] font-black text-green-600 uppercase tracking-widest hover:underline">Lihat Semua</router-link>
          </div>
          <div class="space-y-4">
             <div v-for="n in 3" :key="n" class="flex gap-4 p-4 bg-slate-50 rounded-2xl">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center text-green-600 shrink-0">
                   <i class="fas fa-newspaper"></i>
                </div>
                <div class="space-y-1">
                   <p class="text-xs font-black text-slate-900 line-clamp-1">Penyaluran Bantuan Sembako Tahap II Berhasil!</p>
                   <p class="text-[9px] text-gray-400 font-bold uppercase">24 April 2026 · Lapangan</p>
                </div>
             </div>
          </div>
       </div>

       <div class="bg-slate-900 rounded-[2.5rem] shadow-xl p-8 text-white relative overflow-hidden">
          <div class="absolute top-0 right-0 w-32 h-32 bg-green-600/20 rounded-full blur-3xl"></div>
          <div class="relative z-10 h-full flex flex-col justify-between">
             <div>
                <p class="text-[9px] font-black text-green-400 uppercase tracking-widest mb-2">Tips Kebaikan</p>
                <h4 class="text-xl font-black tracking-tight leading-snug">Zakat Maal Anda bisa menyelamatkan ribuan nyawa.</h4>
                <p class="text-xs text-white/40 mt-2 font-medium">Hitung zakat Anda sekarang dengan kalkulator cerdas kami.</p>
             </div>
             <router-link to="/zakat" class="mt-8 block w-full py-4 bg-green-600 text-white text-center rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-green-700 transition-all">
                Kalkulator Zakat →
             </router-link>
          </div>
       </div>
    </section>

  </main>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import api from '@/api';

const userRole = computed(() => (localStorage.getItem('role') || 'user').trim().toLowerCase());

const impactStats = computed(() => {
  if (userRole.value === 'field_worker' || userRole.value === 'petugas') {
    return [
      { label: 'Tugas Selesai', value: fieldStats.value.tasksDone, icon: '✅' },
      { label: 'Berita Dibuat', value: fieldStats.value.newsCount, icon: '📰' },
      { label: 'Absensi (Bln)', value: fieldStats.value.attendance, icon: '📅' },
      { label: 'Proyek Aktif', value: fieldStats.value.activeProjects, icon: '🚀' }
    ];
  }
  return [
    { label: 'Total Donasi', value: donaturStats.value.total_donations, icon: '💰' },
    { label: 'Keluarga Terbantu', value: donaturStats.value.families_helped, icon: '👨‍👩‍👧‍👦' },
    { label: 'KG Beras', value: donaturStats.value.rice_kg, icon: '🌾' },
    { label: 'Program Didukung', value: donaturStats.value.programs_supported, icon: '📦' }
  ];
});

const fieldStats = ref({ tasksDone: '0', newsCount: '0', attendance: '0', activeProjects: '0' });
const donaturStats = ref({ total_donations: '0', families_helped: '0', rice_kg: '0', programs_supported: '0' });

const badges = ref([
  { name: 'Penderma Baru', icon: '🌱', desc: 'Donasi pertama kali', unlocked: true },
  { name: 'Pejuang Pangan', icon: '🍱', desc: '5x Donasi Sembako', unlocked: false },
  { name: 'Sahabat Yatim', icon: '🧸', desc: 'Donasi ke program Yatim', unlocked: false },
  { name: 'Ramadan Karim', icon: '🌙', desc: 'Zakat di bulan Ramadan', unlocked: false },
  { name: 'Pahlawan Kemanusiaan', icon: '🏆', desc: 'Total Donasi > 10jt', unlocked: false }
]);

const fetchData = async () => {
  try {
    if (userRole.value === 'field_worker' || userRole.value === 'petugas') {
       const res = await api.get('/stats/field');
       fieldStats.value = res.data;
    } else {
       const res = await api.get('/stats/user');
       donaturStats.value = res.data;
    }
  } catch (e) {
    console.error('Gagal mengambil statistik ringkasan:', e);
  }
};

onMounted(fetchData);
</script>
