@extends('layouts.app')

@section('content')
<section class="max-w-7xl mx-auto px-6 py-20">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- Sidebar Menu -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white border border-slate-100 p-8 rounded-3xl space-y-6 shadow-sm">
                <h3 class="font-montserrat font-black uppercase text-xs sm:text-sm text-slate-800 tracking-wider">Navigasi Admin</h3>
                <hr class="border-slate-100">
                <div class="flex flex-col space-y-2 font-bold text-[10px] uppercase tracking-wider text-slate-500">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 py-3 px-4 rounded-xl bg-primary-50 text-primary-700">
                        <i class="fa-solid fa-chart-pie"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('admin.campaigns') }}" class="flex items-center space-x-3 py-3 px-4 rounded-xl hover:bg-slate-50 hover:text-slate-800 transition-colors">
                        <i class="fa-solid fa-gift"></i>
                        <span>Kelola Kampanye</span>
                    </a>
                    <a href="{{ route('admin.donations') }}" class="flex items-center space-x-3 py-3 px-4 rounded-xl hover:bg-slate-50 hover:text-slate-800 transition-colors">
                        <i class="fa-solid fa-file-invoice-dollar"></i>
                        <span>Verifikasi Donasi</span>
                    </a>
                    <a href="{{ route('admin.distributions') }}" class="flex items-center space-x-3 py-3 px-4 rounded-xl hover:bg-slate-50 hover:text-slate-800 transition-colors">
                        <i class="fa-solid fa-truck-ramp-box"></i>
                        <span>Distribusi</span>
                    </a>
                    <a href="{{ route('admin.users') }}" class="flex items-center space-x-3 py-3 px-4 rounded-xl hover:bg-slate-50 hover:text-slate-800 transition-colors">
                        <i class="fa-solid fa-users"></i>
                        <span>Kelola Pengguna</span>
                    </a>
                    <a href="{{ route('admin.settings') }}" class="flex items-center space-x-3 py-3 px-4 rounded-xl hover:bg-slate-50 hover:text-slate-800 transition-colors">
                        <i class="fa-solid fa-gears"></i>
                        <span>Pengaturan Web</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Dashboard Content -->
        <div class="lg:col-span-3 space-y-8">
            <div class="space-y-2">
                <h1 class="text-2xl sm:text-3xl font-montserrat font-black text-slate-900 uppercase tracking-tight italic">
                    Dashboard <span class="text-primary-600">Manajemen Pusat</span>
                </h1>
                <div class="w-12 h-1 bg-primary-500 rounded-full"></div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <!-- Total Donations -->
                <div class="bg-white border border-slate-100 p-6 rounded-3xl shadow-sm flex items-center space-x-4">
                    <div class="bg-emerald-50 text-emerald-600 w-12 h-12 rounded-xl flex items-center justify-center text-xl shrink-0">
                        <i class="fa-solid fa-hand-holding-dollar"></i>
                    </div>
                    <div>
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block leading-none">Total Donasi Terkumpul</span>
                        <h3 class="text-base sm:text-lg font-montserrat font-black text-slate-800 mt-1">
                            Rp {{ number_format($totalDonationAmount, 0, ',', '.') }}
                        </h3>
                    </div>
                </div>

                <!-- Total Zakat -->
                <div class="bg-white border border-slate-100 p-6 rounded-3xl shadow-sm flex items-center space-x-4">
                    <div class="bg-primary-50 text-primary-600 w-12 h-12 rounded-xl flex items-center justify-center text-xl shrink-0">
                        <i class="fa-solid fa-handshake"></i>
                    </div>
                    <div>
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block leading-none">Total Zakat Terkumpul</span>
                        <h3 class="text-base sm:text-lg font-montserrat font-black text-slate-800 mt-1">
                            Rp {{ number_format($totalZakatAmount, 0, ',', '.') }}
                        </h3>
                    </div>
                </div>

                <!-- Pending Proof Verifications -->
                <div class="bg-white border border-slate-100 p-6 rounded-3xl shadow-sm flex items-center space-x-4">
                    <div class="bg-amber-50 text-amber-600 w-12 h-12 rounded-xl flex items-center justify-center text-xl shrink-0">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                    </div>
                    <div>
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block leading-none">Menunggu Verifikasi</span>
                        <h3 class="text-base sm:text-lg font-montserrat font-black text-slate-800 mt-1">
                            {{ $pendingDonations }} Bukti
                        </h3>
                    </div>
                </div>
            </div>

            <!-- Charts Section (Chart.js via CDN) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Bar Chart (Monthly) -->
                <div class="bg-white border border-slate-100 p-6 rounded-3xl shadow-sm">
                    <h3 class="font-montserrat font-black uppercase text-xs text-slate-800 tracking-wider mb-4">Donasi Bulanan</h3>
                    <canvas id="monthlyChart" height="200"></canvas>
                </div>

                <!-- Pie Chart (Categories) -->
                <div class="bg-white border border-slate-100 p-6 rounded-3xl shadow-sm">
                    <h3 class="font-montserrat font-black uppercase text-xs text-slate-800 tracking-wider mb-4">Donasi Per Kategori</h3>
                    <canvas id="categoryChart" height="200"></canvas>
                </div>
            </div>

            <!-- Recent Verified Transactions -->
            <div class="bg-white border border-slate-100 rounded-3xl p-6 sm:p-8 space-y-6 shadow-sm">
                <h3 class="font-montserrat font-black uppercase text-sm text-slate-800 tracking-wider">Aktivitas Donasi Terbaru</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 text-[10px] font-black uppercase tracking-wider text-slate-400">
                                <th class="pb-4">Donatur</th>
                                <th class="pb-4">Kampanye</th>
                                <th class="pb-4">Jumlah</th>
                                <th class="pb-4">Status</th>
                            </tr>
                        </thead>
                        <tbody class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                            @forelse($recentTransactions as $r)
                                <tr class="border-b border-slate-50 last:border-0">
                                    <td class="py-4">{{ $r->donor_name }}</td>
                                    <td class="py-4 pr-4 max-w-[200px] line-clamp-1 mt-3">{{ $r->campaign->title }}</td>
                                    <td class="py-4">Rp {{ number_format($r->amount, 0, ',', '.') }}</td>
                                    <td class="py-4">
                                        @if($r->payment_status === 'Success')
                                            <span class="bg-emerald-50 text-emerald-700 text-[8px] font-black uppercase tracking-widest px-2.5 py-1 rounded-md">Success</span>
                                        @elseif($r->payment_status === 'Pending')
                                            <span class="bg-amber-50 text-amber-700 text-[8px] font-black uppercase tracking-widest px-2.5 py-1 rounded-md">Pending</span>
                                        @else
                                            <span class="bg-rose-50 text-rose-700 text-[8px] font-black uppercase tracking-widest px-2.5 py-1 rounded-md">Failed</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-8 text-center text-slate-400 font-bold uppercase tracking-wider">Belum ada donasi terdaftar.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>
</section>

<!-- ChartJS Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // 1. Monthly Bar Chart
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
    const monthlyTotals = Array(12).fill(0);
    @foreach($monthlyData as $data)
        monthlyTotals[{{ $data->month - 1 }}] = {{ $data->total }};
    @endforeach

    new Chart(document.getElementById('monthlyChart'), {
        type: 'bar',
        data: {
            labels: months,
            datasets: [{
                label: 'Total Donasi (Rp)',
                data: monthlyTotals,
                backgroundColor: '#22c55e',
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // 2. Category Pie Chart
    const categories = [];
    const categoryTotals = [];
    @foreach($categoryData as $data)
        categories.push('{{ $data->category }}');
        categoryTotals.push({{ $data->total }});
    @endforeach

    new Chart(document.getElementById('categoryChart'), {
        type: 'pie',
        data: {
            labels: categories,
            datasets: [{
                data: categoryTotals,
                backgroundColor: ['#22c55e', '#3b82f6', '#f59e0b', '#ef4444', '#8b5cf6']
            }]
        },
        options: {
            responsive: true
        }
    });
</script>
@endsection
