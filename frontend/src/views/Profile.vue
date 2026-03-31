<template>
  <div class="min-h-screen bg-slate-50 pt-24 pb-20">
    <div class="max-w-3xl mx-auto px-4">
      
      <!-- Header -->
      <div class="mb-12 space-y-2 text-center md:text-left animate-in fade-in slide-in-from-top-4 duration-700">
        <h1 class="text-3xl md:text-5xl font-black text-slate-900 tracking-tighter uppercase italic">
          Pengaturan <span class="text-blue-600">Profil</span>
        </h1>
        <p class="text-slate-400 font-bold text-[10px] md:text-xs uppercase tracking-widest leading-relaxed">
          Kelola informasi pribadi dan keamanan akun Anda di satu tempat.
        </p>
      </div>

      <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden animate-in zoom-in-95 duration-500">
        <!-- Banner/Background -->
        <div class="h-32 bg-gradient-to-r from-blue-600 to-blue-400 relative">
           <div class="absolute -bottom-12 left-1/2 -translate-x-1/2 md:left-12 md:translate-x-0">
              <div class="relative group">
                 <div class="w-24 h-24 md:w-32 md:h-32 rounded-[2rem] bg-white p-1.5 shadow-xl">
                    <img 
                      :src="profileData.profile_photo || 'https://ui-avatars.com/api/?name=' + profileData.name + '&background=0D8ABC&color=fff'" 
                      class="w-full h-full object-cover rounded-[1.5rem]" 
                    />
                 </div>
                 <label class="absolute inset-0 flex items-center justify-center bg-black/40 rounded-[1.5rem] opacity-0 group-hover:opacity-100 transition duration-300 cursor-pointer">
                    <input type="file" @change="handlePhotoUpload" class="hidden" accept="image/*" />
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
                 </label>
              </div>
           </div>
        </div>

        <div class="pt-20 p-6 md:p-12 space-y-10">
           <!-- Basic Info -->
           <div class="space-y-6">
              <div class="flex items-center gap-3 mb-2">
                 <span class="w-1.5 h-6 bg-blue-600 rounded-full"></span>
                 <h3 class="text-[11px] font-black uppercase tracking-[0.2em] text-slate-800">Identitas Publik</h3>
              </div>
              
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                 <div class="space-y-2">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Nama Lengkap</label>
                    <input 
                      v-model="profileData.name" 
                      class="w-full p-4 bg-slate-50 rounded-2xl border-2 border-transparent focus:border-blue-600 outline-none font-bold text-xs"
                      placeholder="Nama Anda"
                    />
                 </div>
                 <div class="space-y-2">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Alamat Email / Username</label>
                    <input 
                      v-model="profileData.email" 
                      type="email"
                      class="w-full p-4 bg-slate-50 rounded-2xl border-2 border-transparent focus:border-blue-600 outline-none font-bold text-xs"
                      placeholder="email@anda.com"
                    />
                 </div>
              </div>
           </div>

           <!-- Security -->
           <div class="space-y-6 pt-6 border-t border-slate-50">
              <div class="flex items-center gap-3 mb-2">
                 <span class="w-1.5 h-6 bg-blue-600 rounded-full"></span>
                 <h3 class="text-[11px] font-black uppercase tracking-[0.2em] text-slate-800">Keamanan Akun</h3>
              </div>

              <div class="space-y-2">
                 <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Kata Sandi Baru <span class="text-blue-400 normal-case font-medium italic">(Kosongkan jika tidak diganti)</span></label>
                 <div class="relative">
                    <input 
                      v-model="profileData.password" 
                      :type="showPass ? 'text' : 'password'"
                      class="w-full p-4 bg-slate-50 rounded-2xl border-2 border-transparent focus:border-blue-600 outline-none font-bold text-xs"
                      placeholder="••••••••"
                    />
                    <button @click="showPass = !showPass" class="absolute right-6 top-1/2 -translate-y-1/2 text-slate-400 hover:text-blue-600">
                       <i :class="showPass ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                    </button>
                 </div>
              </div>
           </div>

           <div class="pt-6">
              <button 
                @click="handleSave"
                :disabled="saving"
                class="w-full py-5 bg-blue-600 text-white rounded-[2rem] font-black uppercase tracking-widest shadow-xl shadow-blue-100 hover:bg-blue-700 transition active:scale-95 disabled:opacity-50 flex items-center justify-center gap-3"
              >
                 <svg v-if="saving" class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                 {{ saving ? 'Menyimpan Perubahan...' : 'Simpan Pembaruan Profil' }}
              </button>
           </div>
        </div>
      </div>

      <!-- Quick Stats -->
      <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-4">
         <div class="p-8 bg-white rounded-[2.5rem] border border-slate-100 flex items-center justify-between shadow-sm">
            <div>
               <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Level Akses</p>
               <p class="text-lg font-black text-slate-900 uppercase italic tracking-tighter">{{ profileData.role }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600">
               <i class="fas fa-shield-alt"></i>
            </div>
         </div>
         <div class="p-8 bg-white rounded-[2.5rem] border border-slate-100 flex items-center justify-between shadow-sm">
            <div>
               <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Bergabung Pada</p>
               <p class="text-lg font-black text-slate-900 uppercase italic tracking-tighter">{{ formatDate(profileData.created_at) }}</p>
            </div>
            <div class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-400">
               <i class="fas fa-calendar-alt"></i>
            </div>
         </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import api from '@/api';
import { useAuth } from '@/store/auth';

const { updateUserData } = useAuth();

const profileData = ref({
   name: '',
   email: '',
   password: '',
   profile_photo: '',
   role: '',
   created_at: ''
});

const saving = ref(false);
const showPass = ref(false);

const fetchProfile = async () => {
   try {
      const res = await api.get('/profile');
      profileData.value = { ...res.data, password: '' };
   } catch (e) {
      console.error(e);
   }
};

const handlePhotoUpload = (e) => {
  const file = e.target.files[0];
  if (!file) return;
  
  const reader = new FileReader();
  reader.onload = (event) => {
    profileData.value.profile_photo = event.target.result;
  };
  reader.readAsDataURL(file);
};

const handleSave = async () => {
   saving.value = true;
   try {
      await api.post('/profile/update', {
         name: profileData.value.name,
         email: profileData.value.email,
         password: profileData.value.password || undefined,
         profile_photo: profileData.value.profile_photo
      });
      
      // Update Auth Store reactively!
      updateUserData(profileData.value.name, profileData.value.profile_photo);
      
      alert('Profil Anda berhasil diperbarui!');
      fetchProfile();
   } catch (e) {
      alert('Gagal memperbarui profil.');
   } finally {
      saving.value = false;
   }
};

const formatDate = (str) => {
   if (!str) return '-';
   return new Date(str).toLocaleDateString('id-ID', { month: 'long', year: 'numeric' });
};

onMounted(fetchProfile);
</script>
