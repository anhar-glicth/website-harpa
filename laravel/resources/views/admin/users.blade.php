@extends('layouts.app')

@section('content')
<section class="max-w-7xl mx-auto px-6 py-12" x-data="{ 
    showAddModal: false, 
    showEditModal: false, 
    showDeleteModal: false,
    selectedUser: {}
}">
    
    {{-- Alerts --}}
    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-250 text-emerald-800 rounded-2xl font-semibold text-xs flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-3">
                <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button @click="$el.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-rose-50 border border-rose-250 text-rose-800 rounded-2xl font-semibold text-xs flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-3">
                <i class="fa-solid fa-triangle-exclamation text-rose-600 text-base"></i>
                <span>{{ session('error') }}</span>
            </div>
            <button @click="$el.parentElement.remove()" class="text-rose-500 hover:text-rose-700">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- Sidebar Menu -->
        <div class="hidden lg:block lg:col-span-1 space-y-6">
            @include('admin.sidebar', ['active' => 'users'])
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-3 space-y-6">
            
            {{-- Header Page Title & Action --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="space-y-1">
                    <h1 class="text-2xl sm:text-3xl font-montserrat font-black text-slate-900 uppercase tracking-tight italic">
                        Kelola <span class="text-primary-600">Pengguna & Hak Akses</span>
                    </h1>
                    <div class="w-12 h-1 bg-primary-500 rounded-full"></div>
                </div>

                <div class="flex items-center gap-3">
                    <button type="button" @click="showAddModal = true" class="px-5 py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-xl text-xs font-black uppercase tracking-wider flex items-center justify-center gap-2 shadow-sm transition-all">
                        <i class="fa-solid fa-user-plus text-xs"></i> Tambah Pengguna
                    </button>
                    <a href="{{ route('admin.users.export', request()->all()) }}" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-black uppercase tracking-wider flex items-center justify-center gap-2 shadow-sm transition-all">
                        <i class="fa-solid fa-file-excel text-sm"></i> Export Excel
                    </a>
                </div>
            </div>

            {{-- Filter Bar Pengguna --}}
            <div class="bg-white border border-slate-100 rounded-2xl p-4 shadow-sm space-y-3">
                <form action="{{ route('admin.users') }}" method="GET" class="flex flex-wrap items-center gap-3 text-xs font-bold text-slate-700">
                    
                    <div class="flex items-center gap-2">
                        <span>Cari:</span>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama / Email / Telp..." class="border border-slate-250 rounded-xl px-3 py-1.5 text-xs focus:border-primary-500 outline-none w-48">
                    </div>

                    <div class="flex items-center gap-2">
                        <span>Hak Akses:</span>
                        <select name="role" class="border border-slate-250 rounded-xl px-3 py-1.5 text-xs focus:border-primary-500 outline-none w-44">
                            <option value="">Semua Peran</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Super Admin</option>
                            <option value="operator" {{ request('role') == 'operator' ? 'selected' : '' }}>Admin Data Entry</option>
                            <option value="finance" {{ request('role') == 'finance' ? 'selected' : '' }}>Staff Keuangan</option>
                            <option value="program_manager" {{ request('role') == 'program_manager' ? 'selected' : '' }}>Manager Program</option>
                            <option value="crm_staff" {{ request('role') == 'crm_staff' ? 'selected' : '' }}>Staff CRM & Telemarketing</option>
                            <option value="field_worker" {{ request('role') == 'field_worker' ? 'selected' : '' }}>Petugas Lapangan</option>
                            <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>Donatur</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-2">
                        <span>Kantor:</span>
                        <select name="office_id" class="border border-slate-250 rounded-xl px-3 py-1.5 text-xs focus:border-primary-500 outline-none min-w-[140px]">
                            <option value="">Semua Kantor</option>
                            @foreach($offices as $ofc)
                                <option value="{{ $ofc->id }}" {{ request('office_id') == $ofc->id ? 'selected' : '' }}>{{ $ofc->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-1.5 rounded-xl text-xs flex items-center gap-1.5 transition-all">
                        🔍 Filter
                    </button>
                    
                    @if(request()->anyFilled(['search', 'role', 'office_id']))
                        <a href="{{ route('admin.users') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-3 py-1.5 rounded-xl text-xs">Reset</a>
                    @endif

                </form>
            </div>

            <!-- Users table -->
            <div class="bg-white border border-slate-100 rounded-3xl p-6 sm:p-8 space-y-6 shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse whitespace-nowrap">
                        <thead>
                            <tr class="border-b border-slate-100 text-[10px] font-black uppercase tracking-wider text-slate-400">
                                <th class="pb-4 w-12 text-center">#</th>
                                <th class="pb-4">Nama Lengkap</th>
                                <th class="pb-4">Email</th>
                                <th class="pb-4">Peran / Hak Akses</th>
                                <th class="pb-4">No. Telp</th>
                                <th class="pb-4">Kantor / Cabang</th>
                                <th class="pb-4">Terdaftar</th>
                                <th class="pb-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-xs font-semibold text-slate-600 uppercase tracking-wide divide-y divide-slate-50">
                            @forelse($users as $index => $user)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="py-4 text-center text-slate-400 font-mono">{{ $users->firstItem() + $index }}</td>
                                    <td class="py-4 font-bold text-slate-900">{{ $user->name }}</td>
                                    <td class="py-4 text-slate-600 font-mono text-[11px] lower-case">{{ $user->email }}</td>
                                    <td class="py-4">
                                        @if($user->role === 'admin')
                                            <span class="bg-red-50 text-red-700 border border-red-200 text-[8px] font-black uppercase tracking-widest px-2.5 py-1 rounded-md">SUPER ADMIN</span>
                                        @elseif($user->role === 'operator')
                                            <span class="bg-amber-50 text-amber-700 border border-amber-200 text-[8px] font-black uppercase tracking-widest px-2.5 py-1 rounded-md">ADMIN DATA ENTRY</span>
                                        @elseif($user->role === 'finance')
                                            <span class="bg-emerald-50 text-emerald-700 border border-emerald-200 text-[8px] font-black uppercase tracking-widest px-2.5 py-1 rounded-md">STAFF KEUANGAN</span>
                                        @elseif($user->role === 'program_manager')
                                            <span class="bg-purple-50 text-purple-700 border border-purple-200 text-[8px] font-black uppercase tracking-widest px-2.5 py-1 rounded-md">MANAGER PROGRAM</span>
                                        @elseif($user->role === 'crm_staff')
                                            <span class="bg-rose-50 text-rose-700 border border-rose-200 text-[8px] font-black uppercase tracking-widest px-2.5 py-1 rounded-md">STAFF CRM</span>
                                        @elseif($user->role === 'field_worker')
                                            <span class="bg-indigo-50 text-indigo-700 border border-indigo-200 text-[8px] font-black uppercase tracking-widest px-2.5 py-1 rounded-md">PETUGAS LAPANGAN</span>
                                        @else
                                            <span class="bg-slate-100 text-slate-700 border border-slate-200 text-[8px] font-black uppercase tracking-widest px-2.5 py-1 rounded-md">DONATUR</span>
                                        @endif
                                    </td>
                                    <td class="py-4 font-mono text-[11px]">{{ $user->phone ?? '-' }}</td>
                                    <td class="py-4 text-slate-700">{{ $user->office ? $user->office->name : '-' }}</td>
                                    <td class="py-4 text-slate-500 text-[11px]">{{ $user->created_at->format('d M Y') }}</td>
                                    <td class="py-4 text-right">
                                        <div class="flex items-center justify-end gap-1.5">
                                            @if($user->role === 'field_worker')
                                                <a href="{{ route('admin.users.monitoring', $user->id) }}" class="p-1.5 bg-primary-50 text-primary-700 hover:bg-primary-100 rounded-lg text-xs transition-all border border-primary-100" title="Pantau Kinerja">
                                                    <i class="fa-solid fa-gauge-high"></i>
                                                </a>
                                            @endif
                                            <button type="button" @click="selectedUser = {{ json_encode($user) }}; showEditModal = true" class="p-1.5 text-amber-600 hover:bg-amber-50 rounded-lg transition-all" title="Edit Pengguna">
                                                <i class="fa-solid fa-pen-to-square text-sm"></i>
                                            </button>
                                            <button type="button" @click="selectedUser = {{ json_encode($user) }}; showDeleteModal = true" class="p-1.5 text-rose-600 hover:bg-rose-50 rounded-lg transition-all" title="Hapus Pengguna">
                                                <i class="fa-solid fa-trash-can text-sm"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="py-12 text-center text-slate-400 font-bold uppercase tracking-wider">Belum ada pengguna terdaftar yang sesuai filter.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="pt-4 border-t border-slate-100">
                    {{ $users->links() }}
                </div>
            </div>

        </div>

    </div>

    {{-- MODAL ADD USER --}}
    <div x-show="showAddModal" x-cloak class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 space-y-4 shadow-xl border border-slate-100">
            <div class="flex items-center justify-between border-b pb-3">
                <h3 class="font-black text-slate-900 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-user-plus text-primary-600"></i> Tambah Pengguna Baru
                </h3>
                <button type="button" @click="showAddModal = false" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-3 text-xs font-semibold">
                @csrf
                <div>
                    <label class="block text-slate-700 mb-1">Nama Lengkap *</label>
                    <input type="text" name="name" required placeholder="Nama Pengguna" class="w-full border border-slate-300 rounded-xl px-3.5 py-2 text-xs focus:border-primary-600">
                </div>
                <div>
                    <label class="block text-slate-700 mb-1">Email *</label>
                    <input type="email" name="email" required placeholder="user@domain.com" class="w-full border border-slate-300 rounded-xl px-3.5 py-2 text-xs focus:border-primary-600">
                </div>
                <div x-data="{ show: false }">
                    <label class="block text-slate-700 mb-1">Password *</label>
                    <div class="relative">
                        <input :type="show ? 'text' : 'password'" name="password" required placeholder="Minimal 6 karakter" class="w-full border border-slate-300 rounded-xl pl-3.5 pr-10 py-2 text-xs focus:border-primary-600">
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-primary-600 transition-colors focus:outline-none" :title="show ? 'Sembunyikan' : 'Lihat'">
                            <i class="fa-solid text-xs" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-slate-700 mb-1">Hak Akses / Role *</label>
                        <select name="role" required class="w-full border border-slate-300 rounded-xl px-3 py-2 text-xs focus:border-primary-600">
                            <option value="user">Donatur</option>
                            <option value="field_worker">Petugas Lapangan</option>
                            <option value="operator">Admin Data Entry</option>
                            <option value="finance">Staff Keuangan</option>
                            <option value="program_manager">Manager Program</option>
                            <option value="crm_staff">Staff CRM & Telemarketing</option>
                            <option value="admin">Super Admin</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-slate-700 mb-1">No. Telp / WA</label>
                        <input type="text" name="phone" placeholder="08123456789" class="w-full border border-slate-300 rounded-xl px-3.5 py-2 text-xs focus:border-primary-600">
                    </div>
                </div>
                <div>
                    <label class="block text-slate-700 mb-1">Kantor / Cabang</label>
                    <select name="office_id" class="w-full border border-slate-300 rounded-xl px-3 py-2 text-xs focus:border-primary-600">
                        <option value="">-- Pilih Kantor (Opsional) --</option>
                        @foreach($offices as $ofc)
                            <option value="{{ $ofc->id }}">{{ $ofc->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex justify-end gap-2 pt-3 border-t">
                    <button type="button" @click="showAddModal = false" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-xl font-bold">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-primary-600 text-white rounded-xl font-bold hover:bg-primary-700">Simpan User</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL EDIT USER --}}
    <div x-show="showEditModal" x-cloak class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 space-y-4 shadow-xl border border-slate-100">
            <div class="flex items-center justify-between border-b pb-3">
                <h3 class="font-black text-slate-900 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-pen-to-square text-amber-600"></i> Edit Pengguna
                </h3>
                <button type="button" @click="showEditModal = false" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form :action="'{{ url('admin/users') }}/' + selectedUser.id + '/update'" method="POST" class="space-y-3 text-xs font-semibold">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-slate-700 mb-1">Nama Lengkap *</label>
                    <input type="text" name="name" required :value="selectedUser.name" class="w-full border border-slate-300 rounded-xl px-3.5 py-2 text-xs focus:border-primary-600">
                </div>
                <div>
                    <label class="block text-slate-700 mb-1">Email *</label>
                    <input type="email" name="email" required :value="selectedUser.email" class="w-full border border-slate-300 rounded-xl px-3.5 py-2 text-xs focus:border-primary-600">
                </div>
                <div x-data="{ show: false }">
                    <label class="block text-slate-700 mb-1">Password Baru (Kosongkan jika tidak diganti)</label>
                    <div class="relative">
                        <input :type="show ? 'text' : 'password'" name="password" placeholder="Min. 6 karakter jika mau ubah" class="w-full border border-slate-300 rounded-xl pl-3.5 pr-10 py-2 text-xs focus:border-primary-600">
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-primary-600 transition-colors focus:outline-none" :title="show ? 'Sembunyikan' : 'Lihat'">
                            <i class="fa-solid text-xs" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-slate-700 mb-1">Hak Akses / Role *</label>
                        <select name="role" required class="w-full border border-slate-300 rounded-xl px-3 py-2 text-xs focus:border-primary-600">
                            <option value="user" :selected="selectedUser.role == 'user'">Donatur</option>
                            <option value="field_worker" :selected="selectedUser.role == 'field_worker'">Petugas Lapangan</option>
                            <option value="operator" :selected="selectedUser.role == 'operator'">Admin Data Entry</option>
                            <option value="finance" :selected="selectedUser.role == 'finance'">Staff Keuangan</option>
                            <option value="program_manager" :selected="selectedUser.role == 'program_manager'">Manager Program</option>
                            <option value="crm_staff" :selected="selectedUser.role == 'crm_staff'">Staff CRM & Telemarketing</option>
                            <option value="admin" :selected="selectedUser.role == 'admin'">Super Admin</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-slate-700 mb-1">No. Telp / WA</label>
                        <input type="text" name="phone" :value="selectedUser.phone" class="w-full border border-slate-300 rounded-xl px-3.5 py-2 text-xs focus:border-primary-600">
                    </div>
                </div>
                <div>
                    <label class="block text-slate-700 mb-1">Kantor / Cabang</label>
                    <select name="office_id" class="w-full border border-slate-300 rounded-xl px-3 py-2 text-xs focus:border-primary-600">
                        <option value="">-- Pilih Kantor --</option>
                        @foreach($offices as $ofc)
                            <option value="{{ $ofc->id }}" :selected="selectedUser.office_id == {{ $ofc->id }}">{{ $ofc->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex justify-end gap-2 pt-3 border-t">
                    <button type="button" @click="showEditModal = false" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-xl font-bold">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-amber-600 text-white rounded-xl font-bold hover:bg-amber-700">Update User</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL DELETE USER --}}
    <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-sm w-full p-6 space-y-4 text-center shadow-xl border border-slate-100">
            <div class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center mx-auto text-xl">
                <i class="fa-solid fa-trash"></i>
            </div>
            <h3 class="font-black text-slate-900 text-sm">Hapus Pengguna?</h3>
            <p class="text-xs text-slate-500">Apakah Anda yakin ingin menghapus akun <strong class="text-slate-900" x-text="selectedUser.name"></strong>?</p>
            <form :action="'{{ url('admin/users') }}/' + selectedUser.id + '/delete'" method="POST" class="flex justify-center gap-2 pt-2">
                @csrf
                @method('DELETE')
                <button type="button" @click="showDeleteModal = false" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-xl font-bold text-xs">Batal</button>
                <button type="submit" class="px-5 py-2 bg-rose-600 text-white rounded-xl font-bold text-xs hover:bg-rose-700">Hapus</button>
            </form>
        </div>
    </div>

</section>
@endsection
