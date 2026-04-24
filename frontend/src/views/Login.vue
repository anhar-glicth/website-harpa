<template>
  <div class="min-h-[calc(100vh-64px)] flex items-center justify-center p-6 bg-gray-50">
    <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-lg border border-gray-100">
      <!-- Header Login -->
      <div class="text-center mb-8">
        <div class="bg-green-600 w-12 h-12 rounded-xl flex items-center justify-center mx-auto mb-4 text-white shadow-lg shadow-green-200">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
          </svg>
        </div>
        <h2 class="text-2xl font-bold text-gray-900">Selamat Datang</h2>
        <p class="text-gray-500 text-sm mt-1">Masuk untuk mengelola donasi dan laporan Anda</p>
      </div>

      <!-- Form Login -->
      <form @submit.prevent="handleLogin" class="space-y-5">
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1.5">Alamat Email</label>
          <input 
            v-model="form.email" 
            type="email" 
            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500/20 focus:border-green-500 outline-none transition-all" 
            placeholder="nama@email.com"
            required
          >
        </div>
        
        <div>
          <div class="flex justify-between mb-1.5">
            <label class="block text-sm font-semibold text-gray-700">Kata Sandi</label>
            <a href="#" class="text-xs text-green-600 hover:underline">Lupa sandi?</a>
          </div>
          <input 
            v-model="form.password" 
            type="password" 
            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500/20 focus:border-green-500 outline-none transition-all" 
            placeholder="••••••••"
            required
          >
        </div>

        <!-- Pesan Error -->
        <div v-if="errorMessage" class="p-3 bg-red-50 border border-red-100 text-red-600 text-xs rounded-xl font-medium flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          {{ errorMessage }}
        </div>

        <!-- Tombol Submit -->
        <button 
          type="submit" 
          :disabled="isLoading"
          class="w-full bg-green-600 text-white py-3 rounded-xl font-bold hover:bg-green-700 active:scale-[0.98] transition-all disabled:opacity-50 disabled:cursor-not-allowed shadow-md shadow-green-100"
        >
          <span v-if="!isLoading">Masuk Sekarang</span>
          <span v-else class="flex items-center justify-center gap-2">
            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Memproses...
          </span>
        </button>
      </form>

      <!-- Footer Info -->
      <div class="mt-8 pt-6 border-t border-gray-50 text-center">
        <p class="text-sm text-gray-500">
          Belum memiliki akun? 
          <router-link to="/register" class="text-green-600 font-bold hover:underline">Daftar di sini</router-link>
        </p>
      </div>

      <!-- Hint untuk simulasi -->
      <div class="mt-4 p-3 bg-green-50 rounded-xl text-[10px] text-green-700 leading-relaxed">
        <strong>Tips Simulasi:</strong> Gunakan email yang mengandung kata <b>"admin"</b> untuk masuk sebagai Admin, atau <b>"field"</b> untuk Petugas Lapangan.
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue';
import { useRouter } from 'vue-router';
import api from '../api';
import { useAuth } from '../store/auth';

const router = useRouter();
const { setAuth } = useAuth();

// State reaktif untuk formulir
const form = reactive({
  email: '',
  password: ''
});

// UI State
const isLoading = ref(false);
const errorMessage = ref('');

/**
 * Menangani proses login pengguna
 */
const handleLogin = async () => {
  isLoading.value = true;
  errorMessage.value = '';

  try {
    // Memanggil API login ke backend
    const response = await api.post('/auth/login', form);
    
    // Simpan data ke store & localStorage menggunakan action setAuth
    setAuth({
      name: response.data.name,
      role: response.data.role,
      token: response.data.token
    });

    // Arahkan ke dashboard setelah berhasil
    router.push('/dashboard');
  } catch (error) {
    // Tangani error dari server
    if (error.response) {
      errorMessage.value = error.response.data.message || 'Email atau password salah.';
    } else {
      errorMessage.value = 'Koneksi ke server gagal. Pastikan backend Anda berjalan.';
    }
    console.error('Login Error:', error);
  } finally {
    isLoading.value = false;
  }
};
</script>