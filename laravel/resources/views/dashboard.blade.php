@extends('layouts.app')

@section('content')
<section class="max-w-7xl mx-auto px-6 py-20">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- Sidebar Links -->
        <div class="hidden lg:block lg:col-span-1 space-y-6">
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

            {{-- Absensi GPS (Hanya untuk Staf Internal) --}}
            @if(!Auth::user()->isUser())
            <div class="space-y-4">
                
            </div>
            @endif

            {{-- Papan Tugas / To-Do List Trello (Tersedia untuk Semua Role) --}}
            <div class="space-y-4">
                <x-trello-board 
                    :todos="$todos" 
                    :canAssign="false" 
                    :storeRoute="route('todo.store')" 
                    :showViewToggle="true" 
                />
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Total Donated -->
                <div class="bg-white border border-slate-100 p-8 rounded-3xl shadow-sm flex items-center space-x-6 hover:shadow-lg hover:shadow-primary-500/5 hover:-translate-y-0.5 transition-all duration-300">
                    <div class="bg-emerald-50 text-emerald-700 w-16 h-16 rounded-2xl flex items-center justify-center text-2xl shrink-0">
                        <i class="fa-solid fa-hand-holding-heart"></i>
                    </div>
                    <div>
                        <span class="text-xs font-bold text-slate-600 uppercase tracking-widest block leading-none">Total Donasi Anda</span>
                        <h2 class="text-xl sm:text-2xl font-montserrat font-black text-slate-800 leading-none mt-1.5">
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
                        <span class="text-xs font-bold text-slate-600 uppercase tracking-widest block leading-none">Total Zakat Anda</span>
                        <h2 class="text-xl sm:text-2xl font-montserrat font-black text-slate-800 leading-none mt-1.5">
                            Rp {{ number_format($totalZakat, 0, ',', '.') }}
                        </h3>
                    </div>
                </div>
            </div>

            <!-- Donation History -->
            <div class="bg-white border border-slate-100 rounded-3xl p-6 sm:p-8 space-y-6 shadow-sm">
                <h2 class="font-montserrat font-black uppercase text-xs sm:text-sm text-slate-800 tracking-wider">Riwayat Donasi Kebaikan</h2>
                
                @if($donations->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-slate-200 text-xs font-black uppercase tracking-wider text-slate-600">
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
                                            <span class="block text-slate-900 font-bold truncate max-w-[250px]">{{ $don->campaign->title ?? 'Umum' }}</span>
                                            <span class="block text-xs text-slate-600 font-bold uppercase tracking-wider mt-0.5">ID: #DON-{{ $don->id }}</span>
                                        </td>
                                        <td class="py-4 text-primary-700 font-bold">Rp {{ number_format($don->amount, 0, ',', '.') }}</td>
                                        <td class="py-4">
                                            @if($don->payment_status === 'Success')
                                                <span class="bg-emerald-50 text-emerald-700 text-xs font-black uppercase tracking-widest px-2.5 py-1 rounded-md">SUCCESS</span>
                                            @elseif($don->payment_status === 'Pending')
                                                <span class="bg-amber-50 text-amber-700 text-[8px] font-black uppercase tracking-widest px-2.5 py-1 rounded-md">PENDING</span>
                                            @else
                                                <span class="bg-rose-50 text-rose-700 text-[8px] font-black uppercase tracking-widest px-2.5 py-1 rounded-md">FAILED</span>
                                            @endif
                                        </td>
                                        <td class="py-4">
                                            @if($don->tracking_status === 'Disalurkan')
                                                <span class="bg-emerald-50 text-emerald-700 text-xs font-black uppercase tracking-widest px-2.5 py-1 rounded-md border border-emerald-200">DISALURKAN</span>
                                            @elseif($don->tracking_status === 'Diproses')
                                                <span class="bg-blue-50 text-blue-700 text-[8px] font-black uppercase tracking-widest px-2.5 py-1 rounded-md border border-blue-100">DIPROSES</span>
                                            @else
                                                <span class="bg-slate-100 text-slate-600 text-[8px] font-black uppercase tracking-widest px-2.5 py-1 rounded-md border border-slate-200/50">
                                                    {{ strtoupper($don->tracking_status ?? 'MENUNGGU') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-4 text-right">
                                            <a href="{{ route('donations.receipt', $don->id) }}" class="inline-flex items-center gap-1.5 px-3 py-2 min-h-[44px] bg-slate-900 hover:bg-primary-700 text-white rounded-lg text-xs font-black uppercase tracking-wider transition-all">
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
                    <div class="py-16 text-center text-slate-600 font-bold uppercase tracking-wider space-y-4">
                        <i class="fa-solid fa-heart-crack text-slate-500 text-4xl block" aria-hidden="true"></i>
                        <p>Anda belum pernah melakukan donasi kebaikan.</p>
                        <div>
                            <a href="{{ route('campaigns.index') }}" class="inline-flex items-center gap-2 px-6 py-3 min-h-[44px] bg-primary-600 hover:bg-primary-700 text-white rounded-2xl text-xs font-black uppercase tracking-widest transition-colors shadow-lg shadow-primary-600/20">
                                <i class="fa-solid fa-hand-holding-heart"></i> Lihat Kampanye Kebaikan
                            </a>
                        </div>
                    </div>
                @endif
            </div>

        </div>

    </div>
</section>
@endsection
