@props(['todo'])

@php
    $isCompleted = $todo->status === 'completed';
    $isOverdue = !$isCompleted && $todo->deadline && $todo->deadline->isPast();
    $isDueSoon = !$isCompleted && $todo->deadline && $todo->deadline->isToday();
@endphp

<div data-id="{{ $todo->id }}" 
     data-assignee-id="{{ $todo->user_id }}"
     role="button"
     tabindex="0"
     @click="openDetailModal({{ $todo->toJson() }})"
     @keydown.enter.prevent="openDetailModal({{ $todo->toJson() }})"
     @keydown.space.prevent="openDetailModal({{ $todo->toJson() }})"
     class="trello-card bg-white rounded-2xl p-4 border border-slate-200/90 shadow-xs hover:shadow-md transition-all cursor-grab active:cursor-grabbing space-y-3 group select-none relative hover:border-primary-300">
    
    <!-- Card Top Header / Tags -->
    <div class="flex items-center justify-between gap-2">
        
        <!-- Priority / Deadline Pill -->
        <div>
            @if($isCompleted)
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-black uppercase tracking-wider bg-emerald-50 text-emerald-800 border border-emerald-200">
                    <i class="fa-solid fa-check text-xs"></i> Selesai
                </span>
            @elseif($isOverdue)
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-black uppercase tracking-wider bg-red-50 text-red-700 border border-red-200">
                    <i class="fa-solid fa-clock text-xs"></i> Terlambat
                </span>
            @elseif($isDueSoon)
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-black uppercase tracking-wider bg-amber-50 text-amber-800 border border-amber-200">
                    <i class="fa-solid fa-hourglass-half text-xs"></i> Hari Ini
                </span>
            @elseif($todo->deadline)
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold text-slate-600 bg-slate-100 border border-slate-200">
                    <i class="fa-regular fa-calendar text-xs"></i> {{ $todo->deadline->format('d M') }}
                </span>
            @else
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold text-slate-600 bg-slate-50 border border-slate-200">
                    Tugas
                </span>
            @endif
        </div>

        <!-- Assignee Tag -->
        @if($todo->user)
            <span class="text-xs font-bold text-indigo-800 bg-indigo-50 border border-indigo-200 px-2 py-0.5 rounded-lg truncate max-w-[120px]" title="Petugas: {{ $todo->user->name }}">
                {{ $todo->user->name }}
            </span>
        @endif
    </div>

    <!-- Task Title -->
    <div>
        <h3 class="text-xs sm:text-sm font-bold text-slate-900 group-hover:text-primary-700 transition-colors leading-snug line-clamp-2 {{ $isCompleted ? 'line-through text-slate-600' : '' }}">
            {{ $todo->task }}
        </h3>
        @if($todo->description)
            <p class="text-xs text-slate-600 line-clamp-2 mt-1 leading-relaxed font-normal">
                {{ $todo->description }}
            </p>
        @endif
    </div>

    <!-- Card Footer -->
    <div class="flex items-center justify-between pt-2 border-t border-slate-100 text-xs text-slate-600 font-semibold">
        
        <!-- Left: Assignee Avatar & Assigner info -->
        <div class="flex items-center gap-1.5">
            @if($todo->user)
                <div class="w-6 h-6 rounded-full bg-primary-100 text-primary-700 font-black text-xs flex items-center justify-center uppercase shrink-0">
                    {{ substr($todo->user->name, 0, 1) }}
                </div>
            @endif
            @if($todo->assigner)
                <span class="text-xs text-slate-600 truncate max-w-[100px]">
                    Oleh: {{ $todo->assigner->name }}
                </span>
            @endif
        </div>

        <!-- Right: Badges (Attachments, Notes, Detail icon) -->
        <div class="flex items-center gap-2">
            @if($todo->result_attachment)
                <span class="text-emerald-700 flex items-center gap-1" title="Ada Foto Bukti">
                    <i class="fa-solid fa-paperclip text-xs"></i>
                </span>
            @endif

            @if($todo->result_note)
                <span class="text-slate-600 flex items-center gap-1" title="Ada Catatan Hasil">
                    <i class="fa-regular fa-comment-dots text-xs"></i>
                </span>
            @endif

            <span class="opacity-0 group-hover:opacity-100 transition-opacity text-primary-600 text-xs" title="Lihat Detail">
                <i class="fa-solid fa-ellipsis-vertical"></i>
            </span>
        </div>

    </div>

</div>

