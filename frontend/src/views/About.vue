<template>
  <div class="min-h-screen bg-white">
    <div class="max-w-4xl mx-auto px-4 py-12 md:py-24 space-y-12 md:space-y-16">
      <!-- Header -->
      <header class="text-center space-y-3">
        <h1 class="text-xl md:text-4xl font-black text-gray-900 tracking-tighter uppercase">Tentang <span class="text-green-600">HumanityCare</span></h1>
        <p class="text-[11px] md:text-base text-gray-400 font-bold max-w-2xl mx-auto tracking-normal leading-relaxed">Membangun jembatan kebaikan antara mereka yang ingin membantu dan mereka yang membutuhkan bantuan.</p>
      </header>

      <!-- Content -->
      <section class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
        <div class="rounded-[2rem] md:rounded-[3rem] overflow-hidden shadow-2xl rotate-2">
          <img :src="siteSettings.about_image || 'https://images.unsplash.com/photo-1469571486292-0ba58a3f068b?q=80&w=2070&auto=format&fit=crop'" class="w-full h-full object-cover" alt="Mission" />
        </div>
        <div class="space-y-4">
          <h2 class="text-lg md:text-2xl font-black text-gray-800 tracking-tight uppercase italic">Visi & Misi Kami</h2>
          <p class="text-[10px] md:text-sm text-gray-400 font-bold uppercase tracking-widest leading-relaxed">Visi</p>
          <p class="text-[10px] md:text-sm text-gray-500 leading-relaxed italic font-medium mb-4">{{ siteSettings.inst_vision || 'Menjadi lembaga filantropi terdepan dalam membangun kemandirian ummat.' }}</p>
          
          <p class="text-[10px] md:text-sm text-gray-400 font-bold uppercase tracking-widest leading-relaxed">Misi</p>
          <p class="text-[10px] md:text-sm text-gray-500 leading-relaxed italic font-medium">{{ siteSettings.inst_mission || 'HumanityCare adalah platform donasi digital yang berfokus pada transparansi dan kemudahan akses.' }}</p>
          <div class="space-y-4">
            <div class="flex items-center gap-4 p-4 bg-green-50 rounded-2xl border border-green-100">
              <div class="w-10 h-10 bg-green-600 text-white rounded-xl flex items-center justify-center font-bold">1</div>
              <p class="font-bold text-green-900 italic text-xs md:text-sm">Verifikasi Ketat Setiap Kampanye.</p>
            </div>
            <div class="flex items-center gap-4 p-4 bg-green-50 rounded-2xl border border-green-100">
              <div class="w-10 h-10 bg-green-600 text-white rounded-xl flex items-center justify-center font-bold">2</div>
              <p class="font-bold text-green-900 italic text-xs md:text-sm">Transparansi Penyaluran Dana.</p>
            </div>
          </div>
        </div>
      </section>

      <!-- Values -->
      <section class="bg-gray-900 rounded-[2.5rem] md:rounded-[4rem] p-8 md:p-20 text-white text-center space-y-8 md:space-y-12 shadow-2xl relative overflow-hidden">
        <div class="absolute -top-20 -left-20 w-64 h-64 bg-green-600/20 rounded-full blur-3xl"></div>
        <div class="relative z-10">
          <h2 class="text-xl md:text-3xl font-black tracking-tighter mb-8 md:mb-12 uppercase italic">NILAI-NILAI UTAMA</h2>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-8 md:gap-10">
            <div class="space-y-4">
              <div class="text-4xl text-green-400 font-black">🔥</div>
              <p class="font-black text-[10px] md:text-xs uppercase tracking-widest text-green-400">Integritas</p>
              <p class="text-[11px] md:text-sm text-gray-400 opacity-80 italic">Kejujuran adalah pondasi dari semua kepercayaan donatur kami.</p>
            </div>
            <div class="space-y-4">
              <div class="text-4xl text-green-400 font-black">⚡</div>
              <p class="font-black text-[10px] md:text-xs uppercase tracking-widest text-green-400">Responsif</p>
              <p class="text-[11px] md:text-sm text-gray-400 opacity-80 italic">Bantuan harus dikirimkan secepat mungkin dalam situasi mendesak.</p>
            </div>
            <div class="space-y-4">
              <div class="text-4xl text-amber-400 font-black">🤝</div>
              <p class="font-black text-[10px] md:text-xs uppercase tracking-widest text-amber-400">Solidaritas</p>
              <p class="text-[11px] md:text-sm text-gray-400 opacity-80 italic">Kita semua adalah satu keluarga kemanusiaan global.</p>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import api from '@/api';

const siteSettings = ref({});

onMounted(async () => {
    try {
        const res = await api.get('/settings');
        siteSettings.value = res.data;
    } catch (e) {
        console.error(e);
    }
});
</script>
