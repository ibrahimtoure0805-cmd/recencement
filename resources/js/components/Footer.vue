<template>
  <footer class="ml-64 bg-slate-900 text-slate-400 text-xs mt-auto border-t border-slate-800 py-8 px-8">
    <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-4">
      <div class="flex items-center space-x-3">
        <span class="font-bold text-white tracking-wide">RÉPUBLIQUE DE CÔTE D'IVOIRE</span>
        <span class="text-slate-600">•</span>
        <span class="text-slate-400">Portail National de Recensement &amp; État Civil</span>
      </div>

      <div class="flex items-center space-x-6 text-slate-400">
        <a href="#" @click.prevent="$emit('navigate', 'home')" class="hover:text-white transition-colors">Accueil</a>
        <a href="#" @click.prevent="$emit('navigate', 'recensement')" class="hover:text-white transition-colors">Déclaration Citoyenne</a>
        <a href="#" @click.prevent="$emit('navigate', 'moderation')" class="hover:text-white transition-colors">Console Modération</a>
        <a href="#" @click.prevent="$emit('navigate', 'stats')" class="hover:text-white transition-colors">Rapports ANStat</a>
      </div>

      <div class="text-slate-500 font-mono text-[11px]">
        © 2026 Ministère du Plan • ESATIC / Worldev
      </div>
    </div>
  </footer>
</template>

<script setup>
// Ce code sert à déclarer l'événement de navigation du pied de page.
// Il fonctionne avec le compilateur de composant script setup de Vue 3.
// Dans le but de réémettre l'événement 'navigate' vers le composant parent.
// Pour régler la réoriention vers les différentes vues depuis le pied de page.
defineEmits(['navigate']);
</script>
