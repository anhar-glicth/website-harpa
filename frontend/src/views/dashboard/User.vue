<template>
  <div class="min-h-screen bg-gray-50/50">
    <div class="max-w-7xl mx-auto px-4 py-6 md:py-10">
      <div class="flex flex-col lg:flex-row gap-8 items-start">
        <!-- Sidebar Navigasi -->
        <Sidebar class="flex-shrink-0 w-full lg:w-72" />

        <!-- Konten Utama Dashboard Donatur -->
        <main class="flex-grow space-y-8 animate-in fade-in slide-in-from-right-4 duration-700 w-full pb-24 lg:pb-0">
          
          <!-- Header Modern -->
          <header class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm flex flex-col md:flex-row justify-between items-center gap-6 relative overflow-hidden group">
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-blue-600/5 rounded-full group-hover:scale-150 transition-transform duration-1000"></div>
            
            <div class="space-y-2 text-center md:text-left relative z-10">
              <h1 class="text-3xl font-black text-gray-900 tracking-tighter uppercase italic leading-none">
                Donatur <span class="text-blue-600">Portal</span>
              </h1>
              <p class="text-[10px] text-gray-400 font-bold uppercase tracking-[0.2em]">Pusat Kebaikan & Kontribusi Anda</p>
            </div>

            <div class="flex gap-4 relative z-10">
              <router-link to="/" class="px-8 py-4 bg-slate-900 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-blue-600 transition-all shadow-xl shadow-slate-200">
                <i class="fas fa-heart mr-2 text-[8px]"></i> Jelajah Kampanye
              </router-link>
            </div>
          </header>

          <!-- Statistik Ringkas -->
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm relative overflow-hidden group">
              <div class="absolute right-0 bottom-0 opacity-5 text-6xl -mb-4 -mr-4 transform rotate-12 group-hover:rotate-0 transition-transform duration-500">
                <i class="fas fa-hand-holding-heart"></i>
              </div>
              <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2">Total Donasi</p>
              <p class="text-3xl font-black text-blue-600 tracking-tighter italic">Rp {{ formatCurrency(totalAmount) }}</p>
            </div>

            <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm relative overflow-hidden group">
              <div class="absolute right-0 bottom-0 opacity-5 text-6xl -mb-4 -mr-4 transform rotate-12 group-hover:rotate-0 transition-transform duration-500">
                <i class="fas fa-bullseye"></i>
              </div>
              <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2">Kampanye Didukung</p>
              <p class="text-3xl font-black text-gray-900 tracking-tighter italic">{{ donations.length }} <span class="text-xs text-gray-300 font-bold uppercase tracking-widest ml-1">Proyek</span></p>
            </div>

            <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm relative overflow-hidden group lg:col-span-1">
              <div class="absolute right-0 bottom-0 opacity-5 text-6xl -mb-4 -mr-4 transform rotate-12 group-hover:rotate-0 transition-transform duration-500">
                <i class="fas fa-award"></i>
              </div>
              <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2">Peringkat Donatur</p>
              <p class="text-lg font-black text-amber-500 tracking-tighter uppercase italic">Donatur Setia</p>
            </div>
          </div>

          <!-- Navigasi Tab Internal -->
          <div class="bg-white p-2 rounded-[2rem] border border-gray-100 shadow-sm inline-flex gap-2">
             <button @click="activeTab = 'donations'" 
               :class="['px-8 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all', 
               activeTab === 'donations' ? 'bg-blue-600 text-white shadow-lg shadow-blue-100' : 'text-gray-400 hover:text-gray-900']">
               Riwayat Kebaikan
             </button>
             <button @click="activeTab = 'impact'" 
               :class="['px-8 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all', 
               activeTab === 'impact' ? 'bg-blue-600 text-white shadow-lg shadow-blue-100' : 'text-gray-400 hover:text-gray-900']">
               Kabar Dampak
             </button>
          </div>

          <!-- Tab: Riwayat Donasi -->
          <div v-if="activeTab === 'donations'" class="space-y-6 animate-in zoom-in-95 duration-500">
             <div class="bg-white rounded-[3rem] border border-gray-100 shadow-sm overflow-hidden">
               <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
                  <h3 class="font-black text-gray-900 uppercase tracking-tighter italic">Log <span class="text-blue-600">Transaksi</span></h3>
                  <div class="flex gap-2">
                    <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                    <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Enkripsi Aman SSL</span>
                  </div>
               </div>
               <div class="overflow-x-auto">
                  <table class="w-full text-left">
                     <thead class="bg-gray-50 text-[10px] text-gray-400 font-black uppercase tracking-[0.2em]">
                        <tr>
                           <th class="px-8 py-5">#</th>
                           <th class="px-8 py-5">Program Kemanusiaan</th>
                           <th class="px-8 py-5">Nominal</th>
                           <th class="px-8 py-5">Metode</th>
                           <th class="px-8 py-5 text-center">Status</th>
                        </tr>
                     </thead>
                     <tbody class="divide-y divide-gray-50">
                        <tr v-for="(d, idx) in donations" :key="d.id" class="hover:bg-blue-50/20 transition-colors group">
                           <td class="px-8 py-6 text-[10px] font-black text-gray-300">{{ String(idx+1).padStart(2, '0') }}</td>
                           <td class="px-8 py-6">
                              <p class="font-black text-gray-900 uppercase tracking-tight italic">{{ d.campaign_title }}</p>
                              <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest">{{ formatDate(d.created_at) }}</p>
                           </td>
                           <td class="px-8 py-6">
                             <p class="font-black text-blue-600 text-sm tracking-tighter italic">Rp {{ formatCurrency(d.amount) }}</p>
                           </td>
                           <td class="px-8 py-6 text-[10px] font-bold text-gray-500 uppercase tracking-widest">{{ d.payment_method || 'Transfer' }}</td>
                           <td class="px-8 py-6 text-center">
                              <span :class="['px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest shadow-sm', 
                                d.payment_status === 'Success' ? 'bg-green-50 text-green-600' : 'bg-amber-50 text-amber-600']">
                                {{ d.payment_status === 'Success' ? 'Berhasil' : 'Menunggu' }}
                              </span>
                           </td>
                        </tr>
                        <tr v-if="donations.length === 0">
                           <td colspan="5" class="px-8 py-24 text-center">
                              <div class="w-20 h-20 bg-gray-50 text-gray-200 rounded-3xl flex items-center justify-center text-3xl mx-auto mb-4">
                                <i class="fas fa-box-open"></i>
                              </div>
                              <p class="text-gray-400 italic font-black uppercase tracking-widest text-[10px]">Belum ada riwayat kebaikan.</p>
                           </td>
                        </tr>
                     </tbody>
                  </table>
               </div>
             </div>
          </div>

          <!-- Tab: Kabar Dampak (Placeholder for News updates on campaigns supported) -->
          <div v-if="activeTab === 'impact'" class="space-y-8 animate-in zoom-in-95 duration-500">
             <div class="bg-slate-900 p-12 rounded-[4rem] text-white relative overflow-hidden group">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-600/30 via-transparent to-transparent"></div>
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-10">
                   <div class="space-y-6 text-center md:text-left">
                      <div class="inline-block px-4 py-1 bg-blue-500/20 border border-blue-500/30 rounded-full text-[10px] font-black uppercase tracking-widest text-blue-400">Update Kebaikan</div>
                      <h3 class="text-4xl font-black uppercase italic tracking-tighter leading-tight">Melihat Hasil <br><span class="text-blue-500">Donasi Anda</span></h3>
                      <p class="text-sm text-gray-400 font-medium max-w-sm leading-relaxed">Ikuti perkembangan kampanye yang Anda dukung dan lihat bagaimana kontribusi Anda merubah hidup mereka.</p>
                   </div>
                   <div class="w-24 h-24 bg-white/10 backdrop-blur-2xl rounded-full flex items-center justify-center text-3xl border border-white/10">
                      <i class="fas fa-newspaper text-blue-400 animate-pulse"></i>
                   </div>
                </div>
             </div>
             
             <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div v-for="i in 2" :key="i" class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm p-8 space-y-6 opacity-50 grayscale cursor-not-allowed">
                   <div class="h-40 bg-gray-50 rounded-[1.5rem] animate-pulse"></div>
                   <div class="space-y-3">
                      <div class="h-4 bg-gray-50 rounded-full w-2/3"></div>
                      <div class="h-2 bg-gray-50 rounded-full w-full"></div>
                      <div class="h-2 bg-gray-50 rounded-full w-full"></div>
                   </div>
                   <p class="text-[9px] font-black text-gray-300 uppercase tracking-widest text-center italic">Fitur Kabar Dampak Sedang Dikembangkan</p>
                </div>
             </div>
          </div>

        </main>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import api from '@/api';
import Sidebar from '@/components/Sidebar.vue';

const activeTab = ref('donations');
const donations = ref([]);
const loading = ref(true);

const totalAmount = computed(() => {
  return donations.value
    .filter(d => d.payment_status === 'Success')
    .reduce((sum, d) => sum + Number(d.amount), 0);
});

const formatCurrency = (val) => new Intl.NumberFormat('id-ID').format(val || 0);

const formatDate = (dateStr) => {
  if (!dateStr) return '-';
  return new Date(dateStr).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
};

const fetchData = async () => {
  loading.value = true;
  try {
    const [donRes] = await Promise.all([
      api.get('/donations/my-donations'),
    ]);
    donations.value = donRes.data;
  } catch (err) {
    console.error(err);
  } finally {
    loading.value = false;
  }
};

onMounted(fetchData);
</script>

<style scoped>
.no-scrollbar::-webkit-scrollbar { display: none; }
.no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>