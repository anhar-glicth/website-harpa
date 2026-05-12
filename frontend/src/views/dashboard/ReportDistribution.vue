<template>
  <div class="max-w-4xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-8 duration-700 pb-20">
    <!-- Header Page -->
    <header class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm relative overflow-hidden">
      <div class="space-y-2 relative z-10">
        <button @click="router.push('/dashboard/field')" class="group flex items-center gap-2 text-gray-400 hover:text-green-600 transition-colors mb-4">
          <div class="w-8 h-8 rounded-xl bg-gray-50 flex items-center justify-center group-hover:bg-green-50 transition-all">
            <i class="fas fa-arrow-left text-xs"></i>
          </div>
          <span class="text-[10px] font-black uppercase tracking-widest">Kembali ke Panel</span>
        </button>
        <div class="flex items-center gap-4">
          <div class="w-14 h-14 bg-green-600 rounded-2xl flex items-center justify-center text-white shadow-xl shadow-green-100">
            <i class="fas fa-hand-holding-heart text-2xl"></i>
          </div>
          <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tighter uppercase italic leading-none">Lapor <span class="text-green-600">Penyaluran</span></h1>
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-[0.2em] mt-1">Dokumentasi & Verifikasi Lapangan</p>
          </div>
        </div>
      </div>

      <div class="hidden md:block text-right">
        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Status GPS</p>
        <div class="flex items-center gap-2 justify-end" :class="coords ? 'text-green-600' : 'text-amber-500'">
          <span class="w-2 h-2 rounded-full bg-current animate-ping"></span>
          <span class="text-xs font-black italic">{{ coords ? 'Lokasi Terkunci' : 'Mencari Sinyal...' }}</span>
        </div>
      </div>
    </header>

    <form @submit.prevent="handleSubmit" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <!-- Kiri: Form Utama -->
      <div class="lg:col-span-2 space-y-6">
        <div class="bg-white p-8 rounded-[3rem] border border-gray-100 shadow-sm space-y-6">
          <div class="space-y-4">
            <!-- Pilihan Kampanye -->
            <div class="space-y-2">
              <label class="text-[10px] font-black uppercase text-gray-400 ml-2 tracking-widest flex items-center gap-2">
                <i class="fas fa-bullhorn text-green-500"></i> Pilih Program / Kampanye
              </label>
              <select v-model="form.campaign_id" class="w-full p-4 bg-gray-50 rounded-2xl outline-none focus:ring-2 focus:ring-green-600 font-bold text-xs transition-all border-none appearance-none cursor-pointer" required>
                <option value="" disabled>-- Pilih Program Terkait --</option>
                <option v-for="c in myCampaigns" :key="c.id" :value="c.id">{{ c.title }}</option>
              </select>
            </div>

            <!-- Judul Kegiatan -->
            <div class="space-y-2">
              <label class="text-[10px] font-black uppercase text-gray-400 ml-2 tracking-widest flex items-center gap-2">
                <i class="fas fa-tag text-green-500"></i> Nama Kegiatan Penyaluran
              </label>
              <input v-model="form.title" placeholder="Contoh: Penyaluran Sembako Tahap 1 - Desa Sukamaju" class="w-full p-4 bg-gray-50 rounded-2xl outline-none focus:ring-2 focus:ring-green-600 font-bold text-xs transition-all border-none" required />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <!-- Nominal Penyaluran -->
              <div class="space-y-2">
                <label class="text-[10px] font-black uppercase text-gray-400 ml-2 tracking-widest flex items-center gap-2">
                  <i class="fas fa-coins text-green-500"></i> Nominal Dana Tersalur
                </label>
                <div class="relative">
                  <span class="absolute left-4 top-1/2 -translate-y-1/2 font-black text-xs text-gray-400">Rp</span>
                  <input v-model="form.amount" type="number" placeholder="0" class="w-full p-4 pl-10 bg-gray-50 rounded-2xl outline-none focus:ring-2 focus:ring-green-600 font-black text-xs transition-all border-none" required />
                </div>
              </div>
              <!-- Tanggal Penyaluran -->
              <div class="space-y-2">
                <label class="text-[10px] font-black uppercase text-gray-400 ml-2 tracking-widest flex items-center gap-2">
                  <i class="fas fa-calendar-alt text-green-500"></i> Tanggal Pelaksanaan
                </label>
                <input v-model="form.distribution_date" type="date" class="w-full p-4 bg-gray-50 rounded-2xl outline-none focus:ring-2 focus:ring-green-600 font-black text-xs transition-all border-none" required />
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
               <!-- Nama Penerima -->
              <div class="space-y-2">
                <label class="text-[10px] font-black uppercase text-gray-400 ml-2 tracking-widest flex items-center gap-2">
                  <i class="fas fa-user text-green-500"></i> Perwakilan Penerima
                </label>
                <input v-model="form.recipient_name" placeholder="Nama Tokoh/Lembaga/Individu" class="w-full p-4 bg-gray-50 rounded-2xl outline-none focus:ring-2 focus:ring-green-600 font-bold text-xs transition-all border-none" />
              </div>
              <!-- Item Tersalur -->
              <div class="space-y-2">
                <label class="text-[10px] font-black uppercase text-gray-400 ml-2 tracking-widest flex items-center gap-2">
                  <i class="fas fa-box text-green-500"></i> Barang yang Disalurkan
                </label>
                <input v-model="form.items_distributed" placeholder="Contoh: 100 Box Nasi, 50 Paket Sembako" class="w-full p-4 bg-gray-50 rounded-2xl outline-none focus:ring-2 focus:ring-green-600 font-bold text-xs transition-all border-none" />
              </div>
            </div>

            <!-- Deskripsi -->
            <div class="space-y-2">
              <label class="text-[10px] font-black uppercase text-gray-400 ml-2 tracking-widest flex items-center gap-2">
                <i class="fas fa-align-left text-green-500"></i> Laporan Detail / Kronologi
              </label>
              <textarea v-model="form.description" placeholder="Ceritakan proses penyaluran secara lengkap agar donatur merasa tenang..." class="w-full h-40 p-4 bg-gray-50 rounded-2xl outline-none focus:ring-2 focus:ring-green-600 font-medium text-xs resize-none transition-all border-none" required></textarea>
            </div>
          </div>
        </div>

        <!-- Bagian Lokasi -->
        <div class="bg-white p-8 rounded-[3rem] border border-gray-100 shadow-sm space-y-6">
          <div class="flex items-center justify-between">
            <h3 class="font-black text-gray-900 uppercase italic text-sm tracking-tight">Data <span class="text-green-600">Geografis</span></h3>
            <button type="button" @click="getMyLocation" :disabled="locating" class="px-4 py-2 bg-green-50 text-green-600 rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-green-100 transition-all">
              <i class="fas fa-sync-alt mr-2" :class="{'animate-spin': locating}"></i> Refresh GPS
            </button>
          </div>

          <div class="space-y-4">
            <div class="space-y-2">
              <label class="text-[10px] font-black uppercase text-gray-400 ml-2 tracking-widest">Link Google Maps / Iframe (Opsional)</label>
              <input v-model="form.maps_link" @input="extractCoordsFromLink" placeholder="Tempel link maps untuk sinkronisasi koordinat otomatis..." class="w-full p-4 bg-gray-50 rounded-2xl outline-none focus:ring-2 focus:ring-green-600 font-medium text-[10px] transition-all border-none" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
               <div class="p-4 bg-green-50/50 rounded-2xl border border-green-100">
                 <p class="text-[8px] font-black text-green-400 uppercase tracking-widest mb-1">Latitude</p>
                 <p class="font-mono font-bold text-xs text-green-700">{{ coords?.latitude || 'Mendeteksi...' }}</p>
               </div>
               <div class="p-4 bg-green-50/50 rounded-2xl border border-green-100">
                 <p class="text-[8px] font-black text-green-400 uppercase tracking-widest mb-1">Longitude</p>
                 <p class="font-mono font-bold text-xs text-green-700">{{ coords?.longitude || 'Mendeteksi...' }}</p>
               </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Kanan: Dokumentasi & Submit -->
      <div class="space-y-6">
        <div class="bg-white p-8 rounded-[3rem] border border-gray-100 shadow-sm space-y-6">
          <h3 class="font-black text-gray-900 uppercase italic text-sm tracking-tight text-center">Bukti <span class="text-green-600">Penyaluran</span></h3>
          
          <div 
            class="relative w-full aspect-square bg-gray-50 border-2 border-dashed border-gray-200 rounded-[2.5rem] flex flex-col items-center justify-center overflow-hidden group cursor-pointer hover:bg-green-50/30 hover:border-green-200 transition-all"
            @click="$refs.photoInput.click()"
          >
            <img v-if="form.photo_preview" :src="form.photo_preview" class="w-full h-full object-cover shadow-2xl">
            <div v-else class="text-center p-6 space-y-4">
              <div class="w-20 h-20 bg-white rounded-3xl flex items-center justify-center mx-auto shadow-xl group-hover:scale-110 transition-transform">
                <i class="fas fa-camera text-3xl text-green-600"></i>
              </div>
              <div class="space-y-1">
                <p class="text-[11px] font-black uppercase text-gray-900 tracking-widest leading-none">Ambil Foto</p>
                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest leading-none">Dokumentasi Lapangan</p>
              </div>
            </div>
          </div>
          <input ref="photoInput" type="file" @change="handlePhotoChange" class="hidden" accept="image/*">
          
          <p class="text-[9px] text-gray-400 text-center italic leading-relaxed">Pastikan wajah penerima dan barang bantuan terlihat jelas untuk validasi laporan.</p>
        </div>

        <button 
          type="submit" 
          :disabled="submitting || !form.photo_preview || !coords" 
          class="w-full py-6 bg-green-600 text-white rounded-[2.5rem] font-black uppercase tracking-[0.3em] text-xs shadow-2xl shadow-green-200 hover:bg-green-700 hover:scale-[1.02] active:scale-95 transition-all disabled:opacity-50 disabled:grayscale disabled:hover:scale-100"
        >
          {{ submitting ? 'Sedang Mengirim...' : 'Kirim Laporan Resmi' }}
        </button>

        <div v-if="!coords" class="bg-amber-50 p-6 rounded-[2rem] border border-amber-100 flex gap-4 items-start animate-pulse">
          <i class="fas fa-exclamation-triangle text-amber-500 mt-1"></i>
          <p class="text-[9px] font-bold text-amber-800 leading-relaxed uppercase tracking-widest">Tombol kirim dinonaktifkan sementara karena koordinat GPS belum terdeteksi. Pastikan izin lokasi aktif.</p>
        </div>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import api from '@/api';
import Swal from 'sweetalert2';

const router = useRouter();
const submitting = ref(false);
const locating = ref(false);
const coords = ref(null);
const myCampaigns = ref([]);
const photoInput = ref(null);

const form = reactive({
  campaign_id: '',
  title: '',
  amount: '',
  recipient_name: '',
  items_distributed: '',
  distribution_date: new Date().toISOString().split('T')[0],
  description: '',
  maps_link: '',
  photo: null,
  photo_preview: ''
});

const fetchMyCampaigns = async () => {
  try {
    const res = await api.get('/campaigns/my-campaigns');
    myCampaigns.value = res.data;
  } catch (error) {
    console.error('Gagal memuat kampanye:', error);
  }
};

const getMyLocation = () => {
  locating.value = true;
  if (!navigator.geolocation) {
    Swal.fire('Error', 'Browser tidak mendukung GPS', 'error');
    locating.value = false;
    return;
  }

  navigator.geolocation.getCurrentPosition(
    (pos) => {
      coords.value = {
        latitude: pos.coords.latitude,
        longitude: pos.coords.longitude
      };
      locating.value = false;
    },
    (err) => {
      console.error(err);
      Swal.fire('GPS Error', 'Gagal mendeteksi lokasi. Pastikan izin GPS aktif.', 'warning');
      locating.value = false;
    },
    { enableHighAccuracy: true }
  );
};

const extractCoordsFromLink = () => {
  const link = form.maps_link;
  if (!link) return;

  const regexNormal = /@(-?\d+\.\d+),(-?\d+\.\d+)/;
  const regexIframe = /!2d(-?\d+\.\d+)!3d(-?\d+\.\d+)/;

  let match = link.match(regexNormal);
  if (match) {
    coords.value = { latitude: parseFloat(match[1]), longitude: parseFloat(match[2]) };
    return;
  }

  match = link.match(regexIframe);
  if (match) {
    // Iframe: !2d long !3d lat
    coords.value = { latitude: parseFloat(match[2]), longitude: parseFloat(match[1]) };
  }
};

const handlePhotoChange = (e) => {
  const file = e.target.files[0];
  if (!file) return;

  form.photo = file;
  const reader = new FileReader();
  reader.onload = (e) => {
    form.photo_preview = e.target.result;
  };
  reader.readAsDataURL(file);
};

const handleSubmit = async () => {
  if (!form.campaign_id) return Swal.fire('Error', 'Pilih kampanye terlebih dahulu', 'error');
  
  submitting.value = true;
  try {
    // Logic upload foto (base64 untuk kemudahan di backend ini)
    const payload = {
      ...form,
      latitude: coords.value.latitude,
      longitude: coords.value.longitude,
      location_name: form.title, // Menggunakan judul sebagai nama lokasi awal
      photo: form.photo_preview
    };

    await api.post('/field/distributions', payload);
    
    await Swal.fire({
      title: 'BERHASIL!',
      text: 'Laporan penyaluran Anda telah resmi tersimpan dan dipublikasikan.',
      icon: 'success',
      confirmButtonColor: '#10b981',
      confirmButtonText: 'MANTAP'
    });
    
    router.push('/dashboard/field');
  } catch (error) {
    console.error(error);
    Swal.fire('Gagal', error.response?.data?.message || 'Terjadi kesalahan sistem', 'error');
  } finally {
    submitting.value = false;
  }
};

onMounted(() => {
  fetchMyCampaigns();
  getMyLocation();
});
</script>

<style scoped>
.appearance-none {
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%239ca3af'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right 1rem center;
  background-size: 1rem;
}
</style>
