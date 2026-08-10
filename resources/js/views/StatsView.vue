<template>
  <div class="ml-64 p-8 max-w-[1400px] mx-auto space-y-8">
    <div class="bg-white p-6 rounded-2xl shadow-level-1 border border-slate-100 flex items-center justify-between">
      <div>
        <div class="flex items-center space-x-2">
          <span class="bg-emerald-100 text-[#006d40] text-xs font-extrabold px-2.5 py-0.5 rounded-full uppercase tracking-wider">Rapport Cartographique</span>
          <span class="text-xs text-slate-400 font-mono">• Source API ANStat</span>
        </div>
        <h1 class="text-2xl font-extrabold text-[#0b1c30] mt-1 tracking-tight">Statistiques Globales &amp; Couverture Territoriale</h1>
        <p class="text-slate-500 text-xs mt-0.5">Analyse cartographique des 526 sous-préfectures et des ressortissants de la Diaspora.</p>
      </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
      <div class="bg-white p-6 rounded-2xl shadow-level-1 border border-slate-100 space-y-2">
        <span class="text-xs font-bold uppercase text-slate-400 tracking-wider">Couverture Administrative</span>
        <div class="text-3xl font-black text-slate-900 font-mono">526 / 526</div>
        <p class="text-xs text-slate-500">Sous-préfectures ANStat synchronisées.</p>
      </div>

      <div class="bg-white p-6 rounded-2xl shadow-level-1 border border-slate-100 space-y-2">
        <span class="text-xs font-bold uppercase text-slate-400 tracking-wider">Référentiel des Pays</span>
        <div class="text-3xl font-black text-[#ff6b00] font-mono">193 Pays</div>
        <p class="text-xs text-slate-500">Nations membres de l'ONU pour la Diaspora.</p>
      </div>

      <div class="bg-white p-6 rounded-2xl shadow-level-1 border border-slate-100 space-y-2">
        <span class="text-xs font-bold uppercase text-slate-400 tracking-wider">Taux de Modération</span>
        <div class="text-3xl font-black text-[#006d40] font-mono">86.5 %</div>
        <p class="text-xs text-slate-500">Proportion des déclarations citoyennes validées.</p>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
      <div class="bg-white p-6 rounded-2xl shadow-level-1 border border-slate-100 space-y-4">
        <h3 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3 flex items-center justify-between">
          <span>Découpage ANStat par District</span>
          <span class="text-xs bg-orange-100 text-[#ff6b00] px-2.5 py-0.5 rounded font-mono font-bold">14 Districts</span>
        </h3>

        <div class="space-y-3 text-xs">
          <div class="flex justify-between items-center p-3 bg-slate-50 rounded-xl">
            <span class="font-bold text-slate-800">District Autonome d'Abidjan</span>
            <span class="font-mono text-slate-700 font-bold">412 Recensés</span>
          </div>
          <div class="flex justify-between items-center p-3 bg-slate-50 rounded-xl">
            <span class="font-bold text-slate-800">District des Lacs (Yamoussoukro / Toumodi)</span>
            <span class="font-mono text-slate-700 font-bold">245 Recensés</span>
          </div>
          <div class="flex justify-between items-center p-3 bg-slate-50 rounded-xl">
            <span class="font-bold text-slate-800">District des Grands Ponts (Dabou / Jacqueville)</span>
            <span class="font-mono text-slate-700 font-bold">188 Recensés</span>
          </div>
          <div class="flex justify-between items-center p-3 bg-slate-50 rounded-xl">
            <span class="font-bold text-slate-800">District des Savanes (Korhogo / Ferké)</span>
            <span class="font-mono text-slate-700 font-bold">156 Recensés</span>
          </div>
        </div>
      </div>

      <div class="bg-white p-6 rounded-2xl shadow-level-1 border border-slate-100 space-y-4">
        <h3 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3 flex items-center justify-between">
          <span>Répartition de la Diaspora</span>
          <span class="text-xs bg-emerald-100 text-[#006d40] px-2.5 py-0.5 rounded font-mono font-bold">International</span>
        </h3>

        <div class="space-y-3 text-xs">
          <div class="flex justify-between items-center p-3 bg-slate-50 rounded-xl">
            <span class="font-bold text-slate-800">France (Europe)</span>
            <span class="font-mono text-[#006d40] font-bold">112 Ressortissants</span>
          </div>
          <div class="flex justify-between items-center p-3 bg-slate-50 rounded-xl">
            <span class="font-bold text-slate-800">Canada (Amérique du Nord)</span>
            <span class="font-mono text-[#006d40] font-bold">48 Ressortissants</span>
          </div>
          <div class="flex justify-between items-center p-3 bg-slate-50 rounded-xl">
            <span class="font-bold text-slate-800">États-Unis (Amérique du Nord)</span>
            <span class="font-mono text-[#006d40] font-bold">32 Ressortissants</span>
          </div>
          <div class="flex justify-between items-center p-3 bg-slate-50 rounded-xl">
            <span class="font-bold text-slate-800">Autres Pays Internationaux</span>
            <span class="font-mono text-[#006d40] font-bold">23 Ressortissants</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const stats = ref(null);

const loadStats = async () => {
  try {
    const res = await axios.get('/api/stats/globales');
    stats.value = res.data;
  } catch (err) {
    console.warn('Fallback stats:', err);
  }
};

onMounted(() => {
  loadStats();
});
</script>
