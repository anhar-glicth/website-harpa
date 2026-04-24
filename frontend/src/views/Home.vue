<template>
  <div class="min-h-screen bg-slate-50">
    <!-- Hero Section -->
    <section class="relative min-h-[60vh] md:min-h-[90vh] flex items-center justify-center text-center text-white overflow-hidden py-20 sm:py-32 px-6 sm:px-10">
      <!-- Background Image -->
      <div class="absolute inset-0 z-0">
        <img 
          :src="siteSettings.hero_image || 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=2070&auto=format&fit=crop'" 
          class="w-full h-full object-cover scale-110"
          alt="Charity"
        />
        <div class="absolute inset-0 bg-black/70"></div>
      </div>

      <!-- Hero Content -->
      <div class="relative z-10 w-full max-w-2xl mx-auto space-y-4 md:space-y-6">
        <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-md border border-white/20 px-3 py-1.5 rounded-full">
           <span class="w-1.5 h-1.5 bg-green-400 rounded-full animate-ping"></span>
           <span class="text-[8px] font-black uppercase tracking-[0.2em]">Mitra Terverifikasi</span>
        </div>
        
        <h1 class="text-xl sm:text-4xl md:text-7xl font-black leading-tight tracking-tighter uppercase break-words">
           {{ siteSettings.hero_title || 'Uluran Tangan Anda, Harapan Mereka' }}
        </h1>
        
        <p class="text-[10px] sm:text-xs md:text-base text-white/90 max-w-lg mx-auto font-bold leading-relaxed break-words px-4">
           {{ siteSettings.hero_subtitle || 'Kami menghubungkan kebaikan hati Anda dengan mereka yang benar-benar membutuhkan bantuan di seluruh pelosok negeri.' }}
        </p>
       
      </div>
    </section>

    <!-- Stats Floating Section -->
    <section class="max-w-7xl mx-auto px-4 -mt-10 sm:-mt-20 relative z-20">
      <div class="grid grid-cols-3 gap-2 md:gap-6">
        
        <!-- Total Donasi -->
        <div class="bg-white p-3 md:p-8 rounded-2xl md:rounded-3xl shadow-xl flex flex-col md:flex-row items-center md:items-center text-center md:text-left md:space-x-6 border border-slate-100 transform hover:-translate-y-2 transition duration-300">
          <div class="bg-green-50 w-10 h-10 md:w-20 md:h-20 rounded-xl md:rounded-2xl flex items-center justify-center text-green-600 text-sm md:text-3xl shrink-0 mb-2 md:mb-0">
            <i class="fas fa-hand-holding-heart"></i>
          </div>
          <div>
            <h4 class="text-xs md:text-3xl font-black tracking-tighter text-slate-900 leading-tight">
              {{ formatCompact(stats.totalDonation) }}
            </h4>
            <p class="text-slate-500 font-bold text-[6px] md:text-xs uppercase tracking-widest mt-0.5 md:mt-1">Total Donasi</p>
          </div>
        </div>

        <!-- Kampanye Sukses -->
        <div class="bg-white p-3 md:p-8 rounded-2xl md:rounded-3xl shadow-xl flex flex-col md:flex-row items-center md:items-center text-center md:text-left md:space-x-6 border border-slate-100 transform hover:-translate-y-2 transition duration-300">
          <div class="bg-green-50 w-10 h-10 md:w-20 md:h-20 rounded-xl md:rounded-2xl flex items-center justify-center text-green-600 text-sm md:text-3xl shrink-0 mb-2 md:mb-0">
            <i class="fas fa-check-circle"></i>
          </div>
          <div>
            <h4 class="text-xs md:text-3xl font-black tracking-tighter text-slate-900 leading-tight">
              {{ stats.campaignsSuccess }}+
            </h4>
            <p class="text-slate-500 font-bold text-[6px] md:text-xs uppercase tracking-widest mt-0.5 md:mt-1">KMPY Sukses</p>
          </div>
        </div>

        <!-- Jangkauan / Kategori -->
        <div class="bg-white p-3 md:p-8 rounded-2xl md:rounded-3xl shadow-xl flex flex-col md:flex-row items-center md:items-center text-center md:text-left md:space-x-6 border border-slate-100 transform hover:-translate-y-2 transition duration-300">
          <div class="bg-amber-50 w-10 h-10 md:w-20 md:h-20 rounded-xl md:rounded-2xl flex items-center justify-center text-amber-600 text-sm md:text-3xl shrink-0 mb-2 md:mb-0">
            <i class="fas fa-globe-asia"></i>
          </div>
          <div>
            <h4 class="text-xs md:text-3xl font-black tracking-tighter text-slate-900 leading-tight">
              {{ stats.reach }}
            </h4>
            <p class="text-slate-500 font-bold text-[6px] md:text-xs uppercase tracking-widest mt-0.5 md:mt-1">Kategori</p>
          </div>
        </div>

      </div>
    </section>

    <!-- Campaign Section -->
    <section id="campaigns" ref="campaignSection" class="max-w-7xl mx-auto px-4 py-12 md:py-20 pb-24 md:pb-32">
      <div class="flex flex-col md:flex-row md:justify-between md:items-end mb-8 gap-4 items-start">
        <div class="w-full">
           <h2 class="text-lg sm:text-2xl md:text-4xl font-black text-slate-900 leading-tight uppercase tracking-tighter italic">Daftar <span class="text-green-600">Kampanye</span></h2>
           <p class="text-slate-400 font-bold text-[8px] md:text-sm tracking-[0.2em] uppercase mt-1">Titipan Kebaikan Anda Terverifikasi</p>
        </div>
        
        <!-- Search -->
        <div class="w-full md:w-80 group">
          <input 
            v-model="searchQuery" 
            type="text" 
            placeholder="Cari kampanye..." 
            class="w-full px-6 py-4 bg-white border border-slate-100 rounded-2xl outline-none focus:ring-4 focus:ring-green-600/5 focus:border-green-600 transition-all font-bold text-xs shadow-sm"
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
            'px-4 md:px-6 py-2 md:py-3 rounded-lg md:rounded-xl text-[8px] md:text-[9px] font-black uppercase tracking-widest transition-all whitespace-nowrap shadow-sm border',
            selectedCategory === cat ? 'bg-green-600 text-white border-green-600 scale-105' : 'bg-white text-slate-400 border-slate-50'
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
      <div v-else-if="filteredCampaigns.length > 0" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 md:gap-6 mb-20 animate-in fade-in slide-in-from-bottom-8 duration-700">
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

    <!-- Social Impact Section (Berita Preview) -->
    <section class="max-w-7xl mx-auto px-4 py-12 md:py-24 border-t border-slate-100">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 md:gap-16 items-center">
        <!-- Text Content -->
        <div class="space-y-6 md:space-y-8">
          <div class="inline-flex py-1 px-4 bg-green-50 rounded-full text-green-600 text-[9px] font-black uppercase tracking-widest border border-green-100">Berita Kebaikan</div>
          <h2 class="text-xl md:text-5xl font-black text-slate-900 leading-none uppercase tracking-tighter italic">Kabar Warta, <br> <span class="text-green-600">Aksi Nyata</span></h2>
          <p class="text-slate-500 font-bold text-[11px] md:text-sm leading-relaxed max-w-md italic">Kami menyajikan kabar warta kebaikan dan laporan penyaluran donasi langsung dari lapangan untuk transparansi Anda.</p>
          
          <div class="flex flex-col sm:flex-row gap-4 pt-4">
            <router-link to="/news" class="px-8 py-4 bg-green-600 text-white rounded-2xl font-black uppercase tracking-widest text-[11px] shadow-xl shadow-green-100 hover:bg-green-700 transition inline-block text-center">Lihat Berita Terbaru</router-link>
          </div>
        </div>

        <!-- Visual Element -->
        <div class="relative group">
           <div class="absolute -inset-4 bg-green-600/5 rounded-[3rem] -rotate-3 group-hover:rotate-0 transition-transform"></div>
           <div class="relative aspect-video overflow-hidden rounded-[2.5rem] shadow-2xl">
              <img :src="siteSettings.impact_image || 'https://images.unsplash.com/photo-1542810634-71277d95dcbb?q=80&w=1000&auto=format&fit=crop'" class="w-full h-full object-cover transition duration-700 group-hover:scale-110" alt="News Preview">
              <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
              <div class="absolute bottom-8 left-8 right-8 text-white">
                 <p class="text-[9px] font-black uppercase tracking-widest mb-2">Terakhir Ditambahkan</p>
                 <h4 class="text-lg font-black tracking-tight leading-tight uppercase italic line-clamp-2">{{ siteSettings.impact_title || 'Penyaluran Ribuan Wakaf Al-Quran ke Pelosok Indonesia Timur' }}</h4>
              </div>
           </div>
        </div>
      </div>
    </section>

    <!-- Prayer Wall (NEW) -->
    <section class="bg-slate-900 py-24 md:py-32 overflow-hidden relative">
      <div class="absolute inset-0 opacity-10 pointer-events-none">
         <div class="absolute top-0 left-0 w-full h-full bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-green-600 via-transparent to-transparent"></div>
      </div>
      
      <div class="max-w-7xl mx-auto px-4 relative z-10">
         <div class="text-center space-y-6 mb-20">
            <div class="inline-flex py-1 px-4 bg-white/5 rounded-full text-white/40 text-[9px] font-black uppercase tracking-widest border border-white/5 italic">Community Prayers</div>
            <h2 class="text-3xl md:text-6xl font-black text-white uppercase italic tracking-tighter leading-none">
               Tembok <span class="text-green-500">Doa</span> <br> <span class="text-white/20">Harapan & Berkah</span>
            </h2>
         </div>

         <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
            <div 
              v-for="p in prayers" :key="p.id" 
              class="bg-white/5 backdrop-blur-xl p-8 rounded-[2.5rem] border border-white/5 space-y-6 flex flex-col justify-between hover:bg-white/10 transition duration-500 group"
            >
               <div class="space-y-4">
                  <div class="flex items-center gap-3">
                     <div class="w-8 h-8 rounded-full bg-green-600/20 text-green-500 flex items-center justify-center text-[10px] font-black">
                        {{ p.name?.[0]?.toUpperCase() || 'H' }}
                     </div>
                     <p class="text-[10px] font-black text-white/40 uppercase tracking-widest">{{ p.is_anonymous ? 'Hamba Allah' : p.name }}</p>
                  </div>
                  <p class="text-sm md:text-base font-bold text-white/80 leading-relaxed italic line-clamp-3">"{{ p.message }}"</p>
               </div>
               
               <div class="flex justify-between items-center pt-4 border-t border-white/5">
                  <button 
                    @click="handleAamiin(p.id)"
                    class="flex items-center gap-2 px-5 py-2.5 bg-green-600/10 text-green-500 rounded-full text-[9px] font-black uppercase tracking-widest border border-green-600/20 hover:bg-green-600 hover:text-white transition active:scale-95 shadow-lg shadow-green-600/5"
                  >
                    <i class="fas fa-praying-hands text-[10px]"></i>
                    Aamiin <span class="opacity-50">({{ p.aamiin_count || 0 }})</span>
                  </button>
                  <span class="text-[8px] text-white/20 font-black uppercase tracking-widest">{{ formatDate(p.created_at) }}</span>
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
const siteSettings = ref({});
const prayers = ref([]);
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
const categories = ['Semua', 'Sosial', 'Medis', 'Pendidikan', 'Bencana', 'Lingkungan', 'Yatim'];

// Logika Filter (BARU)
const filteredCampaigns = computed(() => {
  return campaigns.value.filter(c => {
    const matchesSearch = c.title.toLowerCase().includes(searchQuery.value.toLowerCase()) || 
                          c.description.toLowerCase().includes(searchQuery.value.toLowerCase());
    const matchesCategory = selectedCategory.value === 'Semua' || c.category === selectedCategory.value;
    return matchesSearch && matchesCategory;
  });
});

// Fungsi Ambil Data Dinamis (Campaigns + Stats + Settings)
const fetchData = async () => {
  loading.value = true;
  error.value = null;
  try {
    const [campaignRes, statsRes, settingsRes, prayersRes] = await Promise.all([
      api.get('/campaigns'),
      api.get('/stats'),
      api.get('/settings'),
      api.get('/prayers')
    ]);
    
    campaigns.value = campaignRes.data.map(c => ({
      ...c,
      image: c.foto || 'https://images.unsplash.com/photo-1532629345422-7515f3d16bb6?w=600&auto=format&fit=crop',
      days_left: calculateDaysLeft(c.end_date)
    }));

    stats.value = statsRes.data;
    siteSettings.value = settingsRes.data;
    prayers.value = prayersRes.data;
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

const handleAamiin = async (id) => {
   try {
      await api.post(`/prayers/${id}/aamiin`);
      // Update local state
      const prayer = prayers.value.find(p => p.id === id);
      if (prayer) prayer.aamiin_count++;
   } catch (e) {
      console.error(e);
   }
};

const formatDate = (dateStr) => {
   if (!dateStr) return '';
   return new Date(dateStr).toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
};

onMounted(fetchData);
</script>

<style scoped>
.tracking-tight { letter-spacing: -0.025em; }
</style>