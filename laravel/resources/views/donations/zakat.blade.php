@extends('layouts.app')

@section('content')
<section class="max-w-7xl mx-auto px-6 py-20">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- Sidebar Links -->
        <div class="hidden lg:block lg:col-span-1 space-y-6">
            @include('layouts.donor-sidebar', ['active' => 'zakat'])
        </div>

        <!-- Dashboard Content -->
        <div class="lg:col-span-3 space-y-8">
            <div class="space-y-2">
                <h1 class="text-2xl sm:text-3xl font-montserrat font-black text-slate-900 uppercase tracking-tight italic">
                    Kalkulator & <span class="text-primary-600">Pembayaran Zakat</span>
                </h1>
                <div class="w-12 h-1 bg-primary-500 rounded-full"></div>
            </div>

            <!-- Two-column Zakat calculator and history -->
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
                <!-- Zakat Calculator (Client Side via Alpine.js) -->
                <div x-data="{
                    zakatType: 'zakat_pendapatan',
                    income: '',
                    otherIncome: '',
                    wealth: '',
                    threshold: 6859394, // Standard nisab monthly
                    maalThreshold: 85000000, // Standard nisab gold annual
                    calculatedAmount: 0,
                    showResult: false,
                    calculate() {
                        if (this.zakatType === 'zakat_pendapatan') {
                            let totalIncome = (parseFloat(this.income) || 0) + (parseFloat(this.otherIncome) || 0);
                            if (totalIncome >= this.threshold) {
                                this.calculatedAmount = totalIncome * 0.025;
                            } else {
                                this.calculatedAmount = 0;
                            }
                        } else {
                            let totalWealth = parseFloat(this.wealth) || 0;
                            if (totalWealth >= this.maalThreshold) {
                                this.calculatedAmount = totalWealth * 0.025;
                            } else {
                                this.calculatedAmount = 0;
                            }
                        }
                        this.showResult = true;
                    }
                }" class="bg-white border border-slate-100 shadow-sm rounded-3xl p-6 sm:p-8 space-y-6">
                    
                    <div class="space-y-1">
                        <span class="text-[9px] font-black uppercase tracking-[0.2em] text-primary-600 block">Kalkulator Zakat</span>
                        <h3 class="text-base sm:text-lg font-montserrat font-black text-slate-900 uppercase tracking-tight italic">
                            Hitung <span class="text-primary-600">Zakat Maal & Pendapatan</span>
                        </h3>
                    </div>
                    <hr class="border-slate-100">

                    <!-- Select Zakat Type -->
                    <div class="grid grid-cols-2 gap-3">
                        <button @click="zakatType = 'zakat_pendapatan'; showResult = false" :class="zakatType === 'zakat_pendapatan' ? 'bg-primary-600 text-white shadow-lg shadow-primary-600/10' : 'bg-slate-50 text-slate-550 border border-slate-100 hover:bg-slate-100'" class="py-3 rounded-2xl text-[10px] font-black uppercase tracking-wider transition-all">
                            Zakat Pendapatan
                        </button>
                        <button @click="zakatType = 'zakat_maal'; showResult = false" :class="zakatType === 'zakat_maal' ? 'bg-primary-600 text-white shadow-lg shadow-primary-600/10' : 'bg-slate-50 text-slate-550 border border-slate-100 hover:bg-slate-100'" class="py-3 rounded-2xl text-[10px] font-black uppercase tracking-wider transition-all">
                            Zakat Maal
                        </button>
                    </div>

                    <!-- Form Fields -->
                    <div class="space-y-4">
                        <template x-if="zakatType === 'zakat_pendapatan'">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-[9px] font-black uppercase tracking-wider text-slate-450 mb-1.5 ml-1">Pendapatan Bulanan (Rupiah)</label>
                                    <input type="number" x-model="income" placeholder="Masukkan gaji bulanan" class="w-full bg-slate-50 border border-slate-150 focus:border-primary-500 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none transition-all focus:ring-4 focus:ring-primary-500/10">
                                </div>
                                <div>
                                    <label class="block text-[9px] font-black uppercase tracking-wider text-slate-450 mb-1.5 ml-1">Pendapatan Lain-lain (Rupiah)</label>
                                    <input type="number" x-model="otherIncome" placeholder="Masukkan pendapatan sampingan jika ada" class="w-full bg-slate-50 border border-slate-150 focus:border-primary-500 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none transition-all focus:ring-4 focus:ring-primary-500/10">
                                </div>
                            </div>
                        </template>

                        <template x-if="zakatType === 'zakat_maal'">
                            <div>
                                <label class="block text-[9px] font-black uppercase tracking-wider text-slate-450 mb-1.5 ml-1">Total Nilai Tabungan/Aset Setahun (Rupiah)</label>
                                <input type="number" x-model="wealth" placeholder="Masukkan total tabungan / nilai emas" class="w-full bg-slate-50 border border-slate-150 focus:border-primary-500 rounded-2xl px-5 py-4 text-xs font-bold text-slate-800 outline-none transition-all focus:ring-4 focus:ring-primary-500/10">
                            </div>
                        </template>

                        <button @click="calculate()" class="w-full bg-slate-900 hover:bg-slate-850 text-white font-black text-xs uppercase tracking-wider py-4.5 rounded-2xl transition duration-300">
                            Hitung Zakat <i class="fa-solid fa-calculator ml-2"></i>
                        </button>
                    </div>

                    <!-- Result Box -->
                    <div x-show="showResult" class="bg-slate-50 border border-slate-100 rounded-3xl p-6 space-y-4">
                        <h4 class="font-montserrat font-black uppercase text-xs text-slate-800 tracking-wider text-center">Hasil Perhitungan</h4>
                        
                        <div class="text-center space-y-2">
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Zakat Wajib Dikeluarkan</span>
                            <h3 class="text-2xl sm:text-3xl font-montserrat font-black text-primary-600 leading-none">
                                Rp <span x-text="Math.round(calculatedAmount).toLocaleString('id-ID')"></span>
                            </h3>
                        </div>

                        <template x-if="calculatedAmount === 0">
                            <p class="text-[9px] text-rose-500 font-bold uppercase tracking-wider text-center">
                                *Pendapatan/Harta belum mencapai batas nisab. Anda belum wajib zakat.
                            </p>
                        </template>

                        <template x-if="calculatedAmount > 0">
                            <div class="space-y-4 pt-4 border-t border-slate-200">
                                @auth
                                    <form action="{{ route('zakat.submit') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="zakat_type" :value="zakatType === 'zakat_pendapatan' ? 'Zakat Pendapatan' : 'Zakat Maal'">
                                        <input type="hidden" name="amount" :value="calculatedAmount">
                                        <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-black text-xs uppercase tracking-wider py-4 rounded-2xl shadow-lg shadow-primary-600/10 transition duration-300">
                                            Bayar Zakat Sekarang <i class="fa-solid fa-handshake ml-2"></i>
                                        </button>
                                    </form>
                                @else
                                    <button @click="loginOpen = true" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-black text-xs uppercase tracking-wider py-4.5 rounded-2xl shadow-lg shadow-primary-600/10 text-center block transition duration-300">
                                        Login Untuk Bayar Zakat <i class="fa-solid fa-right-to-bracket ml-2"></i>
                                    </button>
                                @endauth
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Zakat History / Rules (Right/Bottom) -->
                <div class="space-y-8">
                    @auth
                        <!-- Zakat History -->
                        <div class="bg-white border border-slate-100 p-6 sm:p-8 rounded-3xl space-y-6 shadow-sm">
                            <h3 class="font-montserrat font-black uppercase text-xs sm:text-sm text-slate-800 tracking-wider">Riwayat Pembayaran Zakat</h3>
                            <hr class="border-slate-100">
                            <div class="space-y-4 max-h-[400px] overflow-y-auto no-scrollbar">
                                @forelse($zakats as $z)
                                    <div class="flex items-center justify-between border-b border-slate-50 pb-4 last:border-0 last:pb-0">
                                        <div>
                                            <span class="text-[10px] font-black uppercase text-slate-800 tracking-wide block leading-none">{{ $z->zakat_type }}</span>
                                            <span class="text-[8px] text-slate-400 font-bold uppercase tracking-wider block mt-2">{{ $z->created_at->format('d M Y') }}</span>
                                        </div>
                                        <div class="text-right space-y-1.5">
                                            <span class="text-xs font-black text-slate-800 block leading-none">Rp {{ number_format($z->amount, 0, ',', '.') }}</span>
                                            @if($z->payment_status === 'Success')
                                                <span class="bg-emerald-50 text-emerald-700 text-[8px] font-black uppercase tracking-widest px-2.5 py-0.5 rounded-md">SUCCESS</span>
                                            @elseif($z->payment_status === 'Pending')
                                                <span class="bg-amber-50 text-amber-700 text-[8px] font-black uppercase tracking-widest px-2.5 py-0.5 rounded-md">PENDING</span>
                                            @else
                                                <span class="bg-rose-50 text-rose-700 text-[8px] font-black uppercase tracking-widest px-2.5 py-0.5 rounded-md">FAILED</span>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider text-center py-4">Belum ada riwayat pembayaran zakat.</p>
                                @endforelse
                            </div>
                        </div>
                    @else
                        <!-- Zakat Guidelines -->
                        <div class="bg-white border border-slate-100 p-6 sm:p-8 rounded-3xl space-y-6 shadow-sm">
                            <h3 class="font-montserrat font-black uppercase text-xs sm:text-sm text-slate-800 tracking-wider">Ketentuan Zakat</h3>
                            <hr class="border-slate-100">
                            <div class="space-y-4 text-xs font-semibold text-slate-600 leading-relaxed">
                                <div>
                                    <h4 class="font-montserrat font-black uppercase text-[10px] text-slate-800 tracking-wider">Zakat Pendapatan (Penghasilan)</h4>
                                    <p class="text-[11px] mt-1 text-slate-500">Zakat penghasilan dikeluarkan setiap bulan jika total pendapatan bulanan mencapai nisab setara 85 gram emas pertahun (threshold sekitar Rp 6.859.394 per bulan).</p>
                                </div>
                                <div>
                                    <h4 class="font-montserrat font-black uppercase text-[10px] text-slate-800 tracking-wider">Zakat Maal (Harta)</h4>
                                    <p class="text-[11px] mt-1 text-slate-500">Zakat maal dikeluarkan dari kepemilikan emas, perak, tabungan pribadi yang mengendap selama 1 tahun (haul) dan mencapai nisab senilai 85 gram emas.</p>
                                </div>
                            </div>
                        </div>
                    @endauth
                </div>
            </div>

        </div>

    </div>
</section>
@endsection
