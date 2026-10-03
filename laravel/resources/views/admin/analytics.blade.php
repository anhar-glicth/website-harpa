@extends('layouts.app')

@section('content')
<section class="max-w-7xl mx-auto px-6 py-20" x-data="{ activeTab: 'rfm' }">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- Sidebar Menu -->
        <div class="hidden lg:block lg:col-span-1 space-y-6">
            @include('admin.sidebar', ['active' => 'analytics'])
        </div>

        <!-- Dashboard Content -->
        <div class="lg:col-span-3 space-y-8">
            
            <!-- Header section -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div class="space-y-2">
                    <h1 class="text-2xl sm:text-3xl font-montserrat font-black text-slate-900 uppercase tracking-tight italic">
                        Pusat <span class="text-primary-600">Analisa Data & CRM</span>
                    </h1>
                    <p class="text-slate-400 font-bold text-[10px] tracking-widest uppercase">Segmentasi donatur, data prospek CRM, dan alokasi penyaluran</p>
                    <div class="w-12 h-1 bg-primary-500 rounded-full mt-2"></div>
                </div>

                <!-- Tabs Navigation -->
                <div class="bg-slate-100 p-1.5 rounded-2xl flex gap-1 self-stretch md:self-auto shadow-sm">
                    <button 
                        @click="activeTab = 'rfm'"
                        :class="activeTab === 'rfm' ? 'bg-white text-slate-800 shadow-sm' : 'text-slate-500 hover:text-slate-800'"
                        class="flex-1 md:flex-none px-4 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-wider transition-all duration-300">
                        Analisa RFM
                    </button>
                    <button 
                        @click="activeTab = 'crm'"
                        :class="activeTab === 'crm' ? 'bg-white text-slate-800 shadow-sm' : 'text-slate-500 hover:text-slate-800'"
                        class="flex-1 md:flex-none px-4 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-wider transition-all duration-300">
                        Prospek CRM
                    </button>
                    <button 
                        @click="activeTab = 'dist'"
                        :class="activeTab === 'dist' ? 'bg-white text-slate-800 shadow-sm' : 'text-slate-500 hover:text-slate-800'"
                        class="flex-1 md:flex-none px-4 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-wider transition-all duration-300">
                        Penyaluran
                    </button>
                </div>
            </div>

            <!-- ======================= TAB 1: RFM ANALYTICS ======================= -->
            <div x-show="activeTab === 'rfm'" class="space-y-8 animate-in fade-in duration-500">
                <!-- KPI Totals -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm space-y-1">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Total Donatur</span>
                        <h4 class="text-2xl font-montserrat font-black text-slate-800">{{ $totalDonors }}</h4>
                        <span class="text-[8px] font-bold text-slate-400 uppercase">Donatur Terdaftar</span>
                    </div>
                    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm space-y-1">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Donatur Pria</span>
                        <h4 class="text-2xl font-montserrat font-black text-secondary-600">{{ $maleDonors }}</h4>
                        <span class="text-[8px] font-bold text-slate-400 uppercase">Gender Laki-laki</span>
                    </div>
                    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm space-y-1">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Donatur Wanita</span>
                        <h4 class="text-2xl font-montserrat font-black text-primary-500">{{ $femaleDonors }}</h4>
                        <span class="text-[8px] font-bold text-slate-400 uppercase">Gender Perempuan</span>
                    </div>
                    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm space-y-1">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Anonim / Unknown</span>
                        <h4 class="text-2xl font-montserrat font-black text-slate-500">{{ $unknownDonors }}</h4>
                        <span class="text-[8px] font-bold text-slate-400 uppercase">Tanpa Gender</span>
                    </div>
                </div>

                <!-- Charts & Segmentation Table -->
                <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
                    <!-- Segment Chart -->
                    <div class="md:col-span-5 bg-white border border-slate-100 p-6 rounded-3xl shadow-sm flex flex-col justify-between">
                        <h3 class="font-montserrat font-black uppercase text-xs text-slate-800 tracking-wider mb-4">Segmentasi RFM (All Time)</h3>
                        <div class="relative h-[220px] flex items-center justify-center">
                            <canvas id="rfmChart"></canvas>
                        </div>
                    </div>

                    <!-- Segment Breakdown Table -->
                    <div class="md:col-span-7 bg-white border border-slate-100 p-6 rounded-3xl shadow-sm space-y-4">
                        <h3 class="font-montserrat font-black uppercase text-xs text-slate-800 tracking-wider">Tingkat Retensi & Segmen</h3>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse text-xs">
                                <thead>
                                    <tr class="border-b border-slate-100 text-[9px] font-black uppercase tracking-wider text-slate-400">
                                        <th class="pb-3">Profil Segmen</th>
                                        <th class="pb-3 text-right">Jumlah Donatur</th>
                                        <th class="pb-3 text-right">Porsi (%)</th>
                                    </tr>
                                </thead>
                                <tbody class="font-semibold text-slate-650 uppercase tracking-wide">
                                    @php $totalCounted = $rfmSegmentCounts->sum('count'); @endphp
                                    @forelse($rfmSegmentCounts as $seg)
                                        <tr class="border-b border-slate-50 last:border-0 hover:bg-slate-50/50 transition-all">
                                            <td class="py-3">
                                                <span class="px-2.5 py-1 rounded-md text-[9px] font-black uppercase tracking-wider 
                                                    @if(in_array($seg->rfm_alltime, ['CHAMPION', 'Champion'])) bg-emerald-50 text-emerald-700
                                                    @elseif(in_array($seg->rfm_alltime, ['LOYAL', 'Loyal'])) bg-blue-50 text-blue-700
                                                    @elseif(in_array($seg->rfm_alltime, ['POTENTIAL', 'Potential'])) bg-orange-50 text-orange-700
                                                    @elseif(in_array($seg->rfm_alltime, ['DORMANT', 'Dormant'])) bg-red-50 text-red-700
                                                    @else bg-slate-100 text-slate-600 @endif">
                                                    {{ $seg->rfm_alltime }}
                                                </span>
                                            </td>
                                            <td class="py-3 text-right font-bold text-slate-800">{{ $seg->count }} Orang</td>
                                            <td class="py-3 text-right text-slate-400">{{ $totalCounted > 0 ? round(($seg->count / $totalCounted) * 100, 1) : 0 }}%</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="py-8 text-center text-slate-400 font-bold uppercase tracking-wider">Belum ada segmentasi terdaftar.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Top 20 Donors List -->
                <div class="bg-white border border-slate-100 rounded-3xl p-6 sm:p-8 space-y-6 shadow-sm">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-primary-50 text-primary-600 rounded-2xl flex items-center justify-center text-base"><i class="fa-solid fa-crown"></i></div>
                        <div>
                            <h3 class="font-montserrat font-black uppercase text-sm text-slate-800 tracking-wider">Top 20 Donatur Terbesar</h3>
                            <span class="text-[8px] text-slate-400 font-bold uppercase tracking-widest">Akumulasi Kontribusi Dana (All Time)</span>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="border-b border-slate-100 text-[9px] font-black uppercase tracking-wider text-slate-400">
                                    <th class="pb-4">No</th>
                                    <th class="pb-4">Nama Donatur</th>
                                    <th class="pb-4">Email</th>
                                    <th class="pb-4">Segmen RFM</th>
                                    <th class="pb-4 text-right">Frekuensi Trx</th>
                                    <th class="pb-4 text-right">Total Donasi</th>
                                    <th class="pb-4 text-right">% Kontribusi</th>
                                </tr>
                            </thead>
                            <tbody class="font-semibold text-slate-650 uppercase tracking-wide">
                                @forelse($top20Donors as $index => $donor)
                                    <tr class="border-b border-slate-50 last:border-0 hover:bg-slate-50/50 transition-all">
                                        <td class="py-4">{{ $index + 1 }}</td>
                                        <td class="py-4 font-bold text-slate-800">{{ $donor->name }}</td>
                                        <td class="py-4 lowercase">{{ $donor->email }}</td>
                                        <td class="py-4">
                                            <span class="px-2.5 py-1 rounded-md text-[9px] font-black uppercase tracking-wider 
                                                @if(in_array($donor->rfm_alltime, ['CHAMPION', 'Champion'])) bg-emerald-50 text-emerald-700
                                                @elseif(in_array($donor->rfm_alltime, ['LOYAL', 'Loyal'])) bg-blue-50 text-blue-700
                                                @elseif(in_array($donor->rfm_alltime, ['POTENTIAL', 'Potential'])) bg-orange-50 text-orange-700
                                                @elseif(in_array($donor->rfm_alltime, ['DORMANT', 'Dormant'])) bg-red-50 text-red-700
                                                @else bg-slate-100 text-slate-600 @endif">
                                                {{ $donor->rfm_alltime ?: 'NEW' }}
                                            </span>
                                        </td>
                                        <td class="py-4 text-right font-bold text-slate-850">{{ $donor->donations_count }}x</td>
                                        <td class="py-4 text-right font-black text-slate-850">Rp {{ number_format($donor->donations_sum_amount ?: 0, 0, ',', '.') }}</td>
                                        <td class="py-4 text-right">
                                            <span class="bg-primary-50 text-primary-700 font-bold px-2.5 py-1 rounded-md text-[9px]">
                                                {{ $totalGlobalDonations > 0 ? number_format(($donor->donations_sum_amount / $totalGlobalDonations) * 100, 2) : '0' }}%
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-8 text-center text-slate-400 font-bold uppercase tracking-wider">Belum ada donasi terdaftar.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- ======================= TAB 2: CRM PROSPECTS ======================= -->
            <div x-show="activeTab === 'crm'" class="space-y-8 animate-in fade-in duration-500">
                <!-- KPI Totals -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm space-y-1">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Total Prospek CRM</span>
                        <h4 class="text-2xl font-montserrat font-black text-slate-800">{{ $totalProspects }}</h4>
                        <span class="text-[8px] font-bold text-slate-400 uppercase">Prospek Terkumpul</span>
                    </div>
                    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm space-y-1">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Konversi Tertarik</span>
                        <h4 class="text-2xl font-montserrat font-black text-emerald-655">{{ $convertedProspects }}</h4>
                        <span class="text-[8px] font-bold text-slate-400 uppercase">Prospek Deal</span>
                    </div>
                    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm space-y-1">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Follow Up Progres</span>
                        <h4 class="text-2xl font-montserrat font-black text-orange-500">{{ $followUpProspects }}</h4>
                        <span class="text-[8px] font-bold text-slate-400 uppercase">Belum Selesai</span>
                    </div>
                    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm space-y-1">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Estimasi Potensi Dana</span>
                        <h4 class="text-xl font-montserrat font-black text-secondary-600 mt-0.5">Rp {{ number_format($totalPotentialFunds, 0, ',', '.') }}</h4>
                        <span class="text-[8px] font-bold text-slate-400 uppercase">Proyeksi Dana Prospek</span>
                    </div>
                </div>

                <!-- 2-column CRM logs and list -->
                <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
                    <!-- Recent Responses Log (Left/2 cols) -->
                    <div class="xl:col-span-2 bg-white border border-slate-100 rounded-3xl p-6 sm:p-8 space-y-6 shadow-sm">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-secondary-50 text-secondary-600 rounded-2xl flex items-center justify-center text-base"><i class="fa-solid fa-comments"></i></div>
                            <div>
                                <h3 class="font-montserrat font-black uppercase text-sm text-slate-800 tracking-wider">Log Follow Up Prospek</h3>
                                <span class="text-[8px] text-slate-400 font-bold uppercase tracking-widest">Aktivitas komunikasi prospek donatur terbaru</span>
                            </div>
                        </div>

                        <div class="overflow-y-auto max-h-[400px] pr-2 space-y-4 no-scrollbar">
                            @forelse($recentProspectResponses as $res)
                                <div class="bg-slate-50/50 border border-slate-100 p-4 rounded-2xl space-y-2 hover:border-primary-100 transition duration-300">
                                    <div class="flex justify-between items-start gap-4">
                                        <div>
                                            <h4 class="text-xs font-black uppercase text-slate-800 tracking-wide">{{ $res->prospect->name }}</h4>
                                            <span class="text-[8px] text-slate-400 font-bold uppercase tracking-wider block mt-0.5">{{ $res->created_at->diffForHumans() }}</span>
                                        </div>
                                        <span class="px-2 py-0.5 rounded text-[8px] font-black uppercase tracking-wider 
                                            @if($res->status === 'Tertarik') bg-emerald-50 text-emerald-700
                                            @elseif($res->status === 'Follow Up') bg-orange-50 text-orange-700
                                            @else bg-red-50 text-red-700 @endif">
                                            {{ $res->status }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-slate-650 leading-relaxed font-semibold italic">
                                        "{{ $res->response }}"
                                    </p>
                                </div>
                            @empty
                                <div class="py-16 text-center text-slate-400 font-bold uppercase tracking-wider text-xs">
                                    Belum ada log komunikasi prospek terbaru.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Prospects Table (Right/1 col) -->
                    <div class="bg-white border border-slate-100 rounded-3xl p-6 sm:p-8 space-y-6 shadow-sm">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-primary-50 text-primary-600 rounded-2xl flex items-center justify-center text-base"><i class="fa-solid fa-address-book"></i></div>
                            <div>
                                <h3 class="font-montserrat font-black uppercase text-sm text-slate-800 tracking-wider">Daftar Kontak Prospek</h3>
                                <span class="text-[8px] text-slate-400 font-bold uppercase tracking-widest">Kontak prospek yang terdaftar</span>
                            </div>
                        </div>

                        <div class="overflow-y-auto max-h-[400px] pr-2 space-y-3 no-scrollbar">
                            @forelse($top20Prospects as $pro)
                                <div class="p-3 bg-white border border-slate-100 rounded-2xl flex items-center justify-between hover:shadow-md transition-shadow">
                                    <div class="space-y-0.5 truncate">
                                        <span class="text-xs font-black uppercase text-slate-800 block truncate">{{ $pro->name }}</span>
                                        <span class="text-[9px] text-slate-450 font-bold block">{{ $pro->phone ?: $pro->email ?: '-' }}</span>
                                    </div>
                                    <span class="px-2 py-0.5 rounded text-[8px] font-black uppercase tracking-wider shrink-0 
                                        @if($pro->responses->last()?->status === 'Tertarik') bg-emerald-50 text-emerald-700
                                        @elseif($pro->responses->last()?->status === 'Follow Up') bg-orange-50 text-orange-700
                                        @else bg-slate-50 text-slate-600 @endif">
                                        {{ $pro->responses->last()?->status ?: 'Baru' }}
                                    </span>
                                </div>
                            @empty
                                <div class="py-16 text-center text-slate-400 font-bold uppercase tracking-wider text-xs">
                                    Belum ada kontak prospek terdaftar.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- ======================= TAB 3: DISTRIBUTIONS ======================= -->
            <div x-show="activeTab === 'dist'" class="space-y-8 animate-in fade-in duration-500">
                <!-- KPI Totals -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm space-y-1 col-span-2">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Total Penyaluran Bantuan</span>
                        <h4 class="text-2xl font-montserrat font-black text-slate-800">Rp {{ number_format($totalDistributions, 0, ',', '.') }}</h4>
                        <span class="text-[8px] font-bold text-slate-400 uppercase">Dana Tersalurkan Lapangan</span>
                    </div>
                    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm space-y-1 col-span-2">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Frekuensi Distribusi</span>
                        <h4 class="text-2xl font-montserrat font-black text-secondary-600">{{ $distributionCount }} Penyaluran</h4>
                        <span class="text-[8px] font-bold text-slate-400 uppercase">Aktivitas Distribusi</span>
                    </div>
                </div>

                <!-- Recent Distributions Log Table -->
                <div class="bg-white border border-slate-100 rounded-3xl p-6 sm:p-8 space-y-6 shadow-sm">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-primary-50 text-primary-600 rounded-2xl flex items-center justify-center text-base"><i class="fa-solid fa-truck-ramp-box"></i></div>
                        <div>
                            <h3 class="font-montserrat font-black uppercase text-sm text-slate-800 tracking-wider">Log Penyaluran Program Terakhir</h3>
                            <span class="text-[8px] text-slate-400 font-bold uppercase tracking-widest">Detail penyaluran dana program langsung oleh staff di lapangan</span>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="border-b border-slate-100 text-[9px] font-black uppercase tracking-wider text-slate-400">
                                    <th class="pb-4">Penerima Manfaat</th>
                                    <th class="pb-4">Kampanye Program</th>
                                    <th class="pb-4">Tanggal Penyaluran</th>
                                    <th class="pb-4">Provinsi / Kota</th>
                                    <th class="pb-4 text-right">Jumlah Dana</th>
                                    <th class="pb-4 text-right">Status</th>
                                </tr>
                            </thead>
                            <tbody class="font-semibold text-slate-650 uppercase tracking-wide">
                                @forelse($recentDistributions as $dist)
                                    <tr class="border-b border-slate-50 last:border-0 hover:bg-slate-50/50 transition-all">
                                        <td class="py-4">
                                            <div class="space-y-0.5">
                                                <span class="font-bold text-slate-800 block leading-tight">{{ $dist->recipient_name }}</span>
                                                <span class="text-[8px] font-bold text-slate-400 block tracking-wider uppercase">Asnaf: {{ $dist->asnaf ?: '-' }}</span>
                                            </div>
                                        </td>
                                        <td class="py-4 pr-4 max-w-[200px] truncate">{{ $dist->campaign->title }}</td>
                                        <td class="py-4">{{ \Carbon\Carbon::parse($dist->distribution_date)->format('d M Y') }}</td>
                                        <td class="py-4">{{ $dist->city ?: '-' }}, {{ $dist->province ?: '-' }}</td>
                                        <td class="py-4 text-right font-black text-slate-850">Rp {{ number_format($dist->amount, 0, ',', '.') }}</td>
                                        <td class="py-4 text-right">
                                            <span class="bg-emerald-50 text-emerald-700 font-black px-2.5 py-1 rounded-md text-[9px] uppercase tracking-wider">
                                                Success
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-8 text-center text-slate-400 font-bold uppercase tracking-wider">Belum ada log penyaluran program terdaftar.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>

<!-- ChartJS Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // 1. RFM Doughnut Chart
        const rfmLabels = [];
        const rfmData = [];
        @foreach($rfmSegmentCounts as $seg)
            rfmLabels.push('{{ $seg->rfm_alltime }}');
            rfmData.push({{ $seg->count }});
        @endforeach

        if(rfmLabels.length > 0) {
            new Chart(document.getElementById('rfmChart'), {
                type: 'doughnut',
                data: {
                    labels: rfmLabels,
                    datasets: [{
                        data: rfmData,
                        backgroundColor: ['#f97316', '#29abe2', '#ea580c', '#0284c7', '#fed7aa', '#bae6fd']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                font: { size: 9, weight: 'bold' },
                                boxWidth: 10
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endsection
