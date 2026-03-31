import { reactive, computed } from 'vue';

/**
 * State Management sederhana menggunakan Vue Reactive API.
 * Berfungsi untuk menyimpan data pengguna, role, dan token JWT secara terpusat.
 */
const state = reactive({
  user: {
    name: localStorage.getItem('name') || null,
    role: localStorage.getItem('role') || null,
    token: localStorage.getItem('token') || null,
    photo: localStorage.getItem('photo') || null,
  },
  isAuthModalOpen: false,
  authModalMode: 'login' // 'login' atau 'register'
});

/**
 * Getters: Digunakan untuk mengambil data dari state secara reaktif.
 */
const isAuthenticated = computed(() => !!state.user.token);
const getUserRole = computed(() => state.user.role);
const getUserName = computed(() => state.user.name);
const getUserPhoto = computed(() => state.user.photo);

/**
 * Actions: Fungsi untuk mengubah state.
 */

const setAuth = (userData) => {
  state.user.name = userData.name;
  state.user.role = userData.role;
  state.user.token = userData.token || state.user.token;
  state.user.photo = userData.photo || null;

  localStorage.setItem('name', userData.name);
  localStorage.setItem('role', userData.role);
  if (userData.token) localStorage.setItem('token', userData.token);
  if (userData.photo) localStorage.setItem('photo', userData.photo);
  else localStorage.removeItem('photo');
};

const updateUserData = (name, photo) => {
  state.user.name = name;
  state.user.photo = photo;
  localStorage.setItem('name', name);
  if (photo) localStorage.setItem('photo', photo);
  else localStorage.removeItem('photo');
};

const clearAuth = () => {
  state.user.name = null;
  state.user.role = null;
  state.user.token = null;
  state.user.photo = null;

  localStorage.removeItem('name');
  localStorage.removeItem('role');
  localStorage.removeItem('token');
  localStorage.removeItem('photo');
};

const openLogin = () => {
  state.isAuthModalOpen = true;
  state.authModalMode = 'login';
};

const openRegister = () => {
  state.isAuthModalOpen = true;
  state.authModalMode = 'register';
};

const closeAuthModal = () => {
  state.isAuthModalOpen = false;
};

/**
 * Ekspor fungsi useAuth agar bisa digunakan di seluruh komponen Vue.
 */
export const useAuth = () => {
  return {
    state,
    isAuthenticated,
    getUserRole,
    getUserName,
    getUserPhoto,
    setAuth,
    updateUserData,
    clearAuth,
    openLogin,
    openRegister,
    closeAuthModal
  };
};