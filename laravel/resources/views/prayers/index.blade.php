@extends('layouts.app')

@section('content')
<section class="max-w-7xl mx-auto px-6 py-20">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        <!-- Prayers Board List -->
        <div class="lg:col-span-2 space-y-8">
            <div>
                <h1 class="text-3xl sm:text-4xl font-montserrat font-black text-slate-900 uppercase tracking-tight italic">
                    Papan Doa <span class="text-primary-600">Kebaikan Donatur</span>
                </h1>
                <p class="text-slate-400 font-bold text-xs tracking-widest uppercase mt-2">Mari salurkan doa kebaikan kita dan aminkan doa bersama</p>
            </div>

            <!-- List -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                @foreach($prayers as $p)
                    <div class="bg-white border border-slate-100 p-6 rounded-3xl shadow-sm hover:shadow-xl hover:border-slate-200/60 transition duration-300 flex flex-col justify-between min-h-[160px]">
                        <div class="space-y-3">
                            <p class="text-xs text-slate-600 font-semibold italic">
                                "{{ $p->message }}"
                            </p>
                        </div>
                        <div class="flex items-center justify-between pt-4 border-t border-slate-50 mt-4">
                            <div>
                                <span class="text-[10px] font-black uppercase text-slate-800 tracking-wide block">
                                    {{ $p->is_anonymous ? 'Donatur Anonim' : $p->name }}
                                </span>
                                <span class="text-[8px] text-slate-400 font-semibold uppercase tracking-wider">{{ $p->created_at->diffForHumans() }}</span>
                            </div>
                            <form action="{{ route('prayers.upvote', $p->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-primary-50 hover:bg-primary-600 text-primary-700 hover:text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-wider transition duration-300 flex items-center gap-1.5">
                                    <i class="fa-solid fa-hands-praying"></i> Aamiin ({{ $p->aamiin_count }})
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <div>
                {{ $prayers->links() }}
            </div>
        </div>

        <!-- Post a Prayer Form -->
        <div>
            <div class="bg-white border border-slate-100 p-8 rounded-3xl space-y-6 shadow-xl sticky top-28">
                <h3 class="font-montserrat font-black uppercase text-base text-slate-900 leading-tight">
                    Kirim Doa Kebaikan
                </h3>
                <div class="w-12 h-1 bg-primary-500 rounded-full"></div>

                @auth
                    <form action="{{ route('prayers.submit') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-wider text-slate-400 mb-1.5">Isi Doa / Harapan Anda</label>
                            <textarea name="message" required rows="4" placeholder="Tulis doa atau harapan Anda di sini..." class="w-full bg-slate-50 border border-slate-100 focus:border-primary-500 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none transition-all"></textarea>
                        </div>

                        <div class="flex items-center space-x-2">
                            <input type="checkbox" name="is_anonymous" id="is_anonymous" value="1" class="rounded border-slate-200 text-primary-600 focus:ring-primary-500">
                            <label for="is_anonymous" class="text-[10px] font-black uppercase tracking-wider text-slate-550 select-none">Kirim Sebagai Anonim</label>
                        </div>

                        <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-black text-xs uppercase tracking-widest py-4.5 rounded-2xl shadow-lg shadow-primary-600/10 transition duration-300">
                            Kirim Doa <i class="fa-solid fa-paper-plane ml-2"></i>
                        </button>
                    </form>
                @else
                    <div class="text-center py-6 space-y-4">
                        <i class="fa-solid fa-user-lock text-slate-300 text-3xl"></i>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider leading-relaxed">Login terlebih dahulu untuk dapat mengirim doa kebaikan Anda.</p>
                        <button @click="loginOpen = true" class="inline-block bg-primary-600 hover:bg-primary-700 text-white text-xs font-black uppercase tracking-widest px-6 py-3 rounded-xl transition duration-300">
                            Login Sekarang
                        </button>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</section>
@endsection
