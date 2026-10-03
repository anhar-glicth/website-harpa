<template>
  <main class="flex-grow space-y-6 lg:space-y-8 w-full pb-28 lg:pb-0">

          <!-- Header -->
          <!-- Header -->
          <header class="bg-white p-6 sm:p-8 rounded-[2rem] sm:rounded-[2.5rem] border border-gray-100 shadow-sm flex flex-col md:flex-row justify-between items-center gap-5">
            <div class="space-y-1 text-center md:text-left">
              <h1 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tighter uppercase italic">
                Donatur <span class="text-green-600">Portal</span>
              </h1>
              <p class="text-[9px] sm:text-[10px] text-gray-400 font-bold uppercase tracking-widest">Pusat Kebaikan & Kontribusi Anda</p>
            </div>
            <router-link to="/campaigns" class="w-full md:w-auto text-center px-8 py-3.5 bg-green-600 text-white rounded-xl sm:rounded-2xl font-black text-[9px] sm:text-[10px] uppercase tracking-widest hover:bg-green-700 transition-all shadow-lg shadow-green-500/20">
              🌿 Jelajah Program
            </router-link>
          </header>

          <!-- DONATION HISTORY WITH TABS -->
          <section class="bg-white rounded-[2rem] sm:rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden">
            <div class="p-6 sm:p-8 border-b border-gray-50 flex flex-col md:flex-row justify-between items-center gap-4">
              <h3 class="font-black text-gray-900 uppercase tracking-tighter italic text-xl">Riwayat <span class="text-green-600">Donasi</span></h3>
              <div class="bg-gray-50 p-1 rounded-xl sm:rounded-2xl inline-flex gap-1 w-full md:w-auto overflow-x-auto no-scrollbar">
                <button @click="activeTab = 'donations'" :class="['whitespace-nowrap flex-1 md:flex-none px-5 py-2.5 rounded-lg sm:rounded-xl text-[9px] sm:text-[10px] font-black uppercase tracking-widest transition-all', activeTab === 'donations' ? 'bg-green-600 text-white shadow-lg' : 'text-gray-400 hover:text-gray-700']">Transaksi</button>
                <button @click="activeTab = 'tracker'" :class="['whitespace-nowrap flex-1 md:flex-none px-5 py-2.5 rounded-lg sm:rounded-xl text-[9px] sm:text-[10px] font-black uppercase tracking-widest transition-all', activeTab === 'tracker' ? 'bg-green-600 text-white shadow-lg' : 'text-gray-400 hover:text-gray-700']">Live Tracker</button>
                <button @click="activeTab = 'certificate'" :class="['whitespace-nowrap flex-1 md:flex-none px-5 py-2.5 rounded-lg sm:rounded-xl text-[9px] sm:text-[10px] font-black uppercase tracking-widest transition-all', activeTab === 'certificate' ? 'bg-green-600 text-white shadow-lg' : 'text-gray-400 hover:text-gray-700']">Sertifikat</button>
              </div>
            </div>

            <!-- Tab: Transactions -->
            <div v-if="activeTab === 'donations'" class="overflow-x-auto">
              <table class="w-full text-left">
                <thead class="bg-gray-50 text-[10px] text-gray-400 font-black uppercase tracking-widest">
                  <tr>
                    <th class="px-8 py-5">#</th>
                    <th class="px-8 py-5">Program</th>
                    <th class="px-8 py-5">Nominal</th>
                    <th class="px-8 py-5">Metode</th>
                    <th class="px-8 py-5 text-center">Status</th>
                    <th class="px-8 py-5 text-center">Kwitansi</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                  <tr v-for="(d, idx) in donations" :key="d.id" class="hover:bg-green-50/30 transition-colors">
                    <td class="px-8 py-6 text-[10px] font-black text-gray-300">{{ String(idx+1).padStart(2,'0') }}</td>
                    <td class="px-8 py-6">
                      <p class="font-black text-gray-900 italic tracking-tight">{{ d.campaign_title }}</p>
                      <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mt-0.5">{{ formatDate(d.created_at) }}</p>
                    </td>
                    <td class="px-8 py-6 font-black text-green-600 italic">Rp {{ formatCurrency(d.amount) }}</td>
                    <td class="px-8 py-6 text-[10px] font-bold text-gray-500 uppercase tracking-widest">{{ d.payment_method || 'Transfer' }}</td>
                    <td class="px-8 py-6 text-center">
                      <span :class="['px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest', d.payment_status === 'Success' ? 'bg-green-50 text-green-600' : 'bg-amber-50 text-amber-600']">
                        {{ d.payment_status === 'Success' ? 'Berhasil' : 'Menunggu' }}
                      </span>
                    </td>
                    <td class="px-8 py-6 text-center">
                      <router-link v-if="d.payment_status === 'Success'" :to="`/receipt/${d.id}`" target="_blank" class="text-green-600 hover:text-green-700 transition">
                        <i class="fas fa-file-invoice text-lg"></i>
                      </router-link>
                      <span v-else class="text-gray-200">
                        <i class="fas fa-file-invoice text-lg"></i>
                      </span>
                    </td>
                  </tr>
                  <tr v-if="donations.length === 0">
                    <td colspan="5" class="px-8 py-24 text-center">
                      <div class="text-6xl mb-4 opacity-20">📭</div>
                      <p class="text-gray-400 font-black uppercase tracking-widest text-[10px]">Belum ada riwayat kebaikan.</p>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Tab: Live Tracker -->
            <div v-if="activeTab === 'tracker'" class="p-5 sm:p-8 space-y-6">
              <p class="text-xs sm:text-sm text-gray-400">Pantau secara langsung ke mana donasi Anda mengalir.</p>
              <div v-for="(d, idx) in donations.slice(0,3)" :key="d.id" class="bg-slate-50 rounded-[2rem] p-5 sm:p-6 space-y-5">
                <p class="font-black text-gray-900 tracking-tight text-xs sm:text-base">{{ d.campaign_title }}</p>
                <div class="flex items-center justify-between gap-1 overflow-x-auto no-scrollbar pb-2">
                  <div v-for="(step, si) in trackerSteps" :key="step.label" class="flex items-center flex-1 min-w-[70px]">
                    <div class="flex flex-col items-center">
                      <div :class="['w-7 h-7 sm:w-8 sm:h-8 rounded-full flex items-center justify-center text-[10px] sm:text-xs font-black transition-all', si <= getStepIndex(d.tracking_status) && d.payment_status === 'Success' ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-400']">
                        {{ step.icon }}
                      </div>
                      <p class="text-[8px] sm:text-[9px] font-black uppercase text-gray-400 mt-2 tracking-widest text-center leading-tight w-full">{{ step.label }}</p>
                    </div>
                    <div v-if="si < trackerSteps.length - 1" :class="['flex-1 h-0.5 sm:h-1 mx-1 rounded-full', si < getStepIndex(d.tracking_status) && d.payment_status === 'Success' ? 'bg-green-600' : 'bg-gray-200']"></div>
                  </div>
                </div>
              </div>
              <div v-if="donations.length === 0" class="text-center py-16 opacity-40">
                <div class="text-6xl mb-4">🔍</div>
                <p class="font-black uppercase tracking-widest text-[10px] text-gray-400">Belum ada donasi untuk dilacak.</p>
              </div>
            </div>

            <!-- Tab: Certificate -->
            <div v-if="activeTab === 'certificate'" class="p-8 space-y-6">
              <p class="text-sm text-gray-400 mb-6">Unduh sertifikat resmi sebagai bukti kebaikan Anda.</p>
              <div v-if="donations.filter(d => d.payment_status === 'Success').length > 0">
                <div v-for="d in donations.filter(d => d.payment_status === 'Success')" :key="d.id"
                  class="flex flex-col md:flex-row items-center justify-between bg-gradient-to-r from-green-50 to-emerald-50 border border-green-100 rounded-3xl p-6 gap-4">
                  <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-green-600 rounded-2xl flex items-center justify-center text-white text-2xl">🏅</div>
                    <div>
                      <p class="font-black text-gray-900 tracking-tight">{{ d.campaign_title }}</p>
                      <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Donasi {{ formatDate(d.created_at) }} · Rp {{ formatCurrency(d.amount) }}</p>
                    </div>
                  </div>
                  <div class="flex gap-2">
                    <router-link :to="`/receipt/${d.id}`" target="_blank" class="bg-slate-900 text-white px-6 py-3 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-800 transition-all whitespace-nowrap">
                      📄 Kwitansi
                    </router-link>
                    <button @click="downloadCertificate(d)" class="bg-green-600 text-white px-6 py-3 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-green-700 transition-all whitespace-nowrap">
                      ⬇ Sertifikat
                    </button>
                  </div>
                </div>
              </div>
              <div v-else class="text-center py-16 opacity-40">
                <div class="text-6xl mb-4">🏅</div>
                <p class="font-black uppercase tracking-widest text-[10px] text-gray-400">Selesaikan donasi untuk mendapatkan sertifikat.</p>
              </div>
            </div>
          </section>

          <!-- DONOR WALL (Pesan & Doa) -->
          <section class="bg-white rounded-[2rem] sm:rounded-[2.5rem] border border-gray-100 shadow-sm p-6 sm:p-8 md:p-12">
            <div class="mb-6">
              <p class="text-[9px] sm:text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Papan Doa</p>
              <h3 class="text-xl sm:text-2xl font-black text-gray-900 tracking-tighter">Tinggalkan <span class="text-green-600">Pesan</span></h3>
            </div>
            <div class="space-y-4 mb-6">
              <textarea v-model="prayerMessage" rows="3" placeholder="Tulis doa atau pesan untuk penerima manfaat..." class="w-full bg-slate-50 border border-gray-200 rounded-2xl px-6 py-4 text-sm text-gray-700 placeholder-gray-300 font-medium focus:outline-none focus:ring-2 focus:ring-green-500 resize-none transition-all"></textarea>
              <div class="flex justify-between items-center">
                <label class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-gray-400 cursor-pointer">
                  <input type="checkbox" v-model="isAnonymous" class="accent-green-600" />
                  Tampilkan sebagai Anonim
                </label>
                <button @click="submitPrayer" class="bg-green-600 text-white px-8 py-3 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-green-700 transition-all">Kirim Doa</button>
              </div>
            </div>
            <div class="space-y-3">
              <div v-for="msg in prayerWall" :key="msg.id" class="bg-slate-50 rounded-2xl px-6 py-4 flex items-start gap-4">
                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center text-green-600 font-black text-xs flex-shrink-0">
                  {{ msg.anonymous ? '🤍' : msg.name.charAt(0).toUpperCase() }}
                </div>
                <div>
                  <p class="font-black text-[10px] uppercase tracking-widest text-gray-400">{{ msg.anonymous ? 'Anonim' : msg.name }}</p>
                  <p class="text-sm text-gray-600 mt-1 leading-relaxed">{{ msg.message }}</p>
                </div>
              </div>
            </div>
          </section>

        </main>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import api from '@/api';

const activeTab = ref('donations');
const donations = ref([]);
const loading = ref(true);
const prayerMessage = ref('');
const isAnonymous = ref(false);
const prayerWall = ref([]);

const trackerSteps = [
  { icon: '✓', label: 'Diterima', key: 'Received' },
  { icon: '🛒', label: 'Logistik', key: 'Processing' },
  { icon: '🚚', label: 'Dikirim', key: 'Shipping' },
  { icon: '🤝', label: 'Tersalur', key: 'Delivered' },
];

const getStepIndex = (status) => {
  const map = { 'Received': 0, 'Processing': 1, 'Shipping': 2, 'Delivered': 3 };
  return map[status] ?? 0;
};

const totalAmount = computed(() => donations.value.filter(d => d.payment_status === 'Success').reduce((s, d) => s + Number(d.amount), 0));

const impactStats = computed(() => {
  const total = totalAmount.value;
  return [
    { value: formatCurrency(total), label: 'Total Donasi', icon: '💰' },
    { value: Math.floor(total / 500000), label: 'Keluarga Terbantu', icon: '👨‍👩‍👧‍👦' },
    { value: Math.floor(total / 50000), label: 'Kg Beras', icon: '🌾' },
    { value: donations.value.filter(d => d.payment_status === 'Success').length, label: 'Program Didukung', icon: '📦' },
  ];
});

const achievementBadges = computed(() => {
  const total = totalAmount.value;
  const count = donations.value.filter(d => d.payment_status === 'Success').length;
  return [
    { icon: '🌱', title: 'Benih Kebaikan', desc: 'Donasi pertama Anda', unlocked: count >= 1 },
    { icon: '💚', title: 'Pejuang Kemanusiaan', desc: 'Sudah 3x berdonasi', unlocked: count >= 3 },
    { icon: '⭐', title: 'Donatur Setia', desc: 'Total donasi > 500K', unlocked: total >= 500000 },
    { icon: '🏆', title: 'Pahlawan Tersembunyi', desc: 'Total donasi > 2 Juta', unlocked: total >= 2000000 },
    { icon: '🌟', title: 'Ikon Kemanusiaan', desc: 'Sudah 10x berdonasi', unlocked: count >= 10 },
    { icon: '🕊️', title: 'Duta Perdamaian', desc: 'Total donasi > 5 Juta', unlocked: total >= 5000000 },
    { icon: '👑', title: 'Legenda Donatur', desc: 'Total > 10 Juta', unlocked: total >= 10000000 },
    { icon: '💎', title: 'Dermawan Platinum', desc: 'Total > 50 Juta', unlocked: total >= 50000000 },
  ];
});

const formatCurrency = (val) => new Intl.NumberFormat('id-ID').format(val || 0);
const formatDate = (d) => !d ? '-' : new Date(d).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });

const downloadCertificate = (d) => {
  const html = `<!DOCTYPE html>
<html lang="id">
<head><meta charset="UTF-8"><title>Sertifikat Donasi</title>
<style>
  body { margin: 0; font-family: 'Georgia', serif; background: #f8faf8; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
  .cert { width: 800px; background: white; border: 8px solid #16a34a; padding: 60px; text-align: center; position: relative; }
  .cert::before { content: ''; position: absolute; inset: 12px; border: 2px solid #bbf7d0; pointer-events: none; }
  h1 { color: #16a34a; font-size: 38px; letter-spacing: 3px; margin-bottom: 8px; }
  .sub { color: #6b7280; font-size: 14px; letter-spacing: 5px; text-transform: uppercase; margin-bottom: 40px; }
  .name { font-size: 28px; color: #111827; font-weight: bold; border-bottom: 2px solid #16a34a; display: inline-block; padding-bottom: 8px; margin: 16px 0; }
  .amount { font-size: 36px; color: #16a34a; font-weight: bold; }
  .campaign { font-size: 16px; color: #374151; margin: 8px 0 40px; }
  .seal { width: 80px; height: 80px; background: #16a34a; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 32px; margin: 0 auto 20px; }
</style></head>
<body><div class="cert">
  <div class="seal">🏅</div>
  <h1>SERTIFIKAT DONASI</h1>
  <p class="sub">Amal Abadi — Platform Kemanusiaan & Zakat Terpercaya</p>
  <p style="color:#6b7280">Dengan bangga diberikan kepada</p>
  <div class="name">Donatur Amal Abadi</div>
  <p style="color:#6b7280">atas kontribusi sebesar</p>
  <div class="amount">Rp ${formatCurrency(d.amount)}</div>
  <div class="campaign">untuk program: <b>${d.campaign_title}</b></div>
  <p style="color:#9ca3af;font-size:12px">Tanggal: ${formatDate(d.created_at)}</p>
</div></body></html>`;
  const blob = new Blob([html], { type: 'text/html' });
  const a = document.createElement('a');
  a.href = URL.createObjectURL(blob);
  a.download = `Sertifikat_Donasi_${d.id}.html`;
  a.click();
};

const submitPrayer = async () => {
  if (!prayerMessage.value.trim()) return;
  try {
    await api.post('/prayers', { message: prayerMessage.value, is_anonymous: isAnonymous.value });
    prayerMessage.value = '';
    fetchPrayers();
  } catch (err) { console.error(err); }
};

const fetchPrayers = async () => {
  try {
    const res = await api.get('/prayers');
    prayerWall.value = res.data;
  } catch (err) { console.error(err); }
};

const fetchData = async () => {
  loading.value = true;
  try {
    const res = await api.get('/donations/my-donations');
    donations.value = res.data;
    fetchPrayers();
  } catch (err) { console.error(err); }
  finally { loading.value = false; }
};

onMounted(fetchData);
</script>