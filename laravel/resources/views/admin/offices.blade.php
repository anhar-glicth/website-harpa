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
            <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 rounded-2xl px-6 py-4 text-white font-black text-sm uppercase tracking-wider shadow-md flex items-center justify-between">
                <span class="flex items-center gap-2.5 text-base italic">
                    <i class="fa-solid fa-building text-white"></i> Master Kantor
                </span>
                <span class="text-xs text-white/80 font-medium tracking-normal">Admin Management → Master Kantor</span>
            </div>

            {{-- Flash Alerts --}}
            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center justify-between text-xs font-bold shadow-xs">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button @click="$el.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif

            {{-- Search & Filter Controls (Emerald Theme) --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm space-y-3 text-xs font-semibold text-slate-700">
                <form action="{{ route('admin.offices') }}" method="GET" class="flex flex-wrap items-center gap-3">
                    
                    <div class="flex items-center gap-2">
                        <span>Search :</span>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="ID/Nama Kantor/" class="border border-slate-300 rounded-xl px-3 py-1.5 text-xs focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 w-52">
                    </div>

                    <div class="flex items-center gap-2">
                        <span>Kota/Kab :</span>
                        <input type="text" name="city" value="{{ request('city') }}" placeholder="Search By Kota" class="border border-slate-300 rounded-xl px-3 py-1.5 text-xs focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 w-40">
                    </div>

                    <div class="flex items-center gap-2">
                        <span>Kantor Parent :</span>
                        <select name="parent_id" class="border border-slate-300 rounded-xl px-3 py-1.5 text-xs focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 min-w-[150px]">
                            <option value="">Search By Parent</option>
                            @foreach($allOffices as $ofc)
                                <option value="{{ $ofc->id }}" {{ request('parent_id') == $ofc->id ? 'selected' : '' }}>{{ $ofc->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-center gap-2">
                        <span>Status :</span>
                        <select name="status" class="border border-slate-300 rounded-xl px-3 py-1.5 text-xs focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 w-28">
                            <option value="">Semua</option>
                            <option value="Aktif" {{ request('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="Non-Aktif" {{ request('status') == 'Non-Aktif' ? 'selected' : '' }}>Non-Aktif</option>
                        </select>
                    </div>

                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-4 py-1.5 rounded-xl text-xs flex items-center gap-1.5 transition-all shadow-sm">
                        🔍 Search
                    </button>

                </form>
            </div>

            {{-- Action Toolbar & Data Table Card --}}
            <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
                
                {{-- Toolbar (Add, Edit, Delete, Export) --}}
                <div class="bg-slate-50 px-4 py-2.5 border-b border-slate-200 flex flex-wrap items-center gap-4 text-xs font-bold text-slate-700">
                    <button type="button" @click="showAddModal = true" class="text-emerald-600 hover:text-emerald-700 flex items-center gap-1.5">
                        <i class="fa-solid fa-plus-circle text-emerald-500"></i> Add
                    </button>
                    <button type="button" @click="if(activeOffice.id) showEditModal = true; else alert('Pilih kantor dari tabel dulu!')" class="text-emerald-600 hover:text-emerald-700 flex items-center gap-1.5">
                        <i class="fa-solid fa-pen text-emerald-500"></i> Edit
                    </button>
                    <button type="button" @click="if(activeOffice.id) showDeleteModal = true; else alert('Pilih kantor dari tabel dulu!')" class="text-emerald-600 hover:text-emerald-700 flex items-center gap-1.5">
                        <i class="fa-solid fa-minus-circle text-emerald-500"></i> Delete
                    </button>
                    <a href="{{ route('admin.offices.export') }}" class="text-emerald-600 hover:text-emerald-700 flex items-center gap-1.5">
                        <i class="fa-solid fa-file-excel text-emerald-500"></i> Export
                    </a>
                </div>

                {{-- Table Header & Content (Emerald Theme) --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs font-medium text-slate-700 divide-y divide-slate-100 whitespace-nowrap">
                        <thead class="bg-gradient-to-r from-emerald-600 to-emerald-700 text-white text-[11px] font-black uppercase">
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
                                    <td class="py-2.5 px-4 text-center text-slate-400 font-mono">{{ $offices->firstItem() + $idx }}</td>
                                    <td class="py-2.5 px-4 text-center font-mono font-bold text-slate-800">{{ $ofc->office_code ?? $ofc->id }}</td>
                                    <td class="py-2.5 px-4 font-bold text-slate-900">{{ $ofc->name }}</td>
                                    <td class="py-2.5 px-4 text-slate-700">{{ $ofc->city ?? '-' }}</td>
                                    <td class="py-2.5 px-4 text-slate-700">{{ $ofc->province ?? '-' }}</td>
                                    <td class="py-2.5 px-4 text-slate-600">{{ optional($ofc->parent)->name }}</td>
                                    <td class="py-2.5 px-4 text-center font-mono font-bold {{ $ofc->status == 'Aktif' ? 'text-emerald-600' : 'text-rose-600' }}">
                                        {{ $ofc->status == 'Aktif' ? 'y' : 'n' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-6 text-center text-slate-400 font-bold">Belum ada data Master Kantor.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination Footer --}}
                <div class="px-4 py-3 bg-slate-50 border-t border-slate-200 flex flex-wrap items-center justify-between gap-4 text-xs text-slate-500 font-medium">
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

    {{-- MODAL ADD KANTOR --}}
    <div x-show="showAddModal" x-cloak class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 space-y-4 shadow-xl border border-slate-100">
            <div class="flex items-center justify-between border-b pb-3">
                <h3 class="font-black text-slate-900 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-plus-circle text-emerald-600"></i> Tambah Master Kantor
                </h3>
                <button type="button" @click="showAddModal = false" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form action="{{ route('admin.offices.store') }}" method="POST" class="space-y-3 text-xs">
                @csrf
                <div>
                    <label class="block font-bold text-slate-700 mb-1">ID / Kode Kantor</label>
                    <input type="text" name="office_code" placeholder="Contoh: 1 / OFF-01" class="w-full border border-slate-300 rounded-xl px-3 py-2 text-xs focus:border-emerald-600">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nama Kantor *</label>
                    <input type="text" name="name" required placeholder="Nama Kantor Cabang / Pusat" class="w-full border border-slate-300 rounded-xl px-3 py-2 text-xs focus:border-emerald-600">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Kota / Kabupaten</label>
                        <input type="text" name="city" placeholder="Mataram" class="w-full border border-slate-300 rounded-xl px-3 py-2 text-xs focus:border-emerald-600">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Provinsi</label>
                        <input type="text" name="province" placeholder="Nusa Tenggara Barat" class="w-full border border-slate-300 rounded-xl px-3 py-2 text-xs focus:border-emerald-600">
                    </div>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Kantor Parent (Induk)</label>
                    <select name="parent_id" class="w-full border border-slate-300 rounded-xl px-3 py-2 text-xs focus:border-emerald-600">
                        <option value="">-- Pilih Kantor Parent (Jika Ada) --</option>
                        @foreach($allOffices as $ofc)
                            <option value="{{ $ofc->id }}">{{ $ofc->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Status Keaktifan</label>
                    <select name="status" class="w-full border border-slate-300 rounded-xl px-3 py-2 text-xs focus:border-emerald-600">
                        <option value="Aktif">Aktif (y)</option>
                        <option value="Non-Aktif">Non-Aktif (n)</option>
                    </select>
                </div>
                <div class="flex justify-end gap-2 pt-3 border-t">
                    <button type="button" @click="showAddModal = false" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-xl font-bold">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-emerald-600 text-white rounded-xl font-bold hover:bg-emerald-700">Simpan Kantor</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL EDIT KANTOR --}}
    <div x-show="showEditModal" x-cloak class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 space-y-4 shadow-xl border border-slate-100">
            <div class="flex items-center justify-between border-b pb-3">
                <h3 class="font-black text-slate-900 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-pen text-emerald-600"></i> Edit Master Kantor
                </h3>
                <button type="button" @click="showEditModal = false" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form :action="'{{ url('admin/offices') }}/' + activeOffice.id + '/update'" method="POST" class="space-y-3 text-xs">
                @csrf
                @method('PUT')
                <div>
                    <label class="block font-bold text-slate-700 mb-1">ID / Kode Kantor</label>
                    <input type="text" name="office_code" :value="activeOffice.office_code" class="w-full border border-slate-300 rounded-xl px-3 py-2 text-xs focus:border-emerald-600">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nama Kantor *</label>
                    <input type="text" name="name" required :value="activeOffice.name" class="w-full border border-slate-300 rounded-xl px-3 py-2 text-xs focus:border-emerald-600">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Kota / Kabupaten</label>
                        <input type="text" name="city" :value="activeOffice.city" class="w-full border border-slate-300 rounded-xl px-3 py-2 text-xs focus:border-emerald-600">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Provinsi</label>
                        <input type="text" name="province" :value="activeOffice.province" class="w-full border border-slate-300 rounded-xl px-3 py-2 text-xs focus:border-emerald-600">
                    </div>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Kantor Parent (Induk)</label>
                    <select name="parent_id" class="w-full border border-slate-300 rounded-xl px-3 py-2 text-xs focus:border-emerald-600">
                        <option value="">-- Pilih Kantor Parent --</option>
                        @foreach($allOffices as $ofc)
                            <option value="{{ $ofc->id }}" :selected="activeOffice.parent_id == {{ $ofc->id }}">{{ $ofc->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Status Keaktifan</label>
                    <select name="status" class="w-full border border-slate-300 rounded-xl px-3 py-2 text-xs focus:border-emerald-600">
                        <option value="Aktif" :selected="activeOffice.status == 'Aktif'">Aktif (y)</option>
                        <option value="Non-Aktif" :selected="activeOffice.status == 'Non-Aktif'">Non-Aktif (n)</option>
                    </select>
                </div>
                <div class="flex justify-end gap-2 pt-3 border-t">
                    <button type="button" @click="showEditModal = false" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-xl font-bold">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-emerald-600 text-white rounded-xl font-bold hover:bg-emerald-700">Update Kantor</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL DELETE KANTOR --}}
    <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-sm w-full p-6 space-y-4 text-center shadow-xl border border-slate-100">
            <div class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center mx-auto text-xl">
                <i class="fa-solid fa-trash"></i>
            </div>
            <h3 class="font-black text-slate-900 text-sm">Hapus Master Kantor?</h3>
            <p class="text-xs text-slate-500">Apakah Anda yakin ingin menghapus kantor <strong class="text-slate-900" x-text="activeOffice.name"></strong>?</p>
            <form :action="'{{ url('admin/offices') }}/' + activeOffice.id + '/delete'" method="POST" class="flex justify-center gap-2 pt-2">
                @csrf
                @method('DELETE')
                <button type="button" @click="showDeleteModal = false" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-xl font-bold text-xs">Batal</button>
                <button type="submit" class="px-5 py-2 bg-rose-600 text-white rounded-xl font-bold text-xs hover:bg-rose-700">Hapus</button>
            </form>
        </div>
    </div>

</div>
@endsection
