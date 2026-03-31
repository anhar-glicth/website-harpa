<template>
  <div class="min-h-screen bg-slate-50 pt-24 pb-20">
    <div class="max-w-7xl mx-auto px-4">
      
      <!-- Header -->
      <div class="text-center space-y-4 mb-20 animate-in fade-in slide-in-from-top-4 duration-700">
        <div class="inline-flex items-center gap-2 bg-blue-50 px-4 py-2 rounded-full border border-blue-100">
          <span class="text-[10px] font-black text-blue-600 uppercase tracking-widest italic">Rukun Islam ke-3</span>
        </div>
        <h1 class="text-4xl md:text-6xl font-black text-slate-900 tracking-tighter uppercase italic line-clamp-2">
          Sucikan Harta <br> Lewat <span class="text-blue-600">Zakat</span>
        </h1>
        <p class="text-slate-400 font-bold text-sm md:text-lg max-w-2xl mx-auto leading-relaxed">
          Zakat adalah bentuk ketaatan, kepedulian sosial, dan jembatan menuju keberkahan hidup yang hakiki.
        </p>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        <!-- Calculator Section -->
        <div class="lg:col-span-2 space-y-8">
          <div class="bg-white rounded-[2.5rem] p-8 md:p-12 shadow-xl shadow-slate-200/50 border border-slate-100 relative overflow-hidden group">
             <div class="absolute -top-10 -right-10 w-40 h-40 bg-blue-50 rounded-full blur-3xl opacity-50 group-hover:opacity-100 transition duration-1000"></div>
             
             <div class="relative z-10 space-y-10">
                <div class="space-y-4">
                  <h3 class="text-2xl font-black text-slate-900 uppercase tracking-tighter italic">Kalkulator <span class="text-blue-600">Zakat</span></h3>
                  <p class="text-slate-400 text-sm font-bold opacity-80">Pilih jenis zakat dan masukkan jumlahnya untuk menghitung kewajiban Anda.</p>
                </div>

                <!-- Tabs -->
                <div class="flex flex-wrap gap-2 md:gap-3">
                  <button 
                    v-for="tab in zakatTypes" 
                    :key="tab.id"
                    @click="activeCalcTab = tab.id"
                    class="px-5 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-sm border"
                    :class="activeCalcTab === tab.id ? 'bg-blue-600 text-white border-blue-600' : 'bg-slate-50 text-slate-400 border-slate-100 hover:border-blue-200'"
                  >
                    {{ tab.label }}
                  </button>
                </div>

                <!-- Inputs -->
                <div class="space-y-6">
                  <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ activeCalcTab === 'fitrah' ? 'Jumlah Jiwa' : 'Total Pendapatan/Harta (Rp)' }}</label>
                    <div class="relative">
                       <span v-if="activeCalcTab !== 'fitrah'" class="absolute left-6 top-1/2 -translate-y-1/2 text-slate-400 font-black text-xs">Rp</span>
                       <input 
                        v-model="calcValue"
                        type="number" 
                        class="w-full p-6 bg-slate-50 rounded-2xl border-2 border-transparent focus:border-blue-600 outline-none font-black text-lg transition-all"
                        :class="activeCalcTab !== 'fitrah' ? 'pl-14' : 'pl-6'"
                        :placeholder="activeCalcTab === 'fitrah' ? 'Contoh: 4' : '0'"
                       />
                    </div>
                  </div>

                  <div v-if="activeCalcTab !== 'fitrah'" class="p-6 bg-blue-50/50 rounded-2xl border border-blue-100 flex items-center justify-between">
                     <div>
                        <p class="text-[9px] font-black text-blue-600 uppercase tracking-widest mb-1">Nisab Saat Ini (Per-Tahun)</p>
                        <p class="text-xs font-black text-slate-900">Rp 81.945.667 <span class="text-[8px] text-slate-400 font-medium normal-case">(Setara 85g Emas)</span></p>
                     </div>
                     <i class="fas fa-info-circle text-blue-200 text-xl"></i>
                  </div>

                  <!-- Result -->
                  <div class="bg-gradient-to-r from-blue-600 to-blue-500 rounded-[2rem] p-8 md:p-12 text-white shadow-xl shadow-blue-100">
                    <div class="flex flex-col md:flex-row md:justify-between md:items-end gap-6 text-center md:text-left">
                       <div class="space-y-1">
                          <p class="text-[11px] font-black uppercase tracking-widest opacity-80">Estimasi Zakat Anda</p>
                          <h2 class="text-4xl md:text-5xl font-black tracking-tighter italic">Rp {{ calculatedAmount.toLocaleString() }}</h2>
                       </div>
                       <button 
                        @click="handleStartZakat" 
                        class="px-10 py-5 bg-white text-blue-600 rounded-[1.5rem] font-black uppercase tracking-widest text-xs hover:bg-slate-50 transition active:scale-95 shadow-lg shadow-black/10"
                       >
                        Bersihkan Sekarang
                       </button>
                    </div>
                  </div>
                </div>
             </div>
          </div>

          <!-- Why Zakat Info -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
             <div class="p-8 bg-blue-600 rounded-[2rem] text-white space-y-4">
                <i class="fas fa-seedling text-3xl opacity-50"></i>
                <h4 class="text-xl font-black uppercase tracking-tighter">Keberkahan</h4>
                <p class="text-xs font-medium opacity-80 leading-relaxed italic">"Harta tidak akan berkurang karena sedekah." Sebaliknya, Allah akan melipatgandakan bagi siapa yang Ia kehendaki.</p>
             </div>
             <div class="p-8 bg-slate-900 rounded-[2rem] text-white space-y-4">
                <i class="fas fa-users text-3xl opacity-50"></i>
                <h4 class="text-xl font-black uppercase tracking-tighter">Kepedulian</h4>
                <p class="text-xs font-medium opacity-80 leading-relaxed italic">Zakat memastikan roda ekonomi berputar dan mereka yang kekurangan mendapatkan haknya untuk hidup layak.</p>
             </div>
          </div>
        </div>

        <!-- Right Side: Recent activity / Instruction -->
        <div class="space-y-8">
           <!-- Instruction Card -->
           <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 space-y-8 shadow-sm">
              <h4 class="text-sm font-black text-slate-900 uppercase tracking-widest italic flex items-center gap-3">
                 <span class="w-2 h-8 bg-blue-600 rounded-full"></span>
                 Cara Berzakat
              </h4>

              <div class="space-y-6">
                 <div v-for="(step, i) in steps" :key="i" class="flex gap-4 group">
                    <div class="text-lg font-black text-blue-600 group-hover:scale-125 transition">0{{ i+1 }}</div>
                    <div class="space-y-1">
                       <p class="text-xs font-black text-slate-900 uppercase tracking-tight">{{ step.title }}</p>
                       <p class="text-[11px] text-slate-400 font-medium leading-relaxed">{{ step.desc }}</p>
                    </div>
                 </div>
              </div>
           </div>

           <!-- My Recent Zakat (If logged in) -->
           <div v-if="isAuthenticated && myZakats.length > 0" class="bg-white rounded-[2.5rem] border border-slate-100 overflow-hidden shadow-sm">
              <div class="p-8 bg-slate-50 border-b border-slate-100 flex justify-between items-center">
                 <h4 class="text-[10px] font-black text-slate-900 uppercase tracking-widest">Riwayat Zakat</h4>
                 <span class="text-[9px] text-blue-600 font-bold italic">{{ myZakats.length }} Transaksi</span>
              </div>
              <div class="divide-y divide-slate-50">
                 <div v-for="z in myZakats" :key="z.id" class="p-6 hover:bg-slate-50/50 transition">
                    <div class="flex justify-between items-start mb-2">
                       <span class="text-[10px] font-black text-slate-900 uppercase tracking-tight">{{ z.zakat_type }}</span>
                       <span :class="{
                          'text-blue-600': z.payment_status === 'Success',
                          'text-amber-500': z.payment_status === 'Pending',
                          'text-red-500': z.payment_status === 'Failed'
                       }" class="text-[8px] font-black uppercase tracking-widest">{{ z.payment_status }}</span>
                    </div>
                    <p class="text-sm font-black text-slate-900">Rp {{ Number(z.amount).toLocaleString() }}</p>
                    <p class="text-[9px] text-slate-400 mt-1 italic">{{ formatDate(z.created_at) }}</p>
                 </div>
              </div>
           </div>
        </div>
      </div>
    </div>

    <!-- Payment Modal (Hidden by default) -->
    <div v-if="showPaymentModal" class="fixed inset-0 bg-slate-900/80 backdrop-blur-md flex items-center justify-center p-4 z-[100]">
       <div class="bg-white rounded-[3rem] shadow-2xl w-full max-w-md overflow-hidden animate-in zoom-in duration-300">
          <div class="p-8 bg-blue-600 text-white flex justify-between items-center relative overflow-hidden">
             <div class="absolute -top-10 -right-10 w-24 h-24 bg-white/10 rounded-full blur-xl"></div>
             <div class="relative z-10">
                <h3 class="text-xl font-black uppercase tracking-tighter italic">Formulir <span class="text-blue-200">Zakat</span></h3>
                <p class="text-[9px] font-bold text-white/70 uppercase tracking-widest">Sucikan Harta Anda</p>
             </div>
             <button @click="showPaymentModal = false" class="text-white/60 hover:text-white transition">✕</button>
          </div>

          <form @submit.prevent="submitZakat" class="p-8 space-y-6">
             <div class="space-y-4">
                <div class="p-5 bg-blue-50 rounded-2xl border border-blue-100 flex justify-between items-center">
                   <div>
                      <p class="text-[8px] font-black text-blue-600 uppercase tracking-widest">Total Bayar</p>
                      <p class="text-xl font-black text-slate-900 italic tracking-tighter">Rp {{ calculatedAmount.toLocaleString() }}</p>
                   </div>
                   <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest border-l border-slate-200 pl-4">{{ activeCalcTab }}</span>
                </div>

                <div class="space-y-2">
                   <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Metode Penyaluran</label>
                   <select v-model="paymentForm.method" class="w-full p-4 bg-slate-50 rounded-2xl outline-none focus:ring-2 focus:ring-blue-600 font-bold text-xs uppercase tracking-widest">
                      <option value="Transfer Bank">Transfer Bank / Virtual Account</option>
                      <option value="E-Wallet">E-Wallet (OVO/Gopay/QRIS)</option>
                      <option value="Tunai">Jemput Zakat (Lokal Area)</option>
                   </select>
                </div>
             </div>

             <div class="bg-amber-50 p-6 rounded-2xl border border-amber-100">
                <p class="text-[10px] font-bold text-amber-900 leading-relaxed text-center">Niati dengan ikhlas: "Saya berniat menunaikan zakat saya karena Allah Ta'ala."</p>
             </div>

             <button :disabled="submitting" class="w-full py-5 bg-blue-600 text-white rounded-[2rem] font-black uppercase tracking-widest shadow-xl shadow-blue-100 hover:bg-blue-700 transition active:scale-95 disabled:opacity-50">
                {{ submitting ? 'Memproses...' : 'Tunaikan Sekarang' }}
             </button>
          </form>
       </div>
    </div>

  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue';
import { useAuth } from '@/store/auth';
import api from '@/api';

const { isAuthenticated, openLogin } = useAuth();

const zakatTypes = [
  { id: 'maal', label: 'Zakat Maal' },
  { id: 'profesi', label: 'Zakat Profesi' },
  { id: 'fitrah', label: 'Zakat Fitrah' },
  { id: 'perdagangan', label: 'Emas/Dagangan' }
];

const activeCalcTab = ref('maal');
const calcValue = ref('');
const showPaymentModal = ref(false);
const submitting = ref(false);
const myZakats = ref([]);

const paymentForm = ref({
   method: 'Transfer Bank'
});

const steps = [
  { title: 'Hitung Zakat', desc: 'Gunakan kalkulator untuk mengetahui jumlah kewajiban berdasarkan nisab saat ini.' },
  { title: 'Niat & Pilih', desc: 'Pilih jenis zakat yang sesuai dan baca niat penunaian zakat dengan tulus.' },
  { title: 'Penyaluran', desc: 'Klik tunaikan, pilih metode pembayaran, dan lakukan penyaluran dana.' },
  { title: 'Verifikasi', desc: 'Admin akan memeriksa dan menyalurkan zakat Anda kepada mustahik yang berhak.' }
];

const calculatedAmount = computed(() => {
  const val = Number(calcValue.value) || 0;
  if (activeCalcTab.value === 'fitrah') {
    return val * 45000; // Mock calculation for Fitrah (approx. 2.5kg rice value)
  }
  return Math.round(val * 0.025); // General 2.5% for Maal/Profesi
});

const handleStartZakat = () => {
  if (!isAuthenticated.value) {
    openLogin();
    return;
  }
  if (calculatedAmount.value <= 0) {
    alert('Silakan masukkan jumlah aset terlebih dahulu.');
    return;
  }
  showPaymentModal.value = true;
};

const submitZakat = async () => {
   submitting.value = true;
   try {
      await api.post('/zakats', {
         zakat_type: activeCalcTab.value,
         amount: calculatedAmount.value,
         payment_method: paymentForm.value.method
      });
      alert('Alhamdulillah, zakat Anda telah diajukan. Silakan lakukan pembayaran sesuai instruksi.');
      showPaymentModal.value = false;
      calcValue.value = '';
      fetchMyHistory();
   } catch (e) {
      alert('Gagal memproses zakat.');
   } finally {
      submitting.value = false;
   }
};

const fetchMyHistory = async () => {
   if (isAuthenticated.value) {
      try {
         const res = await api.get('/zakats/my');
         myZakats.value = res.data;
      } catch (e) {
         console.error(e);
      }
   }
};

const formatDate = (str) => {
   return new Date(str).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
};

onMounted(fetchMyHistory);
watch(activeCalcTab, () => calcValue.value = '');
</script>

<style scoped>
/* No scrollbar */
.no-scrollbar::-webkit-scrollbar { display: none; }
.no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
