@extends('layouts.app')

@section('content')
<section class="max-w-7xl mx-auto px-6 py-20" x-data="{ 
    showAddModal: false, 
    showEditModal: false, 
    showDeleteModal: false,
    selectedId: null,
    selectedCode: '',
    editData: {
        id: null,
        karyawan_code: '',
        nama_lengkap: '',
        jabatan: '',
        no_hp: '',
        email: '',
        alamat: '',
        status: 'Aktif',
        office_id: '',
        parent_id: ''
    },
    openEdit(item) {
        this.editData = {
            id: item.id,
            karyawan_code: item.karyawan_code,
            nama_lengkap: item.nama_lengkap,
            jabatan: item.jabatan,
            no_hp: item.no_hp || '',
            email: item.email || '',
            alamat: item.alamat || '',
            status: item.status,
            office_id: item.office_id || '',
            parent_id: item.parent_id || ''
        };
        this.showEditModal = true;
    },
    openDelete(id, code) {
        this.selectedId = id;
        this.selectedCode = code;
        this.showDeleteModal = true;
    }
}">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- Sidebar Menu -->
        <div class="hidden lg:block lg:col-span-1 space-y-6">
            @include('admin.sidebar', ['active' => 'karyawan'])
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-3 space-y-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="space-y-2">
                    <h1 class="text-2xl sm:text-3xl font-montserrat font-black text-slate-900 uppercase tracking-tight italic">
                        Master <span class="text-primary-600">Karyawan</span>
                    </h1>
                    <div class="w-12 h-1 bg-primary-500 rounded-full"></div>
                </div>
            </div>

            <!-- Flash Alerts -->
            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center justify-between text-xs font-bold shadow-sm">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button @click="$el.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif

            @if($errors->any())
                <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl space-y-1 text-xs font-bold shadow-sm">
                    <div class="flex items-center gap-2 text-rose-700">
                        <i class="fa-solid fa-triangle-exclamation text-base"></i>
                        <span>Terjadi kesalahan input:</span>
                    </div>
                    <ul class="list-disc list-inside text-[11px] font-medium text-rose-600 pl-4 space-y-0.5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Filter & Search Bar Section -->
            <div class="bg-white border border-slate-100 rounded-3xl p-6 sm:p-8 space-y-6 shadow-sm">
                <form method="GET" action="{{ route('admin.karyawan') }}" class="flex flex-wrap items-center gap-3 text-xs font-bold text-slate-700 pb-4 border-b border-slate-100">
                    
                    <!-- Search Input -->
                    <div class="flex items-center gap-1.5 min-w-[220px] flex-1 sm:flex-none">
                        <span class="text-slate-500 font-bold whitespace-nowrap">Search :</span>
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}" 
                               placeholder="ID/Nama Karyawan/" 
                               class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 placeholder:text-slate-400">
                    </div>

                    <!-- Kantor Dropdown -->
                    <div class="flex items-center gap-1.5 min-w-[170px]">
                        <span class="text-slate-500 font-bold whitespace-nowrap">Kantor :</span>
                        <select name="office_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500">
                            <option value="">Search By Kota/Kantor</option>
                            @foreach($offices as $off)
                                <option value="{{ $off->id }}" {{ request('office_id') == $off->id ? 'selected' : '' }}>
                                    {{ $off->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Karyawan Parent Dropdown -->
                    <div class="flex items-center gap-1.5 min-w-[190px]">
                        <span class="text-slate-500 font-bold whitespace-nowrap">Atasan :</span>
                        <select name="parent_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500">
                            <option value="">Search By Kota/Atasan</option>
                            @foreach($parentOptions as $parent)
                                <option value="{{ $parent->id }}" {{ request('parent_id') == $parent->id ? 'selected' : '' }}>
                                    {{ $parent->nama_lengkap }} ({{ $parent->karyawan_code }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status Dropdown -->
                    <div class="flex items-center gap-1.5 min-w-[120px]">
                        <span class="text-slate-500 font-bold whitespace-nowrap">Status :</span>
                        <select name="status" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500">
                            <option value="">Semua</option>
                            <option value="Aktif" {{ request('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="Nonaktif" {{ request('status') == 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>

                    <!-- Action Buttons for Search & Reset -->
                    <div class="flex items-center gap-2 ml-auto">
                        <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-900 hover:bg-slate-950 text-white rounded-xl text-xs font-bold transition-all shadow-sm">
                            <i class="fa-solid fa-magnifying-glass text-xs"></i> Search
                        </button>
                        <a href="{{ route('admin.karyawan') }}" class="p-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition-all" title="Reset Filter">
                            <i class="fa-solid fa-rotate text-xs"></i>
                        </a>
                    </div>

                </form>

                <!-- Toolbar Actions (Add, Edit, Delete, Export) -->
                <div class="flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <button @click="showAddModal = true" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-2xl text-xs font-black uppercase tracking-wider shadow-lg shadow-primary-500/20 transition-all duration-300">
                            <i class="fa-solid fa-plus text-xs"></i> Add Karyawan
                        </button>
                        <a href="{{ route('admin.karyawan.export', request()->query()) }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100 rounded-2xl text-xs font-bold transition-all shadow-sm">
                            <i class="fa-solid fa-file-excel text-emerald-600"></i> Export Excel
                        </a>
                    </div>

                    <div class="text-xs font-semibold text-slate-500">
                        Total: <span class="font-bold text-slate-900">{{ $karyawans->total() }}</span> Karyawan
                    </div>
                </div>

                <!-- Table Data Karyawan -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 text-[10px] font-black uppercase tracking-wider text-slate-400">
                                <th class="pb-4 w-8">#</th>
                                <th class="pb-4">ID Karyawan</th>
                                <th class="pb-4">Nama Lengkap</th>
                                <th class="pb-4">Jabatan</th>
                                <th class="pb-4">No. HP</th>
                                <th class="pb-4">Email</th>
                                <th class="pb-4">Kantor/Kota</th>
                                <th class="pb-4">Parent (Atasan)</th>
                                <th class="pb-4">Status</th>
                                <th class="pb-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-xs font-semibold text-slate-600">
                            @forelse($karyawans as $index => $item)
                                <tr class="border-b border-slate-50 hover:bg-slate-50/80 transition-colors">
                                    <td class="py-4 text-slate-400 font-mono text-[10px]">{{ $karyawans->firstItem() + $index }}</td>
                                    <td class="py-4 font-mono font-bold text-primary-700">{{ $item->karyawan_code }}</td>
                                    <td class="py-4 font-bold text-slate-900">{{ $item->nama_lengkap }}</td>
                                    <td class="py-4 text-slate-600">{{ $item->jabatan }}</td>
                                    <td class="py-4 font-mono text-[11px] text-slate-600">{{ $item->no_hp ?? '-' }}</td>
                                    <td class="py-4 text-slate-600 text-[11px]">{{ $item->email ?? '-' }}</td>
                                    <td class="py-4">
                                        @if($item->office)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-slate-100 text-slate-700 rounded-lg text-[10px] font-bold">
                                                <i class="fa-solid fa-building text-[9px] text-slate-400"></i> {{ $item->office->name }}
                                            </span>
                                        @else
                                            <span class="text-slate-400">-</span>
                                        @endif
                                    </td>
                                    <td class="py-4">
                                        @if($item->parent)
                                            <span class="text-slate-700 font-medium">{{ $item->parent->nama_lengkap }}</span>
                                        @else
                                            <span class="text-slate-400 italic text-[11px]">Utama (Top)</span>
                                        @endif
                                    </td>
                                    <td class="py-4">
                                        @if($item->status === 'Aktif')
                                            <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 border border-emerald-200 text-[9px] font-black uppercase tracking-wider px-2.5 py-0.5 rounded-full">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 bg-rose-50 text-rose-700 border border-rose-200 text-[9px] font-black uppercase tracking-wider px-2.5 py-0.5 rounded-full">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Nonaktif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-4 text-right">
                                        <div class="flex items-center justify-end gap-1.5">
                                            <button @click="openEdit({{ json_encode($item) }})" class="p-1.5 text-amber-600 hover:bg-amber-50 rounded-lg transition-all" title="Edit Data">
                                                <i class="fa-solid fa-pen-to-square text-sm"></i>
                                            </button>
                                            <button @click="openDelete({{ $item->id }}, '{{ $item->karyawan_code }}')" class="p-1.5 text-rose-600 hover:bg-rose-50 rounded-lg transition-all" title="Hapus Data">
                                                <i class="fa-solid fa-trash-can text-sm"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="py-12 text-center text-slate-400 font-bold uppercase tracking-wider space-y-2">
                                        <i class="fa-solid fa-folder-open text-3xl block text-slate-300 mb-2"></i>
                                        <span>Belum ada data karyawan yang sesuai filter.</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="pt-4">
                    {{ $karyawans->links() }}
                </div>
            </div>

        </div>

    </div>

    <!-- MODAL ADD KARYAWAN -->
    <div x-show="showAddModal" 
         x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm animate-in fade-in duration-200">
        <div @click.away="showAddModal = false" class="bg-white rounded-3xl max-w-xl w-full p-6 sm:p-8 space-y-6 shadow-2xl relative overflow-hidden">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg shadow-sm">
                        <i class="fa-solid fa-user-plus"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-black text-slate-900 uppercase tracking-tight">Tambah Karyawan Baru</h3>
                        <p class="text-xs text-slate-400 font-medium">Isi formulir data karyawan secara lengkap</p>
                    </div>
                </div>
                <button @click="showAddModal = false" class="text-slate-400 hover:text-slate-600 p-2 rounded-xl">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('admin.karyawan.store') }}" class="space-y-4 text-xs font-semibold">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-slate-700 mb-1">ID Karyawan <span class="text-rose-500">*</span></label>
                        <input type="text" name="karyawan_code" placeholder="e.g. KRY-005" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-slate-700 mb-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_lengkap" placeholder="Nama lengkap karyawan" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-slate-700 mb-1">Jabatan <span class="text-rose-500">*</span></label>
                        <input type="text" name="jabatan" placeholder="e.g. Manager, Staff" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-slate-700 mb-1">Status <span class="text-rose-500">*</span></label>
                        <select name="status" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:outline-none bg-white">
                            <option value="Aktif">Aktif</option>
                            <option value="Nonaktif">Nonaktif</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-slate-700 mb-1">No. HP / WhatsApp</label>
                        <input type="text" name="no_hp" placeholder="e.g. 08123456789" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-slate-700 mb-1">Email</label>
                        <input type="email" name="email" placeholder="email@domain.com" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-slate-700 mb-1">Kantor / Cabang</label>
                        <select name="office_id" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:outline-none bg-white">
                            <option value="">-- Pilih Kantor --</option>
                            @foreach($offices as $off)
                                <option value="{{ $off->id }}">{{ $off->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-slate-700 mb-1">Karyawan Parent (Atasan)</label>
                        <select name="parent_id" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:outline-none bg-white">
                            <option value="">-- Tidak Ada / Top Level --</option>
                            @foreach($parentOptions as $parent)
                                <option value="{{ $parent->id }}">{{ $parent->nama_lengkap }} ({{ $parent->karyawan_code }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-slate-700 mb-1">Alamat</label>
                    <textarea name="alamat" rows="2" placeholder="Alamat lengkap karyawan..." class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:outline-none"></textarea>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" @click="showAddModal = false" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold transition-all">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold shadow-lg shadow-emerald-100 transition-all">
                        Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL EDIT KARYAWAN -->
    <div x-show="showEditModal" 
         x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm animate-in fade-in duration-200">
        <div @click.away="showEditModal = false" class="bg-white rounded-3xl max-w-xl w-full p-6 sm:p-8 space-y-6 shadow-2xl relative overflow-hidden">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg shadow-sm">
                        <i class="fa-solid fa-user-pen"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-black text-slate-900 uppercase tracking-tight">Edit Data Karyawan</h3>
                        <p class="text-xs text-slate-400 font-medium">Perbarui informasi data karyawan</p>
                    </div>
                </div>
                <button @click="showEditModal = false" class="text-slate-400 hover:text-slate-600 p-2 rounded-xl">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form method="POST" :action="'/admin/karyawan/' + editData.id + '/update'" class="space-y-4 text-xs font-semibold">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-slate-700 mb-1">ID Karyawan <span class="text-rose-500">*</span></label>
                        <input type="text" name="karyawan_code" x-model="editData.karyawan_code" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-slate-700 mb-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_lengkap" x-model="editData.nama_lengkap" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-slate-700 mb-1">Jabatan <span class="text-rose-500">*</span></label>
                        <input type="text" name="jabatan" x-model="editData.jabatan" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-slate-700 mb-1">Status <span class="text-rose-500">*</span></label>
                        <select name="status" x-model="editData.status" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:outline-none bg-white">
                            <option value="Aktif">Aktif</option>
                            <option value="Nonaktif">Nonaktif</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-slate-700 mb-1">No. HP / WhatsApp</label>
                        <input type="text" name="no_hp" x-model="editData.no_hp" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-slate-700 mb-1">Email</label>
                        <input type="email" name="email" x-model="editData.email" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-slate-700 mb-1">Kantor / Cabang</label>
                        <select name="office_id" x-model="editData.office_id" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:outline-none bg-white">
                            <option value="">-- Pilih Kantor --</option>
                            @foreach($offices as $off)
                                <option value="{{ $off->id }}">{{ $off->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-slate-700 mb-1">Karyawan Parent (Atasan)</label>
                        <select name="parent_id" x-model="editData.parent_id" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:outline-none bg-white">
                            <option value="">-- Tidak Ada / Top Level --</option>
                            @foreach($parentOptions as $parent)
                                <option value="{{ $parent->id }}">{{ $parent->nama_lengkap }} ({{ $parent->karyawan_code }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-slate-700 mb-1">Alamat</label>
                    <textarea name="alamat" x-model="editData.alamat" rows="2" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:outline-none"></textarea>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" @click="showEditModal = false" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold transition-all">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-amber-600 hover:bg-amber-700 text-white rounded-xl font-bold shadow-lg shadow-amber-100 transition-all">
                        Update Data
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL DELETE KARYAWAN -->
    <div x-show="showDeleteModal" 
         x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm animate-in fade-in duration-200">
        <div @click.away="showDeleteModal = false" class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 space-y-6 shadow-2xl relative text-center">
            <div class="w-16 h-16 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center text-2xl mx-auto shadow-sm">
                <i class="fa-solid fa-trash-can"></i>
            </div>
            
            <div class="space-y-2">
                <h3 class="text-lg font-black text-slate-900 uppercase tracking-tight">Hapus Data Karyawan?</h3>
                <p class="text-xs text-slate-500 font-medium">
                    Apakah Anda yakin ingin menghapus data karyawan <span class="font-bold text-slate-900" x-text="selectedCode"></span>? Tindakan ini tidak dapat dibatalkan.
                </p>
            </div>

            <form method="POST" :action="'/admin/karyawan/' + selectedId + '/delete'" class="flex items-center justify-center gap-3 pt-2">
                @csrf
                @method('DELETE')
                <button type="button" @click="showDeleteModal = false" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-all">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold shadow-lg shadow-rose-100 transition-all">
                    Ya, Hapus Data
                </button>
            </form>
        </div>
    </div>

</section>
@endsection
