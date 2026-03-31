<template>
  <div class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
    <!-- ==========================================
         BAGIAN HEADER
         ========================================== -->
    <header class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm">
      <div class="space-y-2">
        <div class="flex items-center gap-3">
          <h1 class="text-3xl font-black text-gray-900 tracking-tight">Panel Petugas Lapangan</h1>
          <span class="bg-blue-600 text-white text-[10px] font-black uppercase px-3 py-1 rounded-full shadow-lg shadow-blue-100">Staff</span>
        </div>
        <p class="text-gray-500 text-sm max-w-md leading-relaxed">
          Kelola distribusi bantuan kemanusiaan, publikasikan kampanye baru, dan pantau perkembangan donasi secara real-time dari lapangan.
        </p>
      </div>
      
      <button 
        @click="openModal"
        class="inline-flex items-center justify-center gap-3 bg-blue-600 text-white px-8 py-4 rounded-2xl font-bold hover:bg-blue-700 active:scale-95 transition-all shadow-xl shadow-blue-100 group"
      >
        <div class="bg-white/20 p-1 rounded-lg group-hover:rotate-90 transition-transform duration-300">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
          </svg>
        </div>
        Buat Kampanye Baru
      </button>
    </header>

    <!-- ==========================================
         KARTU STATISTIK UTAMA
         ========================================== -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm hover:shadow-md transition-all group">
        <div class="flex items-center gap-6">
          <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-3xl flex items-center justify-center group-hover:scale-110 transition-transform duration-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
          </div>
          <div>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-1">Kampanye Aktif</p>
            <p class="text-3xl font-black text-gray-900">{{ myCampaigns.length }} <span class="text-sm font-bold text-gray-400">Program</span></p>
          </div>
        </div>
      </div>

      <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm hover:shadow-md transition-all group">
        <div class="flex items-center gap-6">
          <div class="w-16 h-16 bg-green-50 text-green-600 rounded-3xl flex items-center justify-center group-hover:scale-110 transition-transform duration-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <div>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-1">Dana Terkumpul</p>
            <p class="text-3xl font-black text-gray-900">Rp {{ formatCurrency(totalManagedFunds) }}</p>
          </div>
        </div>
      </div>

      <div class="bg-gradient-to-br from-gray-900 to-blue-900 p-8 rounded-[2.5rem] shadow-xl shadow-blue-100 text-white hidden lg:block relative overflow-hidden group">
        <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/10 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
        <div class="relative z-10">
          <p class="text-[10px] font-bold opacity-60 uppercase tracking-[0.2em] mb-3">Informasi Sistem</p>
          <p class="text-sm font-medium leading-relaxed italic opacity-90">
            "Sekarang Anda dapat mengunggah foto lapangan langsung menggunakan galeri perangkat Anda."
          </p>
        </div>
      </div>
    </div>

    <!-- ==========================================
         DAFTAR KAMPANYE (GRID)
         ========================================== -->
    <section class="space-y-6 pb-20">
      <div class="flex items-center justify-between px-4">
        <h2 class="text-2xl font-black text-gray-800 flex items-center gap-3">
          Daftar Kampanye Saya
          <span class="w-2.5 h-2.5 rounded-full bg-blue-500 animate-pulse"></span>
        </h2>
        <button @click="fetchData" class="text-xs font-bold text-blue-600 hover:bg-blue-50 px-3 py-1 rounded-lg transition-colors">Muat Ulang</button>
      </div>

      <!-- State: Loading -->
      <div v-if="loading" class="py-40 text-center">
        <div class="relative w-16 h-16 mx-auto mb-6">
          <div class="absolute inset-0 border-4 border-blue-100 rounded-full"></div>
          <div class="absolute inset-0 border-4 border-blue-600 border-t-transparent rounded-full animate-spin"></div>
        </div>
        <p class="text-gray-400 font-black text-xs uppercase tracking-[0.3em]">Menyinkronkan Data Lapangan</p>
      </div>

      <!-- State: Kosong -->
      <div v-else-if="myCampaigns.length === 0" class="bg-white p-24 rounded-[3rem] border-2 border-dashed border-gray-100 text-center space-y-6">
        <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto text-gray-200">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
          </svg>
        </div>
        <div>
          <p class="text-gray-900 font-black text-xl">Belum ada kampanye</p>
          <p class="text-gray-400 text-sm mt-2 max-w-sm mx-auto">Mulai dengan membuat kampanye pertama Anda.</p>
        </div>
        <button @click="openModal" class="px-8 py-3 bg-gray-900 text-white rounded-2xl font-bold text-sm hover:bg-black transition-all">Mulai Sekarang</button>
      </div>

      <!-- State: Grid Tampilan Kartu -->
      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <div 
          v-for="c in myCampaigns" 
          :key="c.id" 
          class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm hover:shadow-2xl transition-all duration-500 group overflow-hidden flex flex-col border-b-4 border-b-transparent hover:border-b-blue-600"
        >
          <div class="h-52 bg-gray-100 relative overflow-hidden">
            <img 
              v-if="c.foto" 
              :src="c.foto" 
              :alt="c.title" 
              class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000"
            />
            <div v-else class="w-full h-full flex items-center justify-center text-gray-200 bg-gray-50">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
            </div>
            <div class="absolute top-5 right-5">
              <span 
                v-if="c.status === 'pending'"
                class="px-4 py-1.5 bg-yellow-500 shadow-xl rounded-full text-[10px] font-black uppercase text-white tracking-widest"
              >
                Menunggu Approval
              </span>
              <span 
                v-else
                class="px-4 py-1.5 bg-blue-600 shadow-xl rounded-full text-[10px] font-black uppercase text-white tracking-widest"
              >
                Aktif
              </span>
            </div>
          </div>

          <div class="p-8 flex flex-col flex-grow">
            <h3 class="font-black text-gray-900 text-xl line-clamp-2 mb-6 min-h-[3.5rem] group-hover:text-blue-600 transition-colors leading-tight">
              {{ c.title }}
            </h3>
            
            <div class="mt-auto space-y-5">
              <div class="space-y-3">
                <div class="flex justify-between items-end px-1">
                  <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Progress Donasi</span>
                  <span class="text-xs font-black text-blue-600">{{ Math.round((c.current_amount/c.target_amount)*100) }}%</span>
                </div>
                <div class="w-full bg-gray-100 h-2.5 rounded-full overflow-hidden">
                  <div 
                    class="bg-blue-600 h-full transition-all duration-1000 ease-out" 
                    :style="{ width: (c.current_amount/c.target_amount)*100 + '%' }"
                  ></div>
                </div>
              </div>

              <div class="flex justify-between items-center pt-5 border-t border-gray-50">
                <div>
                  <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Terkumpul</p>
                  <p class="text-base font-black text-gray-900">Rp {{ formatCurrency(c.current_amount) }}</p>
                </div>
                <div class="text-right">
                  <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Target Dana</p>
                  <p class="text-sm font-bold text-gray-500">Rp {{ formatCurrency(c.target_amount) }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Tambahkan di dalam kartu, di atas progress bar atau di bawah target dana -->
<div class="flex items-center gap-2 mt-2 bg-blue-50 p-2 rounded-xl">
  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v10a2 2 0 002 2z" />
  </svg>
  <span class="text-[10px] font-black text-blue-700 uppercase">{{ c.bank_name }} : {{ c.account_number }}</span>
</div>
    </section>

    <!-- ==========================================
         MODAL FORM: BUAT KAMPANYE (DENGAN UPLOAD FOTO)
         ========================================== -->
    <transition name="modal">
      <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 md:p-10">
        <div class="absolute inset-0 bg-gray-900/80 backdrop-blur-md" @click="showModal = false"></div>
        
        <div class="relative bg-white w-full max-w-2xl rounded-[3rem] shadow-2xl overflow-hidden animate-in zoom-in duration-300">
          <div class="p-10 md:p-12">
            <header class="flex justify-between items-start mb-10">
              <div>
                <h2 class="text-3xl font-black text-gray-900">Publikasi Baru</h2>
                <p class="text-sm text-gray-400 mt-2">Detail kampanye Anda akan diverifikasi oleh Admin.</p>
              </div>
              <button @click="showModal = false" class="p-3 hover:bg-gray-100 rounded-2xl transition-colors text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </header>

            <form @submit.prevent="handleCreateCampaign" class="space-y-8">
              <!-- Judul -->
              <div class="space-y-3">
                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Nama Kampanye</label>
                <input 
                  v-model="form.title" 
                  type="text" 
                  class="w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-bold placeholder:font-medium"
                  placeholder="Contoh: Bantuan Medis Gaza"
                  required
                />
              </div>

              <!-- Input Foto (Base64) -->
              <div class="space-y-3">
                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Foto Kampanye</label>
                <div class="flex flex-col gap-4">
                  <label class="relative flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-200 rounded-2xl cursor-pointer bg-gray-50 hover:bg-gray-100 transition-all overflow-hidden group">
                    <div v-if="!form.foto" class="flex flex-col items-center justify-center pt-5 pb-6">
                      <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 mb-2 text-gray-400 group-hover:text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                      </svg>
                      <p class="text-xs text-gray-500"><span class="font-bold">Klik untuk pilih foto</span></p>
                      <p class="text-[10px] text-gray-400">Format: PNG atau JPG</p>
                    </div>
                    <img v-else :src="form.foto" class="w-full h-full object-cover" />
                    <input type="file" class="hidden" accept="foto/*" @change="handleFileChange" />
                  </label>
                  <button v-if="form.foto" @click.prevent="form.foto = ''" class="text-[10px] text-red-500 font-bold uppercase tracking-widest self-end hover:underline">Hapus Foto</button>
                </div>
              </div>

              <!-- Deskripsi -->
              <div class="space-y-3">
                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Deskripsi & Kebutuhan</label>
                <textarea 
                  v-model="form.description" 
                  rows="4"
                  class="w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-medium resize-none"
                  placeholder="Ceritakan urgensi bantuan yang dibutuhkan saat ini..."
                  required
                ></textarea>
              </div>

              <!-- Target Dana -->
              <div class="space-y-3">
                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Target Dana Dibutuhkan</label>
                <div class="relative">
                  <span class="absolute left-6 top-1/2 -translate-y-1/2 font-black text-gray-300 text-xl">Rp</span>
                  <input 
                    v-model="form.target_amount" 
                    type="number" 
                    class="w-full pl-16 pr-6 py-5 bg-gray-50 border border-gray-100 rounded-2xl outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-black text-2xl text-gray-900"
                    placeholder="0"
                    required
                  />
                </div>
              </div>
              <!-- Input Informasi Rekening (BARU) -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-t pt-6">
  <div class="space-y-3 md:col-span-2">
     <h4 class="text-xs font-black text-blue-600 uppercase tracking-widest">Pengaturan Rekening Donasi</h4>
  </div>
  <div class="space-y-3">
    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Nama Bank / E-Wallet</label>
    <input v-model="form.bank_name" type="text" class="w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl outline-none focus:border-blue-500 font-bold" placeholder="Contoh: BSI / Dana" required />
  </div>
  <div class="space-y-3">
    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Nomor Rekening</label>
    <input v-model="form.account_number" type="text" class="w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl outline-none focus:border-blue-500 font-bold" placeholder="000111222" required />
  </div>
  <div class="space-y-3 md:col-span-2">
    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Atas Nama (A/N)</label>
    <input v-model="form.account_holder" type="text" class="w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl outline-none focus:border-blue-500 font-bold" placeholder="Nama lengkap pemilik rekening" required />
  </div>
</div>

              <div class="flex gap-4 pt-6">
                <button type="button" @click="showModal = false" class="flex-1 py-5 font-bold text-gray-400 hover:text-gray-900">Batal</button>
                <button 
                  type="submit" 
                  :disabled="isSubmitting"
                  class="flex-[2] py-5 bg-blue-600 text-white font-black rounded-[1.5rem] hover:bg-blue-700 shadow-2xl transition-all disabled:opacity-50 flex items-center justify-center gap-3"
                >
                  <span v-if="!isSubmitting">Publikasikan Sekarang</span>
                  <div v-else class="flex items-center gap-3">
                    <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Menyimpan...
                  </div>
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted, computed } from 'vue';
import api from '@/api'; 

const myCampaigns = ref([]);
const loading = ref(true);
const showModal = ref(false);
const isSubmitting = ref(false);

const form = reactive({
  title: '',
  description: '',
  foto: '',
  target_amount: '',
  bank_name: '',
  account_number: '',
  account_holder: ''
});

const totalManagedFunds = computed(() => {
  return myCampaigns.value.reduce((sum, item) => sum + parseFloat(item.current_amount || 0), 0);
});

const formatCurrency = (val) => new Intl.NumberFormat('id-ID').format(val || 0);

const openModal = () => {
  form.title = '';
  form.description = '';
  form.foto = '';
  form.target_amount = '';
  // TAMBAHKAN INI:
  form.bank_name = '';
  form.account_number = '';
  form.account_holder = '';
  showModal.value = true;
};

const handleFileChange = (event) => {
  const file = event.target.files[0];
  if (!file) return;

  if (file.size > 2 * 1024 * 1024) {
    alert("Ukuran file terlalu besar! Maksimal 2MB.");
    return;
  }

  const reader = new FileReader();
  reader.onload = (e) => {
    form.foto = e.target.result; 
  };
  reader.readAsDataURL(file);
};

const fetchData = async () => {
  loading.value = true;
  try {
    const res = await api.get('/campaigns/my-campaigns');
    myCampaigns.value = res.data;
  } catch (error) {
    console.error("Gagal memuat data kampanye:", error);
    // Jika gagal fetch di awal, beri info detil
    const errorMsg = error.response?.data?.message || "Gagal menghubungi server.";
    console.error(`Status: ${error.response?.status} - ${errorMsg}`);
  } finally {
    loading.value = false;
  }
};

const handleCreateCampaign = async () => {
  if (isSubmitting.value) return;
  
  isSubmitting.value = true;
  try {
    await api.post('/campaigns', form);
    showModal.value = false;
    await fetchData(); 
  } catch (error) {
    console.error("Detail Error:", error);
    
    // PENANGANAN ERROR LEBIH SPESIFIK
    if (error.response?.status === 413) {
      alert("Gagal: Ukuran foto terlalu besar. Coba gunakan foto yang lebih kecil atau periksa limit di server.js");
    } else if (error.response?.status === 401 || error.response?.status === 403) {
      alert("Gagal: Sesi login berakhir atau Anda tidak memiliki izin. Silakan login ulang.");
    } else if (!error.response) {
      alert("Gagal: Tidak dapat terhubung ke server. Pastikan backend (Node.js) sudah menyala di http://localhost:5000");
    } else {
      alert(error.response?.data?.message || "Terjadi kesalahan saat membuat kampanye.");
    }
  } finally {
    isSubmitting.value = false;
  }
};

onMounted(() => {
  fetchData();
});
</script>

<style scoped>
.modal-enter-active, .modal-leave-active { transition: opacity 0.4s ease; }
.modal-enter-from, .modal-leave-to { opacity: 0; }
.animate-in { animation-fill-mode: both; }
input::-webkit-outer-spin-button, input::-webkit-inner-spin-button {
  -webkit-appearance: none; margin: 0;
}
</style>