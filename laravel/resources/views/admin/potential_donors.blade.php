@extends('layouts.app')

@section('content')
<div class="container max-w-7xl mx-auto px-4 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-[280px_1fr] gap-8 items-start">

        {{-- Sidebar --}}
        <aside class="hidden lg:block space-y-5">
            @include('admin.sidebar', ['active' => 'potential_donors'])
        </aside>

        {{-- Main Content --}}
        <div class="space-y-6">

            {{-- Header Card --}}
            <div class="bg-white border border-slate-100 rounded-3xl p-6 sm:p-8 shadow-sm flex items-center justify-between gap-4">
                <div class="space-y-1">
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight">List Calon Donatur</h1>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest">Transaction Management → Data Calon Donatur Potensial</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600 shadow-sm">
                    <i class="fa-solid fa-user-plus text-lg"></i>
                </div>
            </div>

            {{-- Potential Donors Table --}}
            <div class="bg-white border border-slate-100 rounded-3xl overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-100 text-[10px] font-black uppercase tracking-wider text-slate-500">
                                <th class="py-4 px-6">No</th>
                                <th class="py-4 px-6">Nama Calon Donatur</th>
                                <th class="py-4 px-6">Kontak</th>
                                <th class="py-4 px-6 text-center">Tingkat Potensi</th>
                                <th class="py-4 px-6 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="text-xs font-semibold text-slate-700 divide-y divide-slate-100">
                            @forelse($potentialDonors as $index => $item)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="py-4 px-6 font-mono text-slate-400">{{ $potentialDonors->firstItem() + $index }}</td>
                                    <td class="py-4 px-6 font-bold text-slate-900">{{ $item->name }}</td>
                                    <td class="py-4 px-6 text-slate-500">
                                        <p>{{ $item->email ?? '-' }}</p>
                                        <p class="text-[10px] text-slate-400 font-mono">{{ $item->phone ?? '-' }}</p>
                                    </td>
                                    <td class="py-4 px-6 text-center font-bold text-emerald-600">
                                        {{ $item->potential_level ?? 'High Potential' }}
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-full text-[10px] font-black uppercase">
                                            {{ $item->status ?? 'Tertarik' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-slate-400 text-xs font-bold">Belum ada data calon donatur.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($potentialDonors->hasPages())
                    <div class="p-6 border-t border-slate-100">
                        {{ $potentialDonors->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>
@endsection
