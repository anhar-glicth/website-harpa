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
        <div role="status" class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl font-semibold text-xs flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-3">
                <i class="fa-solid fa-circle-check text-emerald-700 text-base" aria-hidden="true"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" @click="$el.parentElement.remove()" aria-label="Tutup notifikasi" class="w-11 h-11 -my-2 inline-flex items-center justify-center rounded-xl text-emerald-700 hover:bg-emerald-100">
                <i class="fa-solid fa-xmark" aria-hidden="true"></i>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div role="alert" class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl font-semibold text-xs flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-3">
                <i class="fa-solid fa-triangle-exclamation text-rose-700 text-base" aria-hidden="true"></i>
                <span>{{ session('error') }}</span>
            </div>
            <button type="button" @click="$el.parentElement.remove()" aria-label="Tutup notifikasi" class="w-11 h-11 -my-2 inline-flex items-center justify-center rounded-xl text-rose-700 hover:bg-rose-100">
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

                <div class="flex flex-wrap items-center gap-3">
                    <button type="button" @click="showAddModal = true" class="px-5 py-2.5 min-h-[44px] bg-primary-600 hover:bg-primary-700 text-white rounded-xl text-xs font-black uppercase tracking-wider flex items-center justify-center gap-2 shadow-sm transition-colors">
                        <i class="fa-solid fa-user-plus text-xs"></i> Tambah Pengguna
                    </button>
                    <a href="{{ route('admin.users.export', request()->all()) }}" class="px-5 py-2.5 min-h-[44px] bg-emerald-700 hover:bg-emerald-800 text-white rounded-xl text-xs font-black uppercase tracking-wider flex items-center justify-center gap-2 shadow-sm transition-colors">
                        <i class="fa-solid fa-file-excel text-sm"></i> Export Excel
                    </a>
                </div>
            </div>

            {{-- Filter Bar Pengguna --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm space-y-3">
                <form action="{{ route('admin.users') }}" method="GET" class="flex flex-wrap items-center gap-3 text-xs font-bold text-slate-700">
                    
                    <div class="flex items-center gap-2">
                        <label for="filter_users_search">Cari:</label>
                        <input id="filter_users_search" type="text" name="search" value="{{ request('search') }}" placeholder="Nama / Email / Telp..." class="min-h-[44px] border border-slate-300 rounded-xl px-3 py-2 text-xs focus:border-primary-600 w-48">
                    </div>

                    <div class="flex items-center gap-2">
                        <label for="filter_users_role">Hak Akses:</label>
                        <select id="filter_users_role" name="role" class="min-h-[44px] border border-slate-300 rounded-xl px-3 py-2 text-xs focus:border-primary-600 w-44">
                            <option value="">Semua Peran</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Super Admin</option>
                            <option value="operator" {{ request('role') == 'operator' ? 'selected' : '' }}>Admin Data Entry</option>
                            <option value="finance" {{ request('role') == 'finance' ? 'selected' : '' }}>Staff Keuangan</option>
                            <option value="program_manager" {{ request('role') == 'program_manager' ? 'selected' : '' }}>Manager Program</option>
                            <option value="crm_staff" {{ request('role') == 'crm_staff' ? 'selected' : '' }}>Staff CRM &amp; Telemarketing</option>
                            <option value="field_worker" {{ request('role') == 'field_worker' ? 'selected' : '' }}>Petugas Lapangan</option>
                            <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>Donatur</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-2">
                        <label for="filter_users_office">Kantor:</label>
                        <select id="filter_users_office" name="office_id" class="min-h-[44px] border border-slate-300 rounded-xl px-3 py-2 text-xs focus:border-primary-600 min-w-[140px]">
                            <option value="">Semua Kantor</option>
                            @foreach($offices as $ofc)
                                <option value="{{ $ofc->id }}" {{ request('office_id') == $ofc->id ? 'selected' : '' }}>{{ $ofc->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="min-h-[44px] bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-xl text-xs inline-flex items-center gap-1.5 transition-colors">
                        <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i> Filter
                    </button>
                    
                    @if(request()->anyFilled(['search', 'role', 'office_id']))
                        <a href="{{ route('admin.users') }}" class="min-h-[44px] inline-flex items-center bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 px-3 py-2 rounded-xl text-xs">Reset</a>
                    @endif

                </form>
            </div>

            <!-- Users table -->
            <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 space-y-6 shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse whitespace-nowrap">
                        <thead>
                            <tr class="border-b border-slate-200 text-xs font-black uppercase tracking-wider text-slate-600">
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
                        <tbody class="text-xs font-semibold text-slate-700 uppercase tracking-wide divide-y divide-slate-100">
                            @forelse($users as $index => $user)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="py-4 text-center text-slate-600 font-mono">{{ $users->firstItem() + $index }}</td>
                                    <td class="py-4 font-bold text-slate-900">{{ $user->name }}</td>
                                    <td class="py-4 text-slate-600 font-mono text-xs lowercase">{{ $user->email }}</td>
                                    <td class="py-4">
                                        @if($user->role === 'admin')
                                            <span class="bg-red-50 text-red-700 border border-red-200 text-xs font-black uppercase tracking-widest px-2.5 py-1 rounded-md">SUPER ADMIN</span>
                                        @elseif($user->role === 'operator')
                                            <span class="bg-amber-50 text-amber-800 border border-amber-200 text-xs font-black uppercase tracking-widest px-2.5 py-1 rounded-md">ADMIN DATA ENTRY</span>
                                        @elseif($user->role === 'finance')
                                            <span class="bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-black uppercase tracking-widest px-2.5 py-1 rounded-md">STAFF KEUANGAN</span>
                                        @elseif($user->role === 'program_manager')
                                            <span class="bg-primary-50 text-[#063f5c] border border-primary-200 text-xs font-black uppercase tracking-widest px-2.5 py-1 rounded-md">MANAGER PROGRAM</span>
                                        @elseif($user->role === 'crm_staff')
                                            <span class="bg-rose-50 text-rose-700 border border-rose-200 text-xs font-black uppercase tracking-widest px-2.5 py-1 rounded-md">STAFF CRM</span>
                                        @elseif($user->role === 'field_worker')
                                            <span class="bg-sky-50 text-sky-800 border border-sky-200 text-xs font-black uppercase tracking-widest px-2.5 py-1 rounded-md">PETUGAS LAPANGAN</span>
                                        @else
                                            <span class="bg-slate-100 text-slate-700 border border-slate-200 text-xs font-black uppercase tracking-widest px-2.5 py-1 rounded-md">DONATUR</span>
                                        @endif
                                    </td>
                                    <td class="py-4 font-mono text-xs">{{ $user->phone ?? '-' }}</td>
                                    <td class="py-4 text-slate-700">{{ $user->office ? $user->office->name : '-' }}</td>
                                    <td class="py-4 text-slate-600 text-xs">{{ $user->created_at->format('d M Y') }}</td>
                                    <td class="py-4 text-right">
                                        <div class="flex items-center justify-end gap-1.5">
                                            @if($user->role === 'field_worker')
                                                <a href="{{ route('admin.users.monitoring', $user->id) }}" aria-label="Pantau kinerja {{ $user->name }}" class="w-11 h-11 inline-flex items-center justify-center bg-primary-50 text-primary-700 hover:bg-primary-100 rounded-lg text-xs transition-colors border border-primary-200" title="Pantau Kinerja">
                                                    <i class="fa-solid fa-gauge-high" aria-hidden="true"></i>
                                                </a>
                                            @endif
                                            <button type="button" @click="selectedUser = {{ json_encode($user) }}; showEditModal = true" aria-label="Edit pengguna {{ $user->name }}" class="w-11 h-11 inline-flex items-center justify-center text-amber-700 hover:bg-amber-50 rounded-lg transition-colors" title="Edit Pengguna">
                                                <i class="fa-solid fa-pen-to-square text-sm" aria-hidden="true"></i>
                                            </button>
                                            <button type="button" @click="selectedUser = {{ json_encode($user) }}; showDeleteModal = true" aria-label="Hapus pengguna {{ $user->name }}" class="w-11 h-11 inline-flex items-center justify-center text-rose-700 hover:bg-rose-50 rounded-lg transition-colors" title="Hapus Pengguna">
                                                <i class="fa-solid fa-trash-can text-sm" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="py-12 text-center text-slate-600 font-bold uppercase tracking-wider">Belum ada pengguna terdaftar yang sesuai filter.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="pt-4 border-t border-slate-200">
                    {{ $users->links() }}
                </div>
            </div>

        </div>

    </div>

    {{-- Modal Add User --}}
    <div x-show="showAddModal" x-cloak @keydown.escape.window="showAddModal = false" role="dialog" aria-modal="true" aria-labelledby="add-user-title" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/60" @click="showAddModal = false"></div>
        <div class="relative z-10 bg-white rounded-3xl max-w-md w-full p-6 space-y-4 shadow-xl border border-slate-100">
            <div class="flex items-center justify-between border-b pb-3">
                <h2 id="add-user-title" class="font-black text-slate-900 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-user-plus text-primary-600"></i> Tambah Pengguna Baru
                </h2>
                <button type="button" @click="showAddModal = false" aria-label="Tutup modal tambah pengguna" class="w-11 h-11 inline-flex items-center justify-center rounded-xl text-slate-600 hover:bg-slate-100"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-3 text-xs font-semibold">
                @csrf
                <div>
                    <label for="add_user_name" class="block text-slate-700 mb-1">Nama Lengkap *</label>
                    <input id="add_user_name" type="text" name="name" required placeholder="Nama Pengguna" class="w-full border border-slate-300 rounded-xl px-3.5 py-2 min-h-[44px] text-xs focus:border-primary-600">
                </div>
                <div>
                    <label for="add_user_email" class="block text-slate-700 mb-1">Email *</label>
                    <input id="add_user_email" type="email" name="email" required placeholder="user@domain.com" class="w-full border border-slate-300 rounded-xl px-3.5 py-2 min-h-[44px] text-xs focus:border-primary-600">
                </div>
                <div x-data="{ show: false }">
                    <label for="add_user_password" class="block text-slate-700 mb-1">Password *</label>
                    <div class="relative">
                        <input id="add_user_password" :type="show ? 'text' : 'password'" name="password" required placeholder="Minimal 6 karakter" class="w-full border border-slate-300 rounded-xl pl-3.5 pr-12 py-2 min-h-[44px] text-xs focus:border-primary-600">
                        <button type="button" @click="show = !show" :aria-label="show ? 'Sembunyikan password' : 'Lihat password'" class="absolute inset-y-0 right-0 flex items-center justify-center w-11 text-slate-600 hover:text-primary-600 transition-colors" :title="show ? 'Sembunyikan' : 'Lihat'">
                            <i class="fa-solid text-xs" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="add_user_role" class="block text-slate-700 mb-1">Hak Akses / Role *</label>
                        <select id="add_user_role" name="role" required class="w-full border border-slate-300 rounded-xl px-3 py-2 min-h-[44px] text-xs focus:border-primary-600">
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
                        <label for="add_user_phone" class="block text-slate-700 mb-1">No. Telp / WA</label>
                        <input id="add_user_phone" type="text" name="phone" placeholder="08123456789" class="w-full border border-slate-300 rounded-xl px-3.5 py-2 min-h-[44px] text-xs focus:border-primary-600">
                    </div>
                </div>
                <div>
                    <label for="add_user_office" class="block text-slate-700 mb-1">Kantor / Cabang</label>
                    <select id="add_user_office" name="office_id" class="w-full border border-slate-300 rounded-xl px-3 py-2 min-h-[44px] text-xs focus:border-primary-600">
                        <option value="">-- Pilih Kantor (Opsional) --</option>
                        @foreach($offices as $ofc)
                            <option value="{{ $ofc->id }}">{{ $ofc->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex justify-end gap-2 pt-3 border-t">
                    <button type="button" @click="showAddModal = false" class="px-4 py-2 min-h-[44px] bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold">Batal</button>
                    <button type="submit" class="px-5 py-2 min-h-[44px] bg-primary-600 text-white rounded-xl font-bold hover:bg-primary-700">Simpan User</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Edit User --}}
    <div x-show="showEditModal" x-cloak @keydown.escape.window="showEditModal = false" role="dialog" aria-modal="true" aria-labelledby="edit-user-title" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/60" @click="showEditModal = false"></div>
        <div class="relative z-10 bg-white rounded-3xl max-w-md w-full p-6 space-y-4 shadow-xl border border-slate-100">
            <div class="flex items-center justify-between border-b pb-3">
                <h2 id="edit-user-title" class="font-black text-slate-900 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-pen-to-square text-amber-700"></i> Edit Pengguna
                </h2>
                <button type="button" @click="showEditModal = false" aria-label="Tutup modal edit pengguna" class="w-11 h-11 inline-flex items-center justify-center rounded-xl text-slate-600 hover:bg-slate-100"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form :action="'{{ url('admin/users') }}/' + selectedUser.id + '/update'" method="POST" class="space-y-3 text-xs font-semibold">
                @csrf
                @method('PUT')
                <div>
                    <label for="edit_user_name" class="block text-slate-700 mb-1">Nama Lengkap *</label>
                    <input id="edit_user_name" type="text" name="name" required :value="selectedUser.name" class="w-full border border-slate-300 rounded-xl px-3.5 py-2 min-h-[44px] text-xs focus:border-primary-600">
                </div>
                <div>
                    <label for="edit_user_email" class="block text-slate-700 mb-1">Email *</label>
                    <input id="edit_user_email" type="email" name="email" required :value="selectedUser.email" class="w-full border border-slate-300 rounded-xl px-3.5 py-2 min-h-[44px] text-xs focus:border-primary-600">
                </div>
                <div x-data="{ show: false }">
                    <label for="edit_user_password" class="block text-slate-700 mb-1">Password Baru (Kosongkan jika tidak diganti)</label>
                    <div class="relative">
                        <input id="edit_user_password" :type="show ? 'text' : 'password'" name="password" placeholder="Min. 6 karakter jika mau ubah" class="w-full border border-slate-300 rounded-xl pl-3.5 pr-12 py-2 min-h-[44px] text-xs focus:border-primary-600">
                        <button type="button" @click="show = !show" :aria-label="show ? 'Sembunyikan password' : 'Lihat password'" class="absolute inset-y-0 right-0 flex items-center justify-center w-11 text-slate-600 hover:text-primary-600 transition-colors" :title="show ? 'Sembunyikan' : 'Lihat'">
                            <i class="fa-solid text-xs" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="edit_user_role" class="block text-slate-700 mb-1">Hak Akses / Role *</label>
                        <select id="edit_user_role" name="role" required class="w-full border border-slate-300 rounded-xl px-3 py-2 min-h-[44px] text-xs focus:border-primary-600">
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
                        <label for="edit_user_phone" class="block text-slate-700 mb-1">No. Telp / WA</label>
                        <input id="edit_user_phone" type="text" name="phone" :value="selectedUser.phone" class="w-full border border-slate-300 rounded-xl px-3.5 py-2 min-h-[44px] text-xs focus:border-primary-600">
                    </div>
                </div>
                <div>
                    <label for="edit_user_office" class="block text-slate-700 mb-1">Kantor / Cabang</label>
                    <select id="edit_user_office" name="office_id" class="w-full border border-slate-300 rounded-xl px-3 py-2 min-h-[44px] text-xs focus:border-primary-600">
                        <option value="">-- Pilih Kantor --</option>
                        @foreach($offices as $ofc)
                            <option value="{{ $ofc->id }}" :selected="selectedUser.office_id == {{ $ofc->id }}">{{ $ofc->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex justify-end gap-2 pt-3 border-t">
                    <button type="button" @click="showEditModal = false" class="px-4 py-2 min-h-[44px] bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold">Batal</button>
                    <button type="submit" class="px-5 py-2 min-h-[44px] bg-amber-700 text-white rounded-xl font-bold hover:bg-amber-800">Update User</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Delete User --}}
    <div x-show="showDeleteModal" x-cloak @keydown.escape.window="showDeleteModal = false" role="dialog" aria-modal="true" aria-labelledby="delete-user-title" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/60" @click="showDeleteModal = false"></div>
        <div class="relative z-10 bg-white rounded-3xl max-w-sm w-full p-6 space-y-4 text-center shadow-xl border border-slate-100">
            <div class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-700 flex items-center justify-center mx-auto text-xl">
                <i class="fa-solid fa-trash"></i>
            </div>
            <h2 id="delete-user-title" class="font-black text-slate-900 text-sm">Hapus Pengguna?</h2>
            <p class="text-xs text-slate-600">Apakah Anda yakin ingin menghapus akun <strong class="text-slate-900" x-text="selectedUser.name"></strong>?</p>
            <form :action="'{{ url('admin/users') }}/' + selectedUser.id + '/delete'" method="POST" class="flex justify-center gap-2 pt-2">
                @csrf
                @method('DELETE')
                <button type="button" @click="showDeleteModal = false" class="px-4 py-2 min-h-[44px] bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold text-xs">Batal</button>
                <button type="submit" class="px-5 py-2 min-h-[44px] bg-rose-700 text-white rounded-xl font-bold text-xs hover:bg-rose-800">Hapus</button>
            </form>
        </div>
    </div>

</section>
@endsection
