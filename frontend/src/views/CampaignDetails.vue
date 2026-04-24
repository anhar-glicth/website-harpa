<template>
  <div class="min-h-screen bg-transparent pb-20 text-white">
    <!-- Loading State -->
    <div v-if="loading" class="flex flex-col items-center justify-center h-[60vh]">
      <div class="w-12 h-12 border-4 border-green-600 border-t-transparent rounded-full animate-spin mb-4"></div>
      <p class="text-white/40 font-bold uppercase tracking-widest text-xs">Memuat Detail Kampanye...</p>
    </div>

    <!-- Error State -->
    <div v-else-if="error" class="max-w-xl mx-auto mt-20 p-10 bg-white/5 backdrop-blur-xl rounded-[2.5rem] border border-white/10 shadow-2xl text-center">
      <div class="w-20 h-20 bg-red-600/20 text-red-500 rounded-full flex items-center justify-center mx-auto mb-6">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
      </div>
      <h2 class="text-2xl font-black text-white mb-2">Ops! Terjadi Kesalahan</h2>
      <p class="text-white/50 mb-8">{{ error }}</p>
      <router-link to="/" class="bg-green-600 text-white px-8 py-3 rounded-2xl font-bold">Kembali ke Beranda</router-link>
    </div>

    <div v-else>
      <!-- Hero Header -->
      <div class="h-[40vh] md:h-[50vh] relative overflow-hidden border-b border-white/5">
        <img :src="campaign.foto || 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=2070&auto=format&fit=crop'" class="w-full h-full object-cover" />
        <div class="absolute inset-0 bg-gradient-to-t from-[#020205] via-transparent to-transparent"></div>
        <div class="absolute bottom-0 left-0 w-full p-6 md:p-12 text-white">
          <div class="max-w-7xl mx-auto">
            <span class="bg-green-600 text-white text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-widest mb-4 inline-block shadow-lg">{{ campaign.category }}</span>
            <h1 class="text-3xl md:text-5xl font-black leading-tight max-w-4xl italic tracking-tighter">{{ campaign.title }}</h1>
          </div>
        </div>
      </div>

      <div class="max-w-7xl mx-auto px-4 -mt-10 relative z-10 grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-8">
          <div class="bg-white/5 backdrop-blur-xl p-8 md:p-10 rounded-[2.5rem] shadow-2xl border border-white/10">
            <div class="flex items-center gap-4 mb-8">
              <div class="p-4 bg-green-600/20 text-green-500 rounded-2xl border border-green-600/20">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <h2 class="text-xl font-black text-white uppercase tracking-tight italic">Kisah Kampanye</h2>
            </div>
            <p class="text-white/60 leading-relaxed text-lg whitespace-pre-wrap font-medium">{{ campaign.description }}</p>
          </div>

          <!-- Campaign Stats Cards -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white/5 backdrop-blur-xl p-8 rounded-[2rem] shadow-xl border border-white/10">
              <p class="text-[10px] font-black text-white/30 uppercase tracking-widest mb-1">Target Dana</p>
              <h3 class="text-3xl font-black text-white italic tracking-tighter">Rp {{ formatCurrency(campaign.target_amount) }}</h3>
            </div>
            <div class="bg-white/5 backdrop-blur-xl p-8 rounded-[2rem] shadow-xl border border-white/10">
              <p class="text-[10px] font-black text-white/30 uppercase tracking-widest mb-1">Terkumpul</p>
              <h3 class="text-3xl font-black text-green-500 italic tracking-tighter">Rp {{ formatCurrency(campaign.current_amount) }}</h3>
            </div>
          </div>
        </div>

        <!-- Sidebar Donasi -->
        <div class="lg:col-span-1">
          <div class="sticky top-24 space-y-6">
            <div class="bg-white/5 backdrop-blur-xl p-8 rounded-[2.5rem] shadow-2xl border border-white/10">
              <h3 class="text-xl font-black text-white mb-6 uppercase italic tracking-tighter">Kirim Donasi</h3>
              
              <div v-if="successMsg" class="bg-green-600/20 text-green-400 p-6 rounded-2xl mb-6 font-bold text-sm border border-green-600/20 flex items-start gap-3 italic">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                {{ successMsg }}
              </div>

              <form v-else @submit.prevent="handleSubmitDonation" class="space-y-6">
                <!-- Nominal -->
                <div class="space-y-2">
                  <label class="text-[10px] font-black text-white/30 uppercase tracking-widest ml-1">Nominal Donasi (Rp)</label>
                  <input 
                    v-model="donationForm.amount" 
                    type="number" 
                    class="w-full px-6 py-4 bg-white/5 border border-white/5 rounded-2xl outline-none focus:ring-4 focus:ring-green-500/10 focus:border-green-600 transition-all font-black text-xl text-white"
                    placeholder="0"
                    required
                  />
                </div>

                <!-- Nama Donatur -->
                <div class="space-y-2">
                  <label class="text-[10px] font-black text-white/30 uppercase tracking-widest ml-1">Nama Donatur</label>
                  <input 
                    v-model="donationForm.donor_name" 
                    type="text" 
                    class="w-full px-6 py-4 bg-white/5 border border-white/5 rounded-2xl outline-none focus:ring-4 focus:ring-green-500/10 focus:border-green-600 transition-all font-bold text-white"
                    placeholder="Nama Lengkap / Anonim"
                    required
                  />
                </div>

                <!-- Pesan Keunikan -->
                <div class="space-y-2">
                  <label class="text-[10px] font-black text-white/30 uppercase tracking-widest ml-1">Pesan (Opsional)</label>
                  <textarea 
                    v-model="donationForm.message" 
                    class="w-full px-6 py-4 bg-white/5 border border-white/5 rounded-2xl outline-none focus:ring-4 focus:ring-green-500/10 focus:border-green-600 transition-all font-medium h-24 resize-none text-white"
                    placeholder="Doa atau pesan Anda..."
                  ></textarea>
                </div>

                <!-- Bukti Transfer -->
                <div class="space-y-2">
                  <label class="text-[10px] font-black text-white/30 uppercase tracking-widest ml-1">Bukti Transfer (Harus Diisi)</label>
                  <div 
                    class="relative w-full h-40 bg-white/5 border-2 border-dashed border-white/10 rounded-2xl flex flex-col items-center justify-center overflow-hidden group cursor-pointer hover:bg-white/10 transition-all"
                    @click="$refs.fileInput.click()"
                  >
                    <img v-if="donationForm.proof_image" :src="donationForm.proof_image" class="w-full h-full object-cover" />
                    <div v-else class="text-center p-4">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white/20 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                      </svg>
                      <p class="text-[10px] font-bold text-white/30 uppercase tracking-wider">Klik untuk unggah Bukti</p>
                    </div>
                  </div>
                  <input ref="fileInput" type="file" class="hidden" accept="image/*" @change="handleFileChange" />
                </div>

                <div class="pt-4 text-center">
                  <p class="text-[10px] text-white/40 leading-relaxed mb-6 italic">
                    Transfer ke: <b class="text-green-400">{{ campaign.bank_name }} - {{ campaign.account_number }}</b><br>
                    A/N: <b class="text-white">{{ campaign.account_holder }}</b>
                  </p>
                  <button 
                    type="submit" 
                    :disabled="submitting"
                    class="w-full py-5 bg-green-600 text-white font-black rounded-2xl hover:bg-green-700 active:scale-95 transition-all shadow-xl shadow-green-500/20 disabled:opacity-50 flex items-center justify-center gap-3 uppercase tracking-widest text-xs"
                  >
                    <span v-if="!submitting">Konfirmasi Donasi</span>
                    <div v-else class="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                  </button>
                </div>
              </form>
            </div>
            
            <!-- Keamanan & Transparansi Card -->
            <div class="bg-green-600/10 border border-green-500/20 p-6 rounded-[2rem] text-white backdrop-blur-md">
              <div class="flex gap-4 items-start">
                <div class="bg-green-600 text-white p-2 rounded-xl flex-shrink-0 shadow-lg shadow-green-500/20">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M2.166 15.849A10 10 0 0110 2c5.523 0 10 4.477 10 10s-4.477 10-10 10a9.963 9.963 0 01-7.834-3.151zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                  </svg>
                </div>
                <div>
                  <h4 class="text-sm font-black uppercase tracking-tight mb-2 italic text-green-400">Transparansi 100%</h4>
                  <p class="text-xs text-white/40 leading-relaxed font-medium">Dana Anda akan diverifikasi oleh Admin HumanityCare sebelum diserahkan ke penanggung jawab lapangan.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import api from '@/api';

const route = useRoute();
const campaign = ref({});
const loading = ref(true);
const error = ref(null);
const submitting = ref(false);
const successMsg = ref('');

const donationForm = reactive({
  amount: '',
  donor_name: '',
  message: '',
  proof_image: ''
});

const formatCurrency = (val) => new Intl.NumberFormat('id-ID').format(val || 0);

const fetchData = async () => {
  loading.value = true;
  error.value = null;
  try {
    const id = route.params.id;
    const res = await api.get(`/campaigns/${id}`);
    campaign.value = res.data;
  } catch (err) {
    error.value = err.response?.data?.message || 'Gagal mengambil data kampanye.';
  } finally {
    loading.value = false;
  }
};

const handleFileChange = (e) => {
  const file = e.target.files[0];
  if (!file) return;
  
  if (file.size > 2 * 1024 * 1024) {
    alert('Ukuran file bukti transfer maksimal 2MB!');
    return;
  }

  const reader = new FileReader();
  reader.onload = (event) => {
    donationForm.proof_image = event.target.result;
  };
  reader.readAsDataURL(file);
};

const handleSubmitDonation = async () => {
  if (!donationForm.proof_image) {
    alert('Harap unggah bukti transfer!');
    return;
  }

  submitting.value = true;
  try {
    const payload = {
      campaign_id: campaign.value.id,
      ...donationForm
    };
    await api.post('/donations', payload);
    successMsg.value = 'Terima kasih! Donasi Anda telah dikirim dan sedang menunggu verifikasi Admin.';
  } catch (err) {
    alert(err.response?.data?.message || 'Gagal mengirim donasi.');
  } finally {
    submitting.value = false;
  }
};

onMounted(fetchData);
</script>

<style scoped>
.shadow-2xl {
  box-shadow: 0 25px 50px -12px rgba(16, 185, 129, 0.15);
}
</style>
