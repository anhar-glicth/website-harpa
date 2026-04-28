<template>
  <div class="space-y-8 animate-in fade-in duration-700">
    <!-- Header -->
    <div class="bg-gradient-to-r from-green-700 to-green-600 rounded-[2.5rem] p-10 text-white shadow-2xl shadow-green-100">
       <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
          <div class="space-y-2">
             <div class="inline-flex items-center gap-2 px-3 py-1 bg-white/10 rounded-full backdrop-blur-md border border-white/10">
                <span class="w-2 h-2 bg-green-300 rounded-full animate-pulse"></span>
                <p class="text-[9px] font-black uppercase tracking-widest text-green-100">Financial Reporting</p>
             </div>
             <h2 class="text-4xl font-black tracking-tighter uppercase italic">Dashboard <span class="text-yellow-300">Transaksi</span></h2>
             <p class="text-green-50 text-xs font-medium max-w-md">Analisis arus dana masuk dan kontribusi donatur secara real-time.</p>
          </div>
          <button @click="fetchData" class="px-6 py-3 bg-white text-green-700 rounded-2xl font-black text-[11px] uppercase tracking-widest hover:bg-green-50 transition-all shadow-xl active:scale-95">
             <i class="fas fa-sync-alt mr-2" :class="{ 'animate-spin': loading }"></i> Refresh Data
          </button>
       </div>
    </div>

    <!-- Filters -->
    <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm space-y-6">
       <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
          <div class="space-y-2 col-span-1 md:col-span-2 lg:col-span-1">
             <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Tgl Donasi</label>
             <div class="flex gap-2 items-center">
                <input type="date" v-model="filters.startDate" class="flex-1 px-4 py-3 bg-gray-50 border-none rounded-xl text-xs font-bold focus:ring-2 focus:ring-green-500 min-w-0">
                <span class="text-gray-300 text-[10px] font-bold">s/d</span>
                <input type="date" v-model="filters.endDate" class="flex-1 px-4 py-3 bg-gray-50 border-none rounded-xl text-xs font-bold focus:ring-2 focus:ring-green-500 min-w-0">
             </div>
          </div>
          <div class="space-y-2">
             <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Program</label>
             <select v-model="filters.programId" class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl text-xs font-bold focus:ring-2 focus:ring-green-500">
                <option value="">All Program</option>
                <option v-for="opt in options.programs" :key="opt.id" :value="opt.id">{{ opt.title }}</option>
             </select>
          </div>
          <div class="space-y-2">
             <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">PIC Prospek</label>
             <select v-model="filters.picId" class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl text-xs font-bold focus:ring-2 focus:ring-green-500">
                <option value="">All PIC</option>
                <option v-for="opt in options.pics" :key="opt.id" :value="opt.id">{{ opt.name }}</option>
             </select>
          </div>
          <div class="flex items-end">
             <button @click="fetchData" class="w-full py-3.5 bg-green-600 text-white rounded-xl text-[11px] font-black uppercase tracking-widest shadow-lg hover:bg-green-700 transition-all">Show Results</button>
          </div>
       </div>
    </div>

    <!-- KPI Totals -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
       <div class="bg-white p-10 rounded-[3rem] border border-gray-100 shadow-sm">
          <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 italic">Total Dana Masuk</p>
          <h4 class="text-4xl font-black text-green-600 tracking-tighter leading-none">Rp {{ formatNumber(dashData.summary?.total_amount || 0) }}</h4>
       </div>
       <div class="bg-white p-10 rounded-[3rem] border border-gray-100 shadow-sm">
          <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 italic">∑ Transaksi Sukses</p>
          <h4 class="text-4xl font-black text-gray-900 tracking-tighter leading-none">{{ formatNumber(dashData.summary?.total_transactions || 0) }}</h4>
       </div>
       <div class="bg-white p-10 rounded-[3rem] border border-gray-100 shadow-sm">
          <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 italic">∑ Donatur Terlibat</p>
          <h4 class="text-4xl font-black text-blue-600 tracking-tighter leading-none">{{ formatNumber(dashData.summary?.total_donors || 0) }}</h4>
       </div>
    </div>

    <!-- Visual Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
       <!-- Achievement by PIC Chart -->
       <div class="bg-white p-10 rounded-[3rem] border border-gray-100 shadow-sm">
          <div class="flex items-center justify-between mb-8">
             <h3 class="text-xs font-black text-gray-900 uppercase tracking-widest">Share Capaian per PIC</h3>
             <i class="fas fa-chart-pie text-gray-200 text-xl"></i>
          </div>
          <div class="flex flex-col items-center">
             <canvas ref="picChartCanvas" width="280" height="280"></canvas>
             <div class="mt-8 space-y-3 w-full max-h-48 overflow-y-auto custom-scrollbar pr-2">
                <div v-for="(item, i) in dashData.picResume" :key="i" class="flex items-center justify-between gap-4">
                   <div class="flex items-center gap-2 min-w-0">
                      <div class="w-3 h-3 rounded-full flex-shrink-0" :style="{ backgroundColor: getChartColor(i) }"></div>
                      <p class="text-[10px] font-bold text-gray-500 uppercase truncate">{{ item.label }}</p>
                   </div>
                   <p class="text-[10px] font-black text-gray-900">{{ item.percent }}%</p>
                </div>
             </div>
          </div>
       </div>

       <!-- Achievement by Office Chart -->
       <div class="bg-white p-10 rounded-[3rem] border border-gray-100 shadow-sm">
          <div class="flex items-center justify-between mb-8">
             <h3 class="text-xs font-black text-gray-900 uppercase tracking-widest">Share Capaian per Kantor</h3>
             <i class="fas fa-city text-gray-200 text-xl"></i>
          </div>
          <div class="flex flex-col items-center">
             <canvas ref="officeChartCanvas" width="280" height="280"></canvas>
             <div class="mt-8 space-y-3 w-full max-h-48 overflow-y-auto custom-scrollbar pr-2">
                <div v-for="(item, i) in dashData.officeResume" :key="i" class="flex items-center justify-between gap-4">
                   <div class="flex items-center gap-2 min-w-0">
                      <div class="w-3 h-3 rounded-full flex-shrink-0" :style="{ backgroundColor: getChartColor(i+5) }"></div>
                      <p class="text-[10px] font-bold text-gray-500 uppercase truncate">{{ item.label }}</p>
                   </div>
                   <p class="text-[10px] font-black text-gray-900">{{ item.percent }}%</p>
                </div>
             </div>
          </div>
       </div>
    </div>

    <!-- Resume Tables -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
       <!-- Table PIC -->
       <div class="bg-white rounded-[3rem] border border-gray-100 shadow-sm overflow-hidden">
          <div class="p-8 border-b border-gray-50 bg-gray-50/30">
             <h3 class="text-xs font-black text-gray-900 uppercase tracking-widest">Resume Capaian PIC Prospek</h3>
          </div>
          <div class="overflow-x-auto">
             <table class="w-full text-left text-[10px]">
                <thead class="bg-gray-50 text-gray-400 uppercase font-black">
                   <tr>
                      <th class="px-6 py-4">PIC Prospek</th>
                      <th class="px-4 py-4 text-right">Capaian</th>
                      <th class="px-4 py-4 text-center">% Kontribusi</th>
                   </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                   <tr v-for="row in dashData.picResume" :key="row.label" class="hover:bg-green-50/30 transition-colors">
                      <td class="px-6 py-4 font-black text-gray-700 uppercase">{{ row.label }}</td>
                      <td class="px-4 py-4 text-right font-black text-green-700">Rp {{ formatNumber(row.achievement) }}</td>
                      <td class="px-4 py-4 text-center">
                         <span class="px-3 py-1 bg-green-50 text-green-600 rounded-full font-black">{{ row.percent }}%</span>
                      </td>
                   </tr>
                </tbody>
             </table>
          </div>
       </div>

       <!-- Table Office -->
       <div class="bg-white rounded-[3rem] border border-gray-100 shadow-sm overflow-hidden">
          <div class="p-8 border-b border-gray-50 bg-gray-50/30">
             <h3 class="text-xs font-black text-gray-900 uppercase tracking-widest">Resume Capaian Per Kantor</h3>
          </div>
          <div class="overflow-x-auto">
             <table class="w-full text-left text-[10px]">
                <thead class="bg-gray-50 text-gray-400 uppercase font-black">
                   <tr>
                      <th class="px-6 py-4">Kantor</th>
                      <th class="px-4 py-4 text-right">Capaian</th>
                      <th class="px-4 py-4 text-center">% Kontribusi</th>
                   </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                   <tr v-for="row in dashData.officeResume" :key="row.label" class="hover:bg-green-50/30 transition-colors">
                      <td class="px-6 py-4 font-black text-gray-700 uppercase">{{ row.label }}</td>
                      <td class="px-4 py-4 text-right font-black text-blue-600">Rp {{ formatNumber(row.achievement) }}</td>
                      <td class="px-4 py-4 text-center">
                         <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-full font-black">{{ row.percent }}%</span>
                      </td>
                   </tr>
                </tbody>
             </table>
          </div>
       </div>
    </div>

    <!-- Top 20 Donors -->
    <div class="bg-white rounded-[4rem] border border-gray-100 shadow-xl overflow-hidden">
       <div class="p-10 border-b border-gray-50 bg-green-50/20 flex justify-between items-center">
          <div>
             <h3 class="text-xl font-black text-gray-900 tracking-tighter italic">Top 20 Donatur Berdasarkan Kontribusi</h3>
             <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">Ranking donatur dengan akumulasi dana terbesar</p>
          </div>
          <div class="w-12 h-12 bg-green-600 text-white rounded-2xl flex items-center justify-center text-xl shadow-lg shadow-green-100"><i class="fas fa-medal"></i></div>
       </div>
       <div class="overflow-x-auto">
          <table class="w-full text-left">
             <thead class="bg-gray-50 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                <tr>
                   <th class="px-8 py-6">Nama Donatur</th>
                   <th class="px-8 py-6">Kantor / PIC</th>
                   <th class="px-8 py-6">Segmen / Profil</th>
                   <th class="px-8 py-6 text-right">Total Donasi</th>
                   <th class="px-8 py-6 text-center">% Kontribusi</th>
                </tr>
             </thead>
             <tbody class="divide-y divide-gray-50">
                <tr v-for="(don, i) in dashData.top20" :key="don.id" class="hover:bg-green-50/50 transition-all group">
                   <td class="px-8 py-6">
                      <div class="flex items-center gap-4">
                         <div class="w-10 h-10 bg-slate-100 rounded-2xl flex items-center justify-center text-[10px] font-black group-hover:bg-green-600 group-hover:text-white transition-colors">{{ i+1 }}</div>
                         <p class="font-black text-gray-900 text-[11px] uppercase group-hover:text-green-700">{{ don.name }}</p>
                      </div>
                   </td>
                   <td class="px-8 py-6">
                      <div class="flex flex-col gap-1">
                        <span class="text-[10px] font-black text-gray-700 uppercase">{{ don.office_name }}</span>
                        <span class="text-[9px] font-bold text-gray-400 italic">PIC: {{ don.pic_name }}</span>
                      </div>
                   </td>
                   <td class="px-8 py-6">
                      <div class="flex items-center gap-2">
                        <span class="px-2 py-1 bg-slate-100 text-[8px] font-black rounded-lg text-gray-500 uppercase">{{ don.segment }}</span>
                        <span class="px-2 py-1 bg-green-50 text-[8px] font-black rounded-lg text-green-600 uppercase">{{ don.profile }}</span>
                      </div>
                   </td>
                   <td class="px-8 py-6 text-right font-black text-green-700 text-sm">Rp {{ formatNumber(don.total_donation) }}</td>
                   <td class="px-8 py-6 text-center">
                      <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-green-600 text-white rounded-full font-black text-[10px] shadow-md shadow-green-100">
                         {{ don.percent }}%
                      </span>
                   </td>
                </tr>
             </tbody>
          </table>
       </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted, nextTick } from 'vue';
import api from '@/api';

const loading = ref(true);
const dashData = ref({ summary: {}, picResume: [], officeResume: [], top20: [] });
const picChartCanvas = ref(null);
const officeChartCanvas = ref(null);

const filters = reactive({
  startDate: '',
  endDate: '',
  programId: '',
  picId: '',
  officeId: ''
});

const options = reactive({
  programs: [],
  pics: [],
  offices: []
});

const fetchData = async () => {
  loading.value = true;
  try {
    const { data } = await api.get('/admin/transaction-dashboard', { params: filters });
    dashData.value = data;
    await nextTick();
    drawCharts();
  } catch (e) {
    console.error('Transaction Dashboard fetch error:', e);
  } finally {
    loading.value = false;
  }
};

const fetchOptions = async () => {
  try {
    const [progs, pics, offices] = await Promise.all([
      api.get('/admin/master/programs'),
      api.get('/admin/users'),
      api.get('/admin/master/office')
    ]);
    options.programs = progs.data.data || [];
    options.pics = pics.data.filter(u => u.role !== 'user');
    options.offices = offices.data.data || [];
  } catch (e) {
    console.error('Options fetch error:', e);
  }
};

const drawCharts = () => {
  if (picChartCanvas.value && dashData.value.picResume) {
    drawDonut(picChartCanvas.value, dashData.value.picResume.map(r => ({ label: r.label, value: r.achievement })));
  }
  if (officeChartCanvas.value && dashData.value.officeResume) {
    drawDonut(officeChartCanvas.value, dashData.value.officeResume.map(r => ({ label: r.label, value: r.achievement })), 5);
  }
};

const drawDonut = (canvas, data, colorOffset = 0) => {
  const ctx = canvas.getContext('2d');
  ctx.clearRect(0, 0, canvas.width, canvas.height);
  const total = data.reduce((s, r) => s + parseFloat(r.value || 0), 0);
  if (total === 0) return;

  let startAngle = -Math.PI / 2;
  const centerX = canvas.width / 2;
  const centerY = canvas.height / 2;
  const radius = Math.min(centerX, centerY) - 10;

  data.forEach((item, i) => {
    const sliceAngle = (parseFloat(item.value) / total) * 2 * Math.PI;
    ctx.beginPath();
    ctx.moveTo(centerX, centerY);
    ctx.arc(centerX, centerY, radius, startAngle, startAngle + sliceAngle);
    ctx.fillStyle = getChartColor(i + colorOffset);
    ctx.fill();
    startAngle += sliceAngle;
  });

  ctx.beginPath();
  ctx.arc(centerX, centerY, radius * 0.6, 0, 2 * Math.PI);
  ctx.fillStyle = '#ffffff';
  ctx.fill();
};

const getChartColor = (i) => {
  const colors = [
    '#10b981', '#3b82f6', '#f59e0b', '#ef4444', '#8b5cf6', 
    '#ec4899', '#06b6d4', '#f97316', '#64748b', '#14b8a6'
  ];
  return colors[i % colors.length];
};

const formatNumber = (val) => new Intl.NumberFormat('id-ID').format(val);

onMounted(() => {
  fetchOptions();
  fetchData();
});
</script>
