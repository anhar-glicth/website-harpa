<template>
  <div class="flex-grow space-y-6 lg:space-y-8 animate-in fade-in slide-in-from-right-4 duration-700 w-full pb-28 lg:pb-0">
        <!-- Header -->
        <!-- Header -->
        <header class="flex flex-col md:flex-row md:items-center justify-between gap-5 bg-white p-5 sm:p-6 md:p-8 rounded-[2rem] md:rounded-[2.5rem] border border-gray-100 shadow-sm relative overflow-hidden">
          <div class="space-y-1.5 relative z-10 text-center md:text-left">
            <div class="flex items-center justify-center md:justify-start gap-3">
              <h1 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight leading-none uppercase italic">Panel <span class="text-green-600">Staff</span></h1>
              <span class="bg-green-600 text-white text-[8px] sm:text-[9px] font-black uppercase px-3 py-1 rounded-full shadow-lg shadow-green-100">Live</span>
            </div>
            <p class="text-[9px] sm:text-[10px] text-gray-400 font-bold uppercase tracking-widest">Manajemen Lapangan & Warta Kebaikan</p>
          </div>
          
          <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
            <button @click="openNewsModal" class="flex-1 sm:flex-none px-6 py-3 bg-green-50 text-green-600 rounded-xl sm:rounded-2xl font-black uppercase text-[9px] sm:text-[10px] tracking-widest hover:bg-green-100 transition-all border border-green-100">Tulis Berita</button>
            <button @click="openCampaignModal" class="flex-1 sm:flex-none px-6 py-3 bg-green-600 text-white rounded-xl sm:rounded-2xl font-black uppercase text-[9px] sm:text-[10px] tracking-widest hover:bg-green-700 transition-all shadow-xl shadow-green-100">Kampanye Baru</button>
            <button @click="handleLogout" class="flex-1 sm:flex-none px-6 py-3 bg-red-50 text-red-500 rounded-xl sm:rounded-2xl font-black uppercase text-[9px] sm:text-[10px] tracking-widest hover:bg-red-100 transition-all border border-red-100">
              <i class="fas fa-power-off"></i>
            </button>
          </div>
        </header>

        <!-- Navigasi Tab Intern (Sticky on mobile) -->
        <div class="sticky top-[64px] lg:top-0 z-30 flex items-center gap-2 bg-white/80 backdrop-blur-md p-1.5 rounded-2xl w-full overflow-x-auto no-scrollbar border border-gray-100 shadow-sm lg:shadow-none">
           <button @click="activeTab = 'campaigns'" :class="['whitespace-nowrap px-5 py-2.5 rounded-xl text-[9px] sm:text-[10px] font-black uppercase tracking-widest transition-all', activeTab === 'campaigns' ? 'bg-green-600 text-white shadow-lg shadow-green-100' : 'text-gray-500 hover:text-green-600']">Proyek Saya</button>
           <button @click="activeTab = 'news'" :class="['whitespace-nowrap px-5 py-2.5 rounded-xl text-[9px] sm:text-[10px] font-black uppercase tracking-widest transition-all', activeTab === 'news' ? 'bg-green-600 text-white shadow-lg shadow-green-100' : 'text-gray-500 hover:text-green-600']">Arsip Berita</button>
           <button @click="activeTab = 'todo'" :class="['whitespace-nowrap px-5 py-2.5 rounded-xl text-[9px] sm:text-[10px] font-black uppercase tracking-widest transition-all', activeTab === 'todo' ? 'bg-green-600 text-white shadow-lg shadow-green-100' : 'text-gray-500 hover:text-green-600']">To-Do List</button>
           <button @click="activeTab = 'attendance'" :class="['whitespace-nowrap px-5 py-2.5 rounded-xl text-[9px] sm:text-[10px] font-black uppercase tracking-widest transition-all', activeTab === 'attendance' ? 'bg-green-600 text-white shadow-lg shadow-green-100' : 'text-gray-500 hover:text-green-600']">Absensi</button>
           <button @click="activeTab = 'stats'" :class="['whitespace-nowrap px-5 py-2.5 rounded-xl text-[9px] sm:text-[10px] font-black uppercase tracking-widest transition-all', activeTab === 'stats' ? 'bg-green-600 text-white shadow-lg shadow-green-100' : 'text-gray-500 hover:text-green-600']">Statistik</button>
        </div>

        <!-- Tab: Campaigns -->
        <div v-if="activeTab === 'campaigns'" class="space-y-8">
           <div v-if="loading" class="py-20 text-center bg-white rounded-[2.5rem] border border-dashed border-gray-200">
              <div class="animate-spin h-8 w-8 border-4 border-green-600 border-t-transparent rounded-full mx-auto mb-4"></div>
              <p class="text-gray-400 font-black text-[10px] uppercase tracking-widest">Sinkronisasi Proyek...</p>
           </div>
           
           <div v-else-if="myCampaigns.length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-8">
              <div v-for="c in myCampaigns" :key="c.id" class="group bg-white rounded-[2.5rem] border border-gray-100 overflow-hidden shadow-sm hover:shadow-xl transition-all duration-500">
                 <div class="relative h-48 overflow-hidden">
                    <img :src="c.foto || 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=600'" class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                    <span class="absolute top-6 left-6 px-3 py-1 bg-white/20 backdrop-blur-md text-white text-[9px] font-black uppercase rounded-full border border-white/20">{{ c.category || 'Umum' }}</span>
                 </div>
                 <div class="p-8">
                    <h3 class="font-black text-gray-900 uppercase italic mb-4 line-clamp-1 group-hover:text-green-600 transition-colors">{{ c.title }}</h3>
                    <div class="flex justify-between items-end border-t border-gray-50 pt-4">
                       <div class="space-y-1">
                          <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Status Verifikasi</p>
                          <span :class="['text-[10px] font-black uppercase px-3 py-1 rounded-lg', c.status === 'approved' ? 'bg-green-50 text-green-600' : 'bg-amber-50 text-amber-600']">{{ c.status }}</span>
                       </div>
                       <div class="text-right">
                          <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Terkumpul</p>
                          <p class="font-black text-green-600 text-lg tracking-tighter italic">Rp {{ formatCurrency(c.current_amount) }}</p>
                       </div>
                    </div>
                    <!-- Update Button -->
                    <button @click="openUpdateModal(c)" class="w-full mt-6 py-3.5 bg-slate-900 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-green-600 transition-all shadow-lg active:scale-95">Kirim Kabar Terbaru</button>
                 </div>
              </div>
           </div>

           <div v-else class="py-20 text-center bg-white rounded-[3rem] border-2 border-dashed border-gray-100">
              <p class="text-gray-400 font-bold italic uppercase tracking-widest text-xs">Belum ada proyek yang Anda buat.</p>
           </div>
        </div>

        <!-- Tab: News -->
        <div v-if="activeTab === 'news'" class="space-y-8">
           <div v-if="myNews.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
              <div v-for="n in myNews" :key="n.id" class="bg-white p-6 rounded-[2.5rem] border border-gray-100 shadow-sm hover:shadow-md transition-all group">
                 <div class="relative h-40 rounded-[1.5rem] overflow-hidden mb-6">
                    <img :src="n.image" class="w-full h-full object-cover">
                    <button @click="handleDeleteNews(n.id)" class="absolute top-4 right-4 p-2 bg-red-500/20 backdrop-blur-md text-red-100 rounded-xl opacity-0 group-hover:opacity-100 transition-all hover:bg-red-500 hover:text-white">✕</button>
                 </div>
                 <p class="text-[8px] font-black text-green-600 uppercase tracking-[0.2em] mb-2">{{ formatDate(n.created_at) }}</p>
                 <h4 class="font-black text-gray-900 uppercase italic text-sm line-clamp-1 mb-4">{{ n.title }}</h4>
                 <p class="text-[10px] text-gray-400 font-medium line-clamp-3 leading-relaxed mb-6">{{ n.content }}</p>
              </div>
           </div>
           <div v-else class="py-20 text-center bg-white rounded-[3rem] border border-dashed border-gray-100">
              <p class="text-gray-400 font-bold italic uppercase tracking-widest text-xs">Belum ada berita yang Anda terbitkan.</p>
           </div>
        </div>

        <!-- Tab: To Do List -->
        <div v-if="activeTab === 'todo'" class="space-y-6">
           <div class="bg-white p-8 rounded-[3rem] border border-gray-100 shadow-sm space-y-6">
              <div class="flex justify-between items-center">
                 <h3 class="font-black text-gray-900 uppercase tracking-tighter italic">Daftar <span class="text-green-600">Tugas</span></h3>
                 <div class="flex items-center gap-3">
                    <span class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em]">{{ todos.filter(t => t.status === 'completed').length }}/{{ todos.length }} Selesai</span>
                    <div class="w-20 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                       <div class="h-full bg-green-500 rounded-full transition-all duration-700" :style="{ width: todos.length ? (todos.filter(t => t.status === 'completed').length / todos.length * 100) + '%' : '0%' }"></div>
                    </div>
                 </div>
              </div>
              
              <!-- Form Tambah Tugas -->
              <div class="bg-gray-50 rounded-[2rem] p-5 space-y-3 border border-gray-100">
                 <p class="text-[9px] font-black uppercase tracking-widest text-gray-400">Tambah Tugas Baru</p>
                 <input v-model="newTodo" @keyup.enter="handleAddTodo" placeholder="Nama tugas..." class="w-full p-4 bg-white rounded-2xl outline-none focus:ring-2 focus:ring-green-500 font-black text-xs transition-all border border-gray-100 uppercase italic">
                 <textarea v-model="newTodoDescription" placeholder="Catatan tambahan / Deskripsi tugas..." class="w-full p-3 bg-white rounded-2xl outline-none focus:ring-2 focus:ring-green-500 font-medium text-[10px] transition-all border border-gray-100 text-gray-500 h-20 resize-none"></textarea>
                 <div class="flex flex-col md:flex-row gap-3 items-end">
                    <div class="flex-grow w-full">
                       <label class="text-[8px] font-black uppercase tracking-[0.2em] text-gray-400 mb-1.5 block ml-1">Tenggat Waktu Pengerjaan</label>
                       <input v-model="newTodoDeadline" type="datetime-local" class="w-full p-3.5 bg-white rounded-2xl outline-none focus:ring-2 focus:ring-green-500 font-black text-[10px] transition-all border border-gray-100">
                    </div>
                    <button @click="handleAddTodo" class="w-full md:w-auto px-8 py-3.5 bg-green-600 text-white rounded-2xl font-black uppercase text-[10px] tracking-widest hover:bg-green-700 transition-all shadow-xl shadow-green-100 shrink-0 mb-0.5">
                       Simpan Tugas
                    </button>
                 </div>
              </div>

              <div class="space-y-3">
                 <div v-for="t in todos" :key="t.id" :class="['p-5 rounded-[2rem] border transition-all group', t.status === 'completed' ? 'bg-green-50/30 border-green-100' : 'bg-gray-50/50 border-transparent hover:border-green-100 hover:bg-white']">
                    <div class="flex items-start justify-between gap-4">
                       <div class="flex gap-3 flex-1">
                          <!-- Toggle Button -->
                          <button @click="handleToggleTodo(t.id)" :class="['w-9 h-9 rounded-xl flex items-center justify-center shrink-0 shadow-sm transition-all hover:scale-110', t.status === 'completed' ? 'bg-green-600 text-white' : 'bg-white text-gray-300 border-2 border-gray-200 hover:border-green-400']">
                             <i :class="t.status === 'completed' ? 'fas fa-check text-xs' : 'fas fa-circle text-[8px]'"></i>
                          </button>
                          <div class="space-y-1 flex-1">
                             <h4 :class="['font-black text-xs uppercase tracking-tight', t.status === 'completed' ? 'text-gray-300 line-through' : 'text-gray-700']">{{ t.task }}</h4>
                             <p v-if="t.description" class="text-[10px] text-gray-400 font-medium">{{ t.description }}</p>
                             <div class="flex items-center gap-3 flex-wrap pt-1">
                                <span v-if="t.deadline" :class="['text-[8px] font-black uppercase tracking-widest flex items-center gap-1', isOverdue(t) ? 'text-red-500' : 'text-amber-500']">
                                   <i class="fas fa-clock"></i>
                                   {{ formatDeadline(t.deadline) }}
                                   <span v-if="isOverdue(t)" class="bg-red-100 text-red-500 px-1.5 py-0.5 rounded-full">Terlambat</span>
                                </span>
                                <span v-if="t.assigned_by" class="text-[8px] font-black uppercase tracking-widest text-green-600 bg-green-50 px-2 py-0.5 rounded-full">Admin Assigned</span>
                             </div>
                          </div>
                       </div>
                       
                       <div class="flex flex-col gap-1.5 items-end">
                          <button v-if="t.status !== 'completed'" @click="openTaskResultModal(t)" class="px-4 py-2.5 bg-green-600 text-white rounded-xl text-[9px] font-black uppercase tracking-widest hover:scale-105 transition-all shadow-lg shadow-green-100">
                             Upload Bukti
                          </button>
                          <div v-else class="flex flex-col items-end gap-1">
                             <span class="text-[9px] font-black uppercase tracking-widest text-green-600 flex items-center gap-1"><i class="fas fa-check-double"></i> Selesai</span>
                             <a v-if="t.result_attachment" :href="t.result_attachment" target="_blank" class="text-[8px] font-black uppercase text-blue-500 hover:underline">Lihat Bukti</a>
                          </div>
                          <button v-if="!t.assigned_by" @click="handleDeleteTodo(t.id)" class="opacity-0 group-hover:opacity-100 text-red-400 hover:text-red-600 transition-all text-[9px] font-bold uppercase mt-1">Hapus</button>
                       </div>
                    </div>
                    <!-- Display Note/Result if exist -->
                    <div v-if="t.status === 'completed' && t.result_note" class="mt-4 p-4 bg-green-50/50 rounded-2xl border border-green-100/50">
                       <p class="text-[8px] font-black uppercase text-green-600 tracking-widest mb-1 italic">Catatan Hasil Pengerjaan:</p>
                       <p class="text-[10px] text-gray-600 font-medium italic">"{{ t.result_note }}"</p>
                    </div>
                 </div>
                 <div v-if="todos.length === 0" class="py-12 text-center">
                    <p class="text-gray-400 font-bold italic uppercase tracking-widest text-[10px]">Belum ada tugas. Tambahkan tugas baru di atas!</p>
                 </div>
              </div>
           </div>
        </div>

        <!-- Tab: Absensi -->
        <div v-if="activeTab === 'attendance'" class="space-y-8">
           <!-- Kartu Absensi Hari Ini -->
           <div class="bg-white p-10 rounded-[3.5rem] border border-gray-100 shadow-2xl shadow-green-900/5 text-center space-y-10 relative overflow-hidden">
              <div class="absolute -right-20 -top-20 w-64 h-64 bg-green-600/5 rounded-full blur-3xl animate-pulse"></div>
              <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-emerald-600/5 rounded-full blur-3xl animate-pulse"></div>
              
              <div class="space-y-3 relative z-10">
                 <div class="inline-flex items-center gap-2 bg-green-50 px-4 py-2 rounded-full mb-2">
                    <span class="w-2 h-2 bg-green-600 rounded-full animate-ping"></span>
                    <p class="text-[10px] font-black text-green-600 uppercase tracking-widest">Sistem Absensi Digital</p>
                 </div>
                 <h3 class="text-4xl font-black text-gray-900 uppercase tracking-tighter italic leading-none">Presensi <span class="text-green-600">Kehadiran</span></h3>
                 <p class="text-[11px] font-bold text-gray-400 uppercase tracking-[0.3em]">{{ todayDate }}</p>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 relative z-10">
                 <div v-for="item in attendanceTypes" :key="item.type" 
                   :class="['group p-8 rounded-[2.5rem] border transition-all duration-500 hover:scale-[1.02]', 
                   getAttendanceClass(item.type)]">
                    <div :class="['w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-6 text-xl shadow-lg transition-transform group-hover:rotate-12', item.iconClass]">
                       <i :class="item.icon"></i>
                    </div>
                    <div class="space-y-1 mb-6">
                       <p class="text-[10px] font-black opacity-60 uppercase tracking-widest">{{ item.label }}</p>
                       <p class="text-lg font-black italic tracking-tight">{{ getAttendanceTime(item.type) }}</p>
                    </div>
                    <button 
                      @click="item.action()" 
                      :disabled="isButtonDisabled(item.type)" 
                      :class="['w-full py-4 rounded-2xl font-black uppercase text-[10px] tracking-widest transition-all shadow-xl active:scale-95', getButtonClass(item.type)]">
                       {{ getButtonLabel(item.type) }}
                    </button>
                 </div>
              </div>

              <div class="pt-10 border-t border-gray-100 space-y-3">
                 <div class="flex items-center justify-center gap-2 text-green-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <p class="text-[10px] font-black uppercase tracking-[0.2em]">{{ currentLocationName || 'Mendeteksi Lokasi...' }}</p>
                 </div>
                 <p v-if="coords" class="text-[8px] text-gray-300 font-mono tracking-widest">GPS: {{ coords.latitude.toFixed(6) }}, {{ coords.longitude.toFixed(6) }}</p>
              </div>
           </div>

           <!-- Riwayat Absensi -->
           <div class="bg-white rounded-[3rem] border border-gray-100 shadow-sm overflow-hidden">
              <!-- Header + Filter -->
              <div class="p-6 border-b border-gray-50 bg-gray-50/30 space-y-4">
                 <div class="flex flex-wrap justify-between items-center gap-4">
                    <h3 class="font-black text-gray-900 uppercase tracking-tighter italic">Riwayat <span class="text-green-600">Presensi</span></h3>
                    <div class="flex items-center gap-2 flex-wrap">
                       <button @click="downloadAttendanceCSV" class="flex items-center gap-2 px-4 py-1.5 bg-green-600 text-white rounded-full text-[9px] font-black uppercase tracking-widest hover:bg-green-700 transition-all shadow-lg shadow-green-100 active:scale-95">
                         <i class="fas fa-download text-[8px]"></i> Download CSV
                       </button>
                    </div>
                 </div>
                 <!-- Filter Row -->
                 <div class="flex flex-wrap gap-2 items-center">
                    <span class="text-[9px] font-black uppercase tracking-widest text-gray-400"><i class="fas fa-filter mr-1"></i>Filter:</span>
                    <!-- Tahun -->
                    <select v-model="filterYear" class="px-3 py-2 bg-white border border-gray-200 rounded-xl text-[10px] font-black text-gray-700 outline-none focus:ring-2 focus:ring-green-400 cursor-pointer transition-all">
                       <option value="">Semua Tahun</option>
                       <option v-for="y in availableYears" :key="y" :value="String(y)">{{ y }}</option>
                    </select>
                    <!-- Bulan -->
                    <select v-model="filterMonth" class="px-3 py-2 bg-white border border-gray-200 rounded-xl text-[10px] font-black text-gray-700 outline-none focus:ring-2 focus:ring-green-400 cursor-pointer transition-all">
                       <option value="">Semua Bulan</option>
                       <option value="1">Januari</option><option value="2">Februari</option>
                       <option value="3">Maret</option><option value="4">April</option>
                       <option value="5">Mei</option><option value="6">Juni</option>
                       <option value="7">Juli</option><option value="8">Agustus</option>
                       <option value="9">September</option><option value="10">Oktober</option>
                       <option value="11">November</option><option value="12">Desember</option>
                    </select>
                    <!-- Tanggal -->
                    <select v-model="filterDay" class="px-3 py-2 bg-white border border-gray-200 rounded-xl text-[10px] font-black text-gray-700 outline-none focus:ring-2 focus:ring-green-400 cursor-pointer transition-all">
                       <option value="">Semua Tanggal</option>
                       <option v-for="d in 31" :key="d" :value="String(d)">{{ d }}</option>
                    </select>
                    <!-- Reset -->
                    <button v-if="filterMonth || filterYear || filterDay" @click="resetFilter" class="px-3 py-2 bg-red-50 text-red-500 rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-red-100 transition-all border border-red-100">
                       <i class="fas fa-times mr-1"></i>Reset
                    </button>
                 </div>
              </div>
              <div class="overflow-x-auto">
                 <table class="w-full text-left">
                    <thead class="bg-gray-50/50 text-[10px] uppercase font-black text-gray-400 tracking-widest text-center">
                       <tr>
                          <th class="px-8 py-5 text-left">Tanggal</th>
                          <th class="px-8 py-5">Masuk (Jam/Lokasi)</th>
                          <th class="px-8 py-5">Pulang (Jam/Lokasi)</th>
                          <th class="px-8 py-5">Keterangan</th>
                       </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 text-center">
                       <tr v-for="group in groupedAttendance" :key="group.date" class="hover:bg-green-50/20 transition-colors group">
                          <td class="px-8 py-6 font-bold text-gray-800 text-xs text-left">{{ group.date }}</td>
                          
                          <!-- Kolom Masuk -->
                          <td class="px-8 py-6">
                             <div v-if="group.check_in" class="space-y-1">
                                <div class="flex items-center justify-center gap-2">
                                   <span class="text-[11px] font-black text-green-600">{{ formatTime(group.check_in) }}</span>
                                   <a v-if="group.check_in.attachment" :href="group.check_in.attachment" target="_blank" class="text-blue-500 hover:scale-110 transition-all"><i class="fas fa-paperclip text-[10px]"></i></a>
                                </div>
                                <p class="text-[9px] text-gray-400 italic line-clamp-1 max-w-[150px] mx-auto" :title="group.check_in.location">{{ group.check_in.location }}</p>
                             </div>
                             <span v-else class="text-[10px] text-gray-200 font-black italic">--:--</span>
                          </td>

                          <!-- Kolom Pulang -->
                          <td class="px-8 py-6 border-l border-gray-50">
                             <div v-if="group.check_out" class="space-y-1">
                                <div class="flex items-center justify-center gap-2">
                                   <span class="text-[11px] font-black text-red-500">{{ formatTime(group.check_out) }}</span>
                                   <a v-if="group.check_out.attachment" :href="group.check_out.attachment" target="_blank" class="text-blue-500 hover:scale-110 transition-all"><i class="fas fa-paperclip text-[10px]"></i></a>
                                </div>
                                <p class="text-[9px] text-gray-400 italic line-clamp-1 max-w-[150px] mx-auto" :title="group.check_out.location">{{ group.check_out.location }}</p>
                             </div>
                             <span v-else class="text-[10px] text-gray-200 font-black italic">--:--</span>
                          </td>

                          <!-- Kolom Lainnya (Sakit/Izin) -->
                          <td class="px-8 py-6 border-l border-gray-50">
                             <div v-if="group.other" class="flex flex-col items-center gap-1">
                                <span :class="['px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest shadow-sm', getStatusBadgeClass(group.other.type)]">
                                   {{ group.other.type }}
                                </span>
                                <a v-if="group.other.attachment" :href="group.other.attachment" target="_blank" class="text-[8px] font-black text-blue-500 hover:underline uppercase tracking-widest">Lihat Dokumen</a>
                             </div>
                             <span v-else class="text-[10px] text-gray-200 font-black italic">Normal</span>
                          </td>
                       </tr>
                       <tr v-if="groupedAttendance.length === 0">
                          <td colspan="4" class="px-8 py-20 text-center text-gray-400 font-bold uppercase tracking-widest text-xs italic">Belum ada riwayat presensi.</td>
                       </tr>
                    </tbody>
                 </table>
              </div>
           </div>
        </div>

        <!-- Tab: Statistik Kinerja -->
        <div v-if="activeTab === 'stats'" class="space-y-8 animate-in zoom-in duration-500">
           <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
              <div v-for="s in statCards" :key="s.label" class="bg-white p-8 rounded-[3rem] border border-gray-100 shadow-sm hover:shadow-xl transition-all group overflow-hidden relative">
                 <div class="absolute -right-4 -top-4 w-24 h-24 bg-green-50 rounded-full blur-2xl group-hover:bg-green-100 transition-colors"></div>
                 <div class="relative z-10 space-y-4">
                    <div class="w-12 h-12 bg-green-50 text-green-600 rounded-2xl flex items-center justify-center text-xl shadow-sm group-hover:scale-110 transition-transform">
                       <i :class="s.icon"></i>
                    </div>
                    <div class="space-y-1">
                       <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">{{ s.label }}</p>
                       <p class="text-2xl font-black text-gray-900 italic tracking-tighter">{{ s.value }}</p>
                    </div>
                 </div>
              </div>
           </div>

           <!-- Pesan Motivasi -->
           <div class="bg-slate-900 p-12 rounded-[4rem] text-white relative overflow-hidden group">
              <div class="absolute inset-0 bg-gradient-to-br from-green-600/30 via-transparent to-transparent"></div>
              <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-10">
                 <div class="space-y-6 text-center md:text-left">
                    <div class="inline-block px-4 py-1 bg-green-500/20 border border-green-500/30 rounded-full text-[10px] font-black uppercase tracking-widest text-green-400">Pahlawan Kebaikan</div>
                    <h3 class="text-4xl font-black uppercase italic tracking-tighter leading-tight">Terus Tebarkan <br><span class="text-green-500">Kebaikan!</span></h3>
                    <p class="text-sm text-gray-400 font-medium max-w-sm leading-relaxed">Setiap langkah Anda di lapangan adalah harapan bagi mereka. Pertahankan kinerja terbaik Anda untuk umat.</p>
                 </div>
                 <div class="p-12 bg-white/5 backdrop-blur-3xl rounded-[3.5rem] border border-white/10 text-center space-y-3 shadow-2xl shadow-black/20">
                    <p class="text-[10px] font-black uppercase tracking-widest text-green-400">Efektivitas Kerja</p>
                    <p class="text-5xl font-black italic tracking-tighter text-white">{{ fieldStats.effectiveness || 0 }}<span class="text-2xl text-green-500">%</span></p>
                    <div class="w-40 h-2 bg-white/10 rounded-full mx-auto overflow-hidden">
                       <div class="h-full bg-green-500 shadow-[0_0_10px_rgba(16,185,129,0.5)] transition-all duration-1000" :style="{ width: (fieldStats.effectiveness || 0) + '%' }"></div>
                    </div>
                    <p class="text-[9px] font-bold text-gray-500 uppercase tracking-widest pt-2">
                      {{ fieldStats.effectiveness >= 90 ? 'Sangat Baik' : fieldStats.effectiveness >= 75 ? 'Baik' : fieldStats.effectiveness >= 50 ? 'Cukup' : 'Perlu Ditingkatkan' }}
                    </p>
                 </div>
              </div>
           </div>
        </div>



    <!-- Modal: Buat Kampanye -->
    <Teleport to="body">
       <Transition name="fade">
          <div v-if="showCampaignModal" class="fixed inset-0 z-[200] flex items-center justify-center p-4">
             <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-md" @click="showCampaignModal = false"></div>
             <div class="relative bg-white rounded-[3rem] shadow-2xl w-full max-w-xl max-h-[90vh] overflow-hidden flex flex-col animate-in zoom-in duration-300">
                <div class="p-8 bg-green-600 text-white flex justify-between items-center shrink-0">
                   <h3 class="text-xl font-black uppercase tracking-tighter italic">Buat <span class="opacity-60">Kampanye</span></h3>
                   <button @click="showCampaignModal = false" class="text-white/60 hover:text-white transition-colors">✕</button>
                </div>
                
                <form @submit.prevent="handleCreateCampaign" class="p-8 space-y-6 overflow-y-auto no-scrollbar">
                   <div class="space-y-4">
                      <div class="space-y-1">
                         <label class="text-[10px] font-black uppercase text-gray-400 ml-2 tracking-widest">Informasi Dasar</label>
                         <input v-model="form.title" placeholder="Judul Kampanye" class="w-full p-4 bg-gray-50 rounded-2xl outline-none focus:ring-2 focus:ring-green-600 font-bold transition-all" required>
                         <textarea v-model="form.description" placeholder="Ceritakan urgensi kampanye ini..." class="w-full h-32 p-4 bg-gray-50 rounded-2xl outline-none focus:ring-2 focus:ring-green-600 font-medium resize-none transition-all" required></textarea>
                      </div>

                      <div class="grid grid-cols-2 gap-4">
                         <div class="space-y-1">
                            <label class="text-[10px] font-black uppercase text-gray-400 ml-2 tracking-widest">Kategori</label>
                            <select v-model="form.category" class="w-full p-4 bg-gray-50 rounded-2xl font-black uppercase text-[10px] tracking-widest">
                               <option v-for="c in categories" :key="c" :value="c">{{ c }}</option>
                            </select>
                         </div>
                         <div class="space-y-1">
                            <label class="text-[10px] font-black uppercase text-gray-400 ml-2 tracking-widest">Target Dana</label>
                            <input v-model="form.target_amount" type="number" placeholder="Rp 0" class="w-full p-4 bg-gray-50 rounded-2xl outline-none focus:ring-2 focus:ring-green-600 font-black" required>
                         </div>
                      </div>

                      <div class="space-y-2">
                         <label class="text-[10px] font-black uppercase text-gray-400 ml-2 tracking-widest">Foto Utama Kampanye</label>
                         <div 
                           class="relative w-full h-40 bg-gray-50 border-2 border-dashed border-gray-200 rounded-[2rem] flex flex-col items-center justify-center overflow-hidden group cursor-pointer hover:bg-gray-100 transition-all"
                           @click="$refs.campaignFileInput.click()"
                         >
                            <img v-if="form.foto" :src="form.foto" class="w-full h-full object-cover">
                            <div v-else class="text-center p-4">
                               <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-gray-300 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
                               <p class="text-[9px] font-black uppercase text-gray-400 tracking-widest leading-none">Klik untuk Unggah Foto</p>
                            </div>
                         </div>
                         <input ref="campaignFileInput" type="file" @change="e => handleFileChange(e, 'cam')" class="hidden" accept="image/*">
                      </div>
                   </div>

                   <button type="submit" :disabled="submitting" class="w-full py-5 bg-green-600 text-white rounded-[1.5rem] font-black uppercase tracking-[0.2em] shadow-xl shadow-green-100 hover:bg-green-700 transition active:scale-95 disabled:opacity-50">
                      {{ submitting ? 'Memproses...' : 'Publikasikan Sekarang' }}
                   </button>
                </form>
             </div>
          </div>
       </Transition>
    </Teleport>

    <!-- Modal: Tulis Berita -->
    <Teleport to="body">
       <Transition name="fade">
          <div v-if="showNewsModal" class="fixed inset-0 z-[200] flex items-center justify-center p-4">
             <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-md" @click="showNewsModal = false"></div>
             <div class="relative bg-white rounded-[3rem] shadow-2xl w-full max-w-xl max-h-[90vh] overflow-hidden flex flex-col animate-in zoom-in duration-300">
                <div class="p-8 bg-green-600 text-white flex justify-between items-center shrink-0">
                   <h3 class="text-xl font-black uppercase tracking-tighter italic">Kabar <span class="opacity-60">Lapangan</span></h3>
                   <button @click="showNewsModal = false" class="text-white/60 hover:text-white transition-colors">✕</button>
                </div>
                
                <form @submit.prevent="handleCreateNews" class="p-8 space-y-6 overflow-y-auto no-scrollbar">
                   <div class="space-y-4">
                      <div class="space-y-1">
                         <label class="text-[10px] font-black uppercase text-gray-400 ml-2 tracking-widest">Judul & Isi Berita</label>
                         <input v-model="newsForm.title" placeholder="Judul Berita" class="w-full p-4 bg-gray-50 rounded-2xl outline-none focus:ring-2 focus:ring-green-600 font-bold transition-all" required>
                         <textarea v-model="newsForm.content" placeholder="Deskripsikan perkembangan terbaru di lapangan..." class="w-full h-48 p-4 bg-gray-50 rounded-2xl outline-none focus:ring-2 focus:ring-green-600 font-medium resize-none transition-all" required></textarea>
                      </div>

                      <div class="space-y-2">
                         <label class="text-[10px] font-black uppercase text-gray-400 ml-2 tracking-widest">Foto Dokumentasi</label>
                         <div 
                           class="relative w-full h-40 bg-gray-50 border-2 border-dashed border-gray-200 rounded-[2rem] flex flex-col items-center justify-center overflow-hidden group cursor-pointer hover:bg-gray-100 transition-all"
                           @click="$refs.newsFileInput.click()"
                         >
                            <img v-if="newsForm.image" :src="newsForm.image" class="w-full h-full object-cover">
                            <div v-else class="text-center p-4">
                               <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-gray-300 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                               <p class="text-[9px] font-black uppercase text-gray-400 tracking-widest leading-none">Klik untuk Unggah Dokumentasi</p>
                            </div>
                         </div>
                         <input ref="newsFileInput" type="file" @change="e => handleFileChange(e, 'news')" class="hidden" accept="image/*">
                      </div>
                   </div>

                   <button type="submit" :disabled="submitting" class="w-full py-5 bg-green-600 text-white rounded-[1.5rem] font-black uppercase tracking-[0.2em] shadow-xl shadow-green-100 hover:bg-green-700 transition active:scale-95 disabled:opacity-50">
                      {{ submitting ? 'Menerbitkan...' : 'Terbitkan Berita' }}
                   </button>
                </form>
             </div>
          </div>
       </Transition>
    </Teleport>
    <!-- Modal: Sakit / Izin -->
    <Teleport to="body">
       <Transition name="fade">
          <div v-if="showLeaveModal" class="fixed inset-0 z-[200] flex items-center justify-center p-4">
             <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-md" @click="showLeaveModal = false"></div>
             <div class="relative bg-white rounded-[3rem] shadow-2xl w-full max-w-lg overflow-hidden animate-in zoom-in duration-300">
                <div :class="['p-8 text-white flex justify-between items-center', leaveType === 'sakit' ? 'bg-amber-500' : 'bg-emerald-500']">
                   <h3 class="text-xl font-black uppercase tracking-tighter italic">Form Keterangan <span class="opacity-60">{{ leaveType }}</span></h3>
                   <button @click="showLeaveModal = false" class="text-white/60 hover:text-white transition-colors">✕</button>
                </div>
                
                <div class="p-8 space-y-6">
                   <div class="space-y-4">
                      <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest text-center">Harap unggah surat keterangan resmi</p>
                      
                      <div 
                        class="relative w-full h-48 bg-gray-50 border-2 border-dashed border-gray-200 rounded-[2rem] flex flex-col items-center justify-center overflow-hidden group cursor-pointer hover:bg-gray-100 transition-all"
                        @click="$refs.leaveFileInput.click()"
                      >
                         <img v-if="leaveForm.attachment" :src="leaveForm.attachment" class="w-full h-full object-cover">
                         <div v-else class="text-center p-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-gray-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2z"/></svg>
                            <p class="text-[10px] font-black uppercase text-gray-400 tracking-widest">Upload Surat Keterangan</p>
                         </div>
                      </div>
                      <input ref="leaveFileInput" type="file" @change="handleLeaveFileChange" class="hidden" accept="image/*,application/pdf">
                   </div>

                   <button 
                     @click="handleAttendance(leaveType)" 
                     :disabled="!leaveForm.attachment || submitting" 
                     :class="['w-full py-5 rounded-[1.5rem] font-black uppercase tracking-widest text-[11px] transition-all', leaveType === 'sakit' ? 'bg-amber-500 text-white shadow-amber-100' : 'bg-emerald-500 text-white shadow-emerald-100', (!leaveForm.attachment || submitting) ? 'opacity-50' : 'hover:scale-[1.02] active:scale-95 shadow-xl']"
                   >
                      {{ submitting ? 'Mengirim...' : 'Kirim Sekarang' }}
                   </button>
                </div>
             </div>
          </div>
       </Transition>
    </Teleport>
    <!-- Modal: Kabar Terbaru (Impact Stories) -->
    <Teleport to="body">
       <Transition name="fade">
          <div v-if="showUpdateModal" class="fixed inset-0 z-[200] flex items-center justify-center p-4">
             <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-md" @click="showUpdateModal = false"></div>
             <div class="relative bg-white rounded-[3rem] shadow-2xl w-full max-w-xl max-h-[90vh] overflow-hidden flex flex-col animate-in zoom-in duration-300">
                <div class="p-8 bg-slate-900 text-white flex justify-between items-center shrink-0">
                   <div class="space-y-1">
                      <h3 class="text-xl font-black uppercase tracking-tighter italic">Update <span class="text-green-500">Dampak</span></h3>
                      <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">{{ selectedCampaign?.title }}</p>
                   </div>
                   <button @click="showUpdateModal = false" class="text-white/60 hover:text-white transition-colors">✕</button>
                </div>
                
                <form @submit.prevent="handleCreateUpdate" class="p-8 space-y-6 overflow-y-auto no-scrollbar">
                   <div class="space-y-4">
                      <div class="space-y-1">
                         <label class="text-[10px] font-black uppercase text-gray-400 ml-2 tracking-widest">Informasi Update</label>
                         <input v-model="updateForm.title" placeholder="Judul Update (Misal: Penyaluran Tahap 1)" class="w-full p-4 bg-gray-50 rounded-2xl outline-none focus:ring-2 focus:ring-green-600 font-bold transition-all" required>
                         <textarea v-model="updateForm.content" placeholder="Deskripsikan apa yang terjadi di lapangan secara detail..." class="w-full h-32 p-4 bg-gray-50 rounded-2xl outline-none focus:ring-2 focus:ring-green-600 font-medium resize-none transition-all" required></textarea>
                      </div>

                      <div class="space-y-2">
                         <label class="text-[10px] font-black uppercase text-gray-400 ml-2 tracking-widest">Foto Bukti Penyaluran</label>
                         <div 
                           class="relative w-full h-40 bg-gray-50 border-2 border-dashed border-gray-200 rounded-[2rem] flex flex-col items-center justify-center overflow-hidden group cursor-pointer hover:bg-gray-100 transition-all"
                           @click="$refs.updateFileInput.click()"
                         >
                            <img v-if="updateForm.image" :src="updateForm.image" class="w-full h-full object-cover">
                            <div v-else class="text-center p-4">
                               <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-gray-300 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                               <p class="text-[9px] font-black uppercase text-gray-400 tracking-widest">Upload Foto Dokumentasi</p>
                            </div>
                         </div>
                         <input ref="updateFileInput" type="file" @change="handleUpdateFileChange" class="hidden" accept="image/*">
                      </div>
                   </div>

                   <button type="submit" :disabled="submitting" class="w-full py-5 bg-green-600 text-white rounded-[1.5rem] font-black uppercase tracking-[0.2em] shadow-xl shadow-green-100 hover:bg-green-700 transition active:scale-95 disabled:opacity-50">
                      {{ submitting ? 'Mengirim...' : 'Kirim Kabar Baik' }}
                   </button>
                </form>
             </div>
          </div>
       </Transition>
    </Teleport>

     <!-- Modal: Upload Hasil Tugas -->
     <Teleport to="body">
        <Transition name="fade">
           <div v-if="showTaskResultModal" class="fixed inset-0 z-[200] flex items-center justify-center p-4">
              <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-md" @click="showTaskResultModal = false"></div>
              <div class="relative bg-white rounded-[3rem] shadow-2xl w-full max-w-lg overflow-hidden flex flex-col animate-in zoom-in duration-300">
                 <div class="p-8 bg-slate-900 text-white flex justify-between items-center shrink-0">
                    <div class="space-y-1">
                       <h3 class="text-xl font-black uppercase tracking-tighter italic">Upload <span class="text-green-500">Hasil</span></h3>
                       <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Tugas: {{ selectedTask?.task }}</p>
                    </div>
                    <button @click="showTaskResultModal = false" class="text-white/60 hover:text-white transition-colors">✕</button>
                 </div>
                 
                 <div class="p-8 space-y-6">
                    <div class="space-y-4">
                       <div class="space-y-1">
                          <label class="text-[10px] font-black uppercase text-gray-400 ml-2 tracking-widest">Catatan Hasil Pengerjaan</label>
                          <textarea v-model="taskResultForm.note" placeholder="Tuliskan laporan singkat atau catatan pengerjaan tugas ini..." class="w-full h-32 p-4 bg-gray-50 rounded-2xl outline-none focus:ring-2 focus:ring-green-600 font-medium text-xs resize-none transition-all" required></textarea>
                       </div>

                       <div class="space-y-2">
                          <label class="text-[10px] font-black uppercase text-gray-400 ml-2 tracking-widest">Bukti Hasil Pekerjaan (Foto)</label>
                          <div 
                            class="relative w-full h-48 bg-gray-50 border-2 border-dashed border-gray-200 rounded-[2rem] flex flex-col items-center justify-center overflow-hidden group cursor-pointer hover:bg-gray-100 transition-all"
                            @click="$refs.taskResultFileInput.click()"
                          >
                             <img v-if="taskResultForm.attachment" :src="taskResultForm.attachment" class="w-full h-full object-cover">
                             <div v-else class="text-center p-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-gray-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <p class="text-[10px] font-black uppercase text-gray-400 tracking-widest leading-tight">Ambil Foto Hasil / <br> Bukti Pekerjaan</p>
                             </div>
                          </div>
                          <input ref="taskResultFileInput" type="file" @change="handleTaskResultFileChange" class="hidden" accept="image/*">
                       </div>
                    </div>

                    <button 
                      @click="handleCompleteTask" 
                      :disabled="!taskResultForm.attachment || submitting" 
                      class="w-full py-5 bg-green-600 text-white rounded-[1.5rem] font-black uppercase tracking-widest text-[11px] shadow-xl shadow-green-100 hover:scale-[1.02] active:scale-95 transition-all disabled:opacity-50"
                    >
                       {{ submitting ? 'Mengirim...' : 'Selesaikan & Kirim Laporan' }}
                    </button>
                 </div>
              </div>
           </div>
        </Transition>
     </Teleport>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted, computed } from 'vue';
import { useRouter } from 'vue-router';
import { useAuth } from '@/store/auth';
import api from '@/api';

const router = useRouter();
const { clearAuth } = useAuth();

const handleLogout = async () => {
  if (confirm('Apakah Anda yakin ingin keluar?')) {
    clearAuth();
    router.push('/login');
  }
};

const activeTab = ref('campaigns');
const loading = ref(true);
const submitting = ref(false);
const myCampaigns = ref([]);
const myNews = ref([]);
const todos = ref([]);
const newTodo = ref('');
const newTodoDeadline = ref('');
const newTodoDescription = ref('');
const attendanceLogs = ref([]);
const allAttendanceLogs = ref([]);
const fieldStats = ref({});
const showLeaveModal = ref(false);
const showUpdateModal = ref(false);
const showTaskResultModal = ref(false);
const selectedCampaign = ref(null);
const selectedTask = ref(null);
const leaveType = ref('');
const leaveForm = reactive({ attachment: '' });
const updateForm = reactive({ title: '', content: '', image: '' });
const taskResultForm = reactive({ attachment: '', note: '' });
const coords = ref(null);
const currentLocationName = ref('');

const attendanceTypes = [
  { type: 'check_in', label: 'Masuk', icon: 'fas fa-sign-in-alt', iconClass: 'bg-green-100 text-green-600', action: () => handleAttendance('check_in') },
  { type: 'check_out', label: 'Pulang', icon: 'fas fa-sign-out-alt', iconClass: 'bg-red-100 text-red-500', action: () => handleAttendance('check_out') },
  { type: 'sakit', label: 'Sakit', icon: 'fas fa-briefcase-medical', iconClass: 'bg-amber-100 text-amber-600', action: () => openLeaveModal('sakit') },
  { type: 'izin', label: 'Izin', icon: 'fas fa-calendar-check', iconClass: 'bg-emerald-100 text-emerald-600', action: () => openLeaveModal('izin') }
];

const showCampaignModal = ref(false);
const showNewsModal = ref(false);

const categories = ['Sosial', 'Medis', 'Pendidikan', 'Bencana', 'Lingkungan', 'Yatim'];

const form = reactive({ title: '', description: '', category: 'Sosial', target_amount: '', foto: '' });
const newsForm = reactive({ title: '', content: '', image: '' });

const fetchData = async () => {
  loading.value = true;
  try {
    const [campRes, newsRes, todoRes, attendRes, allAttendRes, statsRes] = await Promise.all([
      api.get('/campaigns/my-campaigns'),
      api.get('/news/my-news'),
      api.get('/field/todos'),
      api.get('/field/attendance'),
      api.get('/field/attendance/recap'),
      api.get('/field/stats')
    ]);
    myCampaigns.value = campRes.data;
    myNews.value = newsRes.data;
    todos.value = todoRes.data;

    // Filter absensi berdasarkan tanggal LOKAL browser (bukan tanggal server)
    // Ini memastikan absensi kemarin tidak muncul sebagai absensi hari ini
    const todayLocal = new Date().toLocaleDateString('en-CA'); // Format: YYYY-MM-DD
    attendanceLogs.value = attendRes.data.filter(log => {
      const logDate = new Date(log.created_at).toLocaleDateString('en-CA');
      return logDate === todayLocal;
    });

    allAttendanceLogs.value = allAttendRes.data;
    fieldStats.value = statsRes.data;
  } catch (err) {
    console.error(err);
  } finally {
    loading.value = false;
  }
};

const handleAddTodo = async () => {
  if (!newTodo.value.trim()) return;
  try {
    await api.post('/field/todos', {
      task: newTodo.value,
      deadline: newTodoDeadline.value || null,
      description: newTodoDescription.value || null
    });
    newTodo.value = '';
    newTodoDeadline.value = '';
    newTodoDescription.value = '';
    fetchData();
  } catch (err) {
    alert('Gagal menambah tugas');
  }
};

const handleToggleTodo = async (id) => {
  try {
    await api.put(`/field/todos/${id}`);
    fetchData();
  } catch (err) {
    alert('Gagal memperbarui tugas');
  }
};

const isOverdue = (todo) => {
  if (!todo.deadline || todo.status === 'completed') return false;
  return new Date(todo.deadline) < new Date();
};

const formatDeadline = (dateStr) => {
  if (!dateStr) return '';
  return new Date(dateStr).toLocaleString('id-ID', {
    weekday: 'short', day: 'numeric', month: 'short', year: 'numeric',
    hour: '2-digit', minute: '2-digit'
  });
};

const handleDeleteTodo = async (id) => {
  try {
    await api.delete(`/field/todos/${id}`);
    fetchData();
  } catch (err) {
    alert('Gagal menghapus tugas');
  }
};

const handleAttendance = async (type) => {
  submitting.value = true;
  try {
    const payload = {
      type,
      location: currentLocationName.value || 'Lokasi Tidak Diketahui',
      latitude: coords.value?.latitude || null,
      longitude: coords.value?.longitude || null,
      attachment: (type === 'sakit' || type === 'izin') ? leaveForm.attachment : null
    };
    
    await api.post('/field/attendance', payload);
    showLeaveModal.value = false;
    leaveForm.attachment = '';
    fetchData();
  } catch (err) {
    const errorMsg = err.response?.data?.error || err.response?.data?.message || err.message;
    alert('Gagal melakukan absensi: ' + errorMsg);
  } finally {
    submitting.value = false;
  }
};

const openLeaveModal = (type) => {
  leaveType.value = type;
  leaveForm.attachment = '';
  showLeaveModal.value = true;
};

const handleLeaveFileChange = (e) => {
  const file = e.target.files[0];
  if (!file) return;
  const reader = new FileReader();
  reader.onload = (event) => {
    leaveForm.attachment = event.target.result;
  };
  reader.readAsDataURL(file);
};

const getMyLocation = () => {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
      async (position) => {
        coords.value = {
          latitude: position.coords.latitude,
          longitude: position.coords.longitude
        };
        await reverseGeocode(coords.value.latitude, coords.value.longitude);
      },
      (error) => {
        console.error("Error getting location:", error);
        currentLocationName.value = "Akses Lokasi Ditolak";
      }
    );
  } else {
    currentLocationName.value = "Geolocation tidak didukung browser";
  }
};

const reverseGeocode = async (lat, lon) => {
  try {
    const res = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}`);
    const data = await res.json();
    currentLocationName.value = data.display_name.split(',').slice(0, 3).join(',');
  } catch (err) {
    currentLocationName.value = `Koordinat: ${lat.toFixed(4)}, ${lon.toFixed(4)}`;
  }
};

const getAttendanceClass = (type) => {
  const map = {
    check_in: 'bg-green-50/30 border-green-100 shadow-green-900/5',
    check_out: 'bg-red-50/30 border-red-100 shadow-red-900/5',
    sakit: 'bg-amber-50/30 border-amber-100 shadow-amber-900/5',
    izin: 'bg-emerald-50/30 border-emerald-100 shadow-emerald-900/5'
  };
  return map[type];
};

const getButtonClass = (type) => {
  if (isButtonDisabled(type)) return 'bg-gray-100 text-gray-300 cursor-not-allowed shadow-none';
  const map = {
    check_in: 'bg-green-600 text-white shadow-green-100 hover:bg-green-700',
    check_out: 'bg-red-500 text-white shadow-red-100 hover:bg-red-600',
    sakit: 'bg-amber-500 text-white shadow-amber-100 hover:bg-amber-600',
    izin: 'bg-emerald-500 text-white shadow-emerald-100 hover:bg-emerald-600'
  };
  return map[type];
};

const isButtonDisabled = (type) => {
  if (type === 'check_in') return isAlreadyCheckedIn.value || isAbsent.value;
  if (type === 'check_out') return isAlreadyCheckedOut.value || !isAlreadyCheckedIn.value || isAbsent.value;
  return isAnyAttendanceToday.value;
};

const getButtonLabel = (type) => {
  if (type === 'check_in') return isAlreadyCheckedIn.value ? 'Selesai' : 'Check-In';
  if (type === 'check_out') return isAlreadyCheckedOut.value ? 'Selesai' : 'Check-Out';
  return isAnyAttendanceToday.value ? 'Sudah Terisi' : type.charAt(0).toUpperCase() + type.slice(1);
};

const getAttendanceTime = (type) => {
  const log = attendanceLogs.value.find(l => l.type === type);
  return log ? new Date(log.created_at).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) : '--:--';
};

const getStatusBadgeClass = (type) => {
  const map = {
    check_in: 'bg-green-100 text-green-600',
    check_out: 'bg-red-100 text-red-600',
    sakit: 'bg-amber-100 text-amber-600',
    izin: 'bg-emerald-100 text-emerald-600'
  };
  return map[type];
};

const formatTime = (log) => {
  if (!log) return '';
  const dateStr = log.created_at || log;
  const date = new Date(dateStr);
  return date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }).replace('.', ':');
};

const isAlreadyCheckedIn = computed(() => attendanceLogs.value.some(l => l.type === 'check_in'));
const isAlreadyCheckedOut = computed(() => attendanceLogs.value.some(l => l.type === 'check_out'));
const isAbsent = computed(() => attendanceLogs.value.some(l => l.type === 'sakit' || l.type === 'izin'));
const isAnyAttendanceToday = computed(() => attendanceLogs.value.length > 0);

// Filter state untuk riwayat absensi
const filterMonth = ref(''); // '01' - '12' atau '' untuk semua
const filterYear = ref('');  // '2026' atau '' untuk semua
const filterDay = ref('');   // '1'-'31' atau '' untuk semua

const availableYears = computed(() => {
  const years = new Set(allAttendanceLogs.value.map(l => new Date(l.created_at).getFullYear()));
  return [...years].sort((a, b) => b - a);
});

const groupedAttendance = computed(() => {
  const groups = {};
  allAttendanceLogs.value.forEach(log => {
    const d = new Date(log.created_at);
    // Filter berdasarkan bulan, tahun, hari
    if (filterYear.value && d.getFullYear() !== parseInt(filterYear.value)) return;
    if (filterMonth.value && (d.getMonth() + 1) !== parseInt(filterMonth.value)) return;
    if (filterDay.value && d.getDate() !== parseInt(filterDay.value)) return;

    const dateKey = d.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
    if (!groups[dateKey]) {
      groups[dateKey] = { date: dateKey, rawDate: d, check_in: null, check_out: null, other: null };
    }
    if (log.type === 'check_in') groups[dateKey].check_in = log;
    else if (log.type === 'check_out') groups[dateKey].check_out = log;
    else groups[dateKey].other = log;
  });
  // Urutkan dari tanggal terbaru ke terlama
  return Object.values(groups).sort((a, b) => b.rawDate - a.rawDate);
});

const resetFilter = () => {
  filterMonth.value = '';
  filterYear.value = '';
  filterDay.value = '';
};

const todayDate = computed(() => {
  return new Date().toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
});

const downloadAttendanceCSV = () => {
  const headers = ['Tanggal', 'Jam Masuk', 'Lokasi Masuk', 'Jam Pulang', 'Lokasi Pulang', 'Keterangan'];
  const rows = groupedAttendance.value.map(g => [
    g.date,
    g.check_in ? formatTime(g.check_in.created_at) : '-',
    g.check_in ? (g.check_in.location || '-') : '-',
    g.check_out ? formatTime(g.check_out.created_at) : '-',
    g.check_out ? (g.check_out.location || '-') : '-',
    g.other ? g.other.type.toUpperCase() : 'Hadir'
  ]);
  const csvContent = [headers, ...rows].map(r => r.map(c => `"${String(c).replace(/"/g, '""')}"`).join(',')).join('\n');
  const blob = new Blob(['\uFEFF' + csvContent], { type: 'text/csv;charset=utf-8;' });
  const url = URL.createObjectURL(blob);
  const link = document.createElement('a');
  link.href = url;
  link.download = `Rekap_Absensi_${new Date().toLocaleDateString('en-CA')}.csv`;
  link.click();
  URL.revokeObjectURL(url);
};

const openCampaignModal = () => {
  form.title = '';
  form.description = '';
  form.category = 'Sosial';
  form.target_amount = '';
  form.foto = '';
  showCampaignModal.value = true;
};

const openNewsModal = () => {
  newsForm.title = '';
  newsForm.content = '';
  newsForm.image = '';
  showNewsModal.value = true;
};

const handleFileChange = (e, type) => {
  const file = e.target.files[0];
  if (!file) return;
  const reader = new FileReader();
  reader.onload = (event) => {
    if (type === 'cam') form.foto = event.target.result;
    else newsForm.image = event.target.result;
  };
  reader.readAsDataURL(file);
};

const handleCreateCampaign = async () => {
  if (!form.foto) return alert('Harap unggah foto kampanye!');
  submitting.value = true;
  try {
    await api.post('/campaigns', form);
    showCampaignModal.value = false;
    fetchData();
  } catch (err) {
    alert('Gagal membuat kampanye.');
  } finally {
    submitting.value = false;
  }
};

const handleCreateNews = async () => {
  if (!newsForm.image) return alert('Harap unggah foto dokumentasi!');
  submitting.value = true;
  try {
    await api.post('/news', newsForm);
    showNewsModal.value = false;
    fetchData();
  } catch (err) {
    alert('Gagal menerbitkan berita.');
  } finally {
    submitting.value = false;
  }
};

const handleDeleteNews = async (id) => {
  if (!confirm('Hapus berita ini?')) return;
  try {
    await api.post(`/news/delete/${id}`); // We'll update backend to use post or delete properly
    fetchData();
  } catch (err) {
    alert('Gagal menghapus berita.');
  }
};

const openUpdateModal = (campaign) => {
  selectedCampaign.value = campaign;
  updateForm.title = '';
  updateForm.content = '';
  updateForm.image = '';
  showUpdateModal.value = true;
};

const handleUpdateFileChange = (e) => {
  const file = e.target.files[0];
  if (!file) return;
  const reader = new FileReader();
  reader.onload = (event) => {
    updateForm.image = event.target.result;
  };
  reader.readAsDataURL(file);
};

const handleCreateUpdate = async () => {
  if (!updateForm.image) return alert('Harap unggah foto dokumentasi!');
  submitting.value = true;
  try {
    await api.post('/field/updates', {
      campaign_id: selectedCampaign.value.id,
      ...updateForm
    });
    showUpdateModal.value = false;
    alert('Kabar terbaru berhasil dikirim!');
    fetchData();
  } catch (err) {
    alert('Gagal mengirim update');
  } finally {
    submitting.value = false;
  }
};

const openTaskResultModal = (task) => {
  selectedTask.value = task;
  taskResultForm.attachment = '';
  taskResultForm.note = '';
  showTaskResultModal.value = true;
};

const handleTaskResultFileChange = (e) => {
  const file = e.target.files[0];
  if (!file) return;
  const reader = new FileReader();
  reader.onload = (event) => {
    taskResultForm.attachment = event.target.result;
  };
  reader.readAsDataURL(file);
};

const handleCompleteTask = async () => {
  if (!taskResultForm.attachment) return alert('Harap unggah bukti hasil pekerjaan!');
  submitting.value = true;
  try {
    await api.post(`/field/todos/${selectedTask.value.id}/complete`, {
      result_attachment: taskResultForm.attachment,
      result_note: taskResultForm.note
    });
    showTaskResultModal.value = false;
    alert('Tugas berhasil diselesaikan!');
    fetchData();
  } catch (err) {
    alert('Gagal menyelesaikan tugas.');
  } finally {
    submitting.value = false;
  }
};

const statCards = computed(() => [
  { label: 'Kampanye Saya', value: fieldStats.value.totalCampaigns || 0, icon: 'fas fa-hand-holding-heart' },
  { label: 'Update Dampak', value: fieldStats.value.totalContent || 0, icon: 'fas fa-newspaper' },
  { label: 'Dana Terkumpul', value: 'Rp ' + formatCurrency(fieldStats.value.totalFunds), icon: 'fas fa-coins' },
  { label: 'Kehadiran (Bln)', value: fieldStats.value.presentDays + ' Hari', icon: 'fas fa-user-check' }
]);

const formatCurrency = (val) => new Intl.NumberFormat('id-ID').format(val || 0);
const formatDate = (dateStr) => {
  if (!dateStr) return '';
  return new Date(dateStr).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
};

onMounted(() => {
  fetchData();
  getMyLocation();
});
</script>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.4s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>