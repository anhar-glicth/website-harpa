<template>
  <div class="group bg-white rounded-[1.2rem] md:rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-200/20 hover:shadow-blue-100 hover:-translate-y-2 transition-all duration-500 overflow-hidden flex flex-col h-full">
    <!-- Image Section -->
    <div class="aspect-[4/3] bg-slate-100 relative overflow-hidden">
      <img 
        v-if="campaign.foto" 
        :src="campaign.foto" 
        :alt="campaign.title"
        class="w-full h-full object-cover transition duration-700 group-hover:scale-110"
      />
      <div v-else class="w-full h-full flex items-center justify-center text-slate-300 bg-slate-50">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 md:w-10 md:h-10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
      </div>
      
      <!-- Urgent Label -->
      <div v-if="percentage >= 80" class="absolute top-3 left-3 md:top-4 md:left-4 px-2 md:px-3 py-0.5 md:py-1 bg-red-500 text-white text-[7px] md:text-[8px] font-black uppercase tracking-widest rounded-full shadow-lg shadow-red-200 animate-pulse">
        Mendesak
      </div>

      <!-- Category Label -->
      <div class="absolute bottom-3 left-3 md:bottom-4 md:left-4 px-2 md:px-3 py-0.5 md:py-1 bg-white/20 backdrop-blur-xl border border-white/30 text-white text-[7px] md:text-[8px] font-black uppercase tracking-widest rounded-full">
        {{ campaign.category || 'Sosial' }}
      </div>
    </div>

    <!-- Info Section -->
    <div class="p-3 md:p-5 flex flex-col flex-grow space-y-2 md:space-y-3">
      <h3 class="font-black text-slate-900 text-xs md:text-sm lg:text-base leading-tight line-clamp-2 uppercase tracking-tighter italic transition-colors group-hover:text-blue-600">
        {{ campaign.title }}
      </h3>
      
      <p class="text-slate-400 text-[9px] md:text-[10px] font-bold leading-relaxed line-clamp-2 flex-grow opacity-80">
        {{ campaign.description }}
      </p>

      <!-- Progress Section -->
      <div class="space-y-1.5 md:space-y-2 pt-1 md:pt-2">
        <div class="flex justify-between items-end mb-1">
           <div class="space-y-0 md:space-y-0.5">
              <p class="text-[6px] md:text-[8px] font-black text-slate-400 uppercase tracking-widest">Terkumpul</p>
              <p class="text-[10px] md:text-xs lg:text-sm font-black text-blue-600 tracking-tighter italic leading-none">Rp {{ formatCurrency(campaign.current_amount) }}</p>
           </div>
           <p class="text-[8px] md:text-[10px] font-black text-slate-900 italic underline decoration-blue-200 md:decoration-2 decoration-1 underline-offset-4">{{ percentage }}%</p>
        </div>

        <div class="w-full bg-slate-50 h-1 md:h-2 rounded-full overflow-hidden border border-slate-100">
          <div 
            class="bg-gradient-to-r from-blue-600 to-blue-400 h-full rounded-full transition-all duration-1000"
            :style="{ width: Math.min(percentage, 100) + '%' }"
          ></div>
        </div>
      </div>

      <!-- Action Button -->
      <button 
        @click="$emit('select', campaign)"
        class="w-full mt-2 md:mt-3 py-2 md:py-3 bg-white border border-slate-100 text-slate-900 font-black uppercase tracking-widest text-[7px] md:text-[9px] rounded-lg md:rounded-xl group-hover:bg-blue-600 group-hover:text-white group-hover:border-blue-600 transition-all duration-300 active:scale-95 flex items-center justify-center gap-1.5 md:gap-2"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="w-2.5 h-2.5 md:w-3.5 h-3.5 transition-transform group-hover:rotate-12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
        <span>Donasi</span>
      </button>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  campaign: {
    type: Object,
    required: true
  }
});

defineEmits(['select']);

const percentage = computed(() => {
  if (!props.campaign.target_amount) return 0;
  return Math.round((props.campaign.current_amount / props.campaign.target_amount) * 100);
});

const formatCurrency = (value) => {
  return new Intl.NumberFormat('id-ID').format(value || 0);
};
</script>