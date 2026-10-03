@props([
    'latId' => 'lat',
    'lngId' => 'lng',
    'statusId' => 'gps-status',
    'locationId' => null,
    'defaultOfficeLat' => -6.175392,
    'defaultOfficeLng' => 106.827153,
    'defaultOfficeName' => 'Kantor Pusat'
])

<div class="bg-slate-50/80 border border-slate-200/80 rounded-2xl p-3.5 space-y-2 text-left">
    <div class="flex items-center justify-between gap-2 flex-wrap">
        <div id="{{ $statusId }}" class="text-[10px] font-bold text-slate-500 uppercase tracking-wider flex items-center gap-1.5">
            <i class="fa-solid fa-location-crosshairs animate-pulse text-amber-500"></i>
            <span>Mencari titik lokasi & koordinat GPS...</span>
        </div>
        <div class="flex items-center gap-1.5 shrink-0">
            <button type="button" onclick="window.runGpsDetection_{{ str_replace('-', '_', $statusId) }}()" 
                    class="px-2.5 py-1 bg-white hover:bg-slate-100 text-slate-700 rounded-lg text-[9px] font-black uppercase tracking-wider border border-slate-200 shadow-2xs transition-all flex items-center gap-1 cursor-pointer">
                <i class="fa-solid fa-arrows-rotate text-[8px]"></i> Deteksi Ulang
            </button>
            <button type="button" onclick="window.setOfficeLocation_{{ str_replace('-', '_', $statusId) }}()" 
                    class="px-2.5 py-1 bg-primary-50 hover:bg-primary-100 text-primary-700 rounded-lg text-[9px] font-black uppercase tracking-wider border border-primary-200 shadow-2xs transition-all flex items-center gap-1 cursor-pointer">
                <i class="fa-solid fa-building text-[8px]"></i> Lokasi Kantor
            </button>
        </div>
    </div>

    <!-- Manual coordinate toggle -->
    <details class="text-[9px] text-slate-400 group">
        <summary class="cursor-pointer hover:text-slate-600 transition-colors uppercase font-bold tracking-wider select-none flex items-center gap-1">
            <i class="fa-solid fa-sliders text-[8px]"></i> Input / Edit Koordinat Manual
        </summary>
        <div class="mt-2 grid grid-cols-2 gap-2 pt-2 border-t border-slate-200/60">
            <div>
                <label class="block text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-1">Latitude</label>
                <input type="text" id="manual_lat_{{ str_replace('-', '_', $statusId) }}" placeholder="-6.175392"
                       onchange="window.syncManualCoords_{{ str_replace('-', '_', $statusId) }}()"
                       class="w-full bg-white border border-slate-200 rounded-lg px-2.5 py-1.5 text-[11px] font-mono font-bold text-slate-700 outline-none focus:border-primary-500">
            </div>
            <div>
                <label class="block text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-1">Longitude</label>
                <input type="text" id="manual_lng_{{ str_replace('-', '_', $statusId) }}" placeholder="106.827153"
                       onchange="window.syncManualCoords_{{ str_replace('-', '_', $statusId) }}()"
                       class="w-full bg-white border border-slate-200 rounded-lg px-2.5 py-1.5 text-[11px] font-mono font-bold text-slate-700 outline-none focus:border-primary-500">
            </div>
        </div>
    </details>
</div>

<script>
(function() {
    const fnSuffix = "{{ str_replace('-', '_', $statusId) }}";
    const statusEl = document.getElementById("{{ $statusId }}");
    const latInput = document.getElementById("{{ $latId }}");
    const lngInput = document.getElementById("{{ $lngId }}");
    const locationInput = "{{ $locationId }}" ? document.getElementById("{{ $locationId }}") : null;
    const manualLat = document.getElementById("manual_lat_" + fnSuffix);
    const manualLng = document.getElementById("manual_lng_" + fnSuffix);

    const defaultLat = {{ $defaultOfficeLat }};
    const defaultLng = {{ $defaultOfficeLng }};
    const defaultName = "{{ $defaultOfficeName }}";

    function updateFields(lat, lng, sourceLabel, badgeClass = 'text-emerald-600') {
        if (latInput) latInput.value = lat;
        if (lngInput) lngInput.value = lng;
        if (manualLat) manualLat.value = Number(lat).toFixed(6);
        if (manualLng) manualLng.value = Number(lng).toFixed(6);

        // Also update any paired distribution inputs if present
        const distLat = document.getElementById('dist_lat');
        const distLng = document.getElementById('dist_lng');
        if (distLat) distLat.value = lat;
        if (distLng) distLng.value = lng;

        if (statusEl) {
            statusEl.innerHTML = `<span class="${badgeClass} flex items-center gap-1.5">
                <i class="fa-solid fa-location-dot"></i>
                <span>${sourceLabel}: <b>${Number(lat).toFixed(5)}, ${Number(lng).toFixed(5)}</b></span>
            </span>`;
        }
    }

    window["syncManualCoords_" + fnSuffix] = function() {
        const lat = parseFloat(manualLat.value);
        const lng = parseFloat(manualLng.value);
        if (!isNaN(lat) && !isNaN(lng)) {
            updateFields(lat, lng, 'Koordinat Manual', 'text-blue-600');
        }
    };

    window["setOfficeLocation_" + fnSuffix] = function() {
        updateFields(defaultLat, defaultLng, 'Titik Kantor Pusat', 'text-primary-700');
        if (locationInput && !locationInput.value) {
            locationInput.value = defaultName;
        }
    };

    async function tryIpGeolocation() {
        try {
            if (statusEl) {
                statusEl.innerHTML = `<span class="text-amber-600 flex items-center gap-1.5">
                    <i class="fa-solid fa-network-wired animate-pulse"></i>
                    <span>Mencoba deteksi lokasi via IP Jaringan...</span>
                </span>`;
            }
            // First provider
            const res = await fetch('https://ipapi.co/json/', { cache: 'no-store' });
            if (!res.ok) throw new Error('ipapi failed');
            const data = await res.json();
            if (data.latitude && data.longitude) {
                const city = data.city || data.region || 'Jaringan';
                updateFields(data.latitude, data.longitude, `Lokasi IP (${city})`, 'text-blue-600');
                if (locationInput && !locationInput.value) {
                    locationInput.value = `${city}, ${data.country_name || 'Indonesia'}`;
                }
                return true;
            }
        } catch (e) {
            // Second provider fallback
            try {
                const res2 = await fetch('https://freeipapi.com/api/json', { cache: 'no-store' });
                if (res2.ok) {
                    const data2 = await res2.json();
                    if (data2.latitude && data2.longitude) {
                        const city2 = data2.cityName || 'Jaringan';
                        updateFields(data2.latitude, data2.longitude, `Lokasi IP (${city2})`, 'text-blue-600');
                        if (locationInput && !locationInput.value) {
                            locationInput.value = `${city2}`;
                        }
                        return true;
                    }
                }
            } catch (err2) {
                // Ignore
            }
        }
        return false;
    }

    window["runGpsDetection_" + fnSuffix] = function() {
        if (statusEl) {
            statusEl.innerHTML = `<span class="text-amber-500 flex items-center gap-1.5">
                <i class="fa-solid fa-location-crosshairs animate-pulse"></i>
                <span>Mencari koordinat GPS perangkat...</span>
            </span>`;
        }

        // Check if browser has geolocation and is secure or localhost
        if (navigator.geolocation) {
            // Try high accuracy with short timeout
            navigator.geolocation.getCurrentPosition(
                function(pos) {
                    const lat = pos.coords.latitude;
                    const lng = pos.coords.longitude;
                    const acc = pos.coords.accuracy ? ` (±${Math.round(pos.coords.accuracy)}m)` : '';
                    updateFields(lat, lng, `GPS Perangkat${acc}`, 'text-emerald-600');
                },
                function(err) {
                    // Try low accuracy fallback
                    navigator.geolocation.getCurrentPosition(
                        function(posLow) {
                            const lat = posLow.coords.latitude;
                            const lng = posLow.coords.longitude;
                            updateFields(lat, lng, 'GPS Mode Hemat / Wi-Fi', 'text-emerald-600');
                        },
                        async function(errLow) {
                            // High & Low failed, fallback to IP Geolocation
                            const ipSuccess = await tryIpGeolocation();
                            if (!ipSuccess) {
                                // Fallback to office location so attendance coordinates are never empty
                                window["setOfficeLocation_" + fnSuffix]();
                            }
                        },
                        { enableHighAccuracy: false, timeout: 6000, maximumAge: 60000 }
                    );
                },
                { enableHighAccuracy: true, timeout: 5000, maximumAge: 30000 }
            );
        } else {
            // Geolocation unsupported, fallback to IP
            tryIpGeolocation().then(success => {
                if (!success) {
                    window["setOfficeLocation_" + fnSuffix]();
                }
            });
        }
    };

    // Auto-run on page load
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', window["runGpsDetection_" + fnSuffix]);
    } else {
        window["runGpsDetection_" + fnSuffix]();
    }
})();
</script>
