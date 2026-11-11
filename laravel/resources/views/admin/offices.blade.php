@extends('layouts.app')

@section('content')
<div class="container max-w-7xl mx-auto px-4 py-8" x-data="{ showAddModal: false, showEditModal: false, showDeleteModal: false, activeOffice: {} }">

    <div class="grid grid-cols-1 lg:grid-cols-[280px_1fr] gap-8 items-start min-w-0">

        {{-- Sidebar --}}
        <aside class="hidden lg:block space-y-5">
            @include('admin.sidebar', ['active' => 'offices'])
        </aside>

        {{-- Main Content Column --}}
        <div class="space-y-5 min-w-0 overflow-hidden">

            {{-- Top Header Banner (Emerald Theme Website) --}}
            <div class="bg-emerald-700 rounded-2xl px-6 py-4 text-white font-black text-sm uppercase tracking-wider shadow-md flex flex-wrap items-center justify-between gap-2">
                <span class="flex items-center gap-2.5 text-base italic">
                    <i class="fa-solid fa-building text-white"></i> Master Kantor
                </span>
                <span class="text-xs text-emerald-100 font-medium tracking-normal">Admin Management / Master Kantor</span>
            </div>

            {{-- Flash Alerts --}}
            @if(session('success'))
                <div role="status" class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center justify-between text-xs font-bold shadow-xs">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-circle-check text-emerald-700 text-base"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button type="button" @click="$el.parentElement.remove()" aria-label="Tutup notifikasi" class="w-11 h-11 -my-2 inline-flex items-center justify-center rounded-xl text-emerald-700 hover:bg-emerald-100">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif

            {{-- Search & Filter Controls (Emerald Theme) --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm space-y-3 text-xs font-semibold text-slate-700">
                <form action="{{ route('admin.offices') }}" method="GET" class="flex flex-wrap items-center gap-3">
                    
                    <div class="flex items-center gap-2">
                        <label for="office_search">Search :</label>
                        <input id="office_search" type="text" name="search" value="{{ request('search') }}" placeholder="ID/Nama Kantor/" class="border border-slate-300 rounded-xl px-3 py-2 min-h-[44px] text-xs focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700 w-52">
                    </div>

                    <div class="flex items-center gap-2">
                        <label for="office_city">Kota/Kab :</label>
                        <input id="office_city" type="text" name="city" value="{{ request('city') }}" placeholder="Search By Kota" class="border border-slate-300 rounded-xl px-3 py-2 min-h-[44px] text-xs focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700 w-40">
                    </div>

                    <div class="flex items-center gap-2">
                        <label for="office_parent_filter">Kantor Parent :</label>
                        <select id="office_parent_filter" name="parent_id" class="border border-slate-300 rounded-xl px-3 py-2 min-h-[44px] text-xs focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700 min-w-[150px]">
                            <option value="">Search By Parent</option>
                            @foreach($allOffices as $ofc)
                                <option value="{{ $ofc->id }}" {{ request('parent_id') == $ofc->id ? 'selected' : '' }}>{{ $ofc->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-center gap-2">
                        <label for="office_status_filter">Status :</label>
                        <select id="office_status_filter" name="status" class="border border-slate-300 rounded-xl px-3 py-2 min-h-[44px] text-xs focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700 w-28">
                            <option value="">Semua</option>
                            <option value="Aktif" {{ request('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="Non-Aktif" {{ request('status') == 'Non-Aktif' ? 'selected' : '' }}>Non-Aktif</option>
                        </select>
                    </div>

                    <button type="submit" class="bg-emerald-700 hover:bg-emerald-800 text-white font-bold px-4 py-2 min-h-[44px] rounded-xl text-xs inline-flex items-center gap-1.5 transition-colors shadow-sm">
                        <i class="fa-solid fa-magnifying-glass"></i> Search
                    </button>

                </form>
            </div>

            {{-- Action Toolbar & Data Table Card --}}
            <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
                
                {{-- Toolbar (Add, Edit, Delete, Export) --}}
                <div class="bg-slate-50 px-4 py-2.5 border-b border-slate-200 flex flex-wrap items-center gap-4 text-xs font-bold text-slate-700">
                    <button type="button" @click="showAddModal = true" class="min-h-[44px] px-2 text-emerald-700 hover:text-emerald-800 inline-flex items-center gap-1.5">
                        <i class="fa-solid fa-plus-circle text-emerald-700"></i> Add
                    </button>
                    <button type="button" @click="if(activeOffice.id) showEditModal = true; else alert('Pilih kantor dari tabel dulu!')" class="min-h-[44px] px-2 text-emerald-700 hover:text-emerald-800 inline-flex items-center gap-1.5">
                        <i class="fa-solid fa-pen text-emerald-700"></i> Edit
                    </button>
                    <button type="button" @click="if(activeOffice.id) showDeleteModal = true; else alert('Pilih kantor dari tabel dulu!')" class="min-h-[44px] px-2 text-emerald-700 hover:text-emerald-800 inline-flex items-center gap-1.5">
                        <i class="fa-solid fa-minus-circle text-emerald-700"></i> Delete
                    </button>
                    <a href="{{ route('admin.offices.export') }}" class="min-h-[44px] px-2 text-emerald-700 hover:text-emerald-800 inline-flex items-center gap-1.5">
                        <i class="fa-solid fa-file-excel text-emerald-700"></i> Export
                    </a>
                </div>

                {{-- Table Header & Content (Emerald Theme) --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs font-medium text-slate-700 divide-y divide-slate-100 whitespace-nowrap">
                        <thead class="bg-emerald-700 text-white text-xs font-black uppercase">
                            <tr>
                                <th class="py-3 px-4 text-center w-12">NO</th>
                                <th class="py-3 px-4 text-center">ID KANTOR</th>
                                <th class="py-3 px-4">NAMA KANTOR</th>
                                <th class="py-3 px-4">KOTA</th>
                                <th class="py-3 px-4">PROVINSI</th>
                                <th class="py-3 px-4">KANTOR PARENT</th>
                                <th class="py-3 px-4 text-center">AKTIF</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($offices as $idx => $ofc)
                                <tr @click="activeOffice = {{ json_encode($ofc) }}" 
                                    :class="activeOffice.id === {{ $ofc->id }} ? 'bg-emerald-50 font-bold border-l-4 border-emerald-600' : 'hover:bg-slate-50'" 
                                    class="cursor-pointer transition-all">
                                    <td class="py-2.5 px-4 text-center text-slate-600 font-mono">{{ $offices->firstItem() + $idx }}</td>
                                    <td class="py-2.5 px-4 text-center font-mono font-bold text-slate-800">{{ $ofc->office_code ?? $ofc->id }}</td>
                                    <td class="py-2.5 px-4 font-bold text-slate-900">{{ $ofc->name }}</td>
                                    <td class="py-2.5 px-4 text-slate-700">{{ $ofc->city ?? '-' }}</td>
                                    <td class="py-2.5 px-4 text-slate-700">{{ $ofc->province ?? '-' }}</td>
                                    <td class="py-2.5 px-4 text-slate-600">{{ optional($ofc->parent)->name }}</td>
                                    <td class="py-2.5 px-4 text-center font-mono font-bold {{ $ofc->status == 'Aktif' ? 'text-emerald-700' : 'text-rose-700' }}">
                                        {{ $ofc->status == 'Aktif' ? 'y' : 'n' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-6 text-center text-slate-600 font-bold">Belum ada data Master Kantor.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination Footer --}}
                <div class="px-4 py-3 bg-slate-50 border-t border-slate-200 flex flex-wrap items-center justify-between gap-4 text-xs text-slate-600 font-medium">
                    <div class="flex items-center gap-2">
                        <span>Displaying {{ $offices->firstItem() ?? 0 }} to {{ $offices->lastItem() ?? 0 }} of {{ $offices->total() }} items</span>
                    </div>
                    <div>
                        {{ $offices->links() }}
                    </div>
                </div>

            </div>

        </div>
    </div>

    {{-- Modal Add Kantor --}}
    <div x-show="showAddModal" x-cloak @keydown.escape.window="showAddModal = false" role="dialog" aria-modal="true" aria-labelledby="add-office-title" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60" @click="showAddModal = false"></div>
        <div class="relative z-10 bg-white rounded-2xl max-w-md w-full p-6 space-y-4 shadow-xl border border-slate-100">
            <div class="flex items-center justify-between border-b pb-3">
                <h2 id="add-office-title" class="font-black text-slate-900 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-plus-circle text-emerald-700"></i> Tambah Master Kantor
                </h2>
                <button type="button" @click="showAddModal = false" aria-label="Tutup modal tambah kantor" class="w-11 h-11 inline-flex items-center justify-center rounded-xl text-slate-600 hover:bg-slate-100"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form action="{{ route('admin.offices.store') }}" method="POST" class="space-y-3 text-xs">
                @csrf
                <div>
                    <label for="add_office_code" class="block font-bold text-slate-700 mb-1">ID / Kode Kantor</label>
                    <input id="add_office_code" type="text" name="office_code" placeholder="Contoh: 1 / OFF-01" class="w-full border border-slate-300 rounded-xl px-3 py-2 min-h-[44px] text-xs focus:border-emerald-700">
                </div>
                <div>
                    <label for="add_office_name" class="block font-bold text-slate-700 mb-1">Nama Kantor *</label>
                    <input id="add_office_name" type="text" name="name" required placeholder="Nama Kantor Cabang / Pusat" class="w-full border border-slate-300 rounded-xl px-3 py-2 min-h-[44px] text-xs focus:border-emerald-700">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label for="add_office_city" class="block font-bold text-slate-700 mb-1">Kota / Kabupaten</label>
                        <input id="add_office_city" type="text" name="city" placeholder="Mataram" class="w-full border border-slate-300 rounded-xl px-3 py-2 min-h-[44px] text-xs focus:border-emerald-700">
                    </div>
                    <div>
                        <label for="add_office_province" class="block font-bold text-slate-700 mb-1">Provinsi</label>
                        <input id="add_office_province" type="text" name="province" placeholder="Nusa Tenggara Barat" class="w-full border border-slate-300 rounded-xl px-3 py-2 min-h-[44px] text-xs focus:border-emerald-700">
                    </div>
                </div>
                <div>
                    <label for="add_office_parent" class="block font-bold text-slate-700 mb-1">Kantor Parent (Induk)</label>
                    <select id="add_office_parent" name="parent_id" class="w-full border border-slate-300 rounded-xl px-3 py-2 min-h-[44px] text-xs focus:border-emerald-700">
                        <option value="">-- Pilih Kantor Parent (Jika Ada) --</option>
                        @foreach($allOffices as $ofc)
                            <option value="{{ $ofc->id }}">{{ $ofc->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="add_office_status" class="block font-bold text-slate-700 mb-1">Status Keaktifan</label>
                    <select id="add_office_status" name="status" class="w-full border border-slate-300 rounded-xl px-3 py-2 min-h-[44px] text-xs focus:border-emerald-700">
                        <option value="Aktif">Aktif (y)</option>
                        <option value="Non-Aktif">Non-Aktif (n)</option>
                    </select>
                </div>
                <div class="flex justify-end gap-2 pt-3 border-t">
                    <button type="button" @click="showAddModal = false" class="px-4 py-2 min-h-[44px] bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold">Batal</button>
                    <button type="submit" class="px-5 py-2 min-h-[44px] bg-emerald-700 text-white rounded-xl font-bold hover:bg-emerald-800">Simpan Kantor</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Edit Kantor --}}
    <div x-show="showEditModal" x-cloak @keydown.escape.window="showEditModal = false" role="dialog" aria-modal="true" aria-labelledby="edit-office-title" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60" @click="showEditModal = false"></div>
        <div class="relative z-10 bg-white rounded-2xl max-w-md w-full p-6 space-y-4 shadow-xl border border-slate-100">
            <div class="flex items-center justify-between border-b pb-3">
                <h2 id="edit-office-title" class="font-black text-slate-900 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-pen text-emerald-700"></i> Edit Master Kantor
                </h2>
                <button type="button" @click="showEditModal = false" aria-label="Tutup modal edit kantor" class="w-11 h-11 inline-flex items-center justify-center rounded-xl text-slate-600 hover:bg-slate-100"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form :action="'{{ url('admin/offices') }}/' + activeOffice.id + '/update'" method="POST" class="space-y-3 text-xs">
                @csrf
                @method('PUT')
                <div>
                    <label for="edit_office_code" class="block font-bold text-slate-700 mb-1">ID / Kode Kantor</label>
                    <input id="edit_office_code" type="text" name="office_code" :value="activeOffice.office_code" class="w-full border border-slate-300 rounded-xl px-3 py-2 min-h-[44px] text-xs focus:border-emerald-700">
                </div>
                <div>
                    <label for="edit_office_name" class="block font-bold text-slate-700 mb-1">Nama Kantor *</label>
                    <input id="edit_office_name" type="text" name="name" required :value="activeOffice.name" class="w-full border border-slate-300 rounded-xl px-3 py-2 min-h-[44px] text-xs focus:border-emerald-700">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label for="edit_office_city" class="block font-bold text-slate-700 mb-1">Kota / Kabupaten</label>
                        <input id="edit_office_city" type="text" name="city" :value="activeOffice.city" class="w-full border border-slate-300 rounded-xl px-3 py-2 min-h-[44px] text-xs focus:border-emerald-700">
                    </div>
                    <div>
                        <label for="edit_office_province" class="block font-bold text-slate-700 mb-1">Provinsi</label>
                        <input id="edit_office_province" type="text" name="province" :value="activeOffice.province" class="w-full border border-slate-300 rounded-xl px-3 py-2 min-h-[44px] text-xs focus:border-emerald-700">
                    </div>
                </div>
                <div>
                    <label for="edit_office_parent" class="block font-bold text-slate-700 mb-1">Kantor Parent (Induk)</label>
                    <select id="edit_office_parent" name="parent_id" class="w-full border border-slate-300 rounded-xl px-3 py-2 min-h-[44px] text-xs focus:border-emerald-700">
                        <option value="">-- Pilih Kantor Parent --</option>
                        @foreach($allOffices as $ofc)
                            <option value="{{ $ofc->id }}" :selected="activeOffice.parent_id == {{ $ofc->id }}">{{ $ofc->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="edit_office_status" class="block font-bold text-slate-700 mb-1">Status Keaktifan</label>
                    <select id="edit_office_status" name="status" class="w-full border border-slate-300 rounded-xl px-3 py-2 min-h-[44px] text-xs focus:border-emerald-700">
                        <option value="Aktif" :selected="activeOffice.status == 'Aktif'">Aktif (y)</option>
                        <option value="Non-Aktif" :selected="activeOffice.status == 'Non-Aktif'">Non-Aktif (n)</option>
                    </select>
                </div>
                <div class="flex justify-end gap-2 pt-3 border-t">
                    <button type="button" @click="showEditModal = false" class="px-4 py-2 min-h-[44px] bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold">Batal</button>
                    <button type="submit" class="px-5 py-2 min-h-[44px] bg-emerald-700 text-white rounded-xl font-bold hover:bg-emerald-800">Update Kantor</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Delete Kantor --}}
    <div x-show="showDeleteModal" x-cloak @keydown.escape.window="showDeleteModal = false" role="dialog" aria-modal="true" aria-labelledby="delete-office-title" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60" @click="showDeleteModal = false"></div>
        <div class="relative z-10 bg-white rounded-2xl max-w-sm w-full p-6 space-y-4 text-center shadow-xl border border-slate-100">
            <div class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-700 flex items-center justify-center mx-auto text-xl">
                <i class="fa-solid fa-trash"></i>
            </div>
            <h2 id="delete-office-title" class="font-black text-slate-900 text-sm">Hapus Master Kantor?</h2>
            <p class="text-xs text-slate-600">Apakah Anda yakin ingin menghapus kantor <strong class="text-slate-900" x-text="activeOffice.name"></strong>?</p>
            <form :action="'{{ url('admin/offices') }}/' + activeOffice.id + '/delete'" method="POST" class="flex justify-center gap-2 pt-2">
                @csrf
                @method('DELETE')
                <button type="button" @click="showDeleteModal = false" class="px-4 py-2 min-h-[44px] bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold text-xs">Batal</button>
                <button type="submit" class="px-5 py-2 min-h-[44px] bg-rose-700 text-white rounded-xl font-bold text-xs hover:bg-rose-800">Hapus</button>
            </form>
        </div>
    </div>

</div>
@endsection
