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
         role="status"
         aria-live="polite"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-4"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform translate-y-4"
         style="display: none;"
         class="fixed bottom-6 right-6 z-[300] max-w-sm px-5 py-3.5 rounded-2xl shadow-xl flex items-center gap-3 border text-xs font-bold"
         :class="toast.type === 'success' ? 'bg-emerald-700 text-white border-emerald-600 shadow-emerald-700/20' : 'bg-rose-700 text-white border-rose-600 shadow-rose-700/20'">
        <i class="fa-solid" :class="toast.type === 'success' ? 'fa-circle-check text-base' : 'fa-circle-exclamation text-base'"></i>
        <span x-text="toast.message"></span>
    </div>

    <!-- Trello Top Toolbar -->
    <div class="bg-white border border-slate-200 rounded-3xl p-4 sm:p-5 shadow-sm space-y-4">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            
            <!-- Left: Title & Progress Bar -->
            <div class="space-y-2">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-[#063f5c] text-white flex items-center justify-center shadow-xs">
                        <i class="fa-solid fa-table-columns text-sm" aria-hidden="true"></i>
                    </div>
                    <div>
                        <h2 class="text-base sm:text-lg font-montserrat font-black text-slate-900 uppercase tracking-tight">
                            Papan <span class="text-[#063f5c]">Tugas (Trello Board)</span>
                        </h2>
                        <p class="text-xs text-slate-600 font-bold uppercase tracking-wider">
                            <span x-text="counts.completed">{{ $completedCount }}</span> dari <span x-text="counts.total">{{ $totalCount }}</span> Selesai (<span x-text="progressPercent">{{ $progressPercent }}</span>%)
                        </p>
                    </div>
                </div>

                <!-- Mini Progress Bar -->
                <div class="w-full sm:w-64 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                    <div class="h-full bg-emerald-700 rounded-full transition-all duration-500"
                         :style="'width: ' + progressPercent + '%'"></div>
                </div>
            </div>

            <!-- Right: Actions & View Switcher -->
            <div class="flex flex-wrap items-center gap-2.5">
                
                @if($showViewToggle)
                <!-- View Toggle Buttons -->
                <div class="bg-slate-100 border border-slate-200 p-1 rounded-xl flex items-center text-xs font-bold text-slate-600">
                    <button type="button" @click="currentView = 'kanban'" 
                            :aria-pressed="currentView === 'kanban'"
                            :class="currentView === 'kanban' ? 'bg-white text-primary-700 shadow-xs' : 'text-slate-600 hover:text-slate-900'"
                            class="px-3.5 py-2 min-h-[44px] rounded-lg transition-colors inline-flex items-center gap-1.5 text-xs font-black uppercase tracking-wider">
                        <i class="fa-solid fa-table-columns" aria-hidden="true"></i>
                        <span>Papan Kanban</span>
                    </button>
                    <button type="button" @click="currentView = 'table'" 
                            :aria-pressed="currentView === 'table'"
                            :class="currentView === 'table' ? 'bg-white text-primary-700 shadow-xs' : 'text-slate-600 hover:text-slate-900'"
                            class="px-3.5 py-2 min-h-[44px] rounded-lg transition-colors inline-flex items-center gap-1.5 text-xs font-black uppercase tracking-wider">
                        <i class="fa-solid fa-list-check" aria-hidden="true"></i>
                        <span>Tabel List</span>
                    </button>
                </div>
                @endif

                <!-- Button Buat Tugas Baru -->
                <button type="button" @click="openCreateModal()" 
                        class="px-4 py-2.5 min-h-[44px] bg-[#063f5c] hover:bg-primary-700 text-white rounded-xl text-xs font-black uppercase tracking-wider inline-flex items-center gap-2 shadow-sm transition-colors">
                    <i class="fa-solid fa-plus text-xs" aria-hidden="true"></i>
                    <span>Tugas Baru</span>
                </button>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="pt-3 border-t border-slate-100 flex flex-wrap items-center gap-3 text-xs">
            
            <!-- Real-time Search Box -->
            <div class="relative flex-1 min-w-[200px]">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-600 text-xs"></i>
                <input type="text" 
                       aria-label="Cari kartu tugas atau staf"
                       x-model="searchQuery" 
                       @input="filterCards()" 
                       placeholder="Cari kartu tugas atau staf..." 
                       class="w-full bg-slate-50 border border-slate-300 rounded-xl pl-9 pr-4 py-2.5 min-h-[44px] text-xs font-semibold text-slate-800 placeholder:text-slate-500 focus:border-primary-500 focus:bg-white transition-colors">
            </div>

            @if($workers->count() > 0)
            <!-- Assignee Filter -->
            <div class="flex items-center gap-1.5">
                <label for="trello_filter_assignee" class="text-xs font-bold text-slate-600 uppercase tracking-wider hidden sm:inline">Pegawai:</label>
                <select id="trello_filter_assignee" x-model="selectedAssignee" 
                        @change="filterCards()" 
                        class="bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 min-h-[44px] text-xs font-bold text-slate-700 focus:border-primary-500">
                    <option value="">Semua Pegawai</option>
                    @foreach($workers as $w)
                        <option value="{{ $w->id }}">{{ $w->name }}</option>
                    @endforeach
                </select>
            </div>
            @endif

            <button type="button" x-show="searchQuery || selectedAssignee" 
                    @click="resetFilters()" 
                    style="display: none;"
                    class="min-h-[44px] text-xs font-bold text-rose-700 hover:text-rose-800 underline px-2">
                Reset Filter
            </button>
        </div>
    </div>

    <!-- View 1: Trello Kanban Board -->
    <div x-show="currentView === 'kanban'" x-transition class="space-y-4">
        
        <!-- Kanban Columns Grid (3 Lanes) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 items-start">
            
            <!-- Lane 1: To Do (Belum Dikerjakan) -->
            <div class="bg-slate-100 border border-slate-200 rounded-2xl p-4 flex flex-col shadow-xs">
                
                <!-- Lane Header -->
                <div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-200">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-slate-500 inline-block" aria-hidden="true"></span>
                        <h3 class="font-montserrat font-black text-slate-800 text-xs sm:text-sm uppercase tracking-wider">
                            To Do
                        </h3>
                        <span id="badge-pending" 
                              class="px-2 py-0.5 rounded-md text-xs font-black bg-white text-slate-800 border border-slate-200">
                            {{ $pendingTodos->count() }}
                        </span>
                    </div>

                    <button type="button" @click="openCreateModal('pending')" 
                            aria-label="Tambah Kartu di Kolom To Do"
                            class="w-11 h-11 rounded-xl bg-white border border-slate-200 hover:bg-slate-200 text-slate-600 hover:text-slate-900 flex items-center justify-center transition-colors shadow-xs" 
                            title="Tambah Kartu di Kolom Ini">
                        <i class="fa-solid fa-plus text-xs" aria-hidden="true"></i>
                    </button>
                </div>

                <!-- Cards Dropzone -->
                <div id="lane-pending" 
                     data-status="pending" 
                     class="trello-lane space-y-3 min-h-[320px] transition-colors p-0.5 rounded-xl">
                    @forelse($pendingTodos as $item)
                        @include('components.trello-card', ['todo' => $item])
                    @empty
                        <div class="empty-lane-placeholder py-12 text-center text-slate-600 text-xs font-bold space-y-1">
                            <i class="fa-regular fa-clipboard text-slate-500 text-2xl block mb-1" aria-hidden="true"></i>
                            <p>Tidak ada tugas pending</p>
                            <p class="text-xs font-normal text-slate-600">Tarik kartu ke sini atau buat baru</p>
                        </div>
                    @endforelse
                </div>

                <!-- Quick Add Card at Lane Footer -->
                <div class="mt-3 pt-3 border-t border-slate-200">
                    <button type="button" @click="openCreateModal('pending')" 
                            class="w-full py-2.5 px-3 min-h-[44px] rounded-xl bg-white border border-slate-200 text-slate-700 hover:text-[#063f5c] hover:border-primary-400 text-xs font-bold transition-colors flex items-center justify-center gap-1.5">
                        <i class="fa-solid fa-plus text-xs" aria-hidden="true"></i> Tambah Kartu Baru
                    </button>
                </div>
            </div>

            <!-- Lane 2: In Progress (Sedang Berjalan) -->
            <div class="bg-slate-100 border border-slate-200 rounded-2xl p-4 flex flex-col shadow-xs">
                
                <!-- Lane Header -->
                <div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-200">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#f7ad1a] inline-block" aria-hidden="true"></span>
                        <h3 class="font-montserrat font-black text-slate-800 text-xs sm:text-sm uppercase tracking-wider">
                            In Progress
                        </h3>
                        <span id="badge-in_progress" 
                              class="px-2 py-0.5 rounded-md text-xs font-black bg-white text-slate-700 border border-slate-200">
                            {{ $inProgressTodos->count() }}
                        </span>
                    </div>

                    <button type="button" @click="openCreateModal('in_progress')" 
                            aria-label="Tambah Kartu di Kolom In Progress"
                            class="w-11 h-11 rounded-xl bg-white border border-slate-200 hover:bg-slate-200 text-slate-600 hover:text-slate-900 flex items-center justify-center transition-colors shadow-xs" 
                            title="Tambah Kartu di Kolom Ini">
                        <i class="fa-solid fa-plus text-xs" aria-hidden="true"></i>
                    </button>
                </div>

                <!-- Cards Dropzone -->
                <div id="lane-in_progress" 
                     data-status="in_progress" 
                     class="trello-lane space-y-3 min-h-[320px] transition-colors p-0.5 rounded-xl">
                    @forelse($inProgressTodos as $item)
                        @include('components.trello-card', ['todo' => $item])
                    @empty
                        <div class="empty-lane-placeholder py-12 text-center text-slate-600 text-xs font-bold space-y-1">
                            <i class="fa-solid fa-hourglass-half text-slate-500 text-2xl block mb-1" aria-hidden="true"></i>
                            <p>Belum ada yang dikerjakan</p>
                            <p class="text-xs font-normal text-slate-600">Tarik tugas ke sini untuk mulai pengerjaan</p>
                        </div>
                    @endforelse
                </div>

                <!-- Quick Add Card at Lane Footer -->
                <div class="mt-3 pt-3 border-t border-slate-200">
                    <button type="button" @click="openCreateModal('in_progress')" 
                            class="w-full py-2.5 px-3 min-h-[44px] rounded-xl bg-white border border-slate-200 text-slate-700 hover:text-[#063f5c] hover:border-primary-400 text-xs font-bold transition-colors flex items-center justify-center gap-1.5">
                        <i class="fa-solid fa-plus text-xs" aria-hidden="true"></i> Tambah Kartu Baru
                    </button>
                </div>
            </div>

            <!-- Lane 3: Done (Selesai) -->
            <div class="bg-slate-100 border border-slate-200 rounded-2xl p-4 flex flex-col shadow-xs">
                
                <!-- Lane Header -->
                <div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-200">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-700 inline-block" aria-hidden="true"></span>
                        <h3 class="font-montserrat font-black text-slate-800 text-xs sm:text-sm uppercase tracking-wider">
                            Selesai (Done)
                        </h3>
                        <span id="badge-completed" 
                              class="px-2 py-0.5 rounded-md text-xs font-black bg-white text-slate-700 border border-slate-200">
                            {{ $completedTodos->count() }}
                        </span>
                    </div>

                    <button type="button" @click="openCreateModal('completed')" 
                            aria-label="Tambah Kartu di Kolom Selesai"
                            class="w-11 h-11 rounded-xl bg-white border border-slate-200 hover:bg-slate-200 text-slate-600 hover:text-slate-900 flex items-center justify-center transition-colors shadow-xs" 
                            title="Tambah Kartu di Kolom Ini">
                        <i class="fa-solid fa-plus text-xs" aria-hidden="true"></i>
                    </button>
                </div>

                <!-- Cards Dropzone -->
                <div id="lane-completed" 
                     data-status="completed" 
                     class="trello-lane space-y-3 min-h-[320px] transition-colors p-0.5 rounded-xl">
                    @forelse($completedTodos as $item)
                        @include('components.trello-card', ['todo' => $item])
                    @empty
                        <div class="empty-lane-placeholder py-12 text-center text-slate-600 text-xs font-bold space-y-1">
                            <i class="fa-regular fa-circle-check text-slate-500 text-2xl block mb-1" aria-hidden="true"></i>
                            <p>Belum ada tugas selesai</p>
                            <p class="text-xs font-normal text-slate-600">Tarik tugas yang sudah tuntas ke sini</p>
                        </div>
                    @endforelse
                </div>

                <!-- Quick Add Card at Lane Footer -->
                <div class="mt-3 pt-3 border-t border-slate-200">
                    <button type="button" @click="openCreateModal('completed')" 
                            class="w-full py-2.5 px-3 min-h-[44px] rounded-xl bg-white border border-slate-200 text-slate-700 hover:text-[#063f5c] hover:border-primary-400 text-xs font-bold transition-colors flex items-center justify-center gap-1.5">
                        <i class="fa-solid fa-plus text-xs" aria-hidden="true"></i> Tambah Kartu Selesai
                    </button>
                </div>
            </div>

        </div>
    </div>

    <!-- View 2: Classic Table View -->
    @if($showViewToggle)
    <div x-show="currentView === 'table'" x-transition style="display: none;" class="space-y-4">
        <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm overflow-hidden">
            <div class="flex flex-wrap items-center justify-between gap-2 pb-4 border-b border-slate-100">
                <h3 class="font-montserrat font-black text-slate-800 text-sm uppercase tracking-wider">
                    Daftar Tabel Tugas Lengkap
                </h3>
                @if($tableRoute)
                    <a href="{{ $tableRoute }}" class="px-3.5 py-2 min-h-[44px] bg-emerald-700 hover:bg-emerald-800 text-white rounded-xl text-xs font-black uppercase tracking-wider inline-flex items-center gap-1.5 shadow-sm transition-colors">
                        <i class="fa-solid fa-file-excel"></i> Export Data
                    </a>
                @endif
            </div>

            <div class="overflow-x-auto mt-4">
                <table class="w-full text-left text-xs text-slate-700">
                    <thead class="bg-slate-50 text-xs font-black uppercase tracking-wider text-slate-600 border-b border-slate-100">
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
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-3 px-4">
                                <span class="font-bold text-slate-900">{{ $t->task }}</span>
                                @if($t->description)
                                    <p class="text-xs text-slate-600 max-w-sm break-words">{{ $t->description }}</p>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                <span class="text-[#063f5c] font-bold">{{ $t->user->name ?? '-' }}</span>
                            </td>
                            <td class="py-3 px-4 text-slate-600">
                                {{ $t->assigner->name ?? 'Sistem' }}
                            </td>
                            <td class="py-3 px-4">
                                @if($t->status === 'completed')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-slate-50 text-slate-800 border border-slate-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-700 shrink-0" aria-hidden="true"></span> Selesai
                                    </span>
                                @elseif($t->status === 'in_progress')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-slate-50 text-slate-800 border border-slate-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#f7ad1a] shrink-0" aria-hidden="true"></span> In Progress
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-slate-50 text-slate-800 border border-slate-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400 shrink-0" aria-hidden="true"></span> To Do
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-xs font-mono">
                                {{ $t->deadline ? $t->deadline->format('d M Y H:i') : '-' }}
                            </td>
                            <td class="py-3 px-4 text-center">
                                <button type="button" @click="openDetailModal({{ $t->toJson() }})" aria-label="Buka Detail"
                                        class="w-11 h-11 inline-flex items-center justify-center text-primary-700 hover:bg-primary-50 rounded-xl transition-colors" title="Buka Detail">
                                    <i class="fa-solid fa-up-right-from-square"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-slate-600 font-bold">Belum ada tugas yang tercatat.</td>
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

    <!-- Modal 1: Trello Card Detail Modal -->
    <div x-show="detailModal.open" 
         x-cloak
         @keydown.escape.window="detailModal.open = false"
         role="dialog"
         aria-modal="true"
         aria-labelledby="trello-detail-title"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         style="display: none;" 
         class="fixed inset-0 z-[250] flex items-center justify-center p-4 overflow-y-auto">
        <div class="fixed inset-0 bg-slate-950/60" @click="detailModal.open = false"></div>
        
        <div class="relative z-10 bg-white border border-slate-100 w-full max-w-xl rounded-3xl p-6 sm:p-8 shadow-2xl space-y-6 my-8">
            
            <button type="button" @click="detailModal.open = false" aria-label="Tutup modal detail tugas"
                    class="w-11 h-11 inline-flex items-center justify-center rounded-xl absolute top-4 right-4 text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition-colors">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>

            <!-- Modal Header -->
            <div class="space-y-2 pr-10">
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md text-xs font-bold bg-slate-50 text-slate-800 border border-slate-200">
                        <span class="w-1.5 h-1.5 rounded-full shrink-0"
                              :class="detailModal.data.status === 'completed' ? 'bg-emerald-700' : (detailModal.data.status === 'in_progress' ? 'bg-[#f7ad1a]' : 'bg-slate-400')"
                              aria-hidden="true"></span>
                        <span x-text="detailModal.data.status === 'completed' ? 'Selesai (Done)' : (detailModal.data.status === 'in_progress' ? 'In Progress' : 'To Do')"></span>
                    </span>
                    <span class="text-xs text-slate-600 font-bold" x-text="'ID: #' + detailModal.data.id"></span>
                </div>
                <h2 id="trello-detail-title" class="text-lg sm:text-xl font-montserrat font-black text-slate-900 tracking-tight" 
                    x-text="detailModal.data.task"></h2>
            </div>

            <!-- Modal Metadata Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 p-4 bg-slate-50 border border-slate-100 rounded-2xl text-xs">
                <div>
                    <span class="block text-xs font-bold uppercase tracking-wider text-slate-600">Pegawai (Untuk)</span>
                    <span class="font-bold text-slate-800" x-text="detailModal.data.user ? detailModal.data.user.name : '-'"></span>
                </div>
                <div>
                    <span class="block text-xs font-bold uppercase tracking-wider text-slate-600">Pemberi Tugas</span>
                    <span class="font-bold text-slate-800" x-text="detailModal.data.assigner ? detailModal.data.assigner.name : 'Sistem'"></span>
                </div>
                <div>
                    <span class="block text-xs font-bold uppercase tracking-wider text-slate-600">Batas Waktu</span>
                    <span class="font-bold text-red-700" x-text="formatDate(detailModal.data.deadline)"></span>
                </div>
            </div>

            <!-- Deskripsi -->
            <div class="space-y-1.5">
                <span class="text-xs font-black uppercase tracking-wider text-slate-600">Deskripsi / Rincian Penugasan</span>
                <div class="p-3.5 bg-slate-50 border border-slate-100 rounded-2xl text-xs text-slate-700 leading-relaxed min-h-[60px]"
                     x-text="detailModal.data.description || 'Tidak ada catatan deskripsi tambahan.'">
                </div>
            </div>

            <!-- Ubah Status Cepat -->
            <div class="space-y-2">
                <span class="text-xs font-black uppercase tracking-wider text-slate-600">Pindahkan Status Kartu:</span>
                <div class="grid grid-cols-3 gap-2">
                    <button type="button" @click="changeStatusInModal('pending')" 
                            :class="detailModal.data.status === 'pending' ? 'bg-[#063f5c] text-white border-[#063f5c] shadow-xs' : 'bg-white text-slate-700 border-slate-300 hover:bg-slate-50'"
                            class="py-2.5 px-2 min-h-[44px] border rounded-xl text-xs font-black uppercase tracking-wider transition-colors flex items-center justify-center gap-1.5">
                        <i class="fa-regular fa-clipboard" aria-hidden="true"></i> To Do
                    </button>
                    <button type="button" @click="changeStatusInModal('in_progress')" 
                            :class="detailModal.data.status === 'in_progress' ? 'bg-[#063f5c] text-white border-[#063f5c] shadow-xs' : 'bg-white text-slate-700 border-slate-300 hover:bg-slate-50'"
                            class="py-2.5 px-2 min-h-[44px] border rounded-xl text-xs font-black uppercase tracking-wider transition-colors flex items-center justify-center gap-1.5">
                        <i class="fa-solid fa-hourglass-half" aria-hidden="true"></i> In Progress
                    </button>
                    <button type="button" @click="changeStatusInModal('completed')" 
                            :class="detailModal.data.status === 'completed' ? 'bg-[#063f5c] text-white border-[#063f5c] shadow-xs' : 'bg-white text-slate-700 border-slate-300 hover:bg-slate-50'"
                            class="py-2.5 px-2 min-h-[44px] border rounded-xl text-xs font-black uppercase tracking-wider transition-colors flex items-center justify-center gap-1.5">
                        <i class="fa-solid fa-check" aria-hidden="true"></i> Selesai
                    </button>
                </div>
            </div>

            <!-- Bukti & Laporan Hasil (Upload / View) -->
            <div class="border-t border-slate-100 pt-4 space-y-4">
                <span class="text-xs font-black uppercase tracking-wider text-slate-600 block">Laporan &amp; Bukti Hasil Kerja</span>
                
                <!-- Existing Proof Image -->
                <template x-if="detailModal.data.result_attachment">
                    <div class="p-3 bg-slate-50 border border-slate-200 rounded-2xl space-y-2">
                        <span class="text-xs font-bold text-slate-600 uppercase tracking-wider block">Foto Bukti Terlampir:</span>
                        <a :href="detailModal.data.result_attachment" target="_blank" class="block group relative overflow-hidden rounded-xl border border-slate-200 max-h-48">
                            <img :src="detailModal.data.result_attachment" class="w-full object-cover" alt="Bukti Pelaksanaan">
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
                        <label for="trello_detail_note" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Catatan Hasil Pelaksanaan:</label>
                        <textarea id="trello_detail_note" name="result_note" rows="2" 
                                  x-model="detailModal.data.result_note"
                                  placeholder="Tuliskan keterangan penyelesaian tugas lapangan..." 
                                  class="w-full bg-slate-50 border border-slate-300 rounded-2xl p-3 text-xs font-semibold text-slate-800 focus:border-primary-500"></textarea>
                    </div>

                    <div>
                        <label for="trello_detail_attachment" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Unggah / Ganti Foto Bukti:</label>
                        <input id="trello_detail_attachment" type="file" name="result_attachment" accept="image/*" class="w-full bg-slate-50 border border-slate-300 rounded-2xl p-2.5 min-h-[44px] text-xs text-slate-700">
                    </div>

                    <div class="flex items-center justify-between gap-3 pt-2">
                        <!-- Delete Card Button -->
                        <button type="button" 
                                @click="deleteCard(detailModal.data.id)"
                                class="px-3.5 py-2.5 min-h-[44px] text-rose-700 hover:bg-rose-50 rounded-xl text-xs font-bold transition-colors flex items-center gap-1.5">
                            <i class="fa-regular fa-trash-can"></i> Hapus
                        </button>

                        <button type="submit" 
                                class="px-5 py-2.5 min-h-[44px] bg-primary-600 hover:bg-primary-700 text-white rounded-xl text-xs font-black uppercase tracking-wider transition-colors shadow-sm">
                            Simpan Laporan
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <!-- Modal 2: Create New Task Modal -->
    <div x-show="createModal.open" 
         x-cloak
         @keydown.escape.window="createModal.open = false"
         role="dialog"
         aria-modal="true"
         aria-labelledby="trello-create-title"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         style="display: none;" 
         class="fixed inset-0 z-[250] flex items-center justify-center p-4 overflow-y-auto">
        <div class="fixed inset-0 bg-slate-950/60" @click="createModal.open = false"></div>
        
        <div class="relative z-10 bg-white border border-slate-100 w-full max-w-lg rounded-3xl p-6 sm:p-8 shadow-2xl space-y-6 my-8">
            
            <button type="button" @click="createModal.open = false" aria-label="Tutup modal tambah tugas"
                    class="w-11 h-11 inline-flex items-center justify-center rounded-xl absolute top-4 right-4 text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition-colors">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>

            <!-- Modal Header -->
            <div class="space-y-1 pr-10">
                <h2 id="trello-create-title" class="text-lg font-montserrat font-black text-slate-900 uppercase tracking-tight italic">
                    Tambah <span class="text-primary-600">Tugas Baru</span>
                </h2>
                <p class="text-xs text-slate-600 font-bold uppercase tracking-wider">
                    Buat kartu tugas baru untuk kolom <span class="text-primary-700 uppercase" x-text="createModal.targetStatus"></span>
                </p>
            </div>

            <!-- Form -->
            <form action="{{ $storeRoute }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="status" :value="createModal.targetStatus">

                @if($canAssign && $workers->count() > 0)
                <div>
                    <label for="trello_create_user" class="block text-xs font-black uppercase tracking-wider text-slate-600 mb-1.5">Pilih Pegawai / Staf Pelaksana:</label>
                    <select id="trello_create_user" name="user_id" required class="w-full bg-slate-50 border border-slate-300 rounded-2xl px-4 py-3 min-h-[44px] text-xs font-bold text-slate-800 focus:border-primary-500">
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
                    <label for="trello_create_task" class="block text-xs font-black uppercase tracking-wider text-slate-600 mb-1.5">Judul Tugas:</label>
                    <input id="trello_create_task" type="text" name="task" required placeholder="Contoh: Distribusi sembako di Posko A..." 
                           class="w-full bg-slate-50 border border-slate-300 rounded-2xl px-4 py-3 min-h-[44px] text-xs font-bold text-slate-800 focus:border-primary-500">
                </div>

                <div>
                    <label for="trello_create_desc" class="block text-xs font-black uppercase tracking-wider text-slate-600 mb-1.5">Deskripsi Detail:</label>
                    <textarea id="trello_create_desc" name="description" rows="3" placeholder="Rincian arahan, PIC lapangan, atau catatan khusus..." 
                              class="w-full bg-slate-50 border border-slate-300 rounded-2xl p-3 text-xs font-semibold text-slate-800 focus:border-primary-500"></textarea>
                </div>

                <div>
                    <label for="trello_create_deadline" class="block text-xs font-black uppercase tracking-wider text-slate-600 mb-1.5">Batas Waktu (Deadline):</label>
                    <input id="trello_create_deadline" type="datetime-local" name="deadline" 
                           class="w-full bg-slate-50 border border-slate-300 rounded-2xl px-4 py-3 min-h-[44px] text-xs font-bold text-slate-800 focus:border-primary-500">
                </div>

                <div class="pt-2">
                    <button type="submit" 
                            class="w-full min-h-[44px] bg-primary-600 hover:bg-primary-700 text-white font-black text-xs uppercase tracking-wider py-3.5 rounded-2xl shadow-lg shadow-primary-600/10 transition-colors">
                        Simpan &amp; Buat Kartu Tugas
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

