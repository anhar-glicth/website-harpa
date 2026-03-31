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
              <h1 class="text-3xl font-black text-gray-900 tracking-tight leading-none uppercase italic">Panel <span class="text-blue-600">Staff</span></h1>
              <span class="bg-blue-600 text-white text-[9px] font-black uppercase px-3 py-1 rounded-full shadow-lg shadow-blue-100">Live</span>
            </div>
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Manajemen Lapangan & Warta Kebaikan</p>
          </div>
          
          <div class="flex flex-col md:flex-row gap-3">
            <button @click="openNewsModal" class="px-6 py-3.5 bg-blue-50 text-blue-600 rounded-2xl font-black uppercase text-[10px] tracking-widest hover:bg-blue-100 transition-all border border-blue-100">Tulis Berita</button>
            <button @click="openCampaignModal" class="px-6 py-3.5 bg-blue-600 text-white rounded-2xl font-black uppercase text-[10px] tracking-widest hover:bg-blue-700 transition-all shadow-xl shadow-blue-100">Kampanye Baru</button>
          </div>
        </header>

        <!-- Navigasi Tab Intern -->
        <div class="flex items-center gap-2 bg-gray-100 p-1.5 rounded-2xl w-full overflow-x-auto no-scrollbar">
           <button @click="activeTab = 'campaigns'" :class="['px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all', activeTab === 'campaigns' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-500 hover:text-blue-600']">Proyek Saya</button>
           <button @click="activeTab = 'news'" :class="['px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all', activeTab === 'news' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-500 hover:text-blue-600']">Arsip Berita</button>
        </div>

        <!-- Tab: Campaigns -->
        <div v-if="activeTab === 'campaigns'" class="space-y-8">
           <div v-if="loading" class="py-20 text-center bg-white rounded-[2.5rem] border border-dashed border-gray-200">
              <div class="animate-spin h-8 w-8 border-4 border-blue-600 border-t-transparent rounded-full mx-auto mb-4"></div>
              <p class="text-gray-400 font-black text-[10px] uppercase tracking-widest">Sinkronisasi Proyek...</p>
           </div>
           
           <div v-else-if="myCampaigns.length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-8">
              <div v-for="c in myCampaigns" :key="c.id" class="group bg-white rounded-[2.5rem] border border-gray-100 overflow-hidden shadow-sm hover:shadow-xl transition-all duration-500">
                 <div class="relative h-48 overflow-hidden">
                    <img :src="c.foto || 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=600'" class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                    <span class="absolute top-6 left-6 px-3 py-1 bg-white/20 backdrop-blur-md text-white text-[9px] font-black uppercase rounded-full border border-white/20">{{ c.category || 'Umum' }}</span>
                 </div>
                 <div class="p-8">
                    <h3 class="font-black text-gray-900 uppercase italic mb-4 line-clamp-1 group-hover:text-blue-600 transition-colors">{{ c.title }}</h3>
                    <div class="flex justify-between items-end border-t border-gray-50 pt-4">
                       <div class="space-y-1">
                          <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Status Verifikasi</p>
                          <span :class="['text-[10px] font-black uppercase px-3 py-1 rounded-lg', c.status === 'approved' ? 'bg-blue-50 text-blue-600' : 'bg-amber-50 text-amber-600']">{{ c.status }}</span>
                       </div>
                       <div class="text-right">
                          <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Terkumpul</p>
                          <p class="font-black text-blue-600 text-lg tracking-tighter italic">Rp {{ formatCurrency(c.current_amount) }}</p>
                       </div>
                    </div>
                 </div>
              </div>
           </div>

           <div v-else class="py-20 text-center bg-white rounded-[3rem] border-2 border-dashed border-gray-100">
              <p class="text-gray-400 font-bold italic uppercase tracking-widest text-xs">Belum ada proyek yang Anda buat.</p>
           </div>
        </div>

        <!-- Tab: News -->
        <div v-if="activeTab === 'news'" class="space-y-8">
           <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden">
              <div class="p-8 border-b border-gray-50 bg-blue-50/20">
                 <h3 class="font-black text-gray-900 uppercase tracking-tighter italic">Berita <span class="text-blue-600">Kebaikan</span></h3>
              </div>
              <div class="overflow-x-auto">
                 <table class="w-full text-left">
                    <thead class="bg-gray-50 text-[10px] uppercase font-black text-gray-400 tracking-widest italic">
                      <tr>
                        <th class="px-8 py-4">Judul Berita</th>
                        <th class="px-8 py-4">Tanggal Terbit</th>
                        <th class="px-8 py-4 text-center">Tindakan</th>
                      </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                      <tr v-for="n in myNews" :key="n.id" class="hover:bg-gray-50/50 group">
                        <td class="px-8 py-6 font-bold text-gray-800 line-clamp-1 max-w-sm">{{ n.title }}</td>
                        <td class="px-8 py-6 text-[11px] text-gray-500 font-medium italic">{{ formatDate(n.created_at) }}</td>
                        <td class="px-8 py-6 text-center">
                           <button @click="handleDeleteNews(n.id)" class="text-red-500 hover:scale-110 transition-transform p-2 bg-red-50 rounded-xl">
                              <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                           </button>
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

    <!-- Modal: Buat Kampanye -->
    <Teleport to="body">
       <Transition name="fade">
          <div v-if="showCampaignModal" class="fixed inset-0 z-[200] flex items-center justify-center p-4">
             <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-md" @click="showCampaignModal = false"></div>
             <div class="relative bg-white rounded-[3rem] shadow-2xl w-full max-w-xl max-h-[90vh] overflow-hidden flex flex-col animate-in zoom-in duration-300">
                <div class="p-8 bg-blue-600 text-white flex justify-between items-center shrink-0">
                   <h3 class="text-xl font-black uppercase tracking-tighter italic">Buat <span class="opacity-60">Kampanye</span></h3>
                   <button @click="showCampaignModal = false" class="text-white/60 hover:text-white transition-colors">✕</button>
                </div>
                
                <form @submit.prevent="handleCreateCampaign" class="p-8 space-y-6 overflow-y-auto no-scrollbar">
                   <div class="space-y-4">
                      <div class="space-y-1">
                         <label class="text-[10px] font-black uppercase text-gray-400 ml-2 tracking-widest">Informasi Dasar</label>
                         <input v-model="form.title" placeholder="Judul Kampanye" class="w-full p-4 bg-gray-50 rounded-2xl outline-none focus:ring-2 focus:ring-blue-600 font-bold transition-all" required>
                         <textarea v-model="form.description" placeholder="Ceritakan urgensi kampanye ini..." class="w-full h-32 p-4 bg-gray-50 rounded-2xl outline-none focus:ring-2 focus:ring-blue-600 font-medium resize-none transition-all" required></textarea>
                      </div>

                      <div class="grid grid-cols-2 gap-4">
                         <div class="space-y-1">
                            <label class="text-[10px] font-black uppercase text-gray-400 ml-2 tracking-widest">Kategori</label>
                            <select v-model="form.category" class="w-full p-4 bg-gray-50 rounded-2xl font-black uppercase text-[10px] tracking-widest">
                               <option v-for="c in categories" :key="c" :value="c">{{ c }}</option>
                            </select>
                         </div>
                         <div class="space-y-1">
                            <label class="text-[10px] font-black uppercase text-gray-400 ml-2 tracking-widest">Target Dana</label>
                            <input v-model="form.target_amount" type="number" placeholder="Rp 0" class="w-full p-4 bg-gray-50 rounded-2xl outline-none focus:ring-2 focus:ring-blue-600 font-black" required>
                         </div>
                      </div>

                      <div class="space-y-2">
                         <label class="text-[10px] font-black uppercase text-gray-400 ml-2 tracking-widest">Foto Utama Kampanye</label>
                         <div 
                           class="relative w-full h-40 bg-gray-50 border-2 border-dashed border-gray-200 rounded-[2rem] flex flex-col items-center justify-center overflow-hidden group cursor-pointer hover:bg-gray-100 transition-all"
                           @click="$refs.campaignFileInput.click()"
                         >
                            <img v-if="form.foto" :src="form.foto" class="w-full h-full object-cover">
                            <div v-else class="text-center p-4">
                               <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-gray-300 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
                               <p class="text-[9px] font-black uppercase text-gray-400 tracking-widest leading-none">Klik untuk Unggah Foto</p>
                            </div>
                         </div>
                         <input ref="campaignFileInput" type="file" @change="e => handleFileChange(e, 'cam')" class="hidden" accept="image/*">
                      </div>
                   </div>

                   <button type="submit" :disabled="submitting" class="w-full py-5 bg-blue-600 text-white rounded-[1.5rem] font-black uppercase tracking-[0.2em] shadow-xl shadow-blue-100 hover:bg-blue-700 transition active:scale-95 disabled:opacity-50">
                      {{ submitting ? 'Memproses...' : 'Publikasikan Sekarang' }}
                   </button>
                </form>
             </div>
          </div>
       </Transition>
    </Teleport>

    <!-- Modal: Tulis Berita -->
    <Teleport to="body">
       <Transition name="fade">
          <div v-if="showNewsModal" class="fixed inset-0 z-[200] flex items-center justify-center p-4">
             <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-md" @click="showNewsModal = false"></div>
             <div class="relative bg-white rounded-[3rem] shadow-2xl w-full max-w-xl max-h-[90vh] overflow-hidden flex flex-col animate-in zoom-in duration-300">
                <div class="p-8 bg-blue-600 text-white flex justify-between items-center shrink-0">
                   <h3 class="text-xl font-black uppercase tracking-tighter italic">Kabar <span class="opacity-60">Lapangan</span></h3>
                   <button @click="showNewsModal = false" class="text-white/60 hover:text-white transition-colors">✕</button>
                </div>
                
                <form @submit.prevent="handleCreateNews" class="p-8 space-y-6 overflow-y-auto no-scrollbar">
                   <div class="space-y-4">
                      <div class="space-y-1">
                         <label class="text-[10px] font-black uppercase text-gray-400 ml-2 tracking-widest">Judul & Isi Berita</label>
                         <input v-model="newsForm.title" placeholder="Judul Berita" class="w-full p-4 bg-gray-50 rounded-2xl outline-none focus:ring-2 focus:ring-blue-600 font-bold transition-all" required>
                         <textarea v-model="newsForm.content" placeholder="Deskripsikan perkembangan terbaru di lapangan..." class="w-full h-48 p-4 bg-gray-50 rounded-2xl outline-none focus:ring-2 focus:ring-blue-600 font-medium resize-none transition-all" required></textarea>
                      </div>

                      <div class="space-y-2">
                         <label class="text-[10px] font-black uppercase text-gray-400 ml-2 tracking-widest">Foto Dokumentasi</label>
                         <div 
                           class="relative w-full h-40 bg-gray-50 border-2 border-dashed border-gray-200 rounded-[2rem] flex flex-col items-center justify-center overflow-hidden group cursor-pointer hover:bg-gray-100 transition-all"
                           @click="$refs.newsFileInput.click()"
                         >
                            <img v-if="newsForm.image" :src="newsForm.image" class="w-full h-full object-cover">
                            <div v-else class="text-center p-4">
                               <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-gray-300 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                               <p class="text-[9px] font-black uppercase text-gray-400 tracking-widest leading-none">Klik untuk Unggah Dokumentasi</p>
                            </div>
                         </div>
                         <input ref="newsFileInput" type="file" @change="e => handleFileChange(e, 'news')" class="hidden" accept="image/*">
                      </div>
                   </div>

                   <button type="submit" :disabled="submitting" class="w-full py-5 bg-blue-600 text-white rounded-[1.5rem] font-black uppercase tracking-[0.2em] shadow-xl shadow-blue-100 hover:bg-blue-700 transition active:scale-95 disabled:opacity-50">
                      {{ submitting ? 'Menerbitkan...' : 'Terbitkan Berita' }}
                   </button>
                </form>
             </div>
          </div>
       </Transition>
    </Teleport>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import api from '@/api';
import Sidebar from '@/components/Sidebar.vue';

const activeTab = ref('campaigns');
const loading = ref(true);
const submitting = ref(false);
const myCampaigns = ref([]);
const myNews = ref([]);

const showCampaignModal = ref(false);
const showNewsModal = ref(false);

const categories = ['Sosial', 'Medis', 'Pendidikan', 'Bencana', 'Lingkungan', 'Yatim'];

const form = reactive({ title: '', description: '', category: 'Sosial', target_amount: '', foto: '' });
const newsForm = reactive({ title: '', content: '', image: '' });

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

const openCampaignModal = () => {
  form.title = '';
  form.description = '';
  form.category = 'Sosial';
  form.target_amount = '';
  form.foto = '';
  showCampaignModal.value = true;
};

const openNewsModal = () => {
  newsForm.title = '';
  newsForm.content = '';
  newsForm.image = '';
  showNewsModal.value = true;
};

const handleFileChange = (e, type) => {
  const file = e.target.files[0];
  if (!file) return;
  const reader = new FileReader();
  reader.onload = (event) => {
    if (type === 'cam') form.foto = event.target.result;
    else newsForm.image = event.target.result;
  };
  reader.readAsDataURL(file);
};

const handleCreateCampaign = async () => {
  if (!form.foto) return alert('Harap unggah foto kampanye!');
  submitting.value = true;
  try {
    await api.post('/campaigns', form);
    showCampaignModal.value = false;
    fetchData();
  } catch (err) {
    alert('Gagal membuat kampanye.');
  } finally {
    submitting.value = false;
  }
};

const handleCreateNews = async () => {
  if (!newsForm.image) return alert('Harap unggah foto dokumentasi!');
  submitting.value = true;
  try {
    await api.post('/news', newsForm);
    showNewsModal.value = false;
    fetchData();
  } catch (err) {
    alert('Gagal menerbitkan berita.');
  } finally {
    submitting.value = false;
  }
};

const handleDeleteNews = async (id) => {
  if (!confirm('Hapus berita ini?')) return;
  try {
    await api.post(`/news/delete/${id}`); // We'll update backend to use post or delete properly
    fetchData();
  } catch (err) {
    alert('Gagal menghapus berita.');
  }
};

const formatCurrency = (val) => new Intl.NumberFormat('id-ID').format(val || 0);
const formatDate = (dateStr) => {
  if (!dateStr) return '';
  return new Date(dateStr).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
};

onMounted(fetchData);
</script>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.4s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>