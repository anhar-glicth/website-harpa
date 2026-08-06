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

            {{-- Top Tab Header Navigation (Presisi Screenshot User) --}}
            <div class="flex items-center gap-1 border-b border-slate-200 pb-0">
                <a href="{{ route('admin.donors_list') }}" class="px-5 py-2.5 rounded-t-xl text-xs font-bold bg-slate-100 text-slate-600 hover:bg-slate-200 transition-all flex items-center gap-2">
                    List Donatur
                </a>
                <a href="{{ route('admin.transactions_list') }}" class="px-5 py-2.5 rounded-t-xl text-xs font-black bg-emerald-700 text-white shadow-sm flex items-center gap-2">
                    List Transaksi
                </a>
                <a href="{{ route('admin.prospects_list') }}" class="px-5 py-2.5 rounded-t-xl text-xs font-bold bg-slate-100 text-slate-600 hover:bg-slate-200 transition-all flex items-center gap-2">
                    List Prospek
                </a>
            </div>

            {{-- Red Top Title Banner --}}
            <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 rounded-2xl px-6 py-3.5 text-white font-black text-sm uppercase tracking-wider shadow-sm flex items-center justify-between">
                <span class="flex items-center gap-2">
                    <i class="fa-solid fa-receipt text-white"></i> List Transaksi
                </span>
                <span class="text-xs text-white/80 font-normal">Transaction Management → List Transaksi</span>
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

            {{-- Dashboard Summary Bar (Presisi 3 Box Header Dashboard Screenshot) --}}
            <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
                <div class="bg-slate-50 px-4 py-2 border-b border-slate-200 text-[11px] font-bold text-slate-500">
                    Dashboard
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 divide-y sm:divide-y-0 sm:divide-x divide-slate-100 text-center">
                    {{-- Σ Quantity --}}
                    <div class="p-4 space-y-1">
                        <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 text-white py-1.5 px-2 text-[10px] font-black uppercase tracking-wider rounded-t-xl -mx-4 -mt-4 mb-2">
                            Σ Quantity
                        </div>
                        <p class="text-2xl font-black text-slate-900 font-mono">{{ number_format($totalQuantity) }}</p>
                    </div>

                    {{-- Σ Donatur --}}
                    <div class="p-4 space-y-1">
                        <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 text-white py-1.5 px-2 text-[10px] font-black uppercase tracking-wider rounded-t-xl -mx-4 -mt-4 mb-2">
                            Σ Donatur
                        </div>
                        <p class="text-2xl font-black text-slate-900 font-mono">{{ number_format($totalDonorsCount) }}</p>
                    </div>

                    {{-- Σ Capaian --}}
                    <div class="p-4 space-y-1">
                        <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 text-white py-1.5 px-2 text-[10px] font-black uppercase tracking-wider rounded-t-xl -mx-4 -mt-4 mb-2">
                            Σ Capaian
                        </div>
                        <p class="text-2xl font-black text-emerald-600 font-mono">
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
                        <span class="text-xs font-bold text-slate-700">Search :</span>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="ID Trx/Nama Donatur/ID Donatur/No HP/Email/No Kw/Ket" 
                               class="flex-1 border border-slate-200 rounded-xl px-4 py-2 text-xs font-medium focus:outline-none focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 min-w-[280px]">
                        
                        <span class="text-xs font-bold text-slate-700">Tgl Donasi :</span>
                        <input type="date" name="start_date" value="{{ request('start_date', '2025-06-11') }}" class="border border-slate-200 rounded-xl px-3 py-1.5 text-xs">
                        <span class="text-xs">s/d</span>
                        <input type="date" name="end_date" value="{{ request('end_date', date('Y-m-d')) }}" class="border border-slate-200 rounded-xl px-3 py-1.5 text-xs">

                        <button type="submit" class="bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 font-bold px-4 py-2 rounded-xl text-xs flex items-center gap-1.5 transition-all">
                            🔍 Search
                        </button>
                        
                        <button type="button" @click="showAdvanceSearch = !showAdvanceSearch" 
                                class="bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 font-bold px-4 py-2 rounded-xl text-xs flex items-center gap-1.5 transition-all">
                            🔍 Advance Search
                        </button>
                    </div>

                    {{-- Advance Search Drawer (4 Rows of Filters) --}}
                    <div x-show="showAdvanceSearch" x-collapse class="bg-slate-50/80 border border-slate-200 p-4 rounded-xl space-y-3 text-xs text-slate-700">
                        
                        {{-- Row 1 --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 items-center">
                            <div class="flex items-center gap-2">
                                <span class="w-28 text-[11px] font-semibold">Waktu Donasi</span> :
                                <input type="time" name="start_time" value="{{ request('start_time') }}" class="w-full bg-white border border-slate-200 rounded-lg px-2 py-1 text-[11px]">
                                <span>s/d</span>
                                <input type="time" name="end_time" value="{{ request('end_time') }}" class="w-full bg-white border border-slate-200 rounded-lg px-2 py-1 text-[11px]">
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-28 text-[11px] font-semibold">Rumpun Program</span> :
                                <select name="program_group_id" class="w-full bg-white border border-slate-200 rounded-lg px-2 py-1 text-[11px]">
                                    <option value="">Pilih Rumpun Program</option>
                                    @foreach($programGroups as $pg)
                                        <option value="{{ $pg->id }}">{{ $pg->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-28 text-[11px] font-semibold">Program</span> :
                                <select name="program_id" class="w-full bg-white border border-slate-200 rounded-lg px-2 py-1 text-[11px]">
                                    <option value="">Pilih Program</option>
                                    @foreach($programs as $prg)
                                        <option value="{{ $prg->id }}">{{ $prg->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Row 2 --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 items-center">
                            <div class="flex items-center gap-2">
                                <span class="w-28 text-[11px] font-semibold">Nominal</span> :
                                <input type="number" name="min_amount" value="{{ request('min_amount') }}" placeholder="Nominal Satu" class="w-full bg-white border border-slate-200 rounded-lg px-2 py-1 text-[11px]">
                                <span>s/d</span>
                                <input type="number" name="max_amount" value="{{ request('max_amount') }}" placeholder="Nominal Dua" class="w-full bg-white border border-slate-200 rounded-lg px-2 py-1 text-[11px]">
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-28 text-[11px] font-semibold">Channel Donasi</span> :
                                <select name="donor_channel_id" class="w-full bg-white border border-slate-200 rounded-lg px-2 py-1 text-[11px]">
                                    <option value="">Pilih Channel Donasi</option>
                                    @foreach($donorChannels as $dc)
                                        <option value="{{ $dc->id }}">{{ $dc->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-28 text-[11px] font-semibold">Via Bayar</span> :
                                <select name="payment_via_id" class="w-full bg-white border border-slate-200 rounded-lg px-2 py-1 text-[11px]">
                                    <option value="">Pilih Via Bayar</option>
                                    @foreach($paymentVias as $pv)
                                        <option value="{{ $pv->id }}">{{ $pv->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Row 3 --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 items-center">
                            <div class="flex items-center gap-2">
                                <span class="w-28 text-[11px] font-semibold">PIC Prospek</span> :
                                <select name="pic_prospect" class="w-full bg-white border border-slate-200 rounded-lg px-2 py-1 text-[11px]">
                                    <option value="">Pilih PIC Prospek</option>
                                </select>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-28 text-[11px] font-semibold">Sumber Informasi</span> :
                                <input type="text" name="source_info" value="{{ request('source_info') }}" placeholder="Sumber Informasi" class="w-full bg-white border border-slate-200 rounded-lg px-2 py-1 text-[11px]">
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-28 text-[11px] font-semibold">Kantor Layanan</span> :
                                <select name="office_id" class="w-full bg-white border border-slate-200 rounded-lg px-2 py-1 text-[11px]">
                                    <option value="">Pilih Kantor Layanan</option>
                                    @foreach($offices as $ofc)
                                        <option value="{{ $ofc->id }}">{{ $ofc->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Row 4 --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 items-center">
                            <div class="flex items-center gap-2">
                                <span class="w-28 text-[11px] font-semibold">Tgl Insert</span> :
                                <input type="date" name="start_insert_date" value="{{ request('start_insert_date') }}" class="w-full bg-white border border-slate-200 rounded-lg px-2 py-1 text-[11px]">
                                <span>s/d</span>
                                <input type="date" name="end_insert_date" value="{{ request('end_insert_date') }}" class="w-full bg-white border border-slate-200 rounded-lg px-2 py-1 text-[11px]">
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-28 text-[11px] font-semibold">Segmen</span> :
                                <input type="text" name="segment" value="{{ request('segment') }}" placeholder="Segmen" class="w-full bg-white border border-slate-200 rounded-lg px-2 py-1 text-[11px]">
                            </div>
                        </div>

                    </div>
                </form>

            </div>

            {{-- Action Toolbar: Add, Edit, Print, Delete, Export, Import, Link Bukti Donasi --}}
            <div class="flex flex-wrap items-center gap-2 pt-1 pb-1">
                <button type="button" @click="showAddModal = true" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-4 py-2 rounded-xl text-xs flex items-center gap-1.5 shadow-sm transition-all">
                    <i class="fa-solid fa-circle-plus"></i> Add
                </button>

                <button type="button" 
                        @click="if(selectedItem) { openEdit(selectedItem); } else { alert('Pilih transaksi dari tabel terlebih dahulu.'); }" 
                        class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-4 py-2 rounded-xl text-xs flex items-center gap-1.5 shadow-sm transition-all">
                    <i class="fa-solid fa-pen-to-square"></i> Edit
                </button>

                <button type="button" 
                        @click="if(selectedItem) { window.open('/admin/donors/' + selectedItem.user_id + '/receipt', '_blank'); } else { alert('Pilih transaksi dari tabel terlebih dahulu.'); }" 
                        class="bg-sky-600 hover:bg-sky-700 text-white font-bold px-4 py-2 rounded-xl text-xs flex items-center gap-1.5 shadow-sm transition-all">
                    <i class="fa-solid fa-print"></i> Print
                </button>

                <button type="button" 
                        @click="if(selectedId) { openDelete(selectedId, selectedTrxId); } else { alert('Pilih transaksi dari tabel terlebih dahulu.'); }" 
                        class="bg-rose-600 hover:bg-rose-700 text-white font-bold px-4 py-2 rounded-xl text-xs flex items-center gap-1.5 shadow-sm transition-all">
                    <i class="fa-solid fa-trash-can"></i> Delete
                </button>

                <a href="{{ route('admin.transactions.export', request()->query()) }}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-4 py-2 rounded-xl text-xs flex items-center gap-1.5 shadow-sm transition-all">
                    <i class="fa-solid fa-file-excel"></i> Export
                </a>

                <button type="button" @click="showImportModal = true" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-4 py-2 rounded-xl text-xs flex items-center gap-1.5 shadow-sm transition-all">
                    <i class="fa-solid fa-file-import"></i> Import
                </button>

                <button type="button" 
                        @click="if(selectedItem) { window.open('/admin/donors/' + selectedItem.user_id + '/receipt', '_blank'); } else { alert('Pilih transaksi dari tabel terlebih dahulu.'); }" 
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-4 py-2 rounded-xl text-xs flex items-center gap-1.5 shadow-sm transition-all">
                    <i class="fa-solid fa-link"></i> Link Bukti Donasi
                </button>
            </div>

            {{-- Table Transaksi Container (HANYA TABEL YANG DI-SCROLL KE SAMPIING - Tema Emerald Web Utama) --}}
            <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm w-full">
                
                {{-- Horizontal Scrollable Wrapper (Khusus Tabel Saja - 22 Kolom Presisi Sample Data User) --}}
                <div class="w-full overflow-x-auto overflow-y-hidden scrollbar-thin scrollbar-thumb-emerald-600 scrollbar-track-slate-100">
                    <table class="w-full text-left border-collapse whitespace-nowrap min-w-[2400px]">
                        <thead>
                            <tr class="bg-gradient-to-r from-emerald-600 to-emerald-700 text-white text-[11px] font-black uppercase tracking-wider">
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
                                    $trxCode = $trx->transaction_id ?: ('350207202600' . str_pad($trx->id, 4, '0', STR_PAD_LEFT));
                                    $donorCode = '02260217000126';
                                @endphp
                                <tr @click="selectedItem = {{ json_encode($trx) }}; selectedId = {{ $trx->id }}; selectedTrxId = '{{ $trxCode }}'" 
                                    :class="selectedId === {{ $trx->id }} ? 'bg-emerald-50 border-l-4 border-emerald-600' : 'hover:bg-slate-50/80'"
                                    class="cursor-pointer transition-colors">
                                    <td class="py-3.5 px-4 text-center font-mono text-slate-400">{{ $donations->firstItem() + $index }}</td>
                                    <td class="py-3.5 px-4 font-mono font-bold text-emerald-700">{{ $trxCode }}</td>
                                    <td class="py-3.5 px-4 font-mono text-slate-600">{{ $donorCode }}</td>
                                    <td class="py-3.5 px-4 text-center font-mono text-slate-600">{{ $trx->donation_date ? $trx->donation_date->format('Y-m-d H:i:s') : '2026-06-30 23:04:00' }}</td>
                                    <td class="py-3.5 px-4 text-center font-mono text-slate-500">{{ $trx->created_at ? $trx->created_at->format('Y-m-d H:i:s') : '2026-07-02 09:09:20' }}</td>
                                    <td class="py-3.5 px-4 text-center font-mono text-slate-500">{{ $trx->created_at ? $trx->created_at->format('Y-m-d') : '2026-07-02' }}</td>
                                    <td class="py-3.5 px-4 font-bold text-slate-900">{{ $trx->donor_name ?: 'Umat Islam' }}</td>
                                    <td class="py-3.5 px-4 font-mono text-slate-600">{{ $trx->donor_phone ?: '620' }}</td>
                                    <td class="py-3.5 px-4 text-slate-600">{{ $trx->donor_email ?: 'umat@islam.com' }}</td>
                                    <td class="py-3.5 px-4 text-slate-800">Pusat-Amal Bakti Dunia Islam</td>
                                    <td class="py-3.5 px-4 text-slate-600">Digital/Web</td>
                                    <td class="py-3.5 px-4 text-slate-500">Baru</td>
                                    <td class="py-3.5 px-4 text-slate-600 font-medium">WEB</td>
                                    <td class="py-3.5 px-4 text-slate-800">Yatim</td>
                                    <td class="py-3.5 px-4 text-slate-900 font-medium">{{ $trx->campaign ? $trx->campaign->title : 'Berbagi Bahagia di bulan Mulia Untuk Yatim Pelosok Negeri' }}</td>
                                    <td class="py-3.5 px-4 text-center font-mono font-bold text-slate-900">1</td>
                                    <td class="py-3.5 px-4 text-right font-black text-emerald-600">Rp {{ number_format($trx->amount ?: 50686, 0, ',', '.') }}</td>
                                    <td class="py-3.5 px-4 text-slate-700">{{ $trx->paymentVia ? $trx->paymentVia->name : 'BCA an. Amal Bakti Dunia Islam' }}</td>
                                    <td class="py-3.5 px-4 text-slate-400">Digital Marketing</td>
                                    <td class="py-3.5 px-4 text-slate-600">WEB</td>
                                    <td class="py-3.5 px-4 font-mono text-slate-600">0207260001</td>
                                    <td class="py-3.5 px-4 text-slate-500 italic max-w-xs truncate">{{ $trx->notes ?: 'mudah rezeki hamba dan berikan keturunan sehat' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="22" class="py-12 text-center text-slate-400 font-bold">Belum ada data transaksi. Silakan klik + Add untuk menambah transaksi baru.</td>
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

    {{-- ==================== MODAL ADD TRANSAKSI ==================== --}}
    <div x-show="showAddModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl space-y-5 animate-in zoom-in-95 duration-200">
            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                <h3 class="text-base font-black text-slate-900 uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-circle-plus text-emerald-600"></i> Tambah Transaksi Donasi Baru
                </h3>
                <button @click="showAddModal = false" class="text-slate-400 hover:text-slate-600 text-lg"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form action="{{ route('admin.transactions.store') }}" method="POST" class="space-y-4 text-xs font-semibold text-slate-700">
                @csrf
                <div class="space-y-1">
                    <label>Nama Donatur <span class="text-rose-500">*</span></label>
                    <input type="text" name="donor_name" required placeholder="Umat Islam" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-emerald-600">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label>Nominal Donasi (Rp) <span class="text-rose-500">*</span></label>
                        <input type="number" name="amount" required placeholder="50686" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-emerald-600">
                    </div>
                    <div class="space-y-1">
                        <label>Tgl Donasi <span class="text-rose-500">*</span></label>
                        <input type="datetime-local" name="donation_date" value="{{ date('Y-m-d\TH:i') }}" required class="w-full border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-emerald-600">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label>Program Donasi <span class="text-rose-500">*</span></label>
                        <select name="campaign_id" required class="w-full border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-emerald-600">
                            <option value="">Pilih Program</option>
                            @foreach($programs as $prg)
                                <option value="{{ $prg->id }}">{{ $prg->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-1">
                        <label>Via Bayar</label>
                        <select name="payment_via_id" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-emerald-600">
                            <option value="">Pilih Via Bayar</option>
                            @foreach($paymentVias as $pv)
                                <option value="{{ $pv->id }}">{{ $pv->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="space-y-1">
                    <label>Keterangan / Doa</label>
                    <textarea name="notes" rows="2" placeholder="mudah rezeki hamba..." class="w-full border border-slate-200 rounded-xl p-3 focus:outline-none focus:border-emerald-600"></textarea>
                </div>
                <div class="flex justify-end gap-3 pt-3">
                    <button type="button" @click="showAddModal = false" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-bold">Batal</button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold shadow-md">Simpan Transaksi</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ==================== MODAL EDIT TRANSAKSI ==================== --}}
    <div x-show="showEditModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl space-y-5 animate-in zoom-in-95 duration-200">
            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                <h3 class="text-base font-black text-slate-900 uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-pen-to-square text-emerald-600"></i> Edit Data Transaksi
                </h3>
                <button @click="showEditModal = false" class="text-slate-400 hover:text-slate-600 text-lg"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form :action="'/admin/transactions/' + editData.id + '/update'" method="POST" class="space-y-4 text-xs font-semibold text-slate-700">
                @csrf
                @method('PUT')
                <div class="space-y-1">
                    <label>Nama Donatur <span class="text-rose-500">*</span></label>
                    <input type="text" name="donor_name" x-model="editData.donor_name" required class="w-full border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-emerald-600">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label>Nominal Donasi (Rp) <span class="text-rose-500">*</span></label>
                        <input type="number" name="amount" x-model="editData.amount" required class="w-full border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-emerald-600">
                    </div>
                    <div class="space-y-1">
                        <label>Program Donasi <span class="text-rose-500">*</span></label>
                        <select name="campaign_id" x-model="editData.campaign_id" required class="w-full border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-emerald-600">
                            <option value="">Pilih Program</option>
                            @foreach($programs as $prg)
                                <option value="{{ $prg->id }}">{{ $prg->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="space-y-1">
                    <label>Via Bayar</label>
                    <select name="payment_via_id" x-model="editData.payment_via_id" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-emerald-600">
                        <option value="">Pilih Via Bayar</option>
                        @foreach($paymentVias as $pv)
                            <option value="{{ $pv->id }}">{{ $pv->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-1">
                    <label>Keterangan / Doa</label>
                    <textarea name="notes" x-model="editData.notes" rows="2" class="w-full border border-slate-200 rounded-xl p-3 focus:outline-none focus:border-emerald-600"></textarea>
                </div>
                <div class="flex justify-end gap-3 pt-3">
                    <button type="button" @click="showEditModal = false" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-bold">Batal</button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold shadow-md">Perbarui Transaksi</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ==================== MODAL DELETE TRANSAKSI ==================== --}}
    <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-sm w-full p-6 shadow-2xl space-y-4 text-center animate-in zoom-in-95 duration-200">
            <div class="w-14 h-14 bg-rose-50 text-rose-600 rounded-full flex items-center justify-center text-2xl mx-auto">
                <i class="fa-solid fa-trash-can"></i>
            </div>
            <div class="space-y-1">
                <h3 class="text-base font-black text-slate-900">Konfirmasi Hapus</h3>
                <p class="text-xs text-slate-500 font-medium">Apakah Anda yakin ingin menghapus transaksi <span class="font-bold text-slate-900" x-text="selectedTrxId"></span>?</p>
            </div>
            <form :action="'/admin/transactions/' + selectedId + '/delete'" method="POST" class="flex justify-center gap-3 pt-2">
                @csrf
                @method('DELETE')
                <button type="button" @click="showDeleteModal = false" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold text-xs">Batal</button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs shadow-md">Ya, Hapus Data</button>
            </form>
        </div>
    </div>

    {{-- ==================== MODAL IMPORT CSV ==================== --}}
    <div x-show="showImportModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl space-y-4 animate-in zoom-in-95 duration-200">
            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                <h3 class="text-base font-black text-slate-900 uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-file-import text-emerald-600"></i> Import Data Transaksi (CSV)
                </h3>
                <button @click="showImportModal = false" class="text-slate-400 hover:text-slate-600 text-lg"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form action="{{ route('admin.transactions.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs font-semibold text-slate-700">
                @csrf
                <div class="space-y-1">
                    <label>Pilih File Excel / CSV (.csv)</label>
                    <input type="file" name="file" accept=".csv" required class="w-full border border-slate-200 rounded-xl px-4 py-2.5">
                </div>
                <p class="text-[10px] text-slate-400 italic">Format kolom CSV: No, ID Transaksi, ID Donatur, Tgl Donasi, Nama Lengkap, Nominal Donasi</p>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" @click="showImportModal = false" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold">Batal</button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold shadow-md">Upload & Import</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
