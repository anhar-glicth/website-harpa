  @extends('layouts.app')

@section('content')
<section class="max-w-7xl mx-auto px-6 py-12" x-data="userManagement()">
    
    {{-- Alerts --}}
    @if(session('success'))
        <div role="status" class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl font-semibold text-xs flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-3">
                <i class="fa-solid fa-circle-check text-emerald-700 text-base" aria-hidden="true"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" @click="$el.parentElement.remove()" aria-label="Tutup notifikasi" class="w-11 h-11 -my-2 inline-flex items-center justify-center rounded-xl text-emerald-700 hover:bg-emerald-100 transition-colors">
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
            <button type="button" @click="$el.parentElement.remove()" aria-label="Tutup notifikasi" class="w-11 h-11 -my-2 inline-flex items-center justify-center rounded-xl text-rose-700 hover:bg-rose-100 transition-colors">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div role="alert" class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl font-semibold text-xs space-y-1 shadow-xs">
            <div class="flex items-center gap-2 font-bold text-rose-900">
                <i class="fa-solid fa-triangle-exclamation text-rose-700"></i>
                <span>Terjadi kendala pada pengisian formulir:</span>
            </div>
            <ul class="list-disc list-inside space-y-0.5 text-rose-700 pl-2">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
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
                    <button type="button" @click="openAddModal()" class="px-5 py-2.5 min-h-[44px] bg-[#063f5c] hover:bg-[#04324a] text-white rounded-xl text-xs font-bold uppercase tracking-wider flex items-center justify-center gap-2 shadow-xs transition-colors">
                        <i class="fa-solid fa-user-plus text-xs"></i> Tambah Pengguna
                    </button>
                    <button type="button" @click="showImportModal = true" class="px-5 py-2.5 min-h-[44px] bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 rounded-xl text-xs font-bold uppercase tracking-wider flex items-center justify-center gap-2 shadow-xs transition-colors">
                        <i class="fa-solid fa-file-import text-xs text-emerald-700"></i> Import Excel / CSV
                    </button>
                    <a href="{{ route('admin.users.export', request()->all()) }}" class="px-5 py-2.5 min-h-[44px] bg-emerald-700 hover:bg-emerald-800 text-white rounded-xl text-xs font-bold uppercase tracking-wider flex items-center justify-center gap-2 shadow-xs transition-colors">
                        <i class="fa-solid fa-file-excel text-sm"></i> Export Excel
                    </a>
                </div>
            </div>

            {{-- Filter Bar Pengguna --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-xs space-y-3">
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
                            <option value="marketing" {{ request('role') == 'marketing' ? 'selected' : '' }}>Staff Marketing</option>
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

                    <button type="submit" class="min-h-[44px] bg-[#063f5c] hover:bg-[#04324a] text-white px-4 py-2 rounded-xl text-xs inline-flex items-center gap-1.5 transition-colors">
                        <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i> Filter
                    </button>
                    
                    @if(request()->anyFilled(['search', 'role', 'office_id']))
                        <a href="{{ route('admin.users') }}" class="min-h-[44px] inline-flex items-center bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 px-3 py-2 rounded-xl text-xs">Reset</a>
                    @endif

                </form>
            </div>

            <!-- Users table -->
            <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 space-y-6 shadow-xs">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse whitespace-nowrap">
                        <thead>
                            <tr class="border-b border-slate-200 text-xs font-bold uppercase tracking-wider text-slate-500">
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
                        <tbody class="text-xs font-semibold text-slate-700 divide-y divide-slate-100">
                            @forelse($users as $index => $user)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="py-4 text-center text-slate-500 font-mono">{{ $users->firstItem() + $index }}</td>
                                    <td class="py-4 font-bold text-slate-900">{{ $user->name }}</td>
                                    <td class="py-4 text-slate-600 font-mono text-xs lowercase">{{ $user->email }}</td>
                                    <td class="py-4">
                                        @if($user->role === 'admin')
                                            <span class="text-xs font-bold text-rose-700">Super Admin</span>
                                        @elseif($user->role === 'marketing')
                                            <span class="text-xs font-bold text-amber-700">Staff Marketing</span>
                                        @elseif($user->role === 'operator')
                                            <span class="text-xs font-bold text-slate-700">Admin Data Entry</span>
                                        @elseif($user->role === 'finance')
                                            <span class="text-xs font-bold text-emerald-700">Staff Keuangan</span>
                                        @elseif($user->role === 'program_manager')
                                            <span class="text-xs font-bold text-[#063f5c]">Manager Program</span>
                                        @elseif($user->role === 'crm_staff')
                                            <span class="text-xs font-bold text-indigo-700">Staff CRM &amp; WA</span>
                                        @elseif($user->role === 'field_worker')
                                            <span class="text-xs font-bold text-sky-700">Petugas Lapangan</span>
                                        @else
                                            <span class="text-xs font-medium text-slate-500">Donatur</span>
                                        @endif
                                    </td>
                                    <td class="py-4 font-mono text-xs">{{ $user->phone ?? '-' }}</td>
                                    <td class="py-4 text-slate-700">{{ $user->office ? $user->office->name : '-' }}</td>
                                    <td class="py-4 text-slate-500 text-xs">{{ $user->created_at->format('d M Y') }}</td>
                                    <td class="py-4 text-right">
                                        <div class="flex items-center justify-end gap-1.5">
                                            @if($user->role === 'field_worker')
                                                <a href="{{ route('admin.users.monitoring', $user->id) }}" aria-label="Pantau kinerja {{ $user->name }}" class="px-2.5 py-1 text-xs font-semibold text-sky-700 hover:bg-sky-50 rounded-lg transition-colors inline-flex items-center gap-1" title="Pantau Kinerja">
                                                    <i class="fa-solid fa-gauge-high text-[11px]" aria-hidden="true"></i>
                                                    <span>Pantau</span>
                                                </a>
                                            @endif
                                            
                                            {{-- Tombol Edit --}}
                                            <button type="button" 
                                                    @click="openEditModal({{ json_encode([
                                                        'id' => $user->id,
                                                        'name' => $user->name,
                                                        'email' => $user->email,
                                                        'role' => $user->role,
                                                        'phone' => $user->phone ?? '',
                                                        'office_id' => $user->office_id ?? ''
                                                    ]) }})" 
                                                    aria-label="Edit pengguna {{ $user->name }}" 
                                                    class="px-2.5 py-1 text-xs font-bold text-[#063f5c] hover:bg-slate-100 rounded-lg transition-colors inline-flex items-center gap-1" 
                                                    title="Edit Pengguna">
                                                <i class="fa-solid fa-pen-to-square text-[11px]" aria-hidden="true"></i>
                                                <span>Edit</span>
                                            </button>

                                            {{-- Tombol Hapus --}}
                                            @if(auth()->id() !== $user->id)
                                                <button type="button" 
                                                        @click="openDeleteModal({{ json_encode([
                                                            'id' => $user->id,
                                                            'name' => $user->name
                                                        ]) }})" 
                                                        aria-label="Hapus pengguna {{ $user->name }}" 
                                                        class="px-2.5 py-1 text-xs font-semibold text-rose-700 hover:bg-rose-50 rounded-lg transition-colors inline-flex items-center gap-1" 
                                                        title="Hapus Pengguna">
                                                    <i class="fa-regular fa-trash-can text-[11px]" aria-hidden="true"></i>
                                                    <span>Hapus</span>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="py-12 text-center text-slate-500 font-medium">Belum ada pengguna terdaftar yang sesuai filter.</td>
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

    {{-- Modal 1: Tambah Pengguna Baru --}}
    <div x-show="showAddModal" x-cloak @keydown.escape.window="showAddModal = false" role="dialog" aria-modal="true" aria-labelledby="add-user-title" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-950/60" @click="showAddModal = false"></div>
        <div class="relative z-10 bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 space-y-5 shadow-2xl border border-slate-100">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h2 id="add-user-title" class="font-montserrat font-bold text-slate-900 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-user-plus text-[#063f5c]"></i> Tambah Pengguna Baru
                </h2>
                <button type="button" @click="showAddModal = false" aria-label="Tutup modal tambah pengguna" class="w-10 h-10 inline-flex items-center justify-center rounded-xl text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition-colors">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            
            <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-3.5 text-xs font-semibold">
                @csrf
                <div>
                    <label for="add_user_name" class="block text-slate-700 mb-1">Nama Lengkap <span class="text-rose-600">*</span></label>
                    <input id="add_user_name" type="text" name="name" required placeholder="Contoh: Ahmad Fadli" class="w-full border border-slate-300 rounded-xl px-3.5 py-2.5 min-h-[44px] text-xs focus:border-[#063f5c] focus:outline-hidden">
                </div>
                <div>
                    <label for="add_user_email" class="block text-slate-700 mb-1">Email <span class="text-rose-600">*</span></label>
                    <input id="add_user_email" type="email" name="email" required placeholder="user@domain.com" class="w-full border border-slate-300 rounded-xl px-3.5 py-2.5 min-h-[44px] text-xs focus:border-[#063f5c] focus:outline-hidden">
                </div>
                <div x-data="{ show: false }">
                    <label for="add_user_password" class="block text-slate-700 mb-1">Password <span class="text-rose-600">*</span></label>
                    <div class="relative">
                        <input id="add_user_password" :type="show ? 'text' : 'password'" name="password" required placeholder="Minimal 6 karakter" class="w-full border border-slate-300 rounded-xl pl-3.5 pr-12 py-2.5 min-h-[44px] text-xs focus:border-[#063f5c] focus:outline-hidden">
                        <button type="button" @click="show = !show" :aria-label="show ? 'Sembunyikan password' : 'Lihat password'" class="absolute inset-y-0 right-0 flex items-center justify-center w-11 text-slate-500 hover:text-slate-800 transition-colors" :title="show ? 'Sembunyikan' : 'Lihat'">
                            <i class="fa-solid text-xs" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="add_user_role" class="block text-slate-700 mb-1">Hak Akses / Role <span class="text-rose-600">*</span></label>
                        <select id="add_user_role" name="role" required class="w-full border border-slate-300 rounded-xl px-3 py-2.5 min-h-[44px] text-xs focus:border-[#063f5c] focus:outline-hidden">
                            <option value="user">Donatur</option>
                            <option value="field_worker">Petugas Lapangan</option>
                            <option value="marketing">Staff Marketing & Meta Ads</option>
                            <option value="operator">Admin Data Entry</option>
                            <option value="finance">Staff Keuangan</option>
                            <option value="program_manager">Manager Program</option>
                            <option value="crm_staff">Staff CRM &amp; Telemarketing</option>
                            <option value="admin">Super Admin</option>
                        </select>
                    </div>
                    <div>
                        <label for="add_user_phone" class="block text-slate-700 mb-1">No. Telp / WA</label>
                        <input id="add_user_phone" type="text" name="phone" placeholder="08123456789" class="w-full border border-slate-300 rounded-xl px-3.5 py-2.5 min-h-[44px] text-xs focus:border-[#063f5c] focus:outline-hidden">
                    </div>
                </div>
                <div>
                    <label for="add_user_office" class="block text-slate-700 mb-1">Kantor / Cabang</label>
                    <select id="add_user_office" name="office_id" class="w-full border border-slate-300 rounded-xl px-3 py-2.5 min-h-[44px] text-xs focus:border-[#063f5c] focus:outline-hidden">
                        <option value="">-- Pilih Kantor (Opsional) --</option>
                        @foreach($offices as $ofc)
                            <option value="{{ $ofc->id }}">{{ $ofc->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex justify-end gap-2.5 pt-3 border-t border-slate-100">
                    <button type="button" @click="showAddModal = false" class="px-4 py-2.5 min-h-[44px] bg-white border border-slate-300 hover:bg-slate-100 text-slate-700 rounded-xl font-bold transition-colors">Batal</button>
                    <button type="submit" class="px-5 py-2.5 min-h-[44px] bg-[#063f5c] hover:bg-[#04324a] text-white rounded-xl font-bold transition-colors shadow-xs">Simpan Pengguna</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal 2: Edit Pengguna --}}
    <div x-show="showEditModal" x-cloak @keydown.escape.window="showEditModal = false" role="dialog" aria-modal="true" aria-labelledby="edit-user-title" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-950/60" @click="showEditModal = false"></div>
        <div class="relative z-10 bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 space-y-5 shadow-2xl border border-slate-100">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h2 id="edit-user-title" class="font-montserrat font-bold text-slate-900 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-pen-to-square text-[#063f5c]"></i> Edit Pengguna
                </h2>
                <button type="button" @click="showEditModal = false" aria-label="Tutup modal edit pengguna" class="w-10 h-10 inline-flex items-center justify-center rounded-xl text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition-colors">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            
            <form :action="'{{ url('admin/users') }}/' + editForm.id + '/update'" method="POST" class="space-y-3.5 text-xs font-semibold">
                @csrf
                @method('PUT')
                <div>
                    <label for="edit_user_name" class="block text-slate-700 mb-1">Nama Lengkap <span class="text-rose-600">*</span></label>
                    <input id="edit_user_name" type="text" name="name" required x-model="editForm.name" class="w-full border border-slate-300 rounded-xl px-3.5 py-2.5 min-h-[44px] text-xs focus:border-[#063f5c] focus:outline-hidden">
                </div>
                <div>
                    <label for="edit_user_email" class="block text-slate-700 mb-1">Email <span class="text-rose-600">*</span></label>
                    <input id="edit_user_email" type="email" name="email" required x-model="editForm.email" class="w-full border border-slate-300 rounded-xl px-3.5 py-2.5 min-h-[44px] text-xs focus:border-[#063f5c] focus:outline-hidden">
                </div>
                <div x-data="{ show: false }">
                    <label for="edit_user_password" class="block text-slate-700 mb-1">Password Baru (Kosongkan jika tidak ingin diubah)</label>
                    <div class="relative">
                        <input id="edit_user_password" :type="show ? 'text' : 'password'" name="password" placeholder="Min. 6 karakter jika mau ganti" class="w-full border border-slate-300 rounded-xl pl-3.5 pr-12 py-2.5 min-h-[44px] text-xs focus:border-[#063f5c] focus:outline-hidden">
                        <button type="button" @click="show = !show" :aria-label="show ? 'Sembunyikan password' : 'Lihat password'" class="absolute inset-y-0 right-0 flex items-center justify-center w-11 text-slate-500 hover:text-slate-800 transition-colors" :title="show ? 'Sembunyikan' : 'Lihat'">
                            <i class="fa-solid text-xs" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="edit_user_role" class="block text-slate-700 mb-1">Hak Akses / Role <span class="text-rose-600">*</span></label>
                        <select id="edit_user_role" name="role" required x-model="editForm.role" class="w-full border border-slate-300 rounded-xl px-3 py-2.5 min-h-[44px] text-xs focus:border-[#063f5c] focus:outline-hidden">
                            <option value="user">Donatur</option>
                            <option value="field_worker">Petugas Lapangan</option>
                            <option value="marketing">Staff Marketing & Meta Ads</option>
                            <option value="operator">Admin Data Entry</option>
                            <option value="finance">Staff Keuangan</option>
                            <option value="program_manager">Manager Program</option>
                            <option value="crm_staff">Staff CRM &amp; Telemarketing</option>
                            <option value="admin">Super Admin</option>
                        </select>
                    </div>
                    <div>
                        <label for="edit_user_phone" class="block text-slate-700 mb-1">No. Telp / WA</label>
                        <input id="edit_user_phone" type="text" name="phone" x-model="editForm.phone" placeholder="08123456789" class="w-full border border-slate-300 rounded-xl px-3.5 py-2.5 min-h-[44px] text-xs focus:border-[#063f5c] focus:outline-hidden">
                    </div>
                </div>
                <div>
                    <label for="edit_user_office" class="block text-slate-700 mb-1">Kantor / Cabang</label>
                    <select id="edit_user_office" name="office_id" x-model="editForm.office_id" class="w-full border border-slate-300 rounded-xl px-3 py-2.5 min-h-[44px] text-xs focus:border-[#063f5c] focus:outline-hidden">
                        <option value="">-- Pilih Kantor (Opsional) --</option>
                        @foreach($offices as $ofc)
                            <option value="{{ $ofc->id }}">{{ $ofc->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex justify-end gap-2.5 pt-3 border-t border-slate-100">
                    <button type="button" @click="showEditModal = false" class="px-4 py-2.5 min-h-[44px] bg-white border border-slate-300 hover:bg-slate-100 text-slate-700 rounded-xl font-bold transition-colors">Batal</button>
                    <button type="submit" class="px-5 py-2.5 min-h-[44px] bg-[#063f5c] hover:bg-[#04324a] text-white rounded-xl font-bold transition-colors shadow-xs">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal 3: Hapus Pengguna --}}
    <div x-show="showDeleteModal" x-cloak @keydown.escape.window="showDeleteModal = false" role="dialog" aria-modal="true" aria-labelledby="delete-user-title" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-950/60" @click="showDeleteModal = false"></div>
        <div class="relative z-10 bg-white rounded-3xl max-w-sm w-full p-6 sm:p-8 space-y-5 text-center shadow-2xl border border-slate-100">
            <div class="w-12 h-12 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 flex items-center justify-center mx-auto text-xl">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            
            <div class="space-y-1">
                <h2 id="delete-user-title" class="font-montserrat font-bold text-slate-900 text-base">Hapus Pengguna?</h2>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Apakah Anda yakin ingin menghapus akun <strong class="text-slate-900" x-text="deleteTarget.name"></strong>? Tindakan ini bersifat permanen.
                </p>
            </div>
            
            <form :action="'{{ url('admin/users') }}/' + deleteTarget.id + '/delete'" method="POST" class="flex justify-center gap-2.5 pt-2">
                @csrf
                @method('DELETE')
                <button type="button" @click="showDeleteModal = false" class="px-4 py-2.5 min-h-[44px] bg-white border border-slate-300 hover:bg-slate-100 text-slate-700 rounded-xl font-bold text-xs transition-colors">Batal</button>
                <button type="submit" class="px-5 py-2.5 min-h-[44px] bg-rose-700 text-white rounded-xl font-bold text-xs hover:bg-rose-800 transition-colors shadow-xs">Ya, Hapus</button>
            </form>
        </div>
    </div>

    {{-- Modal 4: Import Pengguna Excel / CSV --}}
    <div x-show="showImportModal" x-cloak @keydown.escape.window="showImportModal = false" role="dialog" aria-modal="true" aria-labelledby="import-user-title" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-950/60" @click="showImportModal = false"></div>
        <div class="relative z-10 bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 space-y-5 shadow-2xl border border-slate-100 my-8">
            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                <h2 id="import-user-title" class="font-montserrat font-bold text-slate-900 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-file-import text-emerald-700"></i> Import Data Pengguna (Excel / CSV)
                </h2>
                <button type="button" @click="showImportModal = false" aria-label="Tutup modal import pengguna" class="w-10 h-10 inline-flex items-center justify-center rounded-xl text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition-colors">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form action="{{ route('admin.users.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs font-semibold text-slate-700">
                @csrf
                <div class="space-y-1.5">
                    <label for="import_user_file" class="block font-bold text-slate-800">Pilih File Spreadsheet (.xlsx, .xls, .csv)</label>
                    <input id="import_user_file" type="file" name="file" accept=".xlsx,.xls,.csv,.txt" required class="w-full border border-slate-300 rounded-xl px-4 py-2.5 min-h-[44px] bg-slate-50 text-xs text-slate-700 focus:border-[#063f5c] focus:outline-hidden">
                </div>

                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 space-y-2.5 text-slate-600">
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-slate-800 text-xs">Format Template:</span>
                        <a href="{{ route('admin.users.template') }}" class="text-emerald-700 hover:text-emerald-800 hover:underline font-bold inline-flex items-center gap-1.5 text-xs">
                            <i class="fa-solid fa-download"></i> Unduh Format Template
                        </a>
                    </div>
                    <ul class="list-disc list-inside space-y-1 text-xs leading-relaxed text-slate-600">
                        <li><strong>Kolom Standar:</strong> Nama Lengkap, Email, Peran / Hak Akses, Password, No. Telp, Kantor / Cabang.</li>
                        <li><strong>Pilihan Hak Akses (Role):</strong> <code>admin</code> (Super Admin), <code>marketing</code>, <code>operator</code>, <code>finance</code>, <code>program_manager</code>, <code>crm_staff</code>, <code>field_worker</code>, <code>user</code> (Donatur).</li>
                        <li><strong>Password:</strong> Kosongkan jika ingin memakai password default (<code>password123</code>).</li>
                        <li><strong>Duplikasi Email:</strong> Akun dengan email yang sudah ada akan otomatis diperbarui datanya tanpa membuat akun duplikat.</li>
                    </ul>
                </div>

                <div class="flex justify-end gap-2.5 pt-3 border-t border-slate-100">
                    <button type="button" @click="showImportModal = false" class="px-5 py-2.5 min-h-[44px] rounded-xl border border-slate-300 text-slate-700 hover:bg-slate-50 font-bold transition-colors">Batal</button>
                    <button type="submit" class="px-5 py-2.5 min-h-[44px] rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold transition-colors shadow-xs inline-flex items-center gap-2">
                        <i class="fa-solid fa-cloud-arrow-up"></i> Upload &amp; Import
                    </button>
                </div>
            </form>
        </div>
    </div>

</section>

<!-- Alpine Script -->
<script>
function userManagement() {
    return {
        showAddModal: false,
        showEditModal: false,
        showDeleteModal: false,
        showImportModal: false,
        editForm: {
            id: null,
            name: '',
            email: '',
            role: 'user',
            phone: '',
            office_id: ''
        },
        deleteTarget: {
            id: null,
            name: ''
        },
        openAddModal() {
            this.showAddModal = true;
        },
        openEditModal(user) {
            this.editForm = {
                id: user.id,
                name: user.name,
                email: user.email,
                role: user.role,
                phone: user.phone || '',
                office_id: user.office_id || ''
            };
            this.showEditModal = true;
        },
        openDeleteModal(user) {
            this.deleteTarget = {
                id: user.id,
                name: user.name
            };
            this.showDeleteModal = true;
        }
    };
}
</script>
@endsection
