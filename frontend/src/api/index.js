import axios from 'axios';

/**
 * Membuat instance Axios dengan konfigurasi dasar.
 * Base URL mengarah ke proxy '/api' yang sudah dikonfigurasi di vite.config.js.
 */
const api = axios.create({
  // Gunakan environment variable untuk URL API, atau fallback ke '/api' (proxy di dev, relative di prod)
  baseURL: import.meta.env.VITE_API_URL || '/api',
  headers: {
    'Content-Type': 'application/json'
  }
});

/**
 * Interceptor untuk menyisipkan Token JWT ke setiap permintaan (request).
 * Ini memastikan user tetap terautentikasi saat berdonasi atau membuka dashboard.
 */
api.interceptors.request.use((config) => {
  const token = localStorage.getItem('token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
}, (error) => {
  return Promise.reject(error);
});

/**
 * Interceptor untuk menangani error respons secara global.
 * Jika token kadaluwarsa (403), kita bisa otomatis mengarahkan user ke login.
 */
api.interceptors.response.use((response) => {
  return response;
}, (error) => {
  if (error.response && error.response.status === 403) {
    // Optional: Bersihkan token dan pindah ke login jika token tidak valid
    // localStorage.removeItem('token');
    // window.location.href = '/login';
  }
  return Promise.reject(error);
});

// SANGAT PENTING: Ekspor default agar 'import api from ...' di file .vue bekerja
export default api;