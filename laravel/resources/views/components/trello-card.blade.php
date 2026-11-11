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
     class="trello-card bg-white rounded-2xl p-4 border border-slate-200 shadow-xs hover:shadow-md transition-colors cursor-grab active:cursor-grabbing space-y-3 group select-none relative hover:border-primary-400">
    
    <!-- Card Top Header: Status Badge & Indicators -->
    <div class="flex items-center justify-between gap-2">
        <div>
            @if($isCompleted)
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md text-xs font-bold bg-slate-50 text-slate-800 border border-slate-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-700 shrink-0" aria-hidden="true"></span> Selesai
                </span>
            @elseif($isOverdue)
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md text-xs font-bold bg-slate-50 text-slate-800 border border-slate-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-rose-700 shrink-0" aria-hidden="true"></span> Terlambat
                </span>
            @elseif($isDueSoon)
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md text-xs font-bold bg-slate-50 text-slate-800 border border-slate-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#f7ad1a] shrink-0" aria-hidden="true"></span> Hari Ini
                </span>
            @elseif($todo->deadline)
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md text-xs font-semibold text-slate-700 bg-slate-50 border border-slate-200">
                    <i class="fa-regular fa-calendar text-xs text-slate-500" aria-hidden="true"></i> {{ $todo->deadline->format('d M') }}
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md text-xs font-semibold text-slate-700 bg-slate-50 border border-slate-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400 shrink-0" aria-hidden="true"></span> Tugas
                </span>
            @endif
        </div>

        @if($todo->result_attachment || $todo->result_note)
            <div class="flex items-center gap-2 shrink-0">
                @if($todo->result_attachment)
                    <span class="text-emerald-700 inline-flex items-center gap-1" title="Ada Foto Bukti">
                        <i class="fa-solid fa-paperclip text-xs" aria-hidden="true"></i>
                        <span class="sr-only">Ada Foto Bukti</span>
                    </span>
                @endif

                @if($todo->result_note)
                    <span class="text-slate-600 inline-flex items-center gap-1" title="Ada Catatan Hasil">
                        <i class="fa-regular fa-comment-dots text-xs" aria-hidden="true"></i>
                        <span class="sr-only">Ada Catatan Hasil</span>
                    </span>
                @endif
            </div>
        @endif
    </div>

    <!-- Task Title & Description -->
    <div class="space-y-1">
        <h3 class="text-sm font-bold text-slate-900 group-hover:text-primary-700 transition-colors leading-snug break-words {{ $isCompleted ? 'line-through text-slate-600' : '' }}">
            {{ $todo->task }}
        </h3>
        @if($todo->description)
            <p class="text-xs text-slate-600 leading-relaxed font-normal break-words">
                {{ $todo->description }}
            </p>
        @endif
    </div>

    <!-- Card Footer: Full Assignee & Assigner -->
    <div class="pt-2.5 border-t border-slate-200 space-y-1 text-xs">
        @if($todo->user)
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 rounded-lg bg-[#063f5c] text-white font-black text-xs flex items-center justify-center uppercase shrink-0" aria-hidden="true">
                    {{ strtoupper(substr($todo->user->name, 0, 1)) }}
                </div>
                <span class="font-bold text-slate-800 leading-snug break-words">{{ $todo->user->name }}</span>
            </div>
        @endif
        @if($todo->assigner)
            <p class="text-xs text-slate-600 font-medium leading-snug break-words {{ $todo->user ? 'pl-8' : '' }}">
                Oleh: {{ $todo->assigner->name }}
            </p>
        @endif
    </div>

</div>

