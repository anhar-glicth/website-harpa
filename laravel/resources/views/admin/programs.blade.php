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
        program_group_id: '',
        description: '',
        status: 'Aktif',
    },
    openEdit(item) {
        this.editData = {
            id: item.id,
            name: item.name,
            program_group_id: item.program_group_id ?? '',
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
            @include('admin.sidebar', ['active' => 'programs'])
        </aside>

        {{-- Main Content --}}
        <div class="space-y-6">

            {{-- Page Header --}}
            <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 shadow-sm flex items-center justify-between gap-4">
                <div class="space-y-1">
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight">Master Program</h1>
                    <p class="text-xs font-semibold text-slate-600 uppercase tracking-widest">Admin Management &rarr; Data Program</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-primary-50 flex items-center justify-center text-primary-700 shadow-sm">
                    <i class="fa-solid fa-clipboard-list text-lg" aria-hidden="true"></i>
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
                <form method="GET" action="{{ route('admin.programs') }}" class="flex flex-wrap items-center gap-3 text-xs font-bold text-slate-700 pb-5 border-b border-slate-200">

                    {{-- Keyword --}}
                    <div class="flex items-center gap-1.5 min-w-[200px] flex-1 sm:flex-none">
                        <label for="prg_filter_keyword" class="text-slate-600 font-bold whitespace-nowrap">Keyword :</label>
                        <input id="prg_filter_keyword" type="text"
                               name="keyword"
                               value="{{ request('keyword') }}"
                               placeholder="Keyword..."
                               class="w-full min-h-[44px] px-3.5 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-600 placeholder:text-slate-500">
                    </div>

                    {{-- Rumpun Program Filter --}}
                    <div class="flex items-center gap-1.5 min-w-[180px]">
                        <label for="prg_filter_group" class="text-slate-600 font-bold whitespace-nowrap">Rumpun :</label>
                        <select id="prg_filter_group" name="program_group_id" class="w-full min-h-[44px] px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-600">
                            <option value="">Semua Rumpun</option>
                            @foreach($programGroups as $pg)
                                <option value="{{ $pg->id }}" {{ request('program_group_id') == $pg->id ? 'selected' : '' }}>
                                    {{ $pg->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Status --}}
                    <div class="flex items-center gap-1.5 min-w-[130px]">
                        <label for="prg_filter_status" class="text-slate-600 font-bold whitespace-nowrap">Status :</label>
                        <select id="prg_filter_status" name="status" class="w-full min-h-[44px] px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-600">
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
                        <a href="{{ route('admin.programs') }}" aria-label="Reset filter" class="w-11 h-11 inline-flex items-center justify-center bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 rounded-xl transition-colors" title="Reset Filter">
                            <i class="fa-solid fa-rotate text-xs" aria-hidden="true"></i>
                        </a>
                    </div>

                </form>

                {{-- Toolbar --}}
                <div class="flex items-center justify-between gap-4">
                    <button type="button" @click="showAddModal = true"
                            class="inline-flex items-center gap-2 min-h-[44px] px-5 py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-2xl text-xs font-black uppercase tracking-wider shadow-lg shadow-primary-500/20 transition-colors">
                        <i class="fa-solid fa-plus text-xs" aria-hidden="true"></i> New Program
                    </button>
                    <div class="text-xs font-semibold text-slate-600">
                        Total: <span class="font-bold text-slate-900">{{ $programs->total() }}</span> Program
                    </div>
                </div>

                {{-- Table --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-200 text-xs font-black uppercase tracking-wider text-slate-600">
                                <th class="pb-4 w-8">#</th>
                                <th class="pb-4">Program</th>
                                <th class="pb-4">Rumpun Program</th>
                                <th class="pb-4">Deskripsi</th>
                                <th class="pb-4 text-center">Aktif</th>
                                <th class="pb-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            @forelse($programs as $index => $item)
                                <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors group">
                                    <td class="py-4 text-slate-600 text-xs font-bold">
                                        {{ $programs->firstItem() + $index }}
                                    </td>
                                    <td class="py-4">
                                        <span class="font-bold text-slate-900">{{ $item->name }}</span>
                                    </td>
                                    <td class="py-4">
                                        @if($item->programGroup)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-primary-50 text-primary-700 text-xs font-black">
                                                <i class="fa-solid fa-sitemap text-xs" aria-hidden="true"></i>
                                                {{ $item->programGroup->name }}
                                            </span>
                                        @else
                                            <span class="text-slate-600 text-xs">-</span>
                                        @endif
                                    </td>
                                    <td class="py-4">
                                        <span class="text-slate-600 text-xs">{{ Str::limit($item->description, 55) ?: '-' }}</span>
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
                                                    class="min-w-[36px] min-h-[36px] inline-flex items-center justify-center p-1.5 text-amber-800 hover:bg-amber-50 rounded-lg transition-colors" title="Edit">
                                                <i class="fa-solid fa-pen-to-square text-sm" aria-hidden="true"></i>
                                            </button>
                                            <button type="button" @click="openDelete({{ $item->id }}, '{{ addslashes($item->name) }}')"
                                                    aria-label="Hapus {{ $item->name }}"
                                                    class="min-w-[36px] min-h-[36px] inline-flex items-center justify-center p-1.5 text-rose-700 hover:bg-rose-50 rounded-lg transition-colors" title="Hapus">
                                                <i class="fa-solid fa-trash-can text-sm" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-14 text-center text-slate-600 font-bold uppercase tracking-wider">
                                        <i class="fa-solid fa-folder-open text-3xl block text-slate-500 mb-3" aria-hidden="true"></i>
                                        Belum ada data program.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="pt-2">
                    {{ $programs->links() }}
                </div>

            </div>{{-- end card --}}

        </div>{{-- end main --}}

    </div>{{-- end grid --}}

    {{-- Modal: Tambah Program --}}
    <div x-show="showAddModal"
         @keydown.escape.window="showAddModal = false"
         role="dialog" aria-modal="true" aria-labelledby="modal-add-program-title"
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
                    <h2 id="modal-add-program-title" class="text-lg font-black text-slate-900">Tambah Program</h2>
                    <p class="text-xs text-slate-600 font-semibold mt-0.5">Isi data program baru</p>
                </div>
                <button type="button" @click="showAddModal = false" aria-label="Tutup modal tambah program" class="min-w-[44px] min-h-[44px] inline-flex items-center justify-center rounded-xl hover:bg-slate-100 text-slate-600 hover:text-slate-900 transition-colors">
                    <i class="fa-solid fa-xmark" aria-hidden="true"></i>
                </button>
            </div>
            <form method="POST" action="{{ route('admin.programs.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="add_program_name" class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-1.5">Nama Program <span class="text-rose-700">*</span></label>
                    <input id="add_program_name" type="text" name="name" required placeholder="Contoh: Masjid At Taqwa"
                           class="w-full min-h-[44px] px-4 py-3 bg-slate-50 border border-slate-300 rounded-2xl text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-primary-600/20 focus:border-primary-600 placeholder:text-slate-500">
                </div>
                <div>
                    <label for="add_program_group" class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-1.5">Rumpun Program</label>
                    <select id="add_program_group" name="program_group_id"
                            class="w-full min-h-[44px] px-4 py-3 bg-slate-50 border border-slate-300 rounded-2xl text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-primary-600/20 focus:border-primary-600">
                        <option value="">-- Pilih Rumpun Program --</option>
                        @foreach($programGroups as $pg)
                            <option value="{{ $pg->id }}">{{ $pg->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="add_program_desc" class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-1.5">Deskripsi</label>
                    <textarea id="add_program_desc" name="description" rows="3" placeholder="Deskripsi singkat program..."
                              class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-2xl text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-primary-600/20 focus:border-primary-600 placeholder:text-slate-500 resize-none"></textarea>
                </div>
                <div>
                    <label for="add_program_status" class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-1.5">Status <span class="text-rose-700">*</span></label>
                    <select id="add_program_status" name="status" required
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

    {{-- Modal: Edit Program --}}
    <div x-show="showEditModal"
         @keydown.escape.window="showEditModal = false"
         role="dialog" aria-modal="true" aria-labelledby="modal-edit-program-title"
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
                    <h2 id="modal-edit-program-title" class="text-lg font-black text-slate-900">Edit Program</h2>
                    <p class="text-xs text-slate-600 font-semibold mt-0.5">Perbarui data program</p>
                </div>
                <button type="button" @click="showEditModal = false" aria-label="Tutup modal edit program" class="min-w-[44px] min-h-[44px] inline-flex items-center justify-center rounded-xl hover:bg-slate-100 text-slate-600 hover:text-slate-900 transition-colors">
                    <i class="fa-solid fa-xmark" aria-hidden="true"></i>
                </button>
            </div>
            <form method="POST" :action="`{{ url('admin/programs') }}/${editData.id}/update`" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label for="edit_program_name" class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-1.5">Nama Program <span class="text-rose-700">*</span></label>
                    <input id="edit_program_name" type="text" name="name" x-model="editData.name" required
                           class="w-full min-h-[44px] px-4 py-3 bg-slate-50 border border-slate-300 rounded-2xl text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-primary-600/20 focus:border-primary-600">
                </div>
                <div>
                    <label for="edit_program_group" class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-1.5">Rumpun Program</label>
                    <select id="edit_program_group" name="program_group_id" x-model="editData.program_group_id"
                            class="w-full min-h-[44px] px-4 py-3 bg-slate-50 border border-slate-300 rounded-2xl text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-primary-600/20 focus:border-primary-600">
                        <option value="">-- Pilih Rumpun Program --</option>
                        @foreach($programGroups as $pg)
                            <option value="{{ $pg->id }}">{{ $pg->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="edit_program_desc" class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-1.5">Deskripsi</label>
                    <textarea id="edit_program_desc" name="description" rows="3" x-model="editData.description"
                              class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-2xl text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-primary-600/20 focus:border-primary-600 resize-none"></textarea>
                </div>
                <div>
                    <label for="edit_program_status" class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-1.5">Status <span class="text-rose-700">*</span></label>
                    <select id="edit_program_status" name="status" x-model="editData.status" required
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

    {{-- Modal: Hapus Program --}}
    <div x-show="showDeleteModal"
         @keydown.escape.window="showDeleteModal = false"
         role="dialog" aria-modal="true" aria-labelledby="modal-delete-program-title"
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
                <h2 id="modal-delete-program-title" class="text-lg font-black text-slate-900">Hapus Program?</h2>
                <p class="text-sm text-slate-600 font-semibold mt-1">
                    Data "<span class="text-slate-900 font-black" x-text="selectedName"></span>" akan dihapus permanen.
                </p>
            </div>
            <form method="POST" :action="`{{ url('admin/programs') }}/${selectedId}/delete`">
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
