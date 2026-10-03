@props([
    'variant' => 'card', // 'card', 'compact', 'inline'
    'theme' => 'light'
])

@php
    $id = 'wita_clock_' . uniqid();
@endphp

@if($variant === 'card')
    <div class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-white rounded-3xl p-5 shadow-lg border border-slate-700/50 relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-primary-500/10 rounded-full blur-2xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="space-y-1">
                <div class="flex items-center gap-2">
                    <span class="relative flex h-2.5 w-2.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                    </span>
                    <span class="text-[10px] font-black uppercase tracking-widest text-emerald-400">Waktu Real-Time WITA</span>
                    <span class="text-[9px] font-mono px-2 py-0.5 rounded-full bg-white/10 text-slate-300 font-bold">GMT+8</span>
                </div>
                <p id="{{ $id }}_date" class="text-xs font-bold text-slate-300 capitalize">Memuat tanggal...</p>
            </div>
            <div class="flex items-baseline gap-2">
                <div id="{{ $id }}_time" class="text-3xl sm:text-4xl font-black font-mono tracking-tight text-white tabular-nums drop-shadow-md">
                    --:--:--
                </div>
                <span class="text-xs font-black uppercase tracking-widest text-primary-400">WITA</span>
            </div>
        </div>
    </div>
@elseif($variant === 'compact')
    <div class="inline-flex items-center gap-3 px-4 py-2.5 bg-slate-900 text-white rounded-2xl shadow-sm border border-slate-700">
        <span class="relative flex h-2 w-2">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
        </span>
        <div class="flex items-baseline gap-1.5 font-mono">
            <span id="{{ $id }}_time" class="text-sm font-black text-white tabular-nums">--:--:--</span>
            <span class="text-[9px] font-black text-primary-400">WITA</span>
        </div>
        <span class="text-slate-600">|</span>
        <span id="{{ $id }}_date" class="text-[10px] font-bold text-slate-300">--</span>
    </div>
@else
    {{-- Inline minimal --}}
    <span class="inline-flex items-center gap-1.5 font-mono font-bold text-xs text-slate-700">
        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
        <span id="{{ $id }}_time" class="tabular-nums">--:--:--</span>
        <span class="text-primary-600 font-black text-[10px]">WITA</span>
    </span>
@endif

<script>
(function() {
    const timeEl = document.getElementById("{{ $id }}_time");
    const dateEl = document.getElementById("{{ $id }}_date");

    function updateClock() {
        const now = new Date();

        // Format waktu WITA (Asia/Makassar)
        if (timeEl) {
            try {
                timeEl.textContent = new Intl.DateTimeFormat('id-ID', {
                    timeZone: 'Asia/Makassar',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: false
                }).format(now).replace(/\./g, ':');
            } catch (e) {
                // Fallback kalkulasi manual UTC+8 jika Intl timeZone tidak didukung
                const utc = now.getTime() + (now.getTimezoneOffset() * 60000);
                const witaDate = new Date(utc + (3600000 * 8));
                const h = String(witaDate.getHours()).padStart(2, '0');
                const m = String(witaDate.getMinutes()).padStart(2, '0');
                const s = String(witaDate.getSeconds()).padStart(2, '0');
                timeEl.textContent = `${h}:${m}:${s}`;
            }
        }

        // Format tanggal Indonesia WITA
        if (dateEl) {
            try {
                dateEl.textContent = new Intl.DateTimeFormat('id-ID', {
                    timeZone: 'Asia/Makassar',
                    weekday: 'long',
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric'
                }).format(now);
            } catch (e) {
                dateEl.textContent = now.toLocaleDateString('id-ID');
            }
        }
    }

    updateClock();
    setInterval(updateClock, 1000);
})();
</script>
