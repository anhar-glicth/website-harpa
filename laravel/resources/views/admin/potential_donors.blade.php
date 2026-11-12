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
            <div class="bg-emerald-700 rounded-2xl px-6 py-3.5 text-white font-black text-sm uppercase tracking-wider shadow-sm flex flex-wrap items-center justify-between gap-2">
                <span class="flex items-center gap-2">
                    <i class="fa-solid fa-address-book text-white"></i> List Calon Donatur
                </span>
                <span class="text-xs text-emerald-100 font-normal">Transaction Management / List Calon Donatur</span>
            </div>

            {{-- Flash Messages --}}
            @if(session('success'))
                <div role="status" class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-3.5 rounded-2xl text-sm font-semibold flex items-center gap-3">
                    <i class="fa-solid fa-circle-check text-emerald-700"></i>
                    {{ session('success') }}
                </div>
            @endif
            @if($errors->any())
                <div role="alert" class="bg-rose-50 border border-rose-200 text-rose-800 px-5 py-3.5 rounded-2xl text-sm font-semibold flex items-start gap-3">
                    <i class="fa-solid fa-circle-exclamation text-rose-700 mt-0.5"></i>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Filter Container & Search Bar --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm space-y-4">
                
                <form action="{{ route('admin.potential_donors') }}" method="GET" class="space-y-4">
                    
                    {{-- Row 1: Search Bar & Set Donatur --}}
                    <div class="flex flex-wrap items-center gap-3">
                        <label for="pd_search" class="text-xs font-bold text-slate-700">Search :</label>
                        <input id="pd_search" type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Nama Lengkap/ID/No HP/Email" 
                               class="flex-1 border border-slate-300 rounded-xl px-4 py-2.5 min-h-[44px] text-xs font-medium focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700 min-w-[240px]">
                        
                        <label for="pd_is_donor" class="text-xs font-bold text-slate-700">Set Donatur :</label>
                        <select id="pd_is_donor" name="is_donor" class="border border-slate-300 rounded-xl px-3 py-2 min-h-[44px] text-xs focus:border-emerald-700">
                            <option value="Belum">Belum</option>
                            <option value="Sudah">Sudah</option>
                            <option value="All">All</option>
                        </select>

                        <button type="submit" class="bg-white hover:border-[#063f5c] text-slate-800 hover:text-[#063f5c] border border-slate-300 font-bold px-4 py-2.5 min-h-[44px] rounded-xl text-xs inline-flex items-center gap-1.5 transition-colors">
                            <i class="fa-solid fa-magnifying-glass"></i> Search
                        </button>
                        
                        <button type="button" @click="showAdvanceSearch = !showAdvanceSearch" 
                                :aria-expanded="showAdvanceSearch"
                                class="min-h-[44px] bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-300 font-bold px-4 py-2 rounded-xl text-xs inline-flex items-center gap-1.5 transition-colors">
                            <i class="fa-solid fa-sliders" aria-hidden="true"></i> Advance Search
                        </button>
                    </div>

                    {{-- Advance Search Drawer (4 Rows of 2 Columns) --}}
                    <div x-show="showAdvanceSearch" x-collapse class="bg-slate-50 border border-slate-200 p-4 rounded-xl space-y-3 text-xs text-slate-700 shadow-inner">
                        
                        {{-- Row 1 --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 items-center">
                            <div class="flex items-center gap-2 min-w-0">
                                <span class="w-28 shrink-0 text-xs font-semibold whitespace-nowrap">Tgl Awal Prospek</span>
                                <span class="shrink-0 text-slate-600 font-bold">:</span>
                                <input aria-label="Tanggal awal prospek mulai" type="date" name="start_prospect_date" value="{{ request('start_prospect_date') }}" class="flex-1 min-w-0 min-h-[44px] bg-white border border-slate-300 rounded-lg px-2 py-1.5 text-xs focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700">
                                <span class="shrink-0 text-slate-600 font-medium text-xs">s/d</span>
                                <input aria-label="Tanggal awal prospek akhir" type="date" name="end_prospect_date" value="{{ request('end_prospect_date') }}" class="flex-1 min-w-0 min-h-[44px] bg-white border border-slate-300 rounded-lg px-2 py-1.5 text-xs focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700">
                            </div>
                            <div class="flex items-center gap-2 min-w-0">
                                <span class="w-28 shrink-0 text-xs font-semibold whitespace-nowrap">Last Prospek</span>
                                <span class="shrink-0 text-slate-600 font-bold">:</span>
                                <input aria-label="Tanggal prospek terakhir mulai" type="date" name="start_last_prospect" value="{{ request('start_last_prospect') }}" class="flex-1 min-w-0 min-h-[44px] bg-white border border-slate-300 rounded-lg px-2 py-1.5 text-xs focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700">
                                <span class="shrink-0 text-slate-600 font-medium text-xs">s/d</span>
                                <input aria-label="Tanggal prospek terakhir akhir" type="date" name="end_last_prospect" value="{{ request('end_last_prospect') }}" class="flex-1 min-w-0 min-h-[44px] bg-white border border-slate-300 rounded-lg px-2 py-1.5 text-xs focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700">
                            </div>
                        </div>

                        {{-- Row 2 --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 items-center">
                            <div class="flex items-center gap-2 min-w-0">
                                <span class="w-28 shrink-0 text-xs font-semibold whitespace-nowrap">Pernah Diprospek</span>
                                <span class="shrink-0 text-slate-600 font-bold">:</span>
                                <select aria-label="Filter pernah diprospek" name="ever_prospected" class="flex-1 min-w-0 min-h-[44px] bg-white border border-slate-300 rounded-lg px-2 py-1.5 text-xs focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700">
                                    <option value="All">All</option>
                                    <option value="Ya">Ya</option>
                                    <option value="Tidak">Tidak</option>
                                </select>
                            </div>
                            <div class="flex items-center gap-2 min-w-0">
                                <span class="w-28 shrink-0 text-xs font-semibold whitespace-nowrap">Last Respon</span>
                                <span class="shrink-0 text-slate-600 font-bold">:</span>
                                <select aria-label="Filter respon prospek terakhir" name="last_response" class="flex-1 min-w-0 min-h-[44px] bg-white border border-slate-300 rounded-lg px-2 py-1.5 text-xs focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700">
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
                                <span class="w-28 shrink-0 text-xs font-semibold whitespace-nowrap">Last Media Prospek</span>
                                <span class="shrink-0 text-slate-600 font-bold">:</span>
                                <select aria-label="Filter media prospek terakhir" name="last_media" class="flex-1 min-w-0 min-h-[44px] bg-white border border-slate-300 rounded-lg px-2 py-1.5 text-xs focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700">
                                    <option value="">Last Media Prospek</option>
                                    @foreach($prospectMedia as $pm)
                                        <option value="{{ $pm->id }}">{{ $pm->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex items-center gap-2 min-w-0">
                                <span class="w-28 shrink-0 text-xs font-semibold whitespace-nowrap">Ada No HP</span>
                                <span class="shrink-0 text-slate-600 font-bold">:</span>
                                <select aria-label="Filter kepemilikan nomor HP" name="has_phone" class="flex-1 min-w-0 min-h-[44px] bg-white border border-slate-300 rounded-lg px-2 py-1.5 text-xs focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700">
                                    <option value="All">All</option>
                                    <option value="Ya">Ya</option>
                                    <option value="Tidak">Tidak</option>
                                </select>
                            </div>
                        </div>

                        {{-- Row 4 --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 items-center">
                            <div class="flex items-center gap-2 min-w-0">
                                <span class="w-28 shrink-0 text-xs font-semibold whitespace-nowrap">Ada Email</span>
                                <span class="shrink-0 text-slate-600 font-bold">:</span>
                                <select aria-label="Filter kepemilikan email" name="has_email" class="flex-1 min-w-0 min-h-[44px] bg-white border border-slate-300 rounded-lg px-2 py-1.5 text-xs focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700">
                                    <option value="All">All</option>
                                    <option value="Ya">Ya</option>
                                    <option value="Tidak">Tidak</option>
                                </select>
                            </div>
                            <div class="flex items-center gap-2 min-w-0">
                                <span class="w-28 shrink-0 text-xs font-semibold whitespace-nowrap">Aktif</span>
                                <span class="shrink-0 text-slate-600 font-bold">:</span>
                                <select aria-label="Filter status aktif" name="is_active" class="flex-1 min-w-0 min-h-[44px] bg-white border border-slate-300 rounded-lg px-2 py-1.5 text-xs focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700">
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
                <button type="button" @click="showAddModal = true" class="bg-emerald-700 hover:bg-emerald-800 text-white font-bold px-4 py-2.5 min-h-[44px] rounded-xl text-xs inline-flex items-center gap-1.5 shadow-sm transition-colors">
                    <i class="fa-solid fa-circle-plus"></i> Add
                </button>

                <button type="button" 
                        @click="if(selectedItem) { openEdit(selectedItem); } else { alert('Pilih calon donatur dari tabel terlebih dahulu.'); }" 
                        class="bg-emerald-700 hover:bg-emerald-800 text-white font-bold px-4 py-2.5 min-h-[44px] rounded-xl text-xs inline-flex items-center gap-1.5 shadow-sm transition-colors">
                    <i class="fa-solid fa-pen-to-square"></i> Edit
                </button>

                <button type="button" 
                        @click="if(selectedId) { openDelete(selectedId, selectedName); } else { alert('Pilih calon donatur dari tabel terlebih dahulu.'); }" 
                        class="bg-rose-700 hover:bg-rose-800 text-white font-bold px-4 py-2.5 min-h-[44px] rounded-xl text-xs inline-flex items-center gap-1.5 shadow-sm transition-colors">
                    <i class="fa-solid fa-trash-can"></i> Delete
                </button>

                <button type="button" 
                        @click="if(selectedId) { openSetDonor(selectedId, selectedName); } else { alert('Pilih calon donatur dari tabel terlebih dahulu.'); }" 
                        class="bg-emerald-700 hover:bg-emerald-800 text-white font-bold px-4 py-2.5 min-h-[44px] rounded-xl text-xs inline-flex items-center gap-1.5 shadow-sm transition-colors">
                    <i class="fa-solid fa-user-check"></i> Set Donatur
                </button>

                <a href="{{ route('admin.potential_donors.export', request()->query()) }}" class="bg-emerald-700 hover:bg-emerald-800 text-white font-bold px-4 py-2.5 min-h-[44px] rounded-xl text-xs inline-flex items-center gap-1.5 shadow-sm transition-colors">
                    <i class="fa-solid fa-file-excel"></i> Export
                </a>

                <button type="button" @click="showImportModal = true" class="bg-emerald-700 hover:bg-emerald-800 text-white font-bold px-4 py-2.5 min-h-[44px] rounded-xl text-xs inline-flex items-center gap-1.5 shadow-sm transition-colors">
                    <i class="fa-solid fa-file-import"></i> Import
                </button>
            </div>

            {{-- Table Calon Donatur Container --}}
            <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm w-full">
                
                {{-- Horizontal Scrollable Wrapper --}}
                <div class="w-full overflow-x-auto overflow-y-hidden scrollbar-thin scrollbar-thumb-emerald-700 scrollbar-track-slate-100">
                    <table class="w-full text-left border-collapse whitespace-nowrap min-w-[2500px]">
                        <thead>
                            <tr class="bg-emerald-700 text-white text-xs font-black uppercase tracking-wider">
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
                                    <td class="py-3.5 px-4 text-center font-mono text-slate-600">{{ $potentialDonors->firstItem() + $index }}</td>
                                    <td class="py-3.5 px-4 font-mono font-bold text-emerald-700">{{ $pd->id }}</td>
                                    <td class="py-3.5 px-4 font-bold text-slate-900">{{ $pd->name ?: '-' }}</td>
                                    <td class="py-3.5 px-4 text-center font-mono text-slate-600">{{ $pd->created_at ? $pd->created_at->format('Y-m-d H:i:s') : '-' }}</td>
                                    <td class="py-3.5 px-4 font-mono text-slate-600">{{ $pd->phone ?: '-' }}</td>
                                    <td class="py-3.5 px-4 text-slate-600">{{ $pd->email ?: '-' }}</td>
                                    <td class="py-3.5 px-4 text-slate-800">{{ Auth::user()->name }}</td>
                                    <td class="py-3.5 px-4 text-slate-600">-</td>
                                    <td class="py-3.5 px-4 text-slate-600">-</td>
                                    <td class="py-3.5 px-4 text-center font-mono text-slate-600">{{ $pd->created_at ? $pd->created_at->format('Y-m-d') : '-' }}</td>
                                    
                                    {{-- ACTION COLUMN WITH WHATSAPP & EDIT ICONS --}}
                                    <td class="py-3.5 px-4 text-center bg-slate-50">
                                        <div class="flex items-center justify-center gap-2" @click.stop>
                                            {{-- WhatsApp Direct Chat Icon --}}
                                            @if($phoneClean)
                                            <a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer"
                                               aria-label="Kirim pesan WhatsApp ke {{ $pd->name }}"
                                               class="inline-flex items-center justify-center w-11 h-11 bg-emerald-700 hover:bg-emerald-800 text-white rounded-full shadow-sm transition-colors" 
                                               title="Kirim Pesan WhatsApp">
                                                <i class="fa-brands fa-whatsapp text-sm" aria-hidden="true"></i>
                                            </a>
                                            @else
                                            <span class="inline-flex items-center justify-center w-11 h-11 bg-slate-200 text-slate-600 rounded-full cursor-not-allowed" title="Nomor WhatsApp tidak tersedia">
                                                <i class="fa-brands fa-whatsapp text-sm" aria-hidden="true"></i>
                                            </span>
                                            @endif

                                            {{-- Edit Icon --}}
                                            <button type="button" @click="openEdit({{ json_encode($pd) }})" 
                                                    aria-label="Edit Data Calon Donatur"
                                                    class="inline-flex items-center justify-center w-9 h-9 bg-sky-50 hover:bg-sky-100 text-sky-700 border border-slate-300 rounded-lg shadow-sm transition-colors" 
                                                    title="Edit Data Calon Donatur">
                                                <i class="fa-solid fa-pen-to-square text-xs"></i>
                                            </button>
                                        </div>
                                    </td>

                                    <td class="py-3.5 px-4 font-bold text-slate-800">{{ Auth::user()->name }}</td>
                                    <td class="py-3.5 px-4 text-center font-bold text-slate-600">-</td>
                                    <td class="py-3.5 px-4 text-center font-mono text-slate-600">-</td>
                                    <td class="py-3.5 px-4 text-center font-mono text-slate-600">{{ $lastResp ? $lastResp->created_at->format('Y-m-d H:i:s') : '-' }}</td>
                                    <td class="py-3.5 px-4 font-bold text-slate-600">{{ $lastResp ? 'WhatsApp' : '-' }}</td>
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        <span class="inline-block whitespace-nowrap {{ $lastResp ? 'text-emerald-700' : 'text-amber-800' }} text-xs font-bold">
                                            {{ $lastResp ? $lastResp->status : 'Belum Diprospek' }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 text-right font-black text-slate-600">-</td>
                                    <td class="py-3.5 px-4 text-slate-700">-</td>
                                    <td class="py-3.5 px-4 text-slate-800">-</td>
                                    <td class="py-3.5 px-4 text-slate-900 font-medium">-</td>
                                    <td class="py-3.5 px-4 text-slate-600 italic max-w-xs truncate">{{ $lastResp ? $lastResp->response : '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="22" class="py-12 text-center text-slate-600 font-bold">Belum ada data calon donatur. Silakan klik + Add untuk menambah calon donatur baru.</td>
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

    {{-- Modal Add Calon Donatur --}}
    <div x-show="showAddModal" x-cloak @keydown.escape.window="showAddModal = false" role="dialog" aria-modal="true" aria-labelledby="add-potential-donor-title" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60" @click="showAddModal = false"></div>
        <div class="relative z-10 bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl space-y-5">
            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                <h2 id="add-potential-donor-title" class="text-base font-black text-slate-900 uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-user-plus text-emerald-700"></i> Tambah Data Calon Donatur
                </h2>
                <button type="button" @click="showAddModal = false" aria-label="Tutup modal tambah calon donatur" class="w-11 h-11 inline-flex items-center justify-center rounded-xl text-slate-600 hover:bg-slate-100 text-lg"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form action="{{ route('admin.potential_donors.store') }}" method="POST" class="space-y-4 text-xs font-semibold text-slate-700">
                @csrf
                <div class="space-y-1">
                    <label for="add_pd_name">Nama Lengkap / Mitra <span class="text-rose-700">*</span></label>
                    <input id="add_pd_name" type="text" name="name" required placeholder="Contoh: Nama Calon Donatur" class="w-full border border-slate-300 rounded-xl px-4 py-2.5 min-h-[44px] focus:border-emerald-700">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label for="add_pd_phone">No HP / WA</label>
                        <input id="add_pd_phone" type="text" name="phone" placeholder="Contoh: 08123456789" class="w-full border border-slate-300 rounded-xl px-4 py-2.5 min-h-[44px] focus:border-emerald-700">
                    </div>
                    <div class="space-y-1">
                        <label for="add_pd_email">Email</label>
                        <input id="add_pd_email" type="email" name="email" placeholder="Contoh: calon@email.com" class="w-full border border-slate-300 rounded-xl px-4 py-2.5 min-h-[44px] focus:border-emerald-700">
                    </div>
                </div>
                <div class="flex justify-end gap-3 pt-3">
                    <button type="button" @click="showAddModal = false" class="px-5 py-2.5 min-h-[44px] rounded-xl border border-slate-300 text-slate-700 hover:bg-slate-50 font-bold">Batal</button>
                    <button type="submit" class="px-5 py-2.5 min-h-[44px] rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold shadow-md">Simpan Calon Donatur</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Edit Calon Donatur --}}
    <div x-show="showEditModal" x-cloak @keydown.escape.window="showEditModal = false" role="dialog" aria-modal="true" aria-labelledby="edit-potential-donor-title" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60" @click="showEditModal = false"></div>
        <div class="relative z-10 bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl space-y-5">
            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                <h2 id="edit-potential-donor-title" class="text-base font-black text-slate-900 uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-pen-to-square text-emerald-700"></i> Edit Data Calon Donatur
                </h2>
                <button type="button" @click="showEditModal = false" aria-label="Tutup modal edit calon donatur" class="w-11 h-11 inline-flex items-center justify-center rounded-xl text-slate-600 hover:bg-slate-100 text-lg"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form :action="'{{ url('admin/potential-donors') }}/' + editData.id + '/update'" method="POST" class="space-y-4 text-xs font-semibold text-slate-700">
                @csrf
                @method('PUT')
                <div class="space-y-1">
                    <label for="edit_pd_name">Nama Lengkap / Mitra <span class="text-rose-700">*</span></label>
                    <input id="edit_pd_name" type="text" name="name" x-model="editData.name" required class="w-full border border-slate-300 rounded-xl px-4 py-2.5 min-h-[44px] focus:border-emerald-700">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label for="edit_pd_phone">No HP / WA</label>
                        <input id="edit_pd_phone" type="text" name="phone" x-model="editData.phone" class="w-full border border-slate-300 rounded-xl px-4 py-2.5 min-h-[44px] focus:border-emerald-700">
                    </div>
                    <div class="space-y-1">
                        <label for="edit_pd_email">Email</label>
                        <input id="edit_pd_email" type="email" name="email" x-model="editData.email" class="w-full border border-slate-300 rounded-xl px-4 py-2.5 min-h-[44px] focus:border-emerald-700">
                    </div>
                </div>
                <div class="flex justify-end gap-3 pt-3">
                    <button type="button" @click="showEditModal = false" class="px-5 py-2.5 min-h-[44px] rounded-xl border border-slate-300 text-slate-700 hover:bg-slate-50 font-bold">Batal</button>
                    <button type="submit" class="px-5 py-2.5 min-h-[44px] rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold shadow-md">Perbarui Data</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Delete Calon Donatur --}}
    <div x-show="showDeleteModal" x-cloak @keydown.escape.window="showDeleteModal = false" role="dialog" aria-modal="true" aria-labelledby="delete-potential-donor-title" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60" @click="showDeleteModal = false"></div>
        <div class="relative z-10 bg-white rounded-3xl max-w-sm w-full p-6 shadow-2xl space-y-4 text-center">
            <div class="w-14 h-14 bg-rose-50 text-rose-700 rounded-full flex items-center justify-center text-2xl mx-auto">
                <i class="fa-solid fa-trash-can"></i>
            </div>
            <div class="space-y-1">
                <h2 id="delete-potential-donor-title" class="text-base font-black text-slate-900">Konfirmasi Hapus</h2>
                <p class="text-xs text-slate-600 font-medium">Apakah Anda yakin ingin menghapus calon donatur <span class="font-bold text-slate-900" x-text="selectedName"></span>?</p>
            </div>
            <form :action="'{{ url('admin/potential-donors') }}/' + selectedId + '/delete'" method="POST" class="flex justify-center gap-3 pt-2">
                @csrf
                @method('DELETE')
                <button type="button" @click="showDeleteModal = false" class="px-5 py-2.5 min-h-[44px] rounded-xl border border-slate-300 text-slate-700 hover:bg-slate-50 font-bold text-xs">Batal</button>
                <button type="submit" class="px-5 py-2.5 min-h-[44px] rounded-xl bg-rose-700 hover:bg-rose-800 text-white font-bold text-xs shadow-md">Ya, Hapus Data</button>
            </form>
        </div>
    </div>

    {{-- Modal Set Donatur --}}
    <div x-show="showSetDonorModal" x-cloak @keydown.escape.window="showSetDonorModal = false" role="dialog" aria-modal="true" aria-labelledby="set-donor-title" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60" @click="showSetDonorModal = false"></div>
        <div class="relative z-10 bg-white rounded-3xl max-w-sm w-full p-6 shadow-2xl space-y-4 text-center">
            <div class="w-14 h-14 bg-emerald-50 text-emerald-700 rounded-full flex items-center justify-center text-2xl mx-auto">
                <i class="fa-solid fa-user-check"></i>
            </div>
            <div class="space-y-1">
                <h2 id="set-donor-title" class="text-base font-black text-slate-900">Ubah Menjadi Donatur Resmi</h2>
                <p class="text-xs text-slate-600 font-medium">Ubah status <span class="font-bold text-slate-900" x-text="selectedName"></span> menjadi Donatur Resmi?</p>
            </div>
            <form :action="'{{ url('admin/potential-donors') }}/' + selectedId + '/set-donor'" method="POST" class="flex justify-center gap-3 pt-2">
                @csrf
                <button type="button" @click="showSetDonorModal = false" class="px-5 py-2.5 min-h-[44px] rounded-xl border border-slate-300 text-slate-700 hover:bg-slate-50 font-bold text-xs">Batal</button>
                <button type="submit" class="px-5 py-2.5 min-h-[44px] rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs shadow-md">Ya, Set Donatur</button>
            </form>
        </div>
    </div>

    {{-- Modal Import CSV --}}
    <div x-show="showImportModal" x-cloak @keydown.escape.window="showImportModal = false" role="dialog" aria-modal="true" aria-labelledby="import-potential-donor-title" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60" @click="showImportModal = false"></div>
        <div class="relative z-10 bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl space-y-4">
            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                <h2 id="import-potential-donor-title" class="text-base font-black text-slate-900 uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-file-import text-emerald-700"></i> Import Data Calon Donatur (CSV)
                </h2>
                <button type="button" @click="showImportModal = false" aria-label="Tutup modal import calon donatur" class="w-11 h-11 inline-flex items-center justify-center rounded-xl text-slate-600 hover:bg-slate-100 text-lg"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form action="{{ route('admin.potential_donors.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs font-semibold text-slate-700">
                @csrf
                <div class="space-y-1">
                    <label for="import_pd_file">Pilih File Excel / CSV (.csv)</label>
                    <input id="import_pd_file" type="file" name="file" accept=".csv" required class="w-full border border-slate-300 rounded-xl px-4 py-2.5 min-h-[44px]">
                </div>
                <p class="text-xs text-slate-600 italic">Format kolom CSV: No, ID Calon Donatur, Nama Lengkap/Mitra, Tgl Insert, No HP, Email</p>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" @click="showImportModal = false" class="px-5 py-2.5 min-h-[44px] rounded-xl border border-slate-300 text-slate-700 hover:bg-slate-50 font-bold">Batal</button>
                    <button type="submit" class="px-5 py-2.5 min-h-[44px] rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold shadow-md">Upload & Import</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
