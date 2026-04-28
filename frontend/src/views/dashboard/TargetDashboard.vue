<template>
  <div class="space-y-8 animate-in fade-in duration-700">
    <!-- Header -->
    <div class="bg-gradient-to-r from-green-700 to-green-600 rounded-[2.5rem] p-10 text-white shadow-2xl shadow-green-100">
       <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
          <div class="space-y-2">
             <div class="inline-flex items-center gap-2 px-3 py-1 bg-white/10 rounded-full backdrop-blur-md border border-white/10">
                <span class="w-2 h-2 bg-green-300 rounded-full animate-pulse"></span>
                <p class="text-[9px] font-black uppercase tracking-widest text-green-100">Performance Tracking</p>
             </div>
             <h2 class="text-4xl font-black tracking-tighter uppercase italic">Target vs <span class="text-yellow-300">Capaian</span></h2>
             <p class="text-green-50 text-xs font-medium max-w-md">Monitoring performa penggalangan dana dibandingkan dengan target bulanan.</p>
          </div>
          <div class="flex gap-4">
             <button @click="showAddTarget = true" class="px-6 py-3 bg-green-800/50 text-white border border-white/10 rounded-2xl font-black text-[11px] uppercase tracking-widest hover:bg-green-800 transition-all active:scale-95">
                <i class="fas fa-plus-circle mr-2"></i> Atur Target
             </button>
             <button @click="fetchData" class="px-6 py-3 bg-white text-green-700 rounded-2xl font-black text-[11px] uppercase tracking-widest hover:bg-green-50 transition-all shadow-xl active:scale-95">
                <i class="fas fa-sync-alt mr-2" :class="{ 'animate-spin': loading }"></i> Refresh
             </button>
          </div>
       </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm flex flex-col md:flex-row items-center gap-6">
       <div class="flex items-center gap-3">
          <div class="w-8 h-8 bg-green-50 text-green-600 rounded-xl flex items-center justify-center text-xs shadow-sm"><i class="fas fa-calendar-alt"></i></div>
          <h3 class="text-xs font-black text-gray-900 uppercase tracking-widest">Periode Bulan</h3>
       </div>
       <div class="flex-grow grid grid-cols-1 md:grid-cols-2 gap-4">
          <input type="date" v-model="filters.startDate" class="w-full px-6 py-3.5 bg-gray-50 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-green-500">
          <div class="flex items-center gap-4">
             <span class="text-xs font-bold text-gray-300 uppercase">s/d</span>
             <input type="date" v-model="filters.endDate" class="w-full px-6 py-3.5 bg-gray-50 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-green-500">
          </div>
       </div>
       <button @click="fetchData" class="px-8 py-3.5 bg-green-600 text-white rounded-2xl text-[11px] font-black uppercase tracking-widest shadow-lg hover:bg-green-700 transition-all whitespace-nowrap">Show Results</button>
    </div>

    <!-- KPI Totals -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
       <div class="bg-white p-10 rounded-[3rem] border border-gray-100 shadow-sm hover:border-green-100 transition-all">
          <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 italic">∑ Target</p>
          <h4 class="text-3xl font-black text-gray-900 tracking-tighter leading-none">Rp {{ formatNumber(dashData.summary?.totalTarget || 0) }}</h4>
       </div>
       <div class="bg-white p-10 rounded-[3rem] border border-gray-100 shadow-sm hover:border-green-100 transition-all">
          <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 italic">∑ Capaian</p>
          <h4 class="text-3xl font-black text-green-600 tracking-tighter leading-none">Rp {{ formatNumber(dashData.summary?.totalAchievement || 0) }}</h4>
       </div>
       <div class="bg-white p-10 rounded-[3rem] border border-gray-100 shadow-sm hover:border-green-100 transition-all">
          <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 italic">% Target Vs Capaian</p>
          <div class="flex items-baseline gap-2">
            <h4 class="text-4xl font-black text-gray-900 tracking-tighter leading-none">{{ dashData.summary?.percent || 0 }}</h4>
            <span class="text-lg font-black text-green-500">%</span>
          </div>
       </div>
       <div class="bg-white p-10 rounded-[3rem] border border-gray-100 shadow-sm hover:border-green-100 transition-all">
          <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 italic">Sisa Target</p>
          <h4 class="text-3xl font-black text-red-500 tracking-tighter leading-none">
             {{ dashData.summary?.sisa < 0 ? 'Surplus' : '' }} 
             {{ dashData.summary?.sisa < 0 ? '+' : '' }} 
             {{ formatNumber(Math.abs(dashData.summary?.sisa || 0)) }}
          </h4>
       </div>
    </div>

    <!-- Detailed Table -->
    <div class="bg-white rounded-[4rem] border border-gray-100 shadow-xl overflow-hidden">
       <div class="p-10 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
          <div class="flex items-center gap-4">
             <div class="w-12 h-12 bg-green-600 text-white rounded-2xl flex items-center justify-center text-xl shadow-lg shadow-green-200"><i class="fas fa-users-viewfinder"></i></div>
             <div>
                <h3 class="text-xl font-black text-gray-900 tracking-tighter italic">Resume Target Vs Capaian Per PIC</h3>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-[0.2em]">Monitoring Produktivitas Fundraising per Staff</p>
             </div>
          </div>
          <button @click="exportTable" class="px-5 py-2 bg-green-50 text-green-600 rounded-xl text-[10px] font-black uppercase tracking-widest border border-green-100 hover:bg-green-100 transition-all">Export Excel</button>
       </div>
       <div class="overflow-x-auto">
          <table class="w-full text-left">
             <thead class="bg-gray-50 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                <tr>
                   <th class="px-10 py-6">#</th>
                   <th class="px-10 py-6">Nama PIC Prospek</th>
                   <th class="px-10 py-6 text-right">Total Target</th>
                   <th class="px-10 py-6 text-right">Total Capaian</th>
                   <th class="px-10 py-6 text-right">Sisa Target</th>
                   <th class="px-10 py-6 text-center">% Capaian</th>
                </tr>
             </thead>
             <tbody class="divide-y divide-gray-50">
                <tr v-for="(row, i) in dashData.resume" :key="row.id" class="hover:bg-green-50/30 transition-all group">
                   <td class="px-10 py-8 font-black text-gray-300 group-hover:text-green-500">{{ i + 1 }}</td>
                   <td class="px-10 py-8">
                      <div class="flex items-center gap-4">
                         <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center text-[10px] font-black group-hover:bg-green-600 group-hover:text-white transition-all uppercase">{{ row.name.charAt(0) }}</div>
                         <p class="font-black text-gray-900 text-[11px] uppercase tracking-tight">{{ row.name }}</p>
                      </div>
                   </td>
                   <td class="px-10 py-8 text-right font-black text-gray-900 text-sm">Rp {{ formatNumber(row.target) }}</td>
                   <td class="px-10 py-8 text-right font-black text-green-600 text-sm">Rp {{ formatNumber(row.achievement) }}</td>
                   <td class="px-10 py-8 text-right font-bold text-sm" :class="row.sisa <= 0 ? 'text-green-500' : 'text-red-400'">
                      {{ row.sisa <= 0 ? '+' : '' }}{{ formatNumber(Math.abs(row.sisa)) }}
                   </td>
                   <td class="px-10 py-8 text-center">
                      <div class="inline-flex flex-col items-center gap-1">
                         <span class="text-sm font-black text-gray-900">{{ row.percent }}%</span>
                         <div class="w-20 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full bg-green-500 rounded-full transition-all duration-1000" :style="{ width: Math.min(row.percent, 100) + '%' }"></div>
                         </div>
                      </div>
                   </td>
                </tr>
             </tbody>
             <tfoot v-if="dashData.resume?.length" class="bg-slate-900 text-white font-black uppercase text-[10px]">
                <tr>
                   <td colspan="2" class="px-10 py-6">Total Keseluruhan</td>
                   <td class="px-10 py-6 text-right text-green-400">Rp {{ formatNumber(dashData.summary?.totalTarget) }}</td>
                   <td class="px-10 py-6 text-right text-green-400">Rp {{ formatNumber(dashData.summary?.totalAchievement) }}</td>
                   <td class="px-10 py-6 text-right" :class="dashData.summary?.sisa <= 0 ? 'text-green-400' : 'text-red-400'">
                      {{ dashData.summary?.sisa <= 0 ? '+' : '' }}{{ formatNumber(Math.abs(dashData.summary?.sisa)) }}
                   </td>
                   <td class="px-10 py-6 text-center text-yellow-300">{{ dashData.summary?.percent }}%</td>
                </tr>
             </tfoot>
          </table>
       </div>
    </div>

    <!-- Modal Atur Target -->
    <div v-if="showAddTarget" class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-slate-900/60 backdrop-blur-sm animate-in fade-in duration-300">
       <div class="bg-white w-full max-w-lg rounded-[3rem] shadow-2xl overflow-hidden border border-white/20 animate-in zoom-in-95 duration-300">
          <div class="p-10 bg-green-600 text-white">
             <h3 class="text-2xl font-black tracking-tighter uppercase italic leading-none mb-2">Atur Target PIC</h3>
             <p class="text-green-100 text-[10px] font-bold uppercase tracking-widest">Input target fundraising bulanan per staff</p>
          </div>
          <div class="p-10 space-y-6">
             <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Pilih PIC Prospek</label>
                <select v-model="targetForm.pic_id" class="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-green-500">
                   <option v-for="pic in pics" :key="pic.id" :value="pic.id">{{ pic.name }}</option>
                </select>
             </div>
             <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                   <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Bulan Target</label>
                   <input type="month" v-model="targetForm.month" class="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-green-500">
                </div>
                <div class="space-y-2">
                   <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Nominal Target (Rp)</label>
                   <input type="number" v-model="targetForm.amount" placeholder="Contoh: 10000000" class="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-green-500">
                </div>
             </div>
             <div class="flex gap-4 pt-4">
                <button @click="showAddTarget = false" class="flex-grow py-4 bg-gray-50 text-gray-400 rounded-2xl text-[11px] font-black uppercase tracking-widest hover:bg-gray-100 transition-all">Batal</button>
                <button @click="saveTarget" class="flex-grow py-4 bg-green-600 text-white rounded-2xl text-[11px] font-black uppercase tracking-widest shadow-lg hover:bg-green-700 transition-all">Simpan Target</button>
             </div>
          </div>
       </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import api from '@/api';

const loading = ref(true);
const showAddTarget = ref(false);
const dashData = ref({ summary: {}, resume: [] });
const pics = ref([]);

const filters = reactive({
  startDate: new Date(new Date().getFullYear(), new Date().getMonth(), 1).toISOString().split('T')[0],
  endDate: new Date(new Date().getFullYear(), new Date().getMonth() + 1, 0).toISOString().split('T')[0]
});

const targetForm = reactive({
  pic_id: '',
  month: new Date().toISOString().slice(0, 7),
  amount: ''
});

const fetchData = async () => {
  loading.value = true;
  try {
    const { data } = await api.get('/admin/target-dashboard', { params: filters });
    dashData.value = data;
  } catch (e) {
    console.error('Target Dashboard fetch error:', e);
  } finally {
    loading.value = false;
  }
};

const fetchPics = async () => {
  try {
    const { data } = await api.get('/admin/users');
    pics.value = data.filter(u => u.role !== 'user');
  } catch (e) {
    console.error('Fetch PIC error:', e);
  }
};

const saveTarget = async () => {
  if (!targetForm.pic_id || !targetForm.month || !targetForm.amount) {
    alert('Mohon isi semua field target!');
    return;
  }
  try {
    const monthDate = targetForm.month + '-01';
    await api.post('/admin/save-target', {
      pic_id: targetForm.pic_id,
      month_period: monthDate,
      target_amount: targetForm.amount
    });
    showAddTarget.value = false;
    alert('Target berhasil disimpan!');
    fetchData();
  } catch (e) {
    alert('Gagal menyimpan target.');
  }
};

const formatNumber = (val) => new Intl.NumberFormat('id-ID').format(val);
const exportTable = () => alert('Fitur Export sedang disiapkan.');

onMounted(() => {
  fetchPics();
  fetchData();
});
</script>
