<template>
  <div class="min-h-screen bg-gray-50/50">
    <div class="max-w-7xl mx-auto px-4 py-6 md:py-10">
      <div class="flex flex-col lg:flex-row gap-6 md:gap-8 items-start">
        
        <Sidebar class="flex-shrink-0 w-full lg:w-64" />

        <main class="flex-grow space-y-8 md:space-y-10 animate-in fade-in slide-in-from-right-4 duration-700 w-full overflow-hidden">
          <header class="bg-white p-6 md:p-8 rounded-[2rem] md:rounded-[2.5rem] border border-gray-100 shadow-sm flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="space-y-1 text-center md:text-left">
              <h1 class="text-3xl font-black text-gray-900 tracking-tighter uppercase italic">ADMIN <span class="text-blue-600">PANEL</span></h1>
              <p class="text-[10px] text-gray-400 font-bold uppercase tracking-[0.2em]">Pusat Kendali & CMS HumanityCare</p>
            </div>
            
            <div class="flex gap-3 w-full md:w-auto">
                <button 
                  @click="activeTab = 'settings'"
                  class="flex-1 md:flex-none flex items-center justify-center gap-3 bg-white border-2 border-gray-100 text-gray-900 px-6 py-3.5 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:border-blue-600 transition-all shadow-sm"
                >
                  Edit Tampilan
                </button>
                <button 
                  @click="showAddModal = true"
                  class="flex-1 md:flex-none flex items-center justify-center gap-3 bg-blue-600 text-white px-6 py-3.5 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-blue-700 transition-all shadow-xl shadow-blue-100"
                >
                  Tambah Staff
                </button>
            </div>
          </header>

          <!-- Tabs Navigation -->
          <div class="flex items-center gap-2 bg-gray-100 p-1.5 rounded-2xl w-full overflow-x-auto no-scrollbar">
            <button
              v-for="tab in tabs"
              :key="tab.id"
              @click="activeTab = tab.id"
              class="whitespace-nowrap flex items-center gap-2 px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all"
              :class="activeTab === tab.id ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-500 hover:text-blue-600'"
            >
              <component :is="tab.icon" class="w-4 h-4" />
              {{ tab.label }}
            </button>
          </div>

          <div v-if="loading" class="py-24 md:py-40 text-center bg-white rounded-[2rem] md:rounded-[3rem] border border-dashed border-gray-200">
            <div class="animate-spin h-8 w-8 border-4 border-blue-600 border-t-transparent rounded-full mx-auto mb-4"></div>
            <p class="text-gray-400 font-black text-[10px] uppercase tracking-widest">Sinkronisasi Data...</p>
          </div>

          <div v-else class="space-y-6 md:space-y-8 animate-in zoom-in-95 duration-500 pb-20 lg:pb-0">
            
            <!-- Dashboard Tab -->
            <div v-if="activeTab === 'dashboard'" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
              <div class="bg-white p-6 md:p-8 rounded-[2rem] border border-gray-100 shadow-sm bg-gradient-to-br from-white to-blue-50/30">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Total Donasi</p>
                <p class="text-2xl md:text-3xl font-black text-blue-600 tracking-tighter">Rp {{ stats.totalDonations?.toLocaleString() || 0 }}</p>
              </div>
              <div class="bg-white p-6 md:p-8 rounded-[2rem] border border-gray-100 shadow-sm">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Kampanye Aktif</p>
                <p class="text-3xl font-black text-blue-600 tracking-tighter">{{ stats.activeCampaigns || 0 }}</p>
              </div>
              <div class="bg-white p-6 md:p-8 rounded-[2rem] border border-gray-100 shadow-sm">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Zakat & Syahadat</p>
                <p class="text-3xl font-black text-blue-600 tracking-tighter">{{ pendingZakats.length + mualafRequests.length }} Pending</p>
              </div>
            </div>

            <!-- User Management Tab -->
            <div v-if="activeTab === 'users'" class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
               <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-blue-50/20">
                  <h3 class="font-black text-gray-900 uppercase tracking-tighter italic">Manajemen <span class="text-blue-600">Pengguna</span></h3>
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
                          <span :class="u.role === 'admin' ? 'text-red-500' : (u.role === 'field_worker' ? 'text-blue-500' : 'text-slate-400')">
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
               <div class="p-8 border-b border-gray-50 bg-blue-50/20 text-center md:text-left">
                  <h3 class="font-black text-gray-900 uppercase tracking-tighter italic">Verifikasi <span class="text-blue-600">Zakat</span></h3>
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
                              <span class="text-blue-600 font-black">Rp {{ Number(z.amount).toLocaleString() }}</span>
                           </div>
                        </td>
                        <td class="px-8 py-6 text-center">
                           <div class="flex gap-2 justify-center">
                              <button @click="triggerNoteModal('zakat', z.id, 'Success')" class="bg-blue-600 text-white px-4 py-2 rounded-xl text-[9px] font-black tracking-widest uppercase hover:scale-105 transition">Terima</button>
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
               <div class="p-8 border-b border-gray-50 bg-blue-50/20">
                  <h3 class="font-black text-gray-900 uppercase tracking-tighter">Pengaturan Eksternal <span class="text-blue-600">(CMS)</span></h3>
               </div>
               <div class="p-8 space-y-10">
                  <!-- Section: Hero -->
                  <div class="space-y-6">
                     <h4 class="text-[10px] font-black uppercase tracking-widest text-blue-600 border-l-4 border-blue-600 pl-4">Halaman Utama (Hero)</h4>
                     <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                           <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Judul Headline</label>
                           <textarea v-model="settingsData.hero_title" rows="2" class="w-full p-4 bg-gray-50 rounded-2xl border-none outline-none focus:ring-2 focus:ring-blue-500 font-bold text-xs"></textarea>
                        </div>
                        <div class="space-y-2">
                           <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest">URL Gambar Hero</label>
                           <input v-model="settingsData.hero_image" class="w-full p-4 bg-gray-50 rounded-2xl border-none outline-none focus:ring-2 focus:ring-blue-500 font-bold text-xs" />
                        </div>
                        <div class="md:col-span-2 space-y-2">
                           <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Sub-Judul Hero</label>
                           <textarea v-model="settingsData.hero_subtitle" rows="3" class="w-full p-4 bg-gray-50 rounded-2xl border-none outline-none focus:ring-2 focus:ring-blue-500 font-bold text-xs"></textarea>
                        </div>
                     </div>
                  </div>

                  <!-- Section: About -->
                  <div class="space-y-6 pt-6 border-t border-gray-100">
                     <h4 class="text-[10px] font-black uppercase tracking-widest text-blue-600 border-l-4 border-blue-600 pl-4">Halaman Tentang Kami</h4>
                     <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                           <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Misi Kami (Teks)</label>
                           <textarea v-model="settingsData.about_mission" rows="4" class="w-full p-4 bg-gray-50 rounded-2xl border-none outline-none focus:ring-2 focus:ring-blue-500 font-bold text-xs italic"></textarea>
                        </div>
                        <div class="space-y-2">
                           <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest">URL Gambar Misi</label>
                           <input v-model="settingsData.about_image" class="w-full p-4 bg-gray-50 rounded-2xl border-none outline-none focus:ring-2 focus:ring-blue-500 font-bold text-xs" />
                           <div class="mt-4 rounded-2xl overflow-hidden aspect-video border border-gray-100">
                              <img :src="settingsData.about_image" class="w-full h-full object-cover opacity-80" />
                           </div>
                        </div>
                     </div>
                  </div>

                  <button @click="handleSaveSettings" :disabled="savingSettings" class="w-full py-5 bg-blue-600 text-white rounded-[2rem] font-black uppercase tracking-widest shadow-xl shadow-blue-100 hover:bg-blue-700 transition active:scale-95 disabled:opacity-50">
                     {{ savingSettings ? 'Menyimpan...' : 'Perbarui Seluruh Konten' }}
                  </button>
               </div>
            </div>

            <!-- Other Approval Tabs -->
            <div v-if="activeTab === 'verify_campaign'" class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
               <div class="p-8 border-b border-gray-50 bg-blue-50/20">
                  <h3 class="font-black text-gray-900 uppercase tracking-tighter">Approval <span class="text-blue-600">Kampanye</span></h3>
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
                         <button @click="handleApproveCampaign(cam.id)" class="px-6 py-2 bg-blue-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-100">Setujui</button>
                       </td>
                     </tr>
                   </tbody>
                 </table>
               </div>
            </div>

            <div v-if="activeTab === 'verify_donation'" class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
               <div class="p-8 border-b border-gray-50 bg-blue-50/20 text-center md:text-left">
                  <h3 class="font-black text-gray-900 uppercase tracking-tighter italic">Konfirmasi <span class="text-blue-600">Donasi</span></h3>
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
                        <td class="px-8 py-6 text-blue-600 font-black">Rp {{ don.amount.toLocaleString() }}</td>
                        <td class="px-8 py-6 text-center">
                          <div class="flex gap-2 justify-center">
                             <button @click="triggerNoteModal('donation', don.id, 'Success')" class="bg-blue-600 text-white px-4 py-2 rounded-xl text-[9px] font-black tracking-widest uppercase hover:scale-105 transition">Terima</button>
                             <button @click="triggerNoteModal('donation', don.id, 'Failed')" class="bg-red-500 text-white px-4 py-2 rounded-xl text-[9px] font-black tracking-widest uppercase hover:scale-105 transition">Tolak</button>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
               </div>
            </div>

            <div v-if="activeTab === 'verify_mualaf'" class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
               <div class="p-8 border-b border-gray-50 bg-blue-50/20">
                  <h3 class="font-black text-gray-900 uppercase tracking-tighter italic">Persetujuan <span class="text-blue-600">Syahadat</span></h3>
               </div>
               <div class="overflow-x-auto">
                  <table class="w-full text-left">
                    <thead class="bg-gray-50 text-[10px] uppercase font-black text-gray-400 tracking-widest">
                      <tr>
                        <th class="px-8 py-4">Calon Mualaf</th>
                        <th class="px-8 py-4">Jadwal Permintaan</th>
                        <th class="px-8 py-4 text-center">Keputusan</th>
                      </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 uppercase text-[10px] font-black tracking-tight">
                      <tr v-for="req in mualafRequests" :key="req.id">
                        <td class="px-8 py-6">{{ req.name }}</td>
                        <td class="px-8 py-6 italic text-slate-500">{{ req.desired_date }}</td>
                        <td class="px-8 py-6 text-center">
                           <div class="flex gap-2 justify-center">
                              <button @click="triggerNoteModal('mualaf', req.id, 'approved')" class="px-4 py-2 bg-blue-600 text-white text-[9px] font-black uppercase tracking-widest rounded-xl transition hover:scale-105">Terima</button>
                              <button @click="triggerNoteModal('mualaf', req.id, 'rejected')" class="px-4 py-2 bg-red-500 text-white text-[9px] font-black uppercase tracking-widest rounded-xl transition hover:scale-105">Tolak</button>
                           </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
               </div>
            </div>

          </div>
        </main>
      </div>
    </div>

    <!-- Modal: Note for Approval/Rejection -->
    <div v-if="showNoteModal" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm flex items-center justify-center p-4 z-[100]">
       <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-md overflow-hidden animate-in zoom-in duration-300">
         <div class="p-8 pb-4">
            <div class="flex justify-between items-start mb-6">
               <div class="space-y-1">
                  <h3 class="text-xl font-black uppercase tracking-tighter">Berikan <span class="text-blue-600">Catatan</span></h3>
                  <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Feedback untuk pendaftar/pembayar</p>
               </div>
               <button @click="showNoteModal = false" class="text-gray-300 hover:text-gray-600">✕</button>
            </div>
            
            <div class="space-y-4">
               <div class="p-4 bg-gray-50 rounded-2xl flex items-center gap-3">
                  <div :class="noteData.status.includes('Success') || noteData.status.includes('approved') ? 'bg-blue-100 text-blue-600' : 'bg-red-100 text-red-600'" class="w-10 h-10 rounded-xl flex items-center justify-center text-lg">
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
                  class="w-full p-6 bg-gray-50 rounded-[2rem] border-2 border-transparent focus:border-blue-600 outline-none font-bold text-xs leading-relaxed transition-all"
               ></textarea>
            </div>
         </div>
         <div class="p-8 pt-4">
            <button 
               @click="submitDecisionWithNote" 
               :disabled="processingDecision"
               class="w-full py-5 bg-blue-600 text-white rounded-[2rem] font-black uppercase tracking-widest shadow-xl shadow-blue-100 hover:bg-blue-700 transition active:scale-95 disabled:opacity-50"
            >
               {{ processingDecision ? 'Memproses...' : 'Konfirmasi & Kirim Catatan' }}
            </button>
         </div>
       </div>
    </div>

    <!-- Modal: Add Staff/User (Existing) -->
    <div v-if="showAddModal" class="fixed inset-0 bg-gray-900/70 backdrop-blur-md flex items-center justify-center p-4 z-50">
       <div class="bg-white rounded-[3rem] shadow-2xl w-full max-w-md overflow-hidden animate-in zoom-in duration-300">
         <div class="p-8 bg-blue-600 text-white flex justify-between items-center">
           <h3 class="text-xl font-black uppercase tracking-tighter italic">Pendaftaran <span class="text-blue-200">Personel</span></h3>
           <button @click="showAddModal = false" class="text-white/60 hover:text-white">✕</button>
         </div>
         <form @submit.prevent="handleAddUser" class="p-8 space-y-5">
           <input v-model="formData.name" placeholder="Nama Lengkap" class="w-full p-4 bg-gray-50 rounded-2xl border-none outline-none focus:ring-2 focus:ring-blue-500 font-bold text-sm" required />
           <input v-model="formData.email" type="email" placeholder="Alamat Email" class="w-full p-4 bg-gray-50 rounded-2xl border-none outline-none focus:ring-2 focus:ring-blue-500 font-bold text-sm" required />
           <input v-model="formData.password" type="password" placeholder="Kata Sandi Akses" class="w-full p-4 bg-gray-50 rounded-2xl border-none outline-none focus:ring-2 focus:ring-blue-500 font-bold text-sm" required />
           <select v-model="formData.role" class="w-full p-4 bg-gray-50 rounded-2xl border-none outline-none focus:ring-2 focus:ring-blue-500 font-black uppercase text-[10px] tracking-widest">
             <option value="field_worker">Field Worker</option>
             <option value="admin">Admin</option>
             <option value="user">User</option>
           </select>
           <button type="submit" class="w-full py-5 bg-blue-600 text-white rounded-[2rem] font-black uppercase tracking-widest hover:bg-blue-700">Aktifkan Akun</button>
         </form>
       </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted, markRaw } from 'vue';
import api from '@/api';
import Sidebar from '@/components/Sidebar.vue';

// Icons
const LayoutIcon = { template: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>' };
const UsersIcon = { template: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>' };
const HammerIcon = { template: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a2 2 0 0 1-2.79-2.79L14.7 6.3Z"/><path d="M5 21a2 2 0 0 0 4 0l3-3a6 6 0 0 1 5-1V8M9 15.5 4.5 11M11 15l-3-3M22 22l-2-2"/></svg>' };
const ZakatIcon = { template: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>' };
const VerifyIcon = { template: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"/><path d="m9 12 2 2 4-4"/></svg>' };
const HeartIcon = { template: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>' };
const MualafIcon = { template: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c0 2 2 3 6 3s6-1 6-3v-5"/></svg>' };

const tabs = [
  { id: 'dashboard', label: 'Ringkasan', icon: markRaw(LayoutIcon) },
  { id: 'users', label: 'Manajemen User', icon: markRaw(UsersIcon) },
  { id: 'verify_zakat', label: 'Verifikasi Zakat', icon: markRaw(ZakatIcon) },
  { id: 'settings', label: 'CMS Tampilan', icon: markRaw(HammerIcon) },
  { id: 'verify_campaign', label: 'Approval Kampanye', icon: markRaw(VerifyIcon) },
  { id: 'verify_donation', label: 'Verifikasi Donasi', icon: markRaw(HeartIcon) },
  { id: 'verify_mualaf', label: 'Verifikasi Syahadat', icon: markRaw(MualafIcon) },
];

const activeTab = ref('dashboard');
const loading = ref(true);
const stats = ref({});
const users = ref([]);
const pendingCampaigns = ref([]);
const pendingDonations = ref([]);
const mualafRequests = ref([]);
const pendingZakats = ref([]);
const settingsData = ref({});
const savingSettings = ref(false);

const showAddModal = ref(false);
const formData = reactive({ name: '', email: '', password: '', role: 'field_worker' });

// Modal Notes State
const showNoteModal = ref(false);
const processingDecision = ref(false);
const noteData = reactive({ type: '', id: null, status: '', note: '' });

const fetchData = async () => {
  loading.value = true;
  try {
    const [statsRes, usersRes, campRes, donRes, mualafRes, settingsRes, zakatRes] = await Promise.all([
      api.get('/admin/stats'),
      api.get('/admin/users'),
      api.get('/admin/pending-campaigns'),
      api.get('/admin/pending-donations'),
      api.get('/admin/mualaf-requests'),
      api.get('/settings'),
      api.get('/zakats/pending')
    ]);
    stats.value = statsRes.data;
    users.value = usersRes.data;
    pendingCampaigns.value = campRes.data;
    pendingDonations.value = donRes.data;
    mualafRequests.value = mualafRes.data;
    settingsData.value = settingsRes.data;
    pendingZakats.value = zakatRes.data;
  } catch (error) {
    console.error(error);
  } finally {
    loading.value = false;
  }
};

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
      } else if (noteData.type === 'mualaf') {
         await api.post(`/admin/verify-mualaf/${noteData.id}`, { status: noteData.status, admin_note: noteData.note });
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

onMounted(fetchData);
</script>

<style scoped>
.no-scrollbar::-webkit-scrollbar { display: none; }
.no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>