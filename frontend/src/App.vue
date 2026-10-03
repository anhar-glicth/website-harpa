<template>
  <div class="min-h-screen flex flex-col bg-white overflow-x-hidden text-slate-900">
    <!-- 3D Background Global -->
    <ThreeBackground :theme="currentTheme" />

    <!-- Navbar Global -->
    <Navbar v-if="!isHome" />

    <!-- Area Konten Utama -->
    <main class="flex-grow pb-24 md:pb-0 relative z-10">
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
    <footer v-if="!isDashboard && !isHome" class="bg-slate-50 border-t border-slate-100 py-12 md:py-20 mt-auto text-slate-900">
      <div class="max-w-7xl mx-auto px-6 text-center space-y-4">
        <div class="flex items-center justify-center gap-3 grayscale opacity-40 mb-6">
           <img src="/logo-icon.png" class="w-6 h-6 object-contain" alt="Amal Abadi" />
           <span class="text-xl font-black tracking-tighter text-slate-900 italic uppercase">Amal Abadi</span>
        </div>
        <p class="text-[10px] md:text-sm font-bold text-slate-400 uppercase tracking-[0.3em]">
          &copy; 2026 Amal Abadi. Semua Kebaikan Berawal dari Sini.
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
import ThreeBackground from './components/ThreeBackground.vue';

const route = useRoute();
const isDashboard = computed(() => route.path?.startsWith('/dashboard'));
const isHome = computed(() => route.path === '/');

const currentTheme = computed(() => {
  const path = route.path;
  if (path === '/news') return 'news';
  if (path === '/zakat') return 'zakat';
  if (path.startsWith('/campaigns')) return 'donation';
  return 'default';
});
</script>

<style>
.page-fade-enter-active, .page-fade-leave-active { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
.page-fade-enter-from { opacity: 0; transform: translateY(8px); }
.page-fade-leave-to { opacity: 0; transform: translateY(-8px); }

/* Menghilangkan scrollbar pada elemen tertentu di mobile */
.no-scrollbar::-webkit-scrollbar { display: none; }
.no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

/* Global Light Theme Page Styling */
body {
  background: #ffffff;
  margin: 0;
  padding: 0;
  color: #0f172a;
}
</style>