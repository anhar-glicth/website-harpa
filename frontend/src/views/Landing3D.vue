<template>
  <div class="bg-white text-slate-900 font-sans overflow-x-hidden">

    <!-- ===== NAVBAR ===== -->
    <nav class="bg-white sticky top-0 z-[100] border-b border-gray-100 shadow-sm">
      <div class="max-w-7xl mx-auto px-6">
        <div class="flex justify-between h-16 items-center">
          <router-link to="/" class="flex items-center gap-2">
            <svg class="w-7 h-7 text-green-600" viewBox="0 0 24 24" fill="currentColor"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
            <span class="text-lg font-bold text-slate-900 tracking-tighter">Humanity<span class="text-green-600">Care</span></span>
          </router-link>
          <div class="hidden md:flex items-center gap-8">
            <router-link v-for="link in menuLinks" :key="link.label" :to="link.to" class="text-sm text-slate-500 hover:text-green-600 font-medium transition-colors">{{ link.label }}</router-link>
          </div>
          <div class="flex items-center gap-3">
            <template v-if="!isAuthenticated">
              <button @click="openLogin" class="text-sm font-semibold text-green-600 hover:text-green-700 transition-colors">Masuk</button>
              <button @click="openRegister" class="bg-green-600 text-white px-5 py-2 rounded-lg text-sm font-semibold hover:bg-green-700 transition-all shadow-md">Daftar</button>
            </template>
            <template v-else>
              <router-link to="/dashboard" class="w-9 h-9 rounded-full bg-green-100 border-2 border-green-200 flex items-center justify-center text-green-700 font-bold text-xs overflow-hidden hover:border-green-500 transition-all">
                <img v-if="getUserPhoto" :src="getUserPhoto" class="w-full h-full object-cover" />
                <span v-else>{{ getUserName?.charAt(0).toUpperCase() }}</span>
              </router-link>
            </template>
          </div>
        </div>
      </div>
    </nav>

    <!-- ===== HERO ===== -->
    <section class="max-w-7xl mx-auto px-6 py-16 md:py-24 grid lg:grid-cols-2 gap-12 items-center">
      <div class="space-y-6 text-center lg:text-left order-2 lg:order-1">
        <span class="inline-block bg-green-50 text-green-600 px-4 py-2 rounded-full text-[10px] md:text-xs font-black uppercase tracking-widest">Platform Donasi Terpercaya</span>
        <h1 class="text-4xl md:text-6xl font-black leading-[1.1] text-slate-900 tracking-tighter">
          Kebaikan nyata, <br/>
          <span class="text-green-600">perubahan untuk semua.</span>
        </h1>
        <p class="text-slate-500 text-base md:text-lg leading-relaxed max-w-lg mx-auto lg:mx-0">
          Bersama HumanityCare, setiap donasi Anda menjangkau mereka yang paling membutuhkan. Mari beraksi hari ini.
        </p>
        <div class="flex flex-col sm:flex-row justify-center lg:justify-start gap-4 pt-4">
          <router-link to="/campaigns" class="bg-green-600 text-white px-10 py-4 rounded-xl font-bold hover:bg-green-700 transition-all shadow-lg shadow-green-500/20">Donasi Sekarang</router-link>
          <router-link to="/about" class="border border-green-600 text-green-600 px-10 py-4 rounded-xl font-bold hover:bg-green-50 transition-all">Tentang Kami</router-link>
        </div>
      </div>
      <div class="flex justify-center order-1 lg:order-2">
        <img src="/hero_illustration.png" alt="Humanity Illustration" class="w-full max-w-md lg:max-w-xl object-contain drop-shadow-2xl animate-float" />
      </div>
    </section>

    <!-- ===== FEATURED CAMPAIGNS (HORIZONTAL) ===== -->
    <section class="py-20 bg-slate-50/50">
      <div class="max-w-7xl mx-auto px-6 mb-12 flex flex-col md:flex-row justify-between items-center gap-6">
        <div class="text-center md:text-left">
          <h2 class="text-3xl md:text-5xl font-black text-slate-900 tracking-tighter">Program <span class="text-green-600">Unggulan</span></h2>
          <p class="text-slate-400 mt-2">Bantu mereka melalui program pilihan kami.</p>
        </div>
        <router-link to="/campaigns" class="text-green-600 font-bold text-sm hover:underline flex items-center gap-2">
          Lihat Semua <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="m9 18 6-6-6-6"/></svg>
        </router-link>
      </div>

      <div class="flex overflow-x-auto gap-6 md:gap-8 pb-12 no-scrollbar snap-x snap-mandatory px-6 max-w-7xl mx-auto">
        <div v-for="camp in campaigns" :key="camp.title" class="snap-center flex-shrink-0 w-[300px] md:w-[380px] group bg-white rounded-[2.5rem] border border-slate-100 overflow-hidden hover:shadow-2xl hover:shadow-green-500/10 transition-all duration-500 flex flex-col h-full">
          <div class="relative h-56 bg-slate-100 overflow-hidden flex items-center justify-center text-6xl">
            <div :class="camp.color" class="absolute inset-0 opacity-10 group-hover:scale-110 transition-transform duration-700"></div>
            {{ camp.icon }}
          </div>
          <div class="p-8 flex flex-col flex-grow space-y-4">
            <h3 class="text-xl font-black text-slate-900 leading-snug group-hover:text-green-600 transition-colors">{{ camp.title }}</h3>
            <p class="text-slate-400 text-sm line-clamp-2">{{ camp.desc }}</p>
            <div class="pt-2">
              <div class="flex justify-between text-[10px] font-black uppercase tracking-widest mb-2">
                <span class="text-slate-400">Terkumpul</span>
                <span class="text-green-600">{{ camp.percent }}%</span>
              </div>
              <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                <div class="h-full bg-green-600 rounded-full transition-all duration-1000" :style="{ width: camp.percent + '%' }"></div>
              </div>
            </div>
            <button class="w-full bg-slate-900 text-white py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-green-600 transition-all mt-auto">Donasi Sekarang</button>
          </div>
        </div>
      </div>
    </section>

    <!-- ===== FEATURES ===== -->
    <section class="max-w-7xl mx-auto px-6 py-20">
      <div class="text-center mb-16">
        <h2 class="text-3xl md:text-5xl font-black text-slate-900 tracking-tighter">Kelola Kebaikan <span class="text-green-600">Satu Platform</span></h2>
        <p class="text-slate-400 mt-4 max-w-lg mx-auto">Kami menyediakan berbagai program bantuan kemanusiaan yang transparan dan akuntabel.</p>
      </div>
      <div class="grid md:grid-cols-3 gap-8">
        <div v-for="feat in features" :key="feat.title" class="bg-slate-50 rounded-[2.5rem] p-8 md:p-12 border border-gray-100 hover:shadow-md transition-all group">
          <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center text-green-600 text-3xl mb-8 group-hover:bg-green-600 group-hover:text-white transition-all">
            {{ feat.icon }}
          </div>
          <h3 class="font-black text-slate-900 text-xl mb-4">{{ feat.title }}</h3>
          <p class="text-slate-400 text-sm leading-relaxed">{{ feat.desc }}</p>
        </div>
      </div>
    </section>

    <!-- ===== ABOUT STORY ===== -->
    <section class="max-w-7xl mx-auto px-6 py-20 grid lg:grid-cols-2 gap-16 items-center">
      <div class="bg-green-50 rounded-[3rem] p-12 md:p-20 flex items-center justify-center relative overflow-hidden">
        <svg class="w-64 h-64 text-green-600 opacity-10 absolute" fill="currentColor" viewBox="0 0 24 24"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
        <div class="relative text-center z-10">
          <p class="text-6xl font-black text-green-600">3+</p>
          <p class="text-slate-600 font-bold uppercase tracking-widest mt-2">Tahun Melayani</p>
        </div>
      </div>
      <div class="space-y-6">
        <span class="text-green-600 font-black uppercase text-xs tracking-widest">Tentang Kami</span>
        <h2 class="text-3xl md:text-5xl font-black text-slate-900 leading-tight tracking-tighter">Membangun <span class="text-green-600">Kepercayaan</span> Bersama Anda.</h2>
        <p class="text-slate-500 leading-relaxed">HumanityCare adalah jembatan antara kebaikan hati Anda dan mereka yang membutuhkan bantuan darurat di seluruh dunia.</p>
        <router-link to="/about" class="inline-block bg-green-600 text-white px-8 py-4 rounded-xl font-bold hover:bg-green-700 transition-all shadow-lg shadow-green-500/20">Selengkapnya</router-link>
      </div>
    </section>

    <!-- ===== STATS ===== -->
    <section class="bg-green-600 py-20">
      <div class="max-w-7xl mx-auto px-6 grid grid-cols-2 lg:grid-cols-4 gap-12 text-center text-white">
        <div v-for="stat in stats" :key="stat.label">
          <p class="text-4xl md:text-6xl font-black mb-2 tracking-tighter">{{ stat.value }}</p>
          <p class="text-sm text-green-100 font-bold uppercase tracking-widest">{{ stat.label }}</p>
        </div>
      </div>
    </section>

    <!-- ===== HOW IT WORKS ===== -->
    <section class="max-w-7xl mx-auto px-6 py-24 grid lg:grid-cols-2 gap-16 items-center">
      <div class="space-y-6 order-2 lg:order-1">
        <span class="text-green-600 font-black uppercase text-xs tracking-widest">Cara Kerja</span>
        <h2 class="text-3xl md:text-5xl font-black text-slate-900 leading-tight tracking-tighter">Donasi Mudah, <span class="text-green-600">Dampak Nyata.</span></h2>
        <p class="text-slate-500">Alur sederhana untuk membantu sesama secara transparan.</p>
        <div class="space-y-4 pt-4">
          <div v-for="(step, i) in steps" :key="step.title" class="flex items-center gap-6 bg-slate-50 p-6 rounded-3xl border border-gray-100">
            <span class="w-10 h-10 bg-green-600 text-white rounded-xl flex items-center justify-center font-black">{{ i+1 }}</span>
            <div>
              <p class="font-black text-slate-900">{{ step.title }}</p>
              <p class="text-slate-400 text-xs mt-1">{{ step.desc }}</p>
            </div>
          </div>
        </div>
      </div>
      <div class="bg-slate-100 rounded-[3rem] h-[500px] flex items-center justify-center order-1 lg:order-2">
        <span class="text-9xl opacity-20">🔄</span>
      </div>
    </section>

    <!-- ===== TESTIMONIAL ===== -->
    <section class="bg-slate-900 py-24">
      <div class="max-w-4xl mx-auto px-6 text-center space-y-10">
        <div class="flex justify-center gap-2">
          <span v-for="n in 5" :key="n" class="text-yellow-400 text-2xl">★</span>
        </div>
        <blockquote class="text-white text-2xl md:text-4xl font-black italic leading-tight tracking-tighter">
          "Berdonasi di HumanityCare sangat transparan. Saya bisa memantau langsung bantuan saya sampai ke tangan mereka yang berhak."
        </blockquote>
        <div class="flex flex-col items-center gap-4">
          <div class="w-16 h-16 bg-green-600 rounded-full flex items-center justify-center text-white font-black text-xl">AF</div>
          <div>
            <p class="text-white font-black text-lg">Ahmad Fauzi</p>
            <p class="text-slate-500 font-bold uppercase tracking-widest text-xs">Donatur — Jakarta</p>
          </div>
        </div>
      </div>
    </section>

    <!-- ===== BLOG/NEWS ===== -->
    <section class="max-w-7xl mx-auto px-6 py-24">
      <div class="text-center mb-16">
        <h2 class="text-3xl md:text-5xl font-black text-slate-900 tracking-tighter">Berita & <span class="text-green-600">Laporan</span></h2>
        <p class="text-slate-400 mt-4">Kisah inspiratif dan laporan terbaru dari lapangan.</p>
      </div>
      <div class="grid md:grid-cols-3 gap-8">
        <div v-for="article in articles" :key="article.title" class="bg-white rounded-[2.5rem] border border-slate-100 overflow-hidden group hover:shadow-xl transition-all">
          <div :class="article.color" class="h-48 flex items-center justify-center text-4xl opacity-80">📰</div>
          <div class="p-8 space-y-3">
            <span class="text-green-600 font-black uppercase text-[10px] tracking-widest">{{ article.tag }}</span>
            <h3 class="font-black text-slate-900 group-hover:text-green-600 transition-colors">{{ article.title }}</h3>
            <router-link to="/news" class="text-green-600 text-xs font-bold hover:underline">Baca Selengkapnya →</router-link>
          </div>
        </div>
      </div>
    </section>

    <!-- ===== CTA FINAL ===== -->
    <section class="bg-green-50 py-24 border-t border-green-100">
      <div class="max-w-4xl mx-auto px-6 text-center space-y-8">
        <h2 class="text-3xl md:text-6xl font-black text-slate-900 tracking-tighter leading-none">Mulai Berbagi <span class="text-green-600">Kebaikan Hari Ini.</span></h2>
        <p class="text-slate-500 text-lg">Bergabunglah dengan ribuan donatur lainnya.</p>
        <router-link to="/campaigns" class="inline-block bg-green-600 text-white px-12 py-5 rounded-2xl font-black text-sm uppercase tracking-widest hover:bg-green-700 transition-all shadow-xl shadow-green-500/20">Donasi Sekarang</router-link>
      </div>
    </section>

    <!-- ===== FOOTER ===== -->
    <footer class="bg-slate-900 text-white py-20">
      <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-4 gap-12">
        <div class="space-y-6">
          <div class="flex items-center gap-2">
            <svg class="w-7 h-7 text-green-500" viewBox="0 0 24 24" fill="currentColor"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
            <span class="font-black text-xl italic uppercase tracking-tighter">HumanityCare</span>
          </div>
          <p class="text-slate-500 text-sm leading-relaxed">Platform donasi digital tepercaya untuk kemanusiaan global.</p>
        </div>
        <div v-for="col in footerLinks" :key="col.title">
          <p class="font-black text-white text-xs uppercase tracking-widest mb-6">{{ col.title }}</p>
          <ul class="space-y-3">
            <li v-for="link in col.links" :key="link.label">
              <router-link :to="link.to" class="text-slate-400 hover:text-green-500 transition-colors text-sm font-medium">{{ link.label }}</router-link>
            </li>
          </ul>
        </div>
      </div>
      <div class="max-w-7xl mx-auto px-6 mt-20 pt-8 border-t border-slate-800 text-center text-slate-600 text-[10px] font-bold uppercase tracking-[0.3em]">
        © 2026 HumanityCare. Berdonasi dengan hati, membantu dengan bukti.
      </div>
    </footer>

  </div>
</template>

<script setup>
import { useAuth } from '../store/auth';
const { isAuthenticated, getUserName, getUserPhoto, openLogin, openRegister } = useAuth();

const menuLinks = [
  { label: 'Beranda', to: '/' },
  { label: 'Berita', to: '/news' },
  { label: 'Program', to: '/campaigns' },
  { label: 'Zakat', to: '/zakat' },
  { label: 'Tentang Kami', to: '/about' },
];

const features = [
  { icon: '❤️', title: 'Donasi Program', desc: 'Pilih dari puluhan program kemanusiaan terverifikasi.' },
  { icon: '🕌', title: 'Zakat & Infaq', desc: 'Hitung dan bayar zakat otomatis sesuai syariat.' },
  { icon: '🌍', title: 'Bantuan Darurat', desc: 'Respon cepat untuk bencana dan konflik global.' },
];

const campaigns = [
  { id: 1, title: 'Darurat Pangan Gaza', desc: 'Paket sembako untuk keluarga pengungsi.', color: 'bg-green-500', percent: 75, icon: '📦' },
  { id: 2, title: 'Klinik Mobile Tepi Barat', desc: 'Layanan medis gratis warga terpencil.', color: 'bg-emerald-500', percent: 42, icon: '🏥' },
  { id: 3, title: 'Beasiswa Anak Syuhada', desc: 'Menjamin pendidikan anak yatim konflik.', color: 'bg-green-600', percent: 90, icon: '🎓' },
];

const stats = [
  { value: '2.3 Jt', label: 'Donatur' },
  { value: 'Rp 48 M', label: 'Tersalur' },
  { value: '820 Rb', label: 'Penerima' },
  { value: '1.9k+', label: 'Program' },
];

const steps = [
  { title: 'Pilih Program', desc: 'Cari bantuan yang sesuai niat Anda.' },
  { title: 'Donasi', desc: 'Berapapun sangat berarti bagi mereka.' },
  { title: 'Laporan', desc: 'Dapatkan bukti penyaluran transparan.' },
];

const articles = [
  { tag: 'Update', title: 'Penyaluran Bantuan Gaza Tahap III', color: 'bg-green-600' },
  { tag: 'Laporan', title: 'Transparansi Dana Zakat Fitrah 2026', color: 'bg-emerald-600' },
  { tag: 'Cerita', title: 'Sekolah Kembali Dibuka di Rafah', color: 'bg-green-500' },
];

const footerLinks = [
  { title: 'Program', links: [{ label: 'Palestina', to: '/' }, { label: 'Bencana', to: '/' }, { label: 'Yatim', to: '/' }] },
  { title: 'Bantuan', links: [{ label: 'Cara Donasi', to: '/' }, { label: 'Zakat', to: '/' }, { label: 'Kontak', to: '/' }] },
  { title: 'Legal', links: [{ label: 'Syarat Ketentuan', to: '/' }, { label: 'Kebijakan Privasi', to: '/' }] },
];
</script>

<style>
.no-scrollbar::-webkit-scrollbar { display: none; }
.no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
@keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-20px); } }
.animate-float { animation: float 6s ease-in-out infinite; }
</style>
