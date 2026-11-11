@extends('layouts.app')

@section('content')
<section class="max-w-xl mx-auto px-6 py-20">
    <div class="bg-white border border-slate-100 shadow-xl rounded-3xl p-8 sm:p-10 space-y-6">
        <div class="space-y-2">
            <span class="text-xs font-bold text-slate-600 uppercase tracking-widest block">Berdonasi Untuk</span>
            <h1 class="text-xl sm:text-2xl font-montserrat font-black text-slate-900 uppercase tracking-tight italic">
                {{ $campaign->title }}
            </h1>
            <div class="w-12 h-1 bg-primary-500 rounded-full"></div>
        </div>

        <form action="{{ route('donations.submit', $campaign->id) }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="donor_name" class="block text-xs font-black uppercase tracking-wider text-slate-600 mb-1.5">Nama Donatur</label>
                <input id="donor_name" type="text" name="donor_name" required value="{{ Auth::user()->name }}" class="w-full min-h-[44px] bg-slate-50 border border-slate-400 focus:border-primary-600 rounded-2xl px-5 py-3.5 text-sm font-bold text-slate-800 outline-none transition-all">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="donation_amount" class="block text-xs font-black uppercase tracking-wider text-slate-600 mb-1.5">Jumlah Donasi (Rupiah)</label>
                    <input id="donation_amount" type="number" name="amount" required min="1000" placeholder="Minimal Rp 1.000" class="w-full min-h-[44px] bg-slate-50 border border-slate-400 focus:border-primary-600 rounded-2xl px-5 py-3.5 text-sm font-bold text-slate-800 outline-none transition-all">
                </div>
                <div>
                    <label for="payment_via_id" class="block text-xs font-black uppercase tracking-wider text-slate-600 mb-1.5">Metode Pembayaran</label>
                    <select id="payment_via_id" name="payment_via_id" required class="w-full min-h-[44px] bg-slate-50 border border-slate-400 focus:border-primary-600 rounded-2xl px-5 py-3.5 text-sm font-bold text-slate-800 outline-none transition-all">
                        @foreach($payments as $p)
                            <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->provider }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label for="donation_message" class="block text-xs font-black uppercase tracking-wider text-slate-600 mb-1.5">Pesan / Doa Kebaikan (Opsional)</label>
                <textarea id="donation_message" name="message" rows="3" placeholder="Tuliskan doa kebaikan Anda..." class="w-full bg-slate-50 border border-slate-400 focus:border-primary-600 rounded-2xl px-5 py-3.5 text-sm font-bold text-slate-800 outline-none transition-all"></textarea>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full min-h-[48px] bg-primary-600 hover:bg-primary-700 text-white font-black text-xs uppercase tracking-widest py-4 rounded-2xl shadow-lg shadow-primary-600/10 hover:shadow-primary-600/20 transition-all duration-300">
                    Lanjutkan Pembayaran <i class="fa-solid fa-arrow-right ml-2" aria-hidden="true"></i>
                </button>
            </div>
        </form>
    </div>
</section>
@endsection
