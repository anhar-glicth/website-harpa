<template>
  <div class="min-h-screen bg-white">
    <!-- Hero Header -->
    <header class="bg-blue-600 text-white pt-32 pb-24 px-6 md:px-10 text-center relative overflow-hidden">
      <div class="absolute -right-20 -top-20 w-80 h-80 bg-blue-500 rounded-full opacity-20 blur-3xl animate-pulse"></div>
      <div class="max-w-4xl mx-auto space-y-6 relative z-10">
        <div class="inline-flex py-1 px-4 bg-white/10 rounded-full text-[9px] font-black uppercase tracking-widest border border-white/20">Warta HumanityCare</div>
        <h1 class="text-4xl md:text-6xl font-black uppercase tracking-tighter leading-none italic">
          Kabar <span class="text-blue-200">Terhangat</span> <br> <span class="text-white/80">Kebaikan Kita</span>
        </h1>
        <p class="text-sm md:text-base font-bold text-blue-100 max-w-lg mx-auto leading-relaxed">Ikuti perkembangan terbaru dan laporan penyaluran titipan Anda dari seluruh pelosok negeri.</p>
      </div>
    </header>

    <!-- News Grid -->
    <div class="max-w-7xl mx-auto px-6 py-20">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
        <article 
          v-for="post in newsList" 
          :key="post.id" 
          class="group cursor-pointer space-y-5 transform transition-all duration-500 hover:-translate-y-4"
        >
          <div class="relative h-60 md:h-72 overflow-hidden rounded-[2.5rem] shadow-xl group-hover:shadow-blue-100">
            <img :src="post.image" :alt="post.title" class="w-full h-full object-cover transition duration-700 group-hover:scale-110">
            <div class="absolute top-6 left-6 p-1 bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl flex items-center gap-2 group-hover:bg-blue-600 group-hover:border-blue-500 transition duration-500">
              <div class="p-2.5 bg-white rounded-xl text-blue-600 shadow-sm transition group-hover:rotate-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M12 2v20"/><path d="m17 7-5-5-5 5"/><path d="m17 17-5 5-5-5"/></svg>
              </div>
              <span class="text-[9px] font-black uppercase tracking-wider text-white pr-4 italic">Update Berita</span>
            </div>
          </div>

          <div class="px-4 space-y-4">
            <div class="flex items-center gap-4">
               <span class="text-[9px] font-black uppercase tracking-widest text-blue-600 underline decoration-2 underline-offset-4 decoration-blue-200">{{ post.category }}</span>
               <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">{{ post.date }}</span>
            </div>
            <h3 class="text-xl font-black text-slate-900 leading-tight group-hover:text-blue-600 transition-colors uppercase tracking-tight line-clamp-2 italic">
               {{ post.title }}
            </h3>
            <p class="text-slate-500 text-xs font-bold leading-relaxed line-clamp-3 opacity-70">
               {{ post.excerpt }}
            </p>
          </div>
        </article>
      </div>

      <!-- Load More Button -->
      <div class="mt-20 text-center">
         <button class="px-10 py-5 bg-white border-4 border-slate-100 text-slate-900 rounded-[2rem] font-black uppercase tracking-[0.2em] text-[10px] hover:border-blue-600 hover:text-blue-600 transition duration-300">Muat Berita Lainnya</button>
      </div>
    </div>
  </div>
</template>

<script setup>
const newsList = [
  {
    id: 1,
    title: 'Penyaluran Wakaf Al-Quran ke Pelosok Papua Berjalan Lancar bersama Tim HumanityCare',
    category: 'Laporan Penyaluran',
    date: '31 Maret 2026',
    image: 'https://images.unsplash.com/photo-1542810634-71277d95dcbb?q=80&w=2070&auto=format&fit=crop',
    excerpt: 'Tim lapangan HumanityCare berhasil menyalurkan ribuan mushaf Al-Quran ke titik-titik terjauh di wilayah Papua untuk mendukung program buta aksara hijaiyah.'
  },
  {
    id: 2,
    title: 'Aksi Tanggap Bencana: Bantuan Pangan untuk Korban Banjir Bandang di Sumatera Barat',
    category: 'Tanggap Bencana',
    date: '30 Maret 2026',
    image: 'https://images.unsplash.com/photo-1469571486292-0ba58a3f068b?q=80&w=2070&auto=format&fit=crop',
    excerpt: 'Kebutuhan pokok dan obat-obatan mulai disalurkan ke posko-posko pengungsian di wilayah terdampak banjir bandang hari ini.'
  },
  {
    id: 3,
    title: 'Kisah Mualaf Baru: Hidayah di Balik Program Pemberdayaan Ekonomi Masyarakat',
    category: 'Mualaf Center',
    date: '28 Maret 2026',
    image: 'https://images.unsplash.com/photo-1551884107-2475251fa23b?q=80&w=2070&auto=format&fit=crop',
    excerpt: 'Melalui program pelatihan ekonomi, seorang warga akhirnya menemukan ketenangan dalam Islam dan melakukan proses syahadat di pusat layanan kami.'
  }
];
</script>
