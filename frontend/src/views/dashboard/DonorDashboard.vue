<template>
  <div class="p-4 lg:p-8 space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-1000">
    <!-- Header: Ultra Premium Glassmorphism Design -->
    <div class="relative overflow-hidden bg-slate-950 rounded-[3rem] p-10 lg:p-14 text-white shadow-2xl shadow-slate-300/50">
      <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-emerald-500/20 rounded-full blur-[120px]"></div>
      <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 bg-blue-600/10 rounded-full blur-[100px]"></div>
      <div class="absolute top-1/2 left-1/4 w-40 h-40 bg-purple-500/10 rounded-full blur-[60px]"></div>
      
      <div class="relative z-10 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-8">
        <div class="space-y-4">
          <div class="inline-flex items-center gap-3 px-4 py-1.5 bg-white/5 rounded-full backdrop-blur-xl border border-white/10">
            <span class="relative flex h-2 w-2">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
              <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
            </span>
            <p class="text-[10px] font-black uppercase tracking-[0.3em] text-emerald-400">System Analytics v2.0</p>
          </div>
          <h2 class="text-5xl font-black tracking-tighter uppercase italic leading-none">
            Dashboard <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-teal-300">Donatur</span>
          </h2>
          <p class="text-slate-400 text-sm font-medium max-w-lg leading-relaxed">
            Visualisasi data komprehensif untuk memantau retensi, segmentasi, dan efektivitas pipeline prospek donatur secara real-time.
          </p>
        </div>
        
        <div class="flex flex-wrap items-center gap-6">
          <div class="text-right hidden sm:block">
            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Status Sinkronisasi</p>
            <p class="text-xs font-bold text-slate-300 flex items-center justify-end gap-2">
              <i class="fas fa-check-circle text-emerald-500"></i> Terhubung: {{ lastUpdate || '...' }}
            </p>
          </div>
          <button @click="fetchData" 
            class="group flex items-center gap-4 px-8 py-5 bg-white text-slate-950 rounded-2xl font-black text-[11px] uppercase tracking-widest hover:bg-emerald-500 hover:text-white transition-all duration-700 shadow-2xl shadow-emerald-500/20 active:scale-95"
          >
            <i class="fas fa-sync-alt group-hover:rotate-180 transition-transform duration-1000" :class="{ 'animate-spin': loading }"></i>
            Perbarui Analitik
          </button>
        </div>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="min-h-[500px] flex items-center justify-center bg-white/50 backdrop-blur-sm rounded-[4rem] border border-slate-100 shadow-inner">
      <div class="flex flex-col items-center gap-8">
        <div class="relative w-24 h-24">
          <div class="absolute inset-0 border-8 border-slate-100 rounded-full"></div>
          <div class="absolute inset-0 border-8 border-emerald-500 border-t-transparent rounded-full animate-spin"></div>
          <div class="absolute inset-4 border-4 border-blue-400 border-b-transparent rounded-full animate-spin-slow"></div>
        </div>
        <div class="text-center space-y-2">
          <p class="text-[13px] font-black text-slate-900 uppercase tracking-[0.4em]">Mengolah Data DB</p>
          <div class="flex gap-1 justify-center">
            <div class="w-1 h-1 bg-emerald-500 rounded-full animate-bounce"></div>
            <div class="w-1 h-1 bg-emerald-500 rounded-full animate-bounce [animation-delay:0.2s]"></div>
            <div class="w-1 h-1 bg-emerald-500 rounded-full animate-bounce [animation-delay:0.4s]"></div>
          </div>
        </div>
      </div>
    </div>

    <template v-else>
      <!-- KPI Cards -->
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
        <div v-for="(kpi, i) in kpiCards" :key="i" 
          class="group relative bg-white p-10 rounded-[3.5rem] border border-slate-100 shadow-sm hover:shadow-3xl hover:-translate-y-2 transition-all duration-700 overflow-hidden"
        >
          <div :class="['absolute -right-8 -top-8 w-32 h-32 rounded-full opacity-10 group-hover:scale-150 transition-transform duration-1000 pointer-events-none', kpi.bgClass]"></div>
          <div class="relative z-10 space-y-6">
            <div :class="['w-14 h-14 rounded-2xl flex items-center justify-center text-2xl shadow-sm', kpi.color]">
              <i :class="kpi.icon"></i>
            </div>
            <div>
              <p class="text-4xl font-black text-slate-950 tracking-tighter mb-2">{{ kpi.value }}</p>
              <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest leading-none">{{ kpi.label }}</p>
            </div>
            <div class="flex items-center gap-3">
              <span class="text-[10px] font-black text-emerald-600 bg-emerald-50 px-3 py-1 rounded-full border border-emerald-100">+{{ 4 + i }}%</span>
              <span class="text-[10px] text-slate-300 font-bold uppercase tracking-tighter italic">Growth Analysis</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Main Charts Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        
        <!-- Generic Chart Card Wrapper -->
        <div v-for="(chart, key) in pieCharts" :key="key" 
          class="bg-white p-10 rounded-[4rem] border border-slate-100 shadow-sm flex flex-col hover:shadow-2xl transition-all duration-700 group"
        >
          <div class="mb-10 flex justify-between items-start">
             <div class="space-y-1">
                <h3 class="text-base font-black text-slate-950 tracking-tight italic">{{ chart.title }}</h3>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">{{ chart.subtitle }}</p>
             </div>
             <div :class="['w-12 h-12 rounded-2xl flex items-center justify-center text-lg shadow-sm transition-transform group-hover:rotate-12', chart.iconBg]">
               <i :class="chart.icon"></i>
             </div>
          </div>
          
          <div class="flex flex-col items-center gap-10">
            <!-- Canvas Container -->
            <div class="relative w-56 h-56 flex items-center justify-center">
              <canvas :ref="el => setCanvasRef(el, key)" width="224" height="224" class="relative z-10"></canvas>
              <!-- Center Text Overlay -->
              <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none z-0">
                <p class="text-4xl font-black text-slate-950 tracking-tighter leading-none">{{ totalOf(key) }}</p>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mt-1">Data</p>
              </div>
            </div>
            
            <!-- Legend -->
            <div class="w-full grid grid-cols-1 gap-3">
              <div v-for="(item, i) in dashData.charts?.[key]" :key="i" 
                class="flex items-center justify-between p-3 bg-slate-50/50 rounded-2xl border border-transparent hover:border-slate-100 transition-all"
              >
                <div class="flex items-center gap-3">
                  <div class="w-3 h-3 rounded-full shadow-sm" :style="{ background: chart.colors[i % chart.colors.length] }"></div>
                  <span class="text-[10px] font-black text-slate-600 uppercase truncate max-w-[120px]">{{ item.label }}</span>
                </div>
                <div class="flex items-center gap-3">
                  <span class="text-[11px] font-black text-slate-950">{{ item.value }}</span>
                  <span class="text-[10px] font-bold text-slate-300 bg-white px-2 py-0.5 rounded-lg border border-slate-50">{{ getPercent(item.value, key) }}%</span>
                </div>
              </div>
              <div v-if="!dashData.charts?.[key]?.length" class="text-center py-6 text-slate-300 text-[10px] font-black uppercase italic">
                Data Kosong
              </div>
            </div>
          </div>
        </div>

      </div>

      <!-- Performance Section -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
        
        <!-- Office Distribution -->
        <div class="bg-slate-950 p-12 lg:p-14 rounded-[5rem] shadow-[0_50px_100px_-20px_rgba(0,0,0,0.3)] text-white relative overflow-hidden">
          <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl"></div>
          
          <div class="relative z-10 flex flex-col sm:flex-row justify-between items-start sm:items-center mb-14 gap-6">
            <div class="space-y-2">
              <h3 class="text-2xl font-black tracking-tighter italic leading-none">ANALISIS KANTOR</h3>
              <p class="text-[11px] text-slate-500 font-bold uppercase tracking-[0.3em]">Distribusi Data per Kantor Layanan</p>
            </div>
            <div class="w-16 h-16 bg-white/5 rounded-3xl flex items-center justify-center text-2xl text-emerald-400 border border-white/10 shadow-xl">
              <i class="fas fa-map-location-dot"></i>
            </div>
          </div>
          
          <div class="space-y-8">
            <div v-for="(item, i) in dashData.charts?.byOffice" :key="i" class="space-y-3 group">
              <div class="flex justify-between items-end px-1">
                <span class="text-[11px] font-black uppercase text-slate-400 tracking-widest group-hover:text-white transition-colors">{{ item.label }}</span>
                <div class="flex items-baseline gap-2">
                   <span class="text-lg font-black text-emerald-400">{{ item.value }}</span>
                   <span class="text-[10px] font-bold text-slate-600 uppercase">Entries</span>
                </div>
              </div>
              <div class="h-4 bg-white/5 rounded-full p-1 border border-white/5 shadow-inner">
                <div 
                  class="h-full rounded-full transition-all duration-1000 ease-out shadow-[0_0_15px_rgba(16,185,129,0.3)]"
                  :style="{ 
                    width: maxOffice > 0 ? (item.value / maxOffice * 100) + '%' : '0%',
                    background: `linear-gradient(90deg, #10b981, #34d399)`
                  }"
                ></div>
              </div>
            </div>
            <div v-if="!dashData.charts?.byOffice?.length" class="text-center py-24 bg-white/5 rounded-[3rem] border border-white/5 border-dashed">
              <i class="fas fa-city text-4xl text-slate-800 mb-6 block"></i>
              <p class="text-xs font-black text-slate-500 uppercase tracking-widest">Database Kantor Masih Kosong</p>
            </div>
          </div>
        </div>

        <!-- PIC Performance -->
        <div class="bg-white p-12 lg:p-14 rounded-[5rem] border border-slate-100 shadow-xl relative overflow-hidden">
          <div class="absolute bottom-0 left-0 w-64 h-64 bg-slate-50 rounded-full blur-3xl"></div>
          
          <div class="relative z-10 flex flex-col sm:flex-row justify-between items-start sm:items-center mb-14 gap-6">
            <div class="space-y-2">
              <h3 class="text-2xl font-black tracking-tighter italic leading-none text-slate-950">OPTIMASI PIC</h3>
              <p class="text-[11px] text-slate-400 font-bold uppercase tracking-[0.3em]">Load Data per PIC Prospek</p>
            </div>
            <div class="w-16 h-16 bg-slate-50 text-slate-950 rounded-3xl flex items-center justify-center text-2xl border border-slate-100 shadow-sm">
              <i class="fas fa-users-gear"></i>
            </div>
          </div>
          
          <div class="space-y-8">
            <div v-for="(item, i) in dashData.charts?.byPIC" :key="i" class="space-y-3 group">
              <div class="flex justify-between items-end px-1">
                <span class="text-[11px] font-black uppercase text-slate-500 tracking-widest group-hover:text-slate-950 transition-colors">{{ item.label }}</span>
                <div class="flex items-baseline gap-2">
                   <span class="text-lg font-black text-slate-900">{{ item.value }}</span>
                   <span class="text-[10px] font-bold text-slate-300 uppercase">Handled</span>
                </div>
              </div>
              <div class="h-4 bg-slate-50 rounded-full p-1 border border-slate-100 shadow-inner">
                <div 
                  class="h-full rounded-full transition-all duration-1000 ease-out"
                  :style="{ 
                    width: maxPIC > 0 ? (item.value / maxPIC * 100) + '%' : '0%',
                    background: `linear-gradient(90deg, #0f172a, #334155)`
                  }"
                ></div>
              </div>
            </div>
            <div v-if="!dashData.charts?.byPIC?.length" class="text-center py-24 bg-slate-50 rounded-[3rem] border border-slate-100 border-dashed">
              <i class="fas fa-user-clock text-4xl text-slate-200 mb-6 block"></i>
              <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Belum ada penugasan PIC</p>
            </div>
          </div>
        </div>

      </div>
    </template>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, nextTick } from 'vue';
import api from '@/api';

const loading = ref(true);
const dashData = ref({});
const lastUpdate = ref('');
const canvasRefs = ref({});

// UI Metadata
const chartColors = ['#10b981', '#3b82f6', '#8b5cf6', '#f59e0b', '#ef4444', '#06b6d4'];

const pieCharts = {
  segment: { title: 'Distribusi Segmen', subtitle: 'Retail vs Corporate', icon: 'fas fa-layer-group', iconBg: 'bg-emerald-50 text-emerald-600', colors: ['#10b981', '#3b82f6', '#8b5cf6', '#f59e0b', '#ef4444'] },
  retention: { title: 'Retensi Donatur', subtitle: 'Loyalty Metrics', icon: 'fas fa-sync', iconBg: 'bg-blue-50 text-blue-600', colors: ['#22c55e', '#f59e0b', '#6366f1', '#f43f5e', '#0ea5e9'] },
  gender: { title: 'Demografi Gender', subtitle: 'Gender Distribution', icon: 'fas fa-venus-mars', iconBg: 'bg-purple-50 text-purple-600', colors: ['#3b82f6', '#ec4899', '#94a3b8'] },
  contact: { title: 'Kualitas Kontak', subtitle: 'Data Quality Index', icon: 'fas fa-address-book', iconBg: 'bg-orange-50 text-orange-600', colors: ['#10b981', '#6366f1', '#f59e0b', '#ef4444'] },
  donation: { title: 'Aktivitas Donasi', subtitle: 'Transaction Frequency', icon: 'fas fa-coins', iconBg: 'bg-emerald-50 text-emerald-700', colors: ['#16a34a', '#f97316', '#dc2626'] },
  prospect: { title: 'Pipeline Prospek', subtitle: 'Sales Funnel Stage', icon: 'fas fa-funnel-dollar', iconBg: 'bg-rose-50 text-rose-600', colors: ['#8b5cf6', '#06b6d4', '#10b981', '#f59e0b'] }
};

const kpiCards = computed(() => {
  const k = dashData.value.kpi || {};
  return [
    { label: 'Database User', value: k.total_users || 0, icon: 'fas fa-users', color: 'bg-emerald-50 text-emerald-600', bgClass: 'bg-emerald-500' },
    { label: 'Active Donors', value: k.active_donors || 0, icon: 'fas fa-heart-circle-check', color: 'bg-blue-50 text-blue-600', bgClass: 'bg-blue-500' },
    { label: 'Leads (30 Days)', value: k.new_this_month || 0, icon: 'fas fa-user-plus', color: 'bg-purple-50 text-purple-600', bgClass: 'bg-purple-500' },
    { label: 'Mobile Sync', value: k.has_phone || 0, icon: 'fas fa-mobile-screen-button', color: 'bg-orange-50 text-orange-600', bgClass: 'bg-orange-500' },
  ];
});

const maxOffice = computed(() => Math.max(...(dashData.value.charts?.byOffice?.map(i => i.value) || [0])));
const maxPIC = computed(() => Math.max(...(dashData.value.charts?.byPIC?.map(i => i.value) || [0])));

const setCanvasRef = (el, key) => { if (el) canvasRefs.value[key] = el; };

const fetchData = async () => {
  loading.value = true;
  try {
    const { data } = await api.get('/admin/donor-dashboard');
    dashData.value = data;
    lastUpdate.value = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
    
    await nextTick();
    // Use a small delay to ensure DOM is fully ready for Canvas
    setTimeout(drawAllCharts, 100);
  } catch (e) {
    console.error('Donor Dashboard fetch error:', e);
  } finally {
    loading.value = false;
  }
};

const totalOf = (key) => (dashData.value.charts?.[key] || []).reduce((s, i) => s + Number(i.value), 0);
const getPercent = (value, key) => {
  const total = totalOf(key);
  return total > 0 ? Math.round((value / total) * 100) : 0;
};

const drawDonut = (canvas, data, colors) => {
  if (!canvas) return;
  const ctx = canvas.getContext('2d');
  const cx = canvas.width / 2, cy = canvas.height / 2;
  const r = cx - 15, innerR = cx - 45;
  const total = data?.reduce((s, d) => s + Number(d.value), 0) || 0;
  
  ctx.clearRect(0, 0, canvas.width, canvas.height);
  
  // Empty State Ring
  if (total === 0) {
    ctx.beginPath();
    ctx.arc(cx, cy, r, 0, 2 * Math.PI);
    ctx.fillStyle = '#f8fafc';
    ctx.fill();
    ctx.beginPath();
    ctx.arc(cx, cy, innerR, 0, 2 * Math.PI);
    ctx.fillStyle = '#ffffff';
    ctx.fill();
    return;
  }
  
  let startAngle = -Math.PI / 2;
  data.forEach((item, i) => {
    const slice = (Number(item.value) / total) * 2 * Math.PI;
    if (slice <= 0.001) return;
    
    ctx.beginPath();
    ctx.moveTo(cx, cy);
    ctx.arc(cx, cy, r, startAngle, startAngle + slice);
    ctx.closePath();
    ctx.fillStyle = colors[i % colors.length];
    ctx.fill();
    
    // Slice border
    ctx.strokeStyle = '#ffffff';
    ctx.lineWidth = 4;
    ctx.stroke();
    
    startAngle += slice;
  });
  
  // Donut Hole
  ctx.beginPath();
  ctx.arc(cx, cy, innerR, 0, 2 * Math.PI);
  ctx.fillStyle = '#ffffff';
  ctx.fill();
  
  // Outer subtle ring
  ctx.beginPath();
  ctx.arc(cx, cy, r + 5, 0, 2 * Math.PI);
  ctx.strokeStyle = '#f1f5f9';
  ctx.lineWidth = 1;
  ctx.stroke();
};

const drawAllCharts = () => {
  Object.keys(pieCharts).forEach(key => {
    const canvas = canvasRefs.value[key];
    const data = dashData.value.charts?.[key];
    const colors = pieCharts[key].colors;
    drawDonut(canvas, data, colors);
  });
};

onMounted(fetchData);
</script>

<style scoped>
.animate-spin-slow {
  animation: spin 3s linear infinite;
}
@keyframes spin {
  from { transform: rotate(0deg); }
  to { transform: rotate(360deg); }
}

/* Custom shadow for cards */
.shadow-3xl {
  box-shadow: 0 35px 60px -15px rgba(0, 0, 0, 0.05);
}
</style>
