@extends('layouts.app')

@section('content')
<section class="max-w-7xl mx-auto px-6 py-20" x-data="{
    showAddModal: false,
    showEditModal: false,
    showDeleteModal: false,
    showImportModal: false,
    showAdvanceSearch: {{ (request('program_id') || request('city') || request('province') || request('asnaf') || request('status')) ? 'true' : 'false' }},
    selectedId: null,
    selectedCode: '',
    editData: {
        id: null,
        distribution_code: '',
        program_id: '',
        recipient_name: '',
        pic_name: '',
        pic_phone: '',
        amount: '',
        pm_count: 1,
        distribution_date: '',
        city: '',
        province: '',
        asnaf: '',
        source_of_fund: 'Infak/Shodaqoh',
        status: 'Tersalur',
        notes: ''
    },
    openEdit(item) {
        this.editData = {
            id: item.id,
            distribution_code: item.distribution_code ?? ('DIS-' + item.id),
            program_id: item.program_id,
            recipient_name: item.recipient_name ?? '',
            pic_name: item.pic_name ?? item.recipient_name ?? '',
            pic_phone: item.pic_phone ?? '',
            amount: item.amount,
            pm_count: item.pm_count ?? 1,
            distribution_date: item.distribution_date ? item.distribution_date.substring(0, 10) : '',
            city: item.city ?? '',
            province: item.province ?? '',
            asnaf: item.asnaf ?? '',
            source_of_fund: item.source_of_fund ?? 'Infak/Shodaqoh',
            status: item.status ?? 'Tersalur',
            notes: item.notes ?? ''
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
            @include('admin.sidebar', ['active' => 'distributions'])
        </aside>

        {{-- Main Content --}}
        <div class="space-y-6">

            {{-- Header Card --}}
            <div class="bg-white border border-slate-100 rounded-3xl p-6 sm:p-8 shadow-sm flex items-center justify-between gap-4">
                <div class="space-y-1">
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight">List Penyaluran</h1>
                    <p class="text-xs font-semibold text-slate-600 uppercase tracking-widest">Distribusi Management / List Penyaluran Bantuan</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-700 shadow-sm">
                    <i class="fa-solid fa-truck-ramp-box text-lg"></i>
                </div>
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

            {{-- Dashboard Info Banner --}}
            <div class="bg-white border border-emerald-100 rounded-3xl overflow-hidden shadow-sm">
                <div class="bg-emerald-700 px-6 py-2.5 text-center text-white text-xs font-black uppercase tracking-wider">
                    Informasi Penyaluran
                </div>
                <div class="p-6 text-center">
                    @if($distributions->total() == 0)
                        <div class="py-4 space-y-2">
                            <h2 class="text-xl font-bold text-slate-700">Data Tidak Ditemukan</h2>
                            <p class="text-xs text-slate-600 font-semibold">Belum ada data penyaluran yang sesuai dengan filter atau kata kunci pencarian Anda.</p>
                        </div>
                    @else
                        <div class="flex flex-wrap items-center justify-around gap-4 text-slate-700">
                            <div>
                                <p class="text-xs font-black uppercase tracking-wider text-slate-600">Total Penyaluran Terfilter</p>
                                <p class="text-xl font-black text-emerald-700">Rp {{ number_format($totalFilteredAmount, 0, ',', '.') }}</p>
                            </div>
                            <div class="h-8 w-px bg-slate-200 hidden sm:block"></div>
                            <div>
                                <p class="text-xs font-black uppercase tracking-wider text-slate-600">Jumlah Transaksi</p>
                                <p class="text-xl font-black text-slate-900">{{ number_format($distributions->total()) }} Laporan</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Filter & Toolbar Container --}}
            <div class="bg-white border border-slate-100 rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">

                {{-- Filter Form --}}
                <form method="GET" action="{{ route('admin.distributions') }}" class="space-y-4 pb-5 border-b border-slate-100">

                    {{-- Primary Filter Row (Search + Tgl Penyaluran + Action Buttons) --}}
                    <div class="flex flex-wrap items-center gap-3 text-xs font-bold text-slate-700">

                        {{-- Search Input --}}
                        <div class="flex items-center gap-1.5 min-w-[260px] flex-1">
                            <label for="dist_list_search" class="text-slate-600 font-bold whitespace-nowrap">Search :</label>
                            <input id="dist_list_search" type="text"
                                   name="search"
                                   value="{{ request('search') }}"
                                   placeholder="ID Penyaluran/Nama/Kontak PIC/Program/Alamat"
                                   class="w-full px-3.5 py-2.5 min-h-[44px] bg-slate-50 border border-slate-300 rounded-xl text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 placeholder:text-slate-500">
                        </div>

                        {{-- Date Range --}}
                        <div class="flex items-center gap-1.5 flex-wrap">
                            <label for="dist_list_start" class="text-slate-600 font-bold whitespace-nowrap">Tgl Penyaluran :</label>
                            <input id="dist_list_start" type="date"
                                   name="start_date"
                                   value="{{ request('start_date') }}"
                                   class="px-3 py-2 min-h-[44px] bg-slate-50 border border-slate-300 rounded-xl text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500">
                            <span class="text-slate-600 font-bold">s/d</span>
                            <input id="dist_list_end" type="date"
                                   name="end_date"
                                   aria-label="Tanggal akhir penyaluran"
                                   value="{{ request('end_date') }}"
                                   class="px-3 py-2 min-h-[44px] bg-slate-50 border border-slate-300 rounded-xl text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500">
                        </div>

                        {{-- Search & Advance Search Buttons --}}
                        <div class="flex items-center gap-2 ml-auto">
                            <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 min-h-[44px] bg-slate-900 hover:bg-slate-950 text-white rounded-xl text-xs font-bold transition-colors shadow-sm">
                                <i class="fa-solid fa-magnifying-glass text-xs"></i> Search
                            </button>
                            <button type="button" @click="showAdvanceSearch = !showAdvanceSearch" :aria-expanded="showAdvanceSearch.toString()"
                                    class="inline-flex items-center gap-1.5 px-3.5 py-2 min-h-[44px] border border-slate-300 bg-slate-50 hover:bg-slate-100 text-slate-700 rounded-xl text-xs font-bold transition-colors">
                                <i class="fa-solid fa-sliders text-xs"></i> Advance Search
                            </button>
                            <a href="{{ route('admin.distributions') }}" aria-label="Reset Filter" class="w-11 h-11 inline-flex items-center justify-center bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl transition-colors" title="Reset Filter">
                                <i class="fa-solid fa-rotate text-xs"></i>
                            </a>
                        </div>

                    </div>

                    {{-- Advance Search Expandable Drawer --}}
                    <div x-show="showAdvanceSearch" x-collapse class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 pt-3 border-t border-slate-100">
                        <div>
                            <label for="dist_adv_program" class="block text-xs font-black uppercase text-slate-600 mb-1">Master Program</label>
                            <select id="dist_adv_program" name="program_id" class="w-full px-3 py-2 min-h-[44px] bg-slate-50 border border-slate-300 rounded-xl text-xs font-semibold text-slate-800">
                                <option value="">-- Semua Program --</option>
                                @foreach($programs as $prog)
                                    <option value="{{ $prog->id }}" {{ request('program_id') == $prog->id ? 'selected' : '' }}>
                                        {{ $prog->name }} {{ $prog->programGroup ? '(' . $prog->programGroup->name . ')' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="dist_adv_city" class="block text-xs font-black uppercase text-slate-600 mb-1">Kota / Kabupaten</label>
                            <input id="dist_adv_city" type="text" name="city" value="{{ request('city') }}" placeholder="Contoh: Ende"
                                   class="w-full px-3 py-2 min-h-[44px] bg-slate-50 border border-slate-300 rounded-xl text-xs font-semibold text-slate-800">
                        </div>
                        <div>
                            <label for="dist_adv_province" class="block text-xs font-black uppercase text-slate-600 mb-1">Provinsi</label>
                            <input id="dist_adv_province" type="text" name="province" value="{{ request('province') }}" placeholder="Contoh: Nusa Tenggara Timur"
                                   class="w-full px-3 py-2 min-h-[44px] bg-slate-50 border border-slate-300 rounded-xl text-xs font-semibold text-slate-800">
                        </div>
                        <div>
                            <label for="dist_adv_status" class="block text-xs font-black uppercase text-slate-600 mb-1">Status Penyaluran</label>
                            <select id="dist_adv_status" name="status" class="w-full px-3 py-2 min-h-[44px] bg-slate-50 border border-slate-300 rounded-xl text-xs font-semibold text-slate-800">
                                <option value="">-- Semua Status --</option>
                                <option value="Tersalur" {{ request('status') === 'Tersalur' ? 'selected' : '' }}>Tersalur</option>
                                <option value="Proses" {{ request('status') === 'Proses' ? 'selected' : '' }}>Proses</option>
                            </select>
                        </div>
                    </div>

                </form>

                {{-- Toolbar Actions --}}
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div class="flex flex-wrap items-center gap-2.5">
                        {{-- Add Button --}}
                        <button type="button" @click="showAddModal = true"
                                class="inline-flex items-center gap-1.5 px-4 py-2.5 min-h-[44px] bg-emerald-700 hover:bg-emerald-800 text-white rounded-2xl text-xs font-black uppercase tracking-wider shadow-lg shadow-emerald-700/20 transition-colors">
                            <i class="fa-solid fa-plus text-xs"></i> Add
                        </button>

                        {{-- Export Button --}}
                        <a href="{{ route('admin.distributions.export', request()->query()) }}"
                           class="inline-flex items-center gap-1.5 px-4 py-2.5 min-h-[44px] bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-2xl text-xs font-bold transition-colors shadow-sm">
                            <i class="fa-solid fa-file-excel text-emerald-700"></i> Export
                        </a>

                        {{-- Import Button --}}
                        <button type="button" @click="showImportModal = true"
                                class="inline-flex items-center gap-1.5 px-4 py-2.5 min-h-[44px] bg-sky-50 text-sky-800 border border-sky-300 hover:bg-sky-100 rounded-2xl text-xs font-bold transition-colors shadow-sm">
                            <i class="fa-solid fa-file-import text-sky-700"></i> Import
                        </button>
                    </div>

                    <div class="text-xs font-semibold text-slate-600">
                        Menampilkan <span class="font-bold text-slate-900">{{ $distributions->count() }}</span> dari <span class="font-bold text-slate-900">{{ $distributions->total() }}</span> Penyaluran
                    </div>
                </div>

                {{-- Table --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 text-xs font-black uppercase tracking-wider text-slate-600">
                                <th class="pb-4 w-8">#</th>
                                <th class="pb-4">ID Penyaluran</th>
                                <th class="pb-4">Tgl Penyaluran</th>
                                <th class="pb-4">Rilis Penyaluran</th>
                                <th class="pb-4">Nama PIC</th>
                                <th class="pb-4">Kontak PIC</th>
                                <th class="pb-4">Tgl Insert</th>
                                <th class="pb-4">Kota/Kab</th>
                                <th class="pb-4">Provinsi</th>
                                <th class="pb-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-xs font-semibold text-slate-700">
                            @forelse($distributions as $index => $item)
                                <tr class="border-b border-slate-50 hover:bg-slate-50/60 transition-colors group">
                                    <td class="py-4 text-slate-600 font-bold">
                                        {{ $distributions->firstItem() + $index }}
                                    </td>
                                    <td class="py-4 font-mono font-bold text-slate-900">
                                        <span class="inline-flex items-center px-2 py-0.5 bg-slate-100 rounded text-xs">
                                            {{ $item->distribution_code ?: ('DIS-' . $item->id) }}
                                        </span>
                                    </td>
                                    <td class="py-4 text-slate-600">
                                        {{ $item->distribution_date ? $item->distribution_date->format('d/m/Y') : '-' }}
                                    </td>
                                    <td class="py-4 max-w-[200px]">
                                        <p class="font-bold text-slate-900 truncate">{{ $item->program ? $item->program->name : ($item->campaign ? $item->campaign->title : 'Program Umum') }}</p>
                                        <p class="text-xs text-emerald-700 font-black">Rp {{ number_format($item->amount, 0, ',', '.') }}</p>
                                    </td>
                                    <td class="py-4 font-bold text-slate-800">
                                        {{ $item->pic_name ?: $item->recipient_name ?: '-' }}
                                    </td>
                                    <td class="py-4 font-mono text-slate-600">
                                        {{ $item->pic_phone ?: '-' }}
                                    </td>
                                    <td class="py-4 text-slate-600 text-xs">
                                        {{ $item->created_at ? $item->created_at->format('d/m/Y H:i') : '-' }}
                                    </td>
                                    <td class="py-4 text-slate-600">
                                        {{ $item->city ?: '-' }}
                                    </td>
                                    <td class="py-4 text-slate-600">
                                        {{ $item->province ?: '-' }}
                                    </td>
                                    <td class="py-4">
                                        <div class="flex items-center justify-end gap-1.5">
                                            <button @click="openEdit({{ json_encode($item) }})"
                                                    class="p-1.5 text-amber-600 hover:bg-amber-50 rounded-lg transition-all" title="Edit">
                                                <i class="fa-solid fa-pen-to-square text-sm"></i>
                                            </button>
                                            <button @click="openDelete({{ $item->id }}, '{{ addslashes($item->distribution_code ?: ('DIS-' . $item->id)) }}')"
                                                    class="p-1.5 text-rose-600 hover:bg-rose-50 rounded-lg transition-all" title="Hapus">
                                                <i class="fa-solid fa-trash-can text-sm"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="py-14 text-center text-slate-400 font-bold uppercase tracking-wider">
                                        <i class="fa-solid fa-folder-open text-3xl block text-slate-300 mb-3"></i>
                                        Belum ada data penyaluran.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="pt-2">
                    {{ $distributions->links() }}
                </div>

            </div>{{-- end card --}}

        </div>{{-- end main --}}

    </div>{{-- end grid --}}

    {{-- Modal: Add Penyaluran --}}
    <div x-show="showAddModal"
         x-cloak
         @keydown.escape.window="showAddModal = false"
         role="dialog"
         aria-modal="true"
         aria-labelledby="add-distribution-title"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/50" @click="showAddModal = false"></div>
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-2xl p-6 sm:p-8 space-y-5 z-10 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between">
                <div>
                    <h2 id="add-distribution-title" class="text-lg font-black text-slate-900">Tambah Penyaluran Bantuan</h2>
                    <p class="text-xs text-slate-600 font-semibold mt-0.5">Input laporan penyaluran baru ke sistem</p>
                </div>
                <button type="button" @click="showAddModal = false" aria-label="Tutup modal tambah penyaluran" class="w-11 h-11 inline-flex items-center justify-center rounded-xl hover:bg-slate-100 text-slate-600 transition-colors">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <form method="POST" action="{{ route('admin.distributions.store') }}" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="add_dist_code" class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-1">ID Penyaluran</label>
                        <input id="add_dist_code" type="text" name="distribution_code" placeholder="Otomatis jika dikosongkan (DIS-...)"
                               class="w-full px-3.5 py-2.5 min-h-[44px] bg-slate-50 border border-slate-300 rounded-xl text-xs font-semibold text-slate-800 uppercase placeholder:text-slate-500">
                    </div>
                    <div>
                        <label for="add_dist_program" class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-1">Master Program <span class="text-slate-600 font-normal lowercase">(opsional)</span></label>
                        <select id="add_dist_program" name="program_id" class="w-full px-3.5 py-2.5 min-h-[44px] bg-slate-50 border border-slate-300 rounded-xl text-xs font-semibold text-slate-800">
                            <option value="">-- Non-Program / Penyaluran Umum --</option>
                            @foreach($programs as $prog)
                                <option value="{{ $prog->id }}">{{ $prog->name }} {{ $prog->programGroup ? '(' . $prog->programGroup->name . ')' : '' }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="add_dist_pic_name" class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-1">Nama PIC / Penerima <span class="text-rose-700">*</span></label>
                        <input id="add_dist_pic_name" type="text" name="pic_name" required placeholder="Nama PIC Penyaluran"
                               class="w-full px-3.5 py-2.5 min-h-[44px] bg-slate-50 border border-slate-300 rounded-xl text-xs font-semibold text-slate-800 placeholder:text-slate-500">
                    </div>
                    <div>
                        <label class="block text-xs font-black text-slate-600 uppercase tracking-wider mb-1">Kontak PIC (No. HP/WA)</label>
                        <input type="text" name="pic_phone" placeholder="08xxxxxxxxxx"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none placeholder:text-slate-400">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-black text-slate-600 uppercase tracking-wider mb-1">Rilis Nominal (Rp) <span class="text-rose-500">*</span></label>
                        <input type="number" name="amount" required min="1000" step="1000" placeholder="Contoh: 1000000"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none placeholder:text-slate-400">
                    </div>
                    <div>
                        <label class="block text-xs font-black text-slate-600 uppercase tracking-wider mb-1">Tgl Penyaluran <span class="text-rose-500">*</span></label>
                        <input type="date" name="distribution_date" required value="{{ date('Y-m-d') }}"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-black text-slate-600 uppercase tracking-wider mb-1">Kategori Asnaf</label>
                        <select name="asnaf" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none">
                            <option value="Non Asnaf">Non Asnaf</option>
                            <option value="Fakir">Fakir</option>
                            <option value="Miskin">Miskin</option>
                            <option value="Fisabilillah">Fisabilillah</option>
                            <option value="Mualaf">Mualaf</option>
                            <option value="Ibnusabil">Ibnusabil</option>
                            <option value="Amil">Amil</option>
                            <option value="Riqab">Riqab</option>
                            <option value="Gharimin">Gharimin</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-black text-slate-600 uppercase tracking-wider mb-1">Sumber Dana</label>
                        <select name="source_of_fund" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none">
                            <option value="Infak/Shodaqoh">Infak/Shodaqoh</option>
                            <option value="Program">Program</option>
                            <option value="Zakat">Zakat</option>
                            <option value="Wakaf">Wakaf</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-black text-slate-600 uppercase tracking-wider mb-1">Jumlah PM (Jiwa)</label>
                        <input type="number" name="pm_count" value="1" min="1"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-black text-slate-600 uppercase tracking-wider mb-1">Kota / Kabupaten</label>
                        <input type="text" name="city" placeholder="Contoh: Ende"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none placeholder:text-slate-400">
                    </div>
                    <div>
                        <label class="block text-xs font-black text-slate-600 uppercase tracking-wider mb-1">Provinsi</label>
                        <input type="text" name="province" placeholder="Nusa Tenggara Timur"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none placeholder:text-slate-400">
                    </div>
                    <div>
                        <label class="block text-xs font-black text-slate-600 uppercase tracking-wider mb-1">Status</label>
                        <select name="status" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none">
                            <option value="Tersalur">Tersalur</option>
                            <option value="Proses">Proses</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-600 uppercase tracking-wider mb-1">Keterangan / Notes</label>
                    <textarea name="notes" rows="2" placeholder="Catatan tambahan..."
                              class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none resize-none placeholder:text-slate-400"></textarea>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="button" @click="showAddModal = false"
                            class="flex-1 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-2xl text-xs font-bold transition-all">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-1 py-3 min-h-[44px] bg-emerald-700 hover:bg-emerald-800 text-white rounded-2xl text-xs font-black shadow-lg shadow-emerald-700/20 transition-colors">
                        Simpan Penyaluran
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal: Edit Penyaluran --}}
    <div x-show="showEditModal"
         x-cloak
         @keydown.escape.window="showEditModal = false"
         role="dialog"
         aria-modal="true"
         aria-labelledby="edit-distribution-title"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/50" @click="showEditModal = false"></div>
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-2xl p-6 sm:p-8 space-y-5 z-10 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between">
                <div>
                    <h2 id="edit-distribution-title" class="text-lg font-black text-slate-900">Edit Penyaluran Bantuan</h2>
                    <p class="text-xs text-slate-600 font-semibold mt-0.5">Perbarui data laporan penyaluran</p>
                </div>
                <button type="button" @click="showEditModal = false" aria-label="Tutup modal edit penyaluran" class="w-11 h-11 inline-flex items-center justify-center rounded-xl hover:bg-slate-100 text-slate-600 transition-colors">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <form method="POST" :action="'{{ url('admin/distributions') }}/' + editData.id + '/update'" class="space-y-4">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="edit_dist_code" class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-1">ID Penyaluran</label>
                        <input id="edit_dist_code" type="text" name="distribution_code" x-model="editData.distribution_code" required
                               class="w-full px-3.5 py-2.5 min-h-[44px] bg-slate-50 border border-slate-300 rounded-xl text-xs font-semibold text-slate-800 uppercase">
                    </div>
                    <div>
                        <label for="edit_dist_program" class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-1">Master Program <span class="text-slate-600 font-normal lowercase">(opsional)</span></label>
                        <select id="edit_dist_program" name="program_id" x-model="editData.program_id" class="w-full px-3.5 py-2.5 min-h-[44px] bg-slate-50 border border-slate-300 rounded-xl text-xs font-semibold text-slate-800">
                            <option :value="null">-- Non-Program / Penyaluran Umum --</option>
                            @foreach($programs as $prog)
                                <option value="{{ $prog->id }}">{{ $prog->name }} {{ $prog->programGroup ? '(' . $prog->programGroup->name . ')' : '' }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-black text-slate-600 uppercase tracking-wider mb-1">Nama PIC / Penerima <span class="text-rose-500">*</span></label>
                        <input type="text" name="pic_name" x-model="editData.pic_name" required
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none">
                        <input type="hidden" name="recipient_name" :value="editData.pic_name">
                    </div>
                    <div>
                        <label class="block text-xs font-black text-slate-600 uppercase tracking-wider mb-1">Kontak PIC (No. HP/WA)</label>
                        <input type="text" name="pic_phone" x-model="editData.pic_phone"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-black text-slate-600 uppercase tracking-wider mb-1">Rilis Nominal (Rp) <span class="text-rose-500">*</span></label>
                        <input type="number" name="amount" x-model="editData.amount" required min="1000" step="1000"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-black text-slate-600 uppercase tracking-wider mb-1">Tgl Penyaluran <span class="text-rose-500">*</span></label>
                        <input type="date" name="distribution_date" x-model="editData.distribution_date" required
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-black text-slate-600 uppercase tracking-wider mb-1">Kategori Asnaf</label>
                        <select name="asnaf" x-model="editData.asnaf" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none">
                            <option value="Non Asnaf">Non Asnaf</option>
                            <option value="Fakir">Fakir</option>
                            <option value="Miskin">Miskin</option>
                            <option value="Fisabilillah">Fisabilillah</option>
                            <option value="Mualaf">Mualaf</option>
                            <option value="Ibnusabil">Ibnusabil</option>
                            <option value="Amil">Amil</option>
                            <option value="Riqab">Riqab</option>
                            <option value="Gharimin">Gharimin</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-black text-slate-600 uppercase tracking-wider mb-1">Sumber Dana</label>
                        <select name="source_of_fund" x-model="editData.source_of_fund" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none">
                            <option value="Infak/Shodaqoh">Infak/Shodaqoh</option>
                            <option value="Program">Program</option>
                            <option value="Zakat">Zakat</option>
                            <option value="Wakaf">Wakaf</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-black text-slate-600 uppercase tracking-wider mb-1">Jumlah PM (Jiwa)</label>
                        <input type="number" name="pm_count" x-model="editData.pm_count" min="1"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-black text-slate-600 uppercase tracking-wider mb-1">Kota / Kabupaten</label>
                        <input type="text" name="city" x-model="editData.city"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-black text-slate-600 uppercase tracking-wider mb-1">Provinsi</label>
                        <input type="text" name="province" x-model="editData.province"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-black text-slate-600 uppercase tracking-wider mb-1">Status</label>
                        <select name="status" x-model="editData.status" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none">
                            <option value="Tersalur">Tersalur</option>
                            <option value="Proses">Proses</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-600 uppercase tracking-wider mb-1">Keterangan / Notes</label>
                    <textarea name="notes" rows="2" x-model="editData.notes"
                              class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none resize-none"></textarea>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="button" @click="showEditModal = false"
                            class="flex-1 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-2xl text-xs font-bold transition-all">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-1 py-3 min-h-[44px] bg-emerald-700 hover:bg-emerald-800 text-white rounded-2xl text-xs font-black shadow-lg shadow-emerald-700/20 transition-colors">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal: Delete --}}
    <div x-show="showDeleteModal"
         x-cloak
         @keydown.escape.window="showDeleteModal = false"
         role="dialog"
         aria-modal="true"
         aria-labelledby="delete-distribution-title"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/50" @click="showDeleteModal = false"></div>
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-sm p-6 sm:p-8 text-center space-y-5 z-10">
            <div class="w-16 h-16 bg-rose-50 rounded-2xl flex items-center justify-center text-rose-700 text-2xl mx-auto">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div>
                <h2 id="delete-distribution-title" class="text-lg font-black text-slate-900">Hapus Penyaluran?</h2>
                <p class="text-sm text-slate-600 font-semibold mt-1">
                    Laporan Penyaluran "<span class="text-slate-900 font-black" x-text="selectedCode"></span>" akan dihapus secara permanen.
                </p>
            </div>
            <form method="POST" :action="'{{ url('admin/distributions') }}/' + selectedId + '/delete'">
                @csrf
                @method('DELETE')
                <div class="flex gap-3">
                    <button type="button" @click="showDeleteModal = false"
                            class="flex-1 py-3 min-h-[44px] bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-2xl text-xs font-bold transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-1 py-3 min-h-[44px] bg-rose-700 hover:bg-rose-800 text-white rounded-2xl text-xs font-black shadow-lg shadow-rose-700/20 transition-colors">
                        Ya, Hapus
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal: Import --}}
    <div x-show="showImportModal"
         x-cloak
         @keydown.escape.window="showImportModal = false"
         role="dialog"
         aria-modal="true"
         aria-labelledby="import-distribution-title"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/50" @click="showImportModal = false"></div>
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md p-6 sm:p-8 space-y-5 z-10">
            <div class="flex items-center justify-between">
                <div>
                    <h2 id="import-distribution-title" class="text-lg font-black text-slate-900">Import Data Penyaluran</h2>
                    <p class="text-xs text-slate-600 font-semibold mt-0.5">Upload file CSV data penyaluran</p>
                </div>
                <button type="button" @click="showImportModal = false" aria-label="Tutup modal import penyaluran" class="w-11 h-11 inline-flex items-center justify-center rounded-xl hover:bg-slate-100 text-slate-600 transition-colors">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <form method="POST" action="{{ route('admin.distributions.import') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl text-xs space-y-2 text-slate-700">
                    <p class="font-bold text-slate-800">Format Kolom CSV:</p>
                    <p class="font-mono text-xs text-slate-600">No, ID Penyaluran, Tgl Penyaluran, Program, Nominal, Nama PIC, Kontak PIC, Tgl Insert, Kota/Kab, Provinsi</p>
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-600 uppercase tracking-wider mb-1.5">Pilih File CSV (.csv)</label>
                    <input type="file" name="file" accept=".csv,.txt" required
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-sky-100 file:text-sky-700 hover:file:bg-sky-200">
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="button" @click="showImportModal = false"
                            class="flex-1 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-2xl text-xs font-bold transition-all">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-1 py-3 bg-sky-600 hover:bg-sky-700 text-white rounded-2xl text-xs font-black shadow-lg shadow-sky-500/20 transition-all">
                        Upload & Import
                    </button>
                </div>
            </form>
        </div>
    </div>

</section>
@endsection
