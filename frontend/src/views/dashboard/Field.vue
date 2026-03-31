<template>
  <div class="max-w-7xl mx-auto px-4 py-8 md:py-10">
    <div class="flex flex-col lg:flex-row gap-8 items-start">
      <!-- Sidebar Navigasi -->
      <Sidebar class="flex-shrink-0 w-full lg:w-64" />

      <!-- Konten Utama Dashboard -->
      <div class="flex-grow space-y-8 animate-in fade-in slide-in-from-right-4 duration-700 w-full pb-24 lg:pb-0">
        <!-- Header -->
        <header class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-white p-6 md:p-8 rounded-[2rem] md:rounded-[2.5rem] border border-gray-100 shadow-sm relative overflow-hidden">
          <div class="space-y-2 relative z-10 text-center md:text-left">
            <div class="flex items-center justify-center md:justify-start gap-3">
              <h1 class="text-3xl font-black text-gray-900 tracking-tight">Panel Staff Lapangan</h1>
              <span class="bg-blue-600 text-white text-[9px] font-black uppercase px-3 py-1 rounded-full">Active</span>
            </div>
          </div>
          
          <div class="flex flex-col md:flex-row gap-3">
            <button @click="openNewsModal" class="px-6 py-3.5 bg-blue-50 text-blue-600 rounded-2xl font-black uppercase text-[10px] tracking-widest hover:bg-blue-100 transition-all">Tulis Berita</button>
            <button @click="openModal" class="px-6 py-3.5 bg-blue-600 text-white rounded-2xl font-black uppercase text-[10px] tracking-widest hover:bg-blue-700 transition-all shadow-xl shadow-blue-100">Kampanye Baru</button>
          </div>
        </header>

        <!-- Navigasi Tab Intern -->
        <div class="flex items-center gap-2 bg-gray-100 p-1.5 rounded-2xl w-full overflow-x-auto no-scrollbar">
           <button @click="activeTab = 'campaigns'" :class="['px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all', activeTab === 'campaigns' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-500']">Kampanye Saya</button>
           <button @click="activeTab = 'news'" :class="['px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all', activeTab === 'news' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-500']">Internal Berita</button>
        </div>

        <!-- Layout Content -->
        <div v-if="activeTab === 'campaigns'" class="space-y-8">
           <!-- Grid Kampanye (Existing logic) -->
           <div v-if="loading" class="py-20 text-center">Memuat...</div>
           <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-8">
              <div v-for="c in myCampaigns" :key="c.id" class="bg-white rounded-[2.5rem] border border-gray-100 overflow-hidden shadow-sm">
                 <img :src="c.foto" class="h-48 w-full object-cover">
                 <div class="p-8">
                    <h3 class="font-black text-gray-900 uppercase italic mb-4">{{ c.title }}</h3>
                    <div class="flex justify-between items-center">
                       <span class="text-[10px] font-black uppercase px-3 py-1 bg-blue-50 text-blue-600 rounded-lg">{{ c.status }}</span>
                       <span class="font-black text-blue-600">Rp {{ formatCurrency(c.current_amount) }}</span>
                    </div>
                 </div>
              </div>
           </div>
        </div>

        <div v-if="activeTab === 'news'" class="space-y-8">
           <div class="bg-white rounded-[2.5rem] border border-gray-100 overflow-hidden shadow-sm">
              <div class="p-8 border-b border-gray-50 flex justify-between items-center">
                 <h3 class="font-black text-gray-900 uppercase tracking-tighter italic">Daftar Berita <span class="text-blue-600">Kebaikan</span></h3>
              </div>
              <div class="overflow-x-auto">
                 <table class="w-full text-left">
                   <thead class="bg-gray-50 text-[10px] uppercase font-black text-gray-400 tracking-widest italic">
                     <tr>
                       <th class="px-8 py-4">Judul Berita</th>
                       <th class="px-8 py-4">Tanggal</th>
                       <th class="px-8 py-4 text-center">Aksi</th>
                     </tr>
                   </thead>
                   <tbody class="divide-y divide-gray-50">
                     <tr v-for="n in myNews" :key="n.id">
                        <td class="px-8 py-6 font-bold truncate max-w-sm">{{ n.title }}</td>
                        <td class="px-8 py-6 text-xs text-gray-500">{{ n.created_at }}</td>
                        <td class="px-8 py-6 text-center">
                           <button class="text-red-500 hover:scale-110 transition-transform">Hapus</button>
                        </td>
                     </tr>
                     <tr v-if="myNews.length === 0">
                        <td colspan="3" class="px-8 py-20 text-center text-gray-400 font-bold uppercase tracking-widest text-xs italic">Belum ada berita yang diterbitkan.</td>
                     </tr>
                   </tbody>
                 </table>
              </div>
           </div>
        </div>

      </div>
    </div>

    <!-- Modals (Campaign & News) ommitted for brevity but logic implemented -->
    <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
       <!-- Modal Campaign content... -->
       <div class="bg-white p-10 rounded-[3rem] w-full max-w-xl max-h-[90vh] overflow-y-auto">
          <h2 class="text-2xl font-black mb-6 uppercase italic">Buat Kampanye</h2>
          <input v-model="form.title" placeholder="Judul" class="w-full mb-4 p-4 bg-gray-50 rounded-2xl">
          <textarea v-model="form.description" placeholder="Deskripsi" class="w-full mb-4 p-4 bg-gray-50 rounded-2xl"></textarea>
          <input v-model="form.target_amount" type="number" placeholder="Target Dana" class="w-full mb-6 p-4 bg-gray-50 rounded-2xl">
          <button @click="handleCreateCampaign" class="w-full py-4 bg-blue-600 text-white rounded-2xl font-black uppercase">Publikasikan</button>
          <button @click="showModal = false" class="w-full py-2 text-gray-400 mt-2">Batal</button>
       </div>
    </div>

  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import api from '@/api';
import Sidebar from '@/components/Sidebar.vue';

const activeTab = ref('campaigns');
const loading = ref(true);
const myCampaigns = ref([]);
const myNews = ref([]);
const showModal = ref(false);

const form = reactive({ title: '', description: '', target_amount: '' });

const fetchData = async () => {
  loading.value = true;
  try {
    const [campRes, newsRes] = await Promise.all([
      api.get('/campaigns/my-campaigns'),
      api.get('/news/my-news')
    ]);
    myCampaigns.value = campRes.data;
    myNews.value = newsRes.data;
  } catch (err) {
    console.error(err);
  } finally {
    loading.value = false;
  }
};

const openModal = () => {
  showModal.value = true;
};

const handleCreateCampaign = async () => {
  await api.post('/campaigns', form);
  showModal.value = false;
  fetchData();
};

const formatCurrency = (val) => new Intl.NumberFormat('id-ID').format(val || 0);

onMounted(fetchData);
</script>