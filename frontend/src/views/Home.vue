<template>
  <div class="min-h-screen bg-slate-50">
    <!-- Hero Section -->
    <section class="relative min-h-[90vh] flex items-center justify-center text-center text-white overflow-hidden py-32 px-6 sm:px-10">
      <!-- Background Image -->
      <div class="absolute inset-0 z-0">
        <img 
          src="https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=2070&auto=format&fit=crop" 
          class="w-full h-full object-cover scale-110"
          alt="Charity"
        />
        <div class="absolute inset-0 bg-black/70"></div>
      </div>

      <!-- Hero Content -->
      <div class="relative z-10 w-full max-w-2xl mx-auto space-y-6">
        <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-md border border-white/20 px-4 py-2 rounded-full">
           <span class="w-1.5 h-1.5 bg-blue-400 rounded-full animate-ping"></span>
           <span class="text-[9px] font-black uppercase tracking-[0.2em]">Mitra Terverifikasi</span>
        </div>
        
        <h1 class="text-3xl sm:text-5xl md:text-7xl font-black leading-[1.2] tracking-tighter uppercase break-words">
          Uluran Tangan <br> <span class="text-blue-500">Anda,</span> <br> Harapan <span class="text-blue-500">Mereka</span>
        </h1>
        
        <p class="text-[10px] sm:text-sm md:text-base text-white/80 max-w-lg mx-auto font-bold leading-relaxed break-words px-2">
          Kami menghubungkan kebaikan hati Anda dengan mereka yang benar-benar membutuhkan bantuan di seluruh pelosok negeri.
        </p>
       
      </div>
    </section>

    <!-- Stats Floating Section -->
    <section class="max-w-7xl mx-auto px-4 -mt-10 sm:-mt-20 relative z-20">
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
        
        <!-- Total Donasi -->
        <div class="bg-white p-6 md:p-8 rounded-[2rem] md:rounded-3xl shadow-xl flex items-center space-x-6 border border-slate-100 transform hover:-translate-y-2 transition duration-300">
          <div class="bg-blue-50 w-16 h-16 md:w-20 md:h-20 rounded-2xl flex items-center justify-center text-blue-600 text-2xl md:text-3xl shrink-0">
            <i class="fas fa-hand-holding-heart"></i>
          </div>
          <div>
            <h4 class="text-2xl md:text-3xl font-black tracking-tighter text-slate-900">
              {{ formatCompact(stats.totalDonation) }}
            </h4>
            <p class="text-slate-500 font-bold text-[8px] md:text-xs uppercase tracking-widest mt-1">Total Donas</p>
          </div>
        </div>

        <!-- Kampanye Sukses -->
        <div class="bg-white p-6 md:p-8 rounded-[2rem] md:rounded-3xl shadow-xl flex items-center space-x-6 border border-slate-100 transform hover:-translate-y-2 transition duration-300">
          <div class="bg-blue-50 w-16 h-16 md:w-20 md:h-20 rounded-2xl flex items-center justify-center text-blue-600 text-2xl md:text-3xl shrink-0">
            <i class="fas fa-check-circle"></i>
          </div>
          <div>
            <h4 class="text-2xl md:text-3xl font-black tracking-tighter text-slate-900">
              {{ stats.campaignsSuccess }}+
            </h4>
            <p class="text-slate-500 font-bold text-[8px] md:text-xs uppercase tracking-widest mt-1">Kampanye Sukses</p>
          </div>
        </div>

        <!-- Jangkauan / Kategori -->
        <div class="bg-white p-6 md:p-8 rounded-[2rem] md:rounded-3xl shadow-xl flex items-center space-x-6 border border-slate-100 transform hover:-translate-y-2 transition duration-300 sm:col-span-2 lg:col-span-1">
          <div class="bg-amber-50 w-16 h-16 md:w-20 md:h-20 rounded-2xl flex items-center justify-center text-amber-600 text-2xl md:text-3xl shrink-0">
            <i class="fas fa-globe-asia"></i>
          </div>
          <div>
            <h4 class="text-2xl md:text-3xl font-black tracking-tighter text-slate-900">
              {{ stats.reach }} Kategori
            </h4>
            <p class="text-slate-500 font-bold text-[8px] md:text-xs uppercase tracking-widest mt-1">Jangkauan Luas</p>
          </div>
        </div>

      </div>
    </section>

    <!-- Campaign Section -->
    <section id="campaigns" ref="campaignSection" class="max-w-7xl mx-auto px-4 py-20 pb-32">
      <div class="flex flex-col md:flex-row md:justify-between md:items-end mb-8 gap-6 items-start">
        <div class="w-full">
           <h2 class="text-xl sm:text-2xl md:text-4xl font-black text-slate-900 leading-tight uppercase tracking-tighter italic">Daftar <span class="text-blue-600">Kampanye</span></h2>
           <p class="text-slate-400 font-bold text-[9px] md:text-sm tracking-[0.2em] uppercase mt-1">Titipan Kebaikan Anda Terverifikasi</p>
        </div>
        
        <!-- Search -->
        <div class="w-full md:w-80 group">
          <input 
            v-model="searchQuery" 
            type="text" 
            placeholder="Cari kampanye..." 
            class="w-full px-6 py-4 bg-white border border-slate-100 rounded-2xl outline-none focus:ring-4 focus:ring-blue-600/5 focus:border-blue-600 transition-all font-bold text-xs shadow-sm"
          />
        </div>
      </div>

      <!-- Category Filter Chips -->
      <div class="flex overflow-x-auto no-scrollbar gap-2 mb-12 -mx-4 px-4 md:mx-0 md:px-0">
        <button 
          v-for="cat in categories" 
          :key="cat"
          @click="selectedCategory = cat"
          :class="[
            'px-6 py-3 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all whitespace-nowrap shadow-sm border',
            selectedCategory === cat ? 'bg-blue-600 text-white border-blue-600 scale-105' : 'bg-white text-slate-400 border-slate-50'
          ]"
        >
          {{ cat }}
        </button>
      </div>

      <!-- Error Message -->
      <div v-if="error" class="bg-red-50 text-red-600 p-10 rounded-[3rem] text-center mb-8 border border-red-100 shadow-xl">
        <div class="w-16 h-16 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-4">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
          </svg>
        </div>
        <p class="font-black text-xl mb-2">Gagal memuat data dari server</p>
        <p class="text-sm font-medium opacity-80 mb-6">{{ error }}</p>
        <button @click="fetchData" class="bg-red-600 text-white px-8 py-3 rounded-2xl font-black text-sm uppercase tracking-widest hover:bg-red-700 transition-all">Coba Lagi</button>
      </div>

      <!-- Loading Placeholder -->
      <div v-if="loading" class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div v-for="n in 3" :key="n" class="bg-white p-4 rounded-[2.5rem] shadow-sm border border-slate-100 space-y-4">
          <div class="bg-slate-100 h-52 animate-pulse rounded-[2rem]"></div>
          <div class="h-6 bg-slate-50 rounded-lg w-3/4 animate-pulse"></div>
          <div class="h-4 bg-slate-50 rounded-lg w-1/2 animate-pulse"></div>
        </div>
      </div>

      <!-- Dynamic Campaign Cards -->
      <div v-else-if="filteredCampaigns.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-20 animate-in fade-in slide-in-from-bottom-8 duration-700">
        <CampaignCard 
          v-for="campaign in filteredCampaigns" 
          :key="campaign.id" 
          :campaign="campaign"
          @select="handleDonate"
        />
      </div>

      <!-- Search Empty State -->
      <div v-else class="py-32 text-center bg-white rounded-[3rem] border-2 border-dashed border-slate-100">
        <div class="w-20 h-20 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mx-auto mb-6">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
        </div>
        <h3 class="text-2xl font-black text-slate-900 mb-2">Kampanye Tidak Ditemukan</h3>
        <p class="text-slate-400 font-bold text-sm">Coba kata kunci lain atau pilih kategori yang berbeda.</p>
      </div>
    </section>

    <!-- Social Impact Section (Berita & Syahadat Preview) -->
    <section class="max-w-7xl mx-auto px-4 py-24 border-t border-slate-100">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
        <!-- Text Content -->
        <div class="space-y-8">
          <div class="inline-flex py-1 px-4 bg-blue-50 rounded-full text-blue-600 text-[10px] font-black uppercase tracking-widest border border-blue-100">Mualaf Center & Berita</div>
          <h2 class="text-3xl md:text-5xl font-black text-slate-900 leading-none uppercase tracking-tighter italic">Langkah Baru, <br> <span class="text-blue-600">Kehidupan Baru</span></h2>
          <p class="text-slate-500 font-bold text-sm leading-relaxed max-w-md">Selain berdonasi, kami juga membantu saudara-saudara kita yang membutuhkan bimbingan spiritual serta menyajikan kabar warta kebaikan dari lapangan.</p>
          
          <div class="flex flex-col sm:flex-row gap-4 pt-4">
            <button @click="handleSyahadat" class="px-8 py-4 bg-blue-600 text-white rounded-2xl font-black uppercase tracking-widest text-[11px] shadow-xl shadow-blue-100 hover:bg-blue-700 transition inline-block text-center">Ajukan Syahadat</button>
            <router-link to="/news" class="px-8 py-4 bg-white border-2 border-slate-100 text-slate-900 rounded-2xl font-black uppercase tracking-widest text-[11px] hover:border-blue-600 hover:text-blue-600 transition inline-block text-center">Lihat Berita Terbaru</router-link>
          </div>
        </div>

        <!-- Visual Element -->
        <div class="relative group">
           <div class="absolute -inset-4 bg-blue-600/5 rounded-[3rem] -rotate-3 group-hover:rotate-0 transition-transform"></div>
           <div class="relative aspect-video overflow-hidden rounded-[2.5rem] shadow-2xl">
              <img src="https://images.unsplash.com/photo-1542810634-71277d95dcbb?q=80&w=1000&auto=format&fit=crop" class="w-full h-full object-cover transition duration-700 group-hover:scale-110" alt="News Preview">
              <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
              <div class="absolute bottom-8 left-8 right-8 text-white">
                 <p class="text-[9px] font-black uppercase tracking-widest mb-2">Terakhir Ditambahkan</p>
                 <h4 class="text-lg font-black tracking-tight leading-tight uppercase italic line-clamp-2">Penyaluran Ribuan Wakaf Al-Quran ke Pelosok Indonesia Timur</h4>
              </div>
           </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { useRouter } from 'vue-router';
import api from '@/api';
import CampaignCard from '../components/CampaignCard.vue';

import { useAuth } from '../store/auth';

const router = useRouter();
const { isAuthenticated, openLogin } = useAuth();
const campaigns = ref([]);
const stats = ref({
  totalDonation: 0,
  campaignsSuccess: 0,
  reach: 0
});
const loading = ref(true);
const error = ref(null);
const campaignSection = ref(null);

// State Pencarian & Filter (BARU)
const searchQuery = ref('');
const selectedCategory = ref('Semua');
const categories = ['Semua', 'Medis', 'Pendidikan', 'Bencana', 'Lingkungan', 'Sosial'];

// Logika Filter (BARU)
const filteredCampaigns = computed(() => {
  return campaigns.value.filter(c => {
    const matchesSearch = c.title.toLowerCase().includes(searchQuery.value.toLowerCase()) || 
                          c.description.toLowerCase().includes(searchQuery.value.toLowerCase());
    const matchesCategory = selectedCategory.value === 'Semua' || c.category === selectedCategory.value;
    return matchesSearch && matchesCategory;
  });
});

// Fungsi Ambil Data Dinamis (Campaigns + Stats)
const fetchData = async () => {
  loading.value = true;
  error.value = null;
  try {
    const [campaignRes, statsRes] = await Promise.all([
      api.get('/campaigns'),
      api.get('/stats')
    ]);
    
    campaigns.value = campaignRes.data.map(c => ({
      ...c,
      image: c.foto || 'https://images.unsplash.com/photo-1532629345422-7515f3d16bb6?w=600&auto=format&fit=crop',
      days_left: calculateDaysLeft(c.end_date)
    }));

    stats.value = statsRes.data;
  } catch (err) {
    error.value = "Gagal mengambil data dari server. Pastikan backend menyala.";
    console.error(err);
  } finally {
    loading.value = false;
  }
};

// Helper Format Angka Ringkas (1.2M+)
const formatCompact = (value) => {
  const num = Number(value);
  if (isNaN(num) || num === 0) return 'Rp 0';
  if (num >= 1000000000) return 'Rp ' + (num / 1000000000).toFixed(1) + 'B+';
  if (num >= 1000000) return 'Rp ' + (num / 1000000).toFixed(1) + 'M+';
  if (num >= 1000) return 'Rp ' + (num / 1000).toFixed(0) + 'K+';
  return 'Rp ' + num.toLocaleString();
};

const calculateDaysLeft = (dateString) => {
  if (!dateString) return 0;
  const end = new Date(dateString);
  const now = new Date();
  const diffTime = end - now;
  const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
  return diffDays > 0 ? diffDays : 0;
};

const scrollToCampaign = () => {
  campaignSection.value.scrollIntoView({ behavior: 'smooth' });
};

const handleDonate = (campaign) => {
  if (!isAuthenticated.value) {
    openLogin();
    return;
  }
  router.push(`/campaigns/${campaign.id}`);
};

const handleSyahadat = () => {
  if (!isAuthenticated.value) {
    openLogin();
    return;
  }
  router.push('/muallaf');
};

onMounted(fetchData);
</script>

<style scoped>
.tracking-tight { letter-spacing: -0.025em; }
</style>