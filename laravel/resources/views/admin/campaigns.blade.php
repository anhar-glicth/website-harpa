@extends('layouts.app')

@section('content')
<section class="max-w-7xl mx-auto px-6 py-20">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- Sidebar Menu -->
        <div class="hidden lg:block lg:col-span-1 space-y-6">
            @include('admin.sidebar', ['active' => 'campaigns'])
        </div>

        <!-- Dashboard Content -->
        <div class="lg:col-span-3 space-y-8">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="space-y-2">
                    <h1 class="text-2xl sm:text-3xl font-montserrat font-black text-slate-900 uppercase tracking-tight italic">
                        Kelola <span class="text-primary-600">Kampanye Penggalangan Dana</span>
                    </h1>
                    <div class="w-12 h-1 bg-primary-500 rounded-full"></div>
                </div>
                <a href="{{ route('campaigns.create') }}" 
                   class="inline-flex items-center gap-2 px-5 py-3 min-h-[44px] bg-primary-600 hover:bg-primary-700 text-white rounded-2xl text-xs font-black uppercase tracking-wider shadow-lg shadow-primary-500/20 transition-colors">
                    <i class="fa-solid fa-plus text-xs"></i> Tambah Kampanye Baru
                </a>
            </div>

            <!-- Campaign list table -->
            <div class="bg-white border border-slate-100 rounded-3xl p-6 sm:p-8 space-y-6 shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 text-xs font-black uppercase tracking-wider text-slate-600">
                                <th class="pb-4">Kampanye</th>
                                <th class="pb-4">Pembuat</th>
                                <th class="pb-4">Target</th>
                                <th class="pb-4">Terkumpul</th>
                                <th class="pb-4">Status</th>
                                <th class="pb-4 text-center">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                            @forelse($campaigns as $c)
                                <tr class="border-b border-slate-50 last:border-0">
                                    <td class="py-4 pr-4 max-w-[200px] line-clamp-1 mt-3">
                                        <a href="{{ route('campaigns.show', $c->id) }}" class="hover:text-primary-600">{{ $c->title }}</a>
                                    </td>
                                    <td class="py-4">{{ $c->author->name }}</td>
                                    <td class="py-4">Rp {{ number_format($c->target_amount, 0, ',', '.') }}</td>
                                    <td class="py-4">Rp {{ number_format($c->current_amount, 0, ',', '.') }}</td>
                                    <td class="py-4 font-bold">
                                        @if($c->status === 'approved')
                                            <span class="bg-emerald-50 text-emerald-800 text-xs font-black uppercase tracking-widest px-2.5 py-1 rounded-md">Approved</span>
                                        @elseif($c->status === 'pending')
                                            <span class="bg-amber-50 text-amber-800 text-xs font-black uppercase tracking-widest px-2.5 py-1 rounded-md">Pending</span>
                                        @else
                                            <span class="bg-rose-50 text-rose-800 text-xs font-black uppercase tracking-widest px-2.5 py-1 rounded-md">Rejected</span>
                                        @endif
                                    </td>
                                    <td class="py-4 text-center">
                                        @if($c->status === 'pending')
                                            <div class="flex items-center justify-center gap-2">
                                                <form action="{{ route('admin.campaigns.approve', $c->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="bg-emerald-700 hover:bg-emerald-800 text-white font-black text-xs uppercase tracking-wider px-3 py-2 min-h-[44px] rounded-lg">
                                                        Setujui
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.campaigns.reject', $c->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="bg-rose-700 hover:bg-rose-800 text-white font-black text-xs uppercase tracking-wider px-3 py-2 min-h-[44px] rounded-lg">
                                                        Tolak
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <span class="text-slate-600 text-xs font-bold uppercase tracking-wider">Sudah Diproses</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-slate-600 font-bold uppercase tracking-wider">Belum ada kampanye.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div>
                    {{ $campaigns->links() }}
                </div>
            </div>

        </div>

    </div>
</section>
@endsection
