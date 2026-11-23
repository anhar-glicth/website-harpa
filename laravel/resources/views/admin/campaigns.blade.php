@extends('layouts.app')

@section('content')
<section class="max-w-7xl mx-auto px-6 py-12" x-data="campaignManagement()">
    
    <!-- Alerts -->
    @if(session('success'))
        <div role="status" class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl font-semibold text-xs flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-3">
                <i class="fa-solid fa-circle-check text-emerald-700 text-base"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" @click="$el.parentElement.remove()" aria-label="Tutup notifikasi" class="w-11 h-11 -my-2 inline-flex items-center justify-center rounded-xl text-emerald-700 hover:bg-emerald-100">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div role="alert" class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl font-semibold text-xs flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-3">
                <i class="fa-solid fa-triangle-exclamation text-rose-700 text-base"></i>
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
            @include('admin.sidebar', ['active' => 'campaigns'])
        </div>

        <!-- Dashboard Content -->
        <div class="lg:col-span-3 space-y-6">
            
            {{-- Header Page Title & Top Actions --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="space-y-1">
                    <h1 class="text-2xl sm:text-3xl font-montserrat font-black text-slate-900 uppercase tracking-tight italic">
                        Kelola <span class="text-[#063f5c]">Kampanye Donasi</span>
                    </h1>
                    <div class="w-12 h-1 bg-[#f7ad1a] rounded-full"></div>
                </div>

                <div class="flex flex-wrap items-center gap-2.5">
                    <button type="button" 
                            @click="openCreateModal()" 
                            class="px-5 py-2.5 min-h-[44px] bg-[#063f5c] hover:bg-[#04324a] text-white rounded-xl text-xs font-bold uppercase tracking-wider inline-flex items-center gap-2 shadow-xs transition-colors">
                        <i class="fa-solid fa-plus text-xs" aria-hidden="true"></i>
                        <span>+ Tambah Kampanye Baru</span>
                    </button>
                </div>
            </div>

            {{-- Stat Cards --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-xs">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500 block mb-1">Total Kampanye</span>
                    <span class="text-2xl font-bold font-montserrat text-slate-900">{{ number_format($totalCampaigns, 0, ',', '.') }}</span>
                </div>
                <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-xs">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500 block mb-1">Kampanye Aktif</span>
                    <span class="text-2xl font-bold font-montserrat text-emerald-800">{{ number_format($approvedCampaigns, 0, ',', '.') }}</span>
                </div>
                <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-xs">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500 block mb-1">Menunggu Review</span>
                    <span class="text-2xl font-bold font-montserrat text-amber-800">{{ number_format($pendingCampaigns, 0, ',', '.') }}</span>
                </div>
                <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-xs">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500 block mb-1">Total Terhimpun</span>
                    <span class="text-xl sm:text-2xl font-bold font-montserrat text-slate-900 truncate block">Rp {{ number_format($totalCollected, 0, ',', '.') }}</span>
                </div>
            </div>

            {{-- Search & Filter Toolbar --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-4">
                <form action="{{ route('admin.campaigns') }}" method="GET" class="flex flex-col sm:flex-row flex-wrap items-center gap-3">
                    
                    <div class="relative flex-1 min-w-[200px] w-full">
                        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs" aria-hidden="true"></i>
                        <input type="text" 
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Cari judul, kategori, atau deskripsi..." 
                               class="w-full bg-slate-50 border border-slate-300 rounded-xl pl-9 pr-4 py-2 min-h-[44px] text-xs font-medium text-slate-800 placeholder:text-slate-400 focus:border-primary-500 focus:bg-white transition-colors">
                    </div>

                    <div class="flex items-center gap-2 w-full sm:w-auto">
                        <select name="status" class="bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 min-h-[44px] text-xs font-bold text-slate-700 focus:border-primary-500 w-full sm:w-auto">
                            <option value="all">Semua Status</option>
                            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved (Aktif)</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending (Menunggu)</option>
                            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected (Ditolak)</option>
                        </select>

                        <select name="category" class="bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 min-h-[44px] text-xs font-bold text-slate-700 focus:border-primary-500 w-full sm:w-auto">
                            <option value="all">Semua Kategori</option>
                            @if(isset($programGroups) && $programGroups->count() > 0)
                                @foreach($programGroups as $pg)
                                    <option value="{{ $pg->name }}" {{ request('category') === $pg->name ? 'selected' : '' }}>{{ $pg->name }}</option>
                                @endforeach
                            @elseif(isset($categories))
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="flex items-center gap-2 w-full sm:w-auto">
                        <button type="submit" class="px-4 py-2 min-h-[44px] bg-[#063f5c] hover:bg-[#04324a] text-white rounded-xl text-xs font-bold uppercase tracking-wider inline-flex items-center justify-center gap-1.5 transition-colors flex-1 sm:flex-initial">
                            <i class="fa-solid fa-filter text-xs"></i>
                            <span>Filter</span>
                        </button>
                        @if(request('search') || (request('status') && request('status') !== 'all') || (request('category') && request('category') !== 'all'))
                            <a href="{{ route('admin.campaigns') }}" class="px-3 py-2 min-h-[44px] text-rose-700 hover:text-rose-800 text-xs font-bold inline-flex items-center justify-center underline">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Campaign list table -->
            <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-xs space-y-4 overflow-hidden">
                <div class="flex flex-wrap items-center justify-between gap-3 pb-3 border-b border-slate-100">
                    <div>
                        <h2 class="font-montserrat font-bold text-slate-900 text-sm tracking-wide">
                            Daftar Program Kampanye
                        </h2>
                        <p class="text-xs text-slate-500 font-medium mt-0.5">
                            Kelola data, capaian donasi, dan status publikasi kampanye kemanusiaan.
                        </p>
                    </div>
                    <span class="text-xs text-slate-500 font-medium">
                        Menampilkan {{ $campaigns->count() }} dari {{ $campaigns->total() }} kampanye
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-700">
                        <thead class="bg-slate-50 text-xs font-bold uppercase tracking-wider text-slate-500 border-b border-slate-100">
                            <tr>
                                <th scope="col" class="py-3 px-4">Kampanye</th>
                                <th scope="col" class="py-3 px-4">Target &amp; Capaian</th>
                                <th scope="col" class="py-3 px-4">Pembuat</th>
                                <th scope="col" class="py-3 px-4">Status</th>
                                <th scope="col" class="py-3 px-4 text-right">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-semibold">
                            @forelse($campaigns as $c)
                                @php
                                    $percent = $c->target_amount > 0 ? min(100, round(($c->current_amount / $c->target_amount) * 100)) : 0;
                                @endphp
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="py-4 px-4">
                                        <div class="flex items-center gap-3">
                                            @if($c->foto)
                                                <img src="{{ $c->foto }}" alt="{{ $c->title }}" class="w-14 h-14 rounded-xl object-cover border border-slate-200 shrink-0">
                                            @else
                                                <div class="w-14 h-14 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-400 shrink-0">
                                                    <i class="fa-regular fa-image text-lg"></i>
                                                </div>
                                            @endif
                                            <div class="space-y-1 min-w-0">
                                                <a href="{{ route('campaigns.show', $c->id) }}" target="_blank" class="font-bold text-slate-900 hover:text-[#063f5c] text-sm line-clamp-1 block" title="{{ $c->title }}">
                                                    {{ $c->title }}
                                                </a>
                                                <div class="flex items-center gap-2">
                                                    <span class="px-2 py-0.5 rounded text-[11px] font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                                        {{ $c->category ?? 'Kemanusiaan' }}
                                                    </span>
                                                    <span class="text-slate-400 text-[11px] font-mono">#{{ $c->id }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-4">
                                        <div class="space-y-1.5 min-w-[190px] max-w-[230px]">
                                            <div class="flex items-baseline justify-between gap-2">
                                                <span class="text-xs font-bold text-slate-900 tabular-nums">
                                                    Rp {{ number_format($c->current_amount, 0, ',', '.') }}
                                                </span>
                                                <span class="text-xs font-bold text-[#063f5c] tabular-nums">
                                                    {{ $percent }}%
                                                </span>
                                            </div>
                                            <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden border border-slate-200">
                                                <div class="h-full bg-[#063f5c] rounded-full transition-all duration-300" style="width: {{ $percent }}%"></div>
                                            </div>
                                            <div class="flex items-center justify-between text-[11px] text-slate-500 font-medium whitespace-nowrap">
                                                <span class="tabular-nums">Target: Rp {{ number_format($c->target_amount, 0, ',', '.') }}</span>
                                                <span class="text-slate-500">• {{ $c->donations_count ?? 0 }} donasi</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-4 whitespace-nowrap">
                                        <div class="space-y-0.5">
                                            <span class="text-slate-900 font-bold text-xs block">{{ $c->author->name ?? 'Admin Humanity' }}</span>
                                            <span class="text-xs text-slate-500 font-medium block">{{ optional($c->created_at)->format('d M Y') ?? '-' }}</span>
                                        </div>
                                    </td>
                                    <td class="py-4 px-4 whitespace-nowrap">
                                        @if($c->status === 'approved')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                                Aktif
                                            </span>
                                        @elseif($c->status === 'pending')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-amber-50 text-amber-900 border border-amber-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-600"></span>
                                                Review
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-rose-50 text-rose-900 border border-rose-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-600"></span>
                                                Ditolak
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-4 text-right whitespace-nowrap">
                                        <div class="inline-flex items-center gap-1 justify-end">
                                            
                                            {{-- Tombol Lihat di Web --}}
                                            <a href="{{ route('campaigns.show', $c->id) }}" target="_blank" aria-label="Lihat pratinjau halaman kampanye {{ $c->title }}"
                                               class="px-2.5 py-1.5 text-xs font-semibold text-slate-600 hover:text-[#063f5c] hover:bg-slate-100 rounded-lg transition-colors inline-flex items-center gap-1" title="Lihat di Web">
                                                <i class="fa-solid fa-arrow-up-right-from-square text-[11px] text-slate-400"></i>
                                                <span>Lihat</span>
                                            </a>

                                            {{-- Tombol Edit --}}
                                            <button type="button" @click="openEditModal({{ $c->toJson() }})" aria-label="Edit kampanye {{ $c->title }}"
                                                    class="px-2.5 py-1.5 text-xs font-bold text-[#063f5c] hover:bg-slate-100 rounded-lg transition-colors inline-flex items-center gap-1" title="Edit Data Kampanye">
                                                <i class="fa-solid fa-pen-to-square text-[11px]"></i>
                                                <span>Edit</span>
                                            </button>

                                            {{-- Toggle Status --}}
                                            @if($c->status === 'approved')
                                                <form action="{{ route('admin.campaigns.reject', $c->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" aria-label="Nonaktifkan status kampanye {{ $c->title }}"
                                                            class="px-2.5 py-1.5 text-xs font-semibold text-amber-700 hover:bg-amber-50 rounded-lg transition-colors inline-flex items-center gap-1" title="Tolak / Nonaktifkan Kampanye">
                                                        <i class="fa-solid fa-ban text-[11px]"></i>
                                                        <span>Tolak</span>
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('admin.campaigns.approve', $c->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" aria-label="Setujui dan aktifkan kampanye {{ $c->title }}"
                                                            class="px-2.5 py-1.5 text-xs font-semibold text-emerald-700 hover:bg-emerald-50 rounded-lg transition-colors inline-flex items-center gap-1" title="Setujui Kampanye">
                                                        <i class="fa-solid fa-check text-[11px]"></i>
                                                        <span>Setujui</span>
                                                    </button>
                                                </form>
                                            @endif

                                            {{-- Tombol Hapus --}}
                                            <button type="button" @click="openDeleteModal({{ $c->toJson() }})" aria-label="Hapus kampanye {{ $c->title }}"
                                                    class="px-2.5 py-1.5 text-xs font-semibold text-rose-700 hover:bg-rose-50 rounded-lg transition-colors inline-flex items-center gap-1" title="Hapus Kampanye">
                                                <i class="fa-regular fa-trash-can text-[11px]"></i>
                                                <span>Hapus</span>
                                            </button>

                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-10 text-center text-slate-500 font-bold">
                                        <div class="flex flex-col items-center justify-center gap-2">
                                            <i class="fa-solid fa-bullhorn text-slate-400 text-3xl"></i>
                                            <p>Belum ada data kampanye yang cocok dengan pencarian / filter.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($campaigns->hasPages())
                    <div class="pt-4 border-t border-slate-100">
                        {{ $campaigns->links() }}
                    </div>
                @endif
            </div>

        </div>

    </div>

    <!-- Modal 1: Tambah Kampanye Baru -->
    <div x-show="createModal.open" 
         x-cloak
         @keydown.escape.window="createModal.open = false"
         role="dialog"
         aria-modal="true"
         aria-labelledby="modal-create-title"
         style="display: none;"
         class="fixed inset-0 z-[250] flex items-center justify-center p-4 overflow-y-auto">
        <div class="fixed inset-0 bg-slate-950/60" @click="createModal.open = false"></div>
        
        <div class="relative z-10 bg-white border border-slate-100 w-full max-w-2xl rounded-3xl p-6 sm:p-8 shadow-2xl space-y-6 my-8">
            <button type="button" @click="createModal.open = false" aria-label="Tutup modal tambah kampanye"
                    class="w-11 h-11 inline-flex items-center justify-center rounded-xl absolute top-4 right-4 text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition-colors">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>

            <div class="space-y-1 pr-10">
                <h2 id="modal-create-title" class="text-xl font-montserrat font-black text-slate-900 uppercase tracking-tight italic">
                    Tambah <span class="text-primary-600">Kampanye Baru</span>
                </h2>
                <p class="text-xs text-slate-600 font-bold uppercase tracking-wider">
                    Lengkapi formulir di bawah ini untuk menerbitkan program penggalangan dana baru
                </p>
            </div>

            <form action="{{ route('admin.campaigns.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                
                <div>
                    <label for="create_camp_title" class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-1.5">Judul Kampanye <span class="text-rose-600">*</span></label>
                    <input id="create_camp_title" type="text" name="title" required placeholder="Contoh: Bantuan Sembako dan Pangan Hangat Gaza"
                           class="w-full min-h-[44px] bg-slate-50 border border-slate-300 rounded-2xl px-4 py-3 text-xs font-bold text-slate-800 focus:border-primary-500 focus:bg-white transition-colors">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label for="create_camp_category" class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-1.5">Kategori <span class="text-rose-600">*</span></label>
                        <select id="create_camp_category" name="category" required class="w-full min-h-[44px] bg-slate-50 border border-slate-300 rounded-2xl px-3 py-3 text-xs font-bold text-slate-800 focus:border-primary-500 focus:bg-white transition-colors">
                            @if(isset($programGroups) && $programGroups->count() > 0)
                                @foreach($programGroups as $pg)
                                    <option value="{{ $pg->name }}">{{ $pg->name }}</option>
                                @endforeach
                            @else
                                <option value="Kemanusiaan">Kemanusiaan</option>
                                <option value="Pendidikan">Pendidikan</option>
                                <option value="Kesehatan">Kesehatan</option>
                                <option value="Dakwah">Dakwah</option>
                            @endif
                        </select>
                    </div>

                    <div>
                        <label for="create_camp_target" class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-1.5">Target Donasi (Rp) <span class="text-rose-600">*</span></label>
                        <input id="create_camp_target" type="number" name="target_amount" required min="1000" placeholder="Contoh: 50000000"
                               class="w-full min-h-[44px] bg-slate-50 border border-slate-300 rounded-2xl px-4 py-3 text-xs font-bold text-slate-800 focus:border-primary-500 focus:bg-white transition-colors">
                    </div>

                    <div>
                        <label for="create_camp_status" class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-1.5">Status Publikasi <span class="text-rose-600">*</span></label>
                        <select id="create_camp_status" name="status" required class="w-full min-h-[44px] bg-slate-50 border border-slate-300 rounded-2xl px-3 py-3 text-xs font-bold text-slate-800 focus:border-primary-500 focus:bg-white transition-colors">
                            <option value="approved" selected>Approved (Langsung Tayang)</option>
                            <option value="pending">Pending (Menunggu Review)</option>
                            <option value="rejected">Rejected (Nonaktif)</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="create_camp_foto" class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-1.5">Foto Sampul Kampanye</label>
                    <input id="create_camp_foto" type="file" name="foto" accept="image/*"
                           class="w-full min-h-[44px] bg-slate-50 border border-slate-300 rounded-2xl p-2.5 text-xs text-slate-700 focus:border-primary-500">
                    <p class="text-[11px] text-slate-500 mt-1">Format: JPG, PNG, WEBP (Maksimal 3MB). Rasio lanskap disarankan.</p>
                </div>

                <div>
                    <label for="create_camp_desc" class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-1.5">Deskripsi Lengkap Program <span class="text-rose-600">*</span></label>
                    <textarea id="create_camp_desc" name="description" rows="5" required placeholder="Tuliskan latar belakang kampanye, rencana penyaluran, serta dampak yang diharapkan..."
                              class="w-full bg-slate-50 border border-slate-300 rounded-2xl p-4 text-xs font-medium text-slate-800 focus:border-primary-500 focus:bg-white transition-colors"></textarea>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                    <button type="button" @click="createModal.open = false" 
                            class="px-5 py-2.5 min-h-[44px] bg-white border border-slate-300 hover:bg-slate-100 text-slate-700 rounded-xl text-xs font-bold uppercase tracking-wider transition-colors">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-6 py-2.5 min-h-[44px] bg-[#063f5c] hover:bg-[#04324a] text-white rounded-xl text-xs font-black uppercase tracking-wider transition-colors shadow-xs">
                        Simpan Kampanye
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal 2: Edit Kampanye -->
    <div x-show="editModal.open" 
         x-cloak
         @keydown.escape.window="editModal.open = false"
         role="dialog"
         aria-modal="true"
         aria-labelledby="modal-edit-title"
         style="display: none;"
         class="fixed inset-0 z-[250] flex items-center justify-center p-4 overflow-y-auto">
        <div class="fixed inset-0 bg-slate-950/60" @click="editModal.open = false"></div>
        
        <div class="relative z-10 bg-white border border-slate-100 w-full max-w-2xl rounded-3xl p-6 sm:p-8 shadow-2xl space-y-6 my-8">
            <button type="button" @click="editModal.open = false" aria-label="Tutup modal edit kampanye"
                    class="w-11 h-11 inline-flex items-center justify-center rounded-xl absolute top-4 right-4 text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition-colors">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>

            <div class="space-y-1 pr-10">
                <div class="flex items-center gap-2">
                    <span class="text-xs font-mono font-bold text-slate-500" x-text="'ID #' + editModal.data.id"></span>
                </div>
                <h2 id="modal-edit-title" class="text-xl font-montserrat font-black text-slate-900 uppercase tracking-tight italic">
                    Edit <span class="text-primary-600">Data Kampanye</span>
                </h2>
                <p class="text-xs text-slate-600 font-bold uppercase tracking-wider">
                    Perbarui rincian, target donasi, atau status publikasi program kampanye
                </p>
            </div>

            <form :action="'{{ url('admin/campaigns') }}/' + editModal.data.id + '/update'" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')
                
                <div>
                    <label for="edit_camp_title" class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-1.5">Judul Kampanye <span class="text-rose-600">*</span></label>
                    <input id="edit_camp_title" type="text" name="title" required x-model="editModal.data.title"
                           class="w-full min-h-[44px] bg-slate-50 border border-slate-300 rounded-2xl px-4 py-3 text-xs font-bold text-slate-800 focus:border-primary-500 focus:bg-white transition-colors">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label for="edit_camp_category" class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-1.5">Kategori <span class="text-rose-600">*</span></label>
                        <select id="edit_camp_category" name="category" required x-model="editModal.data.category" class="w-full min-h-[44px] bg-slate-50 border border-slate-300 rounded-2xl px-3 py-3 text-xs font-bold text-slate-800 focus:border-primary-500 focus:bg-white transition-colors">
                            @if(isset($programGroups) && $programGroups->count() > 0)
                                @foreach($programGroups as $pg)
                                    <option value="{{ $pg->name }}">{{ $pg->name }}</option>
                                @endforeach
                            @else
                                <option value="Kemanusiaan">Kemanusiaan</option>
                                <option value="Pendidikan">Pendidikan</option>
                                <option value="Kesehatan">Kesehatan</option>
                                <option value="Dakwah">Dakwah</option>
                            @endif
                        </select>
                    </div>

                    <div>
                        <label for="edit_camp_target" class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-1.5">Target Donasi (Rp) <span class="text-rose-600">*</span></label>
                        <input id="edit_camp_target" type="number" name="target_amount" required min="1000" x-model="editModal.data.target_amount"
                               class="w-full min-h-[44px] bg-slate-50 border border-slate-300 rounded-2xl px-4 py-3 text-xs font-bold text-slate-800 focus:border-primary-500 focus:bg-white transition-colors">
                    </div>

                    <div>
                        <label for="edit_camp_status" class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-1.5">Status Publikasi <span class="text-rose-600">*</span></label>
                        <select id="edit_camp_status" name="status" required x-model="editModal.data.status" class="w-full min-h-[44px] bg-slate-50 border border-slate-300 rounded-2xl px-3 py-3 text-xs font-bold text-slate-800 focus:border-primary-500 focus:bg-white transition-colors">
                            <option value="approved">Approved (Tayang Aktif)</option>
                            <option value="pending">Pending (Menunggu Review)</option>
                            <option value="rejected">Rejected (Nonaktif/Ditolak)</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="edit_camp_foto" class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-1.5">Foto Sampul Kampanye</label>
                    <template x-if="editModal.data.foto">
                        <div class="mb-2 flex items-center gap-3 p-2.5 bg-slate-50 border border-slate-200 rounded-xl">
                            <img :src="editModal.data.foto" alt="Sampul saat ini" class="w-14 h-14 rounded-lg object-cover border border-slate-200">
                            <span class="text-xs text-slate-600 font-semibold">Foto saat ini aktif. Unggah file baru di bawah ini jika ingin mengganti.</span>
                        </div>
                    </template>
                    <input id="edit_camp_foto" type="file" name="foto" accept="image/*"
                           class="w-full min-h-[44px] bg-slate-50 border border-slate-300 rounded-2xl p-2.5 text-xs text-slate-700 focus:border-primary-500">
                </div>

                <div>
                    <label for="edit_camp_desc" class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-1.5">Deskripsi Lengkap Program <span class="text-rose-600">*</span></label>
                    <textarea id="edit_camp_desc" name="description" rows="5" required x-model="editModal.data.description"
                              class="w-full bg-slate-50 border border-slate-300 rounded-2xl p-4 text-xs font-medium text-slate-800 focus:border-primary-500 focus:bg-white transition-colors"></textarea>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                    <button type="button" @click="editModal.open = false" 
                            class="px-5 py-2.5 min-h-[44px] bg-white border border-slate-300 hover:bg-slate-100 text-slate-700 rounded-xl text-xs font-bold uppercase tracking-wider transition-colors">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-6 py-2.5 min-h-[44px] bg-[#063f5c] hover:bg-[#04324a] text-white rounded-xl text-xs font-black uppercase tracking-wider transition-colors shadow-xs">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal 3: Konfirmasi Hapus Kampanye -->
    <div x-show="deleteModal.open" 
         x-cloak
         @keydown.escape.window="deleteModal.open = false"
         role="dialog"
         aria-modal="true"
         aria-labelledby="modal-delete-title"
         style="display: none;"
         class="fixed inset-0 z-[250] flex items-center justify-center p-4 overflow-y-auto">
        <div class="fixed inset-0 bg-slate-950/60" @click="deleteModal.open = false"></div>
        
        <div class="relative z-10 bg-white border border-slate-100 w-full max-w-md rounded-3xl p-6 sm:p-8 shadow-2xl space-y-5 my-8">
            <div class="w-12 h-12 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 flex items-center justify-center text-xl">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>

            <div class="space-y-1">
                <h2 id="modal-delete-title" class="text-lg font-montserrat font-black text-slate-900 tracking-tight">
                    Hapus Kampanye?
                </h2>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Apakah Anda yakin ingin menghapus kampanye <strong class="text-slate-900" x-text="deleteModal.data.title"></strong>? Data kampanye beserta riwayat transaksi dummy/testing terkait akan dihapus secara permanen.
                </p>
            </div>

            <form :action="'{{ url('admin/campaigns') }}/' + deleteModal.data.id + '/delete'" method="POST" class="pt-2 flex items-center justify-end gap-3">
                @csrf
                @method('DELETE')
                <button type="button" @click="deleteModal.open = false" 
                        class="px-4 py-2.5 min-h-[44px] bg-white border border-slate-300 hover:bg-slate-100 text-slate-700 rounded-xl text-xs font-bold uppercase tracking-wider transition-colors">
                    Batal
                </button>
                <button type="submit" 
                        class="px-5 py-2.5 min-h-[44px] bg-rose-700 hover:bg-rose-800 text-white rounded-xl text-xs font-black uppercase tracking-wider transition-colors shadow-xs">
                    Ya, Hapus
                </button>
            </form>
        </div>
    </div>

</section>

<!-- Alpine Script -->
<script>
function campaignManagement() {
    return {
        createModal: {
            open: false
        },
        editModal: {
            open: false,
            data: {}
        },
        deleteModal: {
            open: false,
            data: {}
        },
        openCreateModal() {
            this.createModal.open = true;
        },
        openEditModal(item) {
            this.editModal.data = Object.assign({}, item);
            this.editModal.open = true;
        },
        openDeleteModal(item) {
            this.deleteModal.data = Object.assign({}, item);
            this.deleteModal.open = true;
        }
    };
}
</script>
@endsection
