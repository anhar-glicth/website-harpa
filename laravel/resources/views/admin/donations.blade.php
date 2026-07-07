@extends('layouts.app')

@section('content')
<section class="max-w-7xl mx-auto px-6 py-20">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- Sidebar Menu -->
        <div class="lg:col-span-1 space-y-6">
            @include('admin.sidebar', ['active' => 'donations'])
        </div>

        <!-- Dashboard Content -->
        <div class="lg:col-span-3 space-y-8">
            <div class="space-y-2">
                <h1 class="text-2xl sm:text-3xl font-montserrat font-black text-slate-900 uppercase tracking-tight italic">
                    Verifikasi <span class="text-primary-600">Bukti Transfer Donasi</span>
                </h1>
                <div class="w-12 h-1 bg-primary-500 rounded-full"></div>
            </div>

            <!-- Donations verification table -->
            <div class="bg-white border border-slate-100 rounded-3xl p-6 sm:p-8 space-y-6 shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse font-bold text-xs">
                        <thead>
                            <tr class="border-b border-slate-100 text-[10px] font-black uppercase tracking-wider text-slate-400">
                                <th class="pb-4">ID</th>
                                <th class="pb-4">Donatur</th>
                                <th class="pb-4">Kampanye</th>
                                <th class="pb-4">Nominal</th>
                                <th class="pb-4">Bukti</th>
                                <th class="pb-4 text-center">Verifikasi</th>
                            </tr>
                        </thead>
                        <tbody class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                            @forelse($donations as $d)
                                <tr class="border-b border-slate-50 last:border-0">
                                    <td class="py-4">#{{ $d->id }}</td>
                                    <td class="py-4">{{ $d->donor_name }}</td>
                                    <td class="py-4 pr-4 max-w-[150px] line-clamp-1 mt-3">{{ $d->campaign->title }}</td>
                                    <td class="py-4 text-primary-600">Rp {{ number_format($d->amount, 0, ',', '.') }}</td>
                                    <td class="py-4">
                                        @if($d->proof_image)
                                            <a href="{{ $d->proof_image }}" target="_blank" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-3 py-1.5 rounded-lg border border-slate-200/50 flex items-center justify-center space-x-1.5 w-max">
                                                <i class="fa-solid fa-image"></i>
                                                <span class="text-[9px] font-black">Lihat Foto</span>
                                            </a>
                                        @else
                                            <span class="text-slate-400 text-[9px] font-black">Belum Upload</span>
                                        @endif
                                    </td>
                                    <td class="py-4 text-center">
                                        @if($d->payment_status === 'Pending' && $d->proof_image)
                                            <form action="{{ route('admin.donations.verify', $d->id) }}" method="POST" class="flex items-center justify-center gap-2">
                                                @csrf
                                                <input type="text" name="admin_note" placeholder="Catatan..." class="bg-slate-50 border border-slate-100 focus:border-primary-500 rounded-lg px-3 py-1.5 text-[9px] font-bold text-slate-800 outline-none w-24">
                                                <button type="submit" name="status" value="Success" class="bg-emerald-600 hover:bg-emerald-700 text-white font-black text-[9px] uppercase tracking-wider px-3 py-1.5 rounded-lg">
                                                    Terima
                                                </button>
                                                <button type="submit" name="status" value="Failed" class="bg-rose-600 hover:bg-rose-700 text-white font-black text-[9px] uppercase tracking-wider px-3 py-1.5 rounded-lg">
                                                    Tolak
                                                </button>
                                            </form>
                                        @else
                                            <div class="text-center space-y-1">
                                                @if($d->payment_status === 'Success')
                                                    <span class="bg-emerald-50 text-emerald-700 text-[8px] font-black uppercase tracking-widest px-2.5 py-1 rounded-md">VERIFIED</span>
                                                @elseif($d->payment_status === 'Failed')
                                                    <span class="bg-rose-50 text-rose-700 text-[8px] font-black uppercase tracking-widest px-2.5 py-1 rounded-md">REJECTED</span>
                                                @else
                                                    <span class="bg-slate-50 text-slate-500 text-[8px] font-black uppercase tracking-widest px-2.5 py-1 rounded-md">PENDING TRANSFER</span>
                                                @endif
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-slate-400 font-bold uppercase tracking-wider">Belum ada donasi terdaftar.</td>
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
