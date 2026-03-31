<template>
  <div class="min-h-screen bg-slate-50">
    <!-- Hero Section -->
    <section class="relative h-[80vh] flex items-center justify-center text-center text-white overflow-hidden">
      <!-- Background Image with Overlay -->
      <div class="absolute inset-0 z-0">
        <img 
          src="https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=2070&auto=format&fit=crop" 
          class="w-full h-full object-cover"
          alt="Charity Background"
        />
        <div class="absolute inset-0 bg-black/60 backdrop-blur-[2px]"></div>
      </div>

      <!-- Hero Content -->
      <div class="relative z-10 max-w-3xl px-4">
        <span class="bg-blue-600/30 backdrop-blur-md border border-white/20 px-4 py-2 rounded-full text-sm font-semibold mb-6 inline-block">
          <i class="fas fa-heart text-red-400 mr-2"></i> Dipercaya oleh 10,000+ Donatur
        </span>
        <h1 class="text-5xl md:text-7xl font-extrabold mb-6 leading-tight tracking-tight">
          Uluran Tangan Anda, <br> <span class="text-blue-400">Harapan Mereka</span>
        </h1>
        <p class="text-lg md:text-xl opacity-90 mb-10 max-w-2xl mx-auto font-medium">
          Bergabunglah bersama kami untuk menciptakan perubahan nyata bagi ribuan nyawa yang membutuhkan di seluruh pelosok negeri.
        </p>
       
      </div>
    </section>

    <!-- Stats Floating Section (DINAMIS) -->
    <section class="max-w-7xl mx-auto px-4 -mt-20 relative z-20">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- Total Donasi -->
        <div class="bg-white p-8 rounded-3xl shadow-xl flex items-center space-x-6 border border-slate-100 transform hover:-translate-y-2 transition duration-300">
          <div class="bg-blue-50 w-20 h-20 rounded-2xl flex items-center justify-center text-blue-600 text-3xl">
            <i class="fas fa-hand-holding-heart"></i>
          </div>
          <div>
            <h4 class="text-3xl font-black tracking-tighter text-slate-900">
              {{ formatCompact(stats.totalDonation) }}
            </h4>
            <p class="text-slate-500 font-bold text-xs uppercase tracking-widest mt-1">Total Donasi</p>
          </div>
        </div>

        <!-- Kampanye Sukses -->
        <div class="bg-white p-8 rounded-3xl shadow-xl flex items-center space-x-6 border border-slate-100 transform hover:-translate-y-2 transition duration-300">
          <div class="bg-green-50 w-20 h-50 rounded-2xl flex items-center justify-center text-green-600 text-3xl">
            <i class="fas fa-check-circle"></i>
          </div>
          <div>
            <h4 class="text-3xl font-black tracking-tighter text-slate-900">
              {{ stats.campaignsSuccess }}+
            </h4>
            <p class="text-slate-500 font-bold text-xs uppercase tracking-widest mt-1">Kampanye Sukses</p>
          </div>
        </div>

        <!-- Jangkauan / Kategori -->
        <div class="bg-white p-8 rounded-3xl shadow-xl flex items-center space-x-6 border border-slate-100 transform hover:-translate-y-2 transition duration-300">
          <div class="bg-amber-50 w-20 h-20 rounded-2xl flex items-center justify-center text-amber-600 text-3xl">
            <i class="fas fa-globe-asia"></i>
          </div>
          <div>
            <h4 class="text-3xl font-black tracking-tighter text-slate-900">
              {{ stats.reach }}
            </h4>
            <p class="text-slate-500 font-bold text-xs uppercase tracking-widest mt-1">Kategori Terbantu</p>
          </div>
        </div>

      </div>
    </section>

    <!-- Campaign Section -->
    <section id="campaigns" ref="campaignSection" class="max-w-7xl mx-auto px-4 py-24">
      <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-4">
        <div>
          <h2 class="text-4xl font-bold text-slate-900 mb-2">Kampanye Mendesak</h2>
          <p class="text-slate-500">Membantu sesama mulai dari hal yang paling kecil</p>
        </div>
      </div>

      <!-- Error Message -->
      <div v-if="error" class="bg-red-50 text-red-600 p-6 rounded-2xl text-center mb-8">
        <p class="font-bold">Gagal memuat data dari server</p>
        <p class="text-sm">{{ error }}</p>
        <button @click="fetchData" class="mt-4 underline">Coba Lagi</button>
      </div>

      <!-- Loading Placeholder -->
      <div v-if="loading" class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div v-for="n in 3" :key="n" class="bg-slate-200 h-96 animate-pulse rounded-3xl"></div>
      </div>

      <!-- Dynamic Campaign Cards -->
      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <CampaignCard 
          v-for="campaign in campaigns" 
          :key="campaign.id" 
          :campaign="campaign"
          @select="handleDonate"
        />
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import api from '@/api';
import CampaignCard from '../components/CampaignCard.vue';

const router = useRouter();
const campaigns = ref([]);
const stats = ref({
  totalDonation: 0,
  campaignsSuccess: 0,
  reach: 0
});
const loading = ref(true);
const error = ref(null);
const campaignSection = ref(null);

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
      image: c.image || 'https://images.unsplash.com/photo-1532629345422-7515f3d16bb6?w=600&auto=format&fit=crop',
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
  router.push(`/campaigns/${campaign.id}`);
};

onMounted(fetchData);
</script>

<style scoped>
.tracking-tight { letter-spacing: -0.025em; }
</style>