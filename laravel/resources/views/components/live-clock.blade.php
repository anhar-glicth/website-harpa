@props([
    'variant' => 'card', // 'card', 'compact', 'inline'
    'theme' => 'light'
])

@php
    $id = 'wita_clock_' . uniqid();
@endphp

@if($variant === 'card')
    <div class="bg-slate-900 text-white rounded-3xl p-5 shadow-lg border border-slate-700/50 relative overflow-hidden">
        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="space-y-1">
                <div class="flex items-center gap-2">
                    <i class="fa-regular fa-clock text-[#f7ad1a] text-xs" aria-hidden="true"></i>
                    <span class="text-xs font-black uppercase tracking-widest text-slate-200">Waktu WITA</span>
                    <span class="text-xs font-mono px-2 py-0.5 rounded bg-white/10 text-slate-200 font-bold">GMT+8</span>
                </div>
                <p id="{{ $id }}_date" class="text-xs font-bold text-slate-200 capitalize">Memuat tanggal...</p>
            </div>
            <div class="flex items-baseline gap-2">
                <div id="{{ $id }}_time" class="text-3xl sm:text-4xl font-black font-mono tracking-tight text-white tabular-nums">
                    --:--:--
                </div>
                <span class="text-xs font-black uppercase tracking-widest text-sky-300">WITA</span>
            </div>
        </div>
    </div>
@elseif($variant === 'compact')
    <div class="inline-flex items-center gap-3 px-4 py-2.5 bg-slate-900 text-white rounded-2xl shadow-sm border border-slate-700">
        <i class="fa-regular fa-clock text-[#f7ad1a] text-xs" aria-hidden="true"></i>
        <div class="flex items-baseline gap-1.5 font-mono">
            <span id="{{ $id }}_time" class="text-sm font-black text-white tabular-nums">--:--:--</span>
            <span class="text-xs font-black text-slate-300">WITA</span>
        </div>
        <span class="text-slate-500">|</span>
        <span id="{{ $id }}_date" class="text-xs font-bold text-slate-300">--</span>
    </div>
@else
    {{-- Inline minimal --}}
    <span class="inline-flex items-center gap-1.5 font-mono font-bold text-xs text-slate-700">
        <i class="fa-regular fa-clock text-[#063f5c]" aria-hidden="true"></i>
        <span id="{{ $id }}_time" class="tabular-nums">--:--:--</span>
        <span class="text-primary-700 font-black text-xs">WITA</span>
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
