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
    selectedTrxId: '',
    editData: {
        id: null,
        donor_name: '',
        donor_phone: '',
        amount: '',
        campaign_id: '',
        payment_via_id: '',
        notes: ''
    },
    openEdit(item) {
        this.editData = {
            id: item.id,
            donor_name: item.donor_name ?? '',
            donor_phone: item.donor_phone ?? '',
            amount: item.amount ?? '',
            campaign_id: item.campaign_id ?? '',
            payment_via_id: item.payment_via_id ?? '',
            notes: item.notes ?? ''
        };
        this.showEditModal = true;
    },
    openDelete(id, trxId) {
        this.selectedId = id;
        this.selectedTrxId = trxId;
        this.showDeleteModal = true;
    }
}">

    <div class="grid grid-cols-1 lg:grid-cols-[280px_1fr] gap-8 items-start min-w-0">

        {{-- Sidebar --}}
        <aside class="hidden lg:block space-y-5">
            @include('admin.sidebar', ['active' => 'transactions_list'])
        </aside>

        {{-- Main Content Column (Locked min-w-0 to prevent horizontal page scrolling) --}}
        <div class="space-y-6 min-w-0 overflow-hidden">

            {{-- Top Tab Header Navigation --}}
            <div class="flex flex-wrap items-center gap-1 border-b border-slate-200 pb-0">
                <a href="{{ route('admin.donors_list') }}" class="px-5 py-2.5 min-h-[44px] rounded-t-xl text-xs font-bold bg-slate-100 text-slate-700 hover:bg-slate-200 transition-colors inline-flex items-center gap-2">
                    List Donatur
                </a>
                <a href="{{ route('admin.transactions_list') }}" aria-current="page" class="px-5 py-2.5 min-h-[44px] rounded-t-xl text-xs font-black bg-emerald-700 text-white shadow-sm inline-flex items-center gap-2">
                    List Transaksi
                </a>
                <a href="{{ route('admin.prospects_list') }}" class="px-5 py-2.5 min-h-[44px] rounded-t-xl text-xs font-bold bg-slate-100 text-slate-700 hover:bg-slate-200 transition-colors inline-flex items-center gap-2">
                    List Prospek
                </a>
            </div>

            {{-- Top Banner --}}
            <div class="bg-emerald-700 rounded-2xl px-6 py-3.5 text-white font-black text-sm uppercase tracking-wider shadow-sm flex flex-wrap items-center justify-between gap-2">
                <span class="flex items-center gap-2">
                    <i class="fa-solid fa-receipt text-white"></i> List Transaksi
                </span>
                <span class="text-xs text-emerald-100 font-normal">Transaction Management / List Transaksi</span>
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

            {{-- Dashboard Summary Bar --}}
            <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
                <div class="bg-slate-50 px-4 py-2 border-b border-slate-200 text-xs font-bold text-slate-600">
                    Dashboard
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 divide-y sm:divide-y-0 sm:divide-x divide-slate-100 text-center">
                    {{-- Total Quantity --}}
                    <div class="p-4 space-y-1">
                        <div class="bg-emerald-700 text-white py-1.5 px-2 text-xs font-black uppercase tracking-wider rounded-t-xl -mx-4 -mt-4 mb-2">
                            Jumlah Quantity
                        </div>
                        <p class="text-2xl font-black text-slate-900 font-mono">{{ number_format($totalQuantity) }}</p>
                    </div>

                    {{-- Total Donatur --}}
                    <div class="p-4 space-y-1">
                        <div class="bg-emerald-700 text-white py-1.5 px-2 text-xs font-black uppercase tracking-wider rounded-t-xl -mx-4 -mt-4 mb-2">
                            Jumlah Donatur
                        </div>
                        <p class="text-2xl font-black text-slate-900 font-mono">{{ number_format($totalDonorsCount) }}</p>
                    </div>

                    {{-- Total Capaian --}}
                    <div class="p-4 space-y-1">
                        <div class="bg-emerald-700 text-white py-1.5 px-2 text-xs font-black uppercase tracking-wider rounded-t-xl -mx-4 -mt-4 mb-2">
                            Total Capaian
                        </div>
                        <p class="text-2xl font-black text-emerald-700 font-mono">
                            {{ number_format($totalCapaian, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Filter Container & Search Bar (Presisi Form Screenshot User) --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm space-y-4">
                
                <form action="{{ route('admin.transactions_list') }}" method="GET" class="space-y-4">
                    
                    {{-- Row 1: Search Bar & Tgl Donasi --}}
                    <div class="flex flex-wrap items-center gap-3">
                        <label for="tl_search" class="text-xs font-bold text-slate-700">Search :</label>
                        <input id="tl_search" type="text" name="search" value="{{ request('search') }}" 
                               placeholder="ID Trx/Nama Donatur/ID Donatur/No HP/Email/No Kw/Ket" 
                               class="flex-1 min-h-[44px] border border-slate-300 rounded-xl px-4 py-2 text-xs font-medium focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700 min-w-[280px]">
                        
                        <label for="tl_start_date" class="text-xs font-bold text-slate-700">Tgl Donasi :</label>
                        <input id="tl_start_date" type="date" name="start_date" value="{{ request('start_date') }}" class="min-h-[44px] border border-slate-300 rounded-xl px-3 py-1.5 text-xs">
                        <span class="text-xs text-slate-600 font-semibold">s/d</span>
                        <input aria-label="Tanggal donasi akhir" type="date" name="end_date" value="{{ request('end_date') }}" class="min-h-[44px] border border-slate-300 rounded-xl px-3 py-1.5 text-xs">

                        <button type="submit" class="min-h-[44px] bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-300 font-bold px-4 py-2 rounded-xl text-xs inline-flex items-center gap-1.5 transition-colors">
                            <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i> Search
                        </button>
                        
                        <button type="button" @click="showAdvanceSearch = !showAdvanceSearch" 
                                :aria-expanded="showAdvanceSearch"
                                class="min-h-[44px] bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-300 font-bold px-4 py-2 rounded-xl text-xs inline-flex items-center gap-1.5 transition-colors">
                            <i class="fa-solid fa-sliders" aria-hidden="true"></i> Advance Search
                        </button>
                    </div>

                    {{-- Advance Search Drawer (4 Rows of Filters - Fixed Responsive Grid) --}}
                    <div x-show="showAdvanceSearch" x-collapse class="bg-slate-50 border border-slate-200 p-4 rounded-xl space-y-3 text-xs text-slate-700 shadow-inner">
                        
                        {{-- Row 1: Waktu Donasi (Range), Rumpun Program, Program --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-3 items-center">
                            <div class="md:col-span-2 lg:col-span-5 flex items-center gap-2 min-w-0">
                                <span class="w-24 shrink-0 text-xs font-semibold whitespace-nowrap">Waktu Donasi</span>
                                <span class="shrink-0 text-slate-600 font-bold">:</span>
                                <input aria-label="Jam donasi mulai" type="time" name="start_time" value="{{ request('start_time') }}" class="flex-1 min-w-0 min-h-[44px] bg-white border border-slate-300 rounded-lg px-2 py-1.5 text-xs focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700">
                                <span class="shrink-0 text-slate-600 font-medium text-xs">s/d</span>
                                <input aria-label="Jam donasi akhir" type="time" name="end_time" value="{{ request('end_time') }}" class="flex-1 min-w-0 min-h-[44px] bg-white border border-slate-300 rounded-lg px-2 py-1.5 text-xs focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700">
                            </div>
                            <div class="md:col-span-1 lg:col-span-4 flex items-center gap-2 min-w-0">
                                <span class="w-24 shrink-0 text-xs font-semibold whitespace-nowrap">Rumpun Program</span>
                                <span class="shrink-0 text-slate-600 font-bold">:</span>
                                <select aria-label="Filter rumpun program" name="program_group_id" class="flex-1 min-w-0 min-h-[44px] bg-white border border-slate-300 rounded-lg px-2 py-1.5 text-xs focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700">
                                    <option value="">Pilih Rumpun Program</option>
                                    @foreach($programGroups as $pg)
                                        <option value="{{ $pg->id }}" {{ request('program_group_id') == $pg->id ? 'selected' : '' }}>{{ $pg->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="md:col-span-1 lg:col-span-3 flex items-center gap-2 min-w-0">
                                <span class="w-16 shrink-0 text-xs font-semibold whitespace-nowrap">Program</span>
                                <span class="shrink-0 text-slate-600 font-bold">:</span>
                                <select aria-label="Filter program" name="program_id" class="flex-1 min-w-0 min-h-[44px] bg-white border border-slate-300 rounded-lg px-2 py-1.5 text-xs focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700">
                                    <option value="">Pilih Program</option>
                                    @foreach($programs as $prg)
                                        <option value="{{ $prg->id }}" {{ request('program_id') == $prg->id ? 'selected' : '' }}>{{ $prg->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Row 2: Nominal (Range), Channel Donasi, Via Bayar --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-3 items-center">
                            <div class="md:col-span-2 lg:col-span-5 flex items-center gap-2 min-w-0">
                                <span class="w-24 shrink-0 text-xs font-semibold whitespace-nowrap">Nominal</span>
                                <span class="shrink-0 text-slate-600 font-bold">:</span>
                                <input aria-label="Nominal minimum" type="number" name="min_amount" value="{{ request('min_amount') }}" placeholder="Min Rp" class="flex-1 min-w-0 min-h-[44px] bg-white border border-slate-300 rounded-lg px-2 py-1.5 text-xs focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700">
                                <span class="shrink-0 text-slate-600 font-medium text-xs">s/d</span>
                                <input aria-label="Nominal maksimum" type="number" name="max_amount" value="{{ request('max_amount') }}" placeholder="Maks Rp" class="flex-1 min-w-0 min-h-[44px] bg-white border border-slate-300 rounded-lg px-2 py-1.5 text-xs focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700">
                            </div>
                            <div class="md:col-span-1 lg:col-span-4 flex items-center gap-2 min-w-0">
                                <span class="w-24 shrink-0 text-xs font-semibold whitespace-nowrap">Channel Donasi</span>
                                <span class="shrink-0 text-slate-600 font-bold">:</span>
                                <select aria-label="Filter channel donasi" name="donor_channel_id" class="flex-1 min-w-0 min-h-[44px] bg-white border border-slate-300 rounded-lg px-2 py-1.5 text-xs focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700">
                                    <option value="">Pilih Channel Donasi</option>
                                    @foreach($donorChannels as $dc)
                                        <option value="{{ $dc->id }}" {{ request('donor_channel_id') == $dc->id ? 'selected' : '' }}>{{ $dc->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="md:col-span-1 lg:col-span-3 flex items-center gap-2 min-w-0">
                                <span class="w-16 shrink-0 text-xs font-semibold whitespace-nowrap">Via Bayar</span>
                                <span class="shrink-0 text-slate-600 font-bold">:</span>
                                <select aria-label="Filter via bayar" name="payment_via_id" class="flex-1 min-w-0 min-h-[44px] bg-white border border-slate-300 rounded-lg px-2 py-1.5 text-xs focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700">
                                    <option value="">Pilih Via Bayar</option>
                                    @foreach($paymentVias as $pv)
                                        <option value="{{ $pv->id }}" {{ request('payment_via_id') == $pv->id ? 'selected' : '' }}>{{ $pv->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Row 3: PIC Prospek, Sumber Informasi, Kantor Layanan --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-12 gap-3 items-center">
                            <div class="lg:col-span-4 flex items-center gap-2 min-w-0">
                                <span class="w-24 shrink-0 text-xs font-semibold whitespace-nowrap">PIC Prospek</span>
                                <span class="shrink-0 text-slate-600 font-bold">:</span>
                                <select aria-label="Filter PIC prospek" name="pic_prospect" class="flex-1 min-w-0 min-h-[44px] bg-white border border-slate-300 rounded-lg px-2 py-1.5 text-xs focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700">
                                    <option value="">Pilih PIC Prospek</option>
                                </select>
                            </div>
                            <div class="lg:col-span-4 flex items-center gap-2 min-w-0">
                                <span class="w-24 shrink-0 text-xs font-semibold whitespace-nowrap">Sumber Info</span>
                                <span class="shrink-0 text-slate-600 font-bold">:</span>
                                <input aria-label="Filter sumber info" type="text" name="source_info" value="{{ request('source_info') }}" placeholder="Sumber Informasi" class="flex-1 min-w-0 min-h-[44px] bg-white border border-slate-300 rounded-lg px-2 py-1.5 text-xs focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700">
                            </div>
                            <div class="lg:col-span-4 flex items-center gap-2 min-w-0">
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

                        {{-- Row 4: Tgl Insert (Range) & Segmen (Expanded to prevent any overlap) --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-3 items-center">
                            <div class="md:col-span-1 lg:col-span-7 flex items-center gap-2 min-w-0">
                                <span class="w-24 shrink-0 text-xs font-semibold whitespace-nowrap">Tgl Insert</span>
                                <span class="shrink-0 text-slate-600 font-bold">:</span>
                                <input aria-label="Tanggal insert mulai" type="date" name="start_insert_date" value="{{ request('start_insert_date') }}" class="flex-1 min-w-0 min-h-[44px] bg-white border border-slate-300 rounded-lg px-2 py-1.5 text-xs focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700">
                                <span class="shrink-0 text-slate-600 font-medium text-xs">s/d</span>
                                <input aria-label="Tanggal insert akhir" type="date" name="end_insert_date" value="{{ request('end_insert_date') }}" class="flex-1 min-w-0 min-h-[44px] bg-white border border-slate-300 rounded-lg px-2 py-1.5 text-xs focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700">
                            </div>
                            <div class="md:col-span-1 lg:col-span-5 flex items-center gap-2 min-w-0">
                                <span class="w-24 shrink-0 text-xs font-semibold whitespace-nowrap">Segmen</span>
                                <span class="shrink-0 text-slate-600 font-bold">:</span>
                                <input aria-label="Filter segmen" type="text" name="segment" value="{{ request('segment') }}" placeholder="Segmen" class="flex-1 min-w-0 min-h-[44px] bg-white border border-slate-300 rounded-lg px-2 py-1.5 text-xs focus:border-emerald-700 focus:ring-1 focus:ring-emerald-700">
                            </div>
                        </div>

                    </div>
                </form>

            </div>

            {{-- Action Toolbar: Add, Edit, Print, Delete, Export, Import, Link Bukti Donasi --}}
            <div class="flex flex-wrap items-center gap-2 pt-1 pb-1">
                <button type="button" @click="showAddModal = true" class="bg-emerald-700 hover:bg-emerald-800 text-white font-bold px-4 py-2.5 min-h-[44px] rounded-xl text-xs inline-flex items-center gap-1.5 shadow-sm transition-colors">
                    <i class="fa-solid fa-circle-plus"></i> Add
                </button>

                <button type="button" 
                        @click="if(selectedItem) { openEdit(selectedItem); } else { alert('Pilih transaksi dari tabel terlebih dahulu.'); }" 
                        class="bg-emerald-700 hover:bg-emerald-800 text-white font-bold px-4 py-2.5 min-h-[44px] rounded-xl text-xs inline-flex items-center gap-1.5 shadow-sm transition-colors">
                    <i class="fa-solid fa-pen-to-square"></i> Edit
                </button>

                <button type="button" 
                        @click="if(selectedItem) { window.open('/admin/transactions/' + selectedItem.id + '/receipt', '_blank'); } else { alert('Pilih transaksi dari tabel terlebih dahulu.'); }" 
                        class="bg-sky-700 hover:bg-sky-800 text-white font-bold px-4 py-2.5 min-h-[44px] rounded-xl text-xs inline-flex items-center gap-1.5 shadow-sm transition-colors"
                        title="Cetak kwitansi dari 1 baris yang dipilih">
                    <i class="fa-solid fa-print"></i> Print (Pilihan)
                </button>

                <a href="{{ route('admin.transactions.batch_receipt', request()->query()) }}" target="_blank" 
                   class="bg-indigo-700 hover:bg-indigo-800 text-white font-bold px-4 py-2.5 min-h-[44px] rounded-xl text-xs inline-flex items-center gap-1.5 shadow-sm transition-colors"
                   title="Download atau Cetak Semua Kwitansi Hasil Filter (atau Keseluruhan)">
                    <i class="fa-solid fa-file-invoice"></i> Download / Cetak Kwitansi Terfilter
                </a>

                <button type="button" 
                        @click="if(selectedId) { openDelete(selectedId, selectedTrxId); } else { alert('Pilih transaksi dari tabel terlebih dahulu.'); }" 
                        class="bg-rose-700 hover:bg-rose-800 text-white font-bold px-4 py-2.5 min-h-[44px] rounded-xl text-xs inline-flex items-center gap-1.5 shadow-sm transition-colors">
                    <i class="fa-solid fa-trash-can"></i> Delete
                </button>

                <a href="{{ route('admin.transactions.export', request()->query()) }}" class="bg-emerald-700 hover:bg-emerald-800 text-white font-bold px-4 py-2.5 min-h-[44px] rounded-xl text-xs inline-flex items-center gap-1.5 shadow-sm transition-colors">
                    <i class="fa-solid fa-file-excel"></i> Export
                </a>

                <button type="button" @click="showImportModal = true" class="bg-emerald-700 hover:bg-emerald-800 text-white font-bold px-4 py-2.5 min-h-[44px] rounded-xl text-xs inline-flex items-center gap-1.5 shadow-sm transition-colors">
                    <i class="fa-solid fa-file-import"></i> Import
                </button>

                <button type="button" 
                        @click="if(selectedItem) { window.open('/admin/transactions/' + selectedItem.id + '/receipt', '_blank'); } else { alert('Pilih transaksi dari tabel terlebih dahulu.'); }" 
                        class="bg-indigo-700 hover:bg-indigo-800 text-white font-bold px-4 py-2.5 min-h-[44px] rounded-xl text-xs inline-flex items-center gap-1.5 shadow-sm transition-colors">
                    <i class="fa-solid fa-link"></i> Link Bukti Donasi
                </button>
            </div>

            {{-- Table Transaksi Container --}}
            <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm w-full">
                
                {{-- Horizontal Scrollable Wrapper --}}
                <div class="w-full overflow-x-auto overflow-y-hidden scrollbar-thin scrollbar-thumb-emerald-700 scrollbar-track-slate-100">
                    <table class="w-full text-left border-collapse whitespace-nowrap min-w-[2400px]">
                        <thead>
                            <tr class="bg-emerald-700 text-white text-xs font-black uppercase tracking-wider">
                                <th class="py-3.5 px-4 text-center">No</th>
                                <th class="py-3.5 px-4">ID Transaksi</th>
                                <th class="py-3.5 px-4">ID Donatur</th>
                                <th class="py-3.5 px-4 text-center">Tgl Donasi</th>
                                <th class="py-3.5 px-4 text-center">Tgl Insert</th>
                                <th class="py-3.5 px-4 text-center">Tgl Konfirmasi</th>
                                <th class="py-3.5 px-4">Nama Lengkap</th>
                                <th class="py-3.5 px-4">No HP</th>
                                <th class="py-3.5 px-4">Email</th>
                                <th class="py-3.5 px-4">Kantor Layanan</th>
                                <th class="py-3.5 px-4">Segmen</th>
                                <th class="py-3.5 px-4">Profil</th>
                                <th class="py-3.5 px-4">Channel Donasi</th>
                                <th class="py-3.5 px-4">Rumpun Program</th>
                                <th class="py-3.5 px-4">Program Donasi</th>
                                <th class="py-3.5 px-4 text-center">Qty</th>
                                <th class="py-3.5 px-4 text-right">Nominal Donasi</th>
                                <th class="py-3.5 px-4">Via Bayar</th>
                                <th class="py-3.5 px-4">PIC Prospek</th>
                                <th class="py-3.5 px-4">Sumber Informasi</th>
                                <th class="py-3.5 px-4">No Kwitansi</th>
                                <th class="py-3.5 px-4">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="text-xs font-semibold text-slate-700 divide-y divide-slate-100">
                            @forelse($donations as $index => $trx)
                                @php
                                    $trxCode = $trx->transaction_id ?: ('TRX-' . str_pad($trx->id, 6, '0', STR_PAD_LEFT));
                                    $donorCode = $trx->user ? ($trx->user->donor_id ?: ('DNR-' . str_pad($trx->user->id, 5, '0', STR_PAD_LEFT))) : '-';
                                @endphp
                                <tr @click="selectedItem = {{ json_encode($trx) }}; selectedId = {{ $trx->id }}; selectedTrxId = '{{ $trxCode }}'" 
                                    :class="selectedId === {{ $trx->id }} ? 'bg-emerald-50 border-l-4 border-emerald-600' : 'hover:bg-slate-50/80'"
                                    class="cursor-pointer transition-colors">
                                    <td class="py-3.5 px-4 text-center font-mono text-slate-600">{{ $donations->firstItem() + $index }}</td>
                                    <td class="py-3.5 px-4 font-mono font-bold text-emerald-700">{{ $trxCode }}</td>
                                    <td class="py-3.5 px-4 font-mono text-slate-600">{{ $donorCode }}</td>
                                    <td class="py-3.5 px-4 text-center font-mono text-slate-600">{{ $trx->donation_date ? $trx->donation_date->format('Y-m-d H:i:s') : ($trx->created_at ? $trx->created_at->format('Y-m-d H:i:s') : '-') }}</td>
                                    <td class="py-3.5 px-4 text-center font-mono text-slate-600">{{ $trx->created_at ? $trx->created_at->format('Y-m-d H:i:s') : '-' }}</td>
                                    <td class="py-3.5 px-4 text-center font-mono text-slate-600">{{ $trx->updated_at ? $trx->updated_at->format('Y-m-d') : ($trx->created_at ? $trx->created_at->format('Y-m-d') : '-') }}</td>
                                    <td class="py-3.5 px-4 font-bold text-slate-900">{{ $trx->donor_name ?: ($trx->user ? $trx->user->name : 'Hamba Allah') }}</td>
                                    <td class="py-3.5 px-4 font-mono text-slate-600">{{ $trx->donor_phone ?: ($trx->user ? $trx->user->phone : '-') }}</td>
                                    <td class="py-3.5 px-4 text-slate-600">{{ $trx->donor_email ?: ($trx->user ? $trx->user->email : '-') }}</td>
                                    <td class="py-3.5 px-4 text-slate-800">{{ $trx->user && $trx->user->office ? $trx->user->office->name : '-' }}</td>
                                    <td class="py-3.5 px-4 text-slate-600">{{ $trx->user ? ($trx->user->donor_segment ?: 'Umum') : 'Umum' }}</td>
                                    <td class="py-3.5 px-4 text-slate-600">{{ $trx->user ? ($trx->user->donor_profile ?: 'Individu') : 'Individu' }}</td>
                                    <td class="py-3.5 px-4 text-slate-600 font-medium">{{ $trx->source ?: 'Web' }}</td>
                                    <td class="py-3.5 px-4 text-slate-800">{{ $trx->campaign && $trx->campaign->group ? $trx->campaign->group->name : ($trx->campaign ? $trx->campaign->category : '-') }}</td>
                                    <td class="py-3.5 px-4 text-slate-900 font-medium">{{ $trx->campaign ? $trx->campaign->title : '-' }}</td>
                                    <td class="py-3.5 px-4 text-center font-mono font-bold text-slate-900">1</td>
                                    <td class="py-3.5 px-4 text-right font-black text-emerald-700">Rp {{ number_format($trx->amount ?: 0, 0, ',', '.') }}</td>
                                    <td class="py-3.5 px-4 text-slate-700">{{ $trx->payment_method ?: ($trx->paymentVia ? $trx->paymentVia->name : '-') }}</td>
                                    <td class="py-3.5 px-4 text-slate-600">-</td>
                                    <td class="py-3.5 px-4 text-slate-600">{{ $trx->source ?: 'Web' }}</td>
                                    <td class="py-3.5 px-4 font-mono text-slate-600">{{ $trx->receipt_no ?: ($trx->transaction_id ?: $trx->id) }}</td>
                                    <td class="py-3.5 px-4 text-slate-600 italic max-w-xs truncate">{{ $trx->notes ?: ($trx->prayer ?: '-') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="22" class="py-12 text-center text-slate-600 font-bold">Belum ada data transaksi. Silakan klik + Add untuk menambah transaksi baru.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($donations->hasPages())
                    <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                        {{ $donations->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>

    {{-- Modal Add Transaksi --}}
    <div x-show="showAddModal" x-cloak @keydown.escape.window="showAddModal = false" role="dialog" aria-modal="true" aria-labelledby="add-trx-title" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60" @click="showAddModal = false"></div>
        <div class="relative z-10 bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl space-y-5">
            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                <h2 id="add-trx-title" class="text-base font-black text-slate-900 uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-circle-plus text-emerald-700"></i> Tambah Transaksi Donasi Baru
                </h2>
                <button type="button" @click="showAddModal = false" aria-label="Tutup modal tambah transaksi" class="w-11 h-11 inline-flex items-center justify-center rounded-xl text-slate-600 hover:bg-slate-100 text-lg"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form action="{{ route('admin.transactions.store') }}" method="POST" class="space-y-4 text-xs font-semibold text-slate-700">
                @csrf
                <div class="space-y-1">
                    <label for="add_trx_name">Nama Donatur <span class="text-rose-700">*</span></label>
                    <input id="add_trx_name" type="text" name="donor_name" required placeholder="Contoh: Ahmad Abdullah" class="w-full border border-slate-300 rounded-xl px-4 py-2.5 min-h-[44px] focus:border-emerald-700">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label for="add_trx_phone">No HP / WA Donatur</label>
                        <input id="add_trx_phone" type="text" name="donor_phone" placeholder="Contoh: 08123456789" class="w-full border border-slate-300 rounded-xl px-4 py-2.5 min-h-[44px] focus:border-emerald-700">
                    </div>
                    <div class="space-y-1">
                        <label for="add_trx_email">Email Donatur</label>
                        <input id="add_trx_email" type="email" name="donor_email" placeholder="Contoh: donatur@email.com" class="w-full border border-slate-300 rounded-xl px-4 py-2.5 min-h-[44px] focus:border-emerald-700">
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label for="add_trx_amount">Nominal Donasi (Rp) <span class="text-rose-700">*</span></label>
                        <input id="add_trx_amount" type="number" name="amount" required placeholder="Contoh: 50000" class="w-full border border-slate-300 rounded-xl px-4 py-2.5 min-h-[44px] focus:border-emerald-700">
                    </div>
                    <div class="space-y-1">
                        <label for="add_trx_date">Tgl Donasi <span class="text-rose-700">*</span></label>
                        <input id="add_trx_date" type="datetime-local" name="donation_date" value="{{ date('Y-m-d\TH:i') }}" required class="w-full border border-slate-300 rounded-xl px-4 py-2.5 min-h-[44px] focus:border-emerald-700">
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label for="add_trx_campaign">Program Donasi <span class="text-rose-700">*</span></label>
                        <select id="add_trx_campaign" name="campaign_id" required class="w-full border border-slate-300 rounded-xl px-4 py-2.5 min-h-[44px] focus:border-emerald-700">
                            <option value="">Pilih Program</option>
                            @foreach($programs as $prg)
                                <option value="{{ $prg->id }}">{{ $prg->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-1">
                        <label for="add_trx_payment">Via Bayar</label>
                        <select id="add_trx_payment" name="payment_via_id" class="w-full border border-slate-300 rounded-xl px-4 py-2.5 min-h-[44px] focus:border-emerald-700">
                            <option value="">Pilih Via Bayar</option>
                            @foreach($paymentVias as $pv)
                                <option value="{{ $pv->id }}">{{ $pv->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="space-y-1">
                    <label for="add_trx_notes">Keterangan / Doa</label>
                    <textarea id="add_trx_notes" name="notes" rows="2" placeholder="Catatan transaksi atau titipan doa donatur..." class="w-full border border-slate-300 rounded-xl p-3 focus:border-emerald-700"></textarea>
                </div>
                <div class="flex justify-end gap-3 pt-3">
                    <button type="button" @click="showAddModal = false" class="px-5 py-2.5 min-h-[44px] rounded-xl border border-slate-300 text-slate-700 hover:bg-slate-50 font-bold">Batal</button>
                    <button type="submit" class="px-5 py-2.5 min-h-[44px] rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold shadow-md">Simpan Transaksi</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Edit Transaksi --}}
    <div x-show="showEditModal" x-cloak @keydown.escape.window="showEditModal = false" role="dialog" aria-modal="true" aria-labelledby="edit-trx-title" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60" @click="showEditModal = false"></div>
        <div class="relative z-10 bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl space-y-5">
            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                <h2 id="edit-trx-title" class="text-base font-black text-slate-900 uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-pen-to-square text-emerald-700"></i> Edit Data Transaksi
                </h2>
                <button type="button" @click="showEditModal = false" aria-label="Tutup modal edit transaksi" class="w-11 h-11 inline-flex items-center justify-center rounded-xl text-slate-600 hover:bg-slate-100 text-lg"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form :action="'{{ url('admin/transactions') }}/' + editData.id + '/update'" method="POST" class="space-y-4 text-xs font-semibold text-slate-700">
                @csrf
                @method('PUT')
                <div class="space-y-1">
                    <label for="edit_trx_name">Nama Donatur <span class="text-rose-700">*</span></label>
                    <input id="edit_trx_name" type="text" name="donor_name" x-model="editData.donor_name" required class="w-full border border-slate-300 rounded-xl px-4 py-2.5 min-h-[44px] focus:border-emerald-700">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label for="edit_trx_amount">Nominal Donasi (Rp) <span class="text-rose-700">*</span></label>
                        <input id="edit_trx_amount" type="number" name="amount" x-model="editData.amount" required class="w-full border border-slate-300 rounded-xl px-4 py-2.5 min-h-[44px] focus:border-emerald-700">
                    </div>
                    <div class="space-y-1">
                        <label for="edit_trx_campaign">Program Donasi <span class="text-rose-700">*</span></label>
                        <select id="edit_trx_campaign" name="campaign_id" x-model="editData.campaign_id" required class="w-full border border-slate-300 rounded-xl px-4 py-2.5 min-h-[44px] focus:border-emerald-700">
                            <option value="">Pilih Program</option>
                            @foreach($programs as $prg)
                                <option value="{{ $prg->id }}">{{ $prg->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="space-y-1">
                    <label for="edit_trx_payment">Via Bayar</label>
                    <select id="edit_trx_payment" name="payment_via_id" x-model="editData.payment_via_id" class="w-full border border-slate-300 rounded-xl px-4 py-2.5 min-h-[44px] focus:border-emerald-700">
                        <option value="">Pilih Via Bayar</option>
                        @foreach($paymentVias as $pv)
                            <option value="{{ $pv->id }}">{{ $pv->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-1">
                    <label for="edit_trx_notes">Keterangan / Doa</label>
                    <textarea id="edit_trx_notes" name="notes" x-model="editData.notes" rows="2" class="w-full border border-slate-300 rounded-xl p-3 focus:border-emerald-700"></textarea>
                </div>
                <div class="flex justify-end gap-3 pt-3">
                    <button type="button" @click="showEditModal = false" class="px-5 py-2.5 min-h-[44px] rounded-xl border border-slate-300 text-slate-700 hover:bg-slate-50 font-bold">Batal</button>
                    <button type="submit" class="px-5 py-2.5 min-h-[44px] rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold shadow-md">Perbarui Transaksi</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Delete Transaksi --}}
    <div x-show="showDeleteModal" x-cloak @keydown.escape.window="showDeleteModal = false" role="dialog" aria-modal="true" aria-labelledby="delete-trx-title" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60" @click="showDeleteModal = false"></div>
        <div class="relative z-10 bg-white rounded-3xl max-w-sm w-full p-6 shadow-2xl space-y-4 text-center">
            <div class="w-14 h-14 bg-rose-50 text-rose-700 rounded-full flex items-center justify-center text-2xl mx-auto">
                <i class="fa-solid fa-trash-can"></i>
            </div>
            <div class="space-y-1">
                <h2 id="delete-trx-title" class="text-base font-black text-slate-900">Konfirmasi Hapus</h2>
                <p class="text-xs text-slate-600 font-medium">Apakah Anda yakin ingin menghapus transaksi <span class="font-bold text-slate-900" x-text="selectedTrxId"></span>?</p>
            </div>
            <form :action="'{{ url('admin/transactions') }}/' + selectedId + '/delete'" method="POST" class="flex justify-center gap-3 pt-2">
                @csrf
                @method('DELETE')
                <button type="button" @click="showDeleteModal = false" class="px-5 py-2.5 min-h-[44px] rounded-xl border border-slate-300 text-slate-700 hover:bg-slate-50 font-bold text-xs">Batal</button>
                <button type="submit" class="px-5 py-2.5 min-h-[44px] rounded-xl bg-rose-700 hover:bg-rose-800 text-white font-bold text-xs shadow-md">Ya, Hapus Data</button>
            </form>
        </div>
    </div>

    {{-- Modal Import CSV --}}
    <div x-show="showImportModal" x-cloak @keydown.escape.window="showImportModal = false" role="dialog" aria-modal="true" aria-labelledby="import-trx-title" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60" @click="showImportModal = false"></div>
        <div class="relative z-10 bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl space-y-4">
            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                <h2 id="import-trx-title" class="text-base font-black text-slate-900 uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-file-import text-emerald-700"></i> Import Data Transaksi (CSV)
                </h2>
                <button type="button" @click="showImportModal = false" aria-label="Tutup modal import transaksi" class="w-11 h-11 inline-flex items-center justify-center rounded-xl text-slate-600 hover:bg-slate-100 text-lg"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form action="{{ route('admin.transactions.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs font-semibold text-slate-700">
                @csrf
                <div class="space-y-1">
                    <label for="import_trx_file">Pilih File Excel / CSV (.csv)</label>
                    <input id="import_trx_file" type="file" name="file" accept=".csv" required class="w-full border border-slate-300 rounded-xl px-4 py-2.5 min-h-[44px]">
                </div>
                <p class="text-xs text-slate-600 italic">Format kolom CSV: No, ID Transaksi, ID Donatur, Tgl Donasi, Nama Lengkap, Nominal Donasi</p>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" @click="showImportModal = false" class="px-5 py-2.5 min-h-[44px] rounded-xl border border-slate-300 text-slate-700 hover:bg-slate-50 font-bold">Batal</button>
                    <button type="submit" class="px-5 py-2.5 min-h-[44px] rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold shadow-md">Upload & Import</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
