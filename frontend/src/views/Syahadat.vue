<template>
  <div class="min-h-screen bg-slate-50 pt-20 pb-20">
    <div class="max-w-4xl mx-auto px-6">
      
      <!-- Guest View -->
      <div v-if="!isAuthenticated" class="text-center py-20 animate-in fade-in zoom-in duration-700">
         <div class="bg-white p-12 rounded-[3rem] shadow-2xl border border-slate-100 max-w-lg mx-auto">
            <div class="bg-blue-50 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-8 text-blue-600">
               <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 15v2m-6 4h12a2 2 0 0 0 2-2v-6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2zm10-10V7a4 4 0 0 0-8 0v4h8z" /></svg>
            </div>
            <h2 class="text-2xl font-black text-slate-900 uppercase tracking-tighter italic mb-4">Akses Terbatas</h2>
            <p class="text-slate-400 font-bold text-sm leading-relaxed mb-10">Mohon masuk ke akun Anda terlebih dahulu untuk mengajukan jadwal ikrar syahadat dan mendapatkan bimbingan mualaf.</p>
            <button @click="openLogin" class="w-full py-5 bg-blue-600 text-white rounded-[2rem] font-black uppercase tracking-[0.2em] shadow-xl shadow-blue-100 hover:bg-blue-700 transition">Masuk Sekarang</button>
         </div>
      </div>

      <!-- Auth View -->
      <div v-else class="animate-in slide-in-from-bottom duration-700">
        <!-- Header Section -->
        <div class="text-center mb-12 space-y-4">
          <div class="inline-flex p-4 bg-blue-50 rounded-3xl text-blue-600 mb-4 shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c0 2 2 3 6 3s6-1 6-3v-5"/></svg>
          </div>
          <h1 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tighter uppercase leading-none">Layanan <span class="text-blue-600">Mualaf</span></h1>
          <p class="text-slate-500 font-bold text-sm tracking-wide max-w-lg mx-auto">Kami mendampingi proses ikrar Syahadat Anda dengan penuh keikhlasan dan dukungan berkelanjutan.</p>
        </div>

        <!-- Main Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
          
          <!-- Checklist & Info -->
          <div class="space-y-6 lg:col-span-1">
            <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-200/50">
              <h3 class="text-xs font-black uppercase tracking-widest text-slate-400 mb-6">Persyaratan</h3>
              <ul class="space-y-4">
                <li v-for="step in requirements" :key="step" class="flex gap-4 items-start group">
                  <div class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-[10px] shrink-0 font-black mt-0.5 group-hover:scale-110 transition-transform">✓</div>
                  <span class="text-sm font-bold text-slate-600 leading-snug">{{ step }}</span>
                </li>
              </ul>
            </div>

            <div class="bg-blue-600 p-8 rounded-[2rem] text-white shadow-xl shadow-blue-100">
              <h4 class="text-lg font-black tracking-tight mb-2 uppercase italic">Penting!</h4>
              <p class="text-white/80 text-xs font-bold leading-relaxed mb-6">Proses syahadat tidak dipungut biaya apapun (Gratis). Kami menjamin kerahasiaan data Anda.</p>
              <a href="https://wa.me/628123456789" class="block w-full py-4 bg-white/20 backdrop-blur-md rounded-2xl text-center text-xs font-black uppercase tracking-widest hover:bg-white/30 transition">Butuh Bantuan?</a>
            </div>
          </div>

          <!-- Registration Form -->
          <div class="lg:col-span-2 bg-white p-10 rounded-[3rem] border border-slate-100 shadow-xl shadow-slate-200/50">
            <form @submit.prevent="handleSubmit" class="space-y-8">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                  <label class="text-[10px] font-black uppercase text-slate-400 pl-2">Nama Lengkap</label>
                  <input v-model="form.name" type="text" class="w-full px-6 py-4 bg-slate-50 border border-transparent rounded-2xl focus:border-blue-500 focus:bg-white outline-none transition-all font-bold placeholder:text-slate-300" placeholder="Sesuai KTP" required>
                </div>
                <div class="space-y-2">
                  <label class="text-[10px] font-black uppercase text-slate-400 pl-2">Email</label>
                  <input v-model="form.email" type="email" class="w-full px-6 py-4 bg-slate-50 border border-transparent rounded-2xl focus:border-blue-500 focus:bg-white outline-none transition-all font-bold placeholder:text-slate-300" placeholder="akun@email.com" required>
                </div>
              </div>

              <div class="space-y-2">
                <label class="text-[10px] font-black uppercase text-slate-400 pl-2">Alamat Domisili</label>
                <textarea v-model="form.address" rows="3" class="w-full px-6 py-4 bg-slate-50 border border-transparent rounded-2xl focus:border-blue-500 focus:bg-white outline-none transition-all font-bold placeholder:text-slate-300" placeholder="Alamat lengkap saat ini" required></textarea>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                 <div class="space-y-2">
                   <label class="text-[10px] font-black uppercase text-slate-400 pl-2">Jadwal yang Diinginkan</label>
                   <input v-model="form.date" type="date" class="w-full px-6 py-4 bg-slate-50 border border-transparent rounded-2xl focus:border-blue-500 focus:bg-white outline-none transition-all font-bold" required>
                 </div>
                 <div class="space-y-2">
                   <label class="text-[10px] font-black uppercase text-slate-400 pl-2">Lokasi Terdekat</label>
                   <select v-model="form.location" class="w-full px-6 py-4 bg-slate-50 border border-transparent rounded-2xl focus:border-blue-500 focus:bg-white outline-none transition-all font-bold appearance-none">
                      <option value="" disabled selected>Pilih Cabang</option>
                      <option value="jakarta">Pusat HumanityCare Jakarta</option>
                      <option value="bandung">HumanityCare Bandung</option>
                      <option value="surabaya">HumanityCare Surabaya</option>
                   </select>
                 </div>
              </div>

              <button type="submit" :disabled="isLoading" class="w-full py-5 bg-blue-600 text-white rounded-3xl font-black uppercase tracking-[0.2em] shadow-xl shadow-blue-100 hover:bg-blue-700 hover:-translate-y-1 transition duration-300 disabled:opacity-50">
                {{ isLoading ? 'Mengirim...' : 'Ajukan Jadwal Ikrar Syahadat' }}
              </button>
            </form>
          </div>

        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue';
import { useAuth } from '../store/auth';
import api from '../api';

const { isAuthenticated, openLogin } = useAuth();
const isLoading = ref(false);

const requirements = [
  'Niat Ikhlas dari Hati Sendiri',
  'Salinan KTP / Paspor',
  'Foto Ukuran 3x4 (2 lembar)',
  'Dua Orang Saksi Muslim',
  'Surat Pernatyaan Tidak Ada Paksaan'
];

const form = reactive({
  name: '',
  email: '',
  address: '',
  date: '',
  location: ''
});

const handleSubmit = async () => {
  isLoading.value = true;
  try {
    const res = await api.post('/muallaf/request', form);
    alert('Terima kasih, pengajuan Anda sedang diproses oleh Tim Mualaf Center kami.');
    form.name = '';
    form.email = '';
    form.address = '';
    form.date = '';
    form.location = '';
  } catch (err) {
    alert('Gagal mengirim pengajuan. Coba lagi nanti.');
  } finally {
    isLoading.value = false;
  }
};
</script>
