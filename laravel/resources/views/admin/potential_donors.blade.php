@extends('layouts.app')

@section('content')
<div class="container max-w-7xl mx-auto px-4 py-8" x-data="{
    showAdvanceSearch: true,
    showAddModal: false,
    showEditModal: false,
    showDeleteModal: false,
    showImportModal: false,
    showSetDonorModal: false,
    selectedItem: null,
    selectedId: null,
    selectedName: '',
    editData: {
        id: null,
        name: '',
        phone: '',
        email: ''
    },
    openEdit(item) {
        this.editData = {
            id: item.id,
            name: item.name ?? '',
            phone: item.phone ?? '',
            email: item.email ?? ''
        };
        this.showEditModal = true;
    },
    openDelete(id, name) {
        this.selectedId = id;
        this.selectedName = name;
        this.showDeleteModal = true;
    },
    openSetDonor(id, name) {
        this.selectedId = id;
        this.selectedName = name;
        this.showSetDonorModal = true;
    }
}">

    <div class="grid grid-cols-1 lg:grid-cols-[280px_1fr] gap-8 items-start min-w-0">

        {{-- Sidebar --}}
        <aside class="hidden lg:block space-y-5">
            @include('admin.sidebar', ['active' => 'potential_donors'])
        </aside>

        {{-- Main Content Column (Locked min-w-0 to prevent page-wide horizontal scrolling) --}}
        <div class="space-y-6 min-w-0 overflow-hidden">

            {{-- Top Emerald Header Banner --}}
            <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 rounded-2xl px-6 py-3.5 text-white font-black text-sm uppercase tracking-wider shadow-sm flex items-center justify-between">
                <span class="flex items-center gap-2">
                    <i class="fa-solid fa-address-book text-white"></i> List Calon Donatur
                </span>
                <span class="text-xs text-white/80 font-normal">Transaction Management → List Calon Donatur</span>
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

            {{-- Filter Container & Search Bar (Presisi Form Screenshot User) --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm space-y-4">
                
                <form action="{{ route('admin.potential_donors') }}" method="GET" class="space-y-4">
                    
                    {{-- Row 1: Search Bar & Set Donatur --}}
                    <div class="flex flex-wrap items-center gap-3">
                        <span class="text-xs font-bold text-slate-700">Search :</span>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Nama Lengkap/ID/No HP/Email" 
                               class="flex-1 border border-slate-200 rounded-xl px-4 py-2 text-xs font-medium focus:outline-none focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 min-w-[280px]">
                        
                        <span class="text-xs font-bold text-slate-700">Set Donatur :</span>
                        <select name="is_donor" class="border border-slate-200 rounded-xl px-3 py-1.5 text-xs focus:border-emerald-600">
                            <option value="Belum">Belum</option>
                            <option value="Sudah">Sudah</option>
                            <option value="All">All</option>
                        </select>

                        <button type="submit" class="bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 font-bold px-4 py-2 rounded-xl text-xs flex items-center gap-1.5 transition-all">
                            🔍 Search
                        </button>
                        
                        <button type="button" @click="showAdvanceSearch = !showAdvanceSearch" 
                                class="bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 font-bold px-4 py-2 rounded-xl text-xs flex items-center gap-1.5 transition-all">
                            🔍 Advance Search
                        </button>
                    </div>

                    {{-- Advance Search Drawer (4 Rows of 2 Columns) --}}
                    <div x-show="showAdvanceSearch" x-collapse class="bg-slate-50/90 border border-slate-200 p-4 rounded-xl space-y-3 text-xs text-slate-700 shadow-inner">
                        
                        {{-- Row 1 --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 items-center">
                            <div class="flex items-center gap-2 min-w-0">
                                <span class="w-28 shrink-0 text-[11px] font-semibold whitespace-nowrap">Tgl Awal Prospek</span>
                                <span class="shrink-0 text-slate-400 font-bold">:</span>
                                <input type="date" name="start_prospect_date" value="{{ request('start_prospect_date') }}" class="flex-1 min-w-0 bg-white border border-slate-200 rounded-lg px-2 py-1.5 text-[11px] focus:outline-none focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600">
                                <span class="shrink-0 text-slate-400 font-medium text-[11px]">s/d</span>
                                <input type="date" name="end_prospect_date" value="{{ request('end_prospect_date') }}" class="flex-1 min-w-0 bg-white border border-slate-200 rounded-lg px-2 py-1.5 text-[11px] focus:outline-none focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600">
                            </div>
                            <div class="flex items-center gap-2 min-w-0">
                                <span class="w-28 shrink-0 text-[11px] font-semibold whitespace-nowrap">Last Prospek</span>
                                <span class="shrink-0 text-slate-400 font-bold">:</span>
                                <input type="date" name="start_last_prospect" value="{{ request('start_last_prospect') }}" class="flex-1 min-w-0 bg-white border border-slate-200 rounded-lg px-2 py-1.5 text-[11px] focus:outline-none focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600">
                                <span class="shrink-0 text-slate-400 font-medium text-[11px]">s/d</span>
                                <input type="date" name="end_last_prospect" value="{{ request('end_last_prospect') }}" class="flex-1 min-w-0 bg-white border border-slate-200 rounded-lg px-2 py-1.5 text-[11px] focus:outline-none focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600">
                            </div>
                        </div>

                        {{-- Row 2 --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 items-center">
                            <div class="flex items-center gap-2 min-w-0">
                                <span class="w-28 shrink-0 text-[11px] font-semibold whitespace-nowrap">Pernah Diprospek</span>
                                <span class="shrink-0 text-slate-400 font-bold">:</span>
                                <select name="ever_prospected" class="flex-1 min-w-0 bg-white border border-slate-200 rounded-lg px-2 py-1.5 text-[11px] focus:outline-none focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600">
                                    <option value="All">All</option>
                                    <option value="Ya">Ya</option>
                                    <option value="Tidak">Tidak</option>
                                </select>
                            </div>
                            <div class="flex items-center gap-2 min-w-0">
                                <span class="w-28 shrink-0 text-[11px] font-semibold whitespace-nowrap">Last Respon</span>
                                <span class="shrink-0 text-slate-400 font-bold">:</span>
                                <select name="last_response" class="flex-1 min-w-0 bg-white border border-slate-200 rounded-lg px-2 py-1.5 text-[11px] focus:outline-none focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600">
                                    <option value="">Last Respon Prospek</option>
                                    @foreach($prospectResponses as $pr)
                                        <option value="{{ $pr->id }}">{{ $pr->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Row 3 --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 items-center">
                            <div class="flex items-center gap-2 min-w-0">
                                <span class="w-28 shrink-0 text-[11px] font-semibold whitespace-nowrap">Last Media Prospek</span>
                                <span class="shrink-0 text-slate-400 font-bold">:</span>
                                <select name="last_media" class="flex-1 min-w-0 bg-white border border-slate-200 rounded-lg px-2 py-1.5 text-[11px] focus:outline-none focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600">
                                    <option value="">Last Media Prospek</option>
                                    @foreach($prospectMedia as $pm)
                                        <option value="{{ $pm->id }}">{{ $pm->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex items-center gap-2 min-w-0">
                                <span class="w-28 shrink-0 text-[11px] font-semibold whitespace-nowrap">Ada No HP</span>
                                <span class="shrink-0 text-slate-400 font-bold">:</span>
                                <select name="has_phone" class="flex-1 min-w-0 bg-white border border-slate-200 rounded-lg px-2 py-1.5 text-[11px] focus:outline-none focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600">
                                    <option value="All">All</option>
                                    <option value="Ya">Ya</option>
                                    <option value="Tidak">Tidak</option>
                                </select>
                            </div>
                        </div>

                        {{-- Row 4 --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 items-center">
                            <div class="flex items-center gap-2 min-w-0">
                                <span class="w-28 shrink-0 text-[11px] font-semibold whitespace-nowrap">Ada Email</span>
                                <span class="shrink-0 text-slate-400 font-bold">:</span>
                                <select name="has_email" class="flex-1 min-w-0 bg-white border border-slate-200 rounded-lg px-2 py-1.5 text-[11px] focus:outline-none focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600">
                                    <option value="All">All</option>
                                    <option value="Ya">Ya</option>
                                    <option value="Tidak">Tidak</option>
                                </select>
                            </div>
                            <div class="flex items-center gap-2 min-w-0">
                                <span class="w-28 shrink-0 text-[11px] font-semibold whitespace-nowrap">Aktif</span>
                                <span class="shrink-0 text-slate-400 font-bold">:</span>
                                <select name="is_active" class="flex-1 min-w-0 bg-white border border-slate-200 rounded-lg px-2 py-1.5 text-[11px] focus:outline-none focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600">
                                    <option value="Ya">Ya</option>
                                    <option value="Tidak">Tidak</option>
                                </select>
                            </div>
                        </div>

                    </div>
                </form>

            </div>

            {{-- Action Toolbar: Add, Edit, Delete, Set Donatur, Export, Import --}}
            <div class="flex flex-wrap items-center gap-2 pt-1 pb-1">
                <button type="button" @click="showAddModal = true" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-4 py-2 rounded-xl text-xs flex items-center gap-1.5 shadow-sm transition-all">
                    <i class="fa-solid fa-circle-plus"></i> Add
                </button>

                <button type="button" 
                        @click="if(selectedItem) { openEdit(selectedItem); } else { alert('Pilih calon donatur dari tabel terlebih dahulu.'); }" 
                        class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-4 py-2 rounded-xl text-xs flex items-center gap-1.5 shadow-sm transition-all">
                    <i class="fa-solid fa-pen-to-square"></i> Edit
                </button>

                <button type="button" 
                        @click="if(selectedId) { openDelete(selectedId, selectedName); } else { alert('Pilih calon donatur dari tabel terlebih dahulu.'); }" 
                        class="bg-rose-600 hover:bg-rose-700 text-white font-bold px-4 py-2 rounded-xl text-xs flex items-center gap-1.5 shadow-sm transition-all">
                    <i class="fa-solid fa-trash-can"></i> Delete
                </button>

                <button type="button" 
                        @click="if(selectedId) { openSetDonor(selectedId, selectedName); } else { alert('Pilih calon donatur dari tabel terlebih dahulu.'); }" 
                        class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-4 py-2 rounded-xl text-xs flex items-center gap-1.5 shadow-sm transition-all">
                    <i class="fa-solid fa-user-check"></i> Set Donatur
                </button>

                <a href="{{ route('admin.potential_donors.export', request()->query()) }}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-4 py-2 rounded-xl text-xs flex items-center gap-1.5 shadow-sm transition-all">
                    <i class="fa-solid fa-file-excel"></i> Export
                </a>

                <button type="button" @click="showImportModal = true" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-4 py-2 rounded-xl text-xs flex items-center gap-1.5 shadow-sm transition-all">
                    <i class="fa-solid fa-file-import"></i> Import
                </button>
            </div>

            {{-- Table Calon Donatur Container (HANYA TABEL YANG DI-SCROLL KE SAMPIING - Tema Emerald Web Utama) --}}
            <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm w-full">
                
                {{-- Horizontal Scrollable Wrapper (Khusus Tabel Saja - 22 Kolom Presisi Sample Data User) --}}
                <div class="w-full overflow-x-auto overflow-y-hidden scrollbar-thin scrollbar-thumb-emerald-600 scrollbar-track-slate-100">
                    <table class="w-full text-left border-collapse whitespace-nowrap min-w-[2500px]">
                        <thead>
                            <tr class="bg-gradient-to-r from-emerald-600 to-emerald-700 text-white text-[11px] font-black uppercase tracking-wider">
                                <th class="py-3.5 px-4 text-center">No</th>
                                <th class="py-3.5 px-4">ID Calon Donatur</th>
                                <th class="py-3.5 px-4">Nama Lengkap/Mitra</th>
                                <th class="py-3.5 px-4 text-center">Tgl Insert</th>
                                <th class="py-3.5 px-4">No HP</th>
                                <th class="py-3.5 px-4">Email</th>
                                <th class="py-3.5 px-4">Nama PIC</th>
                                <th class="py-3.5 px-4">Alamat</th>
                                <th class="py-3.5 px-4">Keterangan</th>
                                <th class="py-3.5 px-4 text-center">Tgl Awal Prospek</th>
                                <th class="py-3.5 px-4 text-center bg-emerald-800 shadow-inner">Action</th>
                                <th class="py-3.5 px-4">PIC Prospek</th>
                                <th class="py-3.5 px-4 text-center">Donatur</th>
                                <th class="py-3.5 px-4 text-center">Tgl Set Donatur</th>
                                <th class="py-3.5 px-4 text-center">Tgl Prospek Terakhir</th>
                                <th class="py-3.5 px-4">Media Prospek Terakhir</th>
                                <th class="py-3.5 px-4">Respon Prospek Terakhir</th>
                                <th class="py-3.5 px-4 text-right">Potensi Prospek Terakhir</th>
                                <th class="py-3.5 px-4">Metode Prospek Terakhir</th>
                                <th class="py-3.5 px-4">Rumpun Program Prospek Terakhir</th>
                                <th class="py-3.5 px-4">Campaign Prospek Terakhir</th>
                                <th class="py-3.5 px-4">Ket Prospek Terakhir</th>
                            </tr>
                        </thead>
                        <tbody class="text-xs font-semibold text-slate-700 divide-y divide-slate-100">
                            @forelse($potentialDonors as $index => $pd)
                                @php
                                    $phoneClean = preg_replace('/[^0-9]/', '', $pd->phone ?: '');
                                    if ($phoneClean && str_starts_with($phoneClean, '0')) {
                                        $phoneClean = '62' . substr($phoneClean, 1);
                                    }
                                    $waMsg = urlencode("Assalamu'alaikum Kak " . $pd->name);
                                    $waUrl = $phoneClean ? "https://api.whatsapp.com/send?phone={$phoneClean}&text={$waMsg}" : '#';
                                    $lastResp = $pd->responses ? $pd->responses->last() : null;
                                @endphp
                                <tr @click="selectedItem = {{ json_encode($pd) }}; selectedId = {{ $pd->id }}; selectedName = '{{ addslashes($pd->name) }}'" 
                                    :class="selectedId === {{ $pd->id }} ? 'bg-emerald-50 border-l-4 border-emerald-600' : 'hover:bg-slate-50/80'"
                                    class="cursor-pointer transition-colors">
                                    <td class="py-3.5 px-4 text-center font-mono text-slate-400">{{ $potentialDonors->firstItem() + $index }}</td>
                                    <td class="py-3.5 px-4 font-mono font-bold text-emerald-700">{{ $pd->id }}</td>
                                    <td class="py-3.5 px-4 font-bold text-slate-900">{{ $pd->name ?: '-' }}</td>
                                    <td class="py-3.5 px-4 text-center font-mono text-slate-500">{{ $pd->created_at ? $pd->created_at->format('Y-m-d H:i:s') : '-' }}</td>
                                    <td class="py-3.5 px-4 font-mono text-slate-600">{{ $pd->phone ?: '-' }}</td>
                                    <td class="py-3.5 px-4 text-slate-600">{{ $pd->email ?: '-' }}</td>
                                    <td class="py-3.5 px-4 text-slate-800">{{ Auth::user()->name }}</td>
                                    <td class="py-3.5 px-4 text-slate-400">-</td>
                                    <td class="py-3.5 px-4 text-slate-400">-</td>
                                    <td class="py-3.5 px-4 text-center font-mono text-slate-500">{{ $pd->created_at ? $pd->created_at->format('Y-m-d') : '-' }}</td>
                                    
                                    {{-- ACTION COLUMN WITH WHATSAPP & EDIT ICONS --}}
                                    <td class="py-3.5 px-4 text-center bg-slate-50/80">
                                        <div class="flex items-center justify-center gap-2" @click.stop>
                                            {{-- WhatsApp Direct Chat Icon --}}
                                            @if($phoneClean)
                                            <a href="{{ $waUrl }}" target="_blank" 
                                               class="inline-flex items-center justify-center w-7 h-7 bg-emerald-500 hover:bg-emerald-600 text-white rounded-full shadow-sm hover:scale-110 transition-transform" 
                                               title="Kirim Pesan WhatsApp">
                                                <i class="fa-brands fa-whatsapp text-sm"></i>
                                            </a>
                                            @else
                                            <span class="inline-flex items-center justify-center w-7 h-7 bg-slate-200 text-slate-400 rounded-full cursor-not-allowed" title="Nomor WhatsApp tidak tersedia">
                                                <i class="fa-brands fa-whatsapp text-sm"></i>
                                            </span>
                                            @endif

                                            {{-- Edit Icon --}}
                                            <button type="button" @click="openEdit({{ json_encode($pd) }})" 
                                                    class="inline-flex items-center justify-center w-7 h-7 bg-sky-50 hover:bg-sky-100 text-sky-600 border border-slate-700 rounded-lg shadow-sm hover:scale-110 transition-transform" 
                                                    title="Edit Data Calon Donatur">
                                                <i class="fa-solid fa-pen-to-square text-xs"></i>
                                            </button>
                                        </div>
                                    </td>

                                    <td class="py-3.5 px-4 font-bold text-slate-800">{{ Auth::user()->name }}</td>
                                    <td class="py-3.5 px-4 text-center font-bold text-slate-400">-</td>
                                    <td class="py-3.5 px-4 text-center font-mono text-slate-500">-</td>
                                    <td class="py-3.5 px-4 text-center font-mono text-slate-500">{{ $lastResp ? $lastResp->created_at->format('Y-m-d H:i:s') : '-' }}</td>
                                    <td class="py-3.5 px-4 font-bold text-slate-600">{{ $lastResp ? 'WhatsApp' : '-' }}</td>
                                    <td class="py-3.5 px-4">
                                        <span class="px-2.5 py-1 {{ $lastResp ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }} rounded-full text-[10px] font-bold">
                                            {{ $lastResp ? $lastResp->status : 'Belum Diprospek' }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 text-right font-black text-slate-400">-</td>
                                    <td class="py-3.5 px-4 text-slate-700">-</td>
                                    <td class="py-3.5 px-4 text-slate-800">-</td>
                                    <td class="py-3.5 px-4 text-slate-900 font-medium">-</td>
                                    <td class="py-3.5 px-4 text-slate-500 italic max-w-xs truncate">{{ $lastResp ? $lastResp->response : '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="22" class="py-12 text-center text-slate-400 font-bold">Belum ada data calon donatur. Silakan klik + Add untuk menambah calon donatur baru.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($potentialDonors->hasPages())
                    <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                        {{ $potentialDonors->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>

    {{-- ==================== MODAL ADD CALON DONATUR ==================== --}}
    <div x-show="showAddModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl space-y-5 animate-in zoom-in-95 duration-200">
            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                <h3 class="text-base font-black text-slate-900 uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-user-plus text-emerald-600"></i> Tambah Data Calon Donatur
                </h3>
                <button @click="showAddModal = false" class="text-slate-400 hover:text-slate-600 text-lg"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form action="{{ route('admin.potential_donors.store') }}" method="POST" class="space-y-4 text-xs font-semibold text-slate-700">
                @csrf
                <div class="space-y-1">
                    <label>Nama Lengkap / Mitra <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" required placeholder="Contoh: Nama Calon Donatur" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-emerald-600">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label>No HP / WA</label>
                        <input type="text" name="phone" placeholder="Contoh: 08123456789" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-emerald-600">
                    </div>
                    <div class="space-y-1">
                        <label>Email</label>
                        <input type="email" name="email" placeholder="Contoh: calon@email.com" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-emerald-600">
                    </div>
                </div>
                <div class="flex justify-end gap-3 pt-3">
                    <button type="button" @click="showAddModal = false" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-bold">Batal</button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold shadow-md">Simpan Calon Donatur</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ==================== MODAL EDIT CALON DONATUR ==================== --}}
    <div x-show="showEditModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl space-y-5 animate-in zoom-in-95 duration-200">
            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                <h3 class="text-base font-black text-slate-900 uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-pen-to-square text-emerald-600"></i> Edit Data Calon Donatur
                </h3>
                <button @click="showEditModal = false" class="text-slate-400 hover:text-slate-600 text-lg"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form :action="'{{ url('admin/potential-donors') }}/' + editData.id + '/update'" method="POST" class="space-y-4 text-xs font-semibold text-slate-700">
                @csrf
                @method('PUT')
                <div class="space-y-1">
                    <label>Nama Lengkap / Mitra <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" x-model="editData.name" required class="w-full border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-emerald-600">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label>No HP / WA</label>
                        <input type="text" name="phone" x-model="editData.phone" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-emerald-600">
                    </div>
                    <div class="space-y-1">
                        <label>Email</label>
                        <input type="email" name="email" x-model="editData.email" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-emerald-600">
                    </div>
                </div>
                <div class="flex justify-end gap-3 pt-3">
                    <button type="button" @click="showEditModal = false" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-bold">Batal</button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold shadow-md">Perbarui Data</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ==================== MODAL DELETE CALON DONATUR ==================== --}}
    <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-sm w-full p-6 shadow-2xl space-y-4 text-center animate-in zoom-in-95 duration-200">
            <div class="w-14 h-14 bg-rose-50 text-rose-600 rounded-full flex items-center justify-center text-2xl mx-auto">
                <i class="fa-solid fa-trash-can"></i>
            </div>
            <div class="space-y-1">
                <h3 class="text-base font-black text-slate-900">Konfirmasi Hapus</h3>
                <p class="text-xs text-slate-500 font-medium">Apakah Anda yakin ingin menghapus calon donatur <span class="font-bold text-slate-900" x-text="selectedName"></span>?</p>
            </div>
            <form :action="'{{ url('admin/potential-donors') }}/' + selectedId + '/delete'" method="POST" class="flex justify-center gap-3 pt-2">
                @csrf
                @method('DELETE')
                <button type="button" @click="showDeleteModal = false" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold text-xs">Batal</button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs shadow-md">Ya, Hapus Data</button>
            </form>
        </div>
    </div>

    {{-- ==================== MODAL SET DONATUR ==================== --}}
    <div x-show="showSetDonorModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-sm w-full p-6 shadow-2xl space-y-4 text-center animate-in zoom-in-95 duration-200">
            <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center text-2xl mx-auto">
                <i class="fa-solid fa-user-check"></i>
            </div>
            <div class="space-y-1">
                <h3 class="text-base font-black text-slate-900">Ubah Menjadi Donatur Resmi</h3>
                <p class="text-xs text-slate-500 font-medium">Ubah status <span class="font-bold text-slate-900" x-text="selectedName"></span> menjadi Donatur Resmi?</p>
            </div>
            <form :action="'{{ url('admin/potential-donors') }}/' + selectedId + '/set-donor'" method="POST" class="flex justify-center gap-3 pt-2">
                @csrf
                <button type="button" @click="showSetDonorModal = false" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold text-xs">Batal</button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md">Ya, Set Donatur</button>
            </form>
        </div>
    </div>

    {{-- ==================== MODAL IMPORT CSV ==================== --}}
    <div x-show="showImportModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl space-y-4 animate-in zoom-in-95 duration-200">
            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                <h3 class="text-base font-black text-slate-900 uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-file-import text-emerald-600"></i> Import Data Calon Donatur (CSV)
                </h3>
                <button @click="showImportModal = false" class="text-slate-400 hover:text-slate-600 text-lg"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form action="{{ route('admin.potential_donors.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs font-semibold text-slate-700">
                @csrf
                <div class="space-y-1">
                    <label>Pilih File Excel / CSV (.csv)</label>
                    <input type="file" name="file" accept=".csv" required class="w-full border border-slate-200 rounded-xl px-4 py-2.5">
                </div>
                <p class="text-[10px] text-slate-400 italic">Format kolom CSV: No, ID Calon Donatur, Nama Lengkap/Mitra, Tgl Insert, No HP, Email</p>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" @click="showImportModal = false" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold">Batal</button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold shadow-md">Upload & Import</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
