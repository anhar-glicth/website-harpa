<template>
  <div class="min-h-screen flex flex-col bg-gray-50">
    <!-- Navbar Global -->
    <Navbar />

    <!-- Area Konten Utama -->
    <main class="flex-grow pb-24 md:pb-0">
      <router-view v-slot="{ Component }">
        <transition name="page-fade" mode="out-in">
          <component :is="Component" />
        </transition>
      </router-view>
    </main>

    <!-- Navigasi Bawah Mobile (Tampilan App) -->
    <MobileBottomNav />

    <!-- Pop-up Login & Register -->
    <AuthModal />

    <!-- Footer Premium -->
    <footer v-if="!isDashboard" class="bg-white border-t border-gray-100 py-12 md:py-20 mt-auto">
      <div class="max-w-7xl mx-auto px-6 text-center space-y-4">
        <div class="flex items-center justify-center gap-3 grayscale opacity-40 mb-6">
           <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
           <span class="text-xl font-black tracking-tighter text-gray-900">HumanityCare</span>
        </div>
        <p class="text-[10px] md:text-sm font-bold text-gray-400 uppercase tracking-[0.3em]">
          &copy; 2026 HumanityCare. Semua Kebaikan Berawal dari Sini.
        </p>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { useRoute } from 'vue-router';
import Navbar from './components/Navbar.vue';
import MobileBottomNav from './components/MobileBottomNav.vue';
import AuthModal from './components/AuthModal.vue';

const route = useRoute();
const isDashboard = computed(() => route.path?.startsWith('/dashboard'));
</script>

<style>
.page-fade-enter-active, .page-fade-leave-active { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
.page-fade-enter-from { opacity: 0; transform: translateY(8px); }
.page-fade-leave-to { opacity: 0; transform: translateY(-8px); }

/* Menghilangkan scrollbar pada elemen tertentu di mobile */
.no-scrollbar::-webkit-scrollbar { display: none; }
.no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>