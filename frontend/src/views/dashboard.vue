<template>
  <div class="min-h-screen bg-gray-50/50 flex flex-col md:flex-row gap-6 p-4 md:p-8 max-w-7xl mx-auto">
    <!-- Sidebar Kiri -->
    <Sidebar class="flex-shrink-0" />

    <!-- Area Konten Utama -->
    <main class="flex-1 bg-white rounded-3xl border border-gray-100 shadow-sm p-6 md:p-8 min-h-[600px]">
      <!-- Transisi halus antar tampilan dashboard -->
      <transition name="fade" mode="out-in">
        <component :is="activeDashboard" />
      </transition>
    </main>
  </div>
</template>

<script setup>
import { computed, defineAsyncComponent } from 'vue';
import Sidebar from '../components/Sidebar.vue';
import { useAuth } from '../store/auth';

const { getUserRole } = useAuth();

/**
 * Mengambil data role dari store.
 */
const userRole = computed(() => getUserRole.value || 'user');

/**
 * Mengimpor komponen dashboard secara dinamis berdasarkan role.
 */
const AdminDashboard = defineAsyncComponent(() => import('./dashboard/Admin.vue'));
const FieldDashboard = defineAsyncComponent(() => import('./dashboard/Field.vue'));
const UserDashboard = defineAsyncComponent(() => import('./dashboard/User.vue'));

/**
 * Menentukan komponen mana yang harus dirender.
 */
const activeDashboard = computed(() => {
  switch (userRole.value) {
    case 'admin':
      return AdminDashboard;
    case 'field_worker':
      return FieldDashboard;
    default:
      return UserDashboard;
  }
});
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>