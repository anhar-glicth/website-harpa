<template>
  <div class="min-h-screen bg-gray-50/50">
    <!-- Menggunakan Layout Sidebar yang sama agar konsisten -->
    <div class="max-w-7xl mx-auto px-4 py-6 md:py-10">
      <div class="flex flex-col lg:flex-row gap-6 md:gap-8 items-start">
        
        <!-- Sidebar Khusus Admin -->
        <Sidebar class="flex-shrink-0 w-full lg:w-64" />

        <!-- Konten Utama Admin -->
        <main class="flex-grow space-y-8 md:space-y-10 animate-in fade-in slide-in-from-right-4 duration-700 w-full overflow-hidden">
          <!-- Header Ringkas Admin -->
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

          <!-- Navigasi Tab Internal (Scrollable di Mobile) -->
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

          <!-- Loader -->
          <div v-if="loading" class="py-24 md:py-40 text-center bg-white rounded-[2rem] md:rounded-[3rem] border border-dashed border-gray-200">
            <div class="animate-spin h-8 w-8 border-4 border-blue-600 border-t-transparent rounded-full mx-auto mb-4"></div>
            <p class="text-gray-400 font-black text-[10px] uppercase tracking-widest">Sinkronisasi Data...</p>
          </div>

          <!-- Tab Content Display -->
          <div v-else class="space-y-6 md:space-y-8 animate-in zoom-in-95 duration-500 pb-20 lg:pb-0">
            
            <!-- Dashboard Overview -->
            <div v-if="activeTab === 'dashboard'" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
              <div class="bg-white p-6 md:p-8 rounded-[2rem] border border-gray-100 shadow-sm">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Total Donasi</p>
                <p class="text-2xl md:text-3xl font-black text-emerald-600 tracking-tighter">Rp {{ stats.totalDonations?.toLocaleString() || 0 }}</p>
              </div>
              <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Jumlah Donatur Aktif</p>
                <p class="text-3xl font-black text-gray-900 tracking-tighter">{{ stats.totalDonors || 0 }} <span class="text-xs text-gray-400">Jiwa</span></p>
              </div>
              <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Kampanye Berjalan</p>
                <p class="text-3xl font-black text-blue-600 tracking-tighter">{{ stats.activeCampaigns || 0 }} <span class="text-xs text-gray-400">Projek</span></p>
              </div>
            </div>

            <!-- Approval Kampanye Table -->
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
                       <th class="px-8 py-4">Target Dana</th>
                       <th class="px-8 py-4 text-center">Tindakan</th>
                     </tr>
                   </thead>
                   <tbody class="divide-y divide-gray-50">
                     <tr v-for="cam in pendingCampaigns" :key="cam.id" class="hover:bg-gray-50/50">
                       <td class="px-8 py-6">
                         <div class="flex items-center gap-4">
                           <img :src="cam.foto" class="w-12 h-12 rounded-xl object-cover border" />
                           <div>
                             <p class="font-black text-gray-800 tracking-tight">{{ cam.title }}</p>
                             <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ cam.category }}</p>
                           </div>
                         </div>
                       </td>
                       <td class="px-8 py-6 font-black text-blue-600">Rp {{ cam.target_amount.toLocaleString() }}</td>
                       <td class="px-8 py-6 text-center">
                         <button @click="handleApproveCampaign(cam.id)" class="px-6 py-2 bg-blue-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-100">Setujui</button>
                       </td>
                     </tr>
                     <tr v-if="pendingCampaigns.length === 0">
                       <td colspan="3" class="px-8 py-20 text-center text-gray-400 font-bold uppercase tracking-widest text-xs italic">Semua pengajuan telah diproses.</td>
                     </tr>
                   </tbody>
                 </table>
               </div>
            </div>

            <!-- Verifikasi Donasi Table -->
            <div v-if="activeTab === 'verify_donation'" class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
               <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-emerald-50/20">
                  <h3 class="font-black text-gray-900 uppercase tracking-tighter">Verifikasi <span class="text-emerald-600">Donasi</span></h3>
                  <span class="bg-emerald-600 text-white text-[10px] font-black px-3 py-1 rounded-lg">{{ pendingDonations.length }} Transaksi</span>
               </div>
               <div class="overflow-x-auto">
                  <table class="w-full text-left">
                    <thead class="bg-gray-50 text-[10px] uppercase font-black text-gray-400 tracking-widest">
                      <tr>
                        <th class="px-8 py-4">Donatur & Projek</th>
                        <th class="px-8 py-4">Nominal</th>
                        <th class="px-8 py-4">Bukti</th>
                        <th class="px-8 py-4 text-center">Keputusan</th>
                      </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                      <tr v-for="don in pendingDonations" :key="don.id" class="hover:bg-gray-50/50">
                        <td class="px-8 py-6">
                           <p class="font-black text-gray-800 tracking-tight leading-none mb-1">{{ don.donor_name }}</p>
                           <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter truncate max-w-[150px]">{{ don.campaign_title }}</p>
                        </td>
                        <td class="px-8 py-6 font-black text-emerald-600">Rp {{ don.amount.toLocaleString() }}</td>
                        <td class="px-8 py-6">
                           <a :href="don.proof_image" target="_blank" class="text-blue-600 font-black text-[10px] uppercase tracking-widest hover:underline">Lihat Bukti</a>
                        </td>
                        <td class="px-8 py-6">
                           <div class="flex items-center justify-center gap-2">
                             <button @click="handleVerifyDonation(don.id, 'Success')" class="bg-emerald-600 text-white px-3 py-2 rounded-lg text-[10px] font-black uppercase tracking-widest hover:bg-emerald-700 transition-all">Terima</button>
                             <button @click="handleVerifyDonation(don.id, 'Failed')" class="bg-red-500 text-white px-3 py-2 rounded-lg text-[10px] font-black uppercase tracking-widest hover:bg-red-600 transition-all">Tolak</button>
                           </div>
                        </td>
                      </tr>
                      <tr v-if="pendingDonations.length === 0">
                        <td colspan="4" class="px-8 py-20 text-center text-gray-400 font-bold uppercase tracking-widest text-xs italic">Belum ada donasi baru.</td>
                      </tr>
                    </tbody>
                  </table>
               </div>
            </div>

            <!-- Team & Donors lists also updated for consistency... (Omitted for brevity but structure remains) -->
            <div v-if="activeTab === 'team'" class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
               <div class="p-8 border-b border-gray-50 bg-gray-50/30">
                 <h3 class="font-black text-gray-900 uppercase tracking-tighter">Pengurusan <span class="text-blue-600">Akses Staff</span></h3>
               </div>
               <div class="overflow-x-auto">
                 <table class="w-full text-left">
                   <thead class="bg-gray-50 text-[10px] font-black uppercase text-gray-400 tracking-widest font-sans">
                     <tr>
                        <th class="px-8 py-4">Nama</th>
                        <th class="px-8 py-4">Email</th>
                        <th class="px-8 py-4">Peranan</th>
                        <th class="px-8 py-4 text-center">Tindakan</th>
                     </tr>
                   </thead>
                   <tbody class="divide-y divide-gray-50">
                      <tr v-for="user in teamList" :key="user.id">
                        <td class="px-8 py-6 font-bold text-gray-800">{{ user.name }}</td>
                        <td class="px-8 py-6 text-gray-500 font-medium">{{ user.email }}</td>
                        <td class="px-8 py-6">
                          <span class="px-3 py-1 bg-blue-50 text-blue-600 text-[10px] font-black uppercase rounded-lg tracking-widest">{{ user.role }}</span>
                        </td>
                        <td class="px-8 py-6 text-center">
                          <button @click="handleDelete(user.id)" class="text-gray-300 hover:text-red-500 transition-colors">
                             <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/></svg>
                          </button>
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

    <!-- Modal Form (Tetap sama) -->
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
import { ref, reactive, onMounted, computed, markRaw } from 'vue';
import api from '@/api';
import Sidebar from '@/components/Sidebar.vue';

// Icons using SVG strings or simple components
const LayoutIcon = { template: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>' };
const UsersIcon = { template: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>' };
const VerifyIcon = { template: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"/><path d="m9 12 2 2 4-4"/></svg>' };
const HeartIcon = { template: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>' };

// Configuration
const tabs = [
  { id: 'dashboard', label: 'Ringkasan', icon: markRaw(LayoutIcon) },
  { id: 'team', label: 'Akses Staff', icon: markRaw(UsersIcon) },
  { id: 'verify_campaign', label: 'Approval Kampanye', icon: markRaw(VerifyIcon) },
  { id: 'verify_donation', label: 'Verifikasi Donasi', icon: markRaw(HeartIcon) },
  { id: 'donors', label: 'Donatur', icon: markRaw(HeartIcon) }
];

// State
const activeTab = ref('dashboard');
const loading = ref(true);
const stats = ref({});
const teamList = ref([]);
const donorList = ref([]);
const pendingCampaigns = ref([]);
const pendingDonations = ref([]);
const showAddModal = ref(false);
const formData = reactive({ name: '', email: '', password: '', role: 'field_worker' });

// Fetch Data
const fetchData = async () => {
  loading.value = true;
  try {
    const [statsRes, usersRes, campRes, donRes] = await Promise.all([
      api.get('/admin/stats'),
      api.get('/admin/users'),
      api.get('/admin/pending-campaigns'),
      api.get('/admin/pending-donations')
    ]);
    stats.value = statsRes.data;
    teamList.value = usersRes.data.filter(u => u.role !== 'user');
    donorList.value = usersRes.data.filter(u => u.role === 'user');
    pendingCampaigns.value = campRes.data;
    pendingDonations.value = donRes.data;
  } catch (error) {
    console.error("Fetch Data Error:", error);
  } finally {
    loading.value = false;
  }
};

const handleApproveCampaign = async (id) => {
  try {
    await api.post(`/admin/approve-campaign/${id}`);
    fetchData();
  } catch (error) { alert("Gagal menyetujui kampanye"); }
};

const handleVerifyDonation = async (id, status) => {
  try {
    await api.post(`/admin/verify-donation/${id}`, { status });
    fetchData();
  } catch (error) { alert("Gagal memverifikasi donasi"); }
};

const handleAddUser = async () => {
  try {
    await api.post('/admin/users', formData);
    showAddModal.value = false;
    fetchData();
  } catch (error) { alert("Gagal menambah user"); }
};

const handleDelete = async (id) => {
  if(!confirm("Hapus akun ini secara permanen?")) return;
  try {
    await api.delete(`/admin/users/${id}`);
    fetchData();
  } catch (error) { alert("Gagal menghapus user"); }
};

onMounted(fetchData);
</script>

<style scoped>
.animate-in { animation-fill-mode: both; }
</style>