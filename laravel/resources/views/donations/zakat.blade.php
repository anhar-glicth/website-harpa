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
                }" class="bg-white border border-slate-200 shadow-sm rounded-3xl p-6 sm:p-8 space-y-6">
                    
                    <div class="space-y-1">
                        <span class="text-xs font-black uppercase tracking-[0.2em] text-primary-700 block">Kalkulator Zakat</span>
                        <h2 class="text-base sm:text-lg font-montserrat font-black text-slate-900 uppercase tracking-tight italic">
                            Hitung <span class="text-primary-700">Zakat Maal &amp; Pendapatan</span>
                        </h2>
                    </div>
                    <hr class="border-slate-200">

                    <!-- Select Zakat Type -->
                    <div class="grid grid-cols-2 gap-3">
                        <button type="button" @click="zakatType = 'zakat_pendapatan'; showResult = false" :aria-pressed="zakatType === 'zakat_pendapatan'" :class="zakatType === 'zakat_pendapatan' ? 'bg-primary-600 text-white shadow-lg shadow-primary-600/10' : 'bg-slate-50 text-slate-600 border border-slate-200 hover:bg-slate-100'" class="min-h-[44px] py-3 rounded-2xl text-xs font-black uppercase tracking-wider transition-all">
                            Zakat Pendapatan
                        </button>
                        <button type="button" @click="zakatType = 'zakat_maal'; showResult = false" :aria-pressed="zakatType === 'zakat_maal'" :class="zakatType === 'zakat_maal' ? 'bg-primary-600 text-white shadow-lg shadow-primary-600/10' : 'bg-slate-50 text-slate-600 border border-slate-200 hover:bg-slate-100'" class="min-h-[44px] py-3 rounded-2xl text-xs font-black uppercase tracking-wider transition-all">
                            Zakat Maal
                        </button>
                    </div>

                    <!-- Form Fields -->
                    <div class="space-y-4">
                        <template x-if="zakatType === 'zakat_pendapatan'">
                            <div class="space-y-4">
                                <div>
                                    <label for="zakat_income" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5 ml-1">Pendapatan Bulanan (Rupiah)</label>
                                    <input id="zakat_income" type="number" x-model="income" placeholder="Masukkan gaji bulanan" class="w-full min-h-[44px] bg-slate-50 border border-slate-300 focus:border-primary-600 rounded-2xl px-5 py-3.5 text-sm font-semibold text-slate-800 transition-all focus:ring-4 focus:ring-primary-500/10">
                                </div>
                                <div>
                                    <label for="zakat_other_income" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5 ml-1">Pendapatan Lain-lain (Rupiah)</label>
                                    <input id="zakat_other_income" type="number" x-model="otherIncome" placeholder="Masukkan pendapatan sampingan jika ada" class="w-full min-h-[44px] bg-slate-50 border border-slate-300 focus:border-primary-600 rounded-2xl px-5 py-3.5 text-sm font-semibold text-slate-800 transition-all focus:ring-4 focus:ring-primary-500/10">
                                </div>
                            </div>
                        </template>

                        <template x-if="zakatType === 'zakat_maal'">
                            <div>
                                <label for="zakat_wealth" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5 ml-1">Total Nilai Tabungan/Aset Setahun (Rupiah)</label>
                                <input id="zakat_wealth" type="number" x-model="wealth" placeholder="Masukkan total tabungan / nilai emas" class="w-full min-h-[44px] bg-slate-50 border border-slate-300 focus:border-primary-600 rounded-2xl px-5 py-3.5 text-sm font-semibold text-slate-800 transition-all focus:ring-4 focus:ring-primary-500/10">
                            </div>
                        </template>

                        <button type="button" @click="calculate()" class="w-full min-h-[48px] bg-slate-900 hover:bg-slate-800 text-white font-black text-xs uppercase tracking-wider py-4 rounded-2xl transition duration-300">
                            Hitung Zakat <i class="fa-solid fa-calculator ml-2" aria-hidden="true"></i>
                        </button>
                    </div>

                    <!-- Result Box -->
                    <div x-show="showResult" class="bg-slate-50 border border-slate-200 rounded-3xl p-6 space-y-4">
                        <h3 class="font-montserrat font-black uppercase text-xs text-slate-800 tracking-wider text-center">Hasil Perhitungan</h3>
                        
                        <div class="text-center space-y-2">
                            <span class="text-xs font-bold text-slate-600 uppercase tracking-widest">Zakat Wajib Dikeluarkan</span>
                            <p class="text-2xl sm:text-3xl font-montserrat font-black text-primary-700 leading-none">
                                Rp <span x-text="Math.round(calculatedAmount).toLocaleString('id-ID')"></span>
                            </p>
                        </div>

                        <template x-if="calculatedAmount === 0">
                            <p class="text-xs text-rose-700 font-bold uppercase tracking-wider text-center">
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
                                        <button type="submit" class="w-full min-h-[48px] bg-primary-600 hover:bg-primary-700 text-white font-black text-xs uppercase tracking-wider py-4 rounded-2xl shadow-lg shadow-primary-600/10 transition duration-300">
                                            Bayar Zakat Sekarang <i class="fa-solid fa-handshake ml-2" aria-hidden="true"></i>
                                        </button>
                                    </form>
                                @else
                                    <button type="button" @click="loginOpen = true" class="w-full min-h-[48px] bg-primary-600 hover:bg-primary-700 text-white font-black text-xs uppercase tracking-wider py-4 rounded-2xl shadow-lg shadow-primary-600/10 text-center block transition duration-300">
                                        Login Untuk Bayar Zakat <i class="fa-solid fa-right-to-bracket ml-2" aria-hidden="true"></i>
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
                        <div class="bg-white border border-slate-200 p-6 sm:p-8 rounded-3xl space-y-6 shadow-sm">
                            <h2 class="font-montserrat font-black uppercase text-xs sm:text-sm text-slate-800 tracking-wider">Riwayat Pembayaran Zakat</h2>
                            <hr class="border-slate-200">
                            <div class="space-y-4 max-h-[400px] overflow-y-auto no-scrollbar">
                                @forelse($zakats as $z)
                                    <div class="flex items-center justify-between border-b border-slate-100 pb-4 last:border-0 last:pb-0">
                                        <div>
                                            <span class="text-xs font-black uppercase text-slate-800 tracking-wide block leading-none">{{ $z->zakat_type }}</span>
                                            <span class="text-xs text-slate-600 font-bold uppercase tracking-wider block mt-2">{{ $z->created_at->format('d M Y') }}</span>
                                        </div>
                                        <div class="text-right space-y-1.5">
                                            <span class="text-xs font-black text-slate-800 block leading-none">Rp {{ number_format($z->amount, 0, ',', '.') }}</span>
                                            @if($z->payment_status === 'Success')
                                                <span class="inline-block whitespace-nowrap text-emerald-700 text-xs font-black uppercase tracking-wider">SUCCESS</span>
                                            @elseif($z->payment_status === 'Pending')
                                                <span class="inline-block whitespace-nowrap text-amber-800 text-xs font-black uppercase tracking-wider">PENDING</span>
                                            @else
                                                <span class="inline-block whitespace-nowrap text-rose-700 text-xs font-black uppercase tracking-wider">FAILED</span>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-xs text-slate-600 font-bold uppercase tracking-wider text-center py-4">Belum ada riwayat pembayaran zakat.</p>
                                @endforelse
                            </div>
                        </div>
                    @else
                        <!-- Zakat Guidelines -->
                        <div class="bg-white border border-slate-200 p-6 sm:p-8 rounded-3xl space-y-6 shadow-sm">
                            <h2 class="font-montserrat font-black uppercase text-xs sm:text-sm text-slate-800 tracking-wider">Ketentuan Zakat</h2>
                            <hr class="border-slate-200">
                            <div class="space-y-4 text-xs font-semibold text-slate-600 leading-relaxed">
                                <div>
                                    <h3 class="font-montserrat font-black uppercase text-xs text-slate-800 tracking-wider">Zakat Pendapatan (Penghasilan)</h3>
                                    <p class="text-xs mt-1 text-slate-600">Zakat penghasilan dikeluarkan setiap bulan jika total pendapatan bulanan mencapai nisab setara 85 gram emas pertahun (threshold sekitar Rp 6.859.394 per bulan).</p>
                                </div>
                                <div>
                                    <h3 class="font-montserrat font-black uppercase text-xs text-slate-800 tracking-wider">Zakat Maal (Harta)</h3>
                                    <p class="text-xs mt-1 text-slate-600">Zakat maal dikeluarkan dari kepemilikan emas, perak, tabungan pribadi yang mengendap selama 1 tahun (haul) dan mencapai nisab senilai 85 gram emas.</p>
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
