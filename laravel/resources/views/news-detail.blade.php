@extends('layouts.app')

@section('content')
<section class="max-w-7xl mx-auto px-6 py-20">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        <!-- Main Article -->
        <div class="lg:col-span-2 space-y-6">
            <div class="space-y-4">
                <span class="bg-primary-50 text-primary-700 text-[10px] font-black uppercase tracking-widest px-3 py-1.5 rounded-lg">
                    {{ $item->tag }}
                </span>
                <h1 class="text-2xl sm:text-4xl font-montserrat font-black uppercase tracking-tight text-slate-900 leading-tight">
                    {{ $item->title }}
                </h1>
                <div class="flex items-center space-x-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                    <span>Diposting {{ $item->created_at->format('d M Y') }}</span>
                    <span>&bull;</span>
                    <span>Oleh Admin</span>
                </div>
            </div>

            <!-- Main Image -->
            <div class="rounded-3xl overflow-hidden shadow-lg h-[350px] sm:h-[450px]">
                <img 
                    src="{{ $item->image ?? 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=1200&fit=crop' }}" 
                    class="w-full h-full object-cover" 
                    alt="{{ $item->title }}"
                >
            </div>

            <!-- Content -->
            <div class="text-slate-600 font-semibold text-xs sm:text-sm leading-relaxed space-y-4 pt-4 whitespace-pre-line">
                {!! $item->content !!}
            </div>
        </div>

        <!-- Sidebar Recent Articles -->
        <div class="space-y-8">
            <div class="bg-white border border-slate-100 p-8 rounded-3xl space-y-6 shadow-sm">
                <h3 class="font-montserrat font-black uppercase text-sm text-slate-800 tracking-wider">Artikel Terbaru</h3>
                <div class="space-y-6">
                    @foreach($recent as $r)
                        <a href="{{ route('news.detail', $r->id) }}" class="flex items-center space-x-4 group">
                            <div class="w-16 h-16 bg-slate-100 rounded-xl overflow-hidden shrink-0">
                                <img src="{{ $r->image ?? 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=150&fit=crop' }}" class="w-full h-full object-cover" alt="">
                            </div>
                            <div class="space-y-1">
                                <h4 class="text-xs font-black uppercase text-slate-800 line-clamp-2 leading-snug group-hover:text-primary-600 transition-colors">
                                    {{ $r->title }}
                                </h4>
                                <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">{{ $r->created_at->format('d M Y') }}</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
