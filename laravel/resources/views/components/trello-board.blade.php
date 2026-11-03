@props([
    'todos' => collect(),
    'workers' => collect(),
    'canAssign' => false,
    'storeRoute' => route('todo.store'),
    'showViewToggle' => true,
    'tableRoute' => null,
    'paginatedTodos' => null,
])

@php
    $todosCollection = collect($todos);
    $pendingTodos = $todosCollection->where('status', 'pending');
    $inProgressTodos = $todosCollection->where('status', 'in_progress');
    $completedTodos = $todosCollection->where('status', 'completed');
    $totalCount = $todosCollection->count();
    $completedCount = $completedTodos->count();
    $progressPercent = $totalCount > 0 ? round(($completedCount / $totalCount) * 100) : 0;
@endphp

<!-- SortableJS for smooth drag & drop -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>

<div x-data="trelloBoardData()" x-init="initBoard()" class="space-y-6">

    <!-- Toast Notification -->
    <div x-show="toast.show" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-4"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform translate-y-4"
         style="display: none;"
         class="fixed bottom-6 right-6 z-[300] max-w-sm px-5 py-3.5 rounded-2xl shadow-xl flex items-center gap-3 border text-xs font-bold"
         :class="toast.type === 'success' ? 'bg-emerald-600 text-white border-emerald-500 shadow-emerald-500/20' : 'bg-rose-600 text-white border-rose-500 shadow-rose-500/20'">
        <i class="fa-solid" :class="toast.type === 'success' ? 'fa-circle-check text-base' : 'fa-circle-exclamation text-base'"></i>
        <span x-text="toast.message"></span>
    </div>

    <!-- Trello Top Toolbar -->
    <div class="bg-white border border-slate-200/80 rounded-3xl p-4 sm:p-5 shadow-sm space-y-4">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            
            <!-- Left: Title & Progress Bar -->
            <div class="space-y-2">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-primary-600 text-white flex items-center justify-center shadow-sm">
                        <i class="fa-solid fa-table-columns text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-base sm:text-lg font-montserrat font-black text-slate-900 uppercase tracking-tight italic">
                            Papan <span class="text-primary-600">Tugas (Trello Board)</span>
                        </h2>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">
                            <span x-text="counts.completed">{{ $completedCount }}</span> dari <span x-text="counts.total">{{ $totalCount }}</span> Selesai (<span x-text="progressPercent">{{ $progressPercent }}</span>%)
                        </p>
                    </div>
                </div>

                <!-- Mini Progress Bar -->
                <div class="w-full sm:w-64 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                    <div class="h-full bg-emerald-500 rounded-full transition-all duration-500"
                         :style="'width: ' + progressPercent + '%'"></div>
                </div>
            </div>

            <!-- Right: Actions & View Switcher -->
            <div class="flex flex-wrap items-center gap-2.5">
                
                @if($showViewToggle)
                <!-- View Toggle Buttons -->
                <div class="bg-slate-100 p-1 rounded-2xl flex items-center text-xs font-bold text-slate-600">
                    <button @click="currentView = 'kanban'" 
                            :class="currentView === 'kanban' ? 'bg-white text-primary-600 shadow-xs' : 'text-slate-500 hover:text-slate-800'"
                            class="px-3.5 py-2 rounded-xl transition-all flex items-center gap-1.5 text-[11px] font-black uppercase tracking-wider">
                        <i class="fa-solid fa-table-columns"></i>
                        <span>Papan Kanban</span>
                    </button>
                    <button @click="currentView = 'table'" 
                            :class="currentView === 'table' ? 'bg-white text-primary-600 shadow-xs' : 'text-slate-500 hover:text-slate-800'"
                            class="px-3.5 py-2 rounded-xl transition-all flex items-center gap-1.5 text-[11px] font-black uppercase tracking-wider">
                        <i class="fa-solid fa-list-check"></i>
                        <span>Tabel List</span>
                    </button>
                </div>
                @endif

                <!-- Button Buat Tugas Baru -->
                <button @click="openCreateModal()" 
                        class="px-4 py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-2xl text-xs font-black uppercase tracking-wider flex items-center gap-2 shadow-sm shadow-primary-600/20 transition-all">
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>Tugas Baru</span>
                </button>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="pt-3 border-t border-slate-100 flex flex-wrap items-center gap-3 text-xs">
            
            <!-- Real-time Search Box -->
            <div class="relative flex-1 min-w-[200px]">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="text" 
                       x-model="searchQuery" 
                       @input="filterCards()" 
                       placeholder="Cari kartu tugas atau staf..." 
                       class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-4 py-2 text-xs font-semibold text-slate-800 placeholder:text-slate-400 outline-none focus:border-primary-500 focus:bg-white transition-all">
            </div>

            @if($workers->count() > 0)
            <!-- Assignee Filter -->
            <div class="flex items-center gap-1.5">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider hidden sm:inline">Pegawai:</span>
                <select x-model="selectedAssignee" 
                        @change="filterCards()" 
                        class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold text-slate-700 outline-none focus:border-primary-500">
                    <option value="">Semua Pegawai</option>
                    @foreach($workers as $w)
                        <option value="{{ $w->id }}">{{ $w->name }}</option>
                    @endforeach
                </select>
            </div>
            @endif

            <button x-show="searchQuery || selectedAssignee" 
                    @click="resetFilters()" 
                    style="display: none;"
                    class="text-[11px] font-bold text-rose-500 hover:text-rose-700 underline px-1">
                Reset Filter
            </button>
        </div>
    </div>

    <!-- VIEW 1: TRELLO KANBAN BOARD -->
    <div x-show="currentView === 'kanban'" x-transition class="space-y-4">
        
        <!-- Kanban Columns Grid (3 Lanes) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 items-start">
            
            <!-- ========================================== -->
            <!-- LANE 1: TO DO (Belum Dikerjakan) -->
            <!-- ========================================== -->
            <div class="bg-slate-100/80 border border-slate-200/80 rounded-3xl p-4 flex flex-col shadow-xs">
                
                <!-- Lane Header -->
                <div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-200">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-slate-400 inline-block"></span>
                        <h3 class="font-montserrat font-black text-slate-800 text-xs sm:text-sm uppercase tracking-wider">
                            To Do
                        </h3>
                        <span id="badge-pending" 
                              class="px-2 py-0.5 rounded-full text-[10px] font-black bg-slate-200 text-slate-700">
                            {{ $pendingTodos->count() }}
                        </span>
                    </div>

                    <button @click="openCreateModal('pending')" 
                            class="w-6 h-6 rounded-lg bg-white hover:bg-slate-200 text-slate-500 hover:text-slate-800 flex items-center justify-center transition-all shadow-xs" 
                            title="Tambah Kartu di Kolom Ini">
                        <i class="fa-solid fa-plus text-xs"></i>
                    </button>
                </div>

                <!-- Cards Dropzone -->
                <div id="lane-pending" 
                     data-status="pending" 
                     class="trello-lane space-y-3 min-h-[320px] transition-colors p-0.5 rounded-2xl">
                    @forelse($pendingTodos as $item)
                        @include('components.trello-card', ['todo' => $item])
                    @empty
                        <div class="empty-lane-placeholder py-12 text-center text-slate-400 text-xs font-bold space-y-1">
                            <i class="fa-regular fa-clipboard text-slate-300 text-2xl block mb-1"></i>
                            <p>Tidak ada tugas pending</p>
                            <p class="text-[10px] font-normal text-slate-400">Tarik kartu ke sini atau buat baru</p>
                        </div>
                    @endforelse
                </div>

                <!-- Quick Add Card at Lane Footer -->
                <div class="mt-3 pt-2 border-t border-slate-200/60">
                    <button @click="openCreateModal('pending')" 
                            class="w-full py-2.5 px-3 rounded-2xl text-slate-500 hover:text-primary-700 hover:bg-white text-xs font-bold transition-all flex items-center justify-center gap-1.5 border border-dashed border-slate-300 hover:border-primary-300">
                        <i class="fa-solid fa-plus text-[10px]"></i> Tambah Kartu Baru
                    </button>
                </div>
            </div>

            <!-- ========================================== -->
            <!-- LANE 2: IN PROGRESS (Sedang Berjalan) -->
            <!-- ========================================== -->
            <div class="bg-amber-50/50 border border-amber-200/70 rounded-3xl p-4 flex flex-col shadow-xs">
                
                <!-- Lane Header -->
                <div class="flex items-center justify-between pb-3 mb-3 border-b border-amber-200/80">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-amber-500 animate-pulse inline-block"></span>
                        <h3 class="font-montserrat font-black text-amber-900 text-xs sm:text-sm uppercase tracking-wider">
                            In Progress
                        </h3>
                        <span id="badge-in_progress" 
                              class="px-2 py-0.5 rounded-full text-[10px] font-black bg-amber-200 text-amber-900">
                            {{ $inProgressTodos->count() }}
                        </span>
                    </div>

                    <button @click="openCreateModal('in_progress')" 
                            class="w-6 h-6 rounded-lg bg-white hover:bg-amber-200 text-amber-700 flex items-center justify-center transition-all shadow-xs" 
                            title="Tambah Kartu di Kolom Ini">
                        <i class="fa-solid fa-plus text-xs"></i>
                    </button>
                </div>

                <!-- Cards Dropzone -->
                <div id="lane-in_progress" 
                     data-status="in_progress" 
                     class="trello-lane space-y-3 min-h-[320px] transition-colors p-0.5 rounded-2xl">
                    @forelse($inProgressTodos as $item)
                        @include('components.trello-card', ['todo' => $item])
                    @empty
                        <div class="empty-lane-placeholder py-12 text-center text-amber-600/60 text-xs font-bold space-y-1">
                            <i class="fa-solid fa-bolt text-amber-300 text-2xl block mb-1"></i>
                            <p>Belum ada yang dikerjakan</p>
                            <p class="text-[10px] font-normal text-amber-600/50">Tarik tugas ke sini untuk mulai pengerjaan</p>
                        </div>
                    @endforelse
                </div>

                <!-- Quick Add Card at Lane Footer -->
                <div class="mt-3 pt-2 border-t border-amber-200/60">
                    <button @click="openCreateModal('in_progress')" 
                            class="w-full py-2.5 px-3 rounded-2xl text-amber-700 hover:text-amber-900 hover:bg-white text-xs font-bold transition-all flex items-center justify-center gap-1.5 border border-dashed border-amber-300 hover:border-amber-400">
                        <i class="fa-solid fa-plus text-[10px]"></i> Tambah Kartu Baru
                    </button>
                </div>
            </div>

            <!-- ========================================== -->
            <!-- LANE 3: DONE (Selesai) -->
            <!-- ========================================== -->
            <div class="bg-emerald-50/50 border border-emerald-200/70 rounded-3xl p-4 flex flex-col shadow-xs">
                
                <!-- Lane Header -->
                <div class="flex items-center justify-between pb-3 mb-3 border-b border-emerald-200/80">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-emerald-500 inline-block"></span>
                        <h3 class="font-montserrat font-black text-emerald-900 text-xs sm:text-sm uppercase tracking-wider">
                            Selesai (Done)
                        </h3>
                        <span id="badge-completed" 
                              class="px-2 py-0.5 rounded-full text-[10px] font-black bg-emerald-200 text-emerald-900">
                            {{ $completedTodos->count() }}
                        </span>
                    </div>

                    <button @click="openCreateModal('completed')" 
                            class="w-6 h-6 rounded-lg bg-white hover:bg-emerald-200 text-emerald-700 flex items-center justify-center transition-all shadow-xs" 
                            title="Tambah Kartu di Kolom Ini">
                        <i class="fa-solid fa-plus text-xs"></i>
                    </button>
                </div>

                <!-- Cards Dropzone -->
                <div id="lane-completed" 
                     data-status="completed" 
                     class="trello-lane space-y-3 min-h-[320px] transition-colors p-0.5 rounded-2xl">
                    @forelse($completedTodos as $item)
                        @include('components.trello-card', ['todo' => $item])
                    @empty
                        <div class="empty-lane-placeholder py-12 text-center text-emerald-600/60 text-xs font-bold space-y-1">
                            <i class="fa-regular fa-circle-check text-emerald-300 text-2xl block mb-1"></i>
                            <p>Belum ada tugas selesai</p>
                            <p class="text-[10px] font-normal text-emerald-600/50">Tarik tugas yang sudah tuntas ke sini</p>
                        </div>
                    @endforelse
                </div>

                <!-- Quick Add Card at Lane Footer -->
                <div class="mt-3 pt-2 border-t border-emerald-200/60">
                    <button @click="openCreateModal('completed')" 
                            class="w-full py-2.5 px-3 rounded-2xl text-emerald-700 hover:text-emerald-900 hover:bg-white text-xs font-bold transition-all flex items-center justify-center gap-1.5 border border-dashed border-emerald-300 hover:border-emerald-400">
                        <i class="fa-solid fa-plus text-[10px]"></i> Tambah Kartu Selesai
                    </button>
                </div>
            </div>

        </div>
    </div>

    <!-- VIEW 2: CLASSIC TABLE VIEW -->
    @if($showViewToggle)
    <div x-show="currentView === 'table'" x-transition style="display: none;" class="space-y-4">
        <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <h3 class="font-montserrat font-black text-slate-800 text-sm uppercase tracking-wider">
                    Daftar Tabel Tugas Lengkap
                </h3>
                @if($tableRoute)
                    <a href="{{ $tableRoute }}" class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-black uppercase tracking-wider flex items-center gap-1.5 shadow-sm transition-all">
                        <i class="fa-solid fa-file-excel"></i> Export Data
                    </a>
                @endif
            </div>

            <div class="overflow-x-auto mt-4">
                <table class="w-full text-left text-xs text-slate-700">
                    <thead class="bg-slate-50 text-[10px] font-black uppercase tracking-wider text-slate-400 border-b border-slate-100">
                        <tr>
                            <th class="py-3 px-4">Tugas</th>
                            <th class="py-3 px-4">Pegawai (Untuk)</th>
                            <th class="py-3 px-4">Pemberi Tugas</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4">Deadline</th>
                            <th class="py-3 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-semibold">
                        @forelse($todos as $t)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-3 px-4">
                                <span class="font-bold text-slate-900">{{ $t->task }}</span>
                                @if($t->description)
                                    <p class="text-[10px] text-slate-400 truncate max-w-xs">{{ $t->description }}</p>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                <span class="text-indigo-700 font-bold">{{ $t->user->name ?? '-' }}</span>
                            </td>
                            <td class="py-3 px-4 text-slate-500">
                                {{ $t->assigner->name ?? 'Sistem' }}
                            </td>
                            <td class="py-3 px-4">
                                @if($t->status === 'completed')
                                    <span class="px-2.5 py-1 rounded-full text-[9px] font-black uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-200">Selesai</span>
                                @elseif($t->status === 'in_progress')
                                    <span class="px-2.5 py-1 rounded-full text-[9px] font-black uppercase tracking-wider bg-amber-50 text-amber-700 border border-amber-200">In Progress</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-[9px] font-black uppercase tracking-wider bg-slate-100 text-slate-700 border border-slate-200">To Do</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-[11px] font-mono">
                                {{ $t->deadline ? $t->deadline->format('d M Y H:i') : '-' }}
                            </td>
                            <td class="py-3 px-4 text-center">
                                <button @click="openDetailModal({{ $t->toJson() }})" 
                                        class="p-2 text-primary-600 hover:bg-primary-50 rounded-xl transition-all" title="Buka Detail">
                                    <i class="fa-solid fa-up-right-from-square"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-slate-400 font-bold">Belum ada tugas yang tercatat.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($paginatedTodos && method_exists($paginatedTodos, 'links'))
                <div class="mt-4 pt-4 border-t border-slate-100">
                    {{ $paginatedTodos->links() }}
                </div>
            @endif
        </div>
    </div>
    @endif

    <!-- ============================================================== -->
    <!-- MODAL 1: TRELLO CARD DETAIL MODAL -->
    <!-- ============================================================== -->
    <div x-show="detailModal.open" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         style="display: none;" 
         class="fixed inset-0 z-[250] flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-sm overflow-y-auto">
        
        <div @click.away="detailModal.open = false" 
             class="bg-white border border-slate-100 w-full max-w-xl rounded-3xl p-6 sm:p-8 shadow-2xl space-y-6 relative my-8">
            
            <button @click="detailModal.open = false" 
                    class="absolute top-6 right-6 text-slate-400 hover:text-slate-700 transition-colors">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>

            <!-- Modal Header -->
            <div class="space-y-2 pr-8">
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider"
                          :class="detailModal.data.status === 'completed' ? 'bg-emerald-100 text-emerald-800' : (detailModal.data.status === 'in_progress' ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-800')"
                          x-text="detailModal.data.status === 'completed' ? 'SELESAI (DONE)' : (detailModal.data.status === 'in_progress' ? 'IN PROGRESS' : 'TO DO')">
                    </span>
                    <span class="text-[10px] text-slate-400 font-bold" x-text="'ID: #' + detailModal.data.id"></span>
                </div>
                <h3 class="text-lg sm:text-xl font-montserrat font-black text-slate-900 tracking-tight" 
                    x-text="detailModal.data.task"></h3>
            </div>

            <!-- Modal Metadata Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 p-4 bg-slate-50 border border-slate-100 rounded-2xl text-xs">
                <div>
                    <span class="block text-[9px] font-bold uppercase tracking-wider text-slate-400">Pegawai (Untuk)</span>
                    <span class="font-bold text-slate-800" x-text="detailModal.data.user ? detailModal.data.user.name : '-'"></span>
                </div>
                <div>
                    <span class="block text-[9px] font-bold uppercase tracking-wider text-slate-400">Pemberi Tugas</span>
                    <span class="font-bold text-slate-800" x-text="detailModal.data.assigner ? detailModal.data.assigner.name : 'Sistem'"></span>
                </div>
                <div>
                    <span class="block text-[9px] font-bold uppercase tracking-wider text-slate-400">Batas Waktu</span>
                    <span class="font-bold text-red-600" x-text="formatDate(detailModal.data.deadline)"></span>
                </div>
            </div>

            <!-- Deskripsi -->
            <div class="space-y-1.5">
                <span class="text-[10px] font-black uppercase tracking-wider text-slate-400">Deskripsi / Rincian Penugasan</span>
                <div class="p-3.5 bg-slate-50 border border-slate-100 rounded-2xl text-xs text-slate-700 leading-relaxed min-h-[60px]"
                     x-text="detailModal.data.description || 'Tidak ada catatan deskripsi tambahan.'">
                </div>
            </div>

            <!-- Ubah Status Cepat -->
            <div class="space-y-2">
                <span class="text-[10px] font-black uppercase tracking-wider text-slate-400">Pindahkan Status Kartu:</span>
                <div class="grid grid-cols-3 gap-2">
                    <button @click="changeStatusInModal('pending')" 
                            :class="detailModal.data.status === 'pending' ? 'bg-slate-800 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                            class="py-2.5 px-2 rounded-xl text-[10px] font-black uppercase tracking-wider transition-all flex items-center justify-center gap-1.5">
                        <i class="fa-regular fa-clipboard"></i> To Do
                    </button>
                    <button @click="changeStatusInModal('in_progress')" 
                            :class="detailModal.data.status === 'in_progress' ? 'bg-amber-600 text-white shadow-xs' : 'bg-amber-50 text-amber-700 hover:bg-amber-100'"
                            class="py-2.5 px-2 rounded-xl text-[10px] font-black uppercase tracking-wider transition-all flex items-center justify-center gap-1.5">
                        <i class="fa-solid fa-bolt"></i> In Progress
                    </button>
                    <button @click="changeStatusInModal('completed')" 
                            :class="detailModal.data.status === 'completed' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100'"
                            class="py-2.5 px-2 rounded-xl text-[10px] font-black uppercase tracking-wider transition-all flex items-center justify-center gap-1.5">
                        <i class="fa-solid fa-check"></i> Selesai
                    </button>
                </div>
            </div>

            <!-- Bukti & Laporan Hasil (Upload / View) -->
            <div class="border-t border-slate-100 pt-4 space-y-4">
                <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 block">Laporan & Bukti Hasil Kerja</span>
                
                <!-- Existing Proof Image -->
                <template x-if="detailModal.data.result_attachment">
                    <div class="p-3 bg-slate-50 border border-slate-150 rounded-2xl space-y-2">
                        <span class="text-[9px] font-bold text-slate-500 uppercase tracking-wider block">Foto Bukti Terlampir:</span>
                        <a :href="detailModal.data.result_attachment" target="_blank" class="block group relative overflow-hidden rounded-xl border border-slate-200 max-h-48">
                            <img :src="detailModal.data.result_attachment" class="w-full object-cover group-hover:scale-105 transition-transform" alt="Bukti Pelaksanaan">
                            <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-xs font-bold gap-2">
                                <i class="fa-solid fa-magnifying-glass-plus"></i> Lihat Ukuran Asli
                            </div>
                        </a>
                    </div>
                </template>

                <!-- Existing / Update Note -->
                <form :action="'{{ url('todo') }}/' + detailModal.data.id + '/update'" method="POST" enctype="multipart/form-data" class="space-y-3">
                    @csrf
                    <input type="hidden" name="status" :value="detailModal.data.status">
                    
                    <div>
                        <label class="block text-[9px] font-bold uppercase tracking-wider text-slate-400 mb-1">Catatan Hasil Pelaksanaan:</label>
                        <textarea name="result_note" rows="2" 
                                  x-model="detailModal.data.result_note"
                                  placeholder="Tuliskan keterangan penyelesaian tugas lapangan..." 
                                  class="w-full bg-slate-50 border border-slate-200 rounded-2xl p-3 text-xs font-semibold text-slate-800 outline-none focus:border-primary-500"></textarea>
                    </div>

                    <div>
                        <label class="block text-[9px] font-bold uppercase tracking-wider text-slate-400 mb-1">Unggah / Ganti Foto Bukti:</label>
                        <input type="file" name="result_attachment" accept="image/*" class="w-full bg-slate-50 border border-slate-200 rounded-2xl p-2.5 text-xs text-slate-600">
                    </div>

                    <div class="flex items-center justify-between gap-3 pt-2">
                        <!-- Delete Card Button -->
                        <button type="button" 
                                @click="deleteCard(detailModal.data.id)"
                                class="px-3.5 py-2.5 text-rose-600 hover:bg-rose-50 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5">
                            <i class="fa-regular fa-trash-can"></i> Hapus
                        </button>

                        <button type="submit" 
                                class="px-5 py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-xl text-xs font-black uppercase tracking-wider transition-all shadow-sm">
                            Simpan Laporan
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <!-- ============================================================== -->
    <!-- MODAL 2: CREATE NEW TASK MODAL -->
    <!-- ============================================================== -->
    <div x-show="createModal.open" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         style="display: none;" 
         class="fixed inset-0 z-[250] flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-sm overflow-y-auto">
        
        <div @click.away="createModal.open = false" 
             class="bg-white border border-slate-100 w-full max-w-lg rounded-3xl p-6 sm:p-8 shadow-2xl space-y-6 relative my-8">
            
            <button @click="createModal.open = false" 
                    class="absolute top-6 right-6 text-slate-400 hover:text-slate-700 transition-colors">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>

            <!-- Modal Header -->
            <div class="space-y-1">
                <h3 class="text-lg font-montserrat font-black text-slate-900 uppercase tracking-tight italic">
                    Tambah <span class="text-primary-600">Tugas Baru</span>
                </h3>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">
                    Buat kartu tugas baru untuk kolom <span class="text-primary-600 uppercase" x-text="createModal.targetStatus"></span>
                </p>
            </div>

            <!-- Form -->
            <form action="{{ $storeRoute }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="status" :value="createModal.targetStatus">

                @if($canAssign && $workers->count() > 0)
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-wider text-slate-500 mb-1.5">Pilih Pegawai / Staf Pelaksana:</label>
                    <select name="user_id" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-xs font-bold text-slate-800 outline-none focus:border-primary-500">
                        <option value="" disabled selected>Pilih salah satu staf...</option>
                        @foreach($workers as $w)
                            <option value="{{ $w->id }}">{{ $w->name }} ({{ $w->role_label }})</option>
                        @endforeach
                    </select>
                </div>
                @else
                <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                @endif

                <div>
                    <label class="block text-[10px] font-black uppercase tracking-wider text-slate-500 mb-1.5">Judul Tugas:</label>
                    <input type="text" name="task" required placeholder="Contoh: Distribusi sembako di Posko A..." 
                           class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-xs font-bold text-slate-800 outline-none focus:border-primary-500">
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase tracking-wider text-slate-500 mb-1.5">Deskripsi Detail:</label>
                    <textarea name="description" rows="3" placeholder="Rincian arahan, PIC lapangan, atau catatan khusus..." 
                              class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-xs font-semibold text-slate-800 outline-none focus:border-primary-500"></textarea>
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase tracking-wider text-slate-500 mb-1.5">Batas Waktu (Deadline):</label>
                    <input type="datetime-local" name="deadline" 
                           class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-xs font-bold text-slate-800 outline-none focus:border-primary-500">
                </div>

                <div class="pt-2">
                    <button type="submit" 
                            class="w-full bg-primary-600 hover:bg-primary-700 text-white font-black text-xs uppercase tracking-wider py-3.5 rounded-2xl shadow-lg shadow-primary-600/10 transition-all">
                        Simpan & Buat Kartu Tugas
                    </button>
                </div>
            </form>

        </div>
    </div>

</div>

<!-- Component Logic -->
<script>
function trelloBoardData() {
    return {
        currentView: 'kanban',
        searchQuery: '',
        selectedAssignee: '',
        toast: {
            show: false,
            message: '',
            type: 'success'
        },
        counts: {
            pending: {{ $pendingTodos->count() }},
            in_progress: {{ $inProgressTodos->count() }},
            completed: {{ $completedTodos->count() }},
            total: {{ $totalCount }}
        },
        detailModal: {
            open: false,
            data: {}
        },
        createModal: {
            open: false,
            targetStatus: 'pending'
        },
        get progressPercent() {
            return this.counts.total > 0 ? Math.round((this.counts.completed / this.counts.total) * 100) : 0;
        },
        showToast(msg, type = 'success') {
            this.toast.message = msg;
            this.toast.type = type;
            this.toast.show = true;
            setTimeout(() => { this.toast.show = false; }, 3500);
        },
        formatDate(dateStr) {
            if (!dateStr) return '-';
            try {
                const d = new Date(dateStr);
                return d.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' });
            } catch(e) {
                return dateStr;
            }
        },
        openCreateModal(status = 'pending') {
            this.createModal.targetStatus = status;
            this.createModal.open = true;
        },
        openDetailModal(item) {
            this.detailModal.data = Object.assign({}, item);
            this.detailModal.open = true;
        },
        changeStatusInModal(newStatus) {
            const cardId = this.detailModal.data.id;
            this.detailModal.data.status = newStatus;
            
            // Move card visually
            const cardEl = document.querySelector(`.trello-card[data-id="${cardId}"]`);
            const targetLane = document.getElementById('lane-' + newStatus);
            if (cardEl && targetLane) {
                targetLane.appendChild(cardEl);
                this.updateCounts();
            }

            // Sync with server
            fetch('{{ url("todo") }}/' + cardId + '/move', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ status: newStatus })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    this.showToast('Status berhasil diubah ke ' + (newStatus === 'completed' ? 'Selesai' : (newStatus === 'in_progress' ? 'In Progress' : 'To Do')), 'success');
                } else {
                    this.showToast(data.message || 'Gagal mengubah status.', 'error');
                }
            })
            .catch(() => {
                this.showToast('Koneksi terganggu.', 'error');
            });
        },
        deleteCard(cardId) {
            if (!confirm('Apakah Anda yakin ingin menghapus kartu tugas ini?')) return;
            
            fetch('{{ url("todo") }}/' + cardId + '/delete', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const cardEl = document.querySelector(`.trello-card[data-id="${cardId}"]`);
                    if (cardEl) cardEl.remove();
                    this.detailModal.open = false;
                    this.updateCounts();
                    this.showToast('Tugas berhasil dihapus.', 'success');
                } else {
                    this.showToast(data.message || 'Gagal menghapus tugas.', 'error');
                }
            })
            .catch(() => {
                this.showToast('Koneksi terganggu.', 'error');
            });
        },
        filterCards() {
            const q = this.searchQuery.toLowerCase().trim();
            const assignee = this.selectedAssignee;
            const cards = document.querySelectorAll('.trello-card');

            cards.forEach(card => {
                const text = card.textContent.toLowerCase();
                const cardAssignee = card.getAttribute('data-assignee-id');
                const matchesSearch = !q || text.includes(q);
                const matchesAssignee = !assignee || cardAssignee === assignee;

                if (matchesSearch && matchesAssignee) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        },
        resetFilters() {
            this.searchQuery = '';
            this.selectedAssignee = '';
            this.filterCards();
        },
        updateCounts() {
            const pCount = document.querySelectorAll('#lane-pending .trello-card').length;
            const ipCount = document.querySelectorAll('#lane-in_progress .trello-card').length;
            const cCount = document.querySelectorAll('#lane-completed .trello-card').length;

            this.counts.pending = pCount;
            this.counts.in_progress = ipCount;
            this.counts.completed = cCount;
            this.counts.total = pCount + ipCount + cCount;

            const bP = document.getElementById('badge-pending');
            const bIP = document.getElementById('badge-in_progress');
            const bC = document.getElementById('badge-completed');
            if (bP) bP.textContent = pCount;
            if (bIP) bIP.textContent = ipCount;
            if (bC) bC.textContent = cCount;
        },
        initBoard() {
            const self = this;
            const lanes = ['pending', 'in_progress', 'completed'];

            lanes.forEach(status => {
                const el = document.getElementById('lane-' + status);
                if (!el) return;

                new Sortable(el, {
                    group: 'trello-kanban-board',
                    animation: 250,
                    ghostClass: 'opacity-30',
                    chosenClass: 'scale-[1.02]',
                    dragClass: 'shadow-2xl',
                    filter: '.empty-lane-placeholder',
                    onEnd: function(evt) {
                        const itemEl = evt.item;
                        const cardId = itemEl.getAttribute('data-id');
                        const fromStatus = evt.from.getAttribute('data-status');
                        const toStatus = evt.to.getAttribute('data-status');

                        if (!cardId || fromStatus === toStatus) return;

                        self.updateCounts();

                        // Call AJAX to persist
                        fetch('{{ url("todo") }}/' + cardId + '/move', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ status: toStatus })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                const label = toStatus === 'completed' ? 'Selesai' : (toStatus === 'in_progress' ? 'In Progress' : 'To Do');
                                self.showToast('Status dipindahkan ke: ' + label, 'success');
                            } else {
                                // Revert
                                evt.from.appendChild(itemEl);
                                self.updateCounts();
                                self.showToast(data.message || 'Gagal memindahkan kartu.', 'error');
                            }
                        })
                        .catch(() => {
                            evt.from.appendChild(itemEl);
                            self.updateCounts();
                            self.showToast('Gagal terhubung ke server.', 'error');
                        });
                    }
                });
            });
        }
    };
}
</script>
