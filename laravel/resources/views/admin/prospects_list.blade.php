@extends('layouts.app')

@section('content')
<div class="container max-w-7xl mx-auto px-4 py-8" x-data="{
    showAdvanceSearch: true,
    showAddModal: false,
    showEditModal: false,
    showDeleteModal: false,
    showImportModal: false,
    selectedItem: null,
    selectedId: null,
    selectedName: '',
    editData: {
        id: null,
        name: '',
        phone: '',
        email: '',
        notes: ''
    },
    openEdit(item) {
        this.editData = {
            id: item.id,
            name: item.name ?? '',
            phone: item.phone ?? '',
            email: item.email ?? '',
            notes: item.notes ?? ''
        };
        this.showEditModal = true;
    },
    openDelete(id, name) {
        this.selectedId = id;
        this.selectedName = name;
        this.showDeleteModal = true;
    }
}">

    <div class="grid grid-cols-1 lg:grid-cols-[280px_1fr] gap-8 items-start min-w-0">

        {{-- Sidebar --}}
        <aside class="hidden lg:block space-y-5">
            @include('admin.sidebar', ['active' => 'prospects_list'])
        </aside>

        {{-- Main Content Column (Locked min-w-0 to prevent page-wide horizontal scrolling) --}}
        <div class="space-y-6 min-w-0 overflow-hidden">

            {{-- Top Tab Header Navigation --}}
            <div class="flex flex-wrap items-center gap-1 border-b border-slate-200 pb-0">
                <a href="{{ route('admin.donors_list') }}" class="px-5 py-2.5 min-h-[44px] rounded-t-xl text-xs font-bold bg-slate-100 text-slate-700 hover:bg-slate-200 transition-colors inline-flex items-center gap-2">
                    List Donatur
                </a>
                <a href="{{ route('admin.transactions_list') }}" class="px-5 py-2.5 min-h-[44px] rounded-t-xl text-xs font-bold bg-slate-100 text-slate-700 hover:bg-slate-200 transition-colors inline-flex items-center gap-2">
                    List Transaksi
                </a>
                <a href="{{ route('admin.prospects_list') }}" aria-current="page" class="px-5 py-2.5 min-h-[44px] rounded-t-xl text-xs font-black bg-emerald-700 text-white shadow-sm inline-flex items-center gap-2">
                    List Prospek
                </a>
            </div>

            {{-- Top Emerald Header Banner --}}
            <div class="bg-emerald-700 rounded-2xl px-6 py-3.5 text-white font-black text-sm uppercase tracking-wider shadow-sm flex flex-wrap items-center justify-between gap-2">
                <span class="flex items-center gap-2">
                    <i class="fa-solid fa-user-gear text-white"></i> List Prospek
                </span>
                <span class="text-xs text-emerald-100 font-normal">Transaction Management / List Prospek</span>
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

            {{-- Dashboard Summary Header Box --}}
            <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
                <div class="bg-emerald-700 text-white px-4 py-2 text-center text-xs font-black uppercase tracking-wider">
                    Informasi Transaksi
                </div>
                <div class="p-6 text-center text-sm font-bold text-slate-600">
                    @if(count($prospects) > 0)
                        Total Prospek Terdaftar: <span class="text-emerald-700 font-black text-lg">{{ $totalProspects }}</span> Data
                    @else
                        Data Tidak Ditemukan
                    @endif
                </div>
            </div>

            {{-- Filter Container & Search Bar (Presisi Form Screenshot User) --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm space-y-4">
                
                <form action="{{ route('admin.prospects_list') }}" method="GET" class="space-y-4">
                    
                    {{-- Row 1: Search Bar, Tgl Prospek, Target Prospek --}}
                    <div class="flex flex-wrap items-center gap-3">
                        <label for="pl_search" class="text-xs font-bold text-slate-700">Search :</label>
                        <input id="pl_search" type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Nama Lengkap/ID/No HP/Email" 
                               class="flex-1 min-h-[44px] border border-slate-300 rounded-xl px-4 py-2 text-xs font-medium focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700 min-w-[240px]">
                        
                        <label for="pl_start_date" class="text-xs font-bold text-slate-700">Tgl Prospek :</label>
                        <input id="pl_start_date" type="date" name="start_date" value="{{ request('start_date') }}" class="min-h-[44px] border border-slate-300 rounded-xl px-3 py-1.5 text-xs">
                        <span class="text-xs text-slate-600 font-semibold">s/d</span>
                        <input aria-label="Tanggal prospek akhir" type="date" name="end_date" value="{{ request('end_date') }}" class="min-h-[44px] border border-slate-300 rounded-xl px-3 py-1.5 text-xs">

                        <label for="pl_target" class="text-xs font-bold text-slate-700">Target Prospek :</label>
                        <select id="pl_target" name="target_prospect" class="min-h-[44px] border border-slate-300 rounded-xl px-3 py-1.5 text-xs">
                            <option value="All">All</option>
                        </select>

                        <button type="submit" class="min-h-[44px] bg-white hover:border-[#063f5c] text-slate-800 hover:text-[#063f5c] border border-slate-300 font-bold px-4 py-2 rounded-xl text-xs inline-flex items-center gap-1.5 transition-colors">
                            <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i> Search
                        </button>
                        
                        <button type="button" @click="showAdvanceSearch = !showAdvanceSearch" 
                                :aria-expanded="showAdvanceSearch"
                                class="min-h-[44px] bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-300 font-bold px-4 py-2 rounded-xl text-xs inline-flex items-center gap-1.5 transition-colors">
                            <i class="fa-solid fa-sliders" aria-hidden="true"></i> Advance Search
                        </button>
                    </div>

                    {{-- Advance Search Drawer (3 Rows of 3 Columns) --}}
                    <div x-show="showAdvanceSearch" x-collapse class="bg-slate-50 border border-slate-200 p-4 rounded-xl space-y-3 text-xs text-slate-700">
                        
                        {{-- Row 1 --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 items-center">
                            <div class="flex items-center gap-2">
                                <span class="w-28 text-xs font-semibold">Fundraiser</span> :
                                <select aria-label="Filter fundraiser" name="fundraiser" class="w-full min-h-[44px] bg-white border border-slate-300 rounded-lg px-2 py-1.5 text-xs">
                                    <option value="">Pilih Fundraiser</option>
                                </select>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-28 text-xs font-semibold">Media</span> :
                                <select aria-label="Filter media prospek" name="media_id" class="w-full min-h-[44px] bg-white border border-slate-300 rounded-lg px-2 py-1.5 text-xs">
                                    <option value="">Pilih Media</option>
                                    @foreach($prospectMedia as $pm)
                                        <option value="{{ $pm->id }}">{{ $pm->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-28 text-xs font-semibold">Metode</span> :
                                <select aria-label="Filter metode prospek" name="method_id" class="w-full min-h-[44px] bg-white border border-slate-300 rounded-lg px-2 py-1.5 text-xs">
                                    <option value="">Pilih Metode</option>
                                    @foreach($prospectMethods as $pmt)
                                        <option value="{{ $pmt->id }}">{{ $pmt->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Row 2 --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 items-center">
                            <div class="flex items-center gap-2">
                                <span class="w-28 text-xs font-semibold">Jenis</span> :
                                <select aria-label="Filter jenis prospek" name="type_id" class="w-full min-h-[44px] bg-white border border-slate-300 rounded-lg px-2 py-1.5 text-xs">
                                    <option value="">Pilih Jenis</option>
                                    @foreach($prospectTypes as $pt)
                                        <option value="{{ $pt->id }}">{{ $pt->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-28 text-xs font-semibold">Rumpun Program</span> :
                                <select aria-label="Filter rumpun program" name="program_group_id" class="w-full min-h-[44px] bg-white border border-slate-300 rounded-lg px-2 py-1.5 text-xs">
                                    <option value="">Pilih Rumpun Program</option>
                                    @foreach($programGroups as $pg)
                                        <option value="{{ $pg->id }}">{{ $pg->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-28 text-xs font-semibold">Campaign</span> :
                                <select aria-label="Filter campaign" name="program_id" class="w-full min-h-[44px] bg-white border border-slate-300 rounded-lg px-2 py-1.5 text-xs">
                                    <option value="">Pilih Campaign</option>
                                    @foreach($programs as $prg)
                                        <option value="{{ $prg->id }}">{{ $prg->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Row 3 --}}
                        {{-- Row 3 --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-3 items-center">
                            <div class="md:col-span-1 lg:col-span-3 flex items-center gap-2 min-w-0">
                                <span class="w-20 shrink-0 text-xs font-semibold whitespace-nowrap">Respon</span>
                                <span class="shrink-0 text-slate-600 font-bold">:</span>
                                <select aria-label="Filter respon prospek" name="response_id" class="flex-1 min-w-0 min-h-[44px] bg-white border border-slate-300 rounded-lg px-2 py-1.5 text-xs focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700">
                                    <option value="">Pilih Respon</option>
                                    @foreach($prospectResponses as $pr)
                                        <option value="{{ $pr->id }}" {{ request('response_id') == $pr->id ? 'selected' : '' }}>{{ $pr->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="md:col-span-2 lg:col-span-5 flex items-center gap-2 min-w-0">
                                <span class="w-24 shrink-0 text-xs font-semibold whitespace-nowrap">Potensi Donasi</span>
                                <span class="shrink-0 text-slate-600 font-bold">:</span>
                                <input aria-label="Potensi donasi minimum" type="number" name="min_potential" value="{{ request('min_potential') }}" placeholder="Min Rp" class="flex-1 min-w-0 min-h-[44px] bg-white border border-slate-300 rounded-lg px-2 py-1.5 text-xs focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700">
                                <span class="shrink-0 text-slate-600 font-medium text-xs">s/d</span>
                                <input aria-label="Potensi donasi maksimum" type="number" name="max_potential" value="{{ request('max_potential') }}" placeholder="Maks Rp" class="flex-1 min-w-0 min-h-[44px] bg-white border border-slate-300 rounded-lg px-2 py-1.5 text-xs focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700">
                            </div>
                            <div class="md:col-span-1 lg:col-span-4 flex items-center gap-2 min-w-0">
                                <span class="w-24 shrink-0 text-xs font-semibold whitespace-nowrap">Kantor Layanan</span>
                                <span class="shrink-0 text-slate-600 font-bold">:</span>
                                <select aria-label="Filter kantor layanan" name="office_id" class="flex-1 min-w-0 min-h-[44px] bg-white border border-slate-300 rounded-lg px-2 py-1.5 text-xs focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700">
                                    <option value="">Pilih Kantor Layanan</option>
                                    @foreach($offices as $ofc)
                                        <option value="{{ $ofc->id }}" {{ request('office_id') == $ofc->id ? 'selected' : '' }}>{{ $ofc->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>
                </form>

            </div>

            {{-- Action Toolbar: Add, Edit, Delete, Export, Import --}}
            <div class="flex flex-wrap items-center gap-2 pt-1 pb-1">
                <button type="button" @click="showAddModal = true" class="bg-emerald-700 hover:bg-emerald-800 text-white font-bold px-4 py-2.5 min-h-[44px] rounded-xl text-xs inline-flex items-center gap-1.5 shadow-sm transition-colors">
                    <i class="fa-solid fa-circle-plus"></i> Add
                </button>

                <button type="button" 
                        @click="if(selectedItem) { openEdit(selectedItem); } else { alert('Pilih data prospek dari tabel terlebih dahulu.'); }" 
                        class="bg-emerald-700 hover:bg-emerald-800 text-white font-bold px-4 py-2.5 min-h-[44px] rounded-xl text-xs inline-flex items-center gap-1.5 shadow-sm transition-colors">
                    <i class="fa-solid fa-pen-to-square"></i> Edit
                </button>

                <button type="button" 
                        @click="if(selectedId) { openDelete(selectedId, selectedName); } else { alert('Pilih data prospek dari tabel terlebih dahulu.'); }" 
                        class="bg-rose-700 hover:bg-rose-800 text-white font-bold px-4 py-2.5 min-h-[44px] rounded-xl text-xs inline-flex items-center gap-1.5 shadow-sm transition-colors">
                    <i class="fa-solid fa-trash-can"></i> Delete
                </button>

                <a href="{{ route('admin.prospects.export', request()->query()) }}" class="bg-emerald-700 hover:bg-emerald-800 text-white font-bold px-4 py-2.5 min-h-[44px] rounded-xl text-xs inline-flex items-center gap-1.5 shadow-sm transition-colors">
                    <i class="fa-solid fa-file-excel"></i> Export
                </a>

                <button type="button" @click="showImportModal = true" class="bg-emerald-700 hover:bg-emerald-800 text-white font-bold px-4 py-2.5 min-h-[44px] rounded-xl text-xs inline-flex items-center gap-1.5 shadow-sm transition-colors">
                    <i class="fa-solid fa-file-import"></i> Import
                </button>
            </div>

            {{-- Table Prospek Container --}}
            <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm w-full">
                
                {{-- Horizontal Scrollable Wrapper --}}
                <div class="w-full overflow-x-auto overflow-y-hidden scrollbar-thin scrollbar-thumb-emerald-700 scrollbar-track-slate-100">
                    <table class="w-full text-left border-collapse whitespace-nowrap min-w-[2200px]">
                        <thead>
                            <tr class="bg-emerald-700 text-white text-xs font-black uppercase tracking-wider">
                                <th class="py-3.5 px-4 text-center">No</th>
                                <th class="py-3.5 px-4">ID Prospek</th>
                                <th class="py-3.5 px-4">ID Donatur</th>
                                <th class="py-3.5 px-4 text-center">Tgl Prospek</th>
                                <th class="py-3.5 px-4">Nama Lengkap</th>
                                <th class="py-3.5 px-4">No HP</th>
                                <th class="py-3.5 px-4">Email</th>
                                <th class="py-3.5 px-4">Kantor Layanan</th>
                                <th class="py-3.5 px-4">Segmen</th>
                                <th class="py-3.5 px-4">Fundraiser</th>
                                <th class="py-3.5 px-4">Media</th>
                                <th class="py-3.5 px-4">Metode</th>
                                <th class="py-3.5 px-4">Jenis</th>
                                <th class="py-3.5 px-4">Rumpun Program</th>
                                <th class="py-3.5 px-4">Campaign</th>
                                <th class="py-3.5 px-4">Respon</th>
                                <th class="py-3.5 px-4">Keterangan</th>
                                <th class="py-3.5 px-4 text-right">Potensi Donasi</th>
                                <th class="py-3.5 px-4">PIC Prospek</th>
                            </tr>
                        </thead>
                        <tbody class="text-xs font-semibold text-slate-700 divide-y divide-slate-100">
                            @forelse($prospects as $index => $psp)
                                <tr @click="selectedItem = {{ json_encode($psp) }}; selectedId = {{ $psp->id }}; selectedName = '{{ addslashes($psp->name) }}'" 
                                    :class="selectedId === {{ $psp->id }} ? 'bg-emerald-50 border-l-4 border-emerald-600' : 'hover:bg-slate-50/80'"
                                    class="cursor-pointer transition-colors">
                                    <td class="py-3.5 px-4 text-center font-mono text-slate-600">{{ $prospects->firstItem() + $index }}</td>
                                    <td class="py-3.5 px-4 font-mono font-bold text-emerald-700">{{ $psp->id }}</td>
                                    <td class="py-3.5 px-4 font-mono text-slate-600">-</td>
                                    <td class="py-3.5 px-4 text-center font-mono text-slate-600">{{ $psp->created_at ? $psp->created_at->format('Y-m-d') : '-' }}</td>
                                    <td class="py-3.5 px-4 font-bold text-slate-900">{{ $psp->name ?: '-' }}</td>
                                    <td class="py-3.5 px-4 font-mono text-slate-600">{{ $psp->phone ?: '-' }}</td>
                                    <td class="py-3.5 px-4 text-slate-600">{{ $psp->email ?: '-' }}</td>
                                    <td class="py-3.5 px-4 text-slate-800">-</td>
                                    <td class="py-3.5 px-4 text-slate-600">-</td>
                                    <td class="py-3.5 px-4 text-slate-600">-</td>
                                    <td class="py-3.5 px-4 font-bold text-emerald-700">WhatsApp</td>
                                    <td class="py-3.5 px-4 text-slate-700">-</td>
                                    <td class="py-3.5 px-4 font-bold text-sky-700">-</td>
                                    <td class="py-3.5 px-4 text-slate-800">-</td>
                                    <td class="py-3.5 px-4 text-slate-900 font-medium">-</td>
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        @php
                                            $lastResp = $psp->responses->last();
                                        @endphp
                                        <span class="inline-block whitespace-nowrap {{ $lastResp ? 'text-emerald-700' : 'text-slate-600' }} text-xs font-bold">
                                            {{ $lastResp ? $lastResp->status : 'Belum Diprospek' }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 text-slate-600 italic max-w-xs truncate">{{ $lastResp ? $lastResp->response : '-' }}</td>
                                    <td class="py-3.5 px-4 text-right font-black text-emerald-700">-</td>
                                    <td class="py-3.5 px-4 font-bold text-slate-900">-</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="19" class="py-12 text-center text-slate-600 font-bold">Belum ada data prospek. Silakan klik + Add untuk menambah prospek baru.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($prospects->hasPages())
                    <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                        {{ $prospects->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>

    {{-- Modal Add Prospek --}}
    <div x-show="showAddModal" x-cloak @keydown.escape.window="showAddModal = false" role="dialog" aria-modal="true" aria-labelledby="add-prospect-title" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60" @click="showAddModal = false"></div>
        <div class="relative z-10 bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl space-y-5">
            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                <h2 id="add-prospect-title" class="text-base font-black text-slate-900 uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-circle-plus text-emerald-700"></i> Tambah Data Prospek Baru
                </h2>
                <button type="button" @click="showAddModal = false" aria-label="Tutup modal tambah prospek" class="w-11 h-11 inline-flex items-center justify-center rounded-xl text-slate-600 hover:bg-slate-100 text-lg"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form action="{{ route('admin.prospects.store') }}" method="POST" class="space-y-4 text-xs font-semibold text-slate-700">
                @csrf
                <div class="space-y-1">
                    <label for="add_psp_name">Nama Lengkap <span class="text-rose-700">*</span></label>
                    <input id="add_psp_name" type="text" name="name" required placeholder="Contoh: Nama Calon Donatur" class="w-full border border-slate-300 rounded-xl px-4 py-2.5 min-h-[44px] focus:border-emerald-700">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label for="add_psp_phone">No HP / WA</label>
                        <input id="add_psp_phone" type="text" name="phone" placeholder="Contoh: 08123456789" class="w-full border border-slate-300 rounded-xl px-4 py-2.5 min-h-[44px] focus:border-emerald-700">
                    </div>
                    <div class="space-y-1">
                        <label for="add_psp_email">Email Prospek</label>
                        <input id="add_psp_email" type="email" name="email" placeholder="Contoh: calon@email.com" class="w-full border border-slate-300 rounded-xl px-4 py-2.5 min-h-[44px] focus:border-emerald-700">
                    </div>
                </div>
                <div class="space-y-1">
                    <label for="add_psp_notes">Keterangan Follow Up</label>
                    <textarea id="add_psp_notes" name="notes" rows="2" placeholder="Follow up via WA..." class="w-full border border-slate-300 rounded-xl p-3 focus:border-emerald-700"></textarea>
                </div>
                <div class="flex justify-end gap-3 pt-3">
                    <button type="button" @click="showAddModal = false" class="px-5 py-2.5 min-h-[44px] rounded-xl border border-slate-300 text-slate-700 hover:bg-slate-50 font-bold">Batal</button>
                    <button type="submit" class="px-5 py-2.5 min-h-[44px] rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold shadow-md">Simpan Prospek</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Edit Prospek --}}
    <div x-show="showEditModal" x-cloak @keydown.escape.window="showEditModal = false" role="dialog" aria-modal="true" aria-labelledby="edit-prospect-title" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60" @click="showEditModal = false"></div>
        <div class="relative z-10 bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl space-y-5">
            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                <h2 id="edit-prospect-title" class="text-base font-black text-slate-900 uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-pen-to-square text-emerald-700"></i> Edit Data Prospek
                </h2>
                <button type="button" @click="showEditModal = false" aria-label="Tutup modal edit prospek" class="w-11 h-11 inline-flex items-center justify-center rounded-xl text-slate-600 hover:bg-slate-100 text-lg"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form :action="'{{ url('admin/prospects') }}/' + editData.id + '/update'" method="POST" class="space-y-4 text-xs font-semibold text-slate-700">
                @csrf
                @method('PUT')
                <div class="space-y-1">
                    <label for="edit_psp_name">Nama Lengkap <span class="text-rose-700">*</span></label>
                    <input id="edit_psp_name" type="text" name="name" x-model="editData.name" required class="w-full border border-slate-300 rounded-xl px-4 py-2.5 min-h-[44px] focus:border-emerald-700">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label for="edit_psp_phone">No HP / WA</label>
                        <input id="edit_psp_phone" type="text" name="phone" x-model="editData.phone" class="w-full border border-slate-300 rounded-xl px-4 py-2.5 min-h-[44px] focus:border-emerald-700">
                    </div>
                    <div class="space-y-1">
                        <label for="edit_psp_email">Email Prospek</label>
                        <input id="edit_psp_email" type="email" name="email" x-model="editData.email" class="w-full border border-slate-300 rounded-xl px-4 py-2.5 min-h-[44px] focus:border-emerald-700">
                    </div>
                </div>
                <div class="space-y-1">
                    <label for="edit_psp_notes">Keterangan Follow Up</label>
                    <textarea id="edit_psp_notes" name="notes" x-model="editData.notes" rows="2" class="w-full border border-slate-300 rounded-xl p-3 focus:border-emerald-700"></textarea>
                </div>
                <div class="flex justify-end gap-3 pt-3">
                    <button type="button" @click="showEditModal = false" class="px-5 py-2.5 min-h-[44px] rounded-xl border border-slate-300 text-slate-700 hover:bg-slate-50 font-bold">Batal</button>
                    <button type="submit" class="px-5 py-2.5 min-h-[44px] rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold shadow-md">Perbarui Prospek</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Delete Prospek --}}
    <div x-show="showDeleteModal" x-cloak @keydown.escape.window="showDeleteModal = false" role="dialog" aria-modal="true" aria-labelledby="delete-prospect-title" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60" @click="showDeleteModal = false"></div>
        <div class="relative z-10 bg-white rounded-3xl max-w-sm w-full p-6 shadow-2xl space-y-4 text-center">
            <div class="w-14 h-14 bg-rose-50 text-rose-700 rounded-full flex items-center justify-center text-2xl mx-auto">
                <i class="fa-solid fa-trash-can"></i>
            </div>
            <div class="space-y-1">
                <h2 id="delete-prospect-title" class="text-base font-black text-slate-900">Konfirmasi Hapus</h2>
                <p class="text-xs text-slate-600 font-medium">Apakah Anda yakin ingin menghapus data prospek <span class="font-bold text-slate-900" x-text="selectedName"></span>?</p>
            </div>
            <form :action="'{{ url('admin/prospects') }}/' + selectedId + '/delete'" method="POST" class="flex justify-center gap-3 pt-2">
                @csrf
                @method('DELETE')
                <button type="button" @click="showDeleteModal = false" class="px-5 py-2.5 min-h-[44px] rounded-xl border border-slate-300 text-slate-700 hover:bg-slate-50 font-bold text-xs">Batal</button>
                <button type="submit" class="px-5 py-2.5 min-h-[44px] rounded-xl bg-rose-700 hover:bg-rose-800 text-white font-bold text-xs shadow-md">Ya, Hapus Data</button>
            </form>
        </div>
    </div>

    {{-- Modal Import CSV --}}
    <div x-show="showImportModal" x-cloak @keydown.escape.window="showImportModal = false" role="dialog" aria-modal="true" aria-labelledby="import-prospect-title" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60" @click="showImportModal = false"></div>
        <div class="relative z-10 bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl space-y-4">
            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                <h2 id="import-prospect-title" class="text-base font-black text-slate-900 uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-file-import text-emerald-700"></i> Import Data Prospek (CSV)
                </h2>
                <button type="button" @click="showImportModal = false" aria-label="Tutup modal import prospek" class="w-11 h-11 inline-flex items-center justify-center rounded-xl text-slate-600 hover:bg-slate-100 text-lg"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form action="{{ route('admin.prospects.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs font-semibold text-slate-700">
                @csrf
                <div class="space-y-1">
                    <label for="import_psp_file">Pilih File Excel / CSV (.csv)</label>
                    <input id="import_psp_file" type="file" name="file" accept=".csv" required class="w-full border border-slate-300 rounded-xl px-4 py-2.5 min-h-[44px]">
                </div>
                <p class="text-xs text-slate-600 italic">Format kolom CSV: No, ID Prospek, ID Donatur, Tgl Prospek, Nama Lengkap, No HP, Email</p>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" @click="showImportModal = false" class="px-5 py-2.5 min-h-[44px] rounded-xl border border-slate-300 text-slate-700 hover:bg-slate-50 font-bold">Batal</button>
                    <button type="submit" class="px-5 py-2.5 min-h-[44px] rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold shadow-md">Upload & Import</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
