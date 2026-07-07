@extends('layouts.app')

@section('content')
<section class="max-w-7xl mx-auto px-6 py-20">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- Sidebar Links -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white border border-slate-100 p-8 rounded-3xl space-y-6 shadow-sm">
                <div class="flex items-center space-x-3">
                    @if(Auth::user()->profile_photo)
                        <img src="{{ Auth::user()->profile_photo }}" class="w-12 h-12 rounded-2xl object-cover" alt="">
                    @else
                        <div class="w-12 h-12 bg-primary-100 text-primary-700 rounded-2xl flex items-center justify-center font-bold text-base uppercase">
                            {{ substr(Auth::user()->name, 0, 2) }}
                        </div>
                    @endif
                    <div>
                        <h4 class="font-montserrat font-black uppercase text-xs sm:text-sm text-slate-800 leading-tight">{{ Auth::user()->name }}</h4>
                        <span class="text-[9px] font-bold text-slate-400 uppercase block tracking-wider mt-1">{{ Auth::user()->email }}</span>
                    </div>
                </div>
                
                <hr class="border-slate-100">

                <div class="flex flex-col space-y-2 font-bold text-[10px] uppercase tracking-wider text-slate-500">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 py-3 px-4 rounded-xl bg-primary-50 text-primary-700">
                        <i class="fa-solid fa-gauge"></i>
                        <span>Ringkasan</span>
                    </a>
                    <a href="{{ route('zakat.index') }}" class="flex items-center space-x-3 py-3 px-4 rounded-xl hover:bg-slate-50 hover:text-slate-800 transition-colors">
                        <i class="fa-solid fa-calculator"></i>
                        <span>Bayar Zakat</span>
                    </a>
                    <a href="{{ route('profile.custom') }}" class="flex items-center space-x-3 py-3 px-4 rounded-xl hover:bg-slate-50 hover:text-slate-800 transition-colors">
                        <i class="fa-solid fa-user-pen"></i>
                        <span>Edit Profil</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Dashboard Content -->
        <div class="lg:col-span-3 space-y-8">
            <div class="space-y-2">
                <h1 class="text-2xl sm:text-3xl font-montserrat font-black text-slate-900 uppercase tracking-tight italic">
                    Ringkasan <span class="text-primary-600">Aktivitas Donatur</span>
                </h1>
                <div class="w-12 h-1 bg-primary-500 rounded-full"></div>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Total Donated -->
                <div class="bg-white border border-slate-100 p-8 rounded-3xl shadow-sm flex items-center space-x-6">
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
                <div class="bg-white border border-slate-100 p-8 rounded-3xl shadow-sm flex items-center space-x-6">
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
                <h3 class="font-montserrat font-black uppercase text-sm text-slate-800 tracking-wider">Riwayat Donasi Kebaikan</h3>
                
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
                            @forelse($donations as $don)
                                <tr class="border-b border-slate-50 last:border-0">
                                    <td class="py-4 pr-4 max-w-[200px] line-clamp-1 mt-3">{{ $don->campaign->title }}</td>
                                    <td class="py-4">Rp {{ number_format($don->amount, 0, ',', '.') }}</td>
                                    <td class="py-4">
                                        @if($don->payment_status === 'Success')
                                            <span class="bg-emerald-50 text-emerald-700 text-[8px] font-black uppercase tracking-widest px-2.5 py-1 rounded-md">Success</span>
                                        @elseif($don->payment_status === 'Pending')
                                            <span class="bg-amber-50 text-amber-700 text-[8px] font-black uppercase tracking-widest px-2.5 py-1 rounded-md">Pending</span>
                                        @else
                                            <span class="bg-rose-50 text-rose-700 text-[8px] font-black uppercase tracking-widest px-2.5 py-1 rounded-md">Failed</span>
                                        @endif
                                    </td>
                                    <td class="py-4">
                                        <span class="bg-slate-100 text-slate-700 text-[8px] font-black uppercase tracking-widest px-2.5 py-1 rounded-md">
                                            {{ $don->tracking_status }}
                                        </span>
                                    </td>
                                    <td class="py-4 text-right">
                                        <a href="{{ route('donations.receipt', $don->id) }}" class="text-primary-600 hover:text-primary-800 font-black">Detail</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-slate-400 font-bold uppercase tracking-wider">Anda belum pernah melakukan donasi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div>
                    {{ $donations->links() }}
                </div>
            </div>

        </div>

    </div>
</section>
@endsection
