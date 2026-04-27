<template>
  <div class="min-h-screen bg-white text-slate-900 pt-24 pb-20">
    <div class="max-w-7xl mx-auto px-4">
      
      <!-- Hero Section -->
      <div class="grid lg:grid-cols-2 gap-16 items-center mb-24">
        <div class="space-y-8 text-center lg:text-left">
          <div class="inline-flex items-center gap-2 bg-amber-50 px-4 py-2 rounded-full border border-amber-200">
             <span class="text-[10px] font-black text-amber-600 uppercase tracking-widest italic">Join Our Community</span>
          </div>
          <h1 class="text-4xl md:text-7xl font-black tracking-tighter leading-none uppercase italic">
            Jadilah <span class="text-green-600">Pahlawan</span> <br> Kemanusiaan
          </h1>
          <p class="text-slate-500 font-bold text-sm md:text-lg max-w-xl mx-auto lg:mx-0 leading-relaxed italic">
            Waktu dan tenaga Anda adalah investasi terbaik untuk masa depan mereka. Bergabunglah sebagai relawan dan buat perubahan nyata hari ini.
          </p>
          <div class="flex flex-wrap justify-center lg:justify-start gap-4">
            <div class="flex items-center gap-3 bg-slate-50 px-6 py-4 rounded-2xl border border-slate-100">
               <span class="text-2xl">🌍</span>
               <div class="text-left">
                  <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Cakupan</p>
                  <p class="text-xs font-black text-slate-900">Nasional & Global</p>
               </div>
            </div>
            <div class="flex items-center gap-3 bg-slate-50 px-6 py-4 rounded-2xl border border-slate-100">
               <span class="text-2xl">🤝</span>
               <div class="text-left">
                  <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Komunitas</p>
                  <p class="text-xs font-black text-slate-900">500+ Relawan</p>
               </div>
            </div>
          </div>
        </div>
        
        <div class="relative group">
           <div class="absolute -inset-4 bg-green-600/10 rounded-[4rem] rotate-3 group-hover:rotate-0 transition-transform duration-700"></div>
           <div class="relative aspect-square md:aspect-video lg:aspect-square bg-slate-100 rounded-[3rem] overflow-hidden shadow-2xl">
              <img src="https://images.unsplash.com/photo-1593113598332-cd288d649433?q=80&w=2070&auto=format&fit=crop" class="w-full h-full object-cover transition duration-700 group-hover:scale-110" alt="Volunteer" />
              <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
              <div class="absolute bottom-10 left-10 text-white">
                 <p class="text-2xl font-black italic uppercase tracking-tighter">"Aksi kecil, <br> dampak besar."</p>
              </div>
           </div>
        </div>
      </div>

      <!-- Registration Form -->
      <div id="register-form" class="max-w-4xl mx-auto">
        <div class="bg-white rounded-[3rem] shadow-2xl shadow-slate-200 border border-slate-100 overflow-hidden">
           <div class="p-8 md:p-12 bg-slate-900 text-white text-center space-y-2">
              <h3 class="text-2xl font-black uppercase italic tracking-tighter">Formulir <span class="text-green-500">Pendaftaran</span></h3>
              <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em]">Isi data diri Anda dengan lengkap</p>
           </div>
           
           <form @submit.prevent="handleSubmit" class="p-8 md:p-16 space-y-10">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                 <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">NAMA LENGKAP</label>
                    <input v-model="form.name" type="text" class="w-full bg-slate-50 border-b-2 border-slate-200 focus:border-green-600 outline-none p-4 font-bold text-sm transition-all" placeholder="Contoh: Ahmad Fauzi" required />
                 </div>
                 <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">ALAMAT EMAIL</label>
                    <input v-model="form.email" type="email" class="w-full bg-slate-50 border-b-2 border-slate-200 focus:border-green-600 outline-none p-4 font-bold text-sm transition-all" placeholder="ahmad@example.com" required />
                 </div>
                 <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">NOMOR TELEPON / WA</label>
                    <input v-model="form.phone" type="text" class="w-full bg-slate-50 border-b-2 border-slate-200 focus:border-green-600 outline-none p-4 font-bold text-sm transition-all" placeholder="08123456789" required />
                 </div>
                 <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">ALAMAT DOMISILI</label>
                    <input v-model="form.address" type="text" class="w-full bg-slate-50 border-b-2 border-slate-200 focus:border-green-600 outline-none p-4 font-bold text-sm transition-all" placeholder="Kota, Provinsi" required />
                 </div>
              </div>

              <div class="space-y-2">
                 <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">KEAHLIAN / SKILL (PISAH DENGAN KOMA)</label>
                 <textarea v-model="form.skills" rows="3" class="w-full bg-slate-50 border-b-2 border-slate-200 focus:border-green-600 outline-none p-4 font-bold text-sm transition-all resize-none" placeholder="Contoh: Medis, Dokumentasi, Mengajar, Logistik..." required></textarea>
              </div>

              <div class="space-y-2">
                 <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">ALASAN INGIN BERGABUNG</label>
                 <textarea v-model="form.reason" rows="4" class="w-full bg-slate-50 border-b-2 border-slate-200 focus:border-green-600 outline-none p-4 font-bold text-sm transition-all resize-none" placeholder="Ceritakan motivasi Anda..." required></textarea>
              </div>

              <div class="flex flex-col md:flex-row items-center gap-6 pt-4">
                 <button :disabled="submitting" class="w-full md:w-auto px-12 py-5 bg-green-600 text-white rounded-[2rem] font-black uppercase tracking-widest text-xs shadow-xl shadow-green-100 hover:bg-green-700 transition active:scale-95 disabled:opacity-50">
                    {{ submitting ? 'Mendaftarkan...' : 'Kirim Pendaftaran' }}
                 </button>
                 <p class="text-[10px] font-bold text-slate-400 italic">Data Anda akan ditinjau oleh Admin kami dalam 2-3 hari kerja.</p>
              </div>
           </form>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue';
import api from '@/api';

const form = reactive({
  name: '',
  email: '',
  phone: '',
  address: '',
  skills: '',
  reason: ''
});

const submitting = ref(false);

const handleSubmit = async () => {
  submitting.value = true;
  try {
    await api.post('/volunteers', form);
    alert('Terima kasih! Pendaftaran Anda telah kami terima. Tim kami akan segera menghubungi Anda.');
    // Reset form
    Object.keys(form).forEach(key => form[key] = '');
  } catch (err) {
    alert('Gagal mengirim pendaftaran. Silakan coba lagi nanti.');
  } finally {
    submitting.value = false;
  }
};
</script>
