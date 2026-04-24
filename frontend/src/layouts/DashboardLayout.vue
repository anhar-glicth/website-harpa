<template>
  <div class="min-h-screen bg-gray-50/50">
    <div class="max-w-7xl mx-auto px-4 py-6 md:py-10">
      <div class="flex flex-col lg:flex-row gap-8 items-start">
        
        <!-- Sidebar: Always persistent -->
        <Sidebar v-if="!isAdmin" class="flex-shrink-0 w-full lg:w-72 sticky top-10" />
        
        <!-- Main Content Area: Changes based on route -->
        <main class="flex-grow w-full">
          <router-view v-slot="{ Component }">
            <transition name="fade-slide" mode="out-in">
              <component :is="Component" />
            </transition>
          </router-view>
        </main>

      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { useRoute } from 'vue-router';
import Sidebar from '@/components/Sidebar.vue';

const route = useRoute();
const isAdmin = computed(() => route.path.startsWith('/dashboard/admin'));
</script>

<style scoped>
.fade-slide-enter-active,
.fade-slide-leave-active {
  transition: all 0.3s ease;
}

.fade-slide-enter-from {
  opacity: 0;
  transform: translateX(20px);
}

.fade-slide-leave-to {
  opacity: 0;
  transform: translateX(-20px);
}
</style>
