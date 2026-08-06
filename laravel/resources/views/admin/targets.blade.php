@extends('layouts.app')

@section('content')
<section class="max-w-7xl mx-auto px-6 py-20" x-data="{
    showAddModal: false,
    showEditModal: false,
    showDeleteModal: false,
    selectedId: null,
    selectedCode: '',
    editData: {
        id: null,
        target_code: '',
        target_date: '',
        target_amount: '',
        notes: '',
    },
    openEdit(item) {
        this.editData = {
            id: item.id,
            target_code: item.target_code,
            target_date: item.target_date ? item.target_date.substring(0, 10) : '',
            target_amount: item.target_amount,
            notes: item.notes ?? '',
        };
        this.showEditModal = true;
    },
    openDelete(id, code) {
        this.selectedId = id;
        this.selectedCode = code;
        this.showDeleteModal = true;
    }
}">

    <div class="grid grid-cols-1 lg:grid-cols-[280px_1fr] gap-8 items-start">

        {{-- Sidebar --}}
        <aside class="hidden lg:block space-y-5">
            @include('admin.sidebar', ['active' => 'targets'])
        </aside>

        {{-- Main Content --}}
        <div class="space-y-6">

            {{-- Page Header --}}
            <div class="bg-white border border-slate-100 rounded-3xl p-6 sm:p-8 shadow-sm flex items-center justify-between gap-4">
                <div class="space-y-1">
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight">Master Target</h1>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest">Admin Management → Data Target Bulanan</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-primary-50 flex items-center justify-center text-primary-600 shadow-sm">
                    <i class="fa-solid fa-bullseye text-lg"></i>
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
                <form method="GET" action="{{ route('admin.targets') }}" class="flex flex-wrap items-center gap-3 text-xs font-bold text-slate-700 pb-5 border-b border-slate-100">

                    {{-- Search ID Target / Keyword --}}
                    <div class="flex items-center gap-1.5 min-w-[200px] flex-1 sm:flex-none">
                        <span class="text-slate-500 font-bold whitespace-nowrap">Search :</span>
                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="ID Target..."
                               class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 placeholder:text-slate-400">
                    </div>

                    {{-- Bulan Target Date Range --}}
                    <div class="flex items-center gap-1.5 flex-wrap">
                        <span class="text-slate-500 font-bold whitespace-nowrap">Bulan Target :</span>
                        <input type="date"
                               name="start_date"
                               value="{{ request('start_date', '2026-08-01') }}"
                               class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500">
                        <span class="text-slate-400 font-bold">s/d</span>
                        <input type="date"
                               name="end_date"
                               value="{{ request('end_date', '2026-08-07') }}"
                               class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500">
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center gap-2 ml-auto">
                        <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-900 hover:bg-slate-950 text-white rounded-xl text-xs font-bold transition-all shadow-sm">
                            <i class="fa-solid fa-magnifying-glass text-xs"></i> Search
                        </button>
                        <a href="{{ route('admin.targets') }}" class="p-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition-all" title="Reset Filter">
                            <i class="fa-solid fa-rotate text-xs"></i>
                        </a>
                    </div>

                </form>

                {{-- Toolbar Actions (Add, Edit, Delete, Export) --}}
                <div class="flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <button @click="showAddModal = true"
                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-2xl text-xs font-black uppercase tracking-wider shadow-lg shadow-primary-500/20 transition-all duration-300">
                            <i class="fa-solid fa-plus text-xs"></i> Add Target
                        </button>
                        <a href="{{ route('admin.targets.export', request()->query()) }}"
                           class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100 rounded-2xl text-xs font-bold transition-all shadow-sm">
                            <i class="fa-solid fa-file-excel text-emerald-600"></i> Export Excel
                        </a>
                    </div>
                    <div class="text-xs font-semibold text-slate-500">
                        Total: <span class="font-bold text-slate-900">{{ $targets->total() }}</span> Data Target
                    </div>
                </div>

                {{-- Table --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 text-[10px] font-black uppercase tracking-wider text-slate-400">
                                <th class="pb-4 w-8">#</th>
                                <th class="pb-4">ID Target</th>
                                <th class="pb-4">Bulan Target</th>
                                <th class="pb-4">Nominal Target</th>
                                <th class="pb-4">Keterangan</th>
                                <th class="pb-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            @forelse($targets as $index => $item)
                                <tr class="border-b border-slate-50 hover:bg-slate-50/60 transition-colors group">
                                    <td class="py-4 text-slate-400 text-xs font-bold">
                                        {{ $targets->firstItem() + $index }}
                                    </td>
                                    <td class="py-4">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-slate-100 text-slate-800 font-mono text-xs font-bold">
                                            {{ $item->target_code }}
                                        </span>
                                    </td>
                                    <td class="py-4 font-semibold text-slate-700">
                                        {{ $item->target_date ? $item->target_date->translatedFormat('d F Y') : '—' }}
                                    </td>
                                    <td class="py-4 font-bold text-slate-900">
                                        Rp {{ number_format($item->target_amount, 0, ',', '.') }}
                                    </td>
                                    <td class="py-4 text-xs text-slate-500">
                                        {{ Str::limit($item->notes, 50) ?: '—' }}
                                    </td>
                                    <td class="py-4">
                                        <div class="flex items-center justify-end gap-1.5">
                                            <button @click="openEdit({{ json_encode($item) }})"
                                                    class="p-1.5 text-amber-600 hover:bg-amber-50 rounded-lg transition-all" title="Edit">
                                                <i class="fa-solid fa-pen-to-square text-sm"></i>
                                            </button>
                                            <button @click="openDelete({{ $item->id }}, '{{ addslashes($item->target_code) }}')"
                                                    class="p-1.5 text-rose-600 hover:bg-rose-50 rounded-lg transition-all" title="Hapus">
                                                <i class="fa-solid fa-trash-can text-sm"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-14 text-center text-slate-400 font-bold uppercase tracking-wider">
                                        <i class="fa-solid fa-folder-open text-3xl block text-slate-300 mb-3"></i>
                                        Belum ada data target.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                        {{-- Summary Footer Row (Presisi sesuai desain screenshot) --}}
                        @if($targets->count() > 0)
                            <tfoot>
                                <tr class="bg-amber-500/10 border-t-2 border-amber-500/30 text-slate-900 font-black text-sm">
                                    <td class="py-3.5 px-3 text-amber-900 font-mono text-xs">Total</td>
                                    <td class="py-3.5 text-primary-600 uppercase tracking-wider" colspan="2">TOTAL TARGET BULANAN</td>
                                    <td class="py-3.5 font-black text-slate-900 text-base" colspan="3">
                                        Rp {{ number_format($totalNominalTarget, 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tfoot>
                        @endif

                    </table>
                </div>

                {{-- Pagination --}}
                <div class="pt-2">
                    {{ $targets->links() }}
                </div>

            </div>{{-- end card --}}

        </div>{{-- end main --}}

    </div>{{-- end grid --}}

    {{-- ============ MODAL: ADD TARGET ============ --}}
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
                    <h3 class="text-lg font-black text-slate-900">Tambah Master Target</h3>
                    <p class="text-xs text-slate-400 font-semibold mt-0.5">Isi data target bulanan baru</p>
                </div>
                <button @click="showAddModal = false" class="p-2 rounded-xl hover:bg-slate-100 text-slate-400 transition-all">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <form method="POST" action="{{ route('admin.targets.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-black text-slate-600 uppercase tracking-wider mb-1.5">ID / Kode Target <span class="text-rose-500">*</span></label>
                    <input type="text" name="target_code" required placeholder="Contoh: TGT-2026-08"
                           class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 uppercase placeholder:text-slate-400">
                </div>
                <div>
                    <label class="block text-xs font-black text-slate-600 uppercase tracking-wider mb-1.5">Bulan Target <span class="text-rose-500">*</span></label>
                    <input type="date" name="target_date" required value="{{ date('Y-m-01') }}"
                           class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500">
                </div>
                <div>
                    <label class="block text-xs font-black text-slate-600 uppercase tracking-wider mb-1.5">Nominal Target (Rp) <span class="text-rose-500">*</span></label>
                    <input type="number" name="target_amount" required step="1000" min="0" placeholder="Contoh: 50000000"
                           class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 placeholder:text-slate-400">
                </div>
                <div>
                    <label class="block text-xs font-black text-slate-600 uppercase tracking-wider mb-1.5">Keterangan</label>
                    <textarea name="notes" rows="3" placeholder="Keterangan singkat..."
                              class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 placeholder:text-slate-400 resize-none"></textarea>
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

    {{-- ============ MODAL: EDIT TARGET ============ --}}
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
                    <h3 class="text-lg font-black text-slate-900">Edit Master Target</h3>
                    <p class="text-xs text-slate-400 font-semibold mt-0.5">Perbarui data target bulanan</p>
                </div>
                <button @click="showEditModal = false" class="p-2 rounded-xl hover:bg-slate-100 text-slate-400 transition-all">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <form method="POST" :action="`/admin/targets/${editData.id}/update`" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-black text-slate-600 uppercase tracking-wider mb-1.5">ID / Kode Target <span class="text-rose-500">*</span></label>
                    <input type="text" name="target_code" x-model="editData.target_code" required
                           class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 uppercase">
                </div>
                <div>
                    <label class="block text-xs font-black text-slate-600 uppercase tracking-wider mb-1.5">Bulan Target <span class="text-rose-500">*</span></label>
                    <input type="date" name="target_date" x-model="editData.target_date" required
                           class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500">
                </div>
                <div>
                    <label class="block text-xs font-black text-slate-600 uppercase tracking-wider mb-1.5">Nominal Target (Rp) <span class="text-rose-500">*</span></label>
                    <input type="number" name="target_amount" x-model="editData.target_amount" required step="1000" min="0"
                           class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500">
                </div>
                <div>
                    <label class="block text-xs font-black text-slate-600 uppercase tracking-wider mb-1.5">Keterangan</label>
                    <textarea name="notes" rows="3" x-model="editData.notes"
                              class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 resize-none"></textarea>
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
                <h3 class="text-lg font-black text-slate-900">Hapus Master Target?</h3>
                <p class="text-sm text-slate-500 font-semibold mt-1">
                    Target "<span class="text-slate-800 font-black" x-text="selectedCode"></span>" akan dihapus permanen.
                </p>
            </div>
            <form method="POST" :action="`/admin/targets/${selectedId}/delete`">
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
