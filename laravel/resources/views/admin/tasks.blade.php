@extends('layouts.app')

@section('content')
<section class="max-w-7xl mx-auto px-6 py-12">
    
    <!-- Alerts -->
    @if(session('success'))
        <div role="status" class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl font-semibold text-xs flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-3">
                <i class="fa-solid fa-circle-check text-emerald-700 text-base"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" @click="$el.parentElement.remove()" aria-label="Tutup notifikasi" class="w-11 h-11 -my-2 inline-flex items-center justify-center rounded-xl text-emerald-700 hover:bg-emerald-100">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div role="alert" class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl font-semibold text-xs flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-3">
                <i class="fa-solid fa-triangle-exclamation text-rose-700 text-base"></i>
                <span>{{ session('error') }}</span>
            </div>
            <button type="button" @click="$el.parentElement.remove()" aria-label="Tutup notifikasi" class="w-11 h-11 -my-2 inline-flex items-center justify-center rounded-xl text-rose-700 hover:bg-rose-100">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- Sidebar Menu -->
        <div class="hidden lg:block lg:col-span-1 space-y-6">
            @include('admin.sidebar', ['active' => 'tasks'])
        </div>

        <!-- Dashboard Content -->
        <div class="lg:col-span-3 space-y-6">
            
            {{-- Header Page Title & Export Excel Button --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="space-y-1">
                    <h1 class="text-2xl sm:text-3xl font-montserrat font-black text-slate-900 uppercase tracking-tight italic">
                        Manajemen <span class="text-primary-600">Tugas & To-Do List</span>
                    </h1>
                    <div class="w-12 h-1 bg-primary-500 rounded-full"></div>
                </div>

                {{-- Export Button (Excel/CSV) --}}
                <a href="{{ route('admin.tasks.export', request()->all()) }}" class="px-5 py-2.5 min-h-[44px] bg-emerald-700 hover:bg-emerald-800 text-white rounded-xl text-xs font-black uppercase tracking-wider inline-flex items-center justify-center gap-2 shadow-sm transition-colors">
                    <i class="fa-solid fa-file-excel text-sm"></i> Export Excel
                </a>
            </div>

            <!-- Trello Kanban Board Component -->
            <x-trello-board 
                :todos="$allTodos" 
                :workers="$workers" 
                :canAssign="true" 
                :storeRoute="route('admin.tasks.store')" 
                :paginatedTodos="$todos" 
                :tableRoute="route('admin.tasks.export', request()->all())" 
                :showViewToggle="true" 
            />

        </div>

    </div>
</section>
@endsection
