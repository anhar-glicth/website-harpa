@extends('layouts.app')

@section('content')
<section class="max-w-7xl mx-auto px-6 py-20" 
         x-data="{
             spend: {{ old('spend', $ad->spend) }},
             impressions: {{ old('impressions', $ad->impressions) }},
             clicks: {{ old('clicks', $ad->clicks) }},
             conversions: {{ old('conversions', $ad->conversions) }},
             donation_amount: {{ old('donation_amount', $ad->donation_amount) }},
             decisionManual: '{{ old('decision', $ad->decision ?? 'AUTO') }}',

             get cpc() {
                 return this.clicks > 0 ? (this.spend / this.clicks).toFixed(0) : 0;
             },
             get ctr() {
                 return this.impressions > 0 ? ((this.clicks / this.impressions) * 100).toFixed(2) : 0;
             },
             get cpm() {
                 return this.impressions > 0 ? ((this.spend / this.impressions) * 1000).toFixed(0) : 0;
             },
             get cpa() {
                 return this.conversions > 0 ? (this.spend / this.conversions).toFixed(0) : 0;
             },
             get roas() {
                 return this.spend > 0 ? (this.donation_amount / this.spend).toFixed(2) : 0;
             },
             get calculatedDecision() {
                 if (this.decisionManual && this.decisionManual !== 'AUTO') {
                     return this.decisionManual;
                 }
                 const r = parseFloat(this.roas);
                 const sp = parseFloat(this.spend);
                 const c = parseInt(this.conversions);
                 const costPerConv = parseFloat(this.cpa);
                 const ct = parseFloat(this.ctr);

                 if (r >= 3.0 || (c >= 5 && costPerConv <= 50000 && costPerConv > 0)) {
                     return 'SCALE';
                 } else if (sp >= 100000 && (r < 1.0 && ct < 0.8)) {
                     return 'STOP';
                 }
                 return 'MAINTAIN';
             }
         }">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- Sidebar Menu -->
        <div class="hidden lg:block lg:col-span-1 space-y-6">
            @include('admin.sidebar', ['active' => 'marketing_edit'])
        </div>

        <!-- Content Area -->
        <div class="lg:col-span-3 space-y-8">
            
            <!-- Header section -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div class="space-y-2">
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-full text-[10px] font-black uppercase tracking-widest shadow-md">
                        <i class="fa-solid fa-pen-to-square"></i> Perbarui Data Iklan
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-montserrat font-black text-slate-900 uppercase tracking-tight italic">
                        Edit Data <span class="text-primary-600">Iklan & Hasil</span>
                    </h1>
                    <p class="text-slate-400 font-bold text-[10px] tracking-widest uppercase">
                        Perbarui rincian biaya, hasil donasi, dan keputusan strategi iklan
                    </p>
                    <div class="w-12 h-1 bg-primary-500 rounded-full mt-2"></div>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.marketing.index') }}" 
                       class="px-4 py-2.5 bg-slate-100 text-slate-700 rounded-2xl text-[10px] font-black uppercase tracking-wider hover:bg-slate-200 transition-all flex items-center gap-2">
                        <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar
                    </a>
                </div>
            </div>

            <!-- Errors Alert -->
            @if($errors->any())
            <div class="p-4 bg-rose-50 border border-rose-200 rounded-2xl text-rose-800 text-xs font-bold space-y-1">
                <div class="flex items-center gap-2 font-black text-rose-900 mb-1">
                    <i class="fa-solid fa-triangle-exclamation"></i> Terdapat kesalahan pada input:
                </div>
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Live Calculation Preview Widget -->
            <div class="bg-gradient-to-br from-slate-900 via-[#063f5c] to-indigo-950 rounded-[2rem] p-6 text-white shadow-xl relative overflow-hidden">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-white/10 pb-4 mb-4">
                    <div>
                        <div class="text-[10px] font-bold text-amber-400 uppercase tracking-widest flex items-center gap-1.5">
                            <i class="fa-solid fa-calculator"></i> Live Metric Calculation Preview
                        </div>
                        <h3 class="text-lg font-black text-white">Estimasi Performa & Rekomendasi Otomatis</h3>
                    </div>
                    <div>
                        <span class="px-4 py-2 rounded-2xl text-xs font-black uppercase tracking-wider inline-flex items-center gap-2 shadow-lg"
                              :class="{
                                  'bg-emerald-500 text-white': calculatedDecision === 'SCALE',
                                  'bg-amber-500 text-white': calculatedDecision === 'MAINTAIN',
                                  'bg-rose-500 text-white': calculatedDecision === 'STOP'
                              }">
                            <i class="fa-solid" :class="{
                                'fa-arrow-trend-up': calculatedDecision === 'SCALE',
                                'fa-wrench': calculatedDecision === 'MAINTAIN',
                                'fa-ban': calculatedDecision === 'STOP'
                            }"></i>
                            Rekomendasi: <span x-text="calculatedDecision"></span>
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
                    <div class="bg-slate-900/55 p-3 rounded-xl border border-white/15">
                        <div class="text-[11px] uppercase font-bold text-slate-200">ROAS</div>
                        <div class="text-xl font-black text-amber-300" x-text="roas + 'x'">{{ $ad->roas }}x</div>
                        <div class="text-xs text-slate-300">Hasil / Biaya</div>
                    </div>
                    <div class="bg-slate-900/55 p-3 rounded-xl border border-white/15">
                        <div class="text-[11px] uppercase font-bold text-slate-200">CTR</div>
                        <div class="text-xl font-black text-indigo-200" x-text="ctr + '%'">{{ $ad->ctr }}%</div>
                        <div class="text-xs text-slate-300">Klik / Tayangan</div>
                    </div>
                    <div class="bg-slate-900/55 p-3 rounded-xl border border-white/15">
                        <div class="text-[11px] uppercase font-bold text-slate-200">Cost Per Click (CPC)</div>
                        <div class="text-xl font-black text-white" x-text="'Rp ' + Number(cpc).toLocaleString('id-ID')">Rp {{ number_format($ad->cpc, 0, ',', '.') }}</div>
                        <div class="text-xs text-slate-300">Biaya per Klik</div>
                    </div>
                    <div class="bg-slate-900/55 p-3 rounded-xl border border-white/15">
                        <div class="text-[11px] uppercase font-bold text-slate-200">Cost Per Lead (CPA)</div>
                        <div class="text-xl font-black text-white" x-text="'Rp ' + Number(cpa).toLocaleString('id-ID')">Rp {{ number_format($ad->cost_per_conversion, 0, ',', '.') }}</div>
                        <div class="text-xs text-slate-300">Biaya per Donasi</div>
                    </div>
                    <div class="bg-slate-900/55 p-3 rounded-xl border border-white/15">
                        <div class="text-[11px] uppercase font-bold text-slate-200">CPM (per 1K Impr)</div>
                        <div class="text-xl font-black text-white" x-text="'Rp ' + Number(cpm).toLocaleString('id-ID')">Rp {{ number_format($ad->cpm, 0, ',', '.') }}</div>
                        <div class="text-xs text-slate-300">Biaya 1.000 Tayangan</div>
                    </div>
                </div>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-[2rem] p-8 border border-slate-100 shadow-sm">
                <form method="POST" action="{{ route('admin.marketing.update', $ad->id) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Baris 1: Informasi Dasar -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-2">
                                Tanggal Pelaporan <span class="text-rose-500">*</span>
                            </label>
                            <input type="date" name="date" value="{{ old('date', $ad->date ? $ad->date->format('Y-m-d') : '') }}" required
                                   class="w-full text-xs font-bold text-slate-800 rounded-xl border-slate-200 focus:ring-primary-500 focus:border-primary-500 py-3">
                        </div>

                        <div>
                            <label class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-2">
                                Platform Iklan <span class="text-rose-500">*</span>
                            </label>
                            <select name="platform" required
                                    class="w-full text-xs font-bold text-slate-800 rounded-xl border-slate-200 focus:ring-primary-500 focus:border-primary-500 py-3">
                                <option value="Meta Ads" {{ old('platform', $ad->platform) === 'Meta Ads' ? 'selected' : '' }}>Meta Ads (Facebook & Instagram)</option>
                                <option value="Google Ads" {{ old('platform', $ad->platform) === 'Google Ads' ? 'selected' : '' }}>Google Ads / Search</option>
                                <option value="TikTok Ads" {{ old('platform', $ad->platform) === 'TikTok Ads' ? 'selected' : '' }}>TikTok Ads</option>
                                <option value="YouTube Ads" {{ old('platform', $ad->platform) === 'YouTube Ads' ? 'selected' : '' }}>YouTube Ads</option>
                                <option value="Lainnya" {{ old('platform', $ad->platform) === 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-2">
                                Kampanye Terkait di Website
                            </label>
                            <select name="campaign_id"
                                    class="w-full text-xs font-bold text-slate-800 rounded-xl border-slate-200 focus:ring-primary-500 focus:border-primary-500 py-3">
                                <option value="">-- Pilih Kampanye Website (Opsional) --</option>
                                @foreach($campaigns as $camp)
                                    <option value="{{ $camp->id }}" {{ old('campaign_id', $ad->campaign_id) == $camp->id ? 'selected' : '' }}>
                                        {{ $camp->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Baris 2: Nama Campaign, Adset, dan Ad -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-2">
                                Nama Campaign Iklan <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="campaign_name" value="{{ old('campaign_name', $ad->campaign_name) }}" required 
                                   class="w-full text-xs font-bold text-slate-800 rounded-xl border-slate-200 focus:ring-primary-500 focus:border-primary-500 py-3">
                        </div>

                        <div>
                            <label class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-2">
                                Nama Adset / Audience Target
                            </label>
                            <input type="text" name="adset_name" value="{{ old('adset_name', $ad->adset_name) }}" 
                                   class="w-full text-xs font-bold text-slate-800 rounded-xl border-slate-200 focus:ring-primary-500 focus:border-primary-500 py-3">
                        </div>

                        <div>
                            <label class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-2">
                                Nama Materi Iklan (Ad Creative)
                            </label>
                            <input type="text" name="ad_name" value="{{ old('ad_name', $ad->ad_name) }}" 
                                   class="w-full text-xs font-bold text-slate-800 rounded-xl border-slate-200 focus:ring-primary-500 focus:border-primary-500 py-3">
                        </div>
                    </div>

                    <!-- Baris 3: Anggaran & Hasil (Biaya & Donasi) -->
                    <div class="p-6 bg-slate-50/70 rounded-2xl border border-slate-100 space-y-4">
                        <div class="text-xs font-black uppercase tracking-wider text-slate-800 flex items-center gap-2">
                            <i class="fa-solid fa-coins text-amber-500"></i> Metrik Biaya & Hasil Finansial
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-2">
                                    Biaya Iklan / Ad Spend (Rp) <span class="text-rose-500">*</span>
                                </label>
                                <input type="number" step="0.01" name="spend" x-model.number="spend" required min="0"
                                       class="w-full text-sm font-black text-slate-900 rounded-xl border-slate-200 focus:ring-primary-500 focus:border-primary-500 py-3">
                            </div>

                            <div>
                                <label class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-2">
                                    Total Donasi Didapat (Rp)
                                </label>
                                <input type="number" step="0.01" name="donation_amount" x-model.number="donation_amount" min="0"
                                       class="w-full text-sm font-black text-emerald-600 rounded-xl border-slate-200 focus:ring-primary-500 focus:border-primary-500 py-3">
                            </div>
                        </div>
                    </div>

                    <!-- Baris 4: Metrik Trafik & Engagement -->
                    <div class="p-6 bg-slate-50/70 rounded-2xl border border-slate-100 space-y-4">
                        <div class="text-xs font-black uppercase tracking-wider text-slate-800 flex items-center gap-2">
                            <i class="fa-solid fa-chart-pie text-blue-500"></i> Metrik Trafik & Konversi
                        </div>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-[10px] font-black uppercase tracking-wider text-slate-700 mb-1.5">
                                    Tayangan (Impressions)
                                </label>
                                <input type="number" name="impressions" x-model.number="impressions" min="0"
                                       class="w-full text-xs font-bold text-slate-800 rounded-xl border-slate-200 py-2.5">
                            </div>

                            <div>
                                <label class="block text-[10px] font-black uppercase tracking-wider text-slate-700 mb-1.5">
                                    Jangkauan (Reach)
                                </label>
                                <input type="number" name="reach" value="{{ old('reach', $ad->reach) }}" min="0"
                                       class="w-full text-xs font-bold text-slate-800 rounded-xl border-slate-200 py-2.5">
                            </div>

                            <div>
                                <label class="block text-[10px] font-black uppercase tracking-wider text-slate-700 mb-1.5">
                                    Tautan Diklik (Clicks)
                                </label>
                                <input type="number" name="clicks" x-model.number="clicks" min="0"
                                       class="w-full text-xs font-bold text-slate-800 rounded-xl border-slate-200 py-2.5">
                            </div>

                            <div>
                                <label class="block text-[10px] font-black uppercase tracking-wider text-slate-700 mb-1.5">
                                    Jumlah Donasi / Konversi
                                </label>
                                <input type="number" name="conversions" x-model.number="conversions" min="0"
                                       class="w-full text-xs font-bold text-slate-800 rounded-xl border-slate-200 py-2.5">
                            </div>
                        </div>
                    </div>

                    <!-- Baris 5: Status, Keputusan Manual & Catatan -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-2">
                                Status Iklan <span class="text-rose-500">*</span>
                            </label>
                            <select name="status" required
                                    class="w-full text-xs font-bold text-slate-800 rounded-xl border-slate-200 focus:ring-primary-500 focus:border-primary-500 py-3">
                                <option value="ACTIVE" {{ old('status', $ad->status) === 'ACTIVE' ? 'selected' : '' }}>ACTIVE (Sedang Berjalan)</option>
                                <option value="PAUSED" {{ old('status', $ad->status) === 'PAUSED' ? 'selected' : '' }}>PAUSED (Dijeda Sementara)</option>
                                <option value="ARCHIVED" {{ old('status', $ad->status) === 'ARCHIVED' ? 'selected' : '' }}>ARCHIVED (Diarsipkan / Selesai)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-2">
                                Keputusan Strategis
                            </label>
                            <select name="decision" x-model="decisionManual"
                                    class="w-full text-xs font-bold text-slate-800 rounded-xl border-slate-200 focus:ring-primary-500 focus:border-primary-500 py-3">
                                <option value="AUTO">AUTO (Dihitung Otomatis oleh Sistem)</option>
                                <option value="SCALE" {{ old('decision', $ad->decision) === 'SCALE' ? 'selected' : '' }}>SCALE (Naikkan Anggaran)</option>
                                <option value="MAINTAIN" {{ old('decision', $ad->decision) === 'MAINTAIN' ? 'selected' : '' }}>MAINTAIN (Pertahankan & Optimasi)</option>
                                <option value="STOP" {{ old('decision', $ad->decision) === 'STOP' ? 'selected' : '' }}>STOP (Matikan Iklan / Ganti Materi)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-2">
                                Catatan / Evaluasi Marketing
                            </label>
                            <textarea name="notes" rows="2"
                                      class="w-full text-xs font-bold text-slate-800 rounded-xl border-slate-200 focus:ring-primary-500 focus:border-primary-500">{{ old('notes', $ad->notes) }}</textarea>
                        </div>
                    </div>

                    <!-- Tombol Submit -->
                    <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                        <a href="{{ route('admin.marketing.index') }}" 
                           class="px-6 py-3 bg-slate-100 text-slate-600 rounded-xl text-xs font-black uppercase tracking-wider hover:bg-slate-200 transition-all">
                            Batal
                        </a>
                        <button type="submit" 
                                class="px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl text-xs font-black uppercase tracking-wider shadow-lg shadow-blue-500/20 hover:from-blue-700 hover:to-indigo-700 transition-all flex items-center gap-2">
                            <i class="fa-solid fa-floppy-disk"></i> Perbarui Data Iklan
                        </button>
                    </div>

                </form>
            </div>

        </div>

    </div>
</section>
@endsection

