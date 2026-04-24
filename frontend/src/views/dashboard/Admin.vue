<template>
  <div class="min-h-screen bg-gray-50/50">
    <div class="max-w-7xl mx-auto px-4 py-6 md:py-10">
      <div class="flex flex-col lg:flex-row gap-8 items-start">
        
        <!-- Sidebar Kiri: Admin Specific -->
        <div class="w-full lg:w-72 space-y-6 flex-shrink-0 sticky top-28">
           <!-- Info Admin -->
           <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm relative overflow-hidden group">
              <div class="absolute -right-4 -top-4 w-24 h-24 bg-green-600/5 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
              <div class="relative z-10 space-y-4 text-center">
                 <div class="w-16 h-16 bg-green-50 text-green-600 rounded-2xl flex items-center justify-center text-2xl mx-auto shadow-sm">
                    <i class="fas fa-user-shield"></i>
                 </div>
                 <div class="space-y-1">
                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none">Super Admin</p>
                    <p class="text-lg font-black text-gray-900 tracking-tighter truncate">Administrator</p>
                 </div>
                 <div class="flex gap-2 justify-center pt-2">
                    <button @click="activeTab = 'settings'" class="p-2.5 bg-gray-50 text-gray-400 rounded-xl hover:text-green-600 hover:bg-green-50 transition-all shadow-sm">
                       <i class="fas fa-cog"></i>
                    </button>
                    <button @click="showAddModal = true" class="px-4 py-2 bg-green-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-green-100 hover:bg-green-700 transition-all">
                       + Staff
                    </button>
                 </div>
              </div>
           </div>

           <!-- Menu Navigasi Admin -->
           <nav class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden">
              <div class="p-3 space-y-1">
                 <p class="text-[9px] font-black text-gray-300 uppercase tracking-widest pl-5 py-3">Menu Utama</p>
                 <button
                   v-for="tab in sidebarTabs"
                   :key="tab.id"
                   @click="activeTab = tab.id"
                   class="w-full flex items-center gap-4 px-5 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all group border-2 border-transparent text-left"
                   :class="activeTab === tab.id 
                     ? 'bg-green-600 border-green-600 text-white shadow-xl shadow-green-100' 
                     : 'text-gray-400 hover:bg-gray-50 hover:text-gray-900 hover:border-gray-100'"
                 >
                   <div :class="['w-8 h-8 rounded-lg flex items-center justify-center transition-all', activeTab === tab.id ? 'bg-white/20 text-white' : 'bg-gray-50 text-gray-400 group-hover:bg-white']">
                      <i :class="[tab.icon, 'text-[10px]']"></i>
                   </div>
                   {{ tab.label }}
                 </button>
              </div>

              <!-- Admin Management Group (Collapsible) -->
              <div class="mt-2 border-t border-gray-100">
                 <div 
                   @click="isAdminManagementOpen = !isAdminManagementOpen"
                   class="bg-green-600 px-6 py-4 flex justify-between items-center text-white cursor-pointer hover:bg-green-700 transition-colors"
                 >
                    <span class="text-[11px] font-bold uppercase tracking-wider">Admin Management</span>
                    <i :class="['fas fa-chevron-up text-[10px] transition-transform duration-300', !isAdminManagementOpen ? 'rotate-180' : '']"></i>
                 </div>
                 <div v-show="isAdminManagementOpen" class="max-h-80 overflow-y-auto p-2 bg-white custom-scrollbar animate-in slide-in-from-top-2 duration-300">
                    <button
                      v-for="tab in managementTabs"
                      :key="tab.id"
                      @click="activeTab = tab.id"
                      class="w-full flex items-center gap-4 px-6 py-3.5 text-[12px] font-semibold transition-all text-left border-l-4 border-transparent"
                      :class="activeTab === tab.id 
                        ? 'text-green-600 bg-green-50/50 border-green-600' 
                        : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900'"
                    >
                      <i :class="[tab.icon, 'w-5 text-center transition-colors', activeTab === tab.id ? 'text-green-600' : 'text-gray-400']"></i>
                      {{ tab.label }}
                    </button>
                 </div>
              </div>
              <!-- Distribution Management Group (Collapsible) -->
              <div class="mt-2 border-t border-gray-100">
                 <div 
                   @click="isDistributionManagementOpen = !isDistributionManagementOpen"
                   class="bg-green-600 px-6 py-4 flex justify-between items-center text-white cursor-pointer hover:bg-green-700 transition-colors"
                 >
                    <span class="text-[11px] font-bold uppercase tracking-wider">Distribution Management</span>
                    <i :class="['fas fa-chevron-up text-[10px] transition-transform duration-300', !isDistributionManagementOpen ? 'rotate-180' : '']"></i>
                 </div>
                 <div v-show="isDistributionManagementOpen" class="p-2 bg-white animate-in slide-in-from-top-2 duration-300">
                    <button
                      v-for="tab in distributionTabs"
                      :key="tab.id"
                      @click="activeTab = tab.id"
                      class="w-full flex items-center gap-4 px-6 py-3.5 text-[12px] font-semibold transition-all text-left border-l-4 border-transparent"
                      :class="activeTab === tab.id 
                        ? 'text-green-600 bg-green-50/50 border-green-600' 
                        : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900'"
                    >
                      <i :class="[tab.icon, 'w-5 text-center transition-colors', activeTab === tab.id ? 'text-green-600' : 'text-gray-400']"></i>
                      {{ tab.label }}
                    </button>
                 </div>
              </div>
              <!-- Transaction Management Group (Collapsible) -->
              <div class="mt-2 border-t border-gray-100">
                 <div 
                   @click="isTransactionManagementOpen = !isTransactionManagementOpen"
                   class="bg-green-600 px-6 py-4 flex justify-between items-center text-white cursor-pointer hover:bg-green-700 transition-colors"
                 >
                    <span class="text-[11px] font-bold uppercase tracking-wider">Transaction Management</span>
                    <i :class="['fas fa-chevron-up text-[10px] transition-transform duration-300', !isTransactionManagementOpen ? 'rotate-180' : '']"></i>
                 </div>
                 <div v-show="isTransactionManagementOpen" class="p-2 bg-white animate-in slide-in-from-top-2 duration-300 max-h-72 overflow-y-auto custom-scrollbar">
                    <button
                      v-for="tab in transactionTabs"
                      :key="tab.id"
                      @click="activeTab = tab.id"
                      class="w-full flex items-center gap-4 px-6 py-3.5 text-[12px] font-semibold transition-all text-left border-l-4 border-transparent"
                      :class="activeTab === tab.id 
                        ? 'text-green-600 bg-green-50/50 border-green-600' 
                        : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900'"
                    >
                      <i :class="[tab.icon, 'w-5 text-center transition-colors', activeTab === tab.id ? 'text-green-600' : 'text-gray-400']"></i>
                      {{ tab.label }}
                    </button>
                 </div>
              </div>
           </nav>

           <!-- Link Kembali -->
           <router-link to="/dashboard" class="flex items-center justify-center gap-3 w-full py-4 bg-slate-900 text-white rounded-2xl font-black uppercase text-[10px] tracking-widest hover:bg-green-600 transition-all shadow-lg">
              <i class="fas fa-arrow-left text-[8px]"></i>
              Dashboard Utama
           </router-link>
        </div>

        <main class="flex-grow space-y-8 animate-in fade-in slide-in-from-right-4 duration-700 w-full overflow-hidden">
          <header class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="space-y-1 text-center md:text-left">
              <h1 class="text-3xl font-black text-gray-900 tracking-tighter uppercase italic">ADMIN <span class="text-green-600">PANEL [V2]</span></h1>
              <p class="text-[10px] text-gray-400 font-bold uppercase tracking-[0.2em]">Pusat Kendali & CMS HumanityCare</p>
            </div>

            <div class="flex gap-4">
               <div class="text-right hidden md:block">
                  <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Status Sistem</p>
                  <div class="flex items-center gap-2 text-green-600 font-black text-[10px] uppercase">
                     <span class="w-2 h-2 bg-green-600 rounded-full animate-ping"></span>
                     Berjalan Normal
                  </div>
               </div>
            </div>
          </header>

          <div v-if="loading" class="py-24 md:py-40 text-center bg-white rounded-[2rem] md:rounded-[3rem] border border-dashed border-gray-200">
            <div class="animate-spin h-8 w-8 border-4 border-green-600 border-t-transparent rounded-full mx-auto mb-4"></div>
            <p class="text-gray-400 font-black text-[10px] uppercase tracking-widest">Sinkronisasi Data...</p>
          </div>

          <div v-else class="space-y-8 animate-in zoom-in-95 duration-500 pb-20 lg:pb-0">
            
            <!-- Dashboard Tab -->
            <div v-if="activeTab === 'dashboard'" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
              <div class="bg-white p-6 md:p-8 rounded-[2rem] border border-gray-100 shadow-sm bg-gradient-to-br from-white to-green-50/30">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Total Donasi</p>
                <p class="text-2xl md:text-3xl font-black text-green-600 tracking-tighter">Rp {{ stats.totalDonations?.toLocaleString() || 0 }}</p>
              </div>
              <div class="bg-white p-6 md:p-8 rounded-[2rem] border border-gray-100 shadow-sm">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Kampanye Aktif</p>
                <p class="text-3xl font-black text-green-600 tracking-tighter">{{ stats.activeCampaigns || 0 }}</p>
              </div>
              <div class="bg-white p-6 md:p-8 rounded-[2rem] border border-gray-100 shadow-sm">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Zakat Pending</p>
                <p class="text-3xl font-black text-green-600 tracking-tighter">{{ pendingZakats.length }}</p>
              </div>
            </div>

            <!-- Generic Module Renderer -->
            <template v-if="managementTabs.some(t => t.id === activeTab) || distributionTabs.some(t => t.id === activeTab) || transactionTabs.some(t => t.id === activeTab)">
              <div v-if="activeModule" class="space-y-6">
                <!-- Module Header -->
                <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm p-8 flex flex-col md:flex-row justify-between items-center gap-5">
                  <div class="flex items-center gap-5">
                    <div class="w-14 h-14 bg-green-50 text-green-600 rounded-2xl flex items-center justify-center text-xl shadow-sm"><i :class="activeModule.icon"></i></div>
                    <div>
                      <h2 class="text-2xl font-black text-gray-900 tracking-tighter uppercase italic">{{ activeModule.title }}</h2>
                      <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{{ activeModule.description }}</p>
                    </div>
                  </div>
                  <button @click="moduleFormData = {}; showModuleModal = true" class="flex items-center gap-3 bg-green-600 text-white px-7 py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-green-700 transition-all shadow-xl shadow-green-100 whitespace-nowrap">
                    <i class="fas fa-plus text-[8px]"></i> Tambah {{ activeModule.entity }}
                  </button>
                </div>

                <!-- Stats Row -->
                <div v-if="activeModule.stats" class="grid grid-cols-2 md:grid-cols-4 gap-4">
                  <div v-for="s in activeModule.stats" :key="s.label" class="bg-white rounded-[1.5rem] border border-gray-100 shadow-sm p-5 flex items-center gap-4">
                    <div :class="['w-10 h-10 rounded-xl flex items-center justify-center text-sm', s.color]">
                      <i :class="s.icon"></i>
                    </div>
                    <div>
                      <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">{{ s.label }}</p>
                      <p class="text-xl font-black tracking-tighter text-gray-900">{{ s.value }}</p>
                    </div>
                  </div>
                </div>

                <!-- Table -->
                <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden">
                  <div class="p-6 border-b border-gray-50 flex flex-col sm:row justify-between items-center gap-4 bg-gradient-to-r from-green-600 to-emerald-600">
                    <div class="relative w-full sm:w-72">
                      <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-white/50 text-xs"></i>
                      <input v-model="moduleSearchQuery" :placeholder="`Cari ${activeModule.entity}...`"
                        class="w-full pl-10 pr-4 py-3 bg-white/20 rounded-2xl text-xs font-semibold text-white placeholder-white/60 outline-none focus:bg-white/30 border-none transition-all" />
                    </div>
                    <span class="text-[9px] font-black text-white/70 uppercase tracking-widest whitespace-nowrap">{{ filteredModuleData.length }} Data</span>
                  </div>
                  <div class="overflow-x-auto">
                    <table class="w-full">
                      <thead class="bg-gray-50">
                        <tr>
                          <th class="px-6 py-4 text-left text-[9px] font-black text-gray-400 uppercase tracking-widest">#</th>
                          <th v-for="col in activeModule.columns" :key="col.key" class="px-6 py-4 text-left text-[9px] font-black text-gray-400 uppercase tracking-widest">{{ col.label }}</th>
                          <th class="px-6 py-4 text-center text-[9px] font-black text-gray-400 uppercase tracking-widest">Aksi</th>
                        </tr>
                      </thead>
                      <tbody class="divide-y divide-gray-50">
                        <tr v-for="(row, idx) in filteredModuleData.slice(0, 10)" :key="idx" class="hover:bg-green-50/20 transition-colors group">
                          <td class="px-6 py-4 text-[10px] font-black text-gray-300">{{ String(idx+1).padStart(2,'0') }}</td>
                          <td v-for="col in activeModule.columns" :key="col.key" class="px-6 py-4">
                            <span v-if="col.type==='badge'" :class="['inline-flex px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest', col.badgeClass?.(row[col.key]) || 'bg-green-50 text-green-600']">{{ row[col.key]||'-' }}</span>
                            <span v-else-if="col.type==='currency'" class="text-[11px] font-black text-green-600">Rp {{ Number(row[col.key]||0).toLocaleString('id-ID') }}</span>
                            <span v-else class="text-[11px] font-semibold text-gray-700">{{ row[col.key]||'-' }}</span>
                          </td>
                          <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                              <button @click="editModuleData(row, idx)" class="w-8 h-8 bg-green-50 text-green-600 rounded-xl hover:bg-green-600 hover:text-white transition-all flex items-center justify-center"><i class="fas fa-pen text-[9px]"></i></button>
                              <button @click="handleDeleteModuleData(row.id)" class="w-8 h-8 bg-red-50 text-red-500 rounded-xl hover:bg-red-500 hover:text-white transition-all flex items-center justify-center"><i class="fas fa-trash text-[9px]"></i></button>
                            </div>
                          </td>
                        </tr>
                        <tr v-if="filteredModuleData.length === 0">
                          <td :colspan="activeModule.columns.length+2" class="py-20 text-center">
                            <div class="flex flex-col items-center gap-3">
                              <div class="w-16 h-16 bg-gray-50 text-gray-200 rounded-3xl flex items-center justify-center text-3xl"><i :class="activeModule.icon"></i></div>
                              <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Belum ada data — klik Tambah untuk memulai</p>
                            </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <div class="px-6 py-4 border-t border-gray-50 flex justify-between items-center">
                    <p class="text-[9px] font-black text-gray-300 uppercase tracking-widest">1–{{ Math.min(10, filteredModuleData.length) }} dari {{ filteredModuleData.length }}</p>
                    <div class="flex gap-2">
                      <button class="w-8 h-8 rounded-xl bg-gray-50 text-gray-400 text-[10px] font-black hover:bg-green-600 hover:text-white transition-all">‹</button>
                      <button class="w-8 h-8 rounded-xl bg-green-600 text-white text-[10px] font-black shadow-lg shadow-green-100">1</button>
                      <button class="w-8 h-8 rounded-xl bg-gray-50 text-gray-400 text-[10px] font-black hover:bg-green-600 hover:text-white transition-all">›</button>
                    </div>
                  </div>
                </div>
              </div>
            </template>

            <!-- Module Add Modal -->
            <div v-if="showModuleModal && activeModule" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm flex items-center justify-center p-4 z-[100]">
              <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-lg overflow-hidden animate-in zoom-in duration-300">
                <div class="p-8 bg-gradient-to-r from-green-600 to-emerald-600 text-white flex justify-between items-center">
                  <div>
                    <p class="text-[9px] font-black uppercase tracking-widest text-green-200">Tambah Data Baru</p>
                    <h3 class="text-xl font-black uppercase tracking-tighter">{{ activeModule.entity }}</h3>
                  </div>
                  <button @click="showModuleModal = false" class="text-white/60 hover:text-white text-xl">✕</button>
                </div>
                <div class="p-8 space-y-5 max-h-[60vh] overflow-y-auto">
                  <div v-for="field in activeModule.fields" :key="field.key" class="space-y-2">
                    <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest">{{ field.label }}</label>
                    <textarea v-if="field.type==='textarea'" v-model="moduleFormData[field.key]" rows="3"
                      class="w-full p-4 bg-gray-50 rounded-2xl outline-none focus:ring-2 focus:ring-green-500 text-xs font-semibold border-none transition-all resize-none"></textarea>
                    <select v-else-if="field.type==='select'" v-model="moduleFormData[field.key]"
                      class="w-full p-4 bg-gray-50 rounded-2xl outline-none focus:ring-2 focus:ring-green-500 text-xs font-semibold border-none transition-all">
                      <option value="">-- Pilih --</option>
                      <option v-for="opt in field.options" :key="opt" :value="opt">{{ opt }}</option>
                    </select>
                    <input v-else :type="field.type||'text'" v-model="moduleFormData[field.key]" :placeholder="field.placeholder||field.label"
                      class="w-full p-4 bg-gray-50 rounded-2xl outline-none focus:ring-2 focus:ring-green-500 text-xs font-semibold border-none transition-all" />
                  </div>
                </div>
                <div class="p-8 pt-0">
                  <button @click="showModuleModal = false" class="w-full py-5 bg-green-600 text-white rounded-[2rem] font-black uppercase tracking-widest shadow-xl shadow-green-100 hover:bg-green-700 transition active:scale-95">
                    Simpan Data
                  </button>
                </div>
              </div>
            </div>

            <!-- User Management Tab -->
            <div v-if="activeTab === 'users'" class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
               <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-green-50/20">
                  <h3 class="font-black text-gray-900 uppercase tracking-tighter italic">Manajemen <span class="text-green-600">Pengguna</span></h3>
                  <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">{{ users.length }} Akun Terdaftar</span>
               </div>
               <div class="overflow-x-auto">
                 <table class="w-full text-left">
                   <thead class="bg-gray-50 text-[10px] uppercase font-black text-gray-400 tracking-widest">
                     <tr>
                       <th class="px-8 py-4">Nama & Email</th>
                       <th class="px-8 py-4">Peran</th>
                       <th class="px-8 py-4 text-center">Aksi</th>
                     </tr>
                   </thead>
                   <tbody class="divide-y divide-gray-50 uppercase text-[10px] font-black tracking-tight">
                     <tr v-for="u in users" :key="u.id" class="hover:bg-gray-50/50">
                       <td class="px-8 py-6">
                         <div class="flex flex-col">
                            <span>{{ u.name }}</span>
                            <span class="text-[9px] text-slate-400 normal-case font-medium">{{ u.email }}</span>
                         </div>
                       </td>
                       <td class="px-8 py-6">
                          <span :class="u.role === 'admin' ? 'text-red-500' : (u.role === 'field_worker' ? 'text-green-500' : 'text-slate-400')">
                             {{ u.role }}
                          </span>
                       </td>
                       <td class="px-8 py-6 text-center">
                         <button @click="handleDeleteUser(u.id)" class="text-red-500 hover:text-red-700 p-2 transition active:scale-90">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 6h18m-2 0v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6m3 0V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                         </button>
                       </td>
                     </tr>
                   </tbody>
                 </table>
               </div>
            </div>

            <!-- Zakat Approval Tab (NEW) -->
            <div v-if="activeTab === 'verify_zakat'" class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
               <div class="p-8 border-b border-gray-50 bg-green-50/20 text-center md:text-left">
                  <h3 class="font-black text-gray-900 uppercase tracking-tighter italic">Verifikasi <span class="text-green-600">Zakat</span></h3>
               </div>
               <div class="overflow-x-auto">
                  <table class="w-full text-left">
                    <thead class="bg-gray-50 font-black text-[10px] uppercase text-gray-400 tracking-widest">
                      <tr>
                        <th class="px-8 py-4">Muzakki (User)</th>
                        <th class="px-8 py-4">Jenis & Nominal</th>
                        <th class="px-8 py-4 text-center">Keputusan</th>
                      </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 uppercase text-[10px] font-black tracking-tight">
                      <tr v-for="z in pendingZakats" :key="z.id">
                        <td class="px-8 py-6">{{ z.user_name }}</td>
                        <td class="px-8 py-6">
                           <div class="flex flex-col">
                              <span>{{ z.zakat_type }}</span>
                              <span class="text-green-600 font-black">Rp {{ Number(z.amount).toLocaleString() }}</span>
                           </div>
                        </td>
                        <td class="px-8 py-6 text-center">
                           <div class="flex gap-2 justify-center">
                              <button @click="triggerNoteModal('zakat', z.id, 'Success')" class="bg-green-600 text-white px-4 py-2 rounded-xl text-[9px] font-black tracking-widest uppercase hover:scale-105 transition">Terima</button>
                              <button @click="triggerNoteModal('zakat', z.id, 'Failed')" class="bg-red-500 text-white px-4 py-2 rounded-xl text-[9px] font-black tracking-widest uppercase hover:scale-105 transition">Tolak</button>
                           </div>
                        </td>
                      </tr>
                      <tr v-if="pendingZakats.length === 0">
                        <td colspan="3" class="px-8 py-20 text-center text-gray-400 italic">Antrean zakat kosong</td>
                      </tr>
                    </tbody>
                  </table>
               </div>
            </div>

            <!-- Site Settings Tab (CMS) -->
            <div v-if="activeTab === 'settings'" class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
               <div class="p-8 border-b border-gray-50 bg-green-50/20">
                  <h3 class="font-black text-gray-900 uppercase tracking-tighter">Pengaturan Eksternal <span class="text-green-600">(CMS)</span></h3>
               </div>
               <div class="p-8 space-y-10">
                  <!-- Section: Hero -->
                  <div class="space-y-6">
                     <h4 class="text-[10px] font-black uppercase tracking-widest text-green-600 border-l-4 border-green-600 pl-4">Halaman Utama (Hero)</h4>
                     <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                           <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Judul Headline</label>
                           <textarea v-model="settingsData.hero_title" rows="2" class="w-full p-4 bg-gray-50 rounded-2xl border-none outline-none focus:ring-2 focus:ring-green-500 font-bold text-xs"></textarea>
                        </div>
                        <div class="space-y-2">
                           <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest">URL Gambar Hero</label>
                           <input v-model="settingsData.hero_image" class="w-full p-4 bg-gray-50 rounded-2xl border-none outline-none focus:ring-2 focus:ring-green-500 font-bold text-xs" />
                        </div>
                        <div class="md:col-span-2 space-y-2">
                           <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Sub-Judul Hero</label>
                           <textarea v-model="settingsData.hero_subtitle" rows="3" class="w-full p-4 bg-gray-50 rounded-2xl border-none outline-none focus:ring-2 focus:ring-green-500 font-bold text-xs"></textarea>
                        </div>
                     </div>
                  </div>

                  <!-- Section: About -->
                  <div class="space-y-6 pt-6 border-t border-gray-100">
                     <h4 class="text-[10px] font-black uppercase tracking-widest text-green-600 border-l-4 border-green-600 pl-4">Halaman Tentang Kami</h4>
                     <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                           <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Misi Kami (Teks)</label>
                           <textarea v-model="settingsData.about_mission" rows="4" class="w-full p-4 bg-gray-50 rounded-2xl border-none outline-none focus:ring-2 focus:ring-green-500 font-bold text-xs italic"></textarea>
                        </div>
                        <div class="space-y-2">
                           <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest">URL Gambar Misi</label>
                           <input v-model="settingsData.about_image" class="w-full p-4 bg-gray-50 rounded-2xl border-none outline-none focus:ring-2 focus:ring-green-500 font-bold text-xs" />
                           <div class="mt-4 rounded-2xl overflow-hidden aspect-video border border-gray-100">
                              <img :src="settingsData.about_image" class="w-full h-full object-cover opacity-80" />
                           </div>
                        </div>
                     </div>
                  </div>

                  <button @click="handleSaveSettings" :disabled="savingSettings" class="w-full py-5 bg-green-600 text-white rounded-[2rem] font-black uppercase tracking-widest shadow-xl shadow-green-100 hover:bg-green-700 transition active:scale-95 disabled:opacity-50">
                     {{ savingSettings ? 'Menyimpan...' : 'Perbarui Seluruh Konten' }}
                  </button>
               </div>
            </div>

            <!-- Other Approval Tabs -->
            <div v-if="activeTab === 'verify_campaign'" class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
               <div class="p-8 border-b border-gray-50 bg-green-50/20">
                  <h3 class="font-black text-gray-900 uppercase tracking-tighter">Approval <span class="text-green-600">Kampanye</span></h3>
               </div>
               <div class="overflow-x-auto">
                 <table class="w-full text-left">
                   <thead class="bg-gray-50 text-[10px] uppercase font-black text-gray-400 tracking-widest">
                     <tr>
                       <th class="px-8 py-4">Informasi Kampanye</th>
                       <th class="px-8 py-4 text-center">Tindakan</th>
                     </tr>
                   </thead>
                   <tbody class="divide-y divide-gray-50 uppercase text-[10px] font-black tracking-tight">
                     <tr v-for="cam in pendingCampaigns" :key="cam.id" class="hover:bg-gray-50/50">
                       <td class="px-8 py-6">{{ cam.title }}</td>
                       <td class="px-8 py-6 text-center">
                         <button @click="handleApproveCampaign(cam.id)" class="px-6 py-2 bg-green-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-green-700 shadow-lg shadow-green-100">Setujui</button>
                       </td>
                     </tr>
                   </tbody>
                 </table>
                </div>
             </div>
 
             <div v-if="activeTab === 'verify_donation'" class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
               <div class="p-8 border-b border-gray-50 bg-green-50/20 text-center md:text-left">
                  <h3 class="font-black text-gray-900 uppercase tracking-tighter italic">Konfirmasi <span class="text-green-600">Donasi</span></h3>
               </div>
               <div class="overflow-x-auto">
                  <table class="w-full text-left">
                    <thead class="bg-gray-50 font-black text-[10px] uppercase text-gray-400 tracking-widest">
                      <tr>
                        <th class="px-8 py-4">Nama Donor</th>
                        <th class="px-8 py-4">Jumlah</th>
                        <th class="px-8 py-4 text-center">Opsi</th>
                      </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 uppercase text-[10px] font-black tracking-tight">
                      <tr v-for="don in pendingDonations" :key="don.id">
                        <td class="px-8 py-6">
                           <div class="flex flex-col">
                               <span>{{ don.donor_name }}</span>
                               <span class="text-[8px] text-slate-400 normal-case font-medium">{{ don.payment_method }}</span>
                           </div>
                        </td>
                        <td class="px-8 py-6 text-green-600 font-black">Rp {{ don.amount.toLocaleString() }}</td>
                        <td class="px-8 py-6 text-center">
                          <div class="flex gap-2 justify-center">
                             <button @click="triggerNoteModal('donation', don.id, 'Success')" class="bg-green-600 text-white px-4 py-2 rounded-xl text-[9px] font-black tracking-widest uppercase hover:scale-105 transition">Terima</button>
                             <button @click="triggerNoteModal('donation', don.id, 'Failed')" class="bg-red-500 text-white px-4 py-2 rounded-xl text-[9px] font-black tracking-widest uppercase hover:scale-105 transition">Tolak</button>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
               </div>
            </div>

            <!-- Monitoring & Task Management Tab (NEW) -->
            <div v-if="activeTab === 'monitoring'" class="space-y-8">
              <!-- Worker Performance Cards -->
              <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div v-for="worker in workerPerformance" :key="worker.id" class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm relative overflow-hidden group">
                  <div class="absolute -right-4 -top-4 w-20 h-20 bg-green-50 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
                  <div class="relative z-10 space-y-4">
                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">{{ worker.role }}</p>
                    <h4 class="text-lg font-black text-gray-900 tracking-tighter">{{ worker.name }}</h4>
                    <div class="space-y-1">
                      <div class="flex justify-between items-center text-[10px] font-bold">
                        <span class="text-gray-400 uppercase">Progres Tugas</span>
                        <span class="text-green-600">{{ worker.percentage }}%</span>
                      </div>
                      <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                        <div :style="{ width: worker.percentage + '%' }" class="h-full bg-green-600 transition-all duration-1000"></div>
                      </div>
                      <p class="text-[9px] text-gray-400 font-medium italic">{{ worker.completed_tasks }} / {{ worker.total_tasks }} Selesai</p>
                    </div>
                    <button @click="openAssignTask(worker)" class="w-full py-3 bg-slate-900 text-white rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-green-600 transition-all">
                      Beri Tugas
                    </button>
                  </div>
                </div>
              </div>

              <!-- Tabs Internal: Absensi vs Daftar Tugas -->
              <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-50 flex gap-4 bg-gray-50/50">
                  <button @click="monitoringSubTab = 'attendance'" :class="monitoringSubTab === 'attendance' ? 'bg-green-600 text-white shadow-lg' : 'text-gray-400 hover:text-gray-700'" class="px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">Absensi</button>
                  <button @click="monitoringSubTab = 'tasks'" :class="monitoringSubTab === 'tasks' ? 'bg-green-600 text-white shadow-lg' : 'text-gray-400 hover:text-gray-700'" class="px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">Daftar Tugas</button>
                </div>

                <!-- Sub-Tab: Attendance -->
                <div v-if="monitoringSubTab === 'attendance'" class="overflow-x-auto">
                  <table class="w-full text-left">
                    <thead class="bg-gray-50 text-[9px] font-black text-gray-400 uppercase tracking-widest">
                      <tr>
                        <th class="px-8 py-5">Pegawai</th>
                        <th class="px-8 py-5">Waktu</th>
                        <th class="px-8 py-5">Tipe</th>
                        <th class="px-8 py-5">Lokasi</th>
                        <th class="px-8 py-5 text-center">Aksi</th>
                      </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                      <tr v-for="att in allAttendances" :key="att.id" class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-8 py-6">
                          <p class="font-black text-gray-900 text-[10px] uppercase tracking-tight">{{ att.employee_name }}</p>
                          <p class="text-[9px] text-gray-400 font-medium lowercase">{{ att.employee_email }}</p>
                        </td>
                        <td class="px-8 py-6">
                           <p class="text-[10px] font-bold text-gray-700">{{ formatDate(att.created_at) }}</p>
                           <p class="text-[9px] text-gray-400 font-medium">{{ formatTime(att.created_at) }}</p>
                        </td>
                        <td class="px-8 py-6">
                          <span :class="att.type === 'check_in' ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-600'" class="px-3 py-1 rounded-full text-[8px] font-black uppercase tracking-widest">
                            {{ att.type === 'check_in' ? 'Masuk' : 'Pulang' }}
                          </span>
                        </td>
                        <td class="px-8 py-6">
                          <p class="text-[10px] font-semibold text-gray-600 italic truncate w-40">{{ att.location }}</p>
                        </td>
                        <td class="px-8 py-6 text-center">
                          <button v-if="att.attachment" @click="viewAttachment(att.attachment)" class="text-green-600 hover:underline text-[9px] font-black uppercase tracking-widest">Lihat Bukti</button>
                          <span v-else class="text-gray-300 text-[9px] uppercase font-black">-</span>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>

                <!-- Sub-Tab: All Tasks -->
                <div v-if="monitoringSubTab === 'tasks'" class="overflow-x-auto">
                  <table class="w-full text-left">
                    <thead class="bg-gray-50 text-[9px] font-black text-gray-400 uppercase tracking-widest">
                      <tr>
                        <th class="px-8 py-5">Tugas & Pegawai</th>
                        <th class="px-8 py-5">Tenggat</th>
                        <th class="px-8 py-5">Status</th>
                        <th class="px-8 py-5 text-center">Hasil</th>
                      </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                      <tr v-for="task in allTasks" :key="task.id" class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-8 py-6">
                           <p class="font-black text-gray-900 text-[10px] uppercase tracking-tight">{{ task.task }}</p>
                           <p class="text-[9px] text-green-600 font-bold uppercase tracking-widest">PIC: {{ task.employee_name }}</p>
                        </td>
                        <td class="px-8 py-6">
                           <p class="text-[10px] font-bold text-gray-700 italic">{{ formatDate(task.deadline) }}</p>
                        </td>
                        <td class="px-8 py-6">
                          <span :class="task.status === 'completed' ? 'bg-green-600 text-white' : 'bg-yellow-50 text-yellow-600'" class="px-3 py-1 rounded-lg text-[8px] font-black uppercase tracking-widest">
                            {{ task.status }}
                          </span>
                        </td>
                        <td class="px-8 py-6 text-center">
                          <button v-if="task.result_attachment" @click="viewAttachment(task.result_attachment)" class="bg-green-50 text-green-600 p-2.5 rounded-xl hover:bg-green-600 hover:text-white transition-all">
                            <i class="fas fa-eye text-[9px]"></i>
                          </button>
                          <span v-else class="text-gray-300 text-[9px] uppercase font-black">Belum Ada</span>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div> <!-- End of Monitoring Tab -->

          </div> <!-- End of main content area -->
        </main>

    <!-- Modal: Note for Approval/Rejection -->
    <div v-if="showNoteModal" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm flex items-center justify-center p-4 z-[100]">
       <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-md overflow-hidden animate-in zoom-in duration-300">
         <div class="p-8 pb-4">
            <div class="flex justify-between items-start mb-6">
               <div class="space-y-1">
                  <h3 class="text-xl font-black uppercase tracking-tighter">Berikan <span class="text-green-600">Catatan</span></h3>
                  <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Feedback untuk pendaftar/pembayar</p>
               </div>
               <button @click="showNoteModal = false" class="text-gray-300 hover:text-gray-600">✕</button>
            </div>
            
            <div class="space-y-4">
               <div class="p-4 bg-gray-50 rounded-2xl flex items-center gap-3">
                  <div :class="noteData.status.includes('Success') || noteData.status.includes('approved') ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600'" class="w-10 h-10 rounded-xl flex items-center justify-center text-lg">
                     <i :class="noteData.status.includes('Success') || noteData.status.includes('approved') ? 'fas fa-check-circle' : 'fas fa-times-circle'"></i>
                  </div>
                  <div>
                     <p class="text-[8px] font-black uppercase tracking-widest text-gray-400">Tindakan</p>
                     <p class="text-[10px] font-black uppercase tracking-tight">{{ noteData.status }}</p>
                  </div>
               </div>
               
               <textarea 
                  v-model="noteData.note" 
                  placeholder="Ketik catatan di sini..." 
                  rows="4" 
                  class="w-full p-6 bg-gray-50 rounded-[2rem] border-2 border-transparent focus:border-green-600 outline-none font-bold text-xs leading-relaxed transition-all"
               ></textarea>
            </div>
         </div>
         <div class="p-8 pt-4">
            <button 
               @click="submitDecisionWithNote" 
               :disabled="processingDecision"
               class="w-full py-5 bg-green-600 text-white rounded-[2rem] font-black uppercase tracking-widest shadow-xl shadow-green-100 hover:bg-green-700 transition active:scale-95 disabled:opacity-50"
            >
               {{ processingDecision ? 'Memproses...' : 'Konfirmasi & Kirim Catatan' }}
            </button>
         </div>
       </div>
    </div>

    <!-- Modal: Add Staff/User (Existing) -->
    <div v-if="showAddModal" class="fixed inset-0 bg-gray-900/70 backdrop-blur-md flex items-center justify-center p-4 z-50">
       <div class="bg-white rounded-[3rem] shadow-2xl w-full max-w-md overflow-hidden animate-in zoom-in duration-300">
         <div class="p-8 bg-green-600 text-white flex justify-between items-center">
           <h3 class="text-xl font-black uppercase tracking-tighter italic">Pendaftaran <span class="text-green-200">Personel</span></h3>
           <button @click="showAddModal = false" class="text-white/60 hover:text-white">✕</button>
         </div>
         <form @submit.prevent="handleAddUser" class="p-8 space-y-5">
           <input v-model="formData.name" placeholder="Nama Lengkap" class="w-full p-4 bg-gray-50 rounded-2xl border-none outline-none focus:ring-2 focus:ring-green-500 font-bold text-sm" required />
           <input v-model="formData.email" type="email" placeholder="Alamat Email" class="w-full p-4 bg-gray-50 rounded-2xl border-none outline-none focus:ring-2 focus:ring-green-500 font-bold text-sm" required />
           <input v-model="formData.password" type="password" placeholder="Kata Sandi Akses" class="w-full p-4 bg-gray-50 rounded-2xl border-none outline-none focus:ring-2 focus:ring-green-500 font-bold text-sm" required />
           <select v-model="formData.role" class="w-full p-4 bg-gray-50 rounded-2xl border-none outline-none focus:ring-2 focus:ring-green-500 font-black uppercase text-[10px] tracking-widest">
             <option value="field_worker">Field Worker</option>
             <option value="admin">Admin</option>
             <option value="user">User</option>
           </select>
           <button type="submit" class="w-full py-5 bg-green-600 text-white rounded-[2rem] font-black uppercase tracking-widest hover:bg-green-700">Aktifkan Akun</button>
         </form>
       </div>
    </div>

    <!-- Generic Module Modal -->
    <div v-if="showModuleModal && activeModule" class="fixed inset-0 bg-gray-900/70 backdrop-blur-md flex items-center justify-center p-4 z-50">
       <div class="bg-white rounded-[3rem] shadow-2xl w-full max-w-md overflow-hidden animate-in zoom-in duration-300">
         <div class="p-8 bg-gradient-to-br from-green-600 to-emerald-700 text-white flex justify-between items-center">
           <div>
             <h3 class="text-xl font-black uppercase tracking-tighter italic">Form <span class="text-green-200">{{ activeModule.entity }}</span></h3>
             <p class="text-[9px] font-bold text-white/60 uppercase tracking-widest mt-1">Input Data Baru</p>
           </div>
           <button @click="showModuleModal = false" class="w-10 h-10 bg-white/10 hover:bg-white/20 rounded-xl flex items-center justify-center transition-colors">✕</button>
         </div>
         <div class="p-8 space-y-5 max-h-[60vh] overflow-y-auto custom-scrollbar">
            <div v-for="field in activeModule.fields" :key="field.key" class="space-y-2">
              <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest px-1">{{ field.label }}</label>
              
              <select v-if="field.type === 'select'" v-model="moduleFormData[field.key]" class="w-full p-4 bg-gray-50 rounded-2xl border-none outline-none focus:ring-2 focus:ring-green-500 font-bold text-sm">
                <option v-for="opt in field.options" :key="opt" :value="opt">{{ opt }}</option>
              </select>

              <textarea v-else-if="field.type === 'textarea'" v-model="moduleFormData[field.key]" :placeholder="field.label" rows="3" class="w-full p-4 bg-gray-50 rounded-2xl border-none outline-none focus:ring-2 focus:ring-green-500 font-bold text-sm"></textarea>

              <input v-else :type="field.type || 'text'" v-model="moduleFormData[field.key]" :placeholder="field.label" class="w-full p-4 bg-gray-50 rounded-2xl border-none outline-none focus:ring-2 focus:ring-green-500 font-bold text-sm" />
            </div>
         </div>
         <div class="p-8 pt-4">
           <button @click="handleSaveModuleData" class="w-full py-5 bg-green-600 text-white rounded-[2rem] font-black uppercase tracking-widest hover:bg-green-700 shadow-xl shadow-green-100 active:scale-95 transition-all">
             {{ moduleFormData._index !== undefined ? 'Update' : 'Simpan' }} Data {{ activeModule.entity }}
           </button>
         </div>
       </div>
    </div>

    <!-- Modal: Assign Task -->
    <div v-if="showAssignModal" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm flex items-center justify-center p-4 z-[100]">
       <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-md overflow-hidden animate-in zoom-in duration-300">
         <div class="p-8 bg-gradient-to-br from-slate-900 to-slate-800 text-white flex justify-between items-center">
           <div>
             <h3 class="text-xl font-black uppercase tracking-tighter italic">Beri <span class="text-green-400">Tugas</span></h3>
             <p class="text-[9px] font-bold text-white/60 uppercase tracking-widest mt-1">PIC: {{ assignTaskForm.worker_name }}</p>
           </div>
           <button @click="showAssignModal = false" class="text-white/60 hover:text-white">✕</button>
         </div>
         <form @submit.prevent="handleAssignTask" class="p-8 space-y-5">
           <div class="space-y-2">
             <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest px-1">Judul Tugas</label>
             <input v-model="assignTaskForm.task" placeholder="Contoh: Distribusi Sembako" class="w-full p-4 bg-gray-50 rounded-2xl outline-none focus:ring-2 focus:ring-green-500 font-bold text-sm" required />
           </div>
           <div class="space-y-2">
             <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest px-1">Deskripsi Detail</label>
             <textarea v-model="assignTaskForm.description" rows="3" placeholder="Jelaskan detail tugas di sini..." class="w-full p-4 bg-gray-50 rounded-2xl outline-none focus:ring-2 focus:ring-green-500 font-bold text-sm resize-none"></textarea>
           </div>
           <div class="space-y-2">
             <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest px-1">Tenggat Waktu</label>
             <input v-model="assignTaskForm.deadline" type="datetime-local" class="w-full p-4 bg-gray-50 rounded-2xl outline-none focus:ring-2 focus:ring-green-500 font-bold text-sm" required />
           </div>
           <button type="submit" :disabled="assigning" class="w-full py-5 bg-green-600 text-white rounded-[2rem] font-black uppercase tracking-widest hover:bg-green-700 shadow-xl shadow-green-100 active:scale-95 transition-all">
             {{ assigning ? 'Mengirim...' : 'Kirim Tugas Sekarang' }}
           </button>
         </form>
       </div>
    </div>

    <!-- Modal: View Attachment (Image/Proof) -->
    <div v-if="showAttachmentModal" class="fixed inset-0 bg-slate-900/95 backdrop-blur-xl flex items-center justify-center p-6 z-[110]">
      <div class="relative max-w-4xl w-full">
        <button @click="showAttachmentModal = false" class="absolute -top-12 right-0 text-white text-3xl">✕</button>
        <img :src="attachmentUrl" class="w-full h-auto rounded-[2rem] shadow-2xl border border-white/10" />
      </div>
    </div>
  </div>
</div>
</div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue';
import api from '@/api';

const sidebarTabs = ref([
  { id: 'dashboard', label: 'Ringkasan V2', icon: 'fas fa-chart-pie' },
  { id: 'users', label: 'Manajemen User V2', icon: 'fas fa-users-cog' },
  { id: 'verify_zakat', label: 'Verifikasi Zakat V2', icon: 'fas fa-hand-holding-heart' },
  { id: 'settings', label: 'CMS Tampilan V2', icon: 'fas fa-paint-roller' },
  { id: 'verify_campaign', label: 'Approval Kampanye V2', icon: 'fas fa-check-double' },
  { id: 'verify_donation', label: 'Verifikasi Donasi V2', icon: 'fas fa-donate' },
  { id: 'monitoring', label: 'Monitoring & Tugas V2', icon: 'fas fa-tasks' },
]);

const activeTab = ref('dashboard');
const managementTabs = [
  { id: 'profile', label: 'Profil Lembaga', icon: 'fas fa-building' },
  { id: 'users_mgt', label: 'Users', icon: 'fas fa-user-friends' },
  { id: 'office', label: 'Kantor', icon: 'fas fa-map-marked-alt' },
  { id: 'employees', label: 'Karyawan', icon: 'fas fa-id-badge' },
  { id: 'donor_channel', label: 'Channel Donasi', icon: 'fas fa-network-wired' },
  { id: 'program_group', label: 'Rumpun Program', icon: 'fas fa-layer-group' },
  { id: 'programs', label: 'Program', icon: 'fas fa-project-diagram' },
  { id: 'payment_via', label: 'Via Bayar', icon: 'fas fa-credit-card' },
  { id: 'prospect_media', label: 'Media Prospek', icon: 'fas fa-photo-video' },
  { id: 'prospect_method', label: 'Metode Prospek', icon: 'fas fa-bullhorn' },
  { id: 'prospect_type', label: 'Jenis Prospek', icon: 'fas fa-filter' },
  { id: 'fundraisers', label: 'Fundraiser', icon: 'fas fa-user-tie' },
  { id: 'prospect_responses', label: 'Respon Prospek', icon: 'fas fa-comment-dots' },
  { id: 'targets', label: 'Target', icon: 'fas fa-bullseye' },
];

const distributionTabs = [
  { id: 'distribution_list', label: 'List Penyaluran', icon: 'fas fa-clipboard-list' },
  { id: 'distribution_dashboard', label: 'Dashboard Penyaluran', icon: 'fas fa-chart-line' },
];

const transactionTabs = [
  { id: 'donors', label: 'List Donatur', icon: 'fas fa-users' },
  { id: 'transactions', label: 'List Transaksi', icon: 'fas fa-exchange-alt' },
  { id: 'prospects', label: 'List Prospek', icon: 'fas fa-search-dollar' },
  { id: 'potential_donors', label: 'List Calon Donatur', icon: 'fas fa-user-plus' },
  { id: 'transaction_dashboard', label: 'Dashboard Transaksi', icon: 'fas fa-chart-bar' },
  { id: 'donor_dashboard', label: 'Dashboard Donatur', icon: 'fas fa-user-chart' },
  { id: 'prospect_dashboard', label: 'Dashboard Prospek', icon: 'fas fa-funnel-dollar' },
  { id: 'target_vs_achievement', label: 'Dashboard Target Vs Capaian', icon: 'fas fa-tasks' },
  { id: 'rfm_dashboard', label: 'Dashboard RFM', icon: 'fas fa-brain' },
];

const isAdminManagementOpen = ref(true);
const isDistributionManagementOpen = ref(true);
const isTransactionManagementOpen = ref(true);
const showModuleModal = ref(false);
const moduleSearchQuery = ref('');
const moduleFormData = ref({});
const moduleData = ref({});

// Module Configs — drives the generic CRUD renderer

const moduleConfigs = {
  profile:     { title: 'Profil Lembaga', entity: 'Profil', description: 'Data resmi & identitas lembaga', icon: 'fas fa-building',
    columns: [{ key: 'setting_key', label: 'Field' }, { key: 'setting_value', label: 'Nilai' }],
    fields: [{ key: 'setting_key', label: 'Nama Field' }, { key: 'setting_value', label: 'Nilai', type: 'textarea' }] },

  users_mgt:   { title: 'Users', entity: 'User', description: 'Manajemen seluruh akun pengguna', icon: 'fas fa-user-friends',
    columns: [{ key: 'name', label: 'Nama' }, { key: 'email', label: 'Email' }, { key: 'role', label: 'Peran', type: 'badge', badgeClass: (v) => v === 'admin' ? 'bg-red-50 text-red-600' : 'bg-green-50 text-green-600' }],
    fields: [{ key: 'name', label: 'Nama Lengkap' }, { key: 'email', label: 'Email', type: 'email' }, { key: 'role', label: 'Peran', type: 'select', options: ['admin', 'field_worker', 'user'] }] },

  office:      { title: 'Kantor', entity: 'Kantor', description: 'Data seluruh lokasi kantor', icon: 'fas fa-map-marked-alt',
    columns: [{ key: 'name', label: 'Nama Kantor' }, { key: 'city', label: 'Kota' }, { key: 'phone', label: 'Telepon' }],
    fields: [{ key: 'name', label: 'Nama Kantor' }, { key: 'address', label: 'Alamat', type: 'textarea' }, { key: 'city', label: 'Kota' }, { key: 'phone', label: 'Telepon' }] },

  employees:   { title: 'Karyawan', entity: 'Karyawan', description: 'Data SDM & personel aktif', icon: 'fas fa-id-badge',
    columns: [{ key: 'name', label: 'Nama' }, { key: 'position', label: 'Jabatan' }, { key: 'department', label: 'Divisi' }, { key: 'status', label: 'Status', type: 'badge', badgeClass: (v) => v === 'Aktif' ? 'bg-green-50 text-green-600' : 'bg-gray-100 text-gray-500' }],
    fields: [{ key: 'name', label: 'Nama Lengkap' }, { key: 'position', label: 'Jabatan' }, { key: 'department', label: 'Divisi' }, { key: 'status', label: 'Status', type: 'select', options: ['Aktif', 'Nonaktif'] }] },

  donor_channel: { title: 'Channel Donasi', entity: 'Channel', description: 'Saluran penerimaan donasi', icon: 'fas fa-network-wired',
    columns: [{ key: 'name', label: 'Nama Channel' }, { key: 'type', label: 'Tipe' }, { key: 'account', label: 'No. Rekening/Akun' }],
    fields: [{ key: 'name', label: 'Nama Channel' }, { key: 'type', label: 'Tipe', type: 'select', options: ['Bank Transfer', 'E-Wallet', 'QRIS', 'Tunai'] }, { key: 'account', label: 'No. Rekening/Akun' }] },

  program_group: { title: 'Rumpun Program', entity: 'Rumpun', description: 'Klaster pengelompokan program', icon: 'fas fa-layer-group',
    columns: [{ key: 'name', label: 'Nama Rumpun' }, { key: 'code', label: 'Kode' }, { key: 'description', label: 'Deskripsi' }],
    fields: [{ key: 'name', label: 'Nama Rumpun' }, { key: 'code', label: 'Kode' }, { key: 'description', label: 'Deskripsi', type: 'textarea' }] },

  programs:    { title: 'Program (Kampanye)', entity: 'Program', description: 'Program sosial & kemanusiaan aktif', icon: 'fas fa-project-diagram',
    columns: [{ key: 'title', label: 'Nama Program' }, { key: 'category', label: 'Kategori' }, { key: 'target_amount', label: 'Target Dana', type: 'currency' }, { key: 'status', label: 'Status', type: 'badge', badgeClass: (v) => v === 'approved' ? 'bg-green-50 text-green-600' : 'bg-yellow-50 text-yellow-600' }],
    fields: [{ key: 'title', label: 'Nama Program' }, { key: 'category', label: 'Kategori' }, { key: 'description', label: 'Deskripsi', type: 'textarea' }, { key: 'target_amount', label: 'Target Dana', type: 'number' }, { key: 'status', label: 'Status', type: 'select', options: ['pending', 'approved', 'rejected'] }] },

  payment_via: { title: 'Via Bayar', entity: 'Metode Bayar', description: 'Metode pembayaran yang diterima', icon: 'fas fa-credit-card',
    columns: [{ key: 'name', label: 'Nama Metode' }, { key: 'provider', label: 'Provider' }, { key: 'fee', label: 'Biaya Admin (%)' }],
    fields: [{ key: 'name', label: 'Nama Metode' }, { key: 'provider', label: 'Provider' }, { key: 'fee', label: 'Biaya Admin (%)', type: 'number' }] },

  prospect_media: { title: 'Media Prospek', entity: 'Media', description: 'Kanal pemasaran & akuisisi donatur', icon: 'fas fa-photo-video',
    columns: [{ key: 'name', label: 'Nama Media' }, { key: 'type', label: 'Tipe' }],
    fields: [{ key: 'name', label: 'Nama Media' }, { key: 'type', label: 'Tipe', type: 'select', options: ['Sosial Media', 'Email', 'Telepon', 'Tatap Muka', 'Website'] }] },

  prospect_method: { title: 'Metode Prospek', entity: 'Metode', description: 'Teknik pendekatan calon donatur', icon: 'fas fa-bullhorn',
    columns: [{ key: 'name', label: 'Nama Metode' }, { key: 'description', label: 'Deskripsi' }],
    fields: [{ key: 'name', label: 'Nama Metode' }, { key: 'description', label: 'Deskripsi', type: 'textarea' }] },

  prospect_type: { title: 'Jenis Prospek', entity: 'Jenis', description: 'Segmentasi tipe calon donatur', icon: 'fas fa-filter',
    columns: [{ key: 'name', label: 'Jenis Prospek' }, { key: 'priority', label: 'Prioritas', type: 'badge', badgeClass: (v) => v === 'Tinggi' ? 'bg-red-50 text-red-600' : v === 'Sedang' ? 'bg-yellow-50 text-yellow-600' : 'bg-green-50 text-green-600' }],
    fields: [{ key: 'name', label: 'Nama Jenis' }, { key: 'priority', label: 'Prioritas', type: 'select', options: ['Tinggi', 'Sedang', 'Rendah'] }] },

  fundraisers:  { title: 'Fundraiser', entity: 'Fundraiser', description: 'Tim penggalangan dana aktif', icon: 'fas fa-user-tie',
    columns: [{ key: 'name', label: 'Nama' }, { key: 'email', label: 'Email' }, { key: 'target', label: 'Target', type: 'currency' }, { key: 'achievement', label: 'Capaian', type: 'currency' }],
    fields: [{ key: 'name', label: 'Nama Fundraiser' }, { key: 'email', label: 'Email' }, { key: 'target', label: 'Target Dana', type: 'number' }] },

  prospects:    { title: 'List Prospek', entity: 'Prospek', description: 'Database calon donatur yang diprospek', icon: 'fas fa-search-dollar',
    columns: [{ key: 'name', label: 'Nama' }, { key: 'phone', label: 'Kontak' }, { key: 'status', label: 'Status', type: 'badge', badgeClass: (v) => v === 'Konversi' ? 'bg-green-50 text-green-600' : 'bg-green-50 text-green-600' }],
    fields: [{ key: 'name', label: 'Nama Prospek' }, { key: 'phone', label: 'No. Telepon' }, { key: 'email', label: 'Email' }, { key: 'status', label: 'Status', type: 'select', options: ['Prospek', 'Calon Donatur', 'Konversi', 'Lost'] }] },

  prospect_responses: { title: 'Respon Prospek', entity: 'Respon', description: 'Tindak lanjut & hasil pendekatan', icon: 'fas fa-comment-dots',
    columns: [{ key: 'response', label: 'Respon' }, { key: 'status', label: 'Status', type: 'badge', badgeClass: (v) => v === 'Tertarik' ? 'bg-green-50 text-green-600' : 'bg-gray-100 text-gray-500' }],
    fields: [{ key: 'response', label: 'Respon', type: 'textarea' }, { key: 'status', label: 'Status', type: 'select', options: ['Tertarik', 'Tidak Tertarik', 'Follow Up'] }] },

  targets:      { title: 'Target', entity: 'Target', description: 'Target pencapaian per periode', icon: 'fas fa-bullseye',
    columns: [{ key: 'period', label: 'Periode' }, { key: 'amount', label: 'Target', type: 'currency' }, { key: 'achievement', label: 'Capaian', type: 'currency' }],
    fields: [{ key: 'period', label: 'Periode (Bulan/Tahun)' }, { key: 'amount', label: 'Target Dana', type: 'number' }] },

  distribution_list: { title: 'List Penyaluran', entity: 'Penyaluran', description: 'Histori distribusi bantuan ke penerima', icon: 'fas fa-clipboard-list',
    columns: [{ key: 'recipient_name', label: 'Penerima' }, { key: 'amount', label: 'Nilai', type: 'currency' }, { key: 'distribution_date', label: 'Tanggal' }, { key: 'status', label: 'Status', type: 'badge', badgeClass: (v) => v === 'Tersalur' ? 'bg-green-50 text-green-600' : 'bg-yellow-50 text-yellow-600' }],
    fields: [{ key: 'recipient_name', label: 'Nama Penerima' }, { key: 'amount', label: 'Nilai Bantuan', type: 'number' }, { key: 'distribution_date', label: 'Tanggal', type: 'date' }, { key: 'status', label: 'Status', type: 'select', options: ['Tersalur', 'Proses'] }] },

  donors:       { title: 'List Donatur', entity: 'Donatur', description: 'Database seluruh donatur terdaftar', icon: 'fas fa-users',
    columns: [{ key: 'name', label: 'Nama' }, { key: 'email', label: 'Email' }, { key: 'phone', label: 'Telepon' }],
    fields: [{ key: 'name', label: 'Nama Lengkap' }, { key: 'email', label: 'Email', type: 'email' }, { key: 'phone', label: 'Telepon' }] },

  transactions: { title: 'List Transaksi', entity: 'Transaksi', description: 'Riwayat seluruh transaksi donasi', icon: 'fas fa-exchange-alt',
    columns: [{ key: 'donor_name', label: 'Donatur' }, { key: 'amount', label: 'Jumlah', type: 'currency' }, { key: 'payment_status', label: 'Status', type: 'badge', badgeClass: (v) => v === 'Success' ? 'bg-green-50 text-green-600' : v === 'Pending' ? 'bg-yellow-50 text-yellow-600' : 'bg-red-50 text-red-600' }],
    fields: [] },

  potential_donors: { title: 'List Calon Donatur', entity: 'Calon Donatur', description: 'Prospek tervalidasi siap dikonversi', icon: 'fas fa-user-plus',
    columns: [{ key: 'name', label: 'Nama' }, { key: 'phone', label: 'Kontak' }, { key: 'status', label: 'Status', type: 'badge' }],
    fields: [{ key: 'name', label: 'Nama' }, { key: 'phone', label: 'Kontak' }, { key: 'status', label: 'Status', type: 'select', options: ['Prospek', 'Calon Donatur', 'Konversi', 'Lost'] }] },

  distribution_dashboard: { title: 'Dashboard Penyaluran', entity: 'Laporan', description: 'Statistik & visualisasi distribusi bantuan', icon: 'fas fa-chart-line', isDashboard: true, fields: [] },
  transaction_dashboard: { title: 'Dashboard Transaksi', entity: 'Laporan', description: 'Analitik & ringkasan transaksi keuangan', icon: 'fas fa-chart-bar', isDashboard: true, fields: [] },
  donor_dashboard: { title: 'Dashboard Donatur', entity: 'Laporan', description: 'Analitik perilaku & segmentasi donatur', icon: 'fas fa-user-chart', isDashboard: true, fields: [] },
  prospect_dashboard: { title: 'Dashboard Prospek', entity: 'Laporan', description: 'Analitik pipeline & konversi prospek', icon: 'fas fa-funnel-dollar', isDashboard: true, fields: [] },
  target_vs_achievement: { title: 'Target vs Capaian', entity: 'Laporan', description: 'Perbandingan target & realisasi per periode', icon: 'fas fa-tasks', isDashboard: true, fields: [] },
  rfm_dashboard: { title: 'Dashboard RFM', entity: 'Laporan', description: 'Analitik Recency, Frequency, Monetary donatur', icon: 'fas fa-brain', isDashboard: true, fields: [] },
};

const activeModule = computed(() => moduleConfigs[activeTab.value] || null);
const filteredModuleData = computed(() => {
  const data = moduleData.value[activeTab.value] || [];
  if (!moduleSearchQuery.value) return data;
  
  const query = moduleSearchQuery.value.toLowerCase();
  return data.filter(item => 
    Object.values(item).some(val => 
      String(val).toLowerCase().includes(query)
    )
  );
});

const loading = ref(true);
const stats = ref({});
const users = ref([]);
const pendingCampaigns = ref([]);
const pendingDonations = ref([]);

const pendingZakats = ref([]);
const settingsData = ref({});
const savingSettings = ref(false);

const showAddModal = ref(false);
const formData = reactive({ name: '', email: '', password: '', role: 'field_worker' });

// Monitoring State
const monitoringSubTab = ref('attendance');
const workerPerformance = ref([]);
const allAttendances = ref([]);
const allTasks = ref([]);
const showAssignModal = ref(false);
const assigning = ref(false);
const assignTaskForm = reactive({ user_id: null, worker_name: '', task: '', description: '', deadline: '' });
const showAttachmentModal = ref(false);
const attachmentUrl = ref('');

// Modal Notes State
const showNoteModal = ref(false);
const processingDecision = ref(false);
const noteData = reactive({ type: '', id: null, status: '', note: '' });

const handleSaveModuleData = async () => {
  const tab = activeTab.value;
  const payload = { ...moduleFormData.value };
  const id = payload.id;
  delete payload._index;
  delete payload.id;
  
  try {
    if (id) {
      await api.put(`/admin/master/${tab}/${id}`, payload);
    } else {
      await api.post(`/admin/master/${tab}`, payload);
    }
    showModuleModal.value = false;
    moduleFormData.value = {};
    fetchModuleData();
  } catch (e) {
    alert('Gagal menyimpan data modul.');
  }
};

const editModuleData = (row, index) => {
  moduleFormData.value = { ...row, _index: index };
  showModuleModal.value = true;
};

const handleDeleteModuleData = async (id) => {
  if (confirm(`Yakin ingin menghapus data ini?`)) {
    try {
      await api.delete(`/admin/master/${activeTab.value}/${id}`);
      fetchModuleData();
    } catch (e) {
      alert('Gagal menghapus data.');
    }
  }
};

const fetchModuleData = async () => {
  const tab = activeTab.value;
  if (!moduleConfigs[tab] || moduleConfigs[tab].isDashboard) return;
  
  try {
    const res = await api.get(`/admin/master/${tab}`);
    moduleData.value[tab] = res.data;
  } catch (e) {
    console.error(`Error fetching module ${tab}:`, e);
  }
};

const fetchMonitoringData = async () => {
  try {
    const [perf, att, tasks] = await Promise.all([
      api.get('/admin/worker-performance'),
      api.get('/admin/attendances'),
      api.get('/admin/tasks')
    ]);
    workerPerformance.value = perf.data;
    allAttendances.value = att.data;
    allTasks.value = tasks.data;
  } catch (e) {
    console.error('Error fetching monitoring data:', e);
  }
};

const openAssignTask = (worker) => {
  assignTaskForm.user_id = worker.id;
  assignTaskForm.worker_name = worker.name;
  assignTaskForm.task = '';
  assignTaskForm.description = '';
  assignTaskForm.deadline = '';
  showAssignModal.value = true;
};

const handleAssignTask = async () => {
  assigning.value = true;
  try {
    await api.post('/admin/tasks', assignTaskForm);
    alert('Tugas berhasil diberikan!');
    showAssignModal.value = false;
    fetchMonitoringData();
  } catch (e) {
    alert('Gagal memberikan tugas.');
  } finally {
    assigning.value = false;
  }
};

const viewAttachment = (url) => {
  attachmentUrl.value = url;
  showAttachmentModal.value = true;
};

const formatTime = (dateStr) => {
  if (!dateStr) return '';
  return new Date(dateStr).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
};

const formatDate = (dateStr) => {
  if (!dateStr) return '';
  return new Date(dateStr).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
};

const fetchData = async () => {
  loading.value = true;
  try {
    const [statsRes, usersRes, campRes, donRes, settingsRes, zakatRes] = await Promise.all([
      api.get('/admin/stats'),
      api.get('/admin/users'),
      api.get('/admin/pending-campaigns'),
      api.get('/admin/pending-donations'),
      api.get('/settings'),
      api.get('/zakats/pending')
    ]);
    stats.value = statsRes.data;
    users.value = usersRes.data;
    pendingCampaigns.value = campRes.data;
    pendingDonations.value = donRes.data;
    settingsData.value = settingsRes.data;
    pendingZakats.value = zakatRes.data;
    
    // Fetch initial module data if applicable
    fetchModuleData();
  } catch (error) {
    console.error(error);
  } finally {
    loading.value = false;
  }
};

// Watch for tab changes to fetch specific module data
import { watch } from 'vue';
watch(activeTab, (newTab) => {
  if (moduleConfigs[newTab]) {
    fetchModuleData();
  }
  if (newTab === 'monitoring') {
    fetchMonitoringData();
  }
});

const handleApproveCampaign = async (id) => {
  if (confirm('Setujui kampanye ini?')) {
    await api.post(`/admin/approve-campaign/${id}`);
    fetchData();
  }
};

const triggerNoteModal = (type, id, status) => {
   noteData.type = type;
   noteData.id = id;
   noteData.status = status;
   noteData.note = '';
   showNoteModal.value = true;
};

const submitDecisionWithNote = async () => {
   processingDecision.value = true;
   try {
      if (noteData.type === 'donation') {
         await api.post(`/admin/verify-donation/${noteData.id}`, { status: noteData.status, admin_note: noteData.note });

      } else if (noteData.type === 'zakat') {
         await api.post(`/zakats/verify/${noteData.id}`, { status: noteData.status, admin_note: noteData.note });
      }
      showNoteModal.value = false;
      fetchData();
   } catch (e) {
      alert('Gagal memproses keputusan.');
   } finally {
      processingDecision.value = false;
   }
};

const handleAddUser = async () => {
  try {
    await api.post('/admin/users', formData);
    showAddModal.value = false;
    formData.name = ''; formData.email = ''; formData.password = '';
    fetchData();
  } catch (e) {
    alert('Gagal menambah pahlawan baru.');
  }
};

const handleDeleteUser = async (id) => {
  if (confirm('Yakin ingin menghapus akun ini secara permanen?')) {
    try {
       await api.delete(`/admin/users/${id}`);
       fetchData();
    } catch (e) {
       alert('Gagal menghapus user.');
    }
  }
}

const handleSaveSettings = async () => {
  savingSettings.value = true;
  try {
    await api.post('/settings', settingsData.value);
    alert('Tampilan website berhasil diperbarui!');
  } catch (e) {
    alert('Gagal menyimpan pengaturan.');
  } finally {
    savingSettings.value = false;
  }
};

onMounted(() => {
  console.log('Admin Dashboard Mounted, Tabs:', sidebarTabs.value.length);
  activeTab.value = 'dashboard';
  fetchData();
  fetchMonitoringData();
});
</script>

<style scoped>
.no-scrollbar::-webkit-scrollbar { display: none; }
.no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

.custom-scrollbar::-webkit-scrollbar {
  width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
  background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
  background: #94a3b8;
}
</style>