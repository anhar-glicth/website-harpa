@extends('layouts.app')

@section('content')
<section class="max-w-7xl mx-auto px-6 py-20">
    <div class="text-center max-w-2xl mx-auto mb-16 space-y-2">
        <h1 class="text-3xl sm:text-5xl font-montserrat font-black text-slate-900 uppercase tracking-tight italic">
            Kabar Terbaru & <span class="text-primary-600">Dampak</span>
        </h1>
        <div class="w-12 h-1.5 bg-primary-500 mx-auto rounded-full"></div>
        <p class="text-slate-600 font-bold text-xs tracking-widest uppercase mt-3">Cerita langsung dari penyaluran donasi Anda di lapangan</p>
    </div>

    <!-- News Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
        @foreach($news as $item)
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden flex flex-col group hover:shadow-xl transition duration-300">
                <div class="h-52 overflow-hidden bg-slate-100">
                    <img 
                        src="{{ $item->image ?? 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=800&fit=crop' }}" 
                        width="800"
                        height="500"
                        loading="lazy"
                        decoding="async"
                        class="w-full h-full object-cover group-hover:scale-105 transition duration-500" 
                        alt="{{ $item->title }}"
                    >
                </div>
                <div class="p-6 sm:p-8 flex-grow flex flex-col justify-between space-y-4">
                    <div class="space-y-3">
                        <span class="bg-primary-50 text-primary-700 text-xs font-black uppercase tracking-widest px-2.5 py-1.5 rounded-md">
                            {{ $item->tag }}
                        </span>
                        <h2 class="font-montserrat font-black uppercase text-sm sm:text-base text-slate-800 line-clamp-2 leading-tight group-hover:text-primary-700 transition-colors">
                            <a href="{{ route('news.detail', $item->id) }}">{{ $item->title }}</a>
                        </h2>
                        <p class="text-xs text-slate-600 font-medium line-clamp-3 leading-relaxed">
                            {{ strip_tags($item->content) }}
                        </p>
                    </div>
                    <div class="flex justify-between items-center text-xs font-bold text-slate-600 uppercase tracking-wider pt-4 border-t border-slate-100">
                        <span>{{ $item->created_at->format('d M Y') }}</span>
                        <a href="{{ route('news.detail', $item->id) }}" class="text-primary-700 font-black inline-flex items-center min-h-[44px]">Baca Artikel <i class="fa-solid fa-arrow-right ml-1" aria-hidden="true"></i></a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="pt-6">
        {{ $news->links() }}
    </div>
</section>
@endsection
