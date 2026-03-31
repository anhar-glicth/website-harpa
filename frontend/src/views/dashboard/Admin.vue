<template>
  <div class="min-h-screen bg-gray-50/50">
    <div class="max-w-7xl mx-auto px-4 py-6 md:py-10">
      <div class="flex flex-col lg:flex-row gap-6 md:gap-8 items-start">
        
        <Sidebar class="flex-shrink-0 w-full lg:w-64" />

        <main class="flex-grow space-y-8 md:space-y-10 animate-in fade-in slide-in-from-right-4 duration-700 w-full overflow-hidden">
          <header class="bg-white p-6 md:p-8 rounded-[2rem] md:rounded-[2.5rem] border border-gray-100 shadow-sm flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="space-y-1 text-center md:text-left">
              <h1 class="text-3xl font-black text-gray-900 tracking-tighter">ADMIN <span class="text-blue-600">DASHBOARD</span></h1>
              <p class="text-[10px] text-gray-400 font-bold uppercase tracking-[0.2em]">Oversight & Verification Center</p>
            </div>
            
            <button 
              @click="showAddModal = true"
              class="w-full md:w-auto flex items-center justify-center gap-3 bg-blue-600 text-white px-6 py-3.5 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-blue-700 transition-all shadow-xl shadow-blue-100"
            >
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/></svg>
              Tambah Staff Baru
            </button>
          </header>

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
            
            <div v-if="activeTab === 'dashboard'" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
              <div class="bg-white p-6 md:p-8 rounded-[2rem] border border-gray-100 shadow-sm">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Total Donasi</p>
                <p class="text-2xl md:text-3xl font-black text-blue-600 tracking-tighter">Rp {{ stats.totalDonations?.toLocaleString() || 0 }}</p>
              </div>
              <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Kampanye Berjalan</p>
                <p class="text-3xl font-black text-blue-600 tracking-tighter">{{ stats.activeCampaigns || 0 }}</p>
              </div>
              <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Pengajuan Mualaf</p>
                <p class="text-3xl font-black text-blue-600 tracking-tighter">{{ mualafRequests.length }}</p>
              </div>
            </div>

            <div v-if="activeTab === 'verify_campaign'" class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
               <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-blue-50/20">
                  <h3 class="font-black text-gray-900 uppercase tracking-tighter">Approval <span class="text-blue-600">Kampanye</span></h3>
                  <span class="bg-blue-600 text-white text-[10px] font-black px-3 py-1 rounded-lg">{{ pendingCampaigns.length }} Pending</span>
               </div>
               <div class="overflow-x-auto">
                 <table class="w-full text-left">
                   <thead class="bg-gray-50 text-[10px] uppercase font-black text-gray-400 tracking-widest">
                     <tr>
                       <th class="px-8 py-4">Informasi Kampanye</th>
                       <th class="px-8 py-4 text-center">Tindakan</th>
                     </tr>
                   </thead>
                   <tbody class="divide-y divide-gray-50">
                     <tr v-for="cam in pendingCampaigns" :key="cam.id" class="hover:bg-gray-50/50">
                       <td class="px-8 py-6 font-bold">{{ cam.title }}</td>
                       <td class="px-8 py-6 text-center">
                         <button @click="handleApproveCampaign(cam.id)" class="px-6 py-2 bg-blue-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-100">Setujui</button>
                       </td>
                     </tr>
                   </tbody>
                 </table>
               </div>
            </div>

            <div v-if="activeTab === 'verify_donation'" class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
               <div class="p-8 border-b border-gray-50 bg-blue-50/20">
                  <h3 class="font-black text-gray-900 uppercase tracking-tighter">Verifikasi <span class="text-blue-600">Donasi</span></h3>
               </div>
               <div class="overflow-x-auto">
                  <table class="w-full text-left">
                    <thead class="bg-gray-50 font-black text-[10px] uppercase text-gray-400 tracking-widest">
                      <tr>
                        <th class="px-8 py-4">Nama</th>
                        <th class="px-8 py-4">Jumlah</th>
                        <th class="px-8 py-4 text-center">Aksi</th>
                      </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                      <tr v-for="don in pendingDonations" :key="don.id">
                        <td class="px-8 py-6 font-bold">{{ don.donor_name }}</td>
                        <td class="px-8 py-6 font-black text-blue-600">Rp {{ don.amount.toLocaleString() }}</td>
                        <td class="px-8 py-6 text-center">
                           <button @click="handleVerifyDonation(don.id, 'Success')" class="bg-blue-600 text-white px-4 py-2 rounded-xl text-[10px] font-black tracking-widest">Terima</button>
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
                        <th class="px-8 py-4">Nama Pemohon</th>
                        <th class="px-8 py-4">Jadwal</th>
                        <th class="px-8 py-4 text-center">Aksi</th>
                      </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                      <tr v-for="req in mualafRequests" :key="req.id">
                        <td class="px-8 py-6 font-bold text-slate-800">{{ req.name }}</td>
                        <td class="px-8 py-6 font-medium text-slate-500 italic">{{ req.desired_date }}</td>
                        <td class="px-8 py-6 text-center">
                           <div class="flex gap-2 justify-center">
                              <button @click="handleUpdateMualaf(req.id, 'approved')" class="px-4 py-2 bg-blue-600 text-white text-[9px] font-black uppercase tracking-widest rounded-xl">Setujui</button>
                              <button @click="handleUpdateMualaf(req.id, 'rejected')" class="px-4 py-2 bg-red-500 text-white text-[9px] font-black uppercase tracking-widest rounded-xl">Tolak</button>
                           </div>
                        </td>
                      </tr>
                      <tr v-if="mualafRequests.length === 0">
                        <td colspan="4" class="px-8 py-20 text-center text-gray-400 font-bold uppercase tracking-widest text-xs italic">Belum ada pengajuan syahadat baru.</td>
                      </tr>
                    </tbody>
                  </table>
               </div>
            </div>

          </div>
        </main>
      </div>
    </div>

    <div v-if="showAddModal" class="fixed inset-0 bg-gray-900/70 backdrop-blur-md flex items-center justify-center p-4 z-50">
       <div class="bg-white rounded-[3rem] shadow-2xl w-full max-w-md overflow-hidden animate-in zoom-in duration-300">
         <div class="p-8 bg-blue-600 text-white flex justify-between items-center">
           <h3 class="text-xl font-black uppercase tracking-tighter">Tambah Pahlawan</h3>
           <button @click="showAddModal = false" class="text-white/60 hover:text-white">✕</button>
         </div>
         <form @submit.prevent="handleAddUser" class="p-8 space-y-5">
           <input v-model="formData.name" placeholder="Nama Lengkap" class="w-full p-4 bg-gray-50 rounded-2xl border-none outline-none focus:ring-2 focus:ring-blue-500 font-bold" required />
           <input v-model="formData.email" type="email" placeholder="Alamat Email" class="w-full p-4 bg-gray-50 rounded-2xl border-none outline-none focus:ring-2 focus:ring-blue-500 font-bold" required />
           <input v-model="formData.password" type="password" placeholder="Mulai Kata Laluan" class="w-full p-4 bg-gray-50 rounded-2xl border-none outline-none focus:ring-2 focus:ring-blue-500 font-bold" required />
           <select v-model="formData.role" class="w-full p-4 bg-gray-50 rounded-2xl border-none outline-none focus:ring-2 focus:ring-blue-500 font-black uppercase text-xs tracking-widest">
             <option value="field_worker">Field Worker</option>
             <option value="admin">Admin System</option>
           </select>
           <button type="submit" class="w-full py-4 bg-blue-600 text-white rounded-2xl font-black uppercase tracking-widest shadow-xl shadow-blue-100 hover:bg-blue-700 transition-all">Daftarkan Sekarang</button>
         </form>
       </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted, markRaw } from 'vue';
import api from '@/api';
import Sidebar from '@/components/Sidebar.vue';

const LayoutIcon = { template: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>' };
const UsersIcon = { template: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>' };
const VerifyIcon = { template: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"/><path d="m9 12 2 2 4-4"/></svg>' };
const HeartIcon = { template: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>' };
const MualafIcon = { template: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c0 2 2 3 6 3s6-1 6-3v-5"/></svg>' };

const tabs = [
  { id: 'dashboard', label: 'Ringkasan', icon: markRaw(LayoutIcon) },
  { id: 'team', label: 'Akses Staff', icon: markRaw(UsersIcon) },
  { id: 'verify_campaign', label: 'Approval Kampanye', icon: markRaw(VerifyIcon) },
  { id: 'verify_donation', label: 'Verifikasi Donasi', icon: markRaw(HeartIcon) },
  { id: 'verify_mualaf', label: 'Persetujuan Mualaf', icon: markRaw(MualafIcon) },
];

const activeTab = ref('dashboard');
const loading = ref(true);
const stats = ref({});
const teamList = ref([]);
const pendingCampaigns = ref([]);
const pendingDonations = ref([]);
const mualafRequests = ref([]);
const showAddModal = ref(false);
const formData = reactive({ name: '', email: '', password: '', role: 'field_worker' });

const fetchData = async () => {
  loading.value = true;
  try {
    const [statsRes, usersRes, campRes, donRes, mualafRes] = await Promise.all([
      api.get('/admin/stats'),
      api.get('/admin/users'),
      api.get('/admin/pending-campaigns'),
      api.get('/admin/pending-donations'),
      api.get('/admin/mualaf-requests')
    ]);
    stats.value = statsRes.data;
    teamList.value = usersRes.data.filter(u => u.role !== 'user');
    pendingCampaigns.value = campRes.data;
    pendingDonations.value = donRes.data;
    mualafRequests.value = mualafRes.data;
  } catch (error) {
    console.error(error);
  } finally {
    loading.value = false;
  }
};

const handleApproveCampaign = async (id) => {
  await api.post(`/admin/approve-campaign/${id}`);
  fetchData();
};

const handleVerifyDonation = async (id, status) => {
  await api.post(`/admin/verify-donation/${id}`, { status });
  fetchData();
};

const handleUpdateMualaf = async (id, status) => {
  await api.post(`/admin/verify-mualaf/${id}`, { status });
  fetchData();
}

const handleAddUser = async () => {
  await api.post('/admin/users', formData);
  showAddModal.value = false;
  fetchData();
};

onMounted(fetchData);
</script>