<template>
  <div class="space-y-8 animate-in fade-in duration-700">
    <!-- Header -->
    <div class="bg-gradient-to-r from-green-700 to-green-600 rounded-[2.5rem] p-10 text-white shadow-2xl shadow-green-100">
       <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
          <div class="space-y-2">
             <div class="inline-flex items-center gap-2 px-3 py-1 bg-white/10 rounded-full backdrop-blur-md border border-white/10">
                <span class="w-2 h-2 bg-green-300 rounded-full animate-pulse"></span>
                <p class="text-[9px] font-black uppercase tracking-widest text-green-100">Prospect Pipeline Analytics</p>
             </div>
             <h2 class="text-4xl font-black tracking-tighter uppercase italic">Dashboard <span class="text-yellow-300">Prospek</span></h2>
             <p class="text-green-50 text-xs font-medium max-w-md">Multi-dimensional analysis of potential donor acquisition channels.</p>
          </div>
          <button @click="fetchData" class="px-6 py-3 bg-white text-green-700 rounded-2xl font-black text-[11px] uppercase tracking-widest hover:bg-green-50 transition-all shadow-xl active:scale-95">
             <i class="fas fa-sync-alt mr-2" :class="{ 'animate-spin': loading }"></i> Refresh Data
          </button>
       </div>
    </div>

    <!-- Enhanced Filters Section -->
    <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm space-y-8">
       <div class="flex items-center gap-3">
          <div class="w-8 h-8 bg-green-50 text-green-600 rounded-xl flex items-center justify-center text-xs shadow-sm"><i class="fas fa-filter"></i></div>
          <h3 class="text-sm font-black text-gray-900 uppercase tracking-tight">Filter Lanjutan</h3>
       </div>
       
       <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-x-8 gap-y-6">
          <div class="space-y-2 col-span-1 md:col-span-2 lg:col-span-1">
             <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Tgl Prospek</label>
             <div class="flex gap-2 items-center">
                <input type="date" v-model="filters.startDate" class="flex-1 px-4 py-3 bg-gray-50 border-none rounded-xl text-xs font-bold focus:ring-2 focus:ring-green-500 min-w-0">
                <span class="text-gray-300 text-[10px] font-bold">s/d</span>
                <input type="date" v-model="filters.endDate" class="flex-1 px-4 py-3 bg-gray-50 border-none rounded-xl text-xs font-bold focus:ring-2 focus:ring-green-500 min-w-0">
             </div>
          </div>
          <div class="space-y-2">
             <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Media</label>
             <select v-model="filters.mediaId" class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl text-xs font-bold focus:ring-2 focus:ring-green-500">
                <option value="">All Media</option>
                <option v-for="opt in options.medias" :key="opt.id" :value="opt.id">{{ opt.name }}</option>
             </select>
          </div>
          <div class="space-y-2">
             <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Respon</label>
             <select v-model="filters.response" class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl text-xs font-bold focus:ring-2 focus:ring-green-500">
                <option value="">All Respon</option>
                <option value="Tertarik">Tertarik</option>
                <option value="Tidak Tertarik">Tidak Tertarik</option>
                <option value="Follow Up">Follow Up</option>
             </select>
          </div>
          <div class="space-y-2">
             <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Potensi Dana</label>
             <select v-model="filters.potentialRange" class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl text-xs font-bold focus:ring-2 focus:ring-green-500">
                <option value="">All Potensi</option>
                <option value="low">0 - 1 Juta</option>
                <option value="mid">1 - 10 Juta</option>
                <option value="high">> 10 Juta</option>
             </select>
          </div>
          <div class="space-y-2">
             <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">PIC Prospek</label>
             <select v-model="filters.picId" class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl text-xs font-bold focus:ring-2 focus:ring-green-500">
                <option value="">All PIC</option>
                <option v-for="opt in options.pics" :key="opt.id" :value="opt.id">{{ opt.name }}</option>
             </select>
          </div>
          <div class="space-y-2">
             <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Kantor</label>
             <select v-model="filters.officeId" class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl text-xs font-bold focus:ring-2 focus:ring-green-500">
                <option value="">All Kantor</option>
                <option v-for="opt in options.offices" :key="opt.id" :value="opt.id">{{ opt.name }}</option>
             </select>
          </div>
          <div class="flex items-end lg:col-span-2">
             <button @click="fetchData" class="w-full py-4 bg-green-600 text-white rounded-2xl text-[11px] font-black uppercase tracking-widest shadow-xl hover:bg-green-700 transition-all flex items-center justify-center gap-2">
               <i class="fas fa-search"></i> Show Results
             </button>
          </div>
       </div>
    </div>

    <!-- KPI Totals -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
       <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm">
          <p class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2">∑ All Prospek</p>
          <h4 class="text-3xl font-black text-gray-900 tracking-tighter">{{ formatNumber(dashData.kpi?.total_prospects || 0) }}</h4>
       </div>
       <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm">
          <p class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2">∑ Konversi</p>
          <h4 class="text-3xl font-black text-green-600 tracking-tighter">{{ formatNumber(dashData.kpi?.total_converted || 0) }}</h4>
       </div>
       <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm">
          <p class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2">∑ Calon Donatur</p>
          <h4 class="text-3xl font-black text-yellow-600 tracking-tighter">{{ formatNumber(dashData.kpi?.total_leads || 0) }}</h4>
       </div>
       <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm">
          <p class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2">∑ Potensi Dana</p>
          <h4 class="text-2xl font-black text-blue-600 tracking-tighter leading-none mt-2">Rp {{ formatNumber(dashData.kpi?.total_potential_fund || 0) }}</h4>
       </div>
    </div>

    <!-- Charts Grid (8 Pie Charts) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
       <div v-for="chart in chartConfigs" :key="chart.id" class="bg-white p-8 rounded-[3rem] border border-gray-100 shadow-sm flex flex-col items-center">
          <div class="w-full flex items-center justify-between mb-6">
             <h3 class="text-[10px] font-black text-gray-900 uppercase tracking-widest">{{ chart.title }}</h3>
             <i :class="[chart.icon, 'text-gray-200 text-sm']"></i>
          </div>
          <canvas :ref="el => setCanvasRef(el, chart.id)" width="200" height="200"></canvas>
          <div class="mt-6 w-full space-y-2 max-h-32 overflow-y-auto custom-scrollbar pr-2">
             <div v-for="(item, i) in dashData.charts?.[chart.id]" :key="i" class="flex items-center justify-between gap-2 border-b border-gray-50 pb-1">
                <div class="flex items-center gap-2 min-w-0">
                   <div class="w-2 h-2 rounded-full flex-shrink-0" :style="{ backgroundColor: getChartColor(i + chart.colorOffset) }"></div>
                   <p class="text-[9px] font-bold text-gray-500 uppercase truncate">{{ item.label }}</p>
                </div>
                <p class="text-[9px] font-black text-gray-900">{{ item.value }}</p>
             </div>
          </div>
       </div>
    </div>

    <!-- Resume Tables -->
    <div class="bg-white rounded-[3rem] border border-gray-100 shadow-sm overflow-hidden">
       <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
          <h3 class="text-xs font-black text-gray-900 uppercase tracking-widest">Resume Prospek Per Kantor</h3>
       </div>
       <div class="overflow-x-auto">
          <table class="w-full text-left text-[10px]">
             <thead class="bg-gray-50 text-gray-400 uppercase font-black">
                <tr>
                   <th class="px-6 py-4">Kantor</th>
                   <th class="px-4 py-4 text-center">Total Prospek</th>
                   <th class="px-4 py-4 text-right">Potensi Dana</th>
                </tr>
             </thead>
             <tbody class="divide-y divide-gray-50">
                <tr v-for="row in dashData.officeResume" :key="row.label" class="hover:bg-green-50/30 transition-colors">
                   <td class="px-6 py-4 font-black text-gray-700 uppercase">{{ row.label || 'Unknown' }}</td>
                   <td class="px-4 py-4 text-center font-bold">{{ row.value }}</td>
                   <td class="px-4 py-4 text-right font-black text-green-600">Rp {{ formatNumber(row.potential || 0) }}</td>
                </tr>
             </tbody>
          </table>
       </div>
    </div>

    <!-- Top 20 Prospects -->
    <div class="bg-white rounded-[4rem] border border-gray-100 shadow-xl overflow-hidden">
       <div class="p-10 border-b border-gray-50 bg-green-50/20">
          <h3 class="text-xl font-black text-gray-900 tracking-tighter italic">Top 20 Prospek Berdasarkan Potensi Dana</h3>
       </div>
       <div class="overflow-x-auto">
          <table class="w-full text-left">
             <thead class="bg-gray-50 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                <tr>
                   <th class="px-8 py-6">Nama Donatur</th>
                   <th class="px-8 py-6">Status</th>
                   <th class="px-8 py-6">PIC</th>
                   <th class="px-8 py-6 text-right">Potensi Dana</th>
                </tr>
             </thead>
             <tbody class="divide-y divide-gray-50">
                <tr v-for="(p, i) in dashData.top20" :key="p.id" class="hover:bg-green-50/50 transition-all group">
                   <td class="px-8 py-6">
                      <div class="flex items-center gap-4">
                         <div class="w-10 h-10 bg-slate-100 rounded-2xl flex items-center justify-center text-[10px] font-black group-hover:bg-green-600 group-hover:text-white transition-colors">{{ i+1 }}</div>
                         <p class="font-black text-gray-900 text-[11px] uppercase truncate w-32 group-hover:text-green-700">{{ p.name }}</p>
                      </div>
                   </td>
                   <td class="px-8 py-6">
                      <span :class="statusClass(p.status)" class="px-3 py-1 rounded-lg text-[8px] font-black uppercase tracking-widest">{{ p.status }}</span>
                   </td>
                   <td class="px-8 py-6 font-bold text-gray-500 text-[10px] uppercase italic">{{ p.pic_name || '-' }}</td>
                   <td class="px-8 py-6 text-right font-black text-green-700 text-sm">Rp {{ formatNumber(p.potential_fund || 0) }}</td>
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
const dashData = ref({ kpi: {}, charts: {}, officeResume: [], top20: [] });
const canvasRefs = {};

const chartConfigs = [
  { id: 'target', title: 'Distribusi Target', icon: 'fas fa-bullseye', colorOffset: 0 },
  { id: 'response', title: 'Distribusi Respon', icon: 'fas fa-comment-dots', colorOffset: 3 },
  { id: 'media', title: 'Distribusi Media', icon: 'fas fa-bullhorn', colorOffset: 6 },
  { id: 'method', title: 'Distribusi Metode', icon: 'fas fa-network-wired', colorOffset: 9 },
  { id: 'type', title: 'Distribusi Jenis', icon: 'fas fa-filter', colorOffset: 12 },
  { id: 'fundraiser', title: 'Distribusi Fundraiser', icon: 'fas fa-user-tie', colorOffset: 15 },
  { id: 'program_group', title: 'Distribusi Rumpun', icon: 'fas fa-layer-group', colorOffset: 18 },
  { id: 'campaign', title: 'Distribusi Campaign', icon: 'fas fa-project-diagram', colorOffset: 21 },
];

const filters = reactive({
  startDate: '', endDate: '', picId: '', officeId: '', mediaId: '',
  status: '', response: '', potentialRange: '', methodId: '', typeId: '', campaignId: ''
});

const options = reactive({ pics: [], offices: [], medias: [] });

const setCanvasRef = (el, id) => { if (el) canvasRefs[id] = el; };

const fetchData = async () => {
  loading.value = true;
  try {
    const { data } = await api.get('/admin/prospect-dashboard', { params: filters });
    dashData.value = data;
    await nextTick();
    drawAllCharts();
  } catch (e) {
    console.error('Prospect Dashboard fetch error:', e);
  } finally {
    loading.value = false;
  }
};

const drawAllCharts = () => {
  chartConfigs.forEach(config => {
    const canvas = canvasRefs[config.id];
    const data = dashData.value.charts?.[config.id];
    if (canvas && data) drawDonut(canvas, data, config.colorOffset);
  });
};

const drawDonut = (canvas, data, colorOffset) => {
  const ctx = canvas.getContext('2d');
  ctx.clearRect(0, 0, canvas.width, canvas.height);
  const total = data.reduce((s, r) => s + r.value, 0);
  if (total === 0) return;

  let startAngle = -Math.PI / 2;
  const centerX = canvas.width / 2;
  const centerY = canvas.height / 2;
  const radius = Math.min(centerX, centerY) - 5;

  data.forEach((item, i) => {
    const sliceAngle = (item.value / total) * 2 * Math.PI;
    ctx.beginPath();
    ctx.moveTo(centerX, centerY);
    ctx.arc(centerX, centerY, radius, startAngle, startAngle + sliceAngle);
    ctx.fillStyle = getChartColor(i + colorOffset);
    ctx.fill();
    startAngle += sliceAngle;
  });

  ctx.beginPath();
  ctx.arc(centerX, centerY, radius * 0.65, 0, 2 * Math.PI);
  ctx.fillStyle = '#ffffff';
  ctx.fill();
};

const getChartColor = (i) => {
  const colors = [
    '#10b981', '#3b82f6', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899', '#06b6d4', '#f97316', 
    '#64748b', '#14b8a6', '#4ade80', '#60a5fa', '#fbbf24', '#f87171', '#a78bfa', '#f472b6'
  ];
  return colors[i % colors.length];
};

const formatNumber = (val) => new Intl.NumberFormat('id-ID').format(val);
const statusClass = (s) => {
  const map = { 'Konversi': 'bg-green-100 text-green-700', 'Calon Donatur': 'bg-blue-100 text-blue-700', 'Prospek': 'bg-yellow-100 text-yellow-700' };
  return map[s] || 'bg-gray-50 text-gray-400';
};

onMounted(() => {
  api.get('/admin/users').then(r => options.pics = r.data);
  api.get('/admin/master/office').then(r => options.offices = r.data.data || []);
  api.get('/admin/master/prospect_media').then(r => options.medias = r.data.data || []);
  fetchData();
});
</script>
