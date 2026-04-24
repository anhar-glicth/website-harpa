<template>
  <div class="min-h-screen bg-slate-50 flex items-center justify-center p-6 md:p-12">
    <div id="receipt-content" class="max-w-2xl w-full bg-white shadow-2xl rounded-[3rem] overflow-hidden border border-slate-100 relative">
      <!-- Watermark -->
      <div class="absolute inset-0 flex items-center justify-center opacity-[0.03] pointer-events-none select-none">
        <h1 class="text-9xl font-black -rotate-45 uppercase">HUMANITY</h1>
      </div>

      <!-- Header -->
      <div class="p-12 bg-green-600 text-white flex justify-between items-center relative overflow-hidden">
        <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
        <div class="relative z-10">
          <h2 class="text-3xl font-black tracking-tighter uppercase italic">Kwitansi <span class="text-green-200">Donasi</span></h2>
          <p class="text-xs font-bold text-white/70 uppercase tracking-widest mt-1">Official Digital Receipt</p>
        </div>
        <div class="relative z-10 text-right">
          <p class="text-xs font-black uppercase tracking-widest opacity-60">No. Referensi</p>
          <p class="text-lg font-black tracking-widest">#HC-{{ String(donation.id).padStart(6, '0') }}</p>
        </div>
      </div>

      <!-- Body -->
      <div v-if="loading" class="p-20 text-center">
        <div class="animate-spin h-10 w-10 border-4 border-green-600 border-t-transparent rounded-full mx-auto mb-4"></div>
        <p class="text-slate-400 font-bold uppercase tracking-widest text-[10px]">Memuat Detail Transaksi...</p>
      </div>

      <div v-else class="p-12 space-y-12 relative z-10">
        <!-- Main Info -->
        <div class="grid grid-cols-2 gap-8">
          <div class="space-y-1">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Donatur</p>
            <p class="text-xl font-black text-slate-900 tracking-tight">{{ donation.donor_name }}</p>
          </div>
          <div class="space-y-1 text-right">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Tanggal</p>
            <p class="text-xl font-black text-slate-900 tracking-tight">{{ formatDate(donation.donation_date) }}</p>
          </div>
        </div>

        <div class="bg-slate-50 p-8 rounded-3xl border border-slate-100 space-y-6">
          <div class="flex justify-between items-center pb-6 border-b border-slate-200">
            <div>
              <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Kampanye / Program</p>
              <p class="text-sm font-black text-slate-900 uppercase tracking-tight">{{ donation.campaign_title }}</p>
            </div>
            <div class="text-right">
              <span class="px-3 py-1 bg-green-600 text-white text-[9px] font-black uppercase tracking-widest rounded-lg">BERHASIL</span>
            </div>
          </div>

          <div class="flex justify-between items-end">
            <div>
              <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Metode Pembayaran</p>
              <p class="text-sm font-black text-slate-900 uppercase tracking-tight">{{ donation.payment_method || 'Transfer Bank' }}</p>
            </div>
            <div class="text-right">
              <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Donasi</p>
              <p class="text-4xl font-black text-green-600 italic tracking-tighter">Rp {{ Number(donation.amount).toLocaleString() }}</p>
            </div>
          </div>
        </div>

        <!-- Footer Info -->
        <div class="flex flex-col md:flex-row justify-between items-center gap-8 pt-8 border-t-2 border-dashed border-slate-200">
          <div class="text-center md:text-left space-y-2">
             <p class="text-[10px] font-bold text-slate-400 leading-relaxed italic">"Terima kasih atas kedermawanan Anda. Setiap rupiah yang Anda berikan adalah harapan bagi mereka yang membutuhkan."</p>
          </div>
          <div class="shrink-0 text-center">
             <div class="w-24 h-24 bg-slate-50 rounded-2xl flex items-center justify-center border border-slate-100 mx-auto mb-2 overflow-hidden p-2">
                <!-- QR Code Placeholder -->
                <i class="fas fa-qrcode text-5xl text-slate-200"></i>
             </div>
             <p class="text-[8px] font-black text-slate-400 uppercase tracking-[0.2em]">VERIFIED BY HC SYSTEM</p>
          </div>
        </div>
      </div>

      <!-- Action Footer -->
      <div class="p-8 bg-slate-50 border-t border-slate-100 flex justify-center print:hidden">
        <button @click="printReceipt" class="flex items-center gap-3 px-8 py-4 bg-slate-900 text-white rounded-2xl font-black uppercase text-[10px] tracking-widest hover:bg-green-600 transition-all shadow-xl shadow-slate-200">
          <i class="fas fa-print"></i> Cetak Kwitansi
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import api from '@/api';

const route = useRoute();
const donation = ref({});
const loading = ref(true);

const fetchDetail = async () => {
  try {
    const res = await api.get(`/donations/${route.params.id}`);
    donation.value = res.data;
  } catch (e) {
    alert('Gagal memuat detail donasi.');
  } finally {
    loading.value = false;
  }
};

const formatDate = (str) => {
  return new Date(str).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
};

const printReceipt = () => {
  window.print();
};

onMounted(fetchDetail);
</script>

<style scoped>
@media print {
  body * {
    visibility: hidden;
  }
  #receipt-content, #receipt-content * {
    visibility: visible;
  }
  #receipt-content {
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
    margin: 0;
    padding: 0;
    box-shadow: none;
    border: none;
  }
}
</style>
