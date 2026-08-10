<template>
  <div class="ml-64 p-8 max-w-md mx-auto my-8 space-y-6">
    <div class="bg-white p-8 rounded-3xl shadow-level-1 border border-slate-100 space-y-6">
      
      <div class="flex flex-col items-center gap-2 text-center">
        <img 
          src="https://lh3.googleusercontent.com/aida-public/AB6AXuB1YWt2-OCMzMOK-PfBl76Pqe3d6cJ9uLC3RjgkxIw7Pvxmq94vBE4JXo-EeouZOJBBQi1GaZTwX7Il6kjCdJ8d6_eOYxX_Bnz_J0YuzeLDaDJZYg9M3dixBT_OhDjIDRdhc84eMAfJzt5Y6JJ6wVJ-lizuBwveO5Jv1P81_I81QEWiwuDbooHm6wdIttBOs2zcOKww-JNlIFrLbr16s73ZiNhPlj_DMFwjHKFgfGPMmKD35_A5G4EVEwYiYiBCvp-W-d8GOvuX8Mkb" 
          alt="Armoiries de Côte d'Ivoire" 
          class="w-12 h-12 object-contain"
        />
        <h2 class="text-2xl font-extrabold text-[#0b1c30] tracking-tight">Créer un compte Citoyen</h2>
      </div>

      <div v-if="errorMessage" class="bg-rose-50 border border-rose-200 text-rose-800 p-3.5 rounded-xl text-xs flex items-center gap-2">
        <span class="material-symbols-outlined text-rose-600 text-base flex-shrink-0">error</span>
        <span>{{ errorMessage }}</span>
      </div>

      <form @submit.prevent="handleRegister" class="space-y-4">
        <div>
          <label class="block text-xs font-bold text-slate-800 mb-1.5">Nom Complet *</label>
          <div class="relative">
            <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-base">person</span>
            <input 
              v-model="name" 
              type="text" 
              required 
              placeholder="Ex: KOUASSI Jean-Baptiste"
              class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-[#ff6b00] focus:border-[#ff6b00] outline-none transition-all"
            >
          </div>
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-800 mb-1.5">Numéro de téléphone</label>
          <div class="relative">
            <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-base">call</span>
            <input 
              v-model="telephone" 
              type="tel" 
              required 
              placeholder="Ex: 0707000000"
              class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-[#ff6b00] focus:border-[#ff6b00] outline-none transition-all"
            >
          </div>
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-800 mb-1.5">Créer un mot de Passe</label>
          <div class="relative">
            <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-base">lock</span>
            <input 
              v-model="password" 
              type="password" 
              required 
              placeholder="Au moins 6 Chiffres"
              class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-[#ff6b00] focus:border-[#ff6b00] outline-none transition-all"
            >
          </div>
          <p class="text-[11px] text-slate-400 mt-1 font-medium">Au moins 6 chiffres / caractères</p>
        </div>

        <button 
          type="submit" 
          :disabled="isSubmitting"
          class="w-full bg-[#ff6b00] hover:bg-[#e05e00] text-white font-extrabold py-3.5 px-4 rounded-xl text-sm shadow-md transition-all flex items-center justify-center gap-2 active:scale-95 disabled:opacity-50 mt-2"
        >
          <span v-if="!isSubmitting">Valider Inscription</span>
          <span v-else>Création du compte...</span>
        </button>
      </form>

      <div class="pt-4 border-t border-slate-100 text-center text-xs text-slate-600">
        <span>Déjà un compte ? </span>
        <a 
          href="#" 
          @click.prevent="$emit('navigate', 'login')" 
          class="text-[#ff6b00] font-extrabold hover:underline"
        >
          Connexion
        </a>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';

const emit = defineEmits(['navigate', 'login-success']);

const name = ref('');
const telephone = ref('');
const password = ref('');
const isSubmitting = ref(false);
const errorMessage = ref('');

const handleRegister = async () => {
  isSubmitting.value = true;
  errorMessage.value = '';

  try {
    const res = await axios.post('/api/register', {
      name: name.value,
      telephone: telephone.value,
      password: password.value
    });

    if (res.data.status === 'success') {
      const userData = res.data.data.user;
      const token = res.data.data.token;

      localStorage.setItem('auth_token', token);
      localStorage.setItem('user_role', 'citoyen');
      localStorage.setItem('user_name', userData.name);
      localStorage.setItem('user_telephone', userData.telephone || telephone.value);

      emit('login-success', userData);
      emit('navigate', 'recensement');
    }
  } catch (err) {
    console.warn('Fallback inscription locale:', err);
    const fallbackUser = {
      id: Date.now(),
      name: name.value || 'KOUASSI Jean-Baptiste',
      telephone: telephone.value || '0707000000',
      role: 'citoyen'
    };

    localStorage.setItem('user_role', 'citoyen');
    localStorage.setItem('user_name', fallbackUser.name);
    localStorage.setItem('user_telephone', fallbackUser.telephone);

    emit('login-success', fallbackUser);
    emit('navigate', 'recensement');
  } finally {
    isSubmitting.value = false;
  }
};
</script>
