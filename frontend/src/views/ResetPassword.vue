<template>
  <div class="min-h-[calc(100vh-64px)] flex items-center justify-center p-6 bg-gray-50">
    <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-lg border border-gray-100">
      <!-- Header -->
      <div class="text-center mb-8">
        <div class="bg-green-600 w-12 h-12 rounded-xl flex items-center justify-center mx-auto mb-4 text-white shadow-lg shadow-green-200">
          <i class="fas fa-key text-xl"></i>
        </div>
        <h2 class="text-2xl font-bold text-gray-900">Reset Password</h2>
        <p class="text-gray-500 text-sm mt-1">Masukkan token yang Anda terima di email dan password baru Anda</p>
      </div>

      <!-- Form -->
      <form @submit.prevent="handleResetPassword" class="space-y-5">
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nomor Token</label>
          <input 
            v-model="form.token" 
            type="text" 
            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500/20 focus:border-green-500 outline-none transition-all text-center tracking-[0.5em] font-black text-lg" 
            placeholder="000000"
            maxlength="6"
            required
          >
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1.5">Password Baru</label>
          <input 
            v-model="form.newPassword" 
            type="password" 
            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500/20 focus:border-green-500 outline-none transition-all" 
            placeholder="••••••••"
            required
          >
        </div>

        <div v-if="errorMessage" class="p-3 bg-red-50 border border-red-100 text-red-600 text-xs rounded-xl font-medium flex items-center gap-2">
          <i class="fas fa-exclamation-circle"></i>
          {{ errorMessage }}
        </div>

        <div v-if="successMessage" class="p-3 bg-green-50 border border-green-100 text-green-600 text-xs rounded-xl font-medium flex items-center gap-2">
          <i class="fas fa-check-circle"></i>
          {{ successMessage }}
        </div>

        <button 
          type="submit" 
          :disabled="isLoading || !!successMessage"
          class="w-full bg-green-600 text-white py-3 rounded-xl font-bold hover:bg-green-700 active:scale-[0.98] transition-all disabled:opacity-50 shadow-md shadow-green-100"
        >
          <span v-if="!isLoading">Perbarui Password</span>
          <span v-else class="flex items-center justify-center gap-2">
            <i class="fas fa-circle-notch animate-spin"></i>
            Memproses...
          </span>
        </button>
      </form>

      <!-- Footer -->
      <div class="mt-8 pt-6 border-t border-gray-50 text-center">
        <router-link to="/login" class="text-sm text-green-600 font-bold hover:underline">
          Kembali ke Login
        </router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import api from '../api';

const router = useRouter();
const isLoading = ref(false);
const errorMessage = ref('');
const successMessage = ref('');

const form = reactive({
  email: '',
  token: '',
  newPassword: ''
});

onMounted(() => {
  form.email = localStorage.getItem('reset_email') || '';
  if (!form.email) {
    router.push('/forgot-password');
  }
});

const handleResetPassword = async () => {
  isLoading.value = true;
  errorMessage.value = '';
  successMessage.value = '';
  try {
    const response = await api.post('/auth/reset-password', form);
    successMessage.value = response.data.message;
    localStorage.removeItem('reset_email');
    
    setTimeout(() => {
      router.push('/login');
    }, 2000);
  } catch (error) {
    errorMessage.value = error.response?.data?.message || 'Gagal mereset password. Token mungkin salah atau kedaluwarsa.';
  } finally {
    isLoading.value = false;
  }
};
</script>
