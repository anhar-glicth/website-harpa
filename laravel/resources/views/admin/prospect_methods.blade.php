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
        description: '',
        status: 'Aktif',
    },
    openEdit(item) {
        this.editData = {
            id: item.id,
            name: item.name,
            description: item.description ?? '',
            status: item.status,
        };
        this.showEditModal = true;
    },
    openDelete(id, name) {
        this.selectedId = id;
        this.selectedName = name;
        this.showDeleteModal = true;
    }
}">

    <div class="grid grid-cols-1 lg:grid-cols-[280px_1fr] gap-8 items-start">

        {{-- Sidebar --}}
        <aside class="hidden lg:block space-y-5">
            @include('admin.sidebar', ['active' => 'prospect_methods'])
        </aside>

        {{-- Main Content --}}
        <div class="space-y-6">

            {{-- Page Header --}}
            <div class="bg-white border border-slate-100 rounded-3xl p-6 sm:p-8 shadow-sm flex items-center justify-between gap-4">
                <div class="space-y-1">
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight">Master Metode Prospek</h1>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest">Admin Management → Data Metode Prospek</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-primary-50 flex items-center justify-center text-primary-600 shadow-sm">
                    <i class="fa-solid fa-handshake-angle text-lg"></i>
                </div>
            </div>

            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-3.5 rounded-2xl text-sm font-semibold flex items-center gap-3">
                    <i class="fa-solid fa-circle-check text-emerald-500"></i>
                    {{ session('success') }}
                </div>
            @endif
            @if($errors->any())
                <div class="bg-rose-50 border border-rose-200 text-rose-800 px-5 py-3.5 rounded-2xl text-sm font-semibold flex items-start gap-3">
                    <i class="fa-solid fa-circle-exclamation text-rose-500 mt-0.5"></i>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Filter & Toolbar Card --}}
            <div class="bg-white border border-slate-100 rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">

                {{-- Filter Form --}}
                <form method="GET" action="{{ route('admin.prospect_methods') }}" class="flex flex-wrap items-center gap-3 text-xs font-bold text-slate-700 pb-5 border-b border-slate-100">

                    {{-- Keyword --}}
                    <div class="flex items-center gap-1.5 min-w-[220px] flex-1 sm:flex-none">
                        <span class="text-slate-500 font-bold whitespace-nowrap">Keyword :</span>
                        <input type="text"
                               name="keyword"
                               value="{{ request('keyword') }}"
                               placeholder="Keyword..."
                               class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 placeholder:text-slate-400">
                    </div>

                    {{-- Status --}}
                    <div class="flex items-center gap-1.5 min-w-[140px]">
                        <span class="text-slate-500 font-bold whitespace-nowrap">Status :</span>
                        <select name="status" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500">
                            <option value="">Semua</option>
                            <option value="Aktif" {{ request('status') === 'Aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="Non-Aktif" {{ request('status') === 'Non-Aktif' ? 'selected' : '' }}>Non-Aktif</option>
                        </select>
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center gap-2 ml-auto">
                        <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-900 hover:bg-slate-950 text-white rounded-xl text-xs font-bold transition-all shadow-sm">
                            <i class="fa-solid fa-magnifying-glass text-xs"></i> Search
                        </button>
                        <a href="{{ route('admin.prospect_methods') }}" class="p-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition-all" title="Reset Filter">
                            <i class="fa-solid fa-rotate text-xs"></i>
                        </a>
                    </div>

                </form>

                {{-- Toolbar --}}
                <div class="flex items-center justify-between gap-4">
                    <button @click="showAddModal = true"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-2xl text-xs font-black uppercase tracking-wider shadow-lg shadow-primary-500/20 transition-all duration-300">
                        <i class="fa-solid fa-plus text-xs"></i> New Metode Prospek
                    </button>
                    <div class="text-xs font-semibold text-slate-500">
                        Total: <span class="font-bold text-slate-900">{{ $prospectMethods->total() }}</span> Metode Prospek
                    </div>
                </div>

                {{-- Table --}}
                <div class="overflow-x-auto -mx-4 sm:mx-0 px-4 sm:px-0">
                    <table class="w-full min-w-[500px] text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 text-[10px] font-black uppercase tracking-wider text-slate-400">
                                <th class="pb-4 w-8">#</th>
                                <th class="pb-4">Metode Prospek</th>
                                <th class="pb-4">Deskripsi</th>
                                <th class="pb-4 text-center">Aktif</th>
                                <th class="pb-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            @forelse($prospectMethods as $index => $item)
                                <tr class="border-b border-slate-50 hover:bg-slate-50/60 transition-colors group">
                                    <td class="py-4 text-slate-400 text-xs font-bold">
                                        {{ $prospectMethods->firstItem() + $index }}
                                    </td>
                                    <td class="py-4">
                                        <span class="font-bold text-slate-900">{{ $item->name }}</span>
                                    </td>
                                    <td class="py-4">
                                        <span class="text-slate-500 text-xs">{{ Str::limit($item->description, 60) ?: '—' }}</span>
                                    </td>
                                    <td class="py-4 text-center">
                                        @if($item->status === 'Aktif')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 text-[11px] font-black">
                                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span> y
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-slate-100 text-slate-500 text-[11px] font-black">
                                                <span class="w-1.5 h-1.5 bg-slate-400 rounded-full"></span> n
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-4">
                                        <div class="flex items-center justify-end gap-1.5">
                                            <button @click="openEdit({{ json_encode($item) }})"
                                                    class="p-1.5 text-amber-600 hover:bg-amber-50 rounded-lg transition-all" title="Edit">
                                                <i class="fa-solid fa-pen-to-square text-sm"></i>
                                            </button>
                                            <button @click="openDelete({{ $item->id }}, '{{ addslashes($item->name) }}')"
                                                    class="p-1.5 text-rose-600 hover:bg-rose-50 rounded-lg transition-all" title="Hapus">
                                                <i class="fa-solid fa-trash-can text-sm"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-14 text-center text-slate-400 font-bold uppercase tracking-wider">
                                        <i class="fa-solid fa-folder-open text-3xl block text-slate-300 mb-3"></i>
                                        Belum ada data metode prospek.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="pt-2">
                    {{ $prospectMethods->links() }}
                </div>

            </div>{{-- end card --}}

        </div>{{-- end main --}}

    </div>{{-- end grid --}}

    {{-- ============ MODAL: ADD METODE PROSPEK ============ --}}
    <div x-show="showAddModal"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="display: none;">
        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" @click="showAddModal = false"></div>
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md p-8 space-y-5 z-10">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-black text-slate-900">Tambah Metode Prospek</h3>
                    <p class="text-xs text-slate-400 font-semibold mt-0.5">Isi data metode prospek baru</p>
                </div>
                <button @click="showAddModal = false" class="p-2 rounded-xl hover:bg-slate-100 text-slate-400 transition-all">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <form method="POST" action="{{ route('admin.prospect_methods.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-black text-slate-600 uppercase tracking-wider mb-1.5">Nama Metode Prospek <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" required placeholder="Contoh: Presentasi, Blast / Broadcast"
                           class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 placeholder:text-slate-400">
                </div>
                <div>
                    <label class="block text-xs font-black text-slate-600 uppercase tracking-wider mb-1.5">Deskripsi</label>
                    <textarea name="description" rows="3" placeholder="Deskripsi singkat metode prospek..."
                              class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 placeholder:text-slate-400 resize-none"></textarea>
                </div>
                <div>
                    <label class="block text-xs font-black text-slate-600 uppercase tracking-wider mb-1.5">Status <span class="text-rose-500">*</span></label>
                    <select name="status" required
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500">
                        <option value="Aktif">Aktif</option>
                        <option value="Non-Aktif">Non-Aktif</option>
                    </select>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="button" @click="showAddModal = false"
                            class="flex-1 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-2xl text-sm font-bold transition-all">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-1 py-3 bg-primary-600 hover:bg-primary-700 text-white rounded-2xl text-sm font-black shadow-lg shadow-primary-500/20 transition-all">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ============ MODAL: EDIT METODE PROSPEK ============ --}}
    <div x-show="showEditModal"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="display: none;">
        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" @click="showEditModal = false"></div>
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md p-8 space-y-5 z-10">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-black text-slate-900">Edit Metode Prospek</h3>
                    <p class="text-xs text-slate-400 font-semibold mt-0.5">Perbarui data metode prospek</p>
                </div>
                <button @click="showEditModal = false" class="p-2 rounded-xl hover:bg-slate-100 text-slate-400 transition-all">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <form method="POST" :action="`{{ url('admin/prospect-methods') }}/${editData.id}/update`" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-black text-slate-600 uppercase tracking-wider mb-1.5">Nama Metode Prospek <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" x-model="editData.name" required
                           class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500">
                </div>
                <div>
                    <label class="block text-xs font-black text-slate-600 uppercase tracking-wider mb-1.5">Deskripsi</label>
                    <textarea name="description" rows="3" x-model="editData.description"
                              class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 resize-none"></textarea>
                </div>
                <div>
                    <label class="block text-xs font-black text-slate-600 uppercase tracking-wider mb-1.5">Status <span class="text-rose-500">*</span></label>
                    <select name="status" x-model="editData.status" required
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500">
                        <option value="Aktif">Aktif</option>
                        <option value="Non-Aktif">Non-Aktif</option>
                    </select>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="button" @click="showEditModal = false"
                            class="flex-1 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-2xl text-sm font-bold transition-all">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-1 py-3 bg-primary-600 hover:bg-primary-700 text-white rounded-2xl text-sm font-black shadow-lg shadow-primary-500/20 transition-all">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ============ MODAL: DELETE ============ --}}
    <div x-show="showDeleteModal"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="display: none;">
        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" @click="showDeleteModal = false"></div>
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-sm p-8 text-center space-y-5 z-10">
            <div class="w-16 h-16 bg-rose-50 rounded-2xl flex items-center justify-center text-rose-500 text-2xl mx-auto">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div>
                <h3 class="text-lg font-black text-slate-900">Hapus Metode Prospek?</h3>
                <p class="text-sm text-slate-500 font-semibold mt-1">
                    Data "<span class="text-slate-800 font-black" x-text="selectedName"></span>" akan dihapus permanen.
                </p>
            </div>
            <form method="POST" :action="`{{ url('admin/prospect-methods') }}/${selectedId}/delete`">
                @csrf
                @method('DELETE')
                <div class="flex gap-3">
                    <button type="button" @click="showDeleteModal = false"
                            class="flex-1 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-2xl text-sm font-bold transition-all">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-1 py-3 bg-rose-600 hover:bg-rose-700 text-white rounded-2xl text-sm font-black shadow-lg shadow-rose-500/20 transition-all">
                        Ya, Hapus
                    </button>
                </div>
            </form>
        </div>
    </div>

</section>
@endsection
