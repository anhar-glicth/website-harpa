<template>
  <Teleport to="body">
    <Transition name="fade">
      <div v-if="state.isAuthModalOpen" class="fixed inset-0 z-[200] flex items-center justify-center p-4 sm:p-6" @click.self="closeAuthModal">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-md transition-opacity"></div>
        
        <!-- Modal Content -->
        <Transition name="scale-up" appear>
          <div class="relative w-full max-w-lg bg-white rounded-[2.5rem] shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-300 max-h-[90vh] overflow-y-auto no-scrollbar">
            <!-- Close Button -->
            <button @click="closeAuthModal" class="absolute top-6 right-6 p-2 bg-slate-50 text-slate-400 hover:text-green-600 rounded-full transition-colors z-10">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M18 6 6 18M6 6l12 12"/></svg>
            </button>

            <div class="grid grid-cols-1 md:grid-cols-12">
               <!-- Left Side (Visual on Desktop) -->
               <div class="hidden md:block md:col-span-4 bg-green-600 p-10 relative overflow-hidden">
                  <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
                  <div class="relative z-10 flex flex-col h-full justify-between">
                     <div class="text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mb-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
                        <h4 class="text-xl font-black uppercase tracking-tighter leading-none">Peduli <br> <span class="opacity-60">Sesama</span></h4>
                     </div>
                     <p class="text-[9px] font-black uppercase tracking-widest text-white/50 italic leading-relaxed">Bergabunglah bersama ribuan pejuang kebaikan lainnya.</p>
                  </div>
               </div>

               <!-- Right Side (Form) -->
               <div class="md:col-span-8 p-8 md:p-12">
                  <div class="mb-8">
                    <h2 class="text-2xl font-black text-slate-900 uppercase tracking-tighter italic">{{ state.authModalMode === 'login' ? 'Selamat Datang' : 'Buat Akun Baru' }}</h2>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">{{ state.authModalMode === 'login' ? 'Silakan masuk ke akun Anda' : 'Langkah awal berbuat kebaikan' }}</p>
                  </div>

                  <!-- Integrated Login/Register Component -->
                  <div v-if="state.authModalMode === 'login'" class="space-y-6">
                    <div class="space-y-4">
                       <input v-model="loginForm.email" type="email" placeholder="Email Anda" class="w-full px-6 py-4 bg-slate-50 border border-transparent rounded-2xl focus:border-green-600 focus:bg-white transition-all font-bold text-sm outline-none">
                       <input v-model="loginForm.password" type="password" placeholder="Kata Sandi" class="w-full px-6 py-4 bg-slate-50 border border-transparent rounded-2xl focus:border-green-600 focus:bg-white transition-all font-bold text-sm outline-none">
                       <div class="flex justify-end px-2">
                         <button @click="handleGoToForgotPassword" class="text-[9px] font-black text-green-600 uppercase tracking-widest hover:underline">Lupa Kata Sandi?</button>
                       </div>
                    </div>
                    <div v-if="error" class="text-[10px] font-bold text-red-500 uppercase tracking-widest animate-pulse">{{ error }}</div>
                    <button @click="handleLogin" :disabled="isLoading" class="w-full py-5 bg-green-600 text-white rounded-[1.5rem] font-black uppercase tracking-[0.2em] shadow-xl shadow-green-100 hover:bg-green-700 transition active:scale-95 disabled:opacity-50">
                       {{ isLoading ? 'Memproses...' : 'Masuk Sekarang' }}
                    </button>
                  </div>

                  <div v-else class="space-y-6">
                    <div class="space-y-4">
                       <input v-model="regForm.name" type="text" placeholder="Nama Lengkap" class="w-full px-6 py-4 bg-slate-50 border border-transparent rounded-2xl focus:border-green-600 focus:bg-white transition-all font-bold text-sm outline-none">
                       <input v-model="regForm.email" type="email" placeholder="Email Anda" class="w-full px-6 py-4 bg-slate-50 border border-transparent rounded-2xl focus:border-green-600 focus:bg-white transition-all font-bold text-sm outline-none">
                       <input v-model="regForm.password" type="password" placeholder="Buat Kata Sandi" class="w-full px-6 py-4 bg-slate-50 border border-transparent rounded-2xl focus:border-green-600 focus:bg-white transition-all font-bold text-sm outline-none">
                    </div>
                    <div v-if="error" class="text-[10px] font-bold text-red-500 uppercase tracking-widest animate-pulse">{{ error }}</div>
                    <button @click="handleRegister" :disabled="isLoading" class="w-full py-5 bg-green-600 text-white rounded-[1.5rem] font-black uppercase tracking-[0.2em] shadow-xl shadow-green-100 hover:bg-green-700 transition active:scale-95 disabled:opacity-50">
                       {{ isLoading ? 'Mendaftar...' : 'Daftar Sekarang' }}
                    </button>
                  </div>

                  <div class="mt-10 pt-6 border-t border-slate-50 text-center">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                       {{ state.authModalMode === 'login' ? 'Belum punya akun?' : 'Sudah punya akun?' }} 
                       <button @click="state.authModalMode = state.authModalMode === 'login' ? 'register' : 'login'" class="text-green-600 font-black hover:underline underline-offset-4 ml-1">
                          {{ state.authModalMode === 'login' ? 'Daftar di sini' : 'Masuk sekarang' }}
                       </button>
                    </p>
                  </div>
               </div>
            </div>
          </div>
        </Transition>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
import { reactive, ref } from 'vue';
import { useAuth } from '../store/auth';
import { useRouter } from 'vue-router';
import api from '../api';

const { state, closeAuthModal, setAuth } = useAuth();
const router = useRouter();

const isLoading = ref(false);
const error = ref('');

const loginForm = reactive({ email: '', password: '' });
const regForm = reactive({ name: '', email: '', password: '' });

const handleGoToForgotPassword = () => {
  closeAuthModal();
  router.push('/forgot-password');
};

const handleLogin = async () => {
  if (!loginForm.email || !loginForm.password) return error.value = 'Mohon isi semua bidang';
  isLoading.value = true;
  error.value = '';
  try {
    const res = await api.post('/auth/login', loginForm);
    setAuth(res.data);
    closeAuthModal();
    router.push('/dashboard');
  } catch (err) {
    error.value = err.response?.data?.message || 'Login gagal';
  } finally {
    isLoading.value = false;
  }
};

const handleRegister = async () => {
  if (!regForm.name || !regForm.email || !regForm.password) return error.value = 'Mohon isi semua bidang';
  isLoading.value = true;
  error.value = '';
  try {
    const res = await api.post('/auth/register', regForm);
    setAuth(res.data);
    closeAuthModal();
    router.push('/dashboard');
  } catch (err) {
    error.value = err.response?.data?.message || 'Pendaftaran gagal';
  } finally {
    isLoading.value = false;
  }
};
</script>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.4s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }

.scale-up-enter-active { transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1); }
.scale-up-enter-from { opacity: 0; transform: scale(0.9) translateY(20px); }
</style>
