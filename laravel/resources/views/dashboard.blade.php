@extends('layouts.app')

@section('content')
<section class="max-w-7xl mx-auto px-6 py-20">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- Sidebar Links -->
        <div class="lg:col-span-1 space-y-6">
            @include('layouts.donor-sidebar', ['active' => 'dashboard'])
        </div>

        <!-- Dashboard Content -->
        <div class="lg:col-span-3 space-y-8">
            <div class="space-y-2">
                <h1 class="text-2xl sm:text-3xl font-montserrat font-black text-slate-900 uppercase tracking-tight italic">
                    Ringkasan <span class="text-primary-600">Aktivitas Donatur</span>
                </h1>
                <div class="w-12 h-1 bg-primary-500 rounded-full"></div>
            </div>


            {{-- ✅ Personal Absensi & Todo Widget — Hanya untuk Staff Internal (bukan donatur) --}}
            @if(!Auth::user()->isUser())
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- Absensi GPS Card --}}
                <div x-data="{ showAbsenForm: false }" class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-montserrat font-black text-slate-900 uppercase tracking-tight italic text-sm">
                                Absensi <span class="text-primary-600">Hari Ini</span>
                            </h3>
                            <div class="mt-1">
                                <x-live-clock variant="inline" />
                            </div>
                        </div>
                        <button @click="showAbsenForm = !showAbsenForm"
                            class="px-3 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-xl text-[9px] font-black uppercase tracking-wider flex items-center gap-1.5 shadow-sm transition-all">
                            <i class="fa-solid fa-stamp"></i> Catat
                        </button>
                    </div>
                    @php
                        $hasCI = $todayAttendance->where('type','check_in')->isNotEmpty();
                        $hasCO = $todayAttendance->where('type','check_out')->isNotEmpty();
                        $hasSK = $todayAttendance->where('type','sakit')->isNotEmpty();
                        $hasIZ = $todayAttendance->where('type','izin')->isNotEmpty();
                    @endphp
                    <div class="grid grid-cols-2 gap-2">
                        <div class="flex items-center gap-2 p-2.5 rounded-xl border {{ $hasCI ? 'bg-emerald-50 border-emerald-200' : 'bg-slate-50 border-slate-100' }}">
                            <i class="fa-solid fa-right-to-bracket text-xs {{ $hasCI ? 'text-emerald-600' : 'text-slate-300' }}"></i>
                            <div>
                                <p class="text-[8px] font-black uppercase tracking-wider {{ $hasCI ? 'text-emerald-700' : 'text-slate-400' }}">Check In</p>
                                <p class="text-[9px] font-bold text-slate-500">{{ $hasCI ? $todayAttendance->where('type','check_in')->first()->created_at->format('H:i') . ' WITA' : '—' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 p-2.5 rounded-xl border {{ $hasCO ? 'bg-blue-50 border-blue-200' : 'bg-slate-50 border-slate-100' }}">
                            <i class="fa-solid fa-right-from-bracket text-xs {{ $hasCO ? 'text-blue-600' : 'text-slate-300' }}"></i>
                            <div>
                                <p class="text-[8px] font-black uppercase tracking-wider {{ $hasCO ? 'text-blue-700' : 'text-slate-400' }}">Check Out</p>
                                <p class="text-[9px] font-bold text-slate-500">{{ $hasCO ? $todayAttendance->where('type','check_out')->first()->created_at->format('H:i') . ' WITA' : '—' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 p-2.5 rounded-xl border {{ $hasSK ? 'bg-amber-50 border-amber-200' : 'bg-slate-50 border-slate-100' }}">
                            <i class="fa-solid fa-kit-medical text-xs {{ $hasSK ? 'text-amber-600' : 'text-slate-300' }}"></i>
                            <div>
                                <p class="text-[8px] font-black uppercase tracking-wider {{ $hasSK ? 'text-amber-700' : 'text-slate-400' }}">Sakit</p>
                                <p class="text-[9px] font-bold text-slate-500">{{ $hasSK ? '✓ Tercatat' : '—' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 p-2.5 rounded-xl border {{ $hasIZ ? 'bg-red-50 border-red-200' : 'bg-slate-50 border-slate-100' }}">
                            <i class="fa-solid fa-file-circle-exclamation text-xs {{ $hasIZ ? 'text-red-500' : 'text-slate-300' }}"></i>
                            <div>
                                <p class="text-[8px] font-black uppercase tracking-wider {{ $hasIZ ? 'text-red-600' : 'text-slate-400' }}">Izin</p>
                                <p class="text-[9px] font-bold text-slate-500">{{ $hasIZ ? '✓ Tercatat' : '—' }}</p>
                            </div>
                        </div>
                    </div>
                    <div x-show="showAbsenForm" x-transition style="display:none;" class="border-t border-slate-100 pt-4">
                        <form action="{{ route('attendance.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                            @csrf
                            <input type="hidden" name="latitude" id="donor_lat">
                            <input type="hidden" name="longitude" id="donor_lng">

                            <select name="type" required class="w-full bg-slate-50 border border-slate-150 rounded-2xl px-4 py-3 text-xs font-bold text-slate-800 outline-none focus:border-primary-500">
                                <option value="check_in">Check In (Masuk)</option>
                                <option value="check_out">Check Out (Pulang)</option>
                                <option value="sakit">Sakit</option>
                                <option value="izin">Izin</option>
                            </select>
                            <input type="text" name="location" id="donor_loc" placeholder="Nama lokasi / kantor..." class="w-full bg-slate-50 border border-slate-150 rounded-2xl px-4 py-3 text-xs font-bold text-slate-800 outline-none focus:border-primary-500">
                            
                            <x-gps-detector latId="donor_lat" lngId="donor_lng" statusId="donor-gps-status" locationId="donor_loc" />

                            <input type="file" name="attachment" accept="image/*" class="w-full bg-slate-50 border border-slate-150 rounded-2xl px-4 py-2.5 text-xs">
                            <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-black text-xs uppercase tracking-wider py-3 rounded-2xl transition-all flex items-center justify-center gap-2">
                                <i class="fa-solid fa-stamp"></i> Kirim Absensi
                            </button>
                        </form>
                    </div>
                </div>

                {{-- To-Do List Card --}}
                <div x-data="{ showAddForm: false, expandedId: null, modalOpen: false, activeTodoId: null, activeTodoTask: '' }"
                     class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-montserrat font-black text-slate-900 uppercase tracking-tight italic text-sm">
                                To-Do <span class="text-primary-600">Saya</span>
                            </h3>
                            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">
                                {{ $todos->where('status','completed')->count() }}/{{ $todos->count() }} Selesai
                            </p>
                        </div>
                        <button @click="showAddForm = !showAddForm"
                            class="px-3 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-xl text-[9px] font-black uppercase tracking-wider flex items-center gap-1.5 shadow-sm transition-all">
                            <i class="fa-solid fa-plus"></i> Tambah
                        </button>
                    </div>
                    <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full bg-primary-600 rounded-full transition-all duration-500"
                             style="width: {{ $todos->count() ? ($todos->where('status','completed')->count() / $todos->count()) * 100 : 0 }}%"></div>
                    </div>
                    <div x-show="showAddForm" x-transition style="display:none;" class="border-t border-slate-100 pt-3">
                        <form action="{{ route('todo.store') }}" method="POST" class="space-y-2.5">
                            @csrf
                            <input type="text" name="task" required placeholder="Judul tugas baru..." class="w-full bg-slate-50 border border-slate-150 rounded-2xl px-4 py-3 text-xs font-bold text-slate-800 outline-none focus:border-primary-500">
                            <textarea name="description" rows="2" placeholder="Deskripsi..." class="w-full bg-slate-50 border border-slate-150 rounded-2xl px-4 py-2.5 text-xs font-semibold text-slate-800 outline-none focus:border-primary-500"></textarea>
                            <input type="datetime-local" name="deadline" class="w-full bg-slate-50 border border-slate-150 rounded-2xl px-4 py-2.5 text-xs font-bold text-slate-800 outline-none focus:border-primary-500">
                            <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-black text-xs uppercase tracking-wider py-3 rounded-2xl transition-all">
                                Tambah Tugas
                            </button>
                        </form>
                    </div>
                    <div class="space-y-2 max-h-56 overflow-y-auto pr-1">
                        @forelse($todos as $t)
                            <div @click="expandedId = (expandedId === {{ $t->id }} ? null : {{ $t->id }})"
                                 :class="expandedId === {{ $t->id }} ? 'border-primary-200 bg-primary-50/20' : 'border-slate-100 bg-slate-50/30'"
                                 class="p-3 rounded-2xl border cursor-pointer space-y-2 transition-all hover:border-primary-100">
                                <div class="flex items-center justify-between gap-2">
                                    <div class="flex items-center gap-2 flex-1 min-w-0">
                                        <span class="shrink-0 w-4 h-4 rounded-full border-2 flex items-center justify-center {{ $t->status === 'completed' ? 'bg-emerald-500 border-emerald-500' : 'border-slate-300' }}">
                                            @if($t->status === 'completed')<i class="fa-solid fa-check text-white text-[7px]"></i>@endif
                                        </span>
                                        <p class="text-[10px] font-black text-slate-800 uppercase tracking-wide truncate {{ $t->status === 'completed' ? 'line-through text-slate-400' : '' }}">{{ $t->task }}</p>
                                    </div>
                                    <i class="fa-solid fa-chevron-down text-[8px] text-slate-400 shrink-0 transition-transform duration-200"
                                       :class="expandedId === {{ $t->id }} ? 'rotate-180 text-primary-600' : ''"></i>
                                </div>
                                <div x-show="expandedId === {{ $t->id }}" @click.stop style="display:none;" class="border-t border-slate-100 pt-2 space-y-2">
                                    @if($t->deadline)<p class="text-[9px] font-bold text-red-500">⏰ {{ $t->deadline->format('d M Y H:i') }}</p>@endif
                                    @if($t->status === 'pending')
                                        <button @click.stop="activeTodoId = {{ $t->id }}; activeTodoTask = '{{ addslashes($t->task) }}'; modalOpen = true"
                                            class="w-full bg-primary-600 hover:bg-primary-700 text-white font-black text-[9px] uppercase tracking-wider px-3 py-2 rounded-xl">
                                            Tandai Selesai & Upload Bukti
                                        </button>
                                    @else
                                        <span class="text-emerald-700 text-[9px] font-black uppercase tracking-wider"><i class="fa-solid fa-circle-check mr-1"></i>Selesai {{ $t->completed_at?->format('d M Y') }}</span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-[10px] text-slate-400 font-bold uppercase tracking-wider py-4">Belum ada tugas.</p>
                        @endforelse
                    </div>
                    <div x-show="modalOpen" style="display:none;" class="fixed inset-0 z-[200] flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-sm">
                        <div @click.away="modalOpen = false" class="bg-white border border-slate-100 w-full max-w-md rounded-3xl p-8 shadow-2xl space-y-5 relative">
                            <button @click="modalOpen = false" class="absolute top-5 right-5 text-slate-400 hover:text-slate-700"><i class="fa-solid fa-xmark text-lg"></i></button>
                            <div>
                                <h4 class="font-montserrat font-black text-slate-900 uppercase tracking-tight text-sm">Upload <span class="text-primary-600">Bukti Selesai</span></h4>
                                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-1">Tugas: <span class="text-slate-700" x-text="activeTodoTask"></span></p>
                            </div>
                            <form :action="'/todo/' + activeTodoId + '/update'" method="POST" enctype="multipart/form-data" class="space-y-3">
                                @csrf
                                <input type="hidden" name="status" value="completed">
                                <textarea name="result_note" rows="3" placeholder="Catatan hasil kerja..." class="w-full bg-slate-50 border border-slate-150 rounded-2xl px-4 py-3 text-xs font-semibold text-slate-800 outline-none focus:border-primary-500"></textarea>
                                <input type="file" name="result_attachment" accept="image/*" class="w-full bg-slate-50 border border-slate-150 rounded-2xl px-4 py-2.5 text-xs">
                                <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-black text-xs uppercase tracking-wider py-3.5 rounded-2xl shadow-lg transition-all">
                                    Kirim Bukti & Tandai Selesai
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>{{-- end personal widget row --}}
            @endif {{-- end @if(!Auth::user()->isUser()) --}}

            <!-- Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Total Donated -->
                <div class="bg-white border border-slate-100 p-8 rounded-3xl shadow-sm flex items-center space-x-6 hover:shadow-lg hover:shadow-primary-500/5 hover:-translate-y-0.5 transition-all duration-300">
                    <div class="bg-emerald-50 text-emerald-600 w-16 h-16 rounded-2xl flex items-center justify-center text-2xl shrink-0">
                        <i class="fa-solid fa-hand-holding-heart"></i>
                    </div>
                    <div>
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block leading-none">Total Donasi Anda</span>
                        <h3 class="text-xl sm:text-2xl font-montserrat font-black text-slate-800 leading-none mt-1.5">
                            Rp {{ number_format($totalDonated, 0, ',', '.') }}
                        </h3>
                    </div>
                </div>

                <!-- Total Zakat Paid -->
                <div class="bg-white border border-slate-100 p-8 rounded-3xl shadow-sm flex items-center space-x-6 hover:shadow-lg hover:shadow-primary-500/5 hover:-translate-y-0.5 transition-all duration-300">
                    <div class="bg-primary-50 text-primary-600 w-16 h-16 rounded-2xl flex items-center justify-center text-2xl shrink-0">
                        <i class="fa-solid fa-handshake"></i>
                    </div>
                    <div>
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block leading-none">Total Zakat Anda</span>
                        <h3 class="text-xl sm:text-2xl font-montserrat font-black text-slate-800 leading-none mt-1.5">
                            Rp {{ number_format($totalZakat, 0, ',', '.') }}
                        </h3>
                    </div>
                </div>
            </div>

            <!-- Donation History -->
            <div class="bg-white border border-slate-100 rounded-3xl p-6 sm:p-8 space-y-6 shadow-sm">
                <h3 class="font-montserrat font-black uppercase text-xs sm:text-sm text-slate-800 tracking-wider">Riwayat Donasi Kebaikan</h3>
                
                @if($donations->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-slate-100 text-[10px] font-black uppercase tracking-wider text-slate-400">
                                    <th class="pb-4">Kampanye</th>
                                    <th class="pb-4">Jumlah</th>
                                    <th class="pb-4">Status Pembayaran</th>
                                    <th class="pb-4">Status Tracking</th>
                                    <th class="pb-4 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                                @foreach($donations as $don)
                                    <tr class="border-b border-slate-50 last:border-0 hover:bg-slate-50/45 transition-colors">
                                        <td class="py-4 pr-4">
                                            <span class="block text-slate-850 font-bold truncate max-w-[250px]">{{ $don->campaign->title ?? 'Umum' }}</span>
                                            <span class="block text-[9px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">ID: #DON-{{ $don->id }}</span>
                                        </td>
                                        <td class="py-4 text-primary-700 font-bold">Rp {{ number_format($don->amount, 0, ',', '.') }}</td>
                                        <td class="py-4">
                                            @if($don->payment_status === 'Success')
                                                <span class="bg-emerald-50 text-emerald-700 text-[8px] font-black uppercase tracking-widest px-2.5 py-1 rounded-md">SUCCESS</span>
                                            @elseif($don->payment_status === 'Pending')
                                                <span class="bg-amber-50 text-amber-700 text-[8px] font-black uppercase tracking-widest px-2.5 py-1 rounded-md">PENDING</span>
                                            @else
                                                <span class="bg-rose-50 text-rose-700 text-[8px] font-black uppercase tracking-widest px-2.5 py-1 rounded-md">FAILED</span>
                                            @endif
                                        </td>
                                        <td class="py-4">
                                            @if($don->tracking_status === 'Disalurkan')
                                                <span class="bg-emerald-50 text-emerald-700 text-[8px] font-black uppercase tracking-widest px-2.5 py-1 rounded-md border border-emerald-100">DISALURKAN</span>
                                            @elseif($don->tracking_status === 'Diproses')
                                                <span class="bg-blue-50 text-blue-700 text-[8px] font-black uppercase tracking-widest px-2.5 py-1 rounded-md border border-blue-100">DIPROSES</span>
                                            @else
                                                <span class="bg-slate-100 text-slate-600 text-[8px] font-black uppercase tracking-widest px-2.5 py-1 rounded-md border border-slate-200/50">
                                                    {{ strtoupper($don->tracking_status ?? 'MENUNGGU') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-4 text-right">
                                            <a href="{{ route('donations.receipt', $don->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-900 hover:bg-primary-650 text-white rounded-lg text-[9px] font-black uppercase tracking-wider transition-all">
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="pt-4">
                        {{ $donations->links() }}
                    </div>
                @else
                    <div class="py-16 text-center text-slate-400 font-bold uppercase tracking-wider">
                        <i class="fa-solid fa-heart-crack text-slate-200 text-4xl mb-3 block"></i>
                        Anda belum pernah melakukan donasi kebaikan.
                    </div>
                @endif
            </div>

        </div>

    </div>
</section>
@endsection
