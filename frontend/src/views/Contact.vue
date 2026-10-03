<template>
  <div class="min-h-screen bg-slate-900 text-white flex items-center justify-center p-4">
    <div class="max-w-7xl w-full bg-white/5 backdrop-blur-3xl rounded-[3rem] border border-white/10 shadow-2xl overflow-hidden flex flex-col md:flex-row shadow-white/5">
      <!-- Contact Info Box -->
      <div class="md:w-1/3 bg-green-600 p-12 md:p-20 text-white flex flex-col justify-between space-y-12 shrink-0">
        <div class="space-y-6">
          <h1 class="text-4xl font-black leading-none uppercase tracking-tighter">KONTAK <br>KAMI</h1>
          <p class="text-[10px] uppercase font-black tracking-widest text-green-200 italic">Sapa kami hari ini untuk berbagi kebahagiaan.</p>
        </div>

        <div class="space-y-8">
          <div class="flex items-center gap-6 group hover:translate-x-2 transition-transform">
            <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center text-white"><i class="fas fa-map-marker-alt"></i></div>
            <p class="text-xs font-bold leading-relaxed" v-html="siteSettings.inst_address || 'Gedung Kemanusiaan Lt. 3, <br> Jakarta Selatan, Indonesia'"></p>
          </div>
          <div class="flex items-center gap-6 group hover:translate-x-2 transition-transform">
            <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center text-white"><i class="fas fa-phone-alt"></i></div>
            <p class="text-xs font-bold tracking-widest">{{ siteSettings.inst_phone || '+62 21 5566 7788' }}</p>
          </div>
          <div class="flex items-center gap-6 group hover:translate-x-2 transition-transform">
            <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center text-white"><i class="fas fa-envelope"></i></div>
            <p class="text-xs font-bold tracking-widest lowercase">{{ siteSettings.inst_email || 'halo@amalabadi.org' }}</p>
          </div>
        </div>

        <div class="flex gap-4 pt-4 border-t border-white/10">
          <a :href="siteSettings.inst_fb || '#'" target="_blank" class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-green-600 hover:scale-110 transition-transform cursor-pointer"><i class="fab fa-facebook-f"></i></a>
          <a :href="siteSettings.inst_ig || '#'" target="_blank" class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-green-600 hover:scale-110 transition-transform cursor-pointer"><i class="fab fa-instagram"></i></a>
          <a :href="siteSettings.inst_tw || '#'" target="_blank" class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-green-600 hover:scale-110 transition-transform cursor-pointer"><i class="fab fa-twitter"></i></a>
        </div>
      </div>

      <!-- Contact Form -->
      <div class="flex-grow p-12 md:p-20 space-y-10">
        <h2 class="text-3xl font-black uppercase tracking-tighter">Kirim Pesan <span class="text-green-500">Langsung</span></h2>
        
        <form @submit.prevent="handleSubmit" class="space-y-8">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-2">
              <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">NAMA LENGKAP</label>
              <input v-model="form.name" type="text" class="w-full bg-white/5 border-b-2 border-white/10 focus:border-green-500 outline-none p-4 font-bold text-sm transition-colors" placeholder="Daffa Anis" required />
            </div>
            <div class="space-y-2">
              <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">ALAMAT EMAIL</label>
              <input v-model="form.email" type="email" class="w-full bg-white/5 border-b-2 border-white/10 focus:border-green-500 outline-none p-4 font-bold text-sm transition-colors" placeholder="daffa@example.com" required />
            </div>
          </div>

          <div class="space-y-2">
            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">HAL YANG INGIN DISAMPAIKAN</label>
            <textarea v-model="form.message" rows="4" class="w-full bg-white/5 border-b-2 border-white/10 focus:border-green-500 outline-none p-4 font-bold text-sm transition-colors resize-none" placeholder="Ceritakan pada kami..." required></textarea>
          </div>

          <button :disabled="submitting" class="px-12 py-5 bg-green-600 text-white rounded-2xl font-black uppercase text-xs tracking-[0.2em] shadow-xl shadow-green-900/50 hover:bg-green-700 transition-all active:scale-95 disabled:opacity-50">
            {{ submitting ? 'Mengirim...' : 'Kirim Pertanyaan' }}
          </button>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue';
import api from '@/api';

const form = reactive({ name: '', email: '', message: '' });
const submitting = ref(false);
const siteSettings = ref({});

const fetchSettings = async () => {
  try {
    const res = await api.get('/settings');
    siteSettings.value = res.data;
  } catch (e) {
    console.error(e);
  }
};

const handleSubmit = async () => {
  submitting.value = true;
  try {
    await api.post('/contacts', form);
    alert('Terima kasih! Pesan Anda telah terkirim.');
    form.name = '';
    form.email = '';
    form.message = '';
  } catch (err) {
    alert('Gagal mengirim pesan. Silakan coba lagi nanti.');
  } finally {
    submitting.value = false;
  }
};

import { onMounted } from 'vue';
onMounted(fetchSettings);
</script>
