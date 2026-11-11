@extends('layouts.app')

@section('content')
<div class="container max-w-7xl mx-auto px-4 py-8" x-data="{
    showAdvanceSearch: true,
    showAddModal: false,
    showEditModal: false,
    showDeleteModal: false,
    showImportModal: false,
    showDetailModal: false,
    selectedItem: null,
    selectedId: null,
    selectedName: '',
    editData: {
        id: null,
        name: '',
        email: '',
        phone: '',
        gender: 'L',
        office_id: '',
        segment: '',
        address: ''
    },
    openEdit(item) {
        this.editData = {
            id: item.id,
            name: item.name ?? '',
            email: item.email ?? '',
            phone: item.phone ?? '',
            gender: item.gender ?? 'L',
            office_id: item.office_id ?? '',
            segment: item.segment ?? '',
            address: item.address ?? ''
        };
        this.showEditModal = true;
    },
    openDelete(id, name) {
        this.selectedId = id;
        this.selectedName = name;
        this.showDeleteModal = true;
    },
    openDetail(item) {
        this.selectedItem = item;
        this.showDetailModal = true;
    }
}">

    <div class="grid grid-cols-1 lg:grid-cols-[280px_1fr] gap-8 items-start min-w-0">

        {{-- Sidebar --}}
        <aside class="hidden lg:block space-y-5">
            @include('admin.sidebar', ['active' => 'donors_list'])
        </aside>

        {{-- Main Content Column (TerKunci min-w-0 agar tidak ikut ter-scroll ke samping) --}}
        <div class="space-y-6 min-w-0 overflow-hidden">

            {{-- Top Emerald Header Banner --}}
            <div class="bg-emerald-700 rounded-2xl px-6 py-3.5 text-white font-black text-sm uppercase tracking-wider shadow-sm flex flex-wrap items-center justify-between gap-2">
                <span class="flex items-center gap-2">
                    <i class="fa-solid fa-users text-white"></i> List Donatur
                </span>
                <span class="text-xs text-emerald-100 font-normal">Transaction Management / List Donatur</span>
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
            <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm space-y-4">
                
                <form action="{{ route('admin.donors_list') }}" method="GET" class="space-y-4">
                    
                    {{-- Row 1: Search Bar & Toggle Advance Search --}}
                    <div class="flex flex-wrap items-center gap-3">
                        <label for="donors_search" class="text-xs font-bold text-slate-700">Search :</label>
                        <input id="donors_search" type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Nama Lengkap/ID/No HP/Email" 
                               class="flex-1 border border-slate-300 rounded-xl px-4 py-2.5 min-h-[44px] text-xs font-medium focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700 min-w-[240px]">
                        
                        <button type="submit" class="bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-300 font-bold px-4 py-2.5 min-h-[44px] rounded-xl text-xs inline-flex items-center gap-1.5 transition-colors">
                            <i class="fa-solid fa-magnifying-glass"></i> Search
                        </button>
                        
                        <button type="button" @click="showAdvanceSearch = !showAdvanceSearch" 
                                :aria-expanded="showAdvanceSearch"
                                class="min-h-[44px] bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-300 font-bold px-4 py-2 rounded-xl text-xs inline-flex items-center gap-1.5 transition-colors">
                            <i class="fa-solid fa-sliders" aria-hidden="true"></i> Advance Search
                        </button>
                    </div>

                    {{-- Advance Search Drawer (Form Filter Lengkap - Fixed Responsive Grid) --}}
                    <div x-show="showAdvanceSearch" x-collapse class="bg-slate-50 border border-slate-200 p-4 rounded-xl space-y-3 text-xs text-slate-700 shadow-inner">
                        
                        {{-- Row 1: Tgl Registrasi & Jenis Kelamin --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-3 items-center">
                            <div class="md:col-span-1 lg:col-span-7 flex items-center gap-2 min-w-0">
                                <span class="w-28 shrink-0 text-xs font-semibold whitespace-nowrap">Tgl Registrasi</span>
                                <span class="shrink-0 text-slate-600 font-bold">:</span>
                                <input aria-label="Tanggal registrasi mulai" type="date" name="start_reg_date" value="{{ request('start_reg_date') }}" class="flex-1 min-w-0 min-h-[44px] bg-white border border-slate-300 rounded-lg px-2 py-1.5 text-xs focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700">
                                <span class="shrink-0 text-slate-600 font-medium text-xs">s/d</span>
                                <input aria-label="Tanggal registrasi akhir" type="date" name="end_reg_date" value="{{ request('end_reg_date') }}" class="flex-1 min-w-0 min-h-[44px] bg-white border border-slate-300 rounded-lg px-2 py-1.5 text-xs focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700">
                            </div>
                            <div class="md:col-span-1 lg:col-span-5 flex items-center gap-2 min-w-0">
                                <span class="w-28 shrink-0 text-xs font-semibold whitespace-nowrap">Jenis Kelamin</span>
                                <span class="shrink-0 text-slate-600 font-bold">:</span>
                                <select aria-label="Filter jenis kelamin" name="gender" class="flex-1 min-w-0 min-h-[44px] bg-white border border-slate-300 rounded-lg px-2 py-1.5 text-xs focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700">
                                    <option value="All">All</option>
                                    <option value="L" {{ request('gender') === 'L' ? 'selected' : '' }}>Laki-Laki (L)</option>
                                    <option value="P" {{ request('gender') === 'P' ? 'selected' : '' }}>Perempuan (P)</option>
                                </select>
                            </div>
                        </div>

                        {{-- Row 2: Sudah Donasi & Segmen --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-3 items-center">
                            <div class="md:col-span-1 lg:col-span-7 flex items-center gap-2 min-w-0">
                                <span class="w-28 shrink-0 text-xs font-semibold whitespace-nowrap">Sudah Donasi</span>
                                <span class="shrink-0 text-slate-600 font-bold">:</span>
                                <input aria-label="Tanggal donasi mulai" type="date" name="start_donation_date" value="{{ request('start_donation_date') }}" class="flex-1 min-w-0 min-h-[44px] bg-white border border-slate-300 rounded-lg px-2 py-1.5 text-xs focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700">
                                <span class="shrink-0 text-slate-600 font-medium text-xs">s/d</span>
                                <input aria-label="Tanggal donasi akhir" type="date" name="end_donation_date" value="{{ request('end_donation_date') }}" class="flex-1 min-w-0 min-h-[44px] bg-white border border-slate-300 rounded-lg px-2 py-1.5 text-xs focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700">
                            </div>
                            <div class="md:col-span-1 lg:col-span-5 flex items-center gap-2 min-w-0">
                                <span class="w-28 shrink-0 text-xs font-semibold whitespace-nowrap">Segmen</span>
                                <span class="shrink-0 text-slate-600 font-bold">:</span>
                                <input aria-label="Filter segmen" type="text" name="segment" value="{{ request('segment') }}" placeholder="Segmen" class="flex-1 min-w-0 min-h-[44px] bg-white border border-slate-300 rounded-lg px-2 py-1.5 text-xs focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700">
                            </div>
                        </div>

                        {{-- Row 3: Sudah Diprospek & Pernah Donasi --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-3 items-center">
                            <div class="md:col-span-1 lg:col-span-7 flex items-center gap-2 min-w-0">
                                <span class="w-28 shrink-0 text-xs font-semibold whitespace-nowrap">Sudah Diprospek</span>
                                <span class="shrink-0 text-slate-600 font-bold">:</span>
                                <input aria-label="Tanggal prospek mulai" type="date" name="start_prospect_date" value="{{ request('start_prospect_date') }}" class="flex-1 min-w-0 min-h-[44px] bg-white border border-slate-300 rounded-lg px-2 py-1.5 text-xs focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700">
                                <span class="shrink-0 text-slate-600 font-medium text-xs">s/d</span>
                                <input aria-label="Tanggal prospek akhir" type="date" name="end_prospect_date" value="{{ request('end_prospect_date') }}" class="flex-1 min-w-0 min-h-[44px] bg-white border border-slate-300 rounded-lg px-2 py-1.5 text-xs focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700">
                            </div>
                            <div class="md:col-span-1 lg:col-span-5 flex items-center gap-2 min-w-0">
                                <span class="w-28 shrink-0 text-xs font-semibold whitespace-nowrap">Pernah Donasi</span>
                                <span class="shrink-0 text-slate-600 font-bold">:</span>
                                <select aria-label="Filter pernah donasi" name="ever_donated" class="flex-1 min-w-0 min-h-[44px] bg-white border border-slate-300 rounded-lg px-2 py-1.5 text-xs focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700">
                                    <option value="All">All</option>
                                    <option value="Ya">Ya</option>
                                    <option value="Tidak">Tidak</option>
                                </select>
                            </div>
                        </div>

                        {{-- Row 4: Profil, Milad, Pernah Diprospek --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-12 gap-3 items-center">
                            <div class="lg:col-span-4 flex items-center gap-2 min-w-0">
                                <span class="w-28 shrink-0 text-xs font-semibold whitespace-nowrap">Profil</span>
                                <span class="shrink-0 text-slate-600 font-bold">:</span>
                                <input aria-label="Filter profil" type="text" name="profile" value="{{ request('profile') }}" placeholder="Profil" class="flex-1 min-w-0 min-h-[44px] bg-white border border-slate-300 rounded-lg px-2 py-1.5 text-xs focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700">
                            </div>
                            <div class="lg:col-span-4 flex items-center gap-2 min-w-0">
                                <span class="w-28 shrink-0 text-xs font-semibold whitespace-nowrap">Milad</span>
                                <span class="shrink-0 text-slate-600 font-bold">:</span>
                                <select aria-label="Tanggal milad" name="milad_date" class="w-20 shrink-0 min-h-[44px] bg-white border border-slate-300 rounded-lg px-2 py-1.5 text-xs focus:border-emerald-700">
                                    <option value="">Tgl</option>
                                    @for($i=1; $i<=31; $i++) <option value="{{ $i }}">{{ $i }}</option> @endfor
                                </select>
                                <select aria-label="Bulan milad" name="milad_month" class="flex-1 min-w-0 min-h-[44px] bg-white border border-slate-300 rounded-lg px-2 py-1.5 text-xs focus:border-emerald-700">
                                    <option value="">Bln</option>
                                    @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $mIdx => $mName)
                                        <option value="{{ $mIdx+1 }}">{{ $mName }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="lg:col-span-4 flex items-center gap-2 min-w-0">
                                <span class="w-28 shrink-0 text-xs font-semibold whitespace-nowrap">Pernah Diprospek</span>
                                <span class="shrink-0 text-slate-600 font-bold">:</span>
                                <select aria-label="Filter pernah diprospek" name="ever_prospected" class="flex-1 min-w-0 min-h-[44px] bg-white border border-slate-300 rounded-lg px-2 py-1.5 text-xs focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700">
                                    <option value="All">All</option>
                                    <option value="Ya">Ya</option>
                                    <option value="Tidak">Tidak</option>
                                </select>
                            </div>
                        </div>

                        {{-- Row 5: Last Nominal, Last Program, Last Respon --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-3 items-center">
                            <div class="md:col-span-2 lg:col-span-5 flex items-center gap-2 min-w-0">
                                <span class="w-28 shrink-0 text-xs font-semibold whitespace-nowrap">Last Nominal</span>
                                <span class="shrink-0 text-slate-600 font-bold">:</span>
                                <input aria-label="Nominal minimum" type="number" name="min_nominal" value="{{ request('min_nominal') }}" placeholder="Min Rp" class="flex-1 min-w-0 min-h-[44px] bg-white border border-slate-300 rounded-lg px-2 py-1.5 text-xs focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700">
                                <span class="shrink-0 text-slate-600 font-medium text-xs">s/d</span>
                                <input aria-label="Nominal maksimum" type="number" name="max_nominal" value="{{ request('max_nominal') }}" placeholder="Maks Rp" class="flex-1 min-w-0 min-h-[44px] bg-white border border-slate-300 rounded-lg px-2 py-1.5 text-xs focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700">
                            </div>
                            <div class="md:col-span-1 lg:col-span-4 flex items-center gap-2 min-w-0">
                                <span class="w-24 shrink-0 text-xs font-semibold whitespace-nowrap">Last Program</span>
                                <span class="shrink-0 text-slate-600 font-bold">:</span>
                                <select aria-label="Filter program terakhir" name="last_program" class="flex-1 min-w-0 min-h-[44px] bg-white border border-slate-300 rounded-lg px-2 py-1.5 text-xs focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700">
                                    <option value="">Last Program Donasi</option>
                                </select>
                            </div>
                            <div class="md:col-span-1 lg:col-span-3 flex items-center gap-2 min-w-0">
                                <span class="w-20 shrink-0 text-xs font-semibold whitespace-nowrap">Last Respon</span>
                                <span class="shrink-0 text-slate-600 font-bold">:</span>
                                <select aria-label="Filter respon terakhir" name="last_response" class="flex-1 min-w-0 min-h-[44px] bg-white border border-slate-300 rounded-lg px-2 py-1.5 text-xs focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700">
                                    <option value="">Last Respon</option>
                                </select>
                            </div>
                        </div>

                        {{-- Row 6: Last Waktu Donasi, Last Via Bayar, Last Media Prospek --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-3 items-center">
                            <div class="md:col-span-2 lg:col-span-5 flex items-center gap-2 min-w-0">
                                <span class="w-28 shrink-0 text-xs font-semibold whitespace-nowrap">Last Waktu Donasi</span>
                                <span class="shrink-0 text-slate-600 font-bold">:</span>
                                <input aria-label="Jam donasi mulai" type="time" name="start_time" value="{{ request('start_time') }}" class="flex-1 min-w-0 min-h-[44px] bg-white border border-slate-300 rounded-lg px-2 py-1.5 text-xs focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700">
                                <span class="shrink-0 text-slate-600 font-medium text-xs">s/d</span>
                                <input aria-label="Jam donasi akhir" type="time" name="end_time" value="{{ request('end_time') }}" class="flex-1 min-w-0 min-h-[44px] bg-white border border-slate-300 rounded-lg px-2 py-1.5 text-xs focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700">
                            </div>
                            <div class="md:col-span-1 lg:col-span-4 flex items-center gap-2 min-w-0">
                                <span class="w-24 shrink-0 text-xs font-semibold whitespace-nowrap">Last Via Bayar</span>
                                <span class="shrink-0 text-slate-600 font-bold">:</span>
                                <select aria-label="Filter via bayar terakhir" name="last_via_bayar" class="flex-1 min-w-0 min-h-[44px] bg-white border border-slate-300 rounded-lg px-2 py-1.5 text-xs focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700">
                                    <option value="">Last Via Bayar</option>
                                </select>
                            </div>
                            <div class="md:col-span-1 lg:col-span-3 flex items-center gap-2 min-w-0">
                                <span class="w-20 shrink-0 text-xs font-semibold whitespace-nowrap">Last Media</span>
                                <span class="shrink-0 text-slate-600 font-bold">:</span>
                                <select aria-label="Filter media prospek terakhir" name="last_prospect_media" class="flex-1 min-w-0 min-h-[44px] bg-white border border-slate-300 rounded-lg px-2 py-1.5 text-xs focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700">
                                    <option value="">Last Media</option>
                                    @foreach($prospectMedia as $pm)
                                        <option value="{{ $pm->id }}">{{ $pm->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Row 7: Ada No HP, Ada Email, Sumber Informasi --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-12 gap-3 items-center">
                            <div class="lg:col-span-4 flex items-center gap-2 min-w-0">
                                <span class="w-28 shrink-0 text-xs font-semibold whitespace-nowrap">Ada No HP</span>
                                <span class="shrink-0 text-slate-600 font-bold">:</span>
                                <select aria-label="Filter kepemilikan nomor HP" name="has_phone" class="flex-1 min-w-0 min-h-[44px] bg-white border border-slate-300 rounded-lg px-2 py-1.5 text-xs focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700">
                                    <option value="All">All</option>
                                    <option value="Ya">Ya</option>
                                    <option value="Tidak">Tidak</option>
                                </select>
                            </div>
                            <div class="lg:col-span-4 flex items-center gap-2 min-w-0">
                                <span class="w-28 shrink-0 text-xs font-semibold whitespace-nowrap">Ada Email</span>
                                <span class="shrink-0 text-slate-600 font-bold">:</span>
                                <select aria-label="Filter kepemilikan email" name="has_email" class="flex-1 min-w-0 min-h-[44px] bg-white border border-slate-300 rounded-lg px-2 py-1.5 text-xs focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700">
                                    <option value="All">All</option>
                                    <option value="Ya">Ya</option>
                                    <option value="Tidak">Tidak</option>
                                </select>
                            </div>
                            <div class="lg:col-span-4 flex items-center gap-2 min-w-0">
                                <span class="w-28 shrink-0 text-xs font-semibold whitespace-nowrap">Sumber Info</span>
                                <span class="shrink-0 text-slate-600 font-bold">:</span>
                                <input aria-label="Filter sumber info" type="text" name="source_info" value="{{ request('source_info') }}" placeholder="Sumber Informasi" class="flex-1 min-w-0 min-h-[44px] bg-white border border-slate-300 rounded-lg px-2 py-1.5 text-xs focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700">
                            </div>
                        </div>

                        {{-- Row 8: PIC Prospek, Kota / Kab, Aktif --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-12 gap-3 items-center">
                            <div class="lg:col-span-4 flex items-center gap-2 min-w-0">
                                <span class="w-28 shrink-0 text-xs font-semibold whitespace-nowrap">PIC Prospek</span>
                                <span class="shrink-0 text-slate-600 font-bold">:</span>
                                <select aria-label="Filter PIC prospek" name="pic_prospect" class="flex-1 min-w-0 min-h-[44px] bg-white border border-slate-300 rounded-lg px-2 py-1.5 text-xs focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700">
                                    <option value="">PIC Prospek</option>
                                </select>
                            </div>
                            <div class="lg:col-span-4 flex items-center gap-2 min-w-0">
                                <span class="w-28 shrink-0 text-xs font-semibold whitespace-nowrap">Kota / Kab</span>
                                <span class="shrink-0 text-slate-600 font-bold">:</span>
                                <input aria-label="Filter kota atau kabupaten" type="text" name="city" value="{{ request('city') }}" placeholder="Kota / Kab" class="flex-1 min-w-0 min-h-[44px] bg-white border border-slate-300 rounded-lg px-2 py-1.5 text-xs focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700">
                            </div>
                            <div class="lg:col-span-4 flex items-center gap-2 min-w-0">
                                <span class="w-28 shrink-0 text-xs font-semibold whitespace-nowrap">Aktif</span>
                                <span class="shrink-0 text-slate-600 font-bold">:</span>
                                <select aria-label="Filter status aktif" name="is_active" class="flex-1 min-w-0 min-h-[44px] bg-white border border-slate-300 rounded-lg px-2 py-1.5 text-xs focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700">
                                    <option value="Ya">Ya</option>
                                    <option value="Tidak">Tidak</option>
                                </select>
                            </div>
                        </div>

                        {{-- Row 9: Kantor Layanan, Kategori, Badge --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-12 gap-3 items-center">
                            <div class="lg:col-span-4 flex items-center gap-2 min-w-0">
                                <span class="w-28 shrink-0 text-xs font-semibold whitespace-nowrap">Kantor Layanan</span>
                                <span class="shrink-0 text-slate-600 font-bold">:</span>
                                <select aria-label="Filter kantor layanan" name="office_id" class="flex-1 min-w-0 min-h-[44px] bg-white border border-slate-300 rounded-lg px-2 py-1.5 text-xs focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700">
                                    <option value="">Pilih Kantor Layanan</option>
                                    @foreach($offices as $ofc)
                                        <option value="{{ $ofc->id }}">{{ $ofc->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="lg:col-span-4 flex items-center gap-2 min-w-0">
                                <span class="w-28 shrink-0 text-xs font-semibold whitespace-nowrap">Kategori</span>
                                <span class="shrink-0 text-slate-600 font-bold">:</span>
                                <select aria-label="Filter kategori" name="category" class="flex-1 min-w-0 min-h-[44px] bg-white border border-slate-300 rounded-lg px-2 py-1.5 text-xs focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700">
                                    <option value="All">All</option>
                                </select>
                            </div>
                            <div class="lg:col-span-4 flex items-center gap-2 min-w-0">
                                <span class="w-28 shrink-0 text-xs font-semibold whitespace-nowrap">Badge</span>
                                <span class="shrink-0 text-slate-600 font-bold">:</span>
                                <select aria-label="Filter badge" name="badge" class="flex-1 min-w-0 min-h-[44px] bg-white border border-slate-300 rounded-lg px-2 py-1.5 text-xs focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700">
                                    <option value="All">All</option>
                                </select>
                            </div>
                        </div>

                        {{-- Row 10: Tgl Insert (Range), RFM Last 90 Days, RFM All Time --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-3 items-center">
                            <div class="md:col-span-2 lg:col-span-6 flex items-center gap-2 min-w-0">
                                <span class="w-28 shrink-0 text-xs font-semibold whitespace-nowrap">Tgl Insert</span>
                                <span class="shrink-0 text-slate-600 font-bold">:</span>
                                <input aria-label="Tanggal insert mulai" type="date" name="start_insert_date" value="{{ request('start_insert_date') }}" class="flex-1 min-w-0 min-h-[44px] bg-white border border-slate-300 rounded-lg px-2 py-1.5 text-xs focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700">
                                <span class="shrink-0 text-slate-600 font-medium text-xs">s/d</span>
                                <input aria-label="Tanggal insert akhir" type="date" name="end_insert_date" value="{{ request('end_insert_date') }}" class="flex-1 min-w-0 min-h-[44px] bg-white border border-slate-300 rounded-lg px-2 py-1.5 text-xs focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700">
                            </div>
                            <div class="md:col-span-1 lg:col-span-3 flex items-center gap-2 min-w-0">
                                <span class="w-20 shrink-0 text-xs font-semibold whitespace-nowrap">RFM 90D</span>
                                <span class="shrink-0 text-slate-600 font-bold">:</span>
                                <select aria-label="Filter RFM 90 hari" name="rfm_last_90" class="flex-1 min-w-0 min-h-[44px] bg-white border border-slate-300 rounded-lg px-2 py-1.5 text-xs focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700">
                                    <option value="All">All</option>
                                    <option value="Champions">Champions</option>
                                    <option value="Loyal Donors">Loyal Donors</option>
                                    <option value="Potential Loyalist">Potential Loyalist</option>
                                    <option value="Recent Donors">Recent Donors</option>
                                    <option value="At Risk">At Risk</option>
                                    <option value="Hibernating">Hibernating</option>
                                    <option value="Lost">Lost</option>
                                </select>
                            </div>
                            <div class="md:col-span-1 lg:col-span-3 flex items-center gap-2 min-w-0">
                                <span class="w-20 shrink-0 text-xs font-semibold whitespace-nowrap">RFM All</span>
                                <span class="shrink-0 text-slate-600 font-bold">:</span>
                                <select aria-label="Filter RFM sepanjang waktu" name="rfm_alltime" class="flex-1 min-w-0 min-h-[44px] bg-white border border-slate-300 rounded-lg px-2 py-1.5 text-xs focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700">
                                    <option value="All">All</option>
                                    <option value="Champions">Champions</option>
                                    <option value="Loyal Donors">Loyal Donors</option>
                                    <option value="Potential Loyalist">Potential Loyalist</option>
                                    <option value="Recent Donors">Recent Donors</option>
                                    <option value="At Risk">At Risk</option>
                                    <option value="Hibernating">Hibernating</option>
                                    <option value="Lost">Lost</option>
                                </select>
                            </div>
                        </div>

                    </div>
                </form>

            </div>

            {{-- Action Toolbar: Add, Edit, Delete, Export, Import, Detail Data --}}
            <div class="flex flex-wrap items-center gap-2 pt-1 pb-1">
                <button type="button" @click="showAddModal = true" class="bg-emerald-700 hover:bg-emerald-800 text-white font-bold px-4 py-2.5 min-h-[44px] rounded-xl text-xs inline-flex items-center gap-1.5 shadow-sm transition-colors">
                    <i class="fa-solid fa-circle-plus"></i> Add
                </button>

                <button type="button" 
                        @click="if(selectedItem) { openEdit(selectedItem); } else { alert('Pilih donatur dari tabel terlebih dahulu.'); }" 
                        class="bg-emerald-700 hover:bg-emerald-800 text-white font-bold px-4 py-2.5 min-h-[44px] rounded-xl text-xs inline-flex items-center gap-1.5 shadow-sm transition-colors">
                    <i class="fa-solid fa-pen-to-square"></i> Edit
                </button>

                <button type="button" 
                        @click="if(selectedId) { openDelete(selectedId, selectedName); } else { alert('Pilih donatur dari tabel terlebih dahulu.'); }" 
                        class="bg-rose-700 hover:bg-rose-800 text-white font-bold px-4 py-2.5 min-h-[44px] rounded-xl text-xs inline-flex items-center gap-1.5 shadow-sm transition-colors">
                    <i class="fa-solid fa-trash-can"></i> Delete
                </button>

                <a href="{{ route('admin.donors.export', request()->query()) }}" class="bg-emerald-700 hover:bg-emerald-800 text-white font-bold px-4 py-2.5 min-h-[44px] rounded-xl text-xs inline-flex items-center gap-1.5 shadow-sm transition-colors">
                    <i class="fa-solid fa-file-excel"></i> Export
                </a>

                <a href="{{ route('admin.donors.batch_receipt', request()->query()) }}" target="_blank" 
                   class="bg-[#063f5c] hover:bg-primary-700 text-white font-bold px-4 py-2.5 min-h-[44px] rounded-xl text-xs inline-flex items-center gap-1.5 shadow-sm transition-colors"
                   title="Download atau Cetak Semua Kwitansi Donatur Hasil Filter (atau Keseluruhan)">
                    <i class="fa-solid fa-file-invoice" aria-hidden="true"></i> Download / Cetak Kwitansi Terfilter
                </a>

                <button type="button" @click="showImportModal = true" class="bg-emerald-700 hover:bg-emerald-800 text-white font-bold px-4 py-2.5 min-h-[44px] rounded-xl text-xs inline-flex items-center gap-1.5 shadow-sm transition-colors">
                    <i class="fa-solid fa-file-import"></i> Import
                </button>

                <button type="button" 
                        @click="if(selectedItem) { openDetail(selectedItem); } else { alert('Pilih donatur dari tabel terlebih dahulu.'); }" 
                        class="bg-sky-700 hover:bg-sky-800 text-white font-bold px-4 py-2.5 min-h-[44px] rounded-xl text-xs inline-flex items-center gap-1.5 shadow-sm transition-colors">
                    <i class="fa-solid fa-circle-info"></i> Detail Data
                </button>
            </div>

            {{-- Table Donatur Container --}}
            <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm w-full">
                
                {{-- Horizontal Scrollable Wrapper --}}
                <div class="w-full overflow-x-auto overflow-y-hidden scrollbar-thin scrollbar-thumb-emerald-700 scrollbar-track-slate-100">
                    <table class="w-full text-left border-collapse whitespace-nowrap min-w-[2400px]">
                        <thead>
                            <tr class="bg-emerald-700 text-white text-xs font-black uppercase tracking-wider">
                                <th class="py-3.5 px-4 text-center">No</th>
                                <th class="py-3.5 px-4">ID Donatur</th>
                                <th class="py-3.5 px-4">Nama Lengkap</th>
                                <th class="py-3.5 px-4 text-center">Jenis Kelamin</th>
                                <th class="py-3.5 px-4">No HP</th>
                                <th class="py-3.5 px-4">Email</th>
                                <th class="py-3.5 px-4">Kantor Layanan</th>
                                <th class="py-3.5 px-4">Segmen</th>
                                <th class="py-3.5 px-4">Profil</th>
                                <th class="py-3.5 px-4">RFM Last 90 Days</th>
                                <th class="py-3.5 px-4">RFM All Time</th>
                                <th class="py-3.5 px-4">Kategori</th>
                                <th class="py-3.5 px-4">Badge</th>
                                <th class="py-3.5 px-4 text-center">Total Transaksi</th>
                                <th class="py-3.5 px-4 text-right">Total Nominal</th>
                                <th class="py-3.5 px-4 text-center">Tgl Registrasi</th>
                                <th class="py-3.5 px-4 text-center">Tgl Insert</th>
                                <th class="py-3.5 px-4 text-center bg-emerald-800 shadow-inner">Action</th>
                                <th class="py-3.5 px-4">PIC Prospek</th>
                                <th class="py-3.5 px-4">Sumber Informasi</th>
                                <th class="py-3.5 px-4 text-center">Tgl Terakhir Donasi</th>
                                <th class="py-3.5 px-4">Program Terakhir Donasi</th>
                                <th class="py-3.5 px-4 text-right">Nominal Terakhir Donasi</th>
                                <th class="py-3.5 px-4">Via Bayar Terakhir</th>
                                <th class="py-3.5 px-4 text-center">Tgl Prospek Terakhir</th>
                                <th class="py-3.5 px-4">Media Prospek Terakhir</th>
                                <th class="py-3.5 px-4">Respon Prospek Terakhir</th>
                            </tr>
                        </thead>
                        <tbody class="text-xs font-semibold text-slate-700 divide-y divide-slate-100">
                            @forelse($donors as $index => $donor)
                                @php
                                    $phoneClean = preg_replace('/[^0-9]/', '', $donor->phone ?: '');
                                    if (!empty($phoneClean) && str_starts_with($phoneClean, '0')) {
                                        $phoneClean = '62' . substr($phoneClean, 1);
                                    }
                                    $waMsg = urlencode("Assalamu'alaikum warahmatullahi wabarakatuh, Kak " . $donor->name);
                                    $waUrl = !empty($phoneClean) ? "https://api.whatsapp.com/send?phone={$phoneClean}&text={$waMsg}" : null;
                                    $donorCode = '022607' . str_pad($donor->id, 8, '0', STR_PAD_LEFT);
                                @endphp
                                <tr @click="selectedItem = {{ json_encode($donor) }}; selectedId = {{ $donor->id }}; selectedName = '{{ addslashes($donor->name) }}'" 
                                    :class="selectedId === {{ $donor->id }} ? 'bg-emerald-50 border-l-4 border-emerald-600' : 'hover:bg-slate-50/80'"
                                    class="cursor-pointer transition-colors">
                                    <td class="py-3.5 px-4 text-center font-mono text-slate-600">{{ $donors->firstItem() + $index }}</td>
                                    <td class="py-3.5 px-4 font-mono font-bold text-emerald-700">{{ $donorCode }}</td>
                                    <td class="py-3.5 px-4 font-bold text-slate-900">{{ $donor->name }}</td>
                                    <td class="py-3.5 px-4 text-center font-bold">
                                        @if($donor->gender === 'L')
                                            <span class="px-2 py-0.5 bg-sky-50 text-sky-700 rounded-md text-xs">L</span>
                                        @elseif($donor->gender === 'P')
                                            <span class="px-2 py-0.5 bg-rose-50 text-rose-700 rounded-md text-xs">P</span>
                                        @else
                                            <span class="text-slate-600">-</span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 font-mono text-slate-600">{{ $donor->phone ?: '-' }}</td>
                                    <td class="py-3.5 px-4 text-slate-600">{{ $donor->email ?: '-' }}</td>
                                    <td class="py-3.5 px-4 text-slate-800">{{ $donor->office ? $donor->office->name : '-' }}</td>
                                    <td class="py-3.5 px-4 text-slate-600">{{ $donor->segment ?: '-' }}</td>
                                    <td class="py-3.5 px-4 text-slate-600">{{ $donor->donations_count > 0 ? 'Aktif' : 'Baru' }}</td>
                                    <td class="py-3.5 px-4">
                                        <span class="px-2.5 py-1 bg-amber-50 text-amber-800 rounded-full text-xs font-bold">
                                            {{ $donor->rfm_alltime ?: 'POTENTIAL' }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 rounded-full text-xs font-bold">
                                            {{ $donor->rfm_alltime ?: 'POTENTIAL' }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 text-slate-600">{{ $donor->donations_count > 0 ? 'AKTIF' : 'PASIF' }}</td>
                                    <td class="py-3.5 px-4 font-bold text-amber-800">
                                        @if(($donor->donations_sum_amount ?? 0) >= 10000000)
                                            PLATINUM
                                        @elseif(($donor->donations_sum_amount ?? 0) >= 2000000)
                                            GOLD
                                        @else
                                            SILVER
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 text-center font-mono font-bold text-slate-900">{{ number_format($donor->donations_count ?? 0) }}</td>
                                    <td class="py-3.5 px-4 text-right font-black text-emerald-700">Rp {{ number_format($donor->donations_sum_amount ?: 0, 0, ',', '.') }}</td>
                                    <td class="py-3.5 px-4 text-center font-mono text-slate-600">{{ $donor->created_at ? $donor->created_at->format('Y-m-d') : '-' }}</td>
                                    <td class="py-3.5 px-4 text-center font-mono text-slate-600">{{ $donor->created_at ? $donor->created_at->format('Y-m-d H:i') : '-' }}</td>
                                    
                                    {{-- ACTION COLUMN WITH BOTH WHATSAPP & PRINT RECEIPT ICONS --}}
                                    <td class="py-3.5 px-4 text-center bg-slate-50">
                                        <div class="flex items-center justify-center gap-2" @click.stop>
                                            {{-- 1. WhatsApp Direct Chat Icon --}}
                                            @if($waUrl)
                                                <a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer"
                                                   aria-label="Kirim pesan WhatsApp ke {{ $donor->name }}"
                                                   class="inline-flex items-center justify-center w-11 h-11 bg-emerald-700 hover:bg-emerald-800 text-white rounded-full shadow-sm transition-colors" 
                                                   title="Kirim Pesan WhatsApp">
                                                    <i class="fa-brands fa-whatsapp text-sm" aria-hidden="true"></i>
                                                </a>
                                            @else
                                                <span class="inline-flex items-center justify-center w-11 h-11 bg-slate-200 text-slate-600 rounded-full cursor-not-allowed" title="No WA Tidak Tersedia">
                                                    <i class="fa-brands fa-whatsapp text-sm" aria-hidden="true"></i>
                                                </span>
                                            @endif

                                            {{-- 2. Print Receipt Icon --}}
                                            <a href="{{ route('admin.donors.receipt', $donor->id) }}" target="_blank" rel="noopener noreferrer"
                                               aria-label="Cetak bukti donasi {{ $donor->name }}"
                                               class="inline-flex items-center justify-center w-11 h-11 bg-sky-50 hover:bg-sky-100 text-sky-700 border border-slate-700 rounded-lg shadow-sm transition-colors" 
                                               title="Cetak Bukti Donasi">
                                                <i class="fa-solid fa-print text-xs" aria-hidden="true"></i>
                                            </a>
                                        </div>
                                    </td>

                                    <td class="py-3.5 px-4 text-slate-600">{{ $donor->picProspect ? $donor->picProspect->name : '-' }}</td>
                                    <td class="py-3.5 px-4 text-slate-600">{{ $donor->source_info ?: 'WEB' }}</td>
                                    <td class="py-3.5 px-4 text-center font-mono text-slate-600">
                                        {{ $donor->latestDonation ? ($donor->latestDonation->donation_date ? \Carbon\Carbon::parse($donor->latestDonation->donation_date)->format('Y-m-d H:i') : $donor->latestDonation->created_at->format('Y-m-d H:i')) : '-' }}
                                    </td>
                                    <td class="py-3.5 px-4 text-slate-800 font-medium">
                                        {{ $donor->latestDonation && $donor->latestDonation->campaign ? $donor->latestDonation->campaign->title : '-' }}
                                    </td>
                                    <td class="py-3.5 px-4 text-right font-black text-slate-900">
                                        {{ $donor->latestDonation ? 'Rp ' . number_format($donor->latestDonation->amount, 0, ',', '.') : '-' }}
                                    </td>
                                    <td class="py-3.5 px-4 text-slate-600">
                                        {{ $donor->latestDonation ? ($donor->latestDonation->paymentVia ? $donor->latestDonation->paymentVia->name : ($donor->latestDonation->payment_method ?: '-')) : '-' }}
                                    </td>
                                    <td class="py-3.5 px-4 text-center text-slate-600">
                                        {{ $donor->prospect_date ? \Carbon\Carbon::parse($donor->prospect_date)->format('Y-m-d') : '-' }}
                                    </td>
                                    <td class="py-3.5 px-4 text-slate-600">
                                        {{ $donor->lastProspectMedia ? $donor->lastProspectMedia->name : '-' }}
                                    </td>
                                    <td class="py-3.5 px-4 text-slate-600">-</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="27" class="py-12 text-center text-slate-600 font-bold">Belum ada data donatur. Silakan klik + Add untuk menambah data baru.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($donors->hasPages())
                    <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                        {{ $donors->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>

    {{-- Modal Add Donatur --}}
    <div x-show="showAddModal" x-cloak @keydown.escape.window="showAddModal = false" role="dialog" aria-modal="true" aria-labelledby="add-donor-title" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60" @click="showAddModal = false"></div>
        <div class="relative z-10 bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl space-y-5">
            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                <h2 id="add-donor-title" class="text-base font-black text-slate-900 uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-user-plus text-emerald-700"></i> Tambah Data Donatur Baru
                </h2>
                <button type="button" @click="showAddModal = false" aria-label="Tutup modal tambah donatur" class="w-11 h-11 inline-flex items-center justify-center rounded-xl text-slate-600 hover:bg-slate-100 text-lg"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form action="{{ route('admin.donors.store') }}" method="POST" class="space-y-4 text-xs font-semibold text-slate-700">
                @csrf
                <div class="space-y-1">
                    <label for="add_donor_name">Nama Lengkap Donatur <span class="text-rose-700">*</span></label>
                    <input id="add_donor_name" type="text" name="name" required class="w-full border border-slate-300 rounded-xl px-4 py-2.5 min-h-[44px] focus:border-emerald-700">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label for="add_donor_phone">No HP / WA</label>
                        <input id="add_donor_phone" type="text" name="phone" placeholder="Contoh: 08123456789" class="w-full border border-slate-300 rounded-xl px-4 py-2.5 min-h-[44px] focus:border-emerald-700">
                    </div>
                    <div class="space-y-1">
                        <label for="add_donor_gender">Jenis Kelamin</label>
                        <select id="add_donor_gender" name="gender" class="w-full border border-slate-300 rounded-xl px-4 py-2.5 min-h-[44px] focus:border-emerald-700">
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="L">Laki-Laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                </div>
                <div class="space-y-1">
                    <label for="add_donor_email">Email Donatur</label>
                    <input id="add_donor_email" type="email" name="email" placeholder="Kosongkan jika tidak ada" class="w-full border border-slate-300 rounded-xl px-4 py-2.5 min-h-[44px] focus:border-emerald-700">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label for="add_donor_office">Kantor Layanan</label>
                        <select id="add_donor_office" name="office_id" class="w-full border border-slate-300 rounded-xl px-4 py-2.5 min-h-[44px] focus:border-emerald-700">
                            <option value="">Pilih Kantor Layanan</option>
                            @foreach($offices as $ofc)
                                <option value="{{ $ofc->id }}">{{ $ofc->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-1">
                        <label for="add_donor_segment">Segmen</label>
                        <input id="add_donor_segment" type="text" name="segment" placeholder="Digital/Web" class="w-full border border-slate-300 rounded-xl px-4 py-2.5 min-h-[44px] focus:border-emerald-700">
                    </div>
                </div>
                <div class="space-y-1">
                    <label for="add_donor_address">Alamat Lengkap</label>
                    <textarea id="add_donor_address" name="address" rows="2" class="w-full border border-slate-300 rounded-xl p-3 focus:border-emerald-700"></textarea>
                </div>
                <div class="flex justify-end gap-3 pt-3">
                    <button type="button" @click="showAddModal = false" class="px-5 py-2.5 min-h-[44px] rounded-xl border border-slate-300 text-slate-700 hover:bg-slate-50 font-bold">Batal</button>
                    <button type="submit" class="px-5 py-2.5 min-h-[44px] rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold shadow-md">Simpan Donatur</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Edit Donatur --}}
    <div x-show="showEditModal" x-cloak @keydown.escape.window="showEditModal = false" role="dialog" aria-modal="true" aria-labelledby="edit-donor-title" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60" @click="showEditModal = false"></div>
        <div class="relative z-10 bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl space-y-5">
            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                <h2 id="edit-donor-title" class="text-base font-black text-slate-900 uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-pen-to-square text-emerald-700"></i> Edit Data Donatur
                </h2>
                <button type="button" @click="showEditModal = false" aria-label="Tutup modal edit donatur" class="w-11 h-11 inline-flex items-center justify-center rounded-xl text-slate-600 hover:bg-slate-100 text-lg"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form :action="'{{ url('admin/donors') }}/' + editData.id + '/update'" method="POST" class="space-y-4 text-xs font-semibold text-slate-700">
                @csrf
                @method('PUT')
                <div class="space-y-1">
                    <label for="edit_donor_name">Nama Lengkap Donatur <span class="text-rose-700">*</span></label>
                    <input id="edit_donor_name" type="text" name="name" x-model="editData.name" required class="w-full border border-slate-300 rounded-xl px-4 py-2.5 min-h-[44px] focus:border-emerald-700">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label for="edit_donor_phone">No HP / WA</label>
                        <input id="edit_donor_phone" type="text" name="phone" x-model="editData.phone" class="w-full border border-slate-300 rounded-xl px-4 py-2.5 min-h-[44px] focus:border-emerald-700">
                    </div>
                    <div class="space-y-1">
                        <label for="edit_donor_gender">Jenis Kelamin</label>
                        <select id="edit_donor_gender" name="gender" x-model="editData.gender" class="w-full border border-slate-300 rounded-xl px-4 py-2.5 min-h-[44px] focus:border-emerald-700">
                            <option value="L">Laki-Laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                </div>
                <div class="space-y-1">
                    <label for="edit_donor_email">Email Donatur</label>
                    <input id="edit_donor_email" type="email" name="email" x-model="editData.email" class="w-full border border-slate-300 rounded-xl px-4 py-2.5 min-h-[44px] focus:border-emerald-700">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label for="edit_donor_office">Kantor Layanan</label>
                        <select id="edit_donor_office" name="office_id" x-model="editData.office_id" class="w-full border border-slate-300 rounded-xl px-4 py-2.5 min-h-[44px] focus:border-emerald-700">
                            <option value="">Pilih Kantor Layanan</option>
                            @foreach($offices as $ofc)
                                <option value="{{ $ofc->id }}">{{ $ofc->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-1">
                        <label for="edit_donor_segment">Segmen</label>
                        <input id="edit_donor_segment" type="text" name="segment" x-model="editData.segment" class="w-full border border-slate-300 rounded-xl px-4 py-2.5 min-h-[44px] focus:border-emerald-700">
                    </div>
                </div>
                <div class="space-y-1">
                    <label for="edit_donor_address">Alamat Lengkap</label>
                    <textarea id="edit_donor_address" name="address" x-model="editData.address" rows="2" class="w-full border border-slate-300 rounded-xl p-3 focus:border-emerald-700"></textarea>
                </div>
                <div class="flex justify-end gap-3 pt-3">
                    <button type="button" @click="showEditModal = false" class="px-5 py-2.5 min-h-[44px] rounded-xl border border-slate-300 text-slate-700 hover:bg-slate-50 font-bold">Batal</button>
                    <button type="submit" class="px-5 py-2.5 min-h-[44px] rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold shadow-md">Perbarui Donatur</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Delete Donatur --}}
    <div x-show="showDeleteModal" x-cloak @keydown.escape.window="showDeleteModal = false" role="dialog" aria-modal="true" aria-labelledby="delete-donor-title" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60" @click="showDeleteModal = false"></div>
        <div class="relative z-10 bg-white rounded-3xl max-w-sm w-full p-6 shadow-2xl space-y-4 text-center">
            <div class="w-14 h-14 bg-rose-50 text-rose-700 rounded-full flex items-center justify-center text-2xl mx-auto">
                <i class="fa-solid fa-trash-can"></i>
            </div>
            <div class="space-y-1">
                <h2 id="delete-donor-title" class="text-base font-black text-slate-900">Konfirmasi Hapus</h2>
                <p class="text-xs text-slate-600 font-medium">Apakah Anda yakin ingin menghapus data donatur <span class="font-bold text-slate-900" x-text="selectedName"></span>?</p>
            </div>
            <form :action="'{{ url('admin/donors') }}/' + selectedId + '/delete'" method="POST" class="flex justify-center gap-3 pt-2">
                @csrf
                @method('DELETE')
                <button type="button" @click="showDeleteModal = false" class="px-5 py-2.5 min-h-[44px] rounded-xl border border-slate-300 text-slate-700 hover:bg-slate-50 font-bold text-xs">Batal</button>
                <button type="submit" class="px-5 py-2.5 min-h-[44px] rounded-xl bg-rose-700 hover:bg-rose-800 text-white font-bold text-xs shadow-md">Ya, Hapus Data</button>
            </form>
        </div>
    </div>

    {{-- Modal Import CSV --}}
    <div x-show="showImportModal" x-cloak @keydown.escape.window="showImportModal = false" role="dialog" aria-modal="true" aria-labelledby="import-donor-title" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60" @click="showImportModal = false"></div>
        <div class="relative z-10 bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl space-y-4">
            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                <h2 id="import-donor-title" class="text-base font-black text-slate-900 uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-file-import text-emerald-700"></i> Import Data Donatur (CSV)
                </h2>
                <button type="button" @click="showImportModal = false" aria-label="Tutup modal import donatur" class="w-11 h-11 inline-flex items-center justify-center rounded-xl text-slate-600 hover:bg-slate-100 text-lg"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form action="{{ route('admin.donors.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs font-semibold text-slate-700">
                @csrf
                <div class="space-y-1">
                    <label for="import_donor_file">Pilih File Excel / CSV (.csv)</label>
                    <input id="import_donor_file" type="file" name="file" accept=".csv" required class="w-full border border-slate-300 rounded-xl px-4 py-2.5 min-h-[44px]">
                </div>
                <p class="text-xs text-slate-600 italic">Format kolom CSV: No, ID Donatur, Nama Lengkap, Jenis Kelamin, No HP, Email, Kantor Layanan, Segmen</p>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" @click="showImportModal = false" class="px-5 py-2.5 min-h-[44px] rounded-xl border border-slate-300 text-slate-700 hover:bg-slate-50 font-bold">Batal</button>
                    <button type="submit" class="px-5 py-2.5 min-h-[44px] rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold shadow-md">Upload & Import</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Detail Data --}}
    <div x-show="showDetailModal" x-cloak @keydown.escape.window="showDetailModal = false" role="dialog" aria-modal="true" aria-labelledby="detail-donor-title" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60" @click="showDetailModal = false"></div>
        <div class="relative z-10 bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl space-y-4" x-if="selectedItem">
            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                <h2 id="detail-donor-title" class="text-base font-black text-slate-900 uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-circle-info text-sky-700"></i> Detail Data Donatur
                </h2>
                <button type="button" @click="showDetailModal = false" aria-label="Tutup modal detail donatur" class="w-11 h-11 inline-flex items-center justify-center rounded-xl text-slate-600 hover:bg-slate-100 text-lg"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="space-y-2 text-xs font-semibold text-slate-700 divide-y divide-slate-100">
                <div class="py-2 flex justify-between"><span class="text-slate-600">ID Donatur:</span><span class="font-bold font-mono text-emerald-700" x-text="'022607' + String(selectedItem.id).padStart(8, '0')"></span></div>
                <div class="py-2 flex justify-between"><span class="text-slate-600">Nama Lengkap:</span><span class="font-bold text-slate-900" x-text="selectedItem.name"></span></div>
                <div class="py-2 flex justify-between"><span class="text-slate-600">Jenis Kelamin:</span><span x-text="selectedItem.gender === 'L' ? 'Laki-Laki' : (selectedItem.gender === 'P' ? 'Perempuan' : '-')"></span></div>
                <div class="py-2 flex justify-between"><span class="text-slate-600">No HP / WA:</span><span class="font-mono" x-text="selectedItem.phone || '-'"></span></div>
                <div class="py-2 flex justify-between"><span class="text-slate-600">Email:</span><span x-text="selectedItem.email || '-'"></span></div>
                <div class="py-2 flex justify-between"><span class="text-slate-600">Segmen:</span><span x-text="selectedItem.segment || '-'"></span></div>
                <div class="py-2 flex justify-between"><span class="text-slate-600">RFM All Time:</span><span class="font-bold text-emerald-700" x-text="selectedItem.rfm_alltime || '-'"></span></div>
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <a :href="'/admin/donors/' + selectedItem.id + '/receipt'" target="_blank" class="px-5 py-2.5 min-h-[44px] rounded-xl bg-sky-700 hover:bg-sky-800 text-white font-bold inline-flex items-center gap-2 text-xs">
                    <i class="fa-solid fa-print"></i> Cetak Bukti Donasi
                </a>
                <button type="button" @click="showDetailModal = false" class="px-5 py-2.5 min-h-[44px] rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs">Tutup</button>
            </div>
        </div>
    </div>

</div>
@endsection
