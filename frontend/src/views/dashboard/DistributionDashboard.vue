<template>
  <div class="space-y-8 animate-in fade-in duration-700">
    <!-- Header -->
    <div class="bg-gradient-to-r from-green-700 to-green-600 rounded-[2.5rem] p-10 text-white shadow-2xl shadow-green-100">
       <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
          <div class="space-y-2">
             <div class="inline-flex items-center gap-2 px-3 py-1 bg-white/10 rounded-full backdrop-blur-md border border-white/10">
                <span class="w-2 h-2 bg-green-300 rounded-full animate-pulse"></span>
                <p class="text-[9px] font-black uppercase tracking-widest text-green-100">Distribution Impact Analytics</p>
             </div>
             <h2 class="text-4xl font-black tracking-tighter uppercase italic">Dashboard <span class="text-yellow-300">Penyaluran</span></h2>
             <p class="text-green-50 text-xs font-medium max-w-md">Multi-dimensional analysis of social aid distribution and beneficiary reach.</p>
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
             <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Tgl Penyaluran</label>
             <div class="flex gap-2 items-center">
                <input type="date" v-model="filters.startDate" class="flex-1 px-4 py-3 bg-gray-50 border-none rounded-xl text-xs font-bold focus:ring-2 focus:ring-green-500 min-w-0">
                <span class="text-gray-300 text-[10px] font-bold">s/d</span>
                <input type="date" v-model="filters.endDate" class="flex-1 px-4 py-3 bg-gray-50 border-none rounded-xl text-xs font-bold focus:ring-2 focus:ring-green-500 min-w-0">
             </div>
          </div>
          <div class="space-y-2">
             <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Sumber Dana</label>
             <select v-model="filters.source" class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl text-xs font-bold focus:ring-2 focus:ring-green-500">
                <option value="">All Sumber</option>
                <option value="Zakat">Zakat</option>
                <option value="Infak">Infak</option>
                <option value="Sedekah">Sedekah</option>
                <option value="Wakaf">Wakaf</option>
                <option value="CSR">CSR</option>
             </select>
          </div>
          <div class="space-y-2">
             <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Asnaf</label>
             <select v-model="filters.asnaf" class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl text-xs font-bold focus:ring-2 focus:ring-green-500">
                <option value="">All Asnaf</option>
                <option value="Fakir">Fakir</option>
                <option value="Miskin">Miskin</option>
                <option value="Gharimin">Gharimin</option>
                <option value="Fisabilillah">Fisabilillah</option>
                <option value="Ibnu Sabil">Ibnu Sabil</option>
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
          <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 italic">Total Dana Disalurkan</p>
          <h4 class="text-4xl font-black text-green-600 tracking-tighter leading-none">Rp {{ formatNumber(dashData.summary?.total_amount || 0) }}</h4>
       </div>
       <div class="bg-white p-10 rounded-[3rem] border border-gray-100 shadow-sm">
          <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 italic">Total Penerima Manfaat</p>
          <h4 class="text-4xl font-black text-gray-900 tracking-tighter leading-none">{{ formatNumber(dashData.summary?.total_pm || 0) }}</h4>
       </div>
       <div class="bg-white p-10 rounded-[3rem] border border-gray-100 shadow-sm">
          <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 italic">∑ Aktivitas Penyaluran</p>
          <h4 class="text-4xl font-black text-blue-600 tracking-tighter leading-none">{{ formatNumber(dashData.summary?.total_distributions || 0) }}</h4>
       </div>
    </div>

    <!-- Distribution Charts (4 Pie Charts) -->
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
                <p class="text-[9px] font-black text-gray-900">{{ item.percent }}%</p>
             </div>
          </div>
       </div>
    </div>

    <!-- Monthly Trend Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
       <div class="bg-white p-10 rounded-[3rem] border border-gray-100 shadow-sm">
          <div class="flex items-center justify-between mb-8">
             <h3 class="text-xs font-black text-gray-900 uppercase tracking-widest">Tren Dana Penyaluran</h3>
             <i class="fas fa-chart-line text-gray-200 text-xl"></i>
          </div>
          <canvas ref="nominalChartCanvas" height="200"></canvas>
       </div>
       <div class="bg-white p-10 rounded-[3rem] border border-gray-100 shadow-sm">
          <div class="flex items-center justify-between mb-8">
             <h3 class="text-xs font-black text-gray-900 uppercase tracking-widest">Tren Jumlah PM Bulanan</h3>
             <i class="fas fa-users text-gray-200 text-xl"></i>
          </div>
          <canvas ref="pmChartCanvas" height="200"></canvas>
       </div>
    </div>

    <!-- Resume Tables -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
       <!-- Table By Asnaf -->
       <div class="bg-white rounded-[3rem] border border-gray-100 shadow-sm overflow-hidden">
          <div class="p-8 border-b border-gray-50 bg-gray-50/30">
             <h3 class="text-xs font-black text-gray-900 uppercase tracking-widest">Resume Penyaluran By Asnaf</h3>
          </div>
          <div class="overflow-x-auto">
             <table class="w-full text-left text-[10px]">
                <thead class="bg-gray-50 text-gray-400 uppercase font-black">
                   <tr>
                      <th class="px-6 py-4">Asnaf</th>
                      <th class="px-4 py-4 text-center">Total PM</th>
                      <th class="px-4 py-4 text-right">Nominal</th>
                      <th class="px-4 py-4 text-center">% Dana</th>
                   </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                   <tr v-for="row in dashData.charts?.asnaf" :key="row.label" class="hover:bg-green-50/30 transition-colors">
                      <td class="px-6 py-4 font-black text-gray-700 uppercase">{{ row.label }}</td>
                      <td class="px-4 py-4 text-center font-bold">{{ formatNumber(row.total_pm) }}</td>
                      <td class="px-4 py-4 text-right font-black text-green-700">Rp {{ formatNumber(row.total_amount) }}</td>
                      <td class="px-4 py-4 text-center"><span class="px-3 py-1 bg-green-50 text-green-600 rounded-full font-black">{{ row.percent }}%</span></td>
                   </tr>
                </tbody>
             </table>
          </div>
       </div>

       <!-- Table By City -->
       <div class="bg-white rounded-[3rem] border border-gray-100 shadow-sm overflow-hidden">
          <div class="p-8 border-b border-gray-50 bg-gray-50/30">
             <h3 class="text-xs font-black text-gray-900 uppercase tracking-widest">Resume Penyaluran By Wilayah</h3>
          </div>
          <div class="overflow-x-auto">
             <table class="w-full text-left text-[10px]">
                <thead class="bg-gray-50 text-gray-400 uppercase font-black">
                   <tr>
                      <th class="px-6 py-4">Wilayah</th>
                      <th class="px-4 py-4 text-center">Total PM</th>
                      <th class="px-4 py-4 text-right">Nominal</th>
                      <th class="px-4 py-4 text-center">% Dana</th>
                   </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                   <tr v-for="row in dashData.byCity" :key="row.label" class="hover:bg-green-50/30 transition-colors">
                      <td class="px-6 py-4 font-black text-gray-700 uppercase">{{ row.label }} ({{ row.province }})</td>
                      <td class="px-4 py-4 text-center font-bold">{{ formatNumber(row.total_pm) }}</td>
                      <td class="px-4 py-4 text-right font-black text-blue-600">Rp {{ formatNumber(row.total_amount) }}</td>
                      <td class="px-4 py-4 text-center"><span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-full font-black">{{ row.percent }}%</span></td>
                   </tr>
                </tbody>
             </table>
          </div>
       </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted, nextTick } from 'vue';
import api from '@/api';

const loading = ref(true);
const dashData = ref({ summary: {}, charts: {}, byCity: [], monthlyTrend: [] });
const nominalChartCanvas = ref(null);
const pmChartCanvas = ref(null);
const canvasRefs = {};

const chartConfigs = [
  { id: 'asnaf', title: 'Distribusi Asnaf', icon: 'fas fa-hand-holding-heart', colorOffset: 0 },
  { id: 'source', title: 'Sumber Dana', icon: 'fas fa-wallet', colorOffset: 4 },
  { id: 'category', title: 'Kategori Program', icon: 'fas fa-tags', colorOffset: 8 },
  { id: 'program', title: 'Nama Program', icon: 'fas fa-project-diagram', colorOffset: 12 },
];

const filters = reactive({
  startDate: '', endDate: '', category: '', asnaf: '', city: '', province: '', source: ''
});

const setCanvasRef = (el, id) => { if (el) canvasRefs[id] = el; };

const fetchData = async () => {
  loading.value = true;
  try {
    const { data } = await api.get('/admin/distribution-dashboard', { params: filters });
    dashData.value = data;
    await nextTick();
    drawAllCharts();
  } catch (e) {
    console.error('Distribution Dashboard fetch error:', e);
  } finally {
    loading.value = false;
  }
};

const drawAllCharts = () => {
  // Pie Charts
  chartConfigs.forEach(config => {
    const canvas = canvasRefs[config.id];
    const data = dashData.value.charts?.[config.id];
    if (canvas && data) drawDonut(canvas, data, config.colorOffset);
  });

  // Bar Trends
  if (nominalChartCanvas.value && dashData.value.monthlyTrend) {
    drawBarChart(nominalChartCanvas.value, dashData.value.monthlyTrend.map(t => t.month), dashData.value.monthlyTrend.map(t => t.nominal), '#10b981');
  }
  if (pmChartCanvas.value && dashData.value.monthlyTrend) {
    drawBarChart(pmChartCanvas.value, dashData.value.monthlyTrend.map(t => t.month), dashData.value.monthlyTrend.map(t => t.pm), '#3b82f6');
  }
};

const drawDonut = (canvas, data, colorOffset) => {
  const ctx = canvas.getContext('2d');
  ctx.clearRect(0, 0, canvas.width, canvas.height);
  const total = data.reduce((s, r) => s + parseFloat(r.total_amount || 0), 0);
  if (total === 0) return;

  let startAngle = -Math.PI / 2;
  const centerX = canvas.width / 2;
  const centerY = canvas.height / 2;
  const radius = Math.min(centerX, centerY) - 5;

  data.forEach((item, i) => {
    const sliceAngle = (parseFloat(item.total_amount) / total) * 2 * Math.PI;
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

const drawBarChart = (canvas, labels, values, color) => {
  const ctx = canvas.getContext('2d');
  ctx.clearRect(0, 0, canvas.width, canvas.height);
  if (!values.length) return;

  const maxVal = Math.max(...values);
  const barWidth = (canvas.width - 40) / labels.length;
  const chartHeight = canvas.height - 40;

  values.forEach((val, i) => {
    const h = (val / (maxVal || 1)) * chartHeight;
    const x = 20 + i * barWidth;
    const y = canvas.height - 20 - h;
    ctx.fillStyle = color;
    ctx.fillRect(x + 5, y, barWidth - 10, h);
    
    ctx.fillStyle = '#94a3b8';
    ctx.font = '8px Arial';
    ctx.textAlign = 'center';
    ctx.fillText(labels[i].split('-')[1], x + barWidth/2, canvas.height - 5);
  });
};

const getChartColor = (i) => {
  const colors = [
    '#10b981', '#3b82f6', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899', '#06b6d4', '#f97316', 
    '#64748b', '#14b8a6', '#4ade80', '#60a5fa', '#fbbf24', '#f87171', '#a78bfa', '#f472b6'
  ];
  return colors[i % colors.length];
};

const formatNumber = (val) => new Intl.NumberFormat('id-ID').format(val);

onMounted(() => {
  fetchData();
});
</script>
