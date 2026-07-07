@extends('layouts.app')

@section('content')
<section class="max-w-xl mx-auto px-6 py-20">
    <div class="bg-white border border-slate-100 shadow-xl rounded-3xl p-8 sm:p-10 space-y-6">
        <div class="space-y-2">
            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Berdonasi Untuk</span>
            <h1 class="text-xl sm:text-2xl font-montserrat font-black text-slate-900 uppercase tracking-tight italic">
                {{ $campaign->title }}
            </h1>
            <div class="w-12 h-1 bg-primary-500 rounded-full"></div>
        </div>

        <form action="{{ route('donations.submit', $campaign->id) }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-[10px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Nama Donatur</label>
                <input type="text" name="donor_name" required value="{{ Auth::user()->name }}" class="w-full bg-slate-50 border border-slate-100 focus:border-primary-500 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none transition-all">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Jumlah Donasi (Rupiah)</label>
                    <input type="number" name="amount" required min="1000" placeholder="Minimal Rp 1.000" class="w-full bg-slate-50 border border-slate-100 focus:border-primary-500 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Metode Pembayaran</label>
                    <select name="payment_via_id" required class="w-full bg-slate-50 border border-slate-100 focus:border-primary-500 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none transition-all">
                        @foreach($payments as $p)
                            <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->provider }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Pesan / Doa Kebaikan (Opsional)</label>
                <textarea name="message" rows="3" placeholder="Tuliskan doa kebaikan Anda..." class="w-full bg-slate-50 border border-slate-100 focus:border-primary-500 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none transition-all"></textarea>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-black text-xs uppercase tracking-widest py-4.5 rounded-2xl shadow-lg shadow-primary-600/10 hover:shadow-primary-600/20 transition-all duration-300">
                    Lanjutkan Pembayaran <i class="fa-solid fa-arrow-right ml-2"></i>
                </button>
            </div>
        </form>
    </div>
</section>
@endsection
