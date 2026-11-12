@extends('layouts.app')

@section('content')
<section class="max-w-7xl mx-auto px-6 py-20">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- Sidebar Menu -->
        <div class="hidden lg:block lg:col-span-1 space-y-6">
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
                            <tr class="border-b border-slate-100 text-xs font-black uppercase tracking-wider text-slate-600">
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
                                            <a href="{{ asset($d->proof_image) }}" target="_blank" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-3 py-2 min-h-[44px] rounded-lg border border-slate-200 flex items-center justify-center space-x-1.5 w-max">
                                                <i class="fa-solid fa-image"></i>
                                                <span class="text-xs font-black">Lihat Foto</span>
                                            </a>
                                        @else
                                            <span class="text-slate-600 text-xs font-black">Belum Upload</span>
                                        @endif
                                    </td>
                                     <td class="py-4 text-center">
                                        @if($d->payment_status === 'Pending')
                                            <form action="{{ route('admin.donations.verify', $d->id) }}" method="POST" class="flex items-center justify-center gap-2">
                                                @csrf
                                                <input type="text" name="admin_note" aria-label="Catatan admin" placeholder="Catatan..." class="bg-slate-50 border border-slate-300 focus:border-primary-500 rounded-lg px-3 py-2 min-h-[44px] text-xs font-bold text-slate-800 w-28">
                                                <button type="submit" name="status" value="Success" class="bg-emerald-700 hover:bg-emerald-800 text-white font-black text-xs uppercase tracking-wider px-3 py-2 min-h-[44px] rounded-lg" title="Verifikasi & Terima Donasi">
                                                    <i class="fa-solid fa-check mr-1"></i> Terima
                                                </button>
                                                <button type="submit" name="status" value="Failed" class="bg-rose-700 hover:bg-rose-800 text-white font-black text-xs uppercase tracking-wider px-3 py-2 min-h-[44px] rounded-lg" title="Tolak Donasi">
                                                    <i class="fa-solid fa-xmark mr-1"></i> Tolak
                                                </button>
                                            </form>
                                        @else
                                            <div class="text-center whitespace-nowrap">
                                                @if($d->payment_status === 'Success')
                                                    <span class="inline-block whitespace-nowrap text-xs font-black uppercase tracking-wider text-emerald-700">VERIFIED</span>
                                                @elseif($d->payment_status === 'Failed')
                                                    <span class="inline-block whitespace-nowrap text-xs font-black uppercase tracking-wider text-rose-700">REJECTED</span>
                                                @else
                                                    <span class="inline-block whitespace-nowrap text-xs font-black uppercase tracking-wider text-slate-600">PENDING TRANSFER</span>
                                                @endif
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-slate-600 font-bold uppercase tracking-wider">Belum ada donasi terdaftar.</td>
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
