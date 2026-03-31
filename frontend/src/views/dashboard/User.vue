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
            <h1 class="text-2xl md:text-3xl font-black text-gray-900 tracking-tight leading-none mb-1 uppercase">PROFIL <span class="text-blue-600">DONATUR</span></h1>
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest leading-none">Pantau kontribusi kemanusiaan Anda</p>
          </div>
          <router-link to="/" class="w-full md:w-auto text-center bg-blue-600 text-white px-8 py-4 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-blue-700 transition-all shadow-xl shadow-blue-100 italic">
            Jelajah Kampanye
          </router-link>
        </header>

        <!-- Ringkasan Statistik -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 md:gap-6">
          <div class="bg-white rounded-[2rem] border border-gray-100 p-8 shadow-sm flex items-center gap-6 group hover:shadow-md transition-all">
            <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-3xl flex items-center justify-center group-hover:scale-110 transition-transform duration-500">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <div>
              <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Kontribusi Saya</p>
              <p class="text-3xl font-black text-gray-900 leading-tight">Rp {{ formatCurrency(totalDonated) }}</p>
            </div>
          </div>
          <div class="bg-white rounded-[2rem] border border-gray-100 p-8 shadow-sm flex items-center gap-6 group hover:shadow-md transition-all">
            <div class="w-16 h-16 bg-red-50 text-red-600 rounded-3xl flex items-center justify-center group-hover:scale-110 transition-transform duration-500">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
              </svg>
            </div>
            <div>
              <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Misi Terbantu</p>
              <p class="text-3xl font-black text-gray-900 leading-tight">{{ donations.length }} <span class="text-xs text-gray-400">Projek</span></p>
            </div>
          </div>
        </div>

        <!-- Bagian Riwayat Donasi -->
        <div v-if="loading" class="py-20 text-center bg-white rounded-[3rem] border border-dashed border-gray-100">
          <div class="animate-spin h-10 w-10 border-4 border-blue-600 border-t-transparent rounded-full mx-auto mb-4"></div>
          <p class="text-gray-400 font-black text-xs uppercase tracking-widest">Sinkronisasi Riwayat...</p>
        </div>

        <div v-else class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden">
          <div class="p-8 border-b border-gray-50 bg-gray-50/30">
            <h2 class="font-black text-xl text-gray-800 uppercase tracking-tighter">Riwayat Transaksi</h2>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Semua donasi yang telah diverifikasi oleh tim HumanityCare</p>
          </div>
          
          <div class="overflow-x-auto">
            <table class="w-full text-left">
              <thead class="bg-gray-50 text-[10px] text-gray-400 font-black uppercase tracking-[0.2em] border-b">
                <tr>
                  <th class="px-8 py-4">Misi Kemanusiaan</th>
                  <th class="px-8 py-4">Waktu</th>
                  <th class="px-8 py-4 text-right">Nominal</th>
                  <th class="px-8 py-4 text-center">Status</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-50">
                <tr v-for="c in donations" :key="c.id" class="hover:bg-gray-50/50 transition-colors group">
                  <td class="px-8 py-6">
                    <div class="flex items-center gap-4">
                      <div class="w-12 h-12 rounded-xl bg-gray-100 overflow-hidden border">
                        <img v-if="c.foto" :src="c.foto" class="w-full h-full object-cover" />
                        <div v-else class="w-full h-full flex items-center justify-center text-gray-300">
                          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                          </svg>
                        </div>
                      </div>
                      <div>
                        <span class="font-black text-gray-800 tracking-tight block">{{ c.campaign_title }}</span>
                        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">TX-ID: #{{ c.id }}</span>
                      </div>
                    </div>
                  </td>
                  <td class="px-8 py-6 text-xs font-bold text-gray-500 uppercase">{{ formatDate(c.created_at) }}</td>
                  <td class="px-8 py-6 text-right font-black text-gray-900">Rp {{ formatCurrency(c.amount) }}</td>
                  <td class="px-8 py-6 text-center">
                    <span :class="[
                      'px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest',
                      c.status === 'Success' ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600'
                    ]">
                      {{ c.status === 'Success' ? 'Telah Diterima' : 'Diverifikasi' }}
                    </span>
                  </td>
                </tr>
                <tr v-if="donations.length === 0">
                  <td colspan="4" class="px-8 py-20 text-center">
                    <p class="text-gray-400 font-bold uppercase tracking-widest text-xs italic">Anda belum memiliki riwayat donasi.</p>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import api from '../../api';
import Sidebar from '@/components/Sidebar.vue';

const donations = ref([]);
const loading = ref(true);

const totalDonated = computed(() => {
  return donations.value.reduce((sum, item) => sum + parseFloat(item.amount), 0);
});

const formatCurrency = (val) => new Intl.NumberFormat('id-ID').format(val || 0);

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('id-ID', {
    day: 'numeric',
    month: 'short',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
};

const fetchDonations = async () => {
  try {
    const response = await api.get('/donations/my-donations');
    donations.value = response.data;
  } catch (error) {
    console.error("Gagal mengambil riwayat:", error);
  } finally {
    loading.value = false;
  }
};

onMounted(fetchDonations);
</script>