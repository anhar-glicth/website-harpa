@extends('layouts.app')

@section('content')
<section class="max-w-7xl mx-auto px-6 py-20" x-data="{ 
    showAddModal: false, 
    showEditModal: false, 
    showDeleteModal: false,
    selectedId: null,
    selectedName: '',
    editData: {
        id: null,
        name: '',
        code: '',
        status: 'Aktif'
    },
    openEdit(item) {
        this.editData = {
            id: item.id,
            name: item.name,
            code: item.code || '',
            status: item.status
        };
        this.showEditModal = true;
    },
    openDelete(id, name) {
        this.selectedId = id;
        this.selectedName = name;
        this.showDeleteModal = true;
    }
}">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- Sidebar Menu -->
        <div class="hidden lg:block lg:col-span-1 space-y-6">
            @include('admin.sidebar', ['active' => 'donor_channels'])
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-3 space-y-8">
            <!-- Header Page Title -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="space-y-2">
                    <h1 class="text-2xl sm:text-3xl font-montserrat font-black text-slate-900 uppercase tracking-tight italic">
                        Master <span class="text-primary-600">Channel Donasi</span>
                    </h1>
                    <div class="w-12 h-1 bg-primary-500 rounded-full"></div>
                </div>
            </div>

            <!-- Flash Alerts -->
            @if(session('success'))
                <div role="status" class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center justify-between text-xs font-bold shadow-sm">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-circle-check text-emerald-700 text-base"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button type="button" @click="$el.parentElement.remove()" aria-label="Tutup notifikasi" class="w-11 h-11 -my-2 inline-flex items-center justify-center rounded-xl text-emerald-700 hover:bg-emerald-100">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif

            @if($errors->any())
                <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl space-y-1 text-xs font-bold shadow-sm">
                    <div class="flex items-center gap-2 text-rose-700">
                        <i class="fa-solid fa-triangle-exclamation text-base"></i>
                        <span>Terjadi kesalahan input:</span>
                    </div>
                    <ul class="list-disc list-inside text-[11px] font-medium text-rose-600 pl-4 space-y-0.5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Main Card Container -->
            <div class="bg-white border border-slate-100 rounded-3xl p-6 sm:p-8 space-y-6 shadow-sm">
                
                <!-- Toolbar & Filter Bar -->
                <form method="GET" action="{{ route('admin.donor_channels') }}" class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-4 border-b border-slate-100">
                    
                    <!-- New Button -->
                    <div class="flex items-center gap-3">
                        <button type="button" @click="showAddModal = true" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-2xl text-xs font-black uppercase tracking-wider shadow-lg shadow-primary-500/20 transition-all duration-300">
                            <i class="fa-solid fa-plus text-xs"></i> New Channel
                        </button>
                    </div>

                    <!-- Search Controls -->
                    <div class="flex flex-wrap items-center gap-3 text-xs font-bold text-slate-700">
                        
                        <!-- Keyword Input -->
                        <div class="relative min-w-[180px]">
                            <label for="channel_search_keyword" class="sr-only">Cari kata kunci channel</label>
                            <input id="channel_search_keyword" type="text" 
                                   name="keyword" 
                                   value="{{ request('keyword') }}" 
                                   placeholder="Keyword..." 
                                   class="w-full min-h-[44px] px-4 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-600 placeholder:text-slate-500 transition-colors">
                        </div>

                        <!-- Status Filter -->
                        <div class="flex items-center gap-2">
                            <label for="channel_filter_status" class="text-slate-600 font-bold whitespace-nowrap text-xs">Status :</label>
                            <select id="channel_filter_status" name="status" class="min-h-[44px] px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-600">
                                <option value="">Semua</option>
                                <option value="Aktif" {{ request('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="Non-Aktif" {{ request('status') == 'Non-Aktif' ? 'selected' : '' }}>Non-Aktif</option>
                            </select>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center gap-2">
                            <button type="submit" class="inline-flex items-center gap-2 min-h-[44px] px-4 py-2 bg-slate-900 hover:bg-slate-950 text-white rounded-xl text-xs font-bold transition-colors shadow-sm">
                                <i class="fa-solid fa-magnifying-glass text-xs" aria-hidden="true"></i> Search
                            </button>
                            <a href="{{ route('admin.donor_channels') }}" aria-label="Reset filter" class="w-11 h-11 inline-flex items-center justify-center bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 rounded-xl transition-colors" title="Reset Filter">
                                <i class="fa-solid fa-rotate text-xs" aria-hidden="true"></i>
                            </a>
                        </div>
                    </div>

                </form>

                <!-- Data Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-200 text-xs font-black uppercase tracking-wider text-slate-600">
                                <th class="pb-4 w-12 text-center">#</th>
                                <th class="pb-4">Channel Donasi</th>
                                <th class="pb-4">Kode Channel</th>
                                <th class="pb-4 text-center w-24">Status (Aktif)</th>
                                <th class="pb-4 text-right w-28">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-xs font-semibold text-slate-700">
                            @forelse($channels as $index => $item)
                                <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                    <td class="py-4 text-center text-slate-600 font-mono text-xs">
                                        {{ $channels->firstItem() + $index }}
                                    </td>
                                    <td class="py-4 font-bold text-slate-900">
                                        {{ $item->name }}
                                    </td>
                                    <td class="py-4 font-mono text-xs text-slate-600">
                                        <span class="bg-slate-100 text-slate-700 px-2.5 py-1 rounded-lg text-xs font-bold uppercase">
                                            {{ $item->code ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="py-4 text-center">
                                        @if($item->status === 'Aktif')
                                            <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-black uppercase tracking-wider px-2.5 py-1 rounded-lg">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-700"></span> y (Aktif)
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 bg-rose-50 text-rose-700 border border-rose-200 text-xs font-black uppercase tracking-wider px-2.5 py-1 rounded-lg">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-700"></span> n (Non-Aktif)
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-4 text-right">
                                        <div class="flex items-center justify-end gap-1.5">
                                            <button type="button" @click="openEdit({{ json_encode($item) }})" aria-label="Edit channel {{ $item->name }}" class="w-11 h-11 inline-flex items-center justify-center text-amber-700 hover:bg-amber-50 rounded-xl transition-colors" title="Edit Channel">
                                                <i class="fa-solid fa-pen-to-square text-sm" aria-hidden="true"></i>
                                            </button>
                                            <button type="button" @click="openDelete({{ $item->id }}, '{{ $item->name }}')" aria-label="Hapus channel {{ $item->name }}" class="w-11 h-11 inline-flex items-center justify-center text-rose-700 hover:bg-rose-50 rounded-xl transition-colors" title="Hapus Channel">
                                                <i class="fa-solid fa-trash-can text-sm" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-12 text-center text-slate-600 font-bold uppercase tracking-wider space-y-2">
                                        <i class="fa-solid fa-folder-open text-3xl block text-slate-500 mb-2" aria-hidden="true"></i>
                                        <span>Belum ada channel donasi yang sesuai filter.</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Footer Pagination -->
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-4 border-t border-slate-100 text-xs font-semibold text-slate-500">
                    <div>
                        Displaying <span class="font-bold text-slate-900">{{ $channels->firstItem() ?? 0 }}</span> to <span class="font-bold text-slate-900">{{ $channels->lastItem() ?? 0 }}</span> of <span class="font-bold text-slate-900">{{ $channels->total() }}</span> items
                    </div>
                    <div>
                        {{ $channels->links() }}
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Modal Add Channel -->
    <div x-show="showAddModal" 
         x-cloak 
         @keydown.escape.window="showAddModal = false"
         role="dialog"
         aria-modal="true"
         aria-labelledby="add-channel-title"
         class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60" @click="showAddModal = false"></div>
        <div class="relative z-10 bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 space-y-6 shadow-2xl overflow-hidden">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-primary-50 text-primary-600 flex items-center justify-center text-lg shadow-sm">
                        <i class="fa-solid fa-plus"></i>
                    </div>
                    <div>
                        <h2 id="add-channel-title" class="text-base font-black text-slate-900 uppercase tracking-tight">Tambah Channel Donasi</h2>
                        <p class="text-xs text-slate-600 font-medium">Buat channel donasi baru</p>
                    </div>
                </div>
                <button type="button" @click="showAddModal = false" aria-label="Tutup modal tambah channel" class="w-11 h-11 inline-flex items-center justify-center text-slate-600 hover:bg-slate-100 rounded-xl">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('admin.donor_channels.store') }}" class="space-y-4 text-xs font-semibold">
                @csrf
                <div>
                    <label for="add_channel_name" class="block text-slate-700 mb-1">Nama Channel Donasi <span class="text-rose-700">*</span></label>
                    <input id="add_channel_name" type="text" name="name" placeholder="e.g. WEB, Retail, Sekolah" required class="w-full px-3.5 py-2.5 min-h-[44px] border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500">
                </div>

                <div>
                    <label for="add_channel_code" class="block text-slate-700 mb-1">Kode Channel</label>
                    <input id="add_channel_code" type="text" name="code" placeholder="e.g. WEB, RTL, SKL" class="w-full px-3.5 py-2.5 min-h-[44px] border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500">
                </div>

                <div>
                    <label for="add_channel_status" class="block text-slate-700 mb-1">Status <span class="text-rose-700">*</span></label>
                    <select id="add_channel_status" name="status" required class="w-full px-3.5 py-2.5 min-h-[44px] border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 bg-white">
                        <option value="Aktif">Aktif (y)</option>
                        <option value="Non-Aktif">Non-Aktif (n)</option>
                    </select>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" @click="showAddModal = false" class="px-5 py-2.5 min-h-[44px] bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 min-h-[44px] bg-primary-600 hover:bg-primary-700 text-white rounded-xl font-bold shadow-lg shadow-primary-500/20 transition-colors">
                        Simpan Channel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Channel -->
    <div x-show="showEditModal" 
         x-cloak 
         @keydown.escape.window="showEditModal = false"
         role="dialog"
         aria-modal="true"
         aria-labelledby="edit-channel-title"
         class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60" @click="showEditModal = false"></div>
        <div class="relative z-10 bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 space-y-6 shadow-2xl overflow-hidden">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-700 flex items-center justify-center text-lg shadow-sm">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </div>
                    <div>
                        <h2 id="edit-channel-title" class="text-base font-black text-slate-900 uppercase tracking-tight">Edit Channel Donasi</h2>
                        <p class="text-xs text-slate-600 font-medium">Perbarui informasi channel donasi</p>
                    </div>
                </div>
                <button type="button" @click="showEditModal = false" aria-label="Tutup modal edit channel" class="w-11 h-11 inline-flex items-center justify-center text-slate-600 hover:bg-slate-100 rounded-xl">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form method="POST" :action="'{{ url('admin/donor-channels') }}/' + editData.id + '/update'" class="space-y-4 text-xs font-semibold">
                @csrf
                @method('PUT')
                <div>
                    <label for="edit_channel_name" class="block text-slate-700 mb-1">Nama Channel Donasi <span class="text-rose-700">*</span></label>
                    <input id="edit_channel_name" type="text" name="name" x-model="editData.name" required class="w-full px-3.5 py-2.5 min-h-[44px] border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500">
                </div>

                <div>
                    <label for="edit_channel_code" class="block text-slate-700 mb-1">Kode Channel</label>
                    <input id="edit_channel_code" type="text" name="code" x-model="editData.code" class="w-full px-3.5 py-2.5 min-h-[44px] border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500">
                </div>

                <div>
                    <label for="edit_channel_status" class="block text-slate-700 mb-1">Status <span class="text-rose-700">*</span></label>
                    <select id="edit_channel_status" name="status" x-model="editData.status" required class="w-full px-3.5 py-2.5 min-h-[44px] border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 bg-white">
                        <option value="Aktif">Aktif (y)</option>
                        <option value="Non-Aktif">Non-Aktif (n)</option>
                    </select>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" @click="showEditModal = false" class="px-5 py-2.5 min-h-[44px] bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 min-h-[44px] bg-amber-700 hover:bg-amber-800 text-white rounded-xl font-bold shadow-lg shadow-amber-500/20 transition-colors">
                        Update Channel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Delete Channel -->
    <div x-show="showDeleteModal" 
         x-cloak 
         @keydown.escape.window="showDeleteModal = false"
         role="dialog"
         aria-modal="true"
         aria-labelledby="delete-channel-title"
         class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60" @click="showDeleteModal = false"></div>
        <div class="relative z-10 bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 text-center space-y-6 shadow-2xl">
            <div class="w-16 h-16 bg-rose-50 text-rose-700 rounded-2xl flex items-center justify-center text-2xl mx-auto shadow-sm">
                <i class="fa-solid fa-trash-can"></i>
            </div>
            
            <div class="space-y-2">
                <h2 id="delete-channel-title" class="text-lg font-black text-slate-900 uppercase tracking-tight">Hapus Channel Donasi?</h2>
                <p class="text-xs text-slate-600 font-medium">
                    Apakah Anda yakin ingin menghapus channel <span class="font-bold text-slate-900" x-text="selectedName"></span>? Tindakan ini tidak dapat dibatalkan.
                </p>
            </div>

            <form method="POST" :action="'{{ url('admin/donor-channels') }}/' + selectedId + '/delete'" class="flex items-center justify-center gap-3 pt-2">
                @csrf
                @method('DELETE')
                <button type="button" @click="showDeleteModal = false" class="px-5 py-2.5 min-h-[44px] bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 min-h-[44px] bg-rose-700 hover:bg-rose-800 text-white rounded-xl text-xs font-bold shadow-lg shadow-rose-100 transition-colors">
                    Ya, Hapus Channel
                </button>
            </form>
        </div>
    </div>

</section>
@endsection
