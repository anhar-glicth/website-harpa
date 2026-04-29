<template>
  <div class="min-h-[calc(100vh-64px)] flex items-center justify-center p-6 bg-gray-50">
    <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-lg border border-gray-100">
      <!-- Header -->
      <div class="text-center mb-8">
        <div class="bg-green-600 w-12 h-12 rounded-xl flex items-center justify-center mx-auto mb-4 text-white shadow-lg shadow-green-200">
          <i class="fas fa-envelope-open-text text-xl"></i>
        </div>
        <h2 class="text-2xl font-bold text-gray-900">Lupa Password?</h2>
        <p class="text-gray-500 text-sm mt-1">Masukkan email Anda untuk menerima nomor token reset password</p>
      </div>

      <!-- Form -->
      <form v-if="!isSent" @submit.prevent="handleForgotPassword" class="space-y-5">
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1.5">Alamat Email</label>
          <input 
            v-model="email" 
            type="email" 
            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500/20 focus:border-green-500 outline-none transition-all" 
            placeholder="nama@email.com"
            required
          >
        </div>

        <div v-if="errorMessage" class="p-3 bg-red-50 border border-red-100 text-red-600 text-xs rounded-xl font-medium flex items-center gap-2">
          <i class="fas fa-exclamation-circle"></i>
          {{ errorMessage }}
        </div>

        <button 
          type="submit" 
          :disabled="isLoading"
          class="w-full bg-green-600 text-white py-3 rounded-xl font-bold hover:bg-green-700 active:scale-[0.98] transition-all disabled:opacity-50 shadow-md shadow-green-100"
        >
          <span v-if="!isLoading">Kirim Token Ke Email</span>
          <span v-else class="flex items-center justify-center gap-2">
            <i class="fas fa-circle-notch animate-spin"></i>
            Memproses...
          </span>
        </button>
      </form>

      <!-- Success Message -->
      <div v-else class="text-center space-y-6">
        <div class="p-4 bg-green-50 border border-green-100 rounded-2xl text-green-700 text-sm leading-relaxed">
          <i class="fas fa-check-circle text-2xl mb-2 block"></i>
          Token berhasil dikirim! Silakan periksa kotak masuk (atau folder spam) email Anda.
        </div>
        <button 
          @click="goToReset"
          class="w-full bg-slate-900 text-white py-3 rounded-xl font-bold hover:bg-slate-800 transition-all shadow-md"
        >
          Masukkan Token & Reset Password
        </button>
      </div>

      <!-- Footer -->
      <div class="mt-8 pt-6 border-t border-gray-50 text-center">
        <router-link to="/login" class="text-sm text-green-600 font-bold hover:underline">
          <i class="fas fa-arrow-left mr-1"></i> Kembali ke Login
        </router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import api from '../api';

const router = useRouter();
const email = ref('');
const isLoading = ref(false);
const isSent = ref(false);
const errorMessage = ref('');

const handleForgotPassword = async () => {
  isLoading.value = true;
  errorMessage.value = '';
  try {
    await api.post('/auth/forgot-password', { email: email.value });
    isSent.value = true;
    localStorage.setItem('reset_email', email.value);
  } catch (error) {
    errorMessage.value = error.response?.data?.message || 'Gagal mengirim email. Pastikan email terdaftar.';
  } finally {
    isLoading.value = false;
  }
};

const goToReset = () => {
  router.push('/reset-password');
};
</script>
