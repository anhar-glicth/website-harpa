<template>
  <div class="min-h-screen bg-gray-50 font-sans text-gray-900">
    <!-- Header -->
    <header class="bg-white border-b sticky top-0 z-10 shadow-sm">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
        <div class="flex items-center space-x-3 text-blue-600">
          <div class="bg-blue-600 p-1.5 rounded-lg text-white">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"/><path d="m9 12 2 2 4-4"/></svg>
          </div>
          <div class="flex flex-col leading-none">
            <span class="font-black text-xl tracking-tighter text-gray-900">HUMANITY<span class="text-blue-600">CORE</span></span>
            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Admin Control Center</span>
          </div>
        </div>
        
        <div class="flex items-center space-x-4">
          <div class="hidden md:flex flex-col items-end mr-2">
            <span class="text-sm font-bold text-gray-800">{{ adminName || 'Administrator' }}</span>
            <span class="text-[10px] text-green-500 font-bold uppercase tracking-tighter flex items-center">
              <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1 animate-pulse"></span> Sistem Aktif
            </span>
          </div>
          <button 
            @click="showAddModal = true"
            class="group flex items-center space-x-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl transition-all shadow-lg shadow-blue-200 active:scale-95"
          >
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="group-hover:rotate-12 transition-transform"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/></svg>
            <span class="text-sm font-bold">Tambah Ahli Pasukan</span>
          </button>
        </div>
      </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Perhatian Keselamatan / Ralat API -->
      <div v-if="errorMsg" class="mb-8 p-5 bg-white border border-red-100 rounded-2xl shadow-sm animate-in fade-in duration-500">
        <div class="flex items-start space-x-4">
          <div class="p-3 bg-red-100 text-red-600 rounded-xl">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
          </div>
          <div class="flex-grow">
            <h3 class="font-black text-red-800 uppercase text-xs tracking-widest mb-1">Kegagalan Sinkronisasi API (Status: {{ errorCode || 'Offline' }})</h3>
            <p class="text-sm text-red-600 font-medium leading-relaxed">{{ errorMsg }}</p>
            <div class="mt-4 flex flex-wrap gap-2">
              <div class="bg-red-50 px-3 py-1.5 rounded-lg border border-red-100 text-[10px] font-mono text-red-500">
                Laluan: {{ lastAttemptedUrl }}
              </div>
              <button @click="fetchData" class="bg-red-600 text-white text-[10px] font-bold px-3 py-1.5 rounded-lg hover:bg-red-700 transition-colors uppercase tracking-widest">
                Cuba Semula Sekarang
              </button>
            </div>
          </div>
          <button @click="errorMsg = ''" class="text-gray-300 hover:text-gray-500 transition-colors">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
          </button>
        </div>
      </div>

      <!-- Navigation Tabs (More Modern) -->
      <div class="flex items-center justify-between mb-8">
        <div class="flex space-x-1 bg-gray-200 p-1.5 rounded-2xl">
          <button
            v-for="tab in tabs"
            :key="tab.id"
            @click="activeTab = tab.id"
            :class="[
              'flex items-center space-x-2 px-6 py-2.5 rounded-xl text-sm font-bold transition-all',
              activeTab === tab.id ? 'bg-white text-blue-600 shadow-lg shadow-gray-200' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-300/30'
            ]"
          >
            <component :is="tab.icon" class="w-4 h-4" />
            <span>{{ tab.label }}</span>
          </button>
        </div>
        <div class="hidden lg:block text-right">
          <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Kemas Kini Terakhir</p>
          <p class="text-xs font-bold text-gray-600">{{ currentTime }}</p>
        </div>
      </div>

      <!-- Content Loader -->
      <div v-if="loading" class="flex flex-col items-center justify-center h-80 bg-white rounded-3xl border border-dashed border-gray-200">
        <div class="relative flex items-center justify-center mb-4">
          <div class="absolute w-12 h-12 border-4 border-blue-100 rounded-full"></div>
          <div class="w-12 h-12 border-4 border-t-blue-600 rounded-full animate-spin"></div>
        </div>
        <p class="text-gray-400 font-bold text-sm uppercase tracking-widest animate-pulse">Menghubungkan ke Pangkalan Data...</p>
      </div>

      <div v-else class="space-y-10">
        <!-- Dashboard Tab -->
        <div v-if="activeTab === 'dashboard'" class="animate-in fade-in slide-in-from-bottom-4 duration-500">
          <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-10">
            <!-- Stat 1: Total Donasi -->
            <div class="group bg-white p-7 rounded-[2rem] shadow-sm border border-gray-100 hover:shadow-xl hover:shadow-blue-100 transition-all duration-300">
              <div class="flex items-start justify-between mb-6">
                <div class="p-4 rounded-2xl bg-emerald-500 text-white shadow-lg shadow-emerald-200">
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>
                </div>
                <span class="text-[10px] font-black text-emerald-500 bg-emerald-50 px-2 py-1 rounded-lg uppercase">+12.5%</span>
              </div>
              <p class="text-xs text-gray-400 font-black uppercase tracking-widest mb-1">Kutipan Keseluruhan</p>
              <p class="text-3xl font-black text-gray-900 leading-tight">Rp {{ stats.totalDonations ? stats.totalDonations.toLocaleString() : '0' }}</p>
            </div>

            <!-- Stat 2: Donatur -->
            <div class="group bg-white p-7 rounded-[2rem] shadow-sm border border-gray-100 hover:shadow-xl hover:shadow-rose-100 transition-all duration-300">
              <div class="flex items-start justify-between mb-6">
                <div class="p-4 rounded-2xl bg-rose-500 text-white shadow-lg shadow-rose-200">
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
                </div>
                <span class="text-[10px] font-black text-rose-500 bg-rose-50 px-2 py-1 rounded-lg uppercase">Aktif</span>
              </div>
              <p class="text-xs text-gray-400 font-black uppercase tracking-widest mb-1">Jumlah Penderma</p>
              <p class="text-3xl font-black text-gray-900 leading-tight">{{ stats.totalDonors || 0 }} <span class="text-xs text-gray-300">Orang</span></p>
            </div>

            <!-- Stat 3: Kempen -->
            <div class="group bg-white p-7 rounded-[2rem] shadow-sm border border-gray-100 hover:shadow-xl hover:shadow-amber-100 transition-all duration-300">
              <div class="flex items-start justify-between mb-6">
                <div class="p-4 rounded-2xl bg-amber-500 text-white shadow-lg shadow-amber-200">
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="15" rx="1"/></svg>
                </div>
                <span class="text-[10px] font-black text-amber-500 bg-amber-50 px-2 py-1 rounded-lg uppercase">Live</span>
              </div>
              <p class="text-xs text-gray-400 font-black uppercase tracking-widest mb-1">Misi Kemanusiaan</p>
              <p class="text-3xl font-black text-gray-900 leading-tight">{{ stats.activeCampaigns || 0 }} <span class="text-xs text-gray-300">Projek</span></p>
            </div>
          </div>
          
          <div class="relative bg-gradient-to-br from-blue-600 to-indigo-700 p-10 rounded-[2.5rem] shadow-2xl shadow-blue-200 overflow-hidden">
            <div class="relative z-10">
              <h2 class="text-3xl font-black text-white mb-4 leading-tight">Laporan Strategik Platform</h2>
              <p class="text-blue-100 max-w-xl font-medium leading-relaxed opacity-90">
                Sistem secara automatik menguruskan aliran dana daripada {{ stats.totalDonors }} penderma. 
                Prestasi semasa menunjukkan kestabilan tinggi dengan purata ketelusan pangkalan data 100%.
              </p>
              <div class="mt-8 flex gap-4">
                <button @click="activeTab = 'team'" class="bg-white text-blue-600 px-6 py-3 rounded-2xl font-black text-sm hover:bg-blue-50 transition-all shadow-xl">Urus Pasukan</button>
                <button @click="fetchData" class="bg-blue-500/30 text-white border border-blue-400/30 backdrop-blur-md px-6 py-3 rounded-2xl font-black text-sm hover:bg-blue-500/50 transition-all">Segarkan Data</button>
              </div>
            </div>
            <!-- Decorative SVG circles -->
            <div class="absolute top-[-20%] right-[-10%] w-80 h-80 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-[-20%] left-[-5%] w-60 h-60 bg-blue-400/20 rounded-full blur-2xl"></div>
          </div>
        </div>

        <!-- Team Table (Pasukan) -->
        <div v-if="activeTab === 'team'" class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden animate-in fade-in slide-in-from-right-4 duration-500">
          <div class="p-8 border-b border-gray-50 flex justify-between items-center">
            <div>
              <h2 class="text-xl font-black text-gray-800 uppercase tracking-tighter">Pasukan Lapangan</h2>
              <p class="text-xs font-bold text-gray-400">Pengurusan peranan Admin & Petugas Lapangan (Field Workers)</p>
            </div>
            <div class="flex items-center space-x-3">
              <span class="bg-blue-50 text-blue-600 px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest">
                {{ teamList.length }} Staff
              </span>
            </div>
          </div>
          <div class="overflow-x-auto">
            <table class="w-full text-left">
              <thead class="bg-gray-50 text-gray-400 text-[10px] uppercase font-black tracking-[0.15em]">
                <tr>
                  <th class="px-8 py-5">Nama Ahli</th>
                  <th class="px-8 py-5">Identiti / Email</th>
                  <th class="px-8 py-5">Peranan Akses</th>
                  <th class="px-8 py-5 text-center">Tindakan Keselamatan</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-50">
                <tr v-for="user in teamList" :key="user.id" class="group hover:bg-blue-50/30 transition-all">
                  <td class="px-8 py-6">
                    <div class="flex items-center space-x-3">
                      <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center font-black text-gray-400 group-hover:bg-blue-600 group-hover:text-white transition-all">
                        {{ user.name.charAt(0).toUpperCase() }}
                      </div>
                      <span class="font-bold text-gray-800 tracking-tight">{{ user.name }}</span>
                    </div>
                  </td>
                  <td class="px-8 py-6 font-medium text-gray-500">{{ user.email }}</td>
                  <td class="px-8 py-6 text-sm">
                    <span :class="[
                      'px-4 py-1.5 rounded-xl text-[10px] font-black tracking-widest uppercase',
                      user.role === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700'
                    ]">
                      {{ user.role === 'field_worker' ? 'field_worker' : user.role }}
                    </span>
                  </td>
                  <td class="px-8 py-6 text-center">
                    <button 
                      @click="handleDelete(user.id)"
                      class="text-gray-300 hover:text-red-600 p-3 rounded-2xl hover:bg-red-50 transition-all active:scale-90"
                      title="Padam Akaun Secara Kekal"
                    >
                      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                    </button>
                  </td>
                </tr>
                <tr v-if="teamList.length === 0">
                  <td colspan="4" class="px-8 py-20 text-center">
                    <div class="flex flex-col items-center">
                      <div class="p-5 bg-gray-100 rounded-full text-gray-300 mb-4">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-3-3.87"/><path d="M9 21v-2a4 4 0 0 0-3-3.87"/><circle cx="12" cy="7" r="4"/></svg>
                      </div>
                      <p class="text-gray-400 font-bold uppercase tracking-widest text-xs">Pangkalan Data Pasukan Kosong</p>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Donors Tab -->
        <div v-if="activeTab === 'donors'" class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden animate-in fade-in slide-in-from-left-4 duration-500">
          <div class="p-8 border-b border-gray-50 bg-rose-50/10">
            <h2 class="text-xl font-black text-rose-600 uppercase tracking-tighter">Pangkalan Data Donatur</h2>
            <p class="text-xs font-bold text-gray-400">Senarai penderma aktif yang menyokong misi kemanusiaan HumanityCare.</p>
          </div>
          <div class="overflow-x-auto">
            <table class="w-full text-left">
              <thead class="bg-gray-50 text-gray-400 text-[10px] uppercase font-black tracking-[0.15em]">
                <tr>
                  <th class="px-8 py-5">Nama Profil</th>
                  <th class="px-8 py-5">Alamat Komunikasi</th>
                  <th class="px-8 py-5">Status Akaun</th>
                  <th class="px-8 py-5 text-center">Aksi</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-50">
                <tr v-for="user in donorList" :key="user.id" class="hover:bg-rose-50/20 transition-all">
                  <td class="px-8 py-6">
                    <p class="font-black text-gray-800 tracking-tight">{{ user.name }}</p>
                    <p class="text-[10px] text-gray-400 font-medium">ID: #{{ user.id }}</p>
                  </td>
                  <td class="px-8 py-6 font-bold text-gray-500">{{ user.email }}</td>
                  <td class="px-8 py-6">
                    <div class="flex items-center space-x-2">
                      <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                      <span class="text-[10px] font-black text-green-600 tracking-widest">AKTIF</span>
                    </div>
                  </td>
                  <td class="px-8 py-6 text-center">
                    <button @click="handleDelete(user.id)" class="text-gray-300 hover:text-red-500 transition-colors p-2">
                      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/></svg>
                    </button>
                  </td>
                </tr>
                <tr v-if="donorList.length === 0">
                  <td colspan="4" class="px-8 py-20 text-center text-gray-400 font-bold uppercase tracking-widest text-xs italic">
                    Belum ada donatur yang berdaftar dalam sistem.
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Verify Campaigns Tab -->
        <div v-if="activeTab === 'verify_campaign'" class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden animate-in fade-in slide-in-from-top-4 duration-500">
          <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-blue-50/10">
            <div>
              <h2 class="text-xl font-black text-blue-600 uppercase tracking-tighter">Approval Kampanye</h2>
              <p class="text-xs font-bold text-gray-400">Kampanye yang diajukan oleh petugas lapangan dan butuh verifikasi.</p>
            </div>
            <span class="bg-blue-600 text-white px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest">
              {{ pendingCampaigns.length }} Menunggu
            </span>
          </div>
          <div class="overflow-x-auto">
            <table class="w-full text-left">
              <thead class="bg-gray-50 text-gray-400 text-[10px] uppercase font-black tracking-[0.15em]">
                <tr>
                  <th class="px-8 py-5">Judul Kampanye</th>
                  <th class="px-8 py-5">Kategori</th>
                  <th class="px-8 py-5">Target</th>
                  <th class="px-8 py-5 text-center">Aksi</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-50">
                <tr v-for="cam in pendingCampaigns" :key="cam.id" class="hover:bg-blue-50/20 transition-all">
                  <td class="px-8 py-6">
                    <div class="flex items-center space-x-3">
                      <div class="w-12 h-12 rounded-xl overflow-hidden bg-gray-100">
                        <img :src="cam.foto" class="w-full h-full object-cover" />
                      </div>
                      <span class="font-bold text-gray-800">{{ cam.title }}</span>
                    </div>
                  </td>
                  <td class="px-8 py-6 text-sm font-bold text-gray-500 uppercase">{{ cam.category }}</td>
                  <td class="px-8 py-6 font-black text-blue-600">Rp {{ cam.target_amount.toLocaleString() }}</td>
                  <td class="px-8 py-6 text-center">
                    <button @click="handleApproveCampaign(cam.id)" class="bg-blue-600 text-white px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-blue-700 transition-all shadow-lg shadow-blue-100">
                      Setujui
                    </button>
                  </td>
                </tr>
                <tr v-if="pendingCampaigns.length === 0">
                  <td colspan="4" class="px-8 py-20 text-center text-gray-400 font-bold uppercase tracking-widest text-xs italic">
                    Semua kampanye telah diproses.
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Verify Donations Tab -->
        <div v-if="activeTab === 'verify_donation'" class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden animate-in fade-in slide-in-from-bottom-4 duration-500">
          <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-emerald-50/10">
            <div>
              <h2 class="text-xl font-black text-emerald-600 uppercase tracking-tighter">Verifikasi Donasi</h2>
              <p class="text-xs font-bold text-gray-400">Verifikasi bukti transfer yang diunggah oleh donatur.</p>
            </div>
            <span class="bg-emerald-600 text-white px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest">
              {{ pendingDonations.length }} Donasi
            </span>
          </div>
          <div class="overflow-x-auto">
            <table class="w-full text-left">
              <thead class="bg-gray-50 text-gray-400 text-[10px] uppercase font-black tracking-[0.15em]">
                <tr>
                  <th class="px-8 py-5">Donatur & Kampanye</th>
                  <th class="px-8 py-5">Nominal</th>
                  <th class="px-8 py-5">Bukti Transfer</th>
                  <th class="px-8 py-5 text-center">Keputusan</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-50">
                <tr v-for="don in pendingDonations" :key="don.id" class="hover:bg-emerald-50/20 transition-all">
                  <td class="px-8 py-6">
                    <p class="font-black text-gray-800 tracking-tight">{{ don.donor_name }}</p>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{{ don.campaign_title }}</p>
                  </td>
                  <td class="px-8 py-6 font-black text-emerald-600">Rp {{ don.amount.toLocaleString() }}</td>
                  <td class="px-8 py-6">
                    <a :href="don.proof_image" target="_blank" class="flex items-center space-x-2 text-blue-600 font-bold text-xs uppercase tracking-widest hover:underline">
                      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M15 3h6v6"/><path d="M10 14L21 3"/><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/></svg>
                      <span>Lihat Bukti</span>
                    </a>
                  </td>
                  <td class="px-8 py-6">
                    <div class="flex items-center justify-center space-x-2">
                      <button @click="handleVerifyDonation(don.id, 'Success')" class="bg-emerald-600 text-white px-3 py-2 rounded-lg text-xs font-black uppercase tracking-widest shadow-lg shadow-emerald-100 hover:bg-emerald-700">Terima</button>
                      <button @click="handleVerifyDonation(don.id, 'Failed')" class="bg-red-500 text-white px-3 py-2 rounded-lg text-xs font-black uppercase tracking-widest shadow-lg shadow-red-100 hover:bg-red-600">Tolak</button>
                    </div>
                  </td>
                </tr>
                <tr v-if="pendingDonations.length === 0">
                  <td colspan="4" class="px-8 py-20 text-center text-gray-400 font-bold uppercase tracking-widest text-xs italic">
                    Belum ada donasi baru untuk diverifikasi.
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </main>

    <!-- Modal Form: Tambah Akaun Baru -->
    <div v-if="showAddModal" class="fixed inset-0 bg-gray-900/60 backdrop-blur-md flex items-center justify-center p-4 z-50 animate-in fade-in duration-300">
      <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-md overflow-hidden transform animate-in slide-in-from-bottom-10 duration-500">
        <div class="p-8 border-b flex justify-between items-center bg-blue-600 text-white">
          <div>
            <h3 class="text-xl font-black tracking-tighter uppercase leading-none">Pendaftaran Ahli</h3>
            <p class="text-xs text-blue-200 font-bold mt-1">Cipta kredensial akses pasukan baru</p>
          </div>
          <button @click="showAddModal = false" class="bg-white/10 hover:bg-white/20 p-2 rounded-2xl transition-all">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
          </button>
        </div>
        
        <form @submit.prevent="handleAddUser" class="p-8 space-y-6">
          <div v-if="message.text" :class="[
            'p-4 rounded-2xl text-xs font-black uppercase tracking-widest border-l-4',
            message.type === 'success' ? 'bg-green-50 text-green-700 border-green-500' : 'bg-red-50 text-red-700 border-red-500'
          ]">
            {{ message.text }}
          </div>
          
          <div class="space-y-4">
            <div>
              <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2 ml-1">Identiti Nama</label>
              <input v-model="formData.name" placeholder="E.g. Khairul Nizam" class="w-full bg-gray-50 border-none p-4 rounded-2xl focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold text-gray-700" required />
            </div>
            
            <div>
              <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2 ml-1">Alamat Email</label>
              <input v-model="formData.email" type="email" placeholder="staff@humanity.org" class="w-full bg-gray-50 border-none p-4 rounded-2xl focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold text-gray-700" required />
            </div>
            
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2 ml-1">Kata Laluan</label>
                <input v-model="formData.password" type="password" placeholder="Min. 6" class="w-full bg-gray-50 border-none p-4 rounded-2xl focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold text-gray-700" required />
              </div>
              <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2 ml-1">Role Akses</label>
                <select v-model="formData.role" class="w-full bg-gray-50 border-none p-4 rounded-2xl focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold text-gray-700 appearance-none">
                  <option value="field_worker">Petugas Lapangan</option>
                  <option value="admin">Administrator</option>
                </select>
              </div>
            </div>
          </div>

          <button 
            type="submit" 
            :disabled="formLoading" 
            class="w-full bg-blue-600 text-white font-black py-4 rounded-[1.5rem] hover:bg-blue-700 transition-all shadow-xl shadow-blue-200 active:scale-95 disabled:opacity-50 flex justify-center items-center space-x-2"
          >
            <svg v-if="formLoading" class="animate-spin h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>
            <span class="uppercase tracking-widest text-xs">{{ formLoading ? 'Menjana Akaun...' : 'Sahkan & Daftar Akaun' }}</span>
          </button>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted, h, computed } from 'vue';
import api from '@/api';

/**
 * PENTING: Menggunakan api instance dari @/api/index.js
 */
const adminName = localStorage.getItem('name');

// Icons SVG Internal (Lucide-inspired)
const LayoutIcon = () => h('svg', { width: 16, height: 16, viewBox: '0 0 24 24', fill: 'none', stroke: 'currentColor', 'stroke-width': 2.5 }, [h('rect', { width: 7, height: 9, x: 3, y: 3, rx: 1 }), h('rect', { width: 7, height: 5, x: 14, y: 3, rx: 1 }), h('rect', { width: 7, height: 9, x: 14, y: 12, rx: 1 }), h('rect', { width: 7, height: 5, x: 3, y: 15, rx: 1 })]);
const UsersIcon = () => h('svg', { width: 16, height: 16, viewBox: '0 0 24 24', fill: 'none', stroke: 'currentColor', 'stroke-width': 2.5 }, [h('path', { d: 'M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2' }), h('circle', { cx: 9, cy: 7, r: 4 }), h('path', { d: 'M22 21v-2a4 4 0 0 0-3-3.87' }), h('path', { d: 'M16 3.13a4 4 0 0 1 0 7.75' })]);
const HeartIcon = () => h('svg', { width: 16, height: 16, viewBox: '0 0 24 24', fill: 'none', stroke: 'currentColor', 'stroke-width': 2.5 }, [h('path', { d: 'M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z' })]);

const activeTab = ref('dashboard');
const stats = ref({ totalDonations: 0, totalDonors: 0, activeCampaigns: 0 });
const teamList = ref([]);
const donorList = ref([]);
const pendingCampaigns = ref([]);
const pendingDonations = ref([]);
const loading = ref(true);
const showAddModal = ref(false);
const errorMsg = ref('');
const errorCode = ref(null);
const lastAttemptedUrl = ref('');

const ShieldCheckIcon = () => h('svg', { width: 16, height: 16, viewBox: '0 0 24 24', fill: 'none', stroke: 'currentColor', 'stroke-width': 2.5 }, [h('path', { d: 'M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10' }), h('path', { d: 'm9 12 2 2 4-4' })]);
const BellIcon = () => h('svg', { width: 16, height: 16, viewBox: '0 0 24 24', fill: 'none', stroke: 'currentColor', 'stroke-width': 2.5 }, [h('path', { d: 'M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9' }), h('path', { d: 'M10.3 21a1.94 1.94 0 0 0 3.4 0' })]);

const tabs = [
  { id: 'dashboard', label: 'Dashboard', icon: LayoutIcon },
  { id: 'team', label: 'Pengurusan Tim', icon: UsersIcon },
  { id: 'donors', label: 'Pangkalan Donatur', icon: HeartIcon },
  { id: 'verify_campaign', label: 'Approval Kampanye', icon: ShieldCheckIcon },
  { id: 'verify_donation', label: 'Verifikasi Donasi', icon: BellIcon }
];

const formData = reactive({ name: '', email: '', password: '', role: 'field_worker' });
const formLoading = ref(false);
const message = reactive({ text: '', type: '' });

// State Masa Real-time
const currentTime = computed(() => {
  return new Date().toLocaleString('id-ID', { 
    hour: '2-digit', 
    minute: '2-digit', 
    second: '2-digit',
    day: 'numeric',
    month: 'long',
    year: 'numeric'
  });
});

const fetchData = async () => {
  loading.value = true;
  errorMsg.value = '';
  errorCode.value = null;

  try {
    const [resStats, resTeam, resDonors, resPCam, resPDon] = await Promise.all([
      api.get('/admin/stats'),
      api.get('/admin/management-list'),
      api.get('/admin/donors-list'),
      api.get('/admin/pending-campaigns'),
      api.get('/admin/pending-donations')
    ]);
    
    stats.value = resStats.data;
    teamList.value = resTeam.data;
    donorList.value = resDonors.data;
    pendingCampaigns.value = resPCam.data;
    pendingDonations.value = resPDon.data;
  } catch (error) {
    console.error("DEBUG API ERROR:", error);
    errorCode.value = error.response?.status || '503';
    errorMsg.value = error.response?.data?.message || "Ralat sistem dikesan semasa memproses data.";
    lastAttemptedUrl.value = error.config?.url || '';
  } finally {
    loading.value = false;
  }
};

const handleApproveCampaign = async (id) => {
  if (!confirm('Setujui kampanye ini untuk dipublikasikan?')) return;
  try {
    await api.put(`/admin/approve-campaign/${id}`);
    fetchData();
  } catch (error) {
    alert('Gagal menyetujui kampanye');
  }
};

const handleVerifyDonation = async (id, status) => {
  const action = status === 'Success' ? 'MENYETUJUI' : 'MENOLAK';
  if (!confirm(`Apakah Anda yakin ingin ${action} donasi ini?`)) return;
  
  try {
    await api.put(`/admin/verify-donation/${id}`, { status });
    fetchData();
  } catch (error) {
    alert('Gagal memproses verifikasi donasi');
  }
};

onMounted(() => {
  fetchData();
});

const handleAddUser = async () => {
  formLoading.value = true;
  message.text = '';
  
  try {
    const res = await api.post('/admin/add-account', formData);
    
    message.text = res.data.message;
    message.type = 'success';
    fetchData();
    setTimeout(() => { 
      showAddModal.value = false; 
      message.text = '';
      formData.name = '';
      formData.email = '';
      formData.password = '';
    }, 1500);
  } catch (error) {
    message.text = error.response?.data?.message || 'Pendaftaran akaun baru gagal.';
    message.type = 'error';
  } finally {
    formLoading.value = false;
  }
};

const handleDelete = async (id) => {
  if (!confirm("Padam akaun ini? Data yang dipadam tidak boleh dikembalikan.")) return;
  try {
    await api.delete(`/admin/delete-account/${id}`);
    fetchData();
  } catch (error) {
    alert(error.response?.data?.message || "Kegagalan operasi pemadaman.");
  }
};
</script>

<style scoped>
.animate-in { animation: fadeIn 0.5s ease-out forwards; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }

/* Scrollbar Styling */
::-webkit-scrollbar {
  width: 8px;
}
::-webkit-scrollbar-track {
  background: #f1f1f1;
}
::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 10px;
}
::-webkit-scrollbar-thumb:hover {
  background: #94a3b8;
}
</style>