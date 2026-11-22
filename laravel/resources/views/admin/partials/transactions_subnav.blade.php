<nav aria-label="Navigasi Manajemen Transaksi" class="inline-flex flex-wrap items-center p-1.5 bg-slate-100/90 border border-slate-200/80 rounded-2xl gap-1 max-w-full">
    <a href="{{ route('admin.donors_list') }}" 
       class="px-3.5 py-2 min-h-[40px] inline-flex items-center gap-1.5 rounded-xl text-xs font-bold transition-all {{ ($active ?? '') === 'donors_list' ? 'bg-[#063f5c] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-200/60' }}">
        <i class="fa-solid fa-users text-xs"></i>
        <span>Donatur</span>
    </a>
    <a href="{{ route('admin.transactions_list') }}" 
       class="px-3.5 py-2 min-h-[40px] inline-flex items-center gap-1.5 rounded-xl text-xs font-bold transition-all {{ ($active ?? '') === 'transactions_list' ? 'bg-[#063f5c] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-200/60' }}">
        <i class="fa-solid fa-receipt text-xs"></i>
        <span>Transaksi</span>
    </a>
    <a href="{{ route('admin.prospects_list') }}" 
       class="px-3.5 py-2 min-h-[40px] inline-flex items-center gap-1.5 rounded-xl text-xs font-bold transition-all {{ ($active ?? '') === 'prospects_list' ? 'bg-[#063f5c] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-200/60' }}">
        <i class="fa-solid fa-user-gear text-xs"></i>
        <span>Prospek</span>
    </a>
    <a href="{{ route('admin.potential_donors') }}" 
       class="px-3.5 py-2 min-h-[40px] inline-flex items-center gap-1.5 rounded-xl text-xs font-bold transition-all {{ ($active ?? '') === 'potential_donors' ? 'bg-[#063f5c] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-200/60' }}">
        <i class="fa-solid fa-address-book text-xs"></i>
        <span>Calon Donatur</span>
    </a>
    <a href="{{ route('admin.transactions_dashboard') }}" 
       class="px-3.5 py-2 min-h-[40px] inline-flex items-center gap-1.5 rounded-xl text-xs font-bold transition-all {{ ($active ?? '') === 'transactions_dashboard' ? 'bg-[#063f5c] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-200/60' }}">
        <i class="fa-solid fa-chart-line text-xs"></i>
        <span>Dashboard Transaksi</span>
    </a>
    <a href="{{ route('admin.donors_dashboard') }}" 
       class="px-3.5 py-2 min-h-[40px] inline-flex items-center gap-1.5 rounded-xl text-xs font-bold transition-all {{ ($active ?? '') === 'donors_dashboard' ? 'bg-[#063f5c] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-200/60' }}">
        <i class="fa-solid fa-users-gear text-xs"></i>
        <span>Dashboard Donatur</span>
    </a>
    <a href="{{ route('admin.prospects_dashboard') }}" 
       class="px-3.5 py-2 min-h-[40px] inline-flex items-center gap-1.5 rounded-xl text-xs font-bold transition-all {{ ($active ?? '') === 'prospects_dashboard' ? 'bg-[#063f5c] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-200/60' }}">
        <i class="fa-solid fa-bullhorn text-xs"></i>
        <span>Dashboard Prospek</span>
    </a>
</nav>
