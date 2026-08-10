<template>
  <div class="ml-64 p-8 max-w-[1400px] mx-auto space-y-8">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
      <div>
        <h2 class="text-2xl font-extrabold text-[#0b1c30] tracking-tight">Tableau de bord de recensement national</h2>
        <p class="text-sm text-slate-500 mt-1">Aperçu général des données d'état civil, d'enrôlement et de modération.</p>
      </div>

      <div class="flex items-center space-x-3">
        <button 
          @click="$emit('navigate', 'recensement')"
          class="bg-[#ff6b00] hover:bg-[#e05e00] text-white font-bold px-4 py-2.5 rounded-xl text-xs shadow-md transition-all flex items-center space-x-2 active:scale-95"
        >
          <span class="material-symbols-outlined text-base">person_add</span>
          <span>Enrôler un citoyen</span>
        </button>

        <button 
          @click="loadStats"
          class="bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-semibold px-3 py-2.5 rounded-xl text-xs shadow-xs transition-all flex items-center space-x-1"
        >
          <span class="material-symbols-outlined text-base text-[#006d40]">refresh</span>
          <span>Actualiser</span>
        </button>
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      <div class="bg-white p-5 rounded-2xl shadow-level-1 hover:shadow-level-2 transition-all border border-slate-100 flex flex-col justify-between">
        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Citoyens Enrôlés</p>
        <div class="flex items-end justify-between mt-3">
          <p class="text-3xl font-black text-slate-900 font-mono leading-none">{{ stats.total_ressortissants || '1,248' }}</p>
          <div class="flex items-center gap-1 bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded-full text-xs font-bold font-mono">
            <span class="material-symbols-outlined text-sm">trending_up</span>
            +2.5%
          </div>
        </div>
      </div>

      <div class="bg-white p-5 rounded-2xl shadow-level-1 hover:shadow-level-2 transition-all border border-slate-100 flex flex-col justify-between">
        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Fiches Validées</p>
        <div class="flex items-end justify-between mt-3">
          <p class="text-3xl font-black text-[#006d40] font-mono leading-none">{{ stats.valides || '1,080' }}</p>
          <div class="flex items-center gap-1 bg-emerald-50 text-emerald-700 px-2 py-0.5 rounded-full text-xs font-bold font-mono">
            <span class="material-symbols-outlined text-sm">check_circle</span>
            Conforme
          </div>
        </div>
      </div>

      <div class="bg-white p-5 rounded-2xl shadow-level-1 hover:shadow-level-2 transition-all border border-slate-100 flex flex-col justify-between">
        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Sous-Préfectures ANStat</p>
        <div class="flex items-end justify-between mt-3">
          <p class="text-3xl font-black text-slate-900 font-mono leading-none">526</p>
          <div class="flex items-center gap-1 bg-orange-100 text-orange-800 px-2 py-0.5 rounded-full text-xs font-bold font-mono">
            <span class="material-symbols-outlined text-sm">horizontal_rule</span>
            100% Sync
          </div>
        </div>
      </div>

      <div class="bg-white p-5 rounded-2xl shadow-level-1 hover:shadow-level-2 transition-all border border-slate-100 flex flex-col justify-between">
        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Taux de Couverture</p>
        <div class="mt-3 space-y-2">
          <div class="flex items-end justify-between">
            <p class="text-2xl font-black text-slate-900 font-mono">86.5%</p>
            <span class="text-xs text-slate-400 font-medium">Objectif national</span>
          </div>
          <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
            <div class="bg-[#006d40] h-full rounded-full transition-all duration-500" style="width: 86.5%"></div>
          </div>
        </div>
      </div>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-level-1 border border-slate-100 space-y-4">
      <div class="flex justify-between items-center border-b border-slate-100 pb-4">
        <div>
          <h3 class="text-base font-bold text-slate-900">Tendance d'enrôlement mensuel</h3>
          <p class="text-xs text-slate-500">Flux d'enregistrement des déclarations sur l'ensemble du territoire et de la Diaspora.</p>
        </div>
        <select class="bg-slate-50 text-xs font-semibold border border-slate-200 rounded-xl px-3 py-1.5 focus:outline-none focus:border-[#ff6b00]">
          <option>12 Derniers Mois</option>
          <option>Cette Année 2026</option>
        </select>
      </div>

      <div class="w-full h-56 bg-slate-50 rounded-xl border border-slate-200 flex flex-col items-center justify-center relative overflow-hidden p-6 text-center">
        <div class="flex items-end justify-between w-full h-full max-w-2xl px-8 pb-4">
          <div class="flex flex-col items-center gap-1"><div class="w-8 bg-orange-300 rounded-t-lg h-24"></div><span class="text-[10px] text-slate-500 font-mono">Jan</span></div>
          <div class="flex flex-col items-center gap-1"><div class="w-8 bg-orange-400 rounded-t-lg h-32"></div><span class="text-[10px] text-slate-500 font-mono">Fév</span></div>
          <div class="flex flex-col items-center gap-1"><div class="w-8 bg-[#ff6b00] rounded-t-lg h-40"></div><span class="text-[10px] text-slate-500 font-mono">Mar</span></div>
          <div class="flex flex-col items-center gap-1"><div class="w-8 bg-[#006d40] rounded-t-lg h-48"></div><span class="text-[10px] text-slate-500 font-mono">Avr</span></div>
          <div class="flex flex-col items-center gap-1"><div class="w-8 bg-[#006d40] rounded-t-lg h-36"></div><span class="text-[10px] text-slate-500 font-mono">Mai</span></div>
          <div class="flex flex-col items-center gap-1"><div class="w-8 bg-[#ff6b00] rounded-t-lg h-44"></div><span class="text-[10px] text-slate-500 font-mono">Juin</span></div>
        </div>
        <span class="text-xs font-bold text-slate-600 bg-white/80 backdrop-blur-xs px-3 py-1 rounded-full border border-slate-200 shadow-xs absolute">
          Volume de recensement en forte progression (+18.4% ce mois-ci)
        </span>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

defineEmits(['navigate']);

const stats = ref({
  total_ressortissants: 1248,
  valides: 1080,
  en_attente: 168,
  diaspora: 215
});

const loadStats = async () => {
  try {
    const res = await axios.get('/api/stats/globales');
    if (res.data) {
      stats.value = res.data;
    }
  } catch (err) {
    console.warn('Stats fallback:', err);
  }
};

onMounted(() => {
  loadStats();
});
</script>
