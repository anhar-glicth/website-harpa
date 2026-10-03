<template>
  <div class="min-h-screen bg-transparent text-slate-900">
    <!-- Hero Header -->
    <header class="pt-32 pb-24 px-6 md:px-10 text-center relative overflow-hidden">
      <div class="max-w-4xl mx-auto space-y-6 relative z-10">
        <div class="inline-flex py-1 px-4 bg-green-600/10 rounded-full text-[9px] font-black uppercase tracking-widest border border-green-600/10 text-green-600">Warta Amal Abadi</div>
        <h1 class="text-4xl md:text-6xl font-black uppercase tracking-tighter leading-none italic text-slate-900">
          Kabar <span class="text-green-600">Terhangat</span> <br> <span class="text-slate-400">Kebaikan Kita</span>
        </h1>
        <p class="text-sm md:text-base font-bold text-slate-500 max-w-lg mx-auto leading-relaxed italic">Ikuti perkembangan terbaru dan laporan penyaluran titipan Anda dari seluruh pelosok negeri.</p>
      </div>
    </header>

    <!-- News Grid -->
    <div class="max-w-7xl mx-auto px-6 py-20">
      <div v-if="loading" class="flex flex-col items-center justify-center py-20">
         <div class="w-12 h-12 border-4 border-green-600 border-t-transparent rounded-full animate-spin mb-4"></div>
         <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Sinkronisasi Warta...</p>
      </div>

      <div v-else-if="newsList.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10 animate-in fade-in slide-in-from-bottom-8 duration-700">
        <article 
          v-for="post in newsList" 
          :key="post.id" 
          class="group space-y-5 transform transition-all duration-500 hover:-translate-y-4"
        >
          <div class="relative h-60 md:h-72 overflow-hidden rounded-[2.5rem] shadow-2xl shadow-slate-200/50 border border-slate-100 bg-white">
            <img :src="post.image || post.foto || 'https://images.unsplash.com/photo-1542810634-71277d95dcbb?q=80&w=1000'" :alt="post.title" class="w-full h-full object-cover transition duration-700 group-hover:scale-110">
            <div class="absolute top-6 left-6 p-1 bg-white/80 backdrop-blur-md border border-slate-100 rounded-2xl flex items-center gap-2 group-hover:bg-green-600 group-hover:border-green-500 transition duration-500">
              <div class="p-2.5 bg-green-600 rounded-xl text-white shadow-sm transition group-hover:rotate-6 group-hover:bg-white group-hover:text-green-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M12 2v20"/><path d="m17 7-5-5-5 5"/><path d="m17 17-5 5-5-5"/></svg>
              </div>
              <span class="text-[9px] font-black uppercase tracking-wider text-slate-900 group-hover:text-white pr-4 italic">Update Berita</span>
            </div>
          </div>

          <div class="px-4 space-y-4">
            <div class="flex items-center gap-4">
               <span class="text-[9px] font-black uppercase tracking-widest text-green-500 underline decoration-2 underline-offset-4 decoration-green-500/20">{{ post.author_name || 'Petugas Lapangan' }}</span>
               <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">{{ formatDate(post.created_at) }}</span>
            </div>
            <h3 class="text-xl font-black text-slate-900 leading-tight group-hover:text-green-600 transition-colors uppercase tracking-tight line-clamp-2 italic">
               {{ post.title }}
            </h3>
            <p class="text-slate-500 text-xs font-bold leading-relaxed line-clamp-3 opacity-70 italic">
               {{ post.content }}
            </p>
          </div>
        </article>
      </div>

      <div v-else class="py-32 text-center bg-white/70 backdrop-blur-xl rounded-[3rem] border-2 border-dashed border-slate-200">
         <p class="text-slate-400 font-bold uppercase tracking-widest text-xs italic">Belum ada berita yang tersedia saat ini.</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import api from '@/api';

const newsList = ref([]);
const loading = ref(true);

const fetchData = async () => {
   loading.value = true;
   try {
      const res = await api.get('/news');
      newsList.value = res.data;
   } catch (err) {
      console.error(err);
   } finally {
      loading.value = false;
   }
};

const formatDate = (dateStr) => {
   if (!dateStr) return '';
   return new Date(dateStr).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
};

onMounted(fetchData);
</script>
