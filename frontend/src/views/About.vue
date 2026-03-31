<template>
  <div class="min-h-screen bg-white">
    <div class="max-w-4xl mx-auto px-4 py-24 space-y-16">
      <!-- Header -->
      <header class="text-center space-y-4">
        <h1 class="text-5xl font-black text-gray-900 tracking-tighter uppercase">Tentang <span class="text-blue-600">HumanityCare</span></h1>
        <p class="text-xl text-gray-400 font-medium max-w-2xl mx-auto">Membangun jembatan kebaikan antara mereka yang ingin membantu dan mereka yang membutuhkan bantuan.</p>
      </header>

      <!-- Content -->
      <section class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
        <div class="rounded-[3rem] overflow-hidden shadow-2xl rotate-2">
          <img src="https://images.unsplash.com/photo-1469571486292-0ba58a3f068b?q=80&w=2070&auto=format&fit=crop" class="w-full h-full object-cover" alt="Mission" />
        </div>
        <div class="space-y-6">
          <h2 class="text-3xl font-black text-gray-800 tracking-tight">Misi Kami</h2>
          <p class="text-gray-500 leading-relaxed">HumanityCare adalah platform donasi digital yang berfokus pada transparansi dan kemudahan akses. Kami percaya bahwa setiap bantuan, sekecil apapun, memiliki dampak besar jika disalurkan kepada orang yang tepat dengan cara yang benar.</p>
          <div class="space-y-4">
            <div class="flex items-center gap-4 p-4 bg-blue-50 rounded-2xl border border-blue-100">
              <div class="w-10 h-10 bg-blue-600 text-white rounded-xl flex items-center justify-center font-bold">1</div>
              <p class="font-bold text-blue-900 italic text-sm">Verifikasi Ketat Setiap Kampanye.</p>
            </div>
            <div class="flex items-center gap-4 p-4 bg-blue-50 rounded-2xl border border-blue-100">
              <div class="w-10 h-10 bg-blue-600 text-white rounded-xl flex items-center justify-center font-bold">2</div>
              <p class="font-bold text-blue-900 italic text-sm">Transparansi Penyaluran Dana.</p>
            </div>
          </div>
        </div>
      </section>

      <!-- Values -->
      <section class="bg-gray-900 rounded-[4rem] p-12 md:p-20 text-white text-center space-y-12 shadow-2xl relative overflow-hidden">
        <div class="absolute -top-20 -left-20 w-64 h-64 bg-blue-600/20 rounded-full blur-3xl"></div>
        <div class="relative z-10">
          <h2 class="text-4xl font-black tracking-tighter mb-12">NILAI-NILAI UTAMA</h2>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
            <div class="space-y-4">
              <div class="text-4xl text-blue-400 font-black">🔥</div>
              <p class="font-black text-xs uppercase tracking-widest text-blue-400">Integritas</p>
              <p class="text-sm text-gray-400 opacity-80 italic">Kejujuran adalah pondasi dari semua kepercayaan donatur kami.</p>
            </div>
            <div class="space-y-4">
              <div class="text-4xl text-blue-400 font-black">⚡</div>
              <p class="font-black text-xs uppercase tracking-widest text-blue-400">Responsif</p>
              <p class="text-sm text-gray-400 opacity-80 italic">Bantuan harus dikirimkan secepat mungkin dalam situasi mendesak.</p>
            </div>
            <div class="space-y-4">
              <div class="text-4xl text-amber-400 font-black">🤝</div>
              <p class="font-black text-xs uppercase tracking-widest text-amber-400">Solidaritas</p>
              <p class="text-sm text-gray-400 opacity-80 italic">Kita semua adalah satu keluarga kemanusiaan global.</p>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>
</template>
