<template>
  <div>
    <!-- SideNavBar (Desktop Fixed Left) -->
    <nav class="h-screen w-64 fixed left-0 top-0 bg-white border-r border-slate-200 shadow-sm z-50 flex flex-col p-4">
      <!-- Brand Header with Ivorian Coat of Arms -->
      <div @click="$emit('navigate', 'home')" class="flex items-center gap-3 mb-6 p-2 cursor-pointer group">
        <img 
          src="https://lh3.googleusercontent.com/aida-public/AB6AXuB1YWt2-OCMzMOK-PfBl76Pqe3d6cJ9uLC3RjgkxIw7Pvxmq94vBE4JXo-EeouZOJBBQi1GaZTwX7Il6kjCdJ8d6_eOYxX_Bnz_J0YuzeLDaDJZYg9M3dixBT_OhDjIDRdhc84eMAfJzt5Y6JJ6wVJ-lizuBwveO5Jv1P81_I81QEWiwuDbooHm6wdIttBOs2zcOKww-JNlIFrLbr16s73ZiNhPlj_DMFwjHKFgfGPMmKD35_A5G4EVEwYiYiBCvp-W-d8GOvuX8Mkb" 
          alt="Armoiries de Côte d'Ivoire" 
          class="w-10 h-10 object-contain group-hover:scale-105 transition-transform"
        />
        <div>
          <h1 class="text-base font-extrabold text-[#ff6b00] tracking-tight leading-tight">Portail National</h1>
          <p class="text-xs text-slate-500 font-medium">Recensement &amp; État Civil</p>
        </div>
      </div>

      <!-- Navigation Links by Role -->
      <ul class="flex flex-col gap-1.5 flex-grow">
        <li>
          <button 
            @click="$emit('navigate', 'home')"
            :class="[activeView === 'home' ? 'bg-[#006d40] text-white font-semibold shadow-sm' : 'text-slate-700 hover:bg-slate-100']"
            class="w-full flex items-center gap-3 p-3 rounded-xl text-xs transition-all active:scale-95 text-left"
          >
            <span class="material-symbols-outlined text-lg" :class="{ 'filled': activeView === 'home' }">dashboard</span>
            <span class="font-medium">{{ userRole === 'admin' ? 'Tableau de bord' : 'Accueil Public' }}</span>
          </button>
        </li>

        <li v-if="userRole === 'citoyen' || userRole === 'admin'">
          <button 
            @click="$emit('navigate', 'recensement')"
            :class="[activeView === 'recensement' ? 'bg-[#ff6b00] text-[#0b1c30] font-semibold shadow-sm' : 'text-slate-700 hover:bg-slate-100']"
            class="w-full flex items-center gap-3 p-3 rounded-xl text-xs transition-all active:scale-95 text-left"
          >
            <span class="material-symbols-outlined text-lg">person_add</span>
            <span class="font-medium">{{ userRole === 'admin' ? 'Enrôler un Citoyen' : 'Faire ma Déclaration' }}</span>
          </button>
        </li>

        <li v-if="userRole === 'admin'">
          <button 
            @click="$emit('navigate', 'moderation')"
            :class="[activeView === 'moderation' ? 'bg-[#006d40] text-white font-semibold shadow-sm' : 'text-slate-700 hover:bg-slate-100']"
            class="w-full flex items-center gap-3 p-3 rounded-xl text-xs transition-all active:scale-95 text-left"
          >
            <span class="material-symbols-outlined text-lg">verified_user</span>
            <span class="font-medium">Console Modération</span>
          </button>
        </li>

        <li>
          <button 
            @click="$emit('navigate', 'stats')"
            :class="[activeView === 'stats' ? 'bg-[#006d40] text-white font-semibold shadow-sm' : 'text-slate-700 hover:bg-slate-100']"
            class="w-full flex items-center gap-3 p-3 rounded-xl text-xs transition-all active:scale-95 text-left"
          >
            <span class="material-symbols-outlined text-lg">analytics</span>
            <span class="font-medium">Rapports &amp; Territoires</span>
          </button>
        </li>
      </ul>

      <!-- Bottom Auth CTA -->
      <div class="mt-auto pt-4 border-t border-slate-100 space-y-2">
        <div v-if="userRole === 'guest'" class="space-y-2">
          <button 
            @click="$emit('navigate', 'login')"
            class="w-full flex items-center justify-center gap-2 bg-[#ff6b00] hover:bg-[#e05e00] text-white font-bold py-2.5 px-4 rounded-xl shadow-xs transition-all text-xs"
          >
            <span class="material-symbols-outlined text-base">login</span>
            <span>Se Connecter</span>
          </button>

          <button 
            @click="$emit('navigate', 'register')"
            class="w-full flex items-center justify-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-2.5 px-4 rounded-xl transition-all text-xs"
          >
            <span class="material-symbols-outlined text-base">person_add</span>
            <span>Créer un Compte</span>
          </button>
        </div>

        <div v-else class="space-y-2">
          <button 
            @click="$emit('logout')"
            class="w-full flex items-center justify-center gap-2 bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold py-2.5 px-4 rounded-xl transition-all text-xs border border-rose-200"
          >
            <span class="material-symbols-outlined text-base">logout</span>
            <span>Déconnexion</span>
          </button>
        </div>

        <div class="text-[11px] text-slate-400 text-center pt-2 font-mono">
          API ANStat v2.1 • 526 SP
        </div>
      </div>
    </nav>

    <!-- TopNavBar Header -->
    <header class="ml-64 bg-white border-b border-slate-200 h-16 px-8 flex justify-between items-center sticky top-0 z-40 shadow-xs">
      <div class="flex items-center space-x-3">
        <div class="h-2.5 w-2.5 rounded-full bg-emerald-500 animate-pulse"></div>
        <h2 class="text-sm font-extrabold text-[#006d40] tracking-wide">
          RÉPUBLIQUE DE CÔTE D'IVOIRE
        </h2>
        <span class="text-slate-300">|</span>
        <span class="text-xs text-slate-500 font-medium italic">Union — Discipline — Travail</span>
      </div>

      <!-- User Active Badge Header -->
      <div class="flex items-center gap-4">
        <div class="relative hidden md:block">
          <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">search</span>
          <input 
            type="text" 
            placeholder="Rechercher un citoyen, un village..." 
            class="pl-9 pr-4 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-[#ff6b00] focus:ring-1 focus:ring-[#ff6b00] w-64 transition-all"
          />
        </div>

        <div v-if="userRole !== 'guest'" class="flex items-center space-x-2.5 pl-3 border-l border-slate-200">
          <div 
            :class="[userRole === 'admin' ? 'bg-[#006d40] text-white' : 'bg-[#ff6b00] text-white']"
            class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs shadow-xs"
          >
            {{ userRole === 'admin' ? 'ADM' : 'RES' }}
          </div>
          <div class="text-left hidden lg:block">
            <div class="text-xs font-bold text-slate-800">{{ userName || 'Ressortissant' }}</div>
            <div class="text-[10px] text-slate-500 font-medium">
              {{ userRole === 'admin' ? 'Administrateur / Agent' : 'Ressortissant Citoyen' }}
            </div>
          </div>
        </div>

        <div v-else class="flex items-center space-x-2">
          <button @click="$emit('navigate', 'login')" class="text-xs font-bold text-[#ff6b00] hover:underline">
            Connexion
          </button>
        </div>
      </div>
    </header>
  </div>
</template>

<script setup>
// Ce code sert à déclarer les propriétés (props) et événements émises de la barre de navigation.
// Il fonctionne avec le système de composables script setup de Vue 3.
// Dans le but de réagir à la vue active, d'afficher l'identité de l'utilisateur connecté et de propager les événements de navigation et déconnexion.
// Pour régler la cohérence de la navigation principale sur l'ensemble de la plateforme.
defineProps({
  activeView: {
    type: String,
    required: true
  },
  userRole: {
    type: String,
    default: 'guest'
  },
  userName: {
    type: String,
    default: ''
  }
});

defineEmits(['navigate', 'logout']);
</script>
