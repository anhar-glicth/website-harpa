<template>
  <div class="min-h-screen bg-gray-50/50 pb-20">
    <div class="max-w-5xl mx-auto px-4 py-8 space-y-8">
      
      <!-- ===== BREADCRUMB & BACK BUTTON ===== -->
      <div class="flex items-center justify-between animate-in fade-in slide-in-from-left-4 duration-500">
        <button @click="$router.push('/dashboard/admin')" class="flex items-center gap-2 text-gray-400 hover:text-green-600 transition-all font-black uppercase text-[10px] tracking-widest group">
          <div class="w-8 h-8 bg-white rounded-xl shadow-sm flex items-center justify-center group-hover:bg-green-50 group-hover:scale-110 transition-all">
            <i class="fas fa-arrow-left"></i>
          </div>
          Kembali ke Dashboard
        </button>
        <div class="flex items-center gap-2">
           <span class="w-2 h-2 bg-green-500 rounded-full animate-ping"></span>
           <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Live Worker Analytics</span>
        </div>
      </div>

      <!-- ===== WORKER PROFILE HEADER ===== -->
      <div class="bg-slate-900 rounded-[2.5rem] p-8 md:p-12 text-white relative overflow-hidden shadow-2xl animate-in zoom-in-95 duration-700">
        <!-- Decorative Elements -->
        <div class="absolute -right-20 -top-20 w-80 h-80 bg-green-500/10 rounded-full blur-3xl"></div>
        <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-blue-500/10 rounded-full blur-2xl"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row items-center gap-8 text-center md:text-left">
          <!-- Avatar -->
          <div class="w-24 h-24 md:w-32 md:h-32 bg-gradient-to-br from-green-400 to-emerald-600 rounded-[2.5rem] flex items-center justify-center text-4xl md:text-5xl font-black text-white shadow-2xl border-4 border-white/10 group hover:rotate-6 transition-all duration-500">
            {{ worker?.name?.charAt(0) || '?' }}
          </div>
          
          <div class="flex-1 space-y-4">
            <div>
              <p class="text-[10px] md:text-[11px] font-black text-green-400 uppercase tracking-[0.3em] mb-1">{{ worker?.role?.replace('_', ' ') || 'Petugas' }}</p>
              <h1 class="text-3xl md:text-5xl font-black tracking-tighter uppercase italic">{{ worker?.name || 'Memuat...' }}</h1>
            </div>
            
            <div class="flex flex-wrap items-center justify-center md:justify-start gap-4">
              <div class="px-4 py-2 bg-white/5 backdrop-blur-md rounded-2xl border border-white/10 flex items-center gap-3">
                <i class="fas fa-envelope text-green-400 text-xs"></i>
                <span class="text-[11px] font-bold text-white/70">{{ worker?.email }}</span>
              </div>
              <button @click="openAssignModal" class="px-6 py-2.5 bg-green-500 text-white rounded-2xl font-black uppercase text-[10px] tracking-widest shadow-lg shadow-green-500/30 hover:bg-green-400 hover:scale-105 active:scale-95 transition-all">
                <i class="fas fa-plus-circle mr-2"></i> Tambah Tugas
              </button>
            </div>
          </div>

          <!-- Quick Stats in Header -->
          <div class="flex flex-col items-center md:items-end gap-2">
            <div class="text-right">
              <p class="text-[9px] font-black text-white/40 uppercase tracking-widest mb-1 text-center md:text-right">Total Completion</p>
              <div class="text-4xl md:text-6xl font-black text-green-400 tracking-tighter tabular-nums leading-none">
                {{ stats.percentage }}%
              </div>
            </div>
            <div class="w-40 h-2 bg-white/10 rounded-full overflow-hidden mt-2">
              <div class="h-full bg-green-400 transition-all duration-1000 ease-out" :style="{ width: stats.percentage + '%' }"></div>
            </div>
          </div>
        </div>
      </div>

      <!-- ===== FILTER SECTION ===== -->
      <div class="bg-white p-6 md:p-8 rounded-[2.5rem] border border-gray-100 shadow-sm flex flex-wrap items-center gap-4 animate-in slide-in-from-bottom-4 duration-500">
        <div class="flex items-center gap-3 mr-auto">
          <div class="w-10 h-10 bg-green-50 text-green-600 rounded-xl flex items-center justify-center text-sm shadow-sm">
            <i class="fas fa-filter"></i>
          </div>
          <div>
            <h3 class="text-[11px] font-black text-gray-900 uppercase tracking-widest">Filter Laporan</h3>
            <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest">Pilih periode waktu tertentu</p>
          </div>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
          <select v-model="filters.year" class="px-4 py-2.5 bg-gray-50 border border-transparent rounded-xl text-[10px] font-black text-gray-700 outline-none focus:ring-2 focus:ring-green-400 cursor-pointer transition-all hover:bg-gray-100">
            <option value="">Tahun</option>
            <option v-for="y in availableYears" :key="y" :value="y">{{ y }}</option>
          </select>
          <select v-model="filters.month" class="px-4 py-2.5 bg-gray-50 border border-transparent rounded-xl text-[10px] font-black text-gray-700 outline-none focus:ring-2 focus:ring-green-400 cursor-pointer transition-all hover:bg-gray-100">
            <option value="">Bulan</option>
            <option v-for="(m, idx) in months" :key="idx+1" :value="idx+1">{{ m }}</option>
          </select>
          <select v-model="filters.day" class="px-4 py-2.5 bg-gray-50 border border-transparent rounded-xl text-[10px] font-black text-gray-700 outline-none focus:ring-2 focus:ring-green-400 cursor-pointer transition-all hover:bg-gray-100">
            <option value="">Tanggal</option>
            <option v-for="d in 31" :key="d" :value="d">{{ d }}</option>
          </select>
          <button v-if="hasFilters" @click="resetFilters" class="px-4 py-2.5 bg-red-50 text-red-500 rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-red-100 transition-all border border-red-100">
            <i class="fas fa-times"></i>
          </button>
        </div>
      </div>

      <!-- ===== STATS CARDS ===== -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 animate-in slide-in-from-bottom-6 duration-700">
        <!-- Card 1: Total -->
        <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm relative overflow-hidden group hover:scale-[1.02] transition-all duration-300">
          <div class="absolute -right-4 -top-4 w-24 h-24 bg-blue-50 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
          <div class="relative z-10 space-y-4">
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-xl">
              <i class="fas fa-clipboard-list"></i>
            </div>
            <div>
              <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Tugas</p>
              <p class="text-4xl font-black text-gray-900 tracking-tighter">{{ stats.total }}</p>
            </div>
            <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest">Tugas diberikan pada periode ini</p>
          </div>
        </div>

        <!-- Card 2: Selesai -->
        <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm relative overflow-hidden group hover:scale-[1.02] transition-all duration-300">
          <div class="absolute -right-4 -top-4 w-24 h-24 bg-green-50 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
          <div class="relative z-10 space-y-4">
            <div class="w-12 h-12 bg-green-50 text-green-600 rounded-2xl flex items-center justify-center text-xl">
              <i class="fas fa-check-double"></i>
            </div>
            <div>
              <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Tugas Selesai</p>
              <p class="text-4xl font-black text-green-600 tracking-tighter">{{ stats.completed }}</p>
            </div>
            <p class="text-[9px] text-green-600/60 font-bold uppercase tracking-widest">{{ stats.percentage }}% dari target tercapai</p>
          </div>
        </div>

        <!-- Card 3: Pending -->
        <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm relative overflow-hidden group hover:scale-[1.02] transition-all duration-300">
          <div class="absolute -right-4 -top-4 w-24 h-24 bg-amber-50 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
          <div class="relative z-10 space-y-4">
            <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center text-xl">
              <i class="fas fa-hourglass-half"></i>
            </div>
            <div>
              <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Pending / Proses</p>
              <p class="text-4xl font-black text-amber-600 tracking-tighter">{{ stats.pending }}</p>
            </div>
            <p class="text-[9px] text-amber-600/60 font-bold uppercase tracking-widest">Tugas yang belum diselesaikan</p>
          </div>
        </div>
      </div>

      <!-- ===== TASK LIST SECTION ===== -->
      <div class="bg-white rounded-[3rem] border border-gray-100 shadow-xl overflow-hidden animate-in slide-in-from-bottom-8 duration-700">
        <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
          <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-slate-900 text-white rounded-2xl flex items-center justify-center text-lg shadow-lg">
              <i class="fas fa-tasks"></i>
            </div>
            <div>
              <h2 class="text-xl font-black text-gray-900 tracking-tighter uppercase italic">Log <span class="text-green-600">Pekerjaan</span></h2>
              <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{{ tasks.length }} Tugas Tercatat</p>
            </div>
          </div>
          <button @click="exportCSV" class="px-5 py-2 bg-slate-900 text-white rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-green-600 transition-all flex items-center gap-2">
            <i class="fas fa-file-export"></i> Ekspor CSV
          </button>
        </div>

        <div class="p-8">
          <div v-if="loading" class="py-20 text-center">
             <div class="animate-spin h-10 w-10 border-4 border-green-600 border-t-transparent rounded-full mx-auto mb-4"></div>
             <p class="text-gray-400 font-black text-[10px] uppercase tracking-widest">Menyelaraskan Data...</p>
          </div>

          <div v-else-if="tasks.length === 0" class="py-24 text-center">
            <div class="w-20 h-20 bg-gray-50 text-gray-200 rounded-[2rem] flex items-center justify-center text-4xl mx-auto mb-6">
              <i class="fas fa-clipboard-check"></i>
            </div>
            <h3 class="text-lg font-black text-gray-400 uppercase tracking-widest mb-2 italic">Belum Ada Tugas</h3>
            <p class="text-xs text-gray-300 font-medium max-w-xs mx-auto">Silakan berikan tugas baru atau sesuaikan filter untuk melihat histori pekerjaan.</p>
            <button @click="openAssignModal" class="mt-8 px-8 py-3 bg-green-600 text-white rounded-2xl font-black uppercase text-[10px] tracking-widest shadow-lg shadow-green-100">Berikan Tugas Pertama</button>
          </div>

          <div v-else class="space-y-4">
            <div v-for="task in tasks" :key="task.id" class="p-6 bg-gray-50 rounded-[2.5rem] border border-transparent hover:border-green-100 hover:bg-green-50/20 transition-all group/task">
              <div class="flex flex-col md:flex-row gap-6">
                <!-- Status & Date Column -->
                <div class="w-full md:w-48 space-y-3 shrink-0">
                  <div :class="task.status === 'completed' ? 'bg-green-600 text-white shadow-lg shadow-green-100' : 'bg-amber-50 text-amber-600 border border-amber-100'" class="px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest text-center">
                    {{ task.status }}
                  </div>
                  <div class="space-y-1">
                    <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest">Dibuat Pada</p>
                    <p class="text-[11px] font-bold text-gray-700">{{ formatDate(task.created_at) }}</p>
                  </div>
                  <div class="space-y-1">
                    <p class="text-[8px] font-black text-red-400 uppercase tracking-widest">Tenggat Waktu</p>
                    <p class="text-[11px] font-bold text-red-600">{{ formatDate(task.deadline) }}</p>
                  </div>
                </div>

                <!-- Content Column -->
                <div class="flex-1 space-y-4">
                  <div>
                    <h4 class="text-lg font-black text-gray-900 tracking-tight leading-none group-hover/task:text-green-600 transition-colors uppercase italic">{{ task.task }}</h4>
                    <p class="text-xs text-gray-500 font-medium mt-2 leading-relaxed">{{ task.description || 'Tidak ada deskripsi detail.' }}</p>
                  </div>
                  
                  <div v-if="task.result_note || task.result_attachment" class="p-5 bg-white rounded-3xl border border-gray-100 shadow-sm space-y-4">
                    <p class="text-[9px] font-black text-green-600 uppercase tracking-widest border-l-4 border-green-600 pl-3">Hasil Laporan Petugas</p>
                    <div class="flex flex-col sm:flex-row gap-5">
                      <div v-if="task.result_attachment" class="w-full sm:w-32 h-32 bg-gray-50 rounded-2xl overflow-hidden cursor-pointer group/img" @click="viewAttachment(task.result_attachment)">
                        <img :src="task.result_attachment" class="w-full h-full object-cover group-hover/img:scale-110 transition-transform duration-500" />
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover/img:opacity-100 flex items-center justify-center text-white text-xs transition-opacity">
                          <i class="fas fa-expand"></i>
                        </div>
                      </div>
                      <div class="flex-1">
                        <p class="text-xs text-gray-600 italic font-medium">"{{ task.result_note || 'Selesai sesuai instruksi.' }}"</p>
                        <p class="text-[10px] text-gray-400 mt-4">Selesai pada: <span class="font-bold text-gray-900">{{ formatDate(task.updated_at) }}</span></p>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Action Column -->
                <div class="flex md:flex-col gap-2 justify-center shrink-0 opacity-0 group-hover/task:opacity-100 transition-opacity">
                  <button @click="openEditTask(task)" class="w-10 h-10 bg-green-50 text-green-600 rounded-2xl hover:bg-green-600 hover:text-white transition-all flex items-center justify-center shadow-sm">
                    <i class="fas fa-pen text-xs"></i>
                  </button>
                  <button @click="handleDeleteTask(task.id)" class="w-10 h-10 bg-red-50 text-red-500 rounded-2xl hover:bg-red-600 hover:text-white transition-all flex items-center justify-center shadow-sm">
                    <i class="fas fa-trash text-xs"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ===== MODALS (REUSED FROM ADMIN) ===== -->
    <!-- Modal: Assign Task -->
    <div v-if="showAssignModal" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm flex items-center justify-center p-4 z-[100]">
        <div class="bg-white rounded-[3rem] shadow-2xl w-full max-w-md max-h-[90vh] flex flex-col overflow-hidden animate-in zoom-in duration-300">
          <div class="p-8 bg-gradient-to-br from-slate-900 to-slate-800 text-white flex justify-between items-center shrink-0">
            <div>
              <h3 class="text-xl font-black uppercase tracking-tighter italic">{{ taskForm.id ? 'Edit' : 'Beri' }} <span class="text-green-400">Tugas</span></h3>
              <p class="text-[9px] font-bold text-white/60 uppercase tracking-widest mt-1">Target: {{ worker?.name }}</p>
            </div>
            <button @click="showAssignModal = false" class="w-10 h-10 bg-white/10 hover:bg-white/20 rounded-xl flex items-center justify-center transition-colors">✕</button>
          </div>
          <form @submit.prevent="submitTask" class="p-8 space-y-5 overflow-y-auto custom-scrollbar">
            <div class="space-y-2">
              <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest px-1">Judul Tugas</label>
              <input v-model="taskForm.task" placeholder="Contoh: Distribusi Sembako" class="w-full p-4 bg-gray-50 rounded-2xl outline-none focus:ring-2 focus:ring-green-500 font-bold text-sm border-none" required />
            </div>
            <div class="space-y-2">
              <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest px-1">Deskripsi Detail</label>
              <textarea v-model="taskForm.description" rows="3" placeholder="Jelaskan detail tugas di sini..." class="w-full p-4 bg-gray-50 rounded-2xl outline-none focus:ring-2 focus:ring-green-500 font-bold text-sm resize-none border-none"></textarea>
            </div>
            <div class="space-y-2">
              <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest px-1">Tenggat Waktu</label>
              <input v-model="taskForm.deadline" type="datetime-local" class="w-full p-4 bg-gray-50 rounded-2xl outline-none focus:ring-2 focus:ring-green-400 font-bold text-sm border-none" required />
            </div>
            <button type="submit" :disabled="submitting" class="w-full py-5 bg-green-600 text-white rounded-[2rem] font-black uppercase tracking-widest hover:bg-green-700 shadow-xl shadow-green-100 active:scale-95 transition-all mt-4">
              <i v-if="submitting" class="fas fa-circle-notch animate-spin mr-2"></i>
              {{ submitting ? 'Memproses...' : (taskForm.id ? 'Perbarui Tugas' : 'Kirim Tugas Sekarang') }}
            </button>
          </form>
        </div>
    </div>

    <!-- Modal: View Attachment -->
    <div v-if="showAttachmentModal" class="fixed inset-0 bg-slate-900/95 backdrop-blur-xl flex items-center justify-center p-6 z-[110]">
      <div class="relative max-w-4xl w-full">
        <button @click="showAttachmentModal = false" class="absolute -top-12 right-0 text-white text-3xl hover:text-green-400 transition-colors">✕</button>
        <img :src="attachmentUrl" class="w-full h-auto rounded-[3rem] shadow-2xl border border-white/10" />
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import api from '@/api';

const route = useRoute();
const router = useRouter();
const workerId = route.params.id;

// State
const loading = ref(true);
const worker = ref(null);
const stats = ref({ total: 0, completed: 0, pending: 0, percentage: 0 });
const tasks = ref([]);
const filters = reactive({ day: '', month: '', year: '' });
const availableYears = ref([new Date().getFullYear(), new Date().getFullYear() - 1]);
const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

// Modals
const showAssignModal = ref(false);
const showAttachmentModal = ref(false);
const attachmentUrl = ref('');
const submitting = ref(false);
const taskForm = reactive({ id: null, task: '', description: '', deadline: '', user_id: workerId });

const hasFilters = computed(() => filters.day || filters.month || filters.year);

const fetchDetail = async () => {
  loading.value = true;
  try {
    const params = {};
    if (filters.day) params.day = filters.day;
    if (filters.month) params.month = filters.month;
    if (filters.year) params.year = filters.year;

    const { data } = await api.get(`/admin/worker-performance/${workerId}`, { params });
    worker.value = data.worker;
    stats.value = data.stats;
    tasks.value = data.tasks;
  } catch (e) {
    console.error('Fetch Worker Detail Error:', e);
    alert('Gagal memuat data pegawai.');
    router.push('/dashboard/admin');
  } finally {
    loading.value = false;
  }
};

const resetFilters = () => {
  filters.day = '';
  filters.month = '';
  filters.year = '';
};

const formatDate = (dateStr) => {
  if (!dateStr) return '-';
  const d = new Date(dateStr);
  return d.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit' });
};

const openAssignModal = () => {
  taskForm.id = null;
  taskForm.task = '';
  taskForm.description = '';
  taskForm.deadline = '';
  showAssignModal.value = true;
};

const openEditTask = (task) => {
  taskForm.id = task.id;
  taskForm.task = task.task;
  taskForm.description = task.description;
  if (task.deadline) {
    const d = new Date(task.deadline);
    taskForm.deadline = d.toISOString().slice(0, 16);
  }
  showAssignModal.value = true;
};

const submitTask = async () => {
  submitting.value = true;
  try {
    if (taskForm.id) {
      await api.put(`/admin/tasks/${taskForm.id}`, taskForm);
    } else {
      await api.post('/admin/tasks', taskForm);
    }
    showAssignModal.value = false;
    fetchDetail();
  } catch (e) {
    alert('Gagal menyimpan tugas.');
  } finally {
    submitting.value = false;
  }
};

const handleDeleteTask = async (id) => {
  if (confirm('Yakin ingin menghapus tugas ini?')) {
    try {
      await api.delete(`/admin/tasks/${id}`);
      fetchDetail();
    } catch (e) {
      alert('Gagal menghapus tugas.');
    }
  }
};

const viewAttachment = (url) => {
  attachmentUrl.value = url;
  showAttachmentModal.value = true;
};

const exportCSV = () => {
  const headers = ['Judul Tugas', 'Tenggat', 'Status', 'Catatan', 'Update Terakhir'];
  const rows = tasks.value.map(t => [
    t.task,
    formatDate(t.deadline),
    t.status,
    t.result_note || '-',
    formatDate(t.updated_at)
  ]);
  const csvContent = [headers, ...rows].map(r => r.map(c => `"${String(c).replace(/"/g, '""')}"`).join(',')).join('\n');
  const blob = new Blob(['\uFEFF' + csvContent], { type: 'text/csv;charset=utf-8;' });
  const url = URL.createObjectURL(blob);
  const link = document.createElement('a');
  link.href = url;
  link.download = `Laporan_Tugas_${worker.value?.name}_${new Date().toLocaleDateString()}.csv`;
  link.click();
  URL.revokeObjectURL(url);
};

onMounted(fetchDetail);
watch(filters, fetchDetail);
</script>

<style scoped>
.animate-in {
  animation-fill-mode: both;
}
.custom-scrollbar::-webkit-scrollbar {
  width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
  background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background: #e2e8f0;
  border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
  background: #cbd5e1;
}
</style>
