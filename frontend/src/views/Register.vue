<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-50 p-6">
    <div class="w-full max-w-md bg-white p-10 rounded-[2.5rem] shadow-xl border border-gray-100">
      <!-- Header Pendaftaran -->
      <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-green-50 text-green-600 rounded-3xl mb-4 shadow-sm">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
          </svg>
        </div>
        <h2 class="text-3xl font-black text-gray-900 tracking-tight">Buat Akun Donatur</h2>
        <p class="text-gray-500 text-sm mt-2 font-medium">
          Bergabunglah untuk mulai menebar kebaikan hari ini.
        </p>
      </div>

      <!-- Form Pendaftaran -->
      <form @submit.prevent="handleRegister" class="space-y-6">
        <!-- Input Nama -->
        <div class="space-y-2">
          <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Nama Lengkap</label>
          <input 
            v-model="form.name" 
            type="text" 
            class="w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl outline-none focus:ring-4 focus:ring-green-500/10 focus:border-green-600 transition-all font-bold placeholder:font-medium" 
            placeholder="Masukkan nama Anda"
            required
          >
        </div>

        <!-- Input Email -->
        <div class="space-y-2">
          <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Alamat Email</label>
          <input 
            v-model="form.email" 
            type="email" 
            class="w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl outline-none focus:ring-4 focus:ring-green-500/10 focus:border-green-600 transition-all font-bold placeholder:font-medium" 
            placeholder="email@contoh.com"
            required
          >
        </div>

        <!-- Input Password -->
        <div class="space-y-2">
          <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Kata Sandi</label>
          <input 
            v-model="form.password" 
            type="password" 
            class="w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl outline-none focus:ring-4 focus:ring-green-500/10 focus:border-green-600 transition-all font-bold placeholder:font-medium" 
            placeholder="••••••••"
            required
          >
        </div>

        <!-- Pesan Sukses -->
        <div v-if="success" class="p-4 bg-green-50 text-green-600 text-xs rounded-2xl font-bold flex items-center gap-3 border border-green-100">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
          </svg>
          Registrasi berhasil! Mengalihkan ke halaman masuk...
        </div>

        <!-- Tombol Daftar -->
        <button 
          type="submit" 
          :disabled="loading" 
          class="w-full py-5 bg-green-600 text-white font-black rounded-2xl hover:bg-green-700 active:scale-95 transition-all shadow-xl shadow-green-100 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-3"
        >
          <span v-if="!loading">Daftar Sekarang</span>
          <div v-else class="flex items-center gap-2">
            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Memproses...
          </div>
        </button>
      </form>

      <!-- Footer Pendaftaran -->
      <div class="mt-8 pt-6 border-t border-gray-50 text-center">
        <p class="text-sm text-gray-500">
          Sudah memiliki akun? 
          <router-link to="/login" class="text-green-600 font-bold hover:underline">Masuk ke sini</router-link>
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue';
import { useRouter } from 'vue-router';
// PERBAIKAN: Menggunakan alias @ untuk merujuk ke folder src
import api from '@/api';

const router = useRouter();
const loading = ref(false);
const success = ref(false);

/**
 * Data Formulir
 * Role tidak disertakan di sini karena backend akan otomatis
 * mengelompokkan sebagai 'user' (Donatur) demi keamanan.
 */
const form = reactive({
  name: '',
  email: '',
  password: ''
});

/**
 * Menangani proses pendaftaran
 */
const handleRegister = async () => {
  if (loading.value) return;

  loading.value = true;
  try {
    // Memanggil API pendaftaran publik
    await api.post('/auth/register', form);
    
    success.value = true;
    
    // Memberikan waktu jeda sebelum pindah halaman agar pesan sukses terbaca
    setTimeout(() => {
      router.push('/login');
    }, 2000);
  } catch (err) {
    const message = err.response?.data?.message || 'Gagal mendaftar. Silakan coba lagi nanti.';
    alert(message);
  } finally {
    loading.value = false;
  }
};
</script>