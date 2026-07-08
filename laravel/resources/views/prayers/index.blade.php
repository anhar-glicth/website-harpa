@extends('layouts.app')

@section('content')
<section class="max-w-7xl mx-auto px-6 py-20">
    
    <!-- Success Alert -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-250 text-emerald-800 rounded-2xl font-semibold text-xs flex items-center gap-3">
            <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- Sidebar Links -->
        <div class="lg:col-span-1 space-y-6">
            @include('layouts.donor-sidebar', ['active' => 'prayers'])
        </div>

        <!-- Dashboard Content -->
        <div class="lg:col-span-3 space-y-8">
            <div class="space-y-2">
                <h1 class="text-2xl sm:text-3xl font-montserrat font-black text-slate-900 uppercase tracking-tight italic">
                    Papan Doa <span class="text-primary-600">Kebaikan Donatur</span>
                </h1>
                <p class="text-slate-400 font-bold text-[10px] tracking-widest uppercase">Mari salurkan doa kebaikan kita dan aminkan doa bersama</p>
                <div class="w-12 h-1 bg-primary-500 rounded-full mt-2"></div>
            </div>

            <!-- Two column layout for list and write form -->
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
                <!-- Prayers Board List (Left/Top) -->
                <div class="xl:col-span-2 space-y-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @forelse($prayers as $p)
                            <div class="bg-white border border-slate-100 p-6 rounded-3xl shadow-sm hover:shadow-lg hover:border-primary-100 transition duration-300 flex flex-col justify-between min-h-[180px]">
                                <div class="space-y-3">
                                    <p class="text-xs text-slate-650 font-semibold italic leading-relaxed">
                                        "{{ $p->message }}"
                                    </p>
                                </div>
                                <div class="flex items-center justify-between pt-4 border-t border-slate-100/50 mt-4">
                                    <div>
                                        <span class="text-[10px] font-black uppercase text-slate-800 tracking-wide block">
                                            {{ $p->is_anonymous ? 'Donatur Anonim' : $p->name }}
                                        </span>
                                        <span class="text-[8px] text-slate-400 font-bold uppercase tracking-wider block mt-0.5">{{ $p->created_at->diffForHumans() }}</span>
                                    </div>
                                    <form action="{{ route('prayers.upvote', $p->id) }}" method="POST" class="shrink-0">
                                        @csrf
                                        <button type="submit" class="bg-primary-50 hover:bg-primary-600 text-primary-700 hover:text-white px-4 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-wider transition duration-300 flex items-center gap-1.5 shadow-sm">
                                            <i class="fa-solid fa-hands-praying"></i> Aamiin ({{ $p->aamiin_count }})
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-2 py-16 text-center text-slate-400 font-bold uppercase tracking-wider text-xs bg-white border border-slate-100 rounded-3xl w-full">
                                <i class="fa-solid fa-hands-praying text-slate-200 text-3xl mb-2 block"></i>
                                Belum ada doa kebaikan yang dikirimkan.
                            </div>
                        @endforelse
                    </div>

                    <div>
                        {{ $prayers->links() }}
                    </div>
                </div>

                <!-- Post a Prayer Form (Right/Bottom) -->
                <div class="xl:col-span-1">
                    <div class="bg-white border border-slate-100 p-6 sm:p-8 rounded-3xl space-y-6 shadow-sm sticky top-28">
                        <div>
                            <h3 class="font-montserrat font-black uppercase text-xs sm:text-sm text-slate-800 leading-tight">
                                Kirim Doa Kebaikan
                            </h3>
                            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Doa Anda akan tampil di papan bersama</p>
                        </div>
                        <hr class="border-slate-100">

                        @auth
                            <form action="{{ route('prayers.submit') }}" method="POST" class="space-y-4">
                                @csrf
                                <div>
                                    <label class="block text-[9px] font-black uppercase tracking-wider text-slate-450 mb-1.5 ml-1">Isi Doa / Harapan Anda</label>
                                    <textarea name="message" required rows="4" placeholder="Tulis doa atau harapan Anda di sini..." class="w-full bg-slate-50 border border-slate-150 focus:border-primary-500 rounded-2xl px-4 py-3.5 text-xs font-semibold text-slate-800 outline-none transition-all focus:ring-4 focus:ring-primary-500/10"></textarea>
                                </div>

                                <div class="flex items-center space-x-2">
                                    <input type="checkbox" name="is_anonymous" id="is_anonymous" value="1" class="rounded border-slate-200 text-primary-650 focus:ring-primary-500">
                                    <label for="is_anonymous" class="text-[9px] font-black uppercase tracking-wider text-slate-450 select-none">Kirim Sebagai Anonim</label>
                                </div>

                                <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-black text-xs uppercase tracking-wider py-4 rounded-2xl shadow-lg shadow-primary-600/10 transition duration-300">
                                    Kirim Doa <i class="fa-solid fa-paper-plane ml-2"></i>
                                </button>
                            </form>
                        @else
                            <div class="text-center py-6 space-y-4">
                                <i class="fa-solid fa-user-lock text-slate-350 text-3xl"></i>
                                <p class="text-xs font-bold text-slate-450 uppercase tracking-wider leading-relaxed">Login terlebih dahulu untuk dapat mengirim doa kebaikan Anda.</p>
                                <button @click="loginOpen = true" class="w-full bg-primary-600 hover:bg-primary-700 text-white text-xs font-black uppercase tracking-wider py-4 rounded-2xl transition duration-300 shadow-md">
                                    Login Sekarang
                                </button>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>
@endsection
