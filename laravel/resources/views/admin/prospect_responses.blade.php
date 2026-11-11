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
        status: 'Aktif',
    },
    openEdit(item) {
        this.editData = {
            id: item.id,
            name: item.name,
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
            @include('admin.sidebar', ['active' => 'prospect_responses'])
        </aside>

        {{-- Main Content --}}
        <div class="space-y-6">

            {{-- Page Header --}}
            <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 shadow-sm flex items-center justify-between gap-4">
                <div class="space-y-1">
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight">Master Respon Prospek</h1>
                    <p class="text-xs font-semibold text-slate-600 uppercase tracking-widest">Admin Management &rarr; Data Respon Prospek</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-primary-50 flex items-center justify-center text-primary-700 shadow-sm">
                    <i class="fa-solid fa-reply-all text-lg" aria-hidden="true"></i>
                </div>
            </div>

            {{-- Flash Messages --}}
            @if(session('success'))
                <div role="status" class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-3.5 rounded-2xl text-sm font-semibold flex items-center gap-3">
                    <i class="fa-solid fa-circle-check text-emerald-700" aria-hidden="true"></i>
                    {{ session('success') }}
                </div>
            @endif
            @if($errors->any())
                <div role="alert" class="bg-rose-50 border border-rose-200 text-rose-800 px-5 py-3.5 rounded-2xl text-sm font-semibold flex items-start gap-3">
                    <i class="fa-solid fa-circle-exclamation text-rose-700 mt-0.5" aria-hidden="true"></i>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Filter & Toolbar Card --}}
            <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">

                {{-- Filter Form --}}
                <form method="GET" action="{{ route('admin.prospect_responses') }}" class="flex flex-wrap items-center gap-3 text-xs font-bold text-slate-700 pb-5 border-b border-slate-200">

                    {{-- Keyword --}}
                    <div class="flex items-center gap-1.5 min-w-[220px] flex-1 sm:flex-none">
                        <label for="pr_filter_keyword" class="text-slate-600 font-bold whitespace-nowrap">Keyword :</label>
                        <input id="pr_filter_keyword" type="text"
                               name="keyword"
                               value="{{ request('keyword') }}"
                               placeholder="Keyword..."
                               class="w-full min-h-[44px] px-3.5 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-600 placeholder:text-slate-500">
                    </div>

                    {{-- Status --}}
                    <div class="flex items-center gap-1.5 min-w-[140px]">
                        <label for="pr_filter_status" class="text-slate-600 font-bold whitespace-nowrap">Status :</label>
                        <select id="pr_filter_status" name="status" class="w-full min-h-[44px] px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-600">
                            <option value="">Semua</option>
                            <option value="Aktif" {{ request('status') === 'Aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="Non-Aktif" {{ request('status') === 'Non-Aktif' ? 'selected' : '' }}>Non-Aktif</option>
                        </select>
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center gap-2 ml-auto">
                        <button type="submit" class="inline-flex items-center gap-1.5 min-h-[44px] px-4 py-2 bg-slate-900 hover:bg-slate-950 text-white rounded-xl text-xs font-bold transition-colors shadow-sm">
                            <i class="fa-solid fa-magnifying-glass text-xs" aria-hidden="true"></i> Search
                        </button>
                        <a href="{{ route('admin.prospect_responses') }}" aria-label="Reset filter" class="w-11 h-11 inline-flex items-center justify-center bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 rounded-xl transition-colors" title="Reset Filter">
                            <i class="fa-solid fa-rotate text-xs" aria-hidden="true"></i>
                        </a>
                    </div>

                </form>

                {{-- Toolbar --}}
                <div class="flex items-center justify-between gap-4">
                    <button type="button" @click="showAddModal = true"
                            class="inline-flex items-center gap-2 min-h-[44px] px-5 py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-2xl text-xs font-black uppercase tracking-wider shadow-lg shadow-primary-500/20 transition-colors">
                        <i class="fa-solid fa-plus text-xs" aria-hidden="true"></i> New Respon Prospek
                    </button>
                    <div class="text-xs font-semibold text-slate-600">
                        Total: <span class="font-bold text-slate-900">{{ $prospectResponses->total() }}</span> Respon Prospek
                    </div>
                </div>

                {{-- Table --}}
                <div class="overflow-x-auto -mx-4 sm:mx-0 px-4 sm:px-0">
                    <table class="w-full min-w-[340px] text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-200 text-xs font-black uppercase tracking-wider text-slate-600">
                                <th class="pb-4 w-8">#</th>
                                <th class="pb-4">Respon Prospek</th>
                                <th class="pb-4 text-center">Aktif</th>
                                <th class="pb-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            @forelse($prospectResponses as $index => $item)
                                <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors group">
                                    <td class="py-4 text-slate-600 text-xs font-bold">
                                        {{ $prospectResponses->firstItem() + $index }}
                                    </td>
                                    <td class="py-4">
                                        <span class="font-bold text-slate-900">{{ $item->name }}</span>
                                    </td>
                                    <td class="py-4 text-center">
                                        @if($item->status === 'Aktif')
                                            <span class="text-xs font-bold text-emerald-700">y</span>
                                        @else
                                            <span class="text-xs font-bold text-slate-600">n</span>
                                        @endif
                                    </td>
                                    <td class="py-4">
                                        <div class="flex items-center justify-end gap-1.5">
                                            <button type="button" @click="openEdit({{ json_encode($item) }})"
                                                    aria-label="Edit {{ $item->name }}"
                                                    class="w-11 h-11 inline-flex items-center justify-center text-amber-700 hover:bg-amber-50 rounded-lg transition-colors" title="Edit">
                                                <i class="fa-solid fa-pen-to-square text-sm" aria-hidden="true"></i>
                                            </button>
                                            <button type="button" @click="openDelete({{ $item->id }}, '{{ addslashes($item->name) }}')"
                                                    aria-label="Hapus {{ $item->name }}"
                                                    class="w-11 h-11 inline-flex items-center justify-center text-rose-700 hover:bg-rose-50 rounded-lg transition-colors" title="Hapus">
                                                <i class="fa-solid fa-trash-can text-sm" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-14 text-center text-slate-600 font-bold uppercase tracking-wider">
                                        <i class="fa-solid fa-folder-open text-3xl block text-slate-500 mb-3" aria-hidden="true"></i>
                                        Belum ada data respon prospek.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="pt-2">
                    {{ $prospectResponses->links() }}
                </div>

            </div>{{-- end card --}}

        </div>{{-- end main --}}

    </div>{{-- end grid --}}

    {{-- Modal: Tambah Respon Prospek --}}
    <div x-show="showAddModal"
         @keydown.escape.window="showAddModal = false"
         role="dialog" aria-modal="true" aria-labelledby="modal-add-pr-title"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="display: none;">
        <div class="absolute inset-0 bg-slate-900/65" @click="showAddModal = false"></div>
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md p-8 space-y-5 z-10">
            <div class="flex items-center justify-between">
                <div>
                    <h2 id="modal-add-pr-title" class="text-lg font-black text-slate-900">Tambah Respon Prospek</h2>
                    <p class="text-xs text-slate-600 font-semibold mt-0.5">Isi data respon prospek baru</p>
                </div>
                <button type="button" @click="showAddModal = false" aria-label="Tutup modal tambah respon prospek" class="min-w-[44px] min-h-[44px] inline-flex items-center justify-center rounded-xl hover:bg-slate-100 text-slate-600 hover:text-slate-900 transition-colors">
                    <i class="fa-solid fa-xmark" aria-hidden="true"></i>
                </button>
            </div>
            <form method="POST" action="{{ route('admin.prospect_responses.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="add_pr_name" class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-1.5">Nama Respon Prospek <span class="text-rose-700">*</span></label>
                    <input id="add_pr_name" type="text" name="name" required placeholder="Contoh: Read, Centang 2, Donasi / Closing"
                           class="w-full min-h-[44px] px-4 py-3 bg-slate-50 border border-slate-300 rounded-2xl text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-primary-600/20 focus:border-primary-600 placeholder:text-slate-500">
                </div>
                <div>
                    <label for="add_pr_status" class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-1.5">Status <span class="text-rose-700">*</span></label>
                    <select id="add_pr_status" name="status" required
                            class="w-full min-h-[44px] px-4 py-3 bg-slate-50 border border-slate-300 rounded-2xl text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-primary-600/20 focus:border-primary-600">
                        <option value="Aktif">Aktif</option>
                        <option value="Non-Aktif">Non-Aktif</option>
                    </select>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="button" @click="showAddModal = false"
                            class="flex-1 min-h-[44px] py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-2xl text-sm font-bold transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-1 min-h-[44px] py-3 bg-primary-600 hover:bg-primary-700 text-white rounded-2xl text-sm font-black shadow-sm transition-colors">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal: Edit Respon Prospek --}}
    <div x-show="showEditModal"
         @keydown.escape.window="showEditModal = false"
         role="dialog" aria-modal="true" aria-labelledby="modal-edit-pr-title"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="display: none;">
        <div class="absolute inset-0 bg-slate-900/65" @click="showEditModal = false"></div>
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md p-8 space-y-5 z-10">
            <div class="flex items-center justify-between">
                <div>
                    <h2 id="modal-edit-pr-title" class="text-lg font-black text-slate-900">Edit Respon Prospek</h2>
                    <p class="text-xs text-slate-600 font-semibold mt-0.5">Perbarui data respon prospek</p>
                </div>
                <button type="button" @click="showEditModal = false" aria-label="Tutup modal edit respon prospek" class="min-w-[44px] min-h-[44px] inline-flex items-center justify-center rounded-xl hover:bg-slate-100 text-slate-600 hover:text-slate-900 transition-colors">
                    <i class="fa-solid fa-xmark" aria-hidden="true"></i>
                </button>
            </div>
            <form method="POST" :action="`{{ url('admin/prospect-responses') }}/${editData.id}/update`" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label for="edit_pr_name" class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-1.5">Nama Respon Prospek <span class="text-rose-700">*</span></label>
                    <input id="edit_pr_name" type="text" name="name" x-model="editData.name" required
                           class="w-full min-h-[44px] px-4 py-3 bg-slate-50 border border-slate-300 rounded-2xl text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-primary-600/20 focus:border-primary-600">
                </div>
                <div>
                    <label for="edit_pr_status" class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-1.5">Status <span class="text-rose-700">*</span></label>
                    <select id="edit_pr_status" name="status" x-model="editData.status" required
                            class="w-full min-h-[44px] px-4 py-3 bg-slate-50 border border-slate-300 rounded-2xl text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-primary-600/20 focus:border-primary-600">
                        <option value="Aktif">Aktif</option>
                        <option value="Non-Aktif">Non-Aktif</option>
                    </select>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="button" @click="showEditModal = false"
                            class="flex-1 min-h-[44px] py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-2xl text-sm font-bold transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-1 min-h-[44px] py-3 bg-primary-600 hover:bg-primary-700 text-white rounded-2xl text-sm font-black shadow-sm transition-colors">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal: Hapus Respon Prospek --}}
    <div x-show="showDeleteModal"
         @keydown.escape.window="showDeleteModal = false"
         role="dialog" aria-modal="true" aria-labelledby="modal-delete-pr-title"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="display: none;">
        <div class="absolute inset-0 bg-slate-900/65" @click="showDeleteModal = false"></div>
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-sm p-8 text-center space-y-5 z-10">
            <div class="w-16 h-16 bg-rose-50 rounded-2xl flex items-center justify-center text-rose-700 text-2xl mx-auto">
                <i class="fa-solid fa-triangle-exclamation" aria-hidden="true"></i>
            </div>
            <div>
                <h2 id="modal-delete-pr-title" class="text-lg font-black text-slate-900">Hapus Respon Prospek?</h2>
                <p class="text-sm text-slate-600 font-semibold mt-1">
                    Data "<span class="text-slate-900 font-black" x-text="selectedName"></span>" akan dihapus permanen.
                </p>
            </div>
            <form method="POST" :action="`{{ url('admin/prospect-responses') }}/${selectedId}/delete`">
                @csrf
                @method('DELETE')
                <div class="flex gap-3">
                    <button type="button" @click="showDeleteModal = false"
                            class="flex-1 min-h-[44px] py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-2xl text-sm font-bold transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-1 min-h-[44px] py-3 bg-rose-700 hover:bg-rose-800 text-white rounded-2xl text-sm font-black shadow-sm transition-colors">
                        Ya, Hapus
                    </button>
                </div>
            </form>
        </div>
    </div>

</section>
@endsection
