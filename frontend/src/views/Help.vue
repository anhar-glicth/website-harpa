<template>
  <div class="min-h-screen bg-slate-50">
    <div class="max-w-4xl mx-auto px-4 py-24 space-y-16">
      <header class="text-center space-y-6">
        <h1 class="text-6xl font-black text-slate-900 tracking-tighter uppercase leading-none">Bantuan <span class="text-green-600">Terbuka</span></h1>
        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.4em]">Panduan & Informasi Platform Amal Abadi</p>
      </header>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
        <!-- FAQ Section -->
        <div class="bg-white rounded-[3rem] p-10 shadow-xl border border-slate-100 space-y-10 order-2 md:order-1">
          <div class="flex items-center gap-4 text-green-600 mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h2 class="text-2xl font-black uppercase tracking-tight">KONTEN BANTUAN</h2>
          </div>

          <div class="space-y-8">
            <div class="group cursor-pointer">
              <h4 class="font-black text-slate-800 text-sm mb-2 group-hover:text-green-600 transition-colors">BAGAIMANA CARA BERDONASI?</h4>
              <p class="text-xs text-slate-400 leading-relaxed font-bold">Pilih kampanye aktif yang Anda sukai, klik tombol donasi, masukkan nominal, lalu unggah bukti transfer ke rekening yang tertera.</p>
            </div>
            <div class="group cursor-pointer">
              <h4 class="font-black text-slate-800 text-sm mb-2 group-hover:text-green-600 transition-colors">APAKAH DONASI SAYA AMAN?</h4>
              <p class="text-xs text-slate-400 leading-relaxed font-bold">Setiap transaksi diverifikasi secara manual oleh Admin kami untuk memastikan integritas penyaluran dana.</p>
            </div>
            <div class="group cursor-pointer">
              <h4 class="font-black text-slate-800 text-sm mb-2 group-hover:text-green-600 transition-colors">BAGAIMANA CARA MENGAJUAKAN KAMPANYE?</h4>
              <p class="text-xs text-slate-400 leading-relaxed font-bold">Daftar sebagai Petugas Lapangan, lengkapi profil Anda, lalu kirimkan rencana kampanye melalui Panel Petugas.</p>
            </div>
          </div>
        </div>

        <!-- Contact Support -->
        <div class="bg-green-600 rounded-[3rem] p-10 text-white shadow-2xl shadow-green-200 space-y-8 flex flex-col items-center text-center order-1 md:order-2">
            <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mb-4 animate-pulse">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
              </svg>
            </div>
            <h2 class="text-3xl font-black leading-none mb-4">MASIH BUTUH BANTUAN?</h2>
            <p class="text-[10px] uppercase font-black tracking-widest text-green-200">Tim kami siap membantu Anda 24/7 untuk segala pertanyaan teknis maupun non-teknis melalui WhatsApp resmi.</p>
            <a href="https://wa.me/628123456789" class="w-full py-5 bg-white text-green-600 rounded-2xl font-black uppercase text-xs tracking-widest shadow-xl shadow-green-900/20 hover:scale-[1.02] transition-transform">Hubungi Support Sekarang</a>
        </div>
      </div>
    </div>
  </div>
</template>
