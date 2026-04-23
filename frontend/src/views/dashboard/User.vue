<template>
  <div class="max-w-7xl mx-auto px-4 py-8 md:py-10">
    <div class="flex flex-col lg:flex-row gap-8 items-start">
      <!-- Sidebar Navigasi -->
      <Sidebar class="flex-shrink-0 w-full lg:w-64" />

      <!-- Konten Utama Dashboard Donatur -->
      <div class="flex-grow space-y-8 animate-in fade-in slide-in-from-right-4 duration-700 w-full pb-24 lg:pb-0">
        <!-- Header -->
        <header class="bg-white p-6 md:p-8 rounded-[2rem] md:rounded-[2.5rem] border border-gray-100 shadow-sm flex flex-col md:flex-row justify-between items-center gap-6">
          <div class="text-center md:text-left">
            <h1 class="text-2xl md:text-3xl font-black text-gray-900 tracking-tight leading-none mb-1 uppercase italic">User <span class="text-blue-600">Profile</span></h1>
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest leading-none">Pantau kontribusi Anda di HumanityCare</p>
          </div>
          <router-link to="/" class="px-8 py-4 bg-blue-600 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-blue-700 transition shadow-xl shadow-blue-100">Jelajah Kampanye</router-link>
        </header>

        <!-- Navigasi Tab Internal -->
        <div class="flex items-center gap-2 bg-gray-100 p-1.5 rounded-2xl w-full overflow-x-auto no-scrollbar">
           <button @click="activeTab = 'donations'" :class="['px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all', activeTab === 'donations' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-500']">Riwayat Donasi</button>
        </div>

        <!-- Tab: Riwayat Donasi -->
        <div v-if="activeTab === 'donations'" class="space-y-6">
           <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden">
             <div class="overflow-x-auto no-scrollbar">
                <table class="w-full text-left">
                   <thead class="bg-gray-50 text-[10px] text-gray-400 font-black uppercase tracking-[0.2em]">
                      <tr>
                         <th class="px-8 py-4">Projek</th>
                         <th class="px-8 py-4">Nominal</th>
                         <th class="px-8 py-4 text-center">Status</th>
                      </tr>
                   </thead>
                   <tbody class="divide-y divide-gray-50">
                      <tr v-for="d in donations" :key="d.id" class="hover:bg-gray-50/50">
                         <td class="px-8 py-6 font-bold text-slate-800">{{ d.campaign_title }}</td>
                         <td class="px-8 py-6 font-black text-blue-600">Rp {{ formatCurrency(d.amount) }}</td>
                         <td class="px-8 py-6 text-center">
                            <span :class="['px-3 py-1 bg-blue-50 text-blue-600 rounded-lg text-[9px] font-black uppercase tracking-widest', d.payment_status === 'Success' ? '' : 'bg-amber-50 text-amber-600']">{{ d.payment_status }}</span>
                         </td>
                      </tr>
                      <tr v-if="donations.length === 0">
                         <td colspan="3" class="px-8 py-20 text-center text-slate-400 italic font-bold uppercase tracking-widest text-[10px]">Belum ada riwayat donasi.</td>
                      </tr>
                   </tbody>
                </table>
             </div>
           </div>
        </div>



      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import api from '@/api';
import Sidebar from '@/components/Sidebar.vue';

const activeTab = ref('donations');
const donations = ref([]);
const loading = ref(true);

const formatCurrency = (val) => new Intl.NumberFormat('id-ID').format(val || 0);

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