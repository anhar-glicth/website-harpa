<template>
  <div class="min-h-screen bg-gray-50/50">

    <!-- ===== MOBILE HEADER BAR ===== -->
    <div class="lg:hidden sticky top-0 z-[90] bg-white/95 backdrop-blur-xl border-b border-gray-100 shadow-sm px-4 py-3 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <div class="w-8 h-8 bg-green-600 text-white rounded-xl flex items-center justify-center text-xs">
          <i class="fas fa-user-shield"></i>
        </div>
        <div>
          <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none">Admin Panel</p>
          <p class="text-[11px] font-black text-gray-900 uppercase tracking-tight leading-tight">{{ allNavTabs.find(t => t.id === currentAdminTab)?.label || 'Dashboard' }}</p>
        </div>
      </div>
      <div class="flex items-center gap-2">
        <button @click="showAddModal = true" class="px-3 py-1.5 bg-green-600 text-white rounded-lg text-[9px] font-black uppercase tracking-wider">
          + Staff
        </button>
        <button @click="isMobileMenuOpen = true" class="w-9 h-9 bg-gray-50 text-gray-600 rounded-xl flex items-center justify-center hover:bg-green-50 hover:text-green-600 transition-all">
          <i class="fas fa-bars text-sm"></i>
        </button>
      </div>
    </div>

    <!-- ===== MOBILE MENU DRAWER (Bottom Sheet) ===== -->
    <Teleport to="body">
      <Transition name="drawer">
        <div v-if="isMobileMenuOpen" class="fixed inset-0 z-[150] flex flex-col justify-end lg:hidden">
          <!-- Backdrop -->
          <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="isMobileMenuOpen = false"></div>
          <!-- Sheet -->
          <div class="relative bg-white rounded-t-[2rem] max-h-[85vh] flex flex-col overflow-hidden shadow-2xl">
            <!-- Sheet Handle -->
            <div class="flex justify-center pt-3 pb-1 flex-shrink-0">
              <div class="w-10 h-1 bg-gray-200 rounded-full"></div>
            </div>
            <!-- Sheet Header -->
            <div class="px-6 py-3 flex justify-between items-center border-b border-gray-50 flex-shrink-0">
              <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">Navigasi Admin</span>
              <button @click="isMobileMenuOpen = false" class="w-7 h-7 bg-gray-100 rounded-lg flex items-center justify-center text-gray-500 text-xs">
                <i class="fas fa-times"></i>
              </button>
            </div>
            <!-- Sheet Content: Scrollable -->
            <div class="overflow-y-auto flex-1 p-4 space-y-2 pb-8">

              <!-- Menu Utama -->
              <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest px-2 pt-2">Menu Utama</p>
              <button
                v-for="tab in sidebarTabs" :key="tab.id"
                @click="currentAdminTab = tab.id; isMobileMenuOpen = false"
                class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl text-[10px] font-black uppercase tracking-wider transition-all text-left"
                :class="currentAdminTab === tab.id ? 'bg-green-600 text-white shadow-lg shadow-green-200/50' : 'text-gray-500 hover:bg-gray-50'"
              >
                <div :class="['w-8 h-8 rounded-xl flex items-center justify-center text-xs flex-shrink-0', currentAdminTab === tab.id ? 'bg-white/20' : 'bg-gray-100']">
                  <i :class="tab.icon"></i>
                </div>
                <span>{{ tab.label }}</span>
              </button>

              <!-- Admin Management -->
              <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest px-2 pt-4">Admin Management</p>
              <button
                v-for="tab in managementTabs" :key="tab.id"
                @click="currentAdminTab = tab.id; isMobileMenuOpen = false"
                class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl text-[10px] font-black uppercase tracking-wider transition-all text-left"
                :class="currentAdminTab === tab.id ? 'bg-green-600 text-white shadow-lg shadow-green-200/50' : 'text-gray-500 hover:bg-gray-50'"
              >
                <div :class="['w-8 h-8 rounded-xl flex items-center justify-center text-xs flex-shrink-0', currentAdminTab === tab.id ? 'bg-white/20' : 'bg-gray-100']">
                  <i :class="tab.icon"></i>
                </div>
                <span>{{ tab.label }}</span>
              </button>

              <!-- Distribution Management -->
              <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest px-2 pt-4">Distribution Management</p>
              <button
                v-for="tab in distributionTabs" :key="tab.id"
                @click="currentAdminTab = tab.id; isMobileMenuOpen = false"
                class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl text-[10px] font-black uppercase tracking-wider transition-all text-left"
                :class="currentAdminTab === tab.id ? 'bg-green-600 text-white shadow-lg shadow-green-200/50' : 'text-gray-500 hover:bg-gray-50'"
              >
                <div :class="['w-8 h-8 rounded-xl flex items-center justify-center text-xs flex-shrink-0', currentAdminTab === tab.id ? 'bg-white/20' : 'bg-gray-100']">
                  <i :class="tab.icon"></i>
                </div>
                <span>{{ tab.label }}</span>
              </button>

              <!-- Transaction Management -->
              <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest px-2 pt-4">Transaction Management</p>
              <button
                v-for="tab in transactionTabs" :key="tab.id"
                @click="currentAdminTab = tab.id; isMobileMenuOpen = false"
                class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl text-[10px] font-black uppercase tracking-wider transition-all text-left"
                :class="currentAdminTab === tab.id ? 'bg-green-600 text-white shadow-lg shadow-green-200/50' : 'text-gray-500 hover:bg-gray-50'"
              >
                <div :class="['w-8 h-8 rounded-xl flex items-center justify-center text-xs flex-shrink-0', currentAdminTab === tab.id ? 'bg-white/20' : 'bg-gray-100']">
                  <i :class="tab.icon"></i>
                </div>
                <span>{{ tab.label }}</span>
              </button>

              <!-- Quick Links -->
              <div class="pt-4 grid grid-cols-2 gap-2">
                <router-link to="/profile" @click="isMobileMenuOpen = false" class="flex items-center justify-center gap-2 py-3 bg-gray-50 text-gray-600 rounded-2xl font-black uppercase text-[9px] tracking-widest">
                  <i class="fas fa-user-circle text-[8px]"></i> Edit Profil
                </router-link>
                <router-link to="/dashboard" @click="isMobileMenuOpen = false" class="flex items-center justify-center gap-2 py-3 bg-slate-900 text-white rounded-2xl font-black uppercase text-[9px] tracking-widest">
                  <i class="fas fa-arrow-left text-[8px]"></i> Dashboard
                </router-link>
                <button @click="isMobileMenuOpen = false; showLogoutModal = true" class="col-span-2 flex items-center justify-center gap-2 py-3 bg-red-50 text-red-500 rounded-2xl font-black uppercase text-[9px] tracking-widest">
                  <i class="fas fa-power-off text-[8px]"></i> Keluar Sistem
                </button>
              </div>

            </div>
          </div>
        </div>
      </Transition>
    </Teleport>

    <div class="max-w-7xl mx-auto px-3 sm:px-4 py-4 lg:py-10">
      <div class="flex flex-col lg:flex-row gap-8 items-start">
        
        <!-- Sidebar Kiri: Admin Specific -->
        <!-- Desktop Sidebar: hidden on mobile -->
        <div class="hidden lg:block w-72 space-y-6 flex-shrink-0 sticky top-28">
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
                    <button @click="currentAdminTab = 'settings'" class="p-2.5 bg-gray-50 text-gray-400 rounded-xl hover:text-green-600 hover:bg-green-50 transition-all shadow-sm">
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
               <!-- Main Menu Group (Collapsible) -->
               <div>
                  <div 
                    @click="isMainMenuOpen = !isMainMenuOpen"
                    class="bg-slate-800 px-6 py-4 flex justify-between items-center text-white cursor-pointer hover:bg-slate-900 transition-colors"
                  >
                     <span class="text-[11px] font-bold uppercase tracking-wider">Menu Utama</span>
                     <i :class="['fas fa-chevron-up text-[10px] transition-transform duration-300', !isMainMenuOpen ? 'rotate-180' : '']"></i>
                  </div>
                  <div v-show="isMainMenuOpen" class="p-3 space-y-1 max-h-96 overflow-y-auto custom-scrollbar animate-in slide-in-from-top-2 duration-300">
                     <button
                       v-for="tab in sidebarTabs"
                       :key="tab.id"
                       @click="currentAdminTab = tab.id"
                       class="w-full flex items-center gap-4 px-5 py-4 rounded-3xl text-[10px] font-black uppercase tracking-widest transition-all duration-300 group border-2 border-transparent text-left outline-none"
                       :class="currentAdminTab === tab.id 
                         ? 'bg-green-600 border-green-600 text-white shadow-2xl shadow-green-200/50 scale-[1.02]' 
                         : 'text-gray-400 hover:bg-white hover:text-green-600 hover:border-green-100 hover:shadow-lg hover:shadow-slate-100'"
                     >
                       <div :class="['w-9 h-9 rounded-2xl flex items-center justify-center transition-all duration-300', currentAdminTab === tab.id ? 'bg-white/20 text-white rotate-12' : 'bg-gray-50 text-gray-400 group-hover:bg-green-50 group-hover:text-green-600 group-hover:rotate-12']">
                          <i :class="[tab.icon, 'text-[11px]']"></i>
                       </div>
                       <span class="whitespace-nowrap">{{ tab.label }}</span>
                     </button>
                  </div>
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
                      @click="currentAdminTab = tab.id"
                      class="w-full flex items-center gap-4 px-6 py-3.5 text-[12px] font-semibold transition-all text-left border-l-4 border-transparent"
                      :class="currentAdminTab === tab.id 
                        ? 'bg-green-600 text-white shadow-lg' 
                        : 'text-gray-500 hover:bg-gray-50'"
                    >
                      <i :class="[tab.icon, 'w-5 text-center transition-colors', currentAdminTab === tab.id ? 'text-white' : 'text-gray-400']"></i>
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
                      @click="currentAdminTab = tab.id"
                      class="w-full flex items-center gap-4 px-6 py-3.5 text-[12px] font-semibold transition-all text-left border-l-4 border-transparent"
                      :class="currentAdminTab === tab.id 
                        ? 'bg-green-600 text-white shadow-lg' 
                        : 'text-gray-500 hover:bg-gray-50'"
                    >
                      <i :class="[tab.icon, 'w-5 text-center transition-colors', currentAdminTab === tab.id ? 'text-white' : 'text-gray-400']"></i>
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
                      @click="currentAdminTab = tab.id"
                      class="w-full flex items-center gap-4 px-6 py-3.5 text-[12px] font-semibold transition-all text-left border-l-4 border-transparent"
                      :class="currentAdminTab === tab.id 
                        ? 'bg-green-600 text-white shadow-lg' 
                        : 'text-gray-500 hover:bg-gray-50'"
                    >
                      <i :class="[tab.icon, 'w-5 text-center transition-colors', currentAdminTab === tab.id ? 'text-white' : 'text-gray-400']"></i>
                      {{ tab.label }}
                    </button>
                 </div>
              </div>

           </nav>

            <!-- Link Kembali -->
            <div class="space-y-2">
              <router-link to="/profile" class="flex items-center justify-center gap-3 w-full py-4 bg-white border-2 border-slate-100 text-slate-600 rounded-2xl font-black uppercase text-[10px] tracking-widest hover:border-green-600 hover:text-green-600 transition-all">
                <i class="fas fa-user-circle text-[8px]"></i>
                Edit Profil Saya
              </router-link>

              <router-link to="/dashboard" class="flex items-center justify-center gap-3 w-full py-4 bg-slate-900 text-white rounded-2xl font-black uppercase text-[10px] tracking-widest hover:bg-green-600 transition-all shadow-lg">
                <i class="fas fa-arrow-left text-[8px]"></i>
                Dashboard Utama
              </router-link>
              
              <button @click="showLogoutModal = true" class="flex items-center justify-center gap-3 w-full py-4 bg-white border-2 border-red-50 text-red-500 rounded-2xl font-black uppercase text-[10px] tracking-widest hover:bg-red-50 hover:border-red-100 transition-all">
                <i class="fas fa-power-off text-[8px]"></i>
                Keluar Sistem
              </button>
            </div>
         </div>

        <main class="flex-grow min-w-0 space-y-6 lg:space-y-8 animate-in fade-in slide-in-from-right-4 duration-700 w-full overflow-hidden">
          <!-- Desktop Header: hidden on mobile (mobile uses sticky top bar instead) -->
          <header class="hidden lg:flex bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm flex-col md:flex-row justify-between items-center gap-6">
            <div class="space-y-1 text-center md:text-left">
              <h1 class="text-3xl font-black text-gray-900 tracking-tighter uppercase italic">ADMIN <span class="text-green-600">PANEL</span></h1>
              <p class="text-[10px] text-gray-400 font-bold uppercase tracking-[0.2em]">Pusat Kendali & CMS HumanityCare</p>
            </div>
            <div class="flex gap-4">
               <div class="text-right">
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

          <div v-else class="space-y-6 lg:space-y-8 animate-in zoom-in-95 duration-500 pb-28 lg:pb-0">
            
            <!-- Dashboard Tab -->
            <div v-if="currentAdminTab === 'dashboard'" class="space-y-8">
              <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
                <div class="bg-white p-6 md:p-8 rounded-[2.5rem] border border-gray-100 shadow-sm bg-gradient-to-br from-white to-green-50/30">
                  <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Total Donasi</p>
                  <p class="text-2xl md:text-3xl font-black text-green-600 tracking-tighter">Rp {{ stats.totalDonations?.toLocaleString() || 0 }}</p>
                </div>
                <div class="bg-white p-6 md:p-8 rounded-[2.5rem] border border-gray-100 shadow-sm">
                  <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Donatur</p>
                  <p class="text-3xl font-black text-blue-600 tracking-tighter">{{ stats.totalDonors || 0 }}</p>
                </div>
                <div class="bg-white p-6 md:p-8 rounded-[2.5rem] border border-gray-100 shadow-sm">
                  <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Kampanye Aktif</p>
                  <p class="text-3xl font-black text-orange-600 tracking-tighter">{{ stats.activeCampaigns || 0 }}</p>
                </div>
                <div class="bg-white p-6 md:p-8 rounded-[2.5rem] border border-gray-100 shadow-sm">
                  <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Target Tercapai</p>
                  <p class="text-3xl font-black text-green-600 tracking-tighter">{{ stats.successCampaigns || 0 }}</p>
                </div>
              </div>

              <!-- Real Charts Section -->
              <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Line Chart: Monthly Donation Trends -->
                <div class="lg:col-span-2 bg-white rounded-[2.5rem] border border-gray-100 shadow-sm p-8 flex flex-col">
                  <div class="flex justify-between items-center mb-8">
                    <div>
                      <h3 class="text-[11px] font-black text-gray-900 uppercase tracking-widest">Tren Donasi Bulanan</h3>
                      <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mt-1">Pertumbuhan 6 bulan terakhir</p>
                    </div>
                    <div class="flex gap-2">
                      <span class="w-3 h-3 bg-green-500 rounded-full"></span>
                      <span class="text-[9px] font-black text-gray-400 uppercase">Donasi Terverifikasi</span>
                    </div>
                  </div>
                  <div class="h-[300px] w-full">
                    <LineChart v-if="stats.monthlyDonations" :data="stats.monthlyDonations" />
                    <div v-else class="h-full flex items-center justify-center text-gray-300 text-[10px] font-black uppercase italic">Memuat grafik...</div>
                  </div>
                </div>

                <!-- Doughnut Chart: Category Distribution -->
                <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm p-8 flex flex-col">
                  <div class="mb-8">
                    <h3 class="text-[11px] font-black text-gray-900 uppercase tracking-widest">Distribusi Program</h3>
                    <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mt-1">Berdasarkan kategori</p>
                  </div>
                  <div class="h-[300px] w-full">
                    <DoughnutChart v-if="stats.categoryDistribution" :data="stats.categoryDistribution" />
                    <div v-else class="h-full flex items-center justify-center text-gray-300 text-[10px] font-black uppercase italic">Memuat grafik...</div>
                  </div>
                </div>
              </div>

              <!-- Recent Activities (Keep existing but inside the dashboard tab) -->
              <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden mt-6">
                <div class="px-8 py-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
                  <h3 class="text-[11px] font-black text-gray-900 uppercase tracking-widest">Aktivitas Terbaru</h3>
                  <span class="px-3 py-1 bg-green-50 text-green-600 rounded-full text-[9px] font-black uppercase">Live Update</span>
                </div>
                <div class="divide-y divide-gray-50">
                  <div v-for="(act, idx) in stats.recent_activities" :key="idx" class="px-8 py-5 flex items-center justify-between hover:bg-gray-50/30 transition-colors">
                    <div class="flex items-center gap-5">
                      <div :class="['w-10 h-10 rounded-xl flex items-center justify-center text-xs', act.type === 'Donasi' ? 'bg-blue-50 text-blue-600' : 'bg-orange-50 text-orange-600']">
                        <i :class="act.type === 'Donasi' ? 'fas fa-hand-holding-usd' : 'fas fa-bullhorn'"></i>
                      </div>
                      <div>
                        <p class="text-[12px] font-black text-gray-900 leading-tight">{{ act.name }}</p>
                        <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mt-0.5">{{ act.type }} • {{ act.detail }}</p>
                      </div>
                    </div>
                    <div class="text-right">
                      <p class="text-[10px] font-black text-gray-900">{{ formatDate(act.date) }}</p>
                      <p class="text-[9px] text-gray-400 font-medium">{{ formatTime(act.date) }}</p>
                    </div>
                  </div>
                  <div v-if="!stats.recent_activities?.length" class="py-12 text-center text-gray-400 text-[10px] font-black uppercase tracking-widest">Belum ada aktivitas hari ini</div>
                </div>
              </div>
            </div>

            <!-- Generic Module Renderer (Table or Dashboard) -->
            <template v-if="activeModule || managementTabs.some(t => t.id === currentAdminTab) || distributionTabs.some(t => t.id === currentAdminTab) || transactionTabs.some(t => t.id === currentAdminTab)">

              <!-- Donor Dashboard Special View -->
              <DonorDashboard v-if="currentAdminTab === 'donor_dashboard'" />
              <RFMDashboard v-if="currentAdminTab === 'rfm_dashboard'" />
              <TargetDashboard v-if="currentAdminTab === 'target_dashboard'" />
              <ProspectDashboard v-if="currentAdminTab === 'prospect_dashboard'" />
              <TransactionDashboard v-if="currentAdminTab === 'transaction_dashboard'" />
              <DistributionDashboard v-if="currentAdminTab === 'distribution_dashboard'" />

              <!-- Other modules -->
              <div v-else-if="activeModule" class="space-y-6">
                
                <!-- Dashboard View -->
                <div v-if="activeModule.isDashboard" class="space-y-8">
                  <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm p-8 flex flex-col md:flex-row justify-between items-center gap-5">
                    <div class="flex items-center gap-5">
                      <div class="w-14 h-14 bg-green-50 text-green-600 rounded-2xl flex items-center justify-center text-xl shadow-sm"><i :class="activeModule.icon"></i></div>
                      <div>
                        <h2 class="text-2xl font-black text-gray-900 tracking-tighter uppercase italic">{{ activeModule.title }}</h2>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{{ activeModule.description }}</p>
                      </div>
                    </div>
                    <div class="flex gap-3">
                       <button @click="exportToCSV" class="px-5 py-3 bg-gray-50 text-gray-400 rounded-xl text-[10px] font-black uppercase tracking-widest hover:text-green-600 transition-all">Export CSV</button>
                       <button class="px-5 py-3 bg-slate-900 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-gray-200">Filter Periode</button>
                    </div>
                  </div>

                  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-2 bg-white rounded-[2.5rem] border border-gray-100 shadow-sm p-8 h-[400px] flex flex-col items-center justify-center relative overflow-hidden">
                       <div class="absolute inset-0 bg-gradient-to-br from-green-50/20 to-transparent pointer-events-none"></div>
                       <i :class="activeModule.icon" class="text-6xl text-green-100 mb-6"></i>
                       <p class="text-lg font-black text-gray-900 tracking-tight">Visualisasi Data {{ activeModule.title }}</p>
                       <p class="text-xs text-gray-400 font-medium">Grafik analitik akan muncul di sini setelah sinkronisasi data selesai.</p>
                       
                       <!-- Mock Chart Bars -->
                       <div class="flex gap-4 items-end mt-10 h-32">
                          <div v-for="i in 8" :key="i" :style="{ height: (20 + Math.random() * 80) + '%' }" class="w-6 bg-green-500/20 rounded-t-lg hover:bg-green-500 transition-all duration-500"></div>
                       </div>
                    </div>
                    
                    <div class="space-y-6">
                       <div v-for="(kpi, i) in moduleStats.kpis" :key="i" class="bg-white rounded-[2rem] border border-gray-100 shadow-sm p-6">
                          <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">{{ kpi.label }}</p>
                          <p class="text-2xl font-black text-gray-900 tracking-tighter">{{ kpi.value }}</p>
                          <div class="w-full h-1.5 bg-gray-50 rounded-full mt-3">
                             <div class="h-full bg-green-600 rounded-full transition-all duration-1000" :style="{ width: kpi.percentage + '%' }"></div>
                          </div>
                       </div>
                    </div>
                  </div>
                </div>

                <!-- Table View -->
                <div v-else class="space-y-6">
                  <!-- Module Header -->
                  <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm p-8 flex flex-col md:flex-row justify-between items-center gap-5">
                    <div class="flex items-center gap-5">
                      <div class="w-14 h-14 bg-green-50 text-green-600 rounded-2xl flex items-center justify-center text-xl shadow-sm"><i :class="activeModule.icon"></i></div>
                      <div>
                        <h2 class="text-2xl font-black text-gray-900 tracking-tighter uppercase italic">{{ activeModule.title }}</h2>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{{ activeModule.description }}</p>
                      </div>
                    </div>
                    <div class="flex items-center gap-3">
                      <button @click="exportToCSV" class="bg-slate-900 text-white px-6 py-2.5 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-800 transition-all flex items-center gap-2">
                        <i class="fas fa-file-export"></i> Ekspor CSV
                      </button>
                      <button @click="openModuleModal()" class="bg-green-600 text-white px-6 py-2.5 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-green-700 transition-all shadow-lg shadow-green-500/20 flex items-center gap-2">
                        <i class="fas fa-plus"></i> Tambah {{ activeModule.entity }}
                      </button>
                    </div>
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
                            <td v-for="col in activeModule.columns" :key="col.key" class="px-6 py-4 whitespace-nowrap">
                              <span v-if="col.type==='badge'" :class="['inline-flex px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest', col.badgeClass?.(row[col.key]) || 'bg-green-50 text-green-600']">{{ row[col.key]||'-' }}</span>
                              <span v-else-if="col.type==='currency'" class="text-[11px] font-black text-green-600">Rp {{ Number(row[col.key]||0).toLocaleString('id-ID') }}</span>
                              <span v-else-if="col.type==='date'" class="text-[11px] font-semibold text-gray-700">{{ formatDate(row[col.key]) }}</span>
                              <span v-else-if="col.type==='image'" class="flex items-center">
                                <img v-if="row[col.key]" :src="row[col.key]" class="w-8 h-8 object-cover rounded-lg border border-gray-200" />
                                <span v-else class="text-gray-300">-</span>
                              </span>
                              <span v-else class="text-[11px] font-semibold text-gray-700">{{ row[col.key]||'-' }}</span>
                            </td>
                            <td class="px-6 py-4">
                              <div class="flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <template v-if="activeModule.actions">
                                  <button v-for="action in activeModule.actions" :key="action.icon" v-show="!action.show || action.show(row)" @click="action.click(row) === 'printReceipt' ? printReceipt(row) : null" :class="['w-8 h-8 rounded-xl hover:text-white transition-all flex items-center justify-center', action.class]">
                                    <i :class="action.icon" class="text-[9px]"></i>
                                  </button>
                                </template>
                                <button @click="editModuleData(row, idx)" class="w-8 h-8 bg-green-50 text-green-600 rounded-xl hover:bg-green-600 hover:text-white transition-all flex items-center justify-center"><i class="fas fa-pen text-[9px]"></i></button>
                                <button @click="handleDeleteModuleData(row)" class="w-8 h-8 bg-red-50 text-red-500 rounded-xl hover:bg-red-500 hover:text-white transition-all flex items-center justify-center"><i class="fas fa-trash text-[9px]"></i></button>
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
              </div>
            </template>



            <!-- User Management Tab -->
            <div v-if="currentAdminTab === 'users'" class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
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
                          <div class="flex gap-2 justify-center">
                            <button @click="handleEditUser(u)" class="text-blue-500 hover:text-blue-700 p-2 transition active:scale-90" title="Edit Akun">
                               <i class="fas fa-edit"></i>
                            </button>
                            <button @click="handleDeleteUser(u.id)" class="text-red-500 hover:text-red-700 p-2 transition active:scale-90" title="Hapus Akun">
                               <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 6h18m-2 0v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6m3 0V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                            </button>
                          </div>
                        </td>
                     </tr>
                   </tbody>
                 </table>
               </div>
            </div>

            <!-- Zakat Approval Tab (NEW) -->
            <div v-if="currentAdminTab === 'verify_zakat'" class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
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

            <!-- Profil Lembaga Tab (New Dedicated Form) -->
            <div v-if="currentAdminTab === 'profile'" class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
               <div class="p-8 border-b border-gray-50 bg-green-50/20 flex justify-between items-center">
                  <div>
                    <h3 class="font-black text-gray-900 uppercase tracking-tighter">Profil Resmi <span class="text-green-600">Lembaga</span></h3>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">Identitas resmi HumanityCare</p>
                  </div>
                  <div class="w-12 h-12 bg-white rounded-2xl shadow-sm flex items-center justify-center text-green-600 border border-green-50">
                    <i class="fas fa-building text-xl"></i>
                  </div>
               </div>
               <div class="p-8 space-y-10">
                  <!-- Identity Section -->
                  <div class="space-y-6">
                     <h4 class="text-[10px] font-black uppercase tracking-widest text-green-600 border-l-4 border-green-600 pl-4">Identitas & Kontak</h4>
                     <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                           <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Nama Lembaga</label>
                           <input v-model="settingsData.inst_name" class="w-full p-4 bg-gray-50 rounded-2xl border-none outline-none focus:ring-2 focus:ring-green-500 font-bold text-xs" placeholder="HumanityCare Indonesia" />
                        </div>
                        <div class="space-y-2">
                           <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Email Resmi</label>
                           <input v-model="settingsData.inst_email" type="email" class="w-full p-4 bg-gray-50 rounded-2xl border-none outline-none focus:ring-2 focus:ring-green-500 font-bold text-xs" placeholder="kontak@humanitycare.org" />
                        </div>
                        <div class="space-y-2">
                           <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Telepon / WhatsApp</label>
                           <input v-model="settingsData.inst_phone" class="w-full p-4 bg-gray-50 rounded-2xl border-none outline-none focus:ring-2 focus:ring-green-500 font-bold text-xs" placeholder="0812-xxxx-xxxx" />
                        </div>
                        <div class="space-y-2">
                           <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Legalitas (NPWP/SK)</label>
                           <input v-model="settingsData.inst_legal" class="w-full p-4 bg-gray-50 rounded-2xl border-none outline-none focus:ring-2 focus:ring-green-500 font-bold text-xs" placeholder="NPWP: 01.xxx.xxx.x-xxx.000" />
                        </div>
                        <div class="md:col-span-2 space-y-2">
                           <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Alamat Kantor Pusat</label>
                           <textarea v-model="settingsData.inst_address" rows="2" class="w-full p-4 bg-gray-50 rounded-2xl border-none outline-none focus:ring-2 focus:ring-green-500 font-bold text-xs" placeholder="Jl. Raya Kebaikan No. 123..."></textarea>
                        </div>
                     </div>
                  </div>

                  <!-- Vision & Mission Section -->
                  <div class="space-y-6 pt-6 border-t border-gray-100">
                     <h4 class="text-[10px] font-black uppercase tracking-widest text-green-600 border-l-4 border-green-600 pl-4">Visi & Misi</h4>
                     <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                           <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Visi Lembaga</label>
                           <textarea v-model="settingsData.inst_vision" rows="4" class="w-full p-4 bg-gray-50 rounded-2xl border-none outline-none focus:ring-2 focus:ring-green-500 font-bold text-xs italic" placeholder="Menjadi lembaga filantropi terdepan..."></textarea>
                        </div>
                        <div class="space-y-2">
                           <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Misi Lembaga</label>
                           <textarea v-model="settingsData.inst_mission" rows="4" class="w-full p-4 bg-gray-50 rounded-2xl border-none outline-none focus:ring-2 focus:ring-green-500 font-bold text-xs italic" placeholder="1. Mengelola dana ummat secara profesional..."></textarea>
                        </div>
                     </div>
                  </div>

                  <!-- Social Media & Logo Section -->
                  <div class="space-y-6 pt-6 border-t border-gray-100">
                     <h4 class="text-[10px] font-black uppercase tracking-widest text-green-600 border-l-4 border-green-600 pl-4">Media Sosial & Logo</h4>
                     <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="space-y-2">
                           <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Instagram URL</label>
                           <input v-model="settingsData.inst_ig" class="w-full p-4 bg-gray-50 rounded-2xl border-none outline-none focus:ring-2 focus:ring-green-500 font-bold text-xs" placeholder="https://instagram.com/..." />
                        </div>
                        <div class="space-y-2">
                           <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Facebook URL</label>
                           <input v-model="settingsData.inst_fb" class="w-full p-4 bg-gray-50 rounded-2xl border-none outline-none focus:ring-2 focus:ring-green-500 font-bold text-xs" placeholder="https://facebook.com/..." />
                        </div>
                        <div class="space-y-2">
                           <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Twitter/X URL</label>
                           <input v-model="settingsData.inst_tw" class="w-full p-4 bg-gray-50 rounded-2xl border-none outline-none focus:ring-2 focus:ring-green-500 font-bold text-xs" placeholder="https://twitter.com/..." />
                        </div>
                        <div class="md:col-span-3 space-y-2">
                           <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest">URL Logo Lembaga (PNG/SVG recommended)</label>
                           <div class="flex gap-4 items-start">
                              <input v-model="settingsData.inst_logo" class="flex-1 p-4 bg-gray-50 rounded-2xl border-none outline-none focus:ring-2 focus:ring-green-500 font-bold text-xs" placeholder="https://domain.com/logo.png" />
                              <div class="w-20 h-20 bg-white rounded-2xl border border-gray-100 p-2 flex items-center justify-center overflow-hidden">
                                 <img v-if="settingsData.inst_logo" :src="settingsData.inst_logo" class="max-w-full max-h-full object-contain" />
                                 <i v-else class="fas fa-image text-gray-200"></i>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>

                  <button @click="handleSaveSettings" :disabled="savingSettings" class="w-full py-5 bg-green-600 text-white rounded-[2rem] font-black uppercase tracking-widest shadow-xl shadow-green-100 hover:bg-green-700 transition active:scale-95 disabled:opacity-50 flex items-center justify-center gap-3">
                     <i v-if="savingSettings" class="fas fa-circle-notch animate-spin"></i>
                     {{ savingSettings ? 'Menyimpan Profil...' : 'Simpan Pembaruan Profil Lembaga' }}
                  </button>
               </div>
            </div>

            <!-- Site Settings Tab (CMS) -->
            <div v-if="currentAdminTab === 'settings'" class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
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
            <div v-if="currentAdminTab === 'verify_campaign'" class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
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
 
             <div v-if="currentAdminTab === 'verify_donation'" class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
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
            <div v-if="currentAdminTab === 'monitoring'" class="space-y-8">
              <!-- Search Row for Workers -->
              <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-4">
                <div class="relative flex-grow">
                  <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                  <input v-model="workerSearchQuery" type="text" placeholder="Cari nama petugas lapangan..." class="w-full pl-12 pr-6 py-4 bg-gray-50 border-none rounded-2xl text-[11px] font-bold text-gray-700 outline-none focus:ring-2 focus:ring-green-400 transition-all placeholder:text-gray-300 placeholder:italic" />
                </div>
                <div class="px-6 py-4 bg-green-50 rounded-2xl">
                   <p class="text-[9px] font-black text-green-600 uppercase tracking-widest">{{ filteredWorkerPerformance.length }} Petugas Ditemukan</p>
                </div>
              </div>

              <!-- Worker Performance Cards -->
              <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-3 md:gap-6">
                <div
                  v-for="worker in filteredWorkerPerformance" :key="worker.id"
                  @click="openWorkerDetail(worker)"
                  class="bg-white p-4 md:p-8 rounded-[1.5rem] md:rounded-[2.5rem] border border-gray-100 shadow-sm relative overflow-hidden group cursor-pointer hover:border-green-200 hover:shadow-xl transition-all duration-300 active:scale-[0.98]"
                >
                  <div class="absolute -right-4 -top-4 w-20 h-20 bg-green-50 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
                  <!-- Click hint badge -->
                  <div class="absolute top-3 right-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <span class="px-2 py-1 bg-green-600 text-white rounded-full text-[7px] font-black uppercase tracking-widest">Klik</span>
                  </div>
                  <div class="relative z-10 space-y-2 md:space-y-4">
                    <p class="text-[8px] md:text-[9px] font-black text-gray-400 uppercase tracking-widest">{{ worker.role }}</p>
                    <h4 class="text-sm md:text-lg font-black text-gray-900 tracking-tighter line-clamp-1">{{ worker.name }}</h4>
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
                    <button @click.stop="openAssignModal(worker)" class="w-full py-3 bg-slate-900 text-white rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-green-600 transition-all">
                      <i class="fas fa-plus-circle mr-1"></i> Beri Tugas
                    </button>
                  </div>
                </div>
              </div>

              <!-- Tabs Internal: Absensi vs Daftar Tugas -->
              <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-50 flex flex-wrap justify-between items-center gap-4 bg-gray-50/50">
                  <div class="flex gap-4">
                    <button @click="monitoringSubTab = 'attendance'" :class="monitoringSubTab === 'attendance' ? 'bg-green-600 text-white shadow-lg' : 'text-gray-400 hover:text-gray-700'" class="px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">Absensi</button>
                    <button @click="monitoringSubTab = 'tasks'" :class="monitoringSubTab === 'tasks' ? 'bg-green-600 text-white shadow-lg' : 'text-gray-400 hover:text-gray-700'" class="px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">Daftar Tugas</button>
                  </div>
                  <div class="flex gap-3">
                    <button v-if="monitoringSubTab === 'tasks'" @click="downloadTasksCSV" class="px-6 py-2.5 bg-green-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg hover:bg-green-700 transition-all flex items-center gap-2">
                      <i class="fas fa-download"></i> Download CSV
                    </button>
                    <button v-if="monitoringSubTab === 'tasks'" @click="showAssignModal = true; assignTaskForm.id = null; assignTaskForm.user_id = null; assignTaskForm.worker_name = 'Umum'" class="px-6 py-2.5 bg-slate-900 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg hover:bg-green-600 transition-all flex items-center gap-2">
                      <i class="fas fa-plus-circle"></i> Beri Tugas Baru
                    </button>
                  </div>
                </div>

                <!-- Sub-Tab: Attendance -->
                <div v-if="monitoringSubTab === 'attendance'" class="space-y-0">
                  <!-- Filter Row Admin -->
                  <div class="p-6 border-b border-gray-50 flex flex-wrap gap-4 items-center bg-gray-50/20">
                     <div class="flex items-center gap-2 flex-wrap">
                        <span class="text-[9px] font-black uppercase tracking-widest text-gray-400"><i class="fas fa-filter mr-1"></i>Filter Absensi:</span>
                        <select v-model="adminFilterYear" class="px-3 py-2 bg-white border border-gray-200 rounded-xl text-[10px] font-black text-gray-700 outline-none focus:ring-2 focus:ring-green-400 cursor-pointer transition-all">
                           <option value="">Tahun</option>
                           <option v-for="y in availableAdminYears" :key="y" :value="String(y)">{{ y }}</option>
                        </select>
                        <select v-model="adminFilterMonth" class="px-3 py-2 bg-white border border-gray-200 rounded-xl text-[10px] font-black text-gray-700 outline-none focus:ring-2 focus:ring-green-400 cursor-pointer transition-all">
                           <option value="">Bulan</option>
                           <option value="1">Januari</option><option value="2">Februari</option><option value="3">Maret</option><option value="4">April</option>
                           <option value="5">Mei</option><option value="6">Juni</option><option value="7">Juli</option><option value="8">Agustus</option>
                           <option value="9">September</option><option value="10">Oktober</option><option value="11">November</option><option value="12">Desember</option>
                        </select>
                        <select v-model="adminFilterDay" class="px-3 py-2 bg-white border border-gray-200 rounded-xl text-[10px] font-black text-gray-700 outline-none focus:ring-2 focus:ring-green-400 cursor-pointer transition-all">
                           <option value="">Tanggal</option>
                           <option v-for="d in 31" :key="d" :value="String(d)">{{ d }}</option>
                        </select>
                        <button v-if="adminFilterMonth || adminFilterYear || adminFilterDay" @click="resetAdminFilter" class="px-3 py-2 bg-red-50 text-red-500 rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-red-100 transition-all border border-red-100">
                           <i class="fas fa-times"></i>
                        </button>
                     </div>
                     <div class="ml-auto flex items-center gap-2">
                        <button @click="downloadAdminAttendanceCSV" class="flex items-center gap-2 px-4 py-1.5 bg-green-600 text-white rounded-full text-[9px] font-black uppercase tracking-widest hover:bg-green-700 transition-all shadow-lg shadow-green-100">
                          <i class="fas fa-download"></i> Download CSV
                        </button>
                     </div>
                  </div>
                  <div class="overflow-x-auto">
                  <table class="w-full text-left">
                    <thead class="bg-gray-50 text-[9px] font-black text-gray-400 uppercase tracking-widest text-center">
                      <tr>
                        <th class="px-8 py-5 text-left">Pegawai & Tanggal</th>
                        <th class="px-8 py-5">Masuk (Jam/Lokasi)</th>
                        <th class="px-8 py-5">Pulang (Jam/Lokasi)</th>
                        <th class="px-8 py-5">Keterangan</th>
                        <th class="px-8 py-5">Aksi</th>
                      </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 text-center">
                      <tr v-for="group in groupedAdminAttendances" :key="group.date + group.employee_email" class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-8 py-6 text-left">
                          <p class="font-black text-gray-900 text-[10px] uppercase tracking-tight">{{ group.employee_name }}</p>
                          <p class="text-[9px] text-green-600 font-black uppercase tracking-widest">{{ group.date }}</p>
                        </td>
                        
                        <!-- Masuk -->
                        <td class="px-8 py-6">
                           <div v-if="group.check_in" class="space-y-1">
                              <p class="text-[10px] font-black text-green-600">{{ formatTime(group.check_in) }}</p>
                              <p class="text-[9px] text-gray-400 italic line-clamp-1 max-w-[150px] mx-auto" :title="group.check_in.location">{{ group.check_in.location }}</p>
                           </div>
                           <span v-else class="text-[10px] text-gray-200 font-black italic">--:--</span>
                        </td>

                        <!-- Pulang -->
                        <td class="px-8 py-6 border-l border-gray-50">
                           <div v-if="group.check_out" class="space-y-1">
                              <p class="text-[10px] font-black text-red-500">{{ formatTime(group.check_out) }}</p>
                              <p class="text-[9px] text-gray-400 italic line-clamp-1 max-w-[150px] mx-auto" :title="group.check_out.location">{{ group.check_out.location }}</p>
                           </div>
                           <span v-else class="text-[10px] text-gray-200 font-black italic">--:--</span>
                        </td>

                        <!-- Keterangan Lain -->
                        <td class="px-8 py-6 border-l border-gray-50">
                           <div v-if="group.other" class="flex flex-col items-center gap-1">
                              <span class="px-3 py-1 bg-amber-50 text-amber-600 rounded-full text-[8px] font-black uppercase tracking-widest">{{ group.other.type }}</span>
                           </div>
                           <span v-else class="text-[10px] text-gray-200 font-black italic">Normal</span>
                        </td>

                        <td class="px-8 py-6">
                           <div class="flex justify-center gap-2">
                              <button v-if="group.check_in?.attachment" @click="viewAttachment(group.check_in.attachment)" class="p-2 bg-green-50 text-green-600 rounded-lg hover:bg-green-600 hover:text-white transition-all" title="Bukti Masuk">
                                 <i class="fas fa-sign-in-alt text-[10px]"></i>
                              </button>
                              <button v-if="group.check_out?.attachment" @click="viewAttachment(group.check_out.attachment)" class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition-all" title="Bukti Pulang">
                                 <i class="fas fa-sign-out-alt text-[10px]"></i>
                              </button>
                              <button v-if="group.other?.attachment" @click="viewAttachment(group.other.attachment)" class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-600 hover:text-white transition-all" title="Bukti Keterangan">
                                 <i class="fas fa-file-alt text-[10px]"></i>
                              </button>
                           </div>
                        </td>
                      </tr>
                      <tr v-if="!loading && (groupedAdminAttendances.length === 0)">
                        <td colspan="5" class="px-8 py-24 text-center">
                           <div class="flex flex-col items-center gap-4 text-gray-300">
                              <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center text-2xl">
                                 <i class="fas fa-calendar-times"></i>
                              </div>
                              <div>
                                 <p class="text-[11px] font-black uppercase tracking-[0.2em] italic">Belum ada data absensi</p>
                                 <p class="text-[9px] font-bold text-gray-400 mt-1">Data akan muncul setelah petugas melakukan absensi</p>
                              </div>
                           </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>

                <!-- Sub-Tab: All Tasks -->
                <div v-if="monitoringSubTab === 'tasks'">
                   <!-- Filter Row Tugas -->
                   <div class="p-6 border-b border-gray-50 flex flex-wrap gap-3 items-center bg-gray-50/20">
                      <span class="text-[9px] font-black uppercase tracking-widest text-gray-400"><i class="fas fa-filter mr-1"></i>Filter Tugas:</span>
                      <select v-model="taskFilterYear" class="px-3 py-2 bg-white border border-gray-200 rounded-xl text-[10px] font-black text-gray-700 outline-none focus:ring-2 focus:ring-green-400 cursor-pointer transition-all">
                         <option value="">Tahun</option>
                         <option v-for="y in availableTaskYears" :key="y" :value="String(y)">{{ y }}</option>
                      </select>
                      <select v-model="taskFilterMonth" class="px-3 py-2 bg-white border border-gray-200 rounded-xl text-[10px] font-black text-gray-700 outline-none focus:ring-2 focus:ring-green-400 cursor-pointer transition-all">
                         <option value="">Bulan</option>
                         <option value="1">Januari</option><option value="2">Februari</option><option value="3">Maret</option><option value="4">April</option>
                         <option value="5">Mei</option><option value="6">Juni</option><option value="7">Juli</option><option value="8">Agustus</option>
                         <option value="9">September</option><option value="10">Oktober</option><option value="11">November</option><option value="12">Desember</option>
                      </select>
                      <select v-model="taskFilterDay" class="px-3 py-2 bg-white border border-gray-200 rounded-xl text-[10px] font-black text-gray-700 outline-none focus:ring-2 focus:ring-green-400 cursor-pointer transition-all">
                         <option value="">Tanggal</option>
                         <option v-for="d in 31" :key="d" :value="String(d)">{{ d }}</option>
                      </select>
                      <select v-model="taskFilterStatus" class="px-3 py-2 bg-white border border-gray-200 rounded-xl text-[10px] font-black text-gray-700 outline-none focus:ring-2 focus:ring-green-400 cursor-pointer transition-all">
                         <option value="">Semua Status</option>
                         <option value="pending">Pending</option>
                         <option value="completed">Completed</option>
                      </select>
                      <button v-if="taskFilterYear || taskFilterMonth || taskFilterDay || taskFilterStatus" @click="resetTaskFilter" class="px-3 py-2 bg-red-50 text-red-500 rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-red-100 transition-all border border-red-100">
                         <i class="fas fa-times"></i> Reset
                      </button>
                      <span class="ml-auto text-[9px] font-black text-gray-400 uppercase tracking-widest">{{ filteredTasks.length }} tugas ditemukan</span>
                   </div>
                   <div class="overflow-x-auto">
                     <table class="w-full text-left">
                        <thead class="bg-gray-50 text-[9px] font-black text-gray-400 uppercase tracking-widest">
                      <tr>
                        <th class="px-8 py-5">Tugas & Pegawai</th>
                        <th class="px-8 py-5">Tenggat</th>
                        <th class="px-8 py-5">Status</th>
                        <th class="px-8 py-5 text-center">Hasil</th>
                        <th class="px-8 py-5 text-center">Aksi</th>
                      </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                      <tr v-for="task in filteredTasks" :key="task.id" class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-8 py-6">
                           <p class="font-black text-gray-900 text-[10px] uppercase tracking-tight">{{ task.task }}</p>
                           <p v-if="task.description" class="text-[9px] text-gray-400 italic mb-1">{{ task.description }}</p>
                           <p class="text-[9px] text-green-600 font-bold uppercase tracking-widest">PIC: {{ task.employee_name }}</p>
                        </td>
                        <td class="px-8 py-6 text-center">
                           <p class="text-[10px] font-bold text-gray-700 italic">{{ formatDate(task.deadline) }}</p>
                        </td>
                        <td class="px-8 py-6 text-center">
                          <span :class="task.status === 'completed' ? 'bg-green-600 text-white' : 'bg-yellow-50 text-yellow-600'" class="px-3 py-1 rounded-lg text-[8px] font-black uppercase tracking-widest">
                            {{ task.status }}
                          </span>
                        </td>
                        <td class="px-8 py-6 text-center">
                          <div v-if="task.result_attachment || task.result_note" class="flex flex-col items-center gap-2">
                            <button v-if="task.result_attachment" @click="viewAttachment(task.result_attachment)" class="bg-green-50 text-green-600 p-2.5 rounded-xl hover:bg-green-600 hover:text-white transition-all" title="Lihat Foto Bukti">
                              <i class="fas fa-eye text-[9px]"></i>
                            </button>
                            <p v-if="task.result_note" class="text-[9px] text-gray-500 italic max-w-[150px] line-clamp-3" :title="task.result_note">"{{ task.result_note }}"</p>
                          </div>
                          <span v-else class="text-gray-300 text-[9px] uppercase font-black">Belum Ada Laporan</span>
                        </td>
                        <td class="px-8 py-6">
                          <div class="flex justify-center gap-2">
                            <button @click="openEditTask(task)" class="p-2 bg-green-50 text-green-600 rounded-lg hover:bg-green-600 hover:text-white transition-all">
                              <i class="fas fa-pen text-[9px]"></i>
                            </button>
                            <button @click="handleDeleteTask(task.id)" class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-500 hover:text-white transition-all">
                              <i class="fas fa-trash text-[9px]"></i>
                            </button>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
               </div>
            </div> <!-- End Monitoring Wrapper (819) -->
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
       <div class="bg-white rounded-[3rem] shadow-2xl w-full max-w-md max-h-[90vh] flex flex-col overflow-hidden animate-in zoom-in duration-300">
         <div class="p-8 bg-green-600 text-white flex justify-between items-center shrink-0">
           <h3 class="text-xl font-black uppercase tracking-tighter italic">{{ formData.id ? 'Edit' : 'Pendaftaran' }} <span class="text-green-200">Personel</span></h3>
           <button @click="showAddModal = false" class="text-white/60 hover:text-white">✕</button>
         </div>
         <form @submit.prevent="handleAddUser" class="p-8 space-y-5 overflow-y-auto custom-scrollbar">
           <input v-model="formData.name" placeholder="Nama Lengkap" class="w-full p-4 bg-gray-50 rounded-2xl border-none outline-none focus:ring-2 focus:ring-green-500 font-bold text-sm" required />
           <input v-model="formData.email" type="email" placeholder="Alamat Email" class="w-full p-4 bg-gray-50 rounded-2xl border-none outline-none focus:ring-2 focus:ring-green-500 font-bold text-sm" required />
           <input v-model="formData.password" type="password" :placeholder="formData.id ? 'Kata Sandi (Kosongkan jika tidak diubah)' : 'Kata Sandi Akses'" class="w-full p-4 bg-gray-50 rounded-2xl border-none outline-none focus:ring-2 focus:ring-green-500 font-bold text-sm" :required="!formData.id" />
           <select v-model="formData.role" class="w-full p-4 bg-gray-50 rounded-2xl border-none outline-none focus:ring-2 focus:ring-green-500 font-black uppercase text-[10px] tracking-widest">
             <option value="field_worker">Field Worker</option>
             <option value="admin">Admin</option>
             <option value="user">User</option>
           </select>
           <button type="submit" class="w-full py-5 bg-green-600 text-white rounded-[2rem] font-black uppercase tracking-widest hover:bg-green-700 shadow-xl shadow-green-100 active:scale-95 transition-all">Aktifkan Akun</button>
         </form>
       </div>
    </div>

    <!-- Generic Module Modal -->
    <div v-if="showModuleModal && activeModule" class="fixed inset-0 bg-gray-900/70 backdrop-blur-md flex items-center justify-center p-4 z-50">
       <div class="bg-white rounded-[3rem] shadow-2xl w-full max-w-md max-h-[90vh] flex flex-col overflow-hidden animate-in zoom-in duration-300">
         <div class="p-8 bg-gradient-to-br from-green-600 to-emerald-700 text-white flex justify-between items-center shrink-0">
           <div>
             <h3 class="text-xl font-black uppercase tracking-tighter italic">Form <span class="text-green-200">{{ activeModule.entity }}</span></h3>
             <p class="text-[9px] font-bold text-white/60 uppercase tracking-widest mt-1">Input Data Baru</p>
           </div>
           <button @click="showModuleModal = false" class="w-10 h-10 bg-white/10 hover:bg-white/20 rounded-xl flex items-center justify-center transition-colors">✕</button>
         </div>
         <div class="p-8 space-y-5 overflow-y-auto custom-scrollbar">
            <div v-for="field in activeModule.fields" :key="field.key" class="space-y-2">
              <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest px-1">{{ field.label }}</label>
               <select v-if="field.type === 'select' || ['office_id', 'group_id', 'payment_via_id', 'pic_prospect_id', 'last_prospect_media_id'].includes(field.key)" v-model="moduleFormData[field.key]" class="w-full p-4 bg-gray-50 rounded-2xl border-none outline-none focus:ring-2 focus:ring-green-500 font-bold text-sm">
                 <option v-if="['office_id', 'group_id', 'payment_via_id', 'pic_prospect_id', 'last_prospect_media_id'].includes(field.key)" value="">-- Pilih --</option>
                 <option v-if="field.key === 'office_id'" v-for="o in (moduleData.office || [])" :key="o.id" :value="o.id">{{ o.name }}</option>
                 <option v-else-if="field.key === 'group_id'" v-for="g in (moduleData.program_group || [])" :key="g.id" :value="g.name">{{ g.name }}</option>
                 <option v-else-if="field.key === 'payment_via_id'" v-for="p in (moduleData.payment_via || [])" :key="p.id" :value="p.id">{{ p.name }} ({{ p.provider }})</option>
                 <option v-else-if="field.key === 'pic_prospect_id'" v-for="u in (moduleData.all_users || [])" :key="u.id" :value="u.id">{{ u.name }} ({{ u.role }})</option>
                 <option v-else-if="field.key === 'last_prospect_media_id'" v-for="m in (moduleData.prospect_media || [])" :key="m.id" :value="m.id">{{ m.name }}</option>
                 <option v-else v-for="opt in (field.options || [])" :key="opt" :value="opt">{{ opt }}</option>
               </select>

              <textarea v-else-if="field.type === 'textarea'" v-model="moduleFormData[field.key]" :placeholder="field.label" rows="3" class="w-full p-4 bg-gray-50 rounded-2xl border-none outline-none focus:ring-2 focus:ring-green-500 font-bold text-sm"></textarea>

               <div v-else-if="field.type === 'image' || field.key.includes('_image') || field.key.includes('photo') || field.key.includes('logo') || field.key.includes('proof')" class="space-y-3">
                 <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-gray-100 rounded-2xl border-2 border-dashed border-gray-300 flex items-center justify-center overflow-hidden">
                       <img v-if="moduleFormData[field.key]" :src="moduleFormData[field.key]" class="w-full h-full object-cover" />
                       <i v-else class="fas fa-image text-gray-400 text-xl"></i>
                    </div>
                    <div class="flex-1 space-y-2">
                       <input type="file" @change="(e) => handleFileUpload(e, field.key)" accept="image/*" class="hidden" :ref="'fileInput_'+field.key" />
                       <button type="button" @click="() => $refs['fileInput_'+field.key][0].click()" class="px-4 py-2 bg-slate-900 text-white rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-green-600 transition-all">Pilih Gambar</button>
                       <p class="text-[8px] text-gray-400 font-medium">Klik untuk upload foto/bukti</p>
                    </div>
                 </div>
                 <input v-model="moduleFormData[field.key]" placeholder="Atau tempel URL gambar di sini" class="w-full p-3 bg-gray-50/50 rounded-xl border-none outline-none focus:ring-1 focus:ring-green-500 font-bold text-[10px]" />
               </div>

              <input v-else :type="field.type || 'text'" v-model="moduleFormData[field.key]" :placeholder="field.label" class="w-full p-4 bg-gray-50 rounded-2xl border-none outline-none focus:ring-2 focus:ring-green-500 font-bold text-sm" />
            </div>
         </div>
         <div class="p-8 pt-4 shrink-0">
           <button @click="handleSaveModuleData" class="w-full py-5 bg-green-600 text-white rounded-[2rem] font-black uppercase tracking-widest hover:bg-green-700 shadow-xl shadow-green-100 active:scale-95 transition-all">
             {{ moduleFormData._index !== undefined ? 'Update' : 'Simpan' }} Data {{ activeModule.entity }}
           </button>
         </div>
       </div>
    </div>

    <!-- Modal: Assign Task -->
    <div v-if="showAssignModal" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm flex items-center justify-center p-4 z-[100]">
        <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-md max-h-[90vh] flex flex-col overflow-hidden animate-in zoom-in duration-300">
          <div class="p-8 bg-gradient-to-br from-slate-900 to-slate-800 text-white flex justify-between items-center shrink-0">
            <div>
              <h3 class="text-xl font-black uppercase tracking-tighter italic">Beri <span class="text-green-400">Tugas</span></h3>
              <p class="text-[9px] font-bold text-white/60 uppercase tracking-widest mt-1">PIC: {{ assignTaskForm.worker_name }}</p>
            </div>
            <button @click="showAssignModal = false" class="text-white/60 hover:text-white">✕</button>
          </div>
          <form @submit.prevent="handleAssignTask" class="p-8 space-y-5 overflow-y-auto custom-scrollbar">
            <div v-if="!assignTaskForm.user_id" class="space-y-2">
              <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest px-1">Pilih Pegawai</label>
              <select v-model="assignTaskForm.user_id" class="w-full p-4 bg-gray-50 rounded-2xl outline-none focus:ring-2 focus:ring-green-500 font-bold text-sm" required>
                <option v-for="w in workerPerformance" :key="w.id" :value="w.id">{{ w.name }} ({{ w.role }})</option>
              </select>
            </div>
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

    <!-- Modal: Worker Detail & Task Management -->
    <div v-if="showWorkerDetailModal && selectedWorker" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm flex items-center justify-center p-4 z-[100]">
      <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col overflow-hidden animate-in zoom-in duration-300">
        <!-- Header -->
        <div class="p-6 bg-gradient-to-br from-slate-900 to-slate-800 text-white flex justify-between items-start shrink-0">
          <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-green-500/20 rounded-2xl flex items-center justify-center text-2xl font-black text-green-400 border border-green-500/30 uppercase">
              {{ selectedWorker.name?.charAt(0) }}
            </div>
            <div>
              <p class="text-[9px] font-black text-white/50 uppercase tracking-widest">{{ selectedWorker.role }}</p>
              <h3 class="text-xl font-black uppercase tracking-tighter">{{ selectedWorker.name }}</h3>
              <div class="flex items-center gap-2 mt-1">
                <div class="w-28 h-1.5 bg-white/10 rounded-full overflow-hidden">
                  <div :style="{ width: selectedWorker.percentage + '%' }" class="h-full bg-green-400 rounded-full transition-all duration-700"></div>
                </div>
                <span class="text-green-400 font-black text-[10px]">{{ selectedWorker.percentage }}%</span>
              </div>
              <p class="text-[9px] text-white/40 mt-0.5">{{ selectedWorker.completed_tasks }} / {{ selectedWorker.total_tasks }} Tugas Selesai</p>
            </div>
          </div>
          <div class="flex items-center gap-3">
            <button @click="openAssignFromDetail" class="px-4 py-2.5 bg-green-500 text-white rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-green-400 transition-all flex items-center gap-2">
              <i class="fas fa-plus"></i> Tambah Tugas
            </button>
            <button @click="showWorkerDetailModal = false" class="w-8 h-8 bg-white/10 rounded-xl flex items-center justify-center text-white/60 hover:text-white hover:bg-white/20 transition-all">
              <i class="fas fa-times text-xs"></i>
            </button>
          </div>
        </div>

        <!-- Stats Bar -->
        <div class="px-6 py-3 bg-gray-50 border-b border-gray-100 flex items-center gap-6 shrink-0">
          <div class="flex items-center gap-2">
            <div class="w-7 h-7 bg-green-50 text-green-600 rounded-lg flex items-center justify-center text-[10px]"><i class="fas fa-tasks"></i></div>
            <div><p class="text-[8px] font-black text-gray-400 uppercase tracking-widest">Total Tugas</p><p class="text-[13px] font-black text-gray-900">{{ workerTasks.length }}</p></div>
          </div>
          <div class="flex items-center gap-2">
            <div class="w-7 h-7 bg-emerald-50 text-emerald-600 rounded-lg flex items-center justify-center text-[10px]"><i class="fas fa-check-circle"></i></div>
            <div><p class="text-[8px] font-black text-gray-400 uppercase tracking-widest">Selesai</p><p class="text-[13px] font-black text-gray-900">{{ workerTasks.filter(t => t.status === 'completed').length }}</p></div>
          </div>
          <div class="flex items-center gap-2">
            <div class="w-7 h-7 bg-amber-50 text-amber-600 rounded-lg flex items-center justify-center text-[10px]"><i class="fas fa-hourglass-half"></i></div>
            <div><p class="text-[8px] font-black text-gray-400 uppercase tracking-widest">Pending</p><p class="text-[13px] font-black text-gray-900">{{ workerTasks.filter(t => t.status !== 'completed').length }}</p></div>
          </div>
        </div>

        <!-- Task List -->
        <div class="flex-1 overflow-y-auto p-6 space-y-3">
          <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest flex items-center gap-2 mb-4">
            <i class="fas fa-clipboard-list text-green-600"></i> Daftar Tugas
            <span class="px-2 py-0.5 bg-green-50 text-green-600 rounded-full">{{ workerTasks.length }}</span>
          </p>

          <div v-if="workerTasks.length === 0" class="py-16 text-center">
            <div class="w-16 h-16 bg-gray-50 text-gray-200 rounded-3xl flex items-center justify-center text-3xl mx-auto mb-4"><i class="fas fa-clipboard-list"></i></div>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Belum Ada Tugas</p>
            <p class="text-[9px] text-gray-300 mt-1">Klik Tambah Tugas untuk memberikan tugas baru</p>
            <button @click="openAssignFromDetail" class="mt-4 px-5 py-2.5 bg-green-600 text-white rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-green-700 transition-all">
              <i class="fas fa-plus mr-1"></i> Tambah Tugas Pertama
            </button>
          </div>

          <div v-for="task in workerTasks" :key="task.id" class="p-4 bg-gray-50 rounded-2xl border border-gray-100 hover:border-green-100 hover:bg-green-50/30 transition-all group/task">
            <div class="flex items-start justify-between gap-3">
              <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 mb-1 flex-wrap">
                  <span :class="task.status === 'completed' ? 'bg-green-100 text-green-700' : 'bg-amber-50 text-amber-600'" class="px-2 py-0.5 rounded-lg text-[8px] font-black uppercase tracking-widest">{{ task.status }}</span>
                </div>
                <p class="font-black text-gray-900 text-[11px] uppercase tracking-tight">{{ task.task }}</p>
                <p v-if="task.description" class="text-[9px] text-gray-400 italic mt-1 line-clamp-2">{{ task.description }}</p>
                <p class="text-[9px] text-green-600 font-bold uppercase tracking-widest mt-2">
                  <i class="fas fa-clock mr-1"></i>Tenggat: {{ formatDate(task.deadline) }}
                </p>
                <div v-if="task.result_note" class="mt-2 p-2 bg-white rounded-xl border border-green-100">
                  <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Hasil Laporan:</p>
                  <p class="text-[9px] text-gray-600 italic line-clamp-3">"{{ task.result_note }}"</p>
                </div>
              </div>
              <div class="flex gap-2 flex-shrink-0 opacity-0 group-hover/task:opacity-100 transition-opacity">
                <button @click="openEditTask(task); showWorkerDetailModal = false" class="p-2 bg-green-50 text-green-600 rounded-lg hover:bg-green-600 hover:text-white transition-all" title="Edit Tugas">
                  <i class="fas fa-pen text-[9px]"></i>
                </button>
                <button @click="handleDeleteTask(task.id)" class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-500 hover:text-white transition-all" title="Hapus Tugas">
                  <i class="fas fa-trash text-[9px]"></i>
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Footer -->
        <div class="p-5 border-t border-gray-50 flex justify-between items-center shrink-0 bg-gray-50/50">
          <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest">{{ workerTasks.length }} Tugas Tercatat</p>
          <button @click="openAssignFromDetail" class="px-5 py-2.5 bg-slate-900 text-white rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-green-600 transition-all flex items-center gap-2">
            <i class="fas fa-plus-circle"></i> Tambah Tugas Baru
          </button>
        </div>
      </div>
    </div>

    <!-- Modal: View Attachment (Image/Proof) -->
      <div v-if="showAttachmentModal" class="fixed inset-0 bg-slate-900/95 backdrop-blur-xl flex items-center justify-center p-6 z-[110]">
      <div class="relative max-w-4xl w-full">
        <button @click="showAttachmentModal = false" class="absolute -top-12 right-0 text-white text-3xl">✕</button>
        <img :src="attachmentUrl" class="w-full h-auto rounded-[2rem] shadow-2xl border border-white/10" />
      </div>
    </div>

    <!-- Modal Konfirmasi Logout Admin (Moved to bottom for better nesting) -->
    <Teleport to="body">
      <div v-if="showLogoutModal" class="fixed inset-0 z-[200] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/80 backdrop-blur-md animate-in fade-in duration-300" @click="!isLoggingOut && (showLogoutModal = false)"></div>
        
        <div class="bg-white w-full max-w-sm rounded-[2.5rem] shadow-2xl relative z-10 overflow-hidden animate-in zoom-in-95 duration-300">
          <div class="p-10 text-center">
            <div class="w-20 h-20 bg-red-50 text-red-500 rounded-[2rem] flex items-center justify-center text-3xl mx-auto mb-6 shadow-sm">
              <i class="fas fa-user-lock"></i>
            </div>
            
            <h3 class="text-2xl font-black text-gray-900 tracking-tighter mb-2 italic">Sesi <span class="text-red-600">Admin</span></h3>
            <p class="text-[11px] text-gray-400 font-bold uppercase tracking-widest leading-relaxed mb-8">
              Anda akan mengakhiri akses administratif. Pastikan semua perubahan telah tersimpan.
            </p>
            
            <div class="flex flex-col gap-3">
              <button @click="handleLogout" :disabled="isLoggingOut" class="w-full bg-slate-900 text-white py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-red-600 transition-all flex items-center justify-center gap-3 shadow-lg shadow-slate-200">
                <i v-if="isLoggingOut" class="fas fa-circle-notch animate-spin"></i>
                <span v-else>Ya, Akhiri Sesi</span>
              </button>
              <button @click="showLogoutModal = false" :disabled="isLoggingOut" class="w-full bg-gray-50 text-gray-400 py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-gray-100 transition-all">Batal</button>
            </div>
          </div>
        </div>
      </div>
    </Teleport>
  </div>
</div>
</div>
</template>

<script setup>
import { ref, reactive, computed, onMounted, watch } from 'vue';
import { useRouter } from 'vue-router';
import api from '@/api';
import { useAuth } from '@/store/auth';
import LineChart from '@/components/charts/LineChart.vue';
import DoughnutChart from '@/components/charts/DoughnutChart.vue';
import DonorDashboard from '@/views/dashboard/DonorDashboard.vue';
import RFMDashboard from '@/views/dashboard/RFMDashboard.vue';
import TargetDashboard from '@/views/dashboard/TargetDashboard.vue';
import ProspectDashboard from '@/views/dashboard/ProspectDashboard.vue';
import TransactionDashboard from '@/views/dashboard/TransactionDashboard.vue';
import DistributionDashboard from '@/views/dashboard/DistributionDashboard.vue';

const delay = (ms) => new Promise(res => setTimeout(res, ms));

const router = useRouter();
const { clearAuth } = useAuth();
const showLogoutModal = ref(false);
const isLoggingOut = ref(false);
const isMobileMenuOpen = ref(false);

const handleLogout = async () => {
  isLoggingOut.value = true;
  await new Promise(r => setTimeout(r, 1000));
  clearAuth();
  sessionStorage.clear();
  router.push('/');
};

const sidebarTabs = ref([
  { id: 'dashboard', label: 'Ringkasan', icon: 'fas fa-chart-pie' },
  { id: 'users', label: 'Manajemen User', icon: 'fas fa-users-cog' },
  { id: 'verify_zakat', label: 'Verifikasi Zakat', icon: 'fas fa-hand-holding-heart' },
  { id: 'settings', label: 'CMS Tampilan', icon: 'fas fa-paint-roller' },
  { id: 'news_mgt', label: 'Manajemen Warta', icon: 'fas fa-newspaper' },
  { id: 'muallaf_mgt', label: 'Manajemen Mualaf', icon: 'fas fa-kaaba' },
  { id: 'recruitment', label: 'Rekrutmen Relawan', icon: 'fas fa-user-plus' },
  { id: 'inbox', label: 'Kotak Pesan', icon: 'fas fa-envelope-open-text' },
  { id: 'newsletter_mgt', label: 'Berlangganan Warta', icon: 'fas fa-mail-bulk' },
  { id: 'audit_logs', label: 'Log Sistem', icon: 'fas fa-history' },
  { id: 'verify_campaign', label: 'Approval Kampanye', icon: 'fas fa-check-double' },
  { id: 'verify_donation', label: 'Verifikasi Donasi', icon: 'fas fa-donate' },
  { id: 'monitoring', label: 'Monitoring & Tugas', icon: 'fas fa-tasks' },
]);

const currentAdminTab = ref('dashboard');
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
  { id: 'field_reports', label: 'Laporan Lapangan', icon: 'fas fa-map-marker-alt' },
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
  { id: 'target_dashboard', label: 'Target Vs Capaian', icon: 'fas fa-tasks' },
  { id: 'rfm_dashboard', label: 'Dashboard RFM', icon: 'fas fa-brain' },
];

// All tabs combined — used by mobile header to show active tab label
const allNavTabs = computed(() => [
  ...sidebarTabs.value,
  ...managementTabs,
  ...distributionTabs,
  ...transactionTabs,
]);


const isAdminManagementOpen = ref(true);
const isDistributionManagementOpen = ref(true);
const isTransactionManagementOpen = ref(true);
const isMainMenuOpen = ref(false); 
const showModuleModal = ref(false);
const moduleSearchQuery = ref('');
const moduleFormData = ref({});
const moduleData = ref({});
const moduleStats = ref({ kpis: [] });

// Module Configs — drives the generic CRUD renderer

const moduleConfigs = {
  profile:     { title: 'Profil Lembaga', entity: 'Profil', description: 'Data resmi & identitas lembaga', icon: 'fas fa-building', pk: 'setting_key',
    columns: [{ key: 'setting_key', label: 'Field' }, { key: 'setting_value', label: 'Nilai' }],
    fields: [{ key: 'setting_key', label: 'Nama Field' }, { key: 'setting_value', label: 'Nilai', type: 'textarea' }] },

  users_mgt:   { title: 'Users', entity: 'User', description: 'Manajemen seluruh akun pengguna', icon: 'fas fa-user-friends',
    columns: [{ key: 'name', label: 'Nama' }, { key: 'email', label: 'Email' }, { key: 'role', label: 'Peran', type: 'badge', badgeClass: (v) => v === 'admin' ? 'bg-red-50 text-red-600' : 'bg-green-50 text-green-600' }],
    fields: [{ key: 'name', label: 'Nama Lengkap' }, { key: 'email', label: 'Email', type: 'email' }, { key: 'role', label: 'Peran', type: 'select', options: ['admin', 'field_worker', 'user'] }] },

  office:      { title: 'Kantor', entity: 'Kantor', description: 'Data seluruh lokasi kantor', icon: 'fas fa-map-marked-alt',
    columns: [{ key: 'name', label: 'Nama Kantor' }, { key: 'city', label: 'Kota' }, { key: 'phone', label: 'Telepon' }, { key: 'status', label: 'Status', type: 'badge', badgeClass: (v) => v === 'Aktif' ? 'bg-green-50 text-green-600' : 'bg-gray-100 text-gray-500' }],
    fields: [{ key: 'name', label: 'Nama Kantor' }, { key: 'address', label: 'Alamat', type: 'textarea' }, { key: 'city', label: 'Kota' }, { key: 'phone', label: 'Telepon' }, { key: 'status', label: 'Status', type: 'select', options: ['Aktif', 'Non-Aktif'] }] },

  employees:   { title: 'Karyawan', entity: 'Karyawan', description: 'Data SDM & personel aktif', icon: 'fas fa-id-badge',
    columns: [{ key: 'name', label: 'Nama' }, { key: 'position', label: 'Jabatan' }, { key: 'office_name', label: 'Kantor' }, { key: 'status', label: 'Status', type: 'badge', badgeClass: (v) => v === 'Aktif' ? 'bg-green-50 text-green-600' : 'bg-gray-100 text-gray-500' }],
    fields: [{ key: 'name', label: 'Nama Lengkap' }, { key: 'position', label: 'Jabatan' }, { key: 'department', label: 'Divisi' }, { key: 'phone', label: 'No. Telepon' }, { key: 'office_id', label: 'Lokasi Kantor' }, { key: 'status', label: 'Status', type: 'select', options: ['Aktif', 'Non-Aktif'] }] },

  donor_channel: { title: 'Channel Donasi', entity: 'Channel', description: 'Saluran penerimaan donasi', icon: 'fas fa-network-wired',
    columns: [{ key: 'name', label: 'Nama Channel' }, { key: 'type', label: 'Tipe' }, { key: 'status', label: 'Status', type: 'badge', badgeClass: (v) => v === 'Aktif' ? 'bg-green-50 text-green-600' : 'bg-gray-100 text-gray-500' }],
    fields: [{ key: 'name', label: 'Nama Channel' }, { key: 'type', label: 'Tipe' }, { key: 'account', label: 'No. Rekening/Akun' }, { key: 'status', label: 'Status', type: 'select', options: ['Aktif', 'Non-Aktif'] }] },

  program_group: { title: 'Rumpun Program', entity: 'Rumpun', description: 'Klaster pengelompokan program', icon: 'fas fa-layer-group',
    columns: [{ key: 'name', label: 'Nama Rumpun' }, { key: 'code', label: 'Kode' }, { key: 'description', label: 'Deskripsi' }],
    fields: [{ key: 'name', label: 'Nama Rumpun' }, { key: 'code', label: 'Kode' }, { key: 'description', label: 'Deskripsi', type: 'textarea' }] },

  programs:    { title: 'Program (Kampanye)', entity: 'Program', description: 'Program sosial & kemanusiaan aktif', icon: 'fas fa-project-diagram',
    columns: [{ key: 'title', label: 'Nama Program' }, { key: 'group_name', label: 'Rumpun' }, { key: 'target_amount', label: 'Target Dana', type: 'currency' }, { key: 'status', label: 'Status', type: 'badge', badgeClass: (v) => v === 'approved' ? 'bg-green-50 text-green-600' : 'bg-yellow-50 text-yellow-600' }],
    fields: [{ key: 'title', label: 'Nama Program' }, { key: 'group_id', label: 'Rumpun Program' }, { key: 'description', label: 'Deskripsi', type: 'textarea' }, { key: 'target_amount', label: 'Target Dana', type: 'number' }, { key: 'status', label: 'Status', type: 'select', options: ['pending', 'approved', 'rejected'] }] },

  payment_via: { title: 'Via Bayar', entity: 'Metode Bayar', description: 'Metode pembayaran yang diterima', icon: 'fas fa-credit-card',
    columns: [{ key: 'name', label: 'Nama Metode' }, { key: 'provider', label: 'Provider' }, { key: 'status', label: 'Status', type: 'badge', badgeClass: (v) => v === 'Aktif' ? 'bg-green-50 text-green-600' : 'bg-gray-100 text-gray-500' }],
    fields: [{ key: 'name', label: 'Nama Metode' }, { key: 'provider', label: 'Provider' }, { key: 'fee', label: 'Biaya Admin (%)', type: 'number' }, { key: 'status', label: 'Status', type: 'select', options: ['Aktif', 'Non-Aktif'] }] },

  prospect_media: { title: 'Media Prospek', entity: 'Media', description: 'Kanal pemasaran & akuisisi donatur', icon: 'fas fa-photo-video',
    columns: [{ key: 'name', label: 'Nama Media' }, { key: 'type', label: 'Tipe' }, { key: 'status', label: 'Status', type: 'badge', badgeClass: (v) => v === 'Aktif' ? 'bg-green-50 text-green-600' : 'bg-gray-100 text-gray-500' }],
    fields: [{ key: 'name', label: 'Nama Media' }, { key: 'type', label: 'Tipe' }, { key: 'status', label: 'Status', type: 'select', options: ['Aktif', 'Non-Aktif'] }] },

  prospect_method: { title: 'Metode Prospek', entity: 'Metode', description: 'Teknik pendekatan calon donatur', icon: 'fas fa-bullhorn',
    columns: [{ key: 'name', label: 'Nama Metode' }, { key: 'description', label: 'Deskripsi' }],
    fields: [{ key: 'name', label: 'Nama Metode' }, { key: 'description', label: 'Deskripsi', type: 'textarea' }] },

  prospect_type: { title: 'Jenis Prospek', entity: 'Jenis', description: 'Segmentasi tipe calon donatur', icon: 'fas fa-filter',
    columns: [{ key: 'name', label: 'Jenis Prospek' }, { key: 'priority', label: 'Prioritas', type: 'badge', badgeClass: (v) => v === 'Tinggi' ? 'bg-red-50 text-red-600' : v === 'Sedang' ? 'bg-yellow-50 text-yellow-600' : 'bg-green-50 text-green-600' }],
    fields: [{ key: 'name', label: 'Nama Jenis' }, { key: 'priority', label: 'Prioritas', type: 'select', options: ['Tinggi', 'Sedang', 'Rendah'] }] },

  fundraisers:  { title: 'Fundraiser', entity: 'Fundraiser', description: 'Tim penggalangan dana aktif', icon: 'fas fa-user-tie',
    columns: [{ key: 'name', label: 'Nama' }, { key: 'email', label: 'Email' }, { key: 'status', label: 'Status', type: 'badge', badgeClass: (v) => v === 'Aktif' ? 'bg-green-50 text-green-600' : 'bg-gray-100 text-gray-500' }],
    fields: [{ key: 'name', label: 'Nama Fundraiser' }, { key: 'email', label: 'Email' }, { key: 'target', label: 'Target Dana', type: 'number' }, { key: 'status', label: 'Status', type: 'select', options: ['Aktif', 'Non-Aktif'] }] },

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
    columns: [{ key: 'recipient_name', label: 'Penerima' }, { key: 'campaign_title', label: 'Program' }, { key: 'amount', label: 'Nilai', type: 'currency' }, { key: 'distribution_date', label: 'Tanggal', type: 'date' }, { key: 'status', label: 'Status', type: 'badge', badgeClass: (v) => v === 'Tersalur' ? 'bg-green-50 text-green-600' : 'bg-yellow-50 text-yellow-600' }],
    fields: [{ key: 'recipient_name', label: 'Nama Penerima' }, { key: 'program_id', label: 'ID Program (Numeric)' }, { key: 'amount', label: 'Nilai Bantuan', type: 'number' }, { key: 'distribution_date', label: 'Tanggal', type: 'date' }, { key: 'status', label: 'Status', type: 'select', options: ['Tersalur', 'Proses'] }] },

  field_reports: { title: 'Laporan Lapangan (Peta)', entity: 'Laporan', table: 'distributions', description: 'Laporan penyaluran real-time dari petugas lapangan', icon: 'fas fa-map-marked-alt',
    columns: [
      { key: 'photo_url', label: 'Foto', type: 'image' },
      { key: 'title', label: 'Judul Laporan' },
      { key: 'worker_name', label: 'Petugas' },
      { key: 'location_name', label: 'Lokasi' },
      { key: 'created_at', label: 'Waktu', type: 'date' }
    ],
    fields: [
      { key: 'title', label: 'Judul Laporan' },
      { key: 'description', label: 'Deskripsi', type: 'textarea' },
      { key: 'location_name', label: 'Nama Lokasi' },
      { key: 'maps_link', label: 'Link Google Maps' }
    ]
  },
 
  donors:       { title: 'List Donatur', entity: 'Donatur', description: 'Database seluruh donatur terdaftar', icon: 'fas fa-users',
    columns: [
      { key: 'profile_photo', label: 'Foto', type: 'image' },
      { key: 'name', label: 'Nama Lengkap' }, 
      { key: 'gender', label: 'L/P' }, 
      { key: 'phone', label: 'No HP' }, 
      { key: 'email', label: 'Email' }, 
      { key: 'office_name', label: 'Kantor' }, 
      { key: 'segment', label: 'Segmen' }, 
      { key: 'profile_type', label: 'Profil' }, 
      { key: 'rfm_90d', label: 'RFM 90D' }, 
      { key: 'rfm_alltime', label: 'RFM AllTime' }, 
      { key: 'category', label: 'Kategori' }, 
      { key: 'badge', label: 'Badge' }, 
      { key: 'total_transactions', label: 'Total Transaksi' }, 
      { key: 'total_nominal', label: 'Total Nominal', type: 'currency' }, 
      { key: 'last_transaction_date', label: 'Tgl Transaksi Terakhir', type: 'date' }, 
      { key: 'registration_date', label: 'Tgl Registrasi', type: 'date' }, 
      { key: 'created_at', label: 'Tgl Insert', type: 'date' }, 
      { key: 'last_proof_image', label: 'Bukti', type: 'image' }, 
      { key: 'pic_name', label: 'PIC Prospek' }, 
      { key: 'source_info', label: 'Sumber Info' }, 
      { key: 'last_program_title', label: 'Prog Terakhir' }, 
      { key: 'last_payment_via_name', label: 'Via Terakhir' }, 
      { key: 'prospect_date', label: 'Tgl Prospek', type: 'date' }, 
      { key: 'last_media_name', label: 'Media Terakhir' }
    ],
     fields: [
       { key: 'name', label: 'Nama Lengkap' }, 
       { key: 'gender', label: 'Jenis Kelamin', type: 'select', options: ['L', 'P'] }, 
       { key: 'phone', label: 'Nomor HP' }, 
       { key: 'email', label: 'Email', type: 'email' }, 
       { key: 'office_id', label: 'Kantor Layanan' }, 
       { key: 'segment', label: 'Segmen' }, 
       { key: 'profile_type', label: 'Profil' }, 
       { key: 'category', label: 'Kategori' }, 
       { key: 'badge', label: 'Badge' }, 
       { key: 'profile_photo', label: 'Foto Profil (Upload)', type: 'image' },
       { key: 'last_proof_image', label: 'Bukti Donasi Terakhir (Upload)', type: 'image' },
       { key: 'pic_prospect_id', label: 'PIC Prospek' }, 
       { key: 'source_info', label: 'Sumber Informasi' }, 
       { key: 'prospect_date', label: 'Tanggal Prospek', type: 'date' }, 
       { key: 'last_prospect_media_id', label: 'Media Prospek Terakhir' },
       { key: 'registration_date', label: 'Tanggal Registrasi', type: 'date' },
       { key: 'rfm_90d', label: 'RFM Score 90 Days' },
       { key: 'rfm_alltime', label: 'RFM Score All Time' }
     ] },

  transactions: { title: 'List Transaksi', entity: 'Transaksi', description: 'Riwayat seluruh transaksi donasi', icon: 'fas fa-exchange-alt',
    columns: [
      { key: 'donation_date', label: 'Tgl Donasi', type: 'date' },
      { key: 'created_at', label: 'Tgl Insert', type: 'date' },
      { key: 'updated_at', label: 'Tgl Konfirmasi', type: 'date' },
      { key: 'donor_name', label: 'Nama Lengkap' }, 
      { key: 'donor_phone', label: 'Nomor HP' },
      { key: 'campaign_title', label: 'Program' }, 
      { key: 'amount', label: 'Jumlah', type: 'currency' }, 
      { key: 'payment_via_name', label: 'Metode' }, 
      { key: 'payment_status', label: 'Status', type: 'badge', badgeClass: (v) => v === 'Success' ? 'bg-green-50 text-green-600' : v === 'Pending' ? 'bg-yellow-50 text-yellow-600' : 'bg-red-50 text-red-600' }
    ],
    fields: [
      { key: 'donor_name', label: 'Nama Donatur' }, 
      { key: 'campaign_id', label: 'ID Program' }, 
      { key: 'amount', label: 'Jumlah', type: 'number' }, 
      { key: 'payment_via_id', label: 'Metode Bayar' }, 
      { key: 'proof_image', label: 'Upload Bukti Transfer', type: 'image' },
      { key: 'payment_status', label: 'Status', type: 'select', options: ['Pending', 'Success', 'Failed'] },
      { key: 'tracking_status', label: 'Status Pelacakan', type: 'select', options: ['Diterima', 'Logistik', 'Dikirim', 'Tersalur'] }
    ] },

  potential_donors: { title: 'List Calon Donatur', entity: 'Calon Donatur', description: 'User terdaftar yang belum melakukan donasi', icon: 'fas fa-user-plus',
    columns: [
       { key: 'profile_photo', label: 'Foto', type: 'image' },
       { key: 'name', label: 'Nama Lengkap' }, 
       { key: 'phone', label: 'No HP' }, 
       { key: 'email', label: 'Email' }, 
       { key: 'created_at', label: 'Tgl Daftar', type: 'date' },
       { key: 'source_info', label: 'Sumber Info' }, 
       { key: 'pic_name', label: 'PIC Prospek' }
    ],
    fields: [
       { key: 'name', label: 'Nama Lengkap' }, 
       { key: 'gender', label: 'Jenis Kelamin', type: 'select', options: ['L', 'P'] }, 
       { key: 'phone', label: 'Nomor HP' }, 
       { key: 'email', label: 'Email', type: 'email' }, 
       { key: 'pic_prospect_id', label: 'PIC Prospek' }, 
       { key: 'source_info', label: 'Sumber Informasi' },
       { key: 'category', label: 'Kategori (e.g. Prospek Baru)' }
    ] },

  distribution_dashboard: { title: 'Dashboard Penyaluran', entity: 'Laporan', description: 'Statistik & visualisasi distribusi bantuan', icon: 'fas fa-chart-line', isDashboard: true, fields: [] },
  transaction_dashboard: { title: 'Dashboard Transaksi', entity: 'Laporan', description: 'Analitik & ringkasan transaksi keuangan', icon: 'fas fa-chart-bar', isDashboard: true, fields: [] },
  donor_dashboard: { title: 'Dashboard Donatur', entity: 'Laporan', description: 'Analitik perilaku & segmentasi donatur', icon: 'fas fa-user-chart', isDashboard: true, fields: [] },
  prospect_dashboard: { title: 'Dashboard Prospek', entity: 'Laporan', description: 'Analitik pipeline & konversi prospek', icon: 'fas fa-funnel-dollar', isDashboard: true, fields: [] },
  target_vs_achievement: { title: 'Target vs Capaian', entity: 'Laporan', description: 'Perbandingan target & realisasi per periode', icon: 'fas fa-tasks', isDashboard: true, fields: [] },
  rfm_dashboard: { title: 'Dashboard RFM', entity: 'Laporan', description: 'Analitik Recency, Frequency, Monetary donatur', icon: 'fas fa-brain', isDashboard: true, fields: [] },
  
  news_mgt: { title: 'Manajemen Warta', entity: 'Berita', description: 'Publikasi artikel dan laporan lapangan', icon: 'fas fa-newspaper',
    columns: [{ key: 'title', label: 'Judul Berita' }, { key: 'tag', label: 'Tag' }, { key: 'created_at', label: 'Tanggal', type: 'date' }],
    fields: [{ key: 'title', label: 'Judul Berita' }, { key: 'tag', label: 'Tag (Contoh: Update, Laporan, Kisah)' }, { key: 'content', label: 'Isi Berita', type: 'textarea' }, { key: 'image', label: 'URL Gambar', type: 'text' }] },

  muallaf_mgt: { title: 'Layanan Mualaf', entity: 'Pengajuan', description: 'Permohonan bimbingan syahadat & pembinaan', icon: 'fas fa-kaaba',
    columns: [{ key: 'name', label: 'Nama' }, { key: 'desired_date', label: 'Rencana Tanggal', type: 'date' }, { key: 'status', label: 'Status', type: 'badge', badgeClass: (v) => v === 'approved' ? 'bg-green-50 text-green-600' : v === 'pending' ? 'bg-yellow-50 text-yellow-600' : 'bg-red-50 text-red-600' }],
    fields: [{ key: 'name', label: 'Nama Lengkap' }, { key: 'email', label: 'Email', type: 'email' }, { key: 'address', label: 'Alamat', type: 'textarea' }, { key: 'desired_date', label: 'Rencana Tanggal', type: 'date' }, { key: 'location', label: 'Lokasi Syahadat' }, { key: 'status', label: 'Status', type: 'select', options: ['pending', 'approved', 'rejected', 'completed'] }] },

  news: { title: 'Berita & Artikel', entity: 'Artikel', description: 'Kelola konten berita dan laporan lapangan', icon: 'fas fa-newspaper',
    columns: [{ key: 'title', label: 'Judul Artikel' }, { key: 'tag', label: 'Tag' }, { key: 'created_at', label: 'Tanggal Publish' }],
    fields: [
      { key: 'title', label: 'Judul Artikel' },
      { key: 'tag', label: 'Kategori/Tag (Contoh: Laporan, Update, Kisah)' },
      { key: 'content', label: 'Konten Lengkap', type: 'textarea' },
      { key: 'image_url', label: 'URL Gambar Utama' }
    ] },

  audit_logs: { title: 'Log Aktivitas', entity: 'Log', description: 'Rekam jejak seluruh perubahan sistem', icon: 'fas fa-history',
    columns: [{ key: 'user_name', label: 'Aktor' }, { key: 'action', label: 'Aksi' }, { key: 'module', label: 'Modul' }, { key: 'created_at', label: 'Waktu', type: 'date' }],
    fields: [] },

  recruitment: { title: 'Manajemen Relawan', entity: 'Relawan', description: 'Pendaftaran dan rekrutmen relawan baru', icon: 'fas fa-user-plus',
    columns: [{ key: 'name', label: 'Nama' }, { key: 'email', label: 'Email' }, { key: 'phone', label: 'Telepon' }, { key: 'status', label: 'Status', type: 'badge', badgeClass: (v) => v === 'approved' ? 'bg-green-50 text-green-600' : v === 'pending' ? 'bg-yellow-50 text-yellow-600' : 'bg-red-50 text-red-600' }],
    fields: [{ key: 'name', label: 'Nama Lengkap' }, { key: 'email', label: 'Email' }, { key: 'phone', label: 'Telepon' }, { key: 'address', label: 'Alamat', type: 'textarea' }, { key: 'skills', label: 'Keahlian', type: 'textarea' }, { key: 'reason', label: 'Alasan Bergabung', type: 'textarea' }, { key: 'status', label: 'Status', type: 'select', options: ['pending', 'approved', 'rejected'] }] },

  inbox: { title: 'Kotak Pesan Masuk', entity: 'Pesan', description: 'Pesan dan pertanyaan dari publik', icon: 'fas fa-envelope-open-text',
    columns: [{ key: 'name', label: 'Pengirim' }, { key: 'email', label: 'Email' }, { key: 'status', label: 'Status', type: 'badge', badgeClass: (v) => v === 'unread' ? 'bg-red-50 text-red-600' : 'bg-green-50 text-green-600' }],
    fields: [{ key: 'name', label: 'Nama Pengirim' }, { key: 'email', label: 'Email' }, { key: 'message', label: 'Isi Pesan', type: 'textarea' }, { key: 'status', label: 'Status', type: 'select', options: ['unread', 'read', 'replied'] }] },

  newsletter_mgt: { title: 'Daftar Pelanggan Warta', entity: 'Pelanggan', description: 'Daftar email yang berlangganan newsletter', icon: 'fas fa-mail-bulk',
    columns: [{ key: 'email', label: 'Email' }, { key: 'status', label: 'Status', type: 'badge', badgeClass: (v) => v === 'active' ? 'bg-green-50 text-green-600' : 'bg-gray-50 text-gray-400' }, { key: 'created_at', label: 'Tanggal', type: 'date' }],
    fields: [{ key: 'email', label: 'Email' }, { key: 'status', label: 'Status', type: 'select', options: ['active', 'unsubscribed'] }] },
};

const activeModule = computed(() => {
  const customTabs = [
    'dashboard', 'users', 'verify_campaign', 'verify_donation', 
    'verify_zakat', 'settings', 'profile', 'monitoring',
    'transaction_dashboard', 'distribution_dashboard', 'donor_dashboard', 
    'prospect_dashboard', 'target_vs_achievement', 'rfm_dashboard'
  ];
  if (customTabs.includes(currentAdminTab.value)) return null;
  return moduleConfigs[currentAdminTab.value] || null;
});
const filteredModuleData = computed(() => {
  const data = moduleData.value[currentAdminTab.value] || [];
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
const formData = reactive({ id: null, name: '', email: '', password: '', role: 'field_worker' });

// Monitoring State
const monitoringSubTab = ref('attendance');
const workerPerformance = ref([]);
const workerSearchQuery = ref('');

const filteredWorkerPerformance = computed(() => {
  if (!workerSearchQuery.value) return workerPerformance.value;
  const q = workerSearchQuery.value.toLowerCase();
  return workerPerformance.value.filter(w => w.name.toLowerCase().includes(q));
});

const allAttendances = ref([]);

const groupedAdminAttendances = computed(() => {
  const groups = {};
  allAttendances.value.forEach(att => {
    const d = new Date(att.created_at);
    // Filter berdasarkan bulan, tahun, hari
    if (adminFilterYear.value && d.getFullYear() !== parseInt(adminFilterYear.value)) return;
    if (adminFilterMonth.value && (d.getMonth() + 1) !== parseInt(adminFilterMonth.value)) return;
    if (adminFilterDay.value && d.getDate() !== parseInt(adminFilterDay.value)) return;

    const dateStr = att.tgl_indo || formatDate(att.created_at);
    const key = `${att.user_id}_${dateStr}`;
    
    if (!groups[key]) {
      groups[key] = {
        employee_name: att.employee_name,
        employee_email: att.employee_email,
        date: dateStr,
        rawDate: d,
        check_in: null,
        check_out: null,
        other: null
      };
    }
    
    if (att.type === 'check_in') groups[key].check_in = att;
    else if (att.type === 'check_out') groups[key].check_out = att;
    else groups[key].other = att;
  });
  // Urutkan dari tanggal terbaru ke terlama
  return Object.values(groups).sort((a, b) => b.rawDate - a.rawDate);
});

// Admin Filter State
const adminFilterMonth = ref('');
const adminFilterYear = ref('');
const adminFilterDay = ref('');

const availableAdminYears = computed(() => {
  const years = new Set(allAttendances.value.map(l => new Date(l.created_at).getFullYear()));
  return [...years].sort((a, b) => b - a);
});

const resetAdminFilter = () => {
  adminFilterMonth.value = '';
  adminFilterYear.value = '';
  adminFilterDay.value = '';
};

const downloadAdminAttendanceCSV = () => {
  const headers = ['Nama Pegawai', 'Email', 'Tanggal', 'Jam Masuk', 'Lokasi Masuk', 'Jam Pulang', 'Lokasi Pulang', 'Keterangan'];
  const rows = groupedAdminAttendances.value.map(g => [
    g.employee_name,
    g.employee_email,
    g.date,
    g.check_in ? formatTime(g.check_in.created_at) : '-',
    g.check_in ? (g.check_in.location || '-') : '-',
    g.check_out ? formatTime(g.check_out.created_at) : '-',
    g.check_out ? (g.check_out.location || '-') : '-',
    g.other ? g.other.type.toUpperCase() : 'Hadir'
  ]);
  const csvContent = [headers, ...rows].map(r => r.map(c => `"${String(c).replace(/"/g, '""')}"`).join(',')).join('\n');
  const blob = new Blob(['\uFEFF' + csvContent], { type: 'text/csv;charset=utf-8;' });
  const url = URL.createObjectURL(blob);
  const link = document.createElement('a');
  link.href = url;
  link.download = `Rekap_Absensi_Admin_${new Date().toLocaleDateString('en-CA')}.csv`;
  link.click();
  URL.revokeObjectURL(url);
};

// Task Filter State
const taskFilterYear = ref('');
const taskFilterMonth = ref('');
const taskFilterDay = ref('');
const taskFilterStatus = ref('');

const availableTaskYears = computed(() => {
  const years = new Set(allTasks.value.map(t => {
    const date = t.deadline ? new Date(t.deadline) : new Date(t.created_at);
    return date.getFullYear();
  }));
  return [...years].sort((a, b) => b - a);
});

const filteredTasks = computed(() => {
  return allTasks.value.filter(t => {
    const d = t.deadline ? new Date(t.deadline) : new Date(t.created_at);
    
    if (taskFilterYear.value && d.getFullYear() !== parseInt(taskFilterYear.value)) return false;
    if (taskFilterMonth.value && (d.getMonth() + 1) !== parseInt(taskFilterMonth.value)) return false;
    if (taskFilterDay.value && d.getDate() !== parseInt(taskFilterDay.value)) return false;
    if (taskFilterStatus.value && t.status !== taskFilterStatus.value) return false;
    
    return true;
  });
});

const resetTaskFilter = () => {
  taskFilterYear.value = '';
  taskFilterMonth.value = '';
  taskFilterDay.value = '';
  taskFilterStatus.value = '';
};

const allTasks = ref([]);
const showAssignModal = ref(false);
const assigning = ref(false);
const assignTaskForm = reactive({ id: null, user_id: null, worker_name: '', task: '', description: '', deadline: '' });

const handleDeleteTask = async (id) => {
  if (confirm('Yakin ingin menghapus tugas ini?')) {
    try {
      await api.delete(`/admin/tasks/${id}`);
      fetchMonitoringData();
    } catch (e) {
      alert('Gagal menghapus tugas.');
    }
  }
};

const openEditTask = (task) => {
  assignTaskForm.id = task.id;
  assignTaskForm.user_id = task.user_id;
  assignTaskForm.worker_name = task.employee_name;
  assignTaskForm.task = task.task;
  assignTaskForm.description = task.description;
  // Format deadline for datetime-local input (YYYY-MM-DDTHH:mm)
  const d = new Date(task.deadline);
  const formattedDeadline = d.getFullYear() + '-' + 
    String(d.getMonth() + 1).padStart(2, '0') + '-' + 
    String(d.getDate()).padStart(2, '0') + 'T' + 
    String(d.getHours()).padStart(2, '0') + ':' + 
    String(d.getMinutes()).padStart(2, '0');
  assignTaskForm.deadline = formattedDeadline;
  showAssignModal.value = true;
};

const downloadTasksCSV = () => {
  const headers = ['Tugas', 'Pegawai', 'Tenggat', 'Status', 'Catatan Hasil'];
  const rows = filteredTasks.value.map(t => [
    t.task,
    t.employee_name,
    formatDate(t.deadline),
    t.status.toUpperCase(),
    t.result_note || '-'
  ]);
  const csvContent = [headers, ...rows].map(r => r.map(c => `"${String(c).replace(/"/g, '""')}"`).join(',')).join('\n');
  const blob = new Blob(['\uFEFF' + csvContent], { type: 'text/csv;charset=utf-8;' });
  const url = URL.createObjectURL(blob);
  const link = document.createElement('a');
  link.href = url;
  link.download = `Daftar_Tugas_${new Date().toLocaleDateString('en-CA')}.csv`;
  link.click();
  URL.revokeObjectURL(url);
};
const showAttachmentModal = ref(false);
const attachmentUrl = ref('');

// Modal Notes State
const showNoteModal = ref(false);
const processingDecision = ref(false);
const noteData = reactive({ type: '', id: null, status: '', note: '' });

 const handleFileUpload = (event, fieldKey) => {
   const file = event.target.files[0];
   if (!file) return;
   
   if (file.size > 2 * 1024 * 1024) {
     alert('File terlalu besar! Maksimal 2MB.');
     return;
   }

   const reader = new FileReader();
   reader.onload = (e) => {
     moduleFormData.value[fieldKey] = e.target.result;
   };
   reader.readAsDataURL(file);
 };

const handleSaveModuleData = async () => {
  const tab = currentAdminTab.value;
  const config = activeModule.value;
  const pkField = config?.pk || 'id';
  
  let payload = { ...moduleFormData.value };
  const pkValue = payload[pkField];
  const isEditing = payload._index !== undefined;

  delete payload._index;

  // Filter payload to only include fields defined in config
  if (config.fields && config.fields.length > 0) {
    const filteredPayload = {};
    config.fields.forEach(f => {
      if (payload[f.key] !== undefined) {
        let val = payload[f.key];
        if (val === "" && (f.key.endsWith('_id') || f.type === 'number')) val = null;
        filteredPayload[f.key] = val;
      }
    });
    payload = filteredPayload;
  }

  try {
    if (isEditing && pkValue) {
      await api.put(`/admin/master/${tab}/${pkValue}`, payload);
    } else {
      await api.post(`/admin/master/${tab}`, payload);
    }
    showModuleModal.value = false;
    moduleFormData.value = {};
    fetchModuleData();
  } catch (e) {
    console.error('Error saving module data:', e);
    alert('Gagal menyimpan data: ' + (e.response?.data?.message || 'Terjadi kesalahan sistem'));
  }
};

const openModuleModal = async () => {
  moduleFormData.value = {};
  
  // Pre-fetch offices if needed for the employee form
  if (currentAdminTab.value === 'employees' && (!moduleData.value.office || moduleData.value.office.length === 0)) {
     try {
       const res = await api.get('/admin/master/office');
       moduleData.value.office = res.data;
     } catch (e) {
       console.error('Gagal mengambil data kantor untuk dropdown');
     }
  }

  // Pre-fetch program groups if needed for the programs form
  if (currentAdminTab.value === 'programs' && (!moduleData.value.program_group || moduleData.value.program_group.length === 0)) {
     try {
       const res = await api.get('/admin/master/program_group');
       moduleData.value.program_group = res.data;
     } catch (e) {
       console.error('Gagal mengambil data rumpun untuk dropdown');
     }
  }

  // Pre-fetch payment via if needed for the transactions form
  if (currentAdminTab.value === 'transactions' && (!moduleData.value.payment_via || moduleData.value.payment_via.length === 0)) {
     try {
       const res = await api.get('/admin/master/payment_via');
       moduleData.value.payment_via = res.data;
     } catch (e) {
       console.error('Gagal mengambil data metode bayar untuk dropdown');
     }
  }

  // Pre-fetch donors data
  if (currentAdminTab.value === 'donors') {
    if (!moduleData.value.office) { try { const res = await api.get('/admin/master/office'); moduleData.value.office = res.data; } catch (e) {} }
    if (!moduleData.value.all_users) { try { const res = await api.get('/admin/master/users_mgt'); moduleData.value.all_users = res.data; } catch (e) {} }
    if (!moduleData.value.prospect_media) { try { const res = await api.get('/admin/master/prospect_media'); moduleData.value.prospect_media = res.data; } catch (e) {} }
  }
  
  showModuleModal.value = true;
};

const editModuleData = async (row, index) => {
  moduleFormData.value = { ...row, _index: index };
  
  // Trigger pre-fetches even on edit
  if (currentAdminTab.value === 'donors') {
    if (!moduleData.value.office) { try { const res = await api.get('/admin/master/office'); moduleData.value.office = res.data; } catch (e) {} }
    if (!moduleData.value.all_users) { try { const res = await api.get('/admin/master/users_mgt'); moduleData.value.all_users = res.data; } catch (e) {} }
    if (!moduleData.value.prospect_media) { try { const res = await api.get('/admin/master/prospect_media'); moduleData.value.prospect_media = res.data; } catch (e) {} }
  }
  
  showModuleModal.value = true;
};



const handleDeleteModuleData = async (row) => {
  const config = activeModule.value;
  const pkField = config?.pk || 'id';
  const pkValue = row[pkField];

  if (confirm(`Yakin ingin menghapus data ini?`)) {
    try {
      await api.delete(`/admin/master/${currentAdminTab.value}/${pkValue}`);
      fetchModuleData();
    } catch (e) {
      alert('Gagal menghapus data.');
    }
  }
};

const printReceipt = (row) => {
  const receiptHtml = `
    <html>
    <head>
      <title>Kwitansi Donasi - ${row.id}</title>
      <style>
        body { font-family: 'Inter', sans-serif; padding: 40px; color: #334155; }
        .receipt { border: 2px solid #f1f5f9; padding: 40px; border-radius: 24px; max-width: 600px; margin: auto; position: relative; }
        .header { text-align: center; margin-bottom: 40px; border-bottom: 1px solid #f1f5f9; padding-bottom: 20px; }
        .logo { font-size: 24px; font-weight: 900; font-style: italic; color: #16a34a; text-transform: uppercase; }
        .title { font-size: 18px; font-weight: 800; margin-top: 10px; color: #1e293b; }
        .item { display: flex; justify-between: space-between; margin: 15px 0; font-size: 14px; }
        .label { color: #94a3b8; font-weight: 600; text-transform: uppercase; font-size: 10px; letter-spacing: 0.1em; }
        .value { color: #1e293b; font-weight: 700; text-align: right; flex-grow: 1; }
        .amount { font-size: 24px; color: #16a34a; font-weight: 900; margin-top: 30px; text-align: center; background: #f0fdf4; padding: 15px; border-radius: 12px; }
        .footer { text-align: center; font-size: 10px; color: #94a3b8; margin-top: 40px; border-top: 1px solid #f1f5f9; padding-top: 20px; }
        @media print { .no-print { display: none; } }
      </style>
    </head>
    <body>
      <div class="receipt">
        <div class="header">
          <div class="logo">HUMANITY<span style="color:#334155">CARE</span></div>
          <div class="title">KWITANSI DONASI RESMI</div>
        </div>
        <div class="item"><span class="label">No. Transaksi</span> <span class="value">#TXN-${row.id}-${new Date().getFullYear()}</span></div>
        <div class="item"><span class="label">Nama Donatur</span> <span class="value">${row.donor_name}</span></div>
        <div class="item"><span class="label">Tanggal</span> <span class="value">${new Date(row.donation_date).toLocaleDateString('id-ID', {day: 'numeric', month: 'long', year: 'numeric'})}</span></div>
        <div class="item"><span class="label">Metode Bayar</span> <span class="value">${row.payment_method || 'Transfer Bank'}</span></div>
        <div class="item"><span class="label">Status</span> <span class="value" style="color: #16a34a">TERVERIFIKASI</span></div>
        
        <div class="amount">Rp ${Number(row.amount).toLocaleString('id-ID')}</div>
        
        <div class="footer">
          <p>Terima kasih atas bantuan Anda. Semoga menjadi amal jariyah yang tak terputus.</p>
          <p style="font-weight: 800; color: #1e293b; margin-top: 10px;">HumanityCare - Digital Donation System</p>
        </div>
      </div>
      <div style="text-align:center; margin-top: 20px;" class="no-print">
        <button onclick="window.print()" style="padding: 10px 20px; background: #16a34a; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 800;">CETAK SEKARANG</button>
      </div>
    </body>
    </html>
  `;
  const printWindow = window.open('', '_blank');
  printWindow.document.write(receiptHtml);
  printWindow.document.close();
};

const fetchModuleStats = async () => {
  try {
    const { data } = await api.get(`/admin/master/stats/${currentAdminTab.value}`);
    moduleStats.value = data;
  } catch (e) {
    console.error('Gagal mengambil statistik modul');
  }
};

const fetchModuleData = async () => {
  const tab = currentAdminTab.value;
  const config = moduleConfigs[tab];
  if (!config) return;
  
  if (config.isDashboard) {
    await fetchModuleStats();
    return;
  }
  
  try {
    const res = await api.get(`/admin/master/${tab}`);
    moduleData.value[tab] = res.data;
  } catch (e) {
    console.error(`Error fetching module ${tab}:`, e);
  }
};

const fetchMonitoringData = async () => {
  // Now handled by the consolidated fetchData() to reduce request count
  await fetchData();
};

const openAssignModal = (worker) => {
  assignTaskForm.id = null;
  assignTaskForm.user_id = worker.id;
  assignTaskForm.worker_name = worker.name;
  assignTaskForm.task = '';
  assignTaskForm.description = '';
  assignTaskForm.deadline = '';
  showAssignModal.value = true;
};

// Worker Detail Modal State
const selectedWorker = ref(null);
const showWorkerDetailModal = ref(false);

const workerTasks = computed(() => {
  if (!selectedWorker.value) return [];
  return allTasks.value.filter(t => t.user_id === selectedWorker.value.id);
});

const openWorkerDetail = (worker) => {
  selectedWorker.value = worker;
  showWorkerDetailModal.value = true;
};

const openAssignFromDetail = () => {
  showWorkerDetailModal.value = false;
  openAssignModal(selectedWorker.value);
};

const handleAssignTask = async () => {
  assigning.value = true;
  try {
    if (assignTaskForm.id) {
      await api.put(`/admin/tasks/${assignTaskForm.id}`, assignTaskForm);
      alert('Tugas berhasil diperbarui!');
    } else {
      await api.post('/admin/tasks', assignTaskForm);
      alert('Tugas berhasil diberikan!');
    }
    showAssignModal.value = false;
    fetchMonitoringData();
  } catch (e) {
    alert('Gagal menyimpan tugas.');
  } finally {
    assigning.value = false;
  }
};

const viewAttachment = (url) => {
  attachmentUrl.value = url;
  showAttachmentModal.value = true;
};

const formatTime = (log) => {
  if (!log) return '';
  const dateStr = log.created_at || log;
  const date = new Date(dateStr);
  return date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }).replace('.', ':');
};

const formatDate = (log) => {
  if (!log) return '';
  if (log.tgl_indo) return log.tgl_indo; // Prioritas data dari server (WITA)
  const date = new Date(log.created_at || log);
  return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
};

const fetchData = async () => {
  loading.value = true;
  try {
    const delay = (ms) => new Promise(res => setTimeout(res, ms));

    // 1. Fetch Basic Stats
    try {
      const resStats = await api.get('/admin/all-dashboard-data'); // Tetap pakai ini tapi ambil stats saja
      stats.value = resStats.data.stats || {};
      recentActivities.value = resStats.data.stats.recent_activities || [];
    } catch (e) { console.error('Stats failed', e); }

    await delay(500); // Tunggu 0.5 detik

    // 2. Fetch Users
    try {
      const resUsers = await api.get('/admin/users');
      users.value = resUsers.data || [];
    } catch (e) { console.error('Users failed', e); }

    await delay(500);

    // 3. Fetch Monitoring (Attendances & Performance)
    try {
      const [resAtt, resPerf] = await Promise.all([
        api.get('/admin/attendances'),
        api.get('/admin/worker-performance')
      ]);
      allAttendances.value = resAtt.data || [];
      workerPerformance.value = resPerf.data || [];
    } catch (e) { console.error('Monitoring failed', e); }

    await delay(500);

    // 4. Fetch Tasks & Pending
    try {
      const resTasks = await api.get('/admin/tasks');
      allTasks.value = resTasks.data || [];
    } catch (e) { console.error('Tasks failed', e); }

    await delay(300);

    try {
      const resCamp = await api.get('/admin/pending-campaigns');
      pendingCampaigns.value = resCamp.data || [];
    } catch (e) { console.error('Campaigns failed', e); }

    await delay(300);

    try {
      const resDon = await api.get('/admin/pending-donations');
      pendingDonations.value = resDon.data || [];
    } catch (e) { console.error('Donations failed', e); }

    await delay(300);

    try {
      const resZakat = await api.get('/admin/master/zakats');
      pendingZakats.value = (resZakat.data && resZakat.data.rows) || resZakat.data || [];
    } catch (e) { console.error('Zakats failed', e); }

    // Fetch site settings
    try {
       const resSet = await api.get('/settings');
       settingsData.value = resSet.data || {};
    } catch (e) {}

  } catch (error) {
    console.error('Fetch Error:', error);
  } finally {
    loading.value = false;
  }
};

// Watch for tab changes to fetch specific module data
watch(currentAdminTab, (newTab) => {
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
    if (formData.id) {
      // UPDATE
      const res = await api.put(`/admin/users/${formData.id}`, formData);
      alert(res.data.message || 'Informasi personel berhasil diperbarui');
    } else {
      // CREATE
      const res = await api.post('/admin/users', formData);
      alert(res.data.message || 'Pahlawan baru berhasil diaktifkan!');
    }
    
    showAddModal.value = false;
    formData.id = null; formData.name = ''; formData.email = ''; formData.password = '';
    fetchData();
  } catch (e) {
    const errorMsg = e.response?.data?.message || 'Gagal menyimpan data personel.';
    alert(errorMsg);
  }
};

const handleEditUser = (user) => {
  formData.id = user.id;
  formData.name = user.name;
  formData.email = user.email;
  formData.role = user.role;
  formData.password = ''; // Kosongkan password saat edit
  showAddModal.value = true;
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

const exportToCSV = () => {
  const data = filteredModuleData.value;
  if (!data || data.length === 0) return alert('Tidak ada data untuk diekspor');

  // Ambil kolom dari config untuk header CSV yang lebih rapi
  const config = moduleConfigs[currentAdminTab.value];
  const headers = config.columns.map(c => c.label);
  const keys = config.columns.map(c => c.key);

  const rows = data.map(item => 
    keys.map(key => {
      const val = item[key] === null ? '' : item[key];
      return `"${String(val).replace(/"/g, '""')}"`;
    }).join(',')
  );

  const csvContent = [headers.join(','), ...rows].join('\n');
  const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
  const link = document.createElement('a');
  const url = URL.createObjectURL(blob);
  
  link.setAttribute('href', url);
  link.setAttribute('download', `${currentAdminTab.value}_export_${new Date().toISOString().split('T')[0]}.csv`);
  link.style.visibility = 'hidden';
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
};

onMounted(() => {
  console.log('Admin Dashboard Mounted, Tabs:', sidebarTabs.value.length);
  currentAdminTab.value = 'dashboard';
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

/* Drawer Animation */
.drawer-enter-active,
.drawer-leave-active {
  transition: opacity 0.4s ease;
}

.drawer-enter-from,
.drawer-leave-to {
  opacity: 0;
}

.drawer-enter-active .relative,
.drawer-leave-active .relative {
  transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}

.drawer-enter-from .relative,
.drawer-leave-to .relative {
  transform: translateY(100%);
}
</style>