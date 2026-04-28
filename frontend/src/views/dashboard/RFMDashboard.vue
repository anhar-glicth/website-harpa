<template>
  <div class="space-y-8 animate-in fade-in duration-700">
    <!-- Header: Pure Green Theme -->
    <div class="bg-gradient-to-r from-green-700 to-green-600 rounded-[2.5rem] p-10 text-white shadow-2xl shadow-green-100">
       <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
          <div class="space-y-2">
             <div class="inline-flex items-center gap-2 px-3 py-1 bg-white/10 rounded-full backdrop-blur-md border border-white/10">
                <span class="w-2 h-2 bg-green-300 rounded-full animate-pulse"></span>
                <p class="text-[9px] font-black uppercase tracking-widest text-green-100">RFM Analytics Engine</p>
             </div>
             <h2 class="text-4xl font-black tracking-tighter uppercase italic">Dashboard <span class="text-yellow-300">RFM</span></h2>
             <p class="text-green-50 text-xs font-medium max-w-md">Segmentasi donatur berdasarkan Recency, Frequency, dan Monetary.</p>
          </div>
          <button @click="fetchData" class="px-6 py-3 bg-white text-green-700 rounded-2xl font-black text-[11px] uppercase tracking-widest hover:bg-green-50 transition-all shadow-xl active:scale-95">
             <i class="fas fa-sync-alt mr-2" :class="{ 'animate-spin': loading }"></i> Perbarui Data
          </button>
       </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm space-y-6">
       <div class="flex items-center gap-3 mb-2">
          <div class="w-8 h-8 bg-green-50 text-green-600 rounded-xl flex items-center justify-center text-xs shadow-sm"><i class="fas fa-filter"></i></div>
          <h3 class="text-sm font-black text-gray-900 uppercase tracking-tight">Filter Data</h3>
       </div>
       <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
          <div class="space-y-2">
             <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Tgl Registrasi</label>
             <div class="flex gap-2 items-center">
                <input type="date" v-model="filters.startDate" class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl text-xs font-bold focus:ring-2 focus:ring-green-500">
                <span class="text-gray-300">s/d</span>
                <input type="date" v-model="filters.endDate" class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl text-xs font-bold focus:ring-2 focus:ring-green-500">
             </div>
          </div>
          <div class="space-y-2">
             <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Segmen</label>
             <select v-model="filters.segment" class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl text-xs font-bold focus:ring-2 focus:ring-green-500">
                <option value="">All</option>
                <option v-for="opt in options.segments" :key="opt" :value="opt">{{ opt }}</option>
             </select>
          </div>
          <div class="space-y-2">
             <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Jenis Kelamin</label>
             <select v-model="filters.gender" class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl text-xs font-bold focus:ring-2 focus:ring-green-500">
                <option value="All">All</option>
                <option value="L">Laki-laki</option>
                <option value="P">Perempuan</option>
             </select>
          </div>
          <div class="space-y-2">
             <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">RFM 90 Days</label>
             <select v-model="filters.rfm90" class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl text-xs font-bold focus:ring-2 focus:ring-green-500">
                <option value="All">All</option>
                <option v-for="opt in options.rfmProfiles" :key="opt" :value="opt">{{ opt }}</option>
             </select>
          </div>
          <div class="space-y-2">
             <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">RFM All Time</label>
             <select v-model="filters.rfmAll" class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl text-xs font-bold focus:ring-2 focus:ring-green-500">
                <option value="All">All</option>
                <option value="NEW">NEW</option>
                <option v-for="opt in options.rfmProfiles" :key="opt" :value="opt">{{ opt }}</option>
             </select>
          </div>
          <div class="space-y-2">
             <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">PIC Prospek</label>
             <select v-model="filters.picId" class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl text-xs font-bold focus:ring-2 focus:ring-green-500">
                <option value="">All</option>
                <option v-for="opt in options.pics" :key="opt.id" :value="opt.id">{{ opt.name }}</option>
             </select>
          </div>
          <div class="space-y-2">
             <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Kantor</label>
             <select v-model="filters.officeId" class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl text-xs font-bold focus:ring-2 focus:ring-green-500">
                <option value="">All</option>
                <option v-for="opt in options.offices" :key="opt.id" :value="opt.id">{{ opt.name }}</option>
             </select>
          </div>
          <div class="flex items-end">
             <button @click="fetchData" class="w-full py-3.5 bg-green-600 text-white rounded-xl text-[11px] font-black uppercase tracking-widest shadow-lg hover:bg-green-700 transition-all">Show Results</button>
          </div>
       </div>
    </div>

    <!-- KPI Totals -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
       <div v-for="(kpi, i) in kpiCards" :key="i" class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm hover:border-green-100 transition-all group">
          <p class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2 group-hover:text-green-600 transition-colors">∑ {{ kpi.label }}</p>
          <div class="flex items-baseline gap-2">
             <h4 class="text-3xl font-black text-gray-900 tracking-tighter">{{ formatNumber(kpi.value) }}</h4>
             <span class="text-[10px] font-bold text-slate-300">Donatur</span>
          </div>
       </div>
    </div>

    <!-- Tables Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
       <div class="bg-white rounded-[3rem] border border-gray-100 shadow-sm overflow-hidden">
          <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
             <h3 class="text-xs font-black text-gray-900 uppercase tracking-widest flex items-center gap-2">
               <span class="w-2 h-2 bg-green-500 rounded-full"></span>
               Resume RFM 90 Days By PIC
             </h3>
             <button @click="exportTable('pic90')" class="text-[10px] font-black text-green-600 uppercase tracking-widest hover:underline">Export</button>
          </div>
          <div class="overflow-x-auto">
             <table class="w-full text-left text-[10px]">
                <thead class="bg-gray-50 text-gray-400 uppercase font-black">
                   <tr>
                      <th class="px-6 py-4">PIC Prospek</th>
                      <th class="px-4 py-4 text-center">Champion</th>
                      <th class="px-4 py-4 text-center">Loyal</th>
                      <th class="px-4 py-4 text-center">Potential</th>
                      <th class="px-4 py-4 text-center">Dormant</th>
                   </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                   <tr v-for="row in dashData.pic90" :key="row.name" class="hover:bg-green-50/30 transition-colors group">
                      <td class="px-6 py-4 font-black text-gray-700 uppercase group-hover:text-green-700">{{ row.name }}</td>
                      <td class="px-4 py-4 text-center font-bold text-emerald-600">{{ row.Champion }}</td>
                      <td class="px-4 py-4 text-center font-bold text-blue-600">{{ row.Loyal }}</td>
                      <td class="px-4 py-4 text-center font-bold text-orange-500">{{ row.Potential }}</td>
                      <td class="px-4 py-4 text-center font-bold text-red-400">{{ row.Dormant }}</td>
                   </tr>
                </tbody>
             </table>
          </div>
       </div>

       <div class="bg-white rounded-[3rem] border border-gray-100 shadow-sm overflow-hidden">
          <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
             <h3 class="text-xs font-black text-gray-900 uppercase tracking-widest flex items-center gap-2">
               <span class="w-2 h-2 bg-green-500 rounded-full"></span>
               Resume RFM All Time By PIC
             </h3>
             <button @click="exportTable('picAll')" class="text-[10px] font-black text-green-600 uppercase tracking-widest hover:underline">Export</button>
          </div>
          <div class="overflow-x-auto">
             <table class="w-full text-left text-[10px]">
                <thead class="bg-gray-50 text-gray-400 uppercase font-black">
                   <tr>
                      <th class="px-6 py-4">PIC Prospek</th>
                      <th class="px-4 py-4 text-center">Champion</th>
                      <th class="px-4 py-4 text-center">Loyal</th>
                      <th class="px-4 py-4 text-center">Potential</th>
                      <th class="px-4 py-4 text-center">Dormant</th>
                      <th class="px-4 py-4 text-center">New</th>
                   </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                   <tr v-for="row in dashData.picAll" :key="row.name" class="hover:bg-green-50/30 transition-colors group">
                      <td class="px-6 py-4 font-black text-gray-700 uppercase group-hover:text-green-700">{{ row.name }}</td>
                      <td class="px-4 py-4 text-center font-bold text-emerald-600">{{ row.Champion }}</td>
                      <td class="px-4 py-4 text-center font-bold text-blue-600">{{ row.Loyal }}</td>
                      <td class="px-4 py-4 text-center font-bold text-orange-500">{{ row.Potential }}</td>
                      <td class="px-4 py-4 text-center font-bold text-red-400">{{ row.Dormant }}</td>
                      <td class="px-4 py-4 text-center font-bold text-gray-400">{{ row.New }}</td>
                   </tr>
                </tbody>
             </table>
          </div>
       </div>
    </div>

    <!-- Top 20 Donors -->
    <div class="bg-white rounded-[4rem] border border-gray-100 shadow-xl overflow-hidden">
       <div class="p-10 border-b border-gray-50 flex flex-col md:flex-row justify-between items-start md:items-center gap-6 bg-green-50/20">
          <div class="flex items-center gap-4">
             <div class="w-12 h-12 bg-green-600 text-white rounded-3xl flex items-center justify-center text-xl shadow-lg shadow-green-100"><i class="fas fa-crown"></i></div>
             <div>
                <h3 class="text-xl font-black text-gray-900 tracking-tighter italic">Top 20 Donatur Terbesar</h3>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-[0.2em]">Kontribusi All Time</p>
             </div>
          </div>
       </div>
       <div class="overflow-x-auto">
          <table class="w-full text-left">
             <thead class="bg-gray-50 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                <tr>
                   <th class="px-8 py-6">Nama Donatur</th>
                   <th class="px-8 py-6">ID Donatur</th>
                   <th class="px-8 py-6">Kantor</th>
                   <th class="px-8 py-6">RFM 90D</th>
                   <th class="px-8 py-6">RFM All</th>
                   <th class="px-8 py-6">PIC</th>
                   <th class="px-8 py-6 text-right">Trx</th>
                   <th class="px-8 py-6 text-right">Total Donasi</th>
                   <th class="px-8 py-6 text-right">% Kontribusi</th>
                </tr>
             </thead>
             <tbody class="divide-y divide-gray-50">
                <tr v-for="(don, i) in dashData.top20" :key="don.id" class="hover:bg-green-50/50 transition-all group">
                   <td class="px-8 py-6">
                      <div class="flex items-center gap-4">
                         <div class="w-10 h-10 bg-slate-100 rounded-2xl flex items-center justify-center text-[10px] font-black group-hover:bg-green-600 group-hover:text-white transition-colors">{{ i+1 }}</div>
                         <p class="font-black text-gray-900 text-[11px] uppercase truncate w-32 group-hover:text-green-700">{{ don.name }}</p>
                      </div>
                   </td>
                   <td class="px-8 py-6 font-bold text-gray-400 text-[10px]">{{ don.id }}</td>
                   <td class="px-8 py-6 font-bold text-gray-500 text-[10px] uppercase">{{ don.office || '-' }}</td>
                   <td class="px-8 py-6">
                      <span :class="rfmClass(don.rfm90)" class="px-3 py-1 rounded-lg text-[8px] font-black uppercase tracking-widest">{{ don.rfm90 }}</span>
                   </td>
                   <td class="px-8 py-6">
                      <span :class="rfmClass(don.rfmAll)" class="px-3 py-1 rounded-lg text-[8px] font-black uppercase tracking-widest">{{ don.rfmAll }}</span>
                   </td>
                   <td class="px-8 py-6 font-bold text-gray-500 text-[10px] uppercase italic">{{ don.pic || '-' }}</td>
                   <td class="px-8 py-6 text-right font-black text-gray-900 text-sm">{{ don.totalTrx }}</td>
                   <td class="px-8 py-6 text-right font-black text-green-700 text-sm">Rp {{ formatNumber(don.totalDonation) }}</td>
                   <td class="px-8 py-6 text-right">
                      <div class="inline-flex items-center gap-2 px-3 py-1 bg-green-50 text-green-600 rounded-full font-black text-[10px] border border-green-100">{{ don.contribution }}%</div>
                   </td>
                </tr>
             </tbody>
          </table>
       </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue';
import api from '@/api';

const loading = ref(true);
const dashData = ref({ summary: {}, pic90: [], picAll: [], top20: [] });

const filters = reactive({
  startDate: '',
  endDate: '',
  segment: '',
  gender: 'All',
  rfm90: 'All',
  rfmAll: 'All',
  picId: '',
  officeId: ''
});

const options = reactive({
  segments: ['Retail WA', 'Community', 'Perusahaan', 'Sekolah', 'Cash'],
  rfmProfiles: ['CHAMPION', 'LOYAL', 'POTENTIAL', 'LOW_ENGAGEMENT', 'DORMANT'],
  pics: [],
  offices: []
});

const kpiCards = computed(() => {
  const s = dashData.value.summary || {};
  return [
    { label: 'Donatur All', value: s.total || 0 },
    { label: 'Donatur Laki-laki', value: s.male || 0 },
    { label: 'Donatur Perempuan', value: s.female || 0 },
    { label: 'Gender Unknown', value: s.unknown || 0 },
  ];
});

const fetchData = async () => {
  loading.value = true;
  try {
    const { data } = await api.get('/admin/rfm-dashboard', { params: filters });
    dashData.value = data;
  } catch (e) {
    console.error('RFM Dashboard fetch error:', e);
  } finally {
    loading.value = false;
  }
};

const fetchOptions = async () => {
  try {
    const [pics, offices] = await Promise.all([
      api.get('/admin/users'),
      api.get('/admin/master/office')
    ]);
    options.pics = pics.data;
    options.offices = offices.data.data || [];
  } catch (e) {
    console.error('Options fetch error:', e);
  }
};

const formatNumber = (val) => new Intl.NumberFormat('id-ID').format(val);

const rfmClass = (profile) => {
  const map = {
    'CHAMPION': 'bg-emerald-100 text-emerald-700',
    'LOYAL': 'bg-blue-100 text-blue-700',
    'POTENTIAL': 'bg-orange-100 text-orange-700',
    'DORMANT': 'bg-red-50 text-red-600',
    'NEW': 'bg-gray-100 text-gray-500',
    'LOW_ENGAGEMENT': 'bg-gray-50 text-gray-400'
  };
  return map[profile] || 'bg-gray-50 text-gray-400';
};

const exportTable = (type) => {
  alert('Fitur ekspor ke Excel sedang dikembangkan.');
};

onMounted(() => {
  fetchOptions();
  fetchData();
});
</script>
