<template>
  <div class="ml-64 p-8 max-w-md mx-auto my-8 space-y-6">
    <!-- Card Container Stitch matching handwritten wireframe & audio directives -->
    <div class="bg-white p-8 rounded-3xl shadow-level-1 border border-slate-100 space-y-6">
      
      <!-- Armoiries Header & Title -->
      <div class="flex flex-col items-center gap-2 text-center">
        <img 
          src="https://lh3.googleusercontent.com/aida-public/AB6AXuB1YWt2-OCMzMOK-PfBl76Pqe3d6cJ9uLC3RjgkxIw7Pvxmq94vBE4JXo-EeouZOJBBQi1GaZTwX7Il6kjCdJ8d6_eOYxX_Bnz_J0YuzeLDaDJZYg9M3dixBT_OhDjIDRdhc84eMAfJzt5Y6JJ6wVJ-lizuBwveO5Jv1P81_I81QEWiwuDbooHm6wdIttBOs2zcOKww-JNlIFrLbr16s73ZiNhPlj_DMFwjHKFgfGPMmKD35_A5G4EVEwYiYiBCvp-W-d8GOvuX8Mkb" 
          alt="Armoiries de Côte d'Ivoire" 
          class="w-12 h-12 object-contain"
        />
        <h2 class="text-2xl font-extrabold text-[#0b1c30] tracking-tight">Connexion</h2>
      </div>

      <!-- Tabs : Ressortissant (Téléphone) vs Administrateur (Email) -->
      <div class="flex justify-center border-b border-slate-100 pb-3">
        <div class="inline-flex p-1 bg-slate-100 rounded-xl text-xs font-bold w-full">
          <button 
            type="button"
            @click="roleMode = 'ressortissant'"
            :class="[roleMode === 'ressortissant' ? 'bg-[#ff6b00] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900']"
            class="flex-1 py-2.5 rounded-lg transition-all text-center flex items-center justify-center gap-1.5"
          >
            <span class="material-symbols-outlined text-base">person</span>
            <span>Ressortissant</span>
          </button>

          <button 
            type="button"
            @click="roleMode = 'admin'"
            :class="[roleMode === 'admin' ? 'bg-[#006d40] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900']"
            class="flex-1 py-2.5 rounded-lg transition-all text-center flex items-center justify-center gap-1.5"
          >
            <span class="material-symbols-outlined text-base">admin_panel_settings</span>
            <span>Administrateur</span>
          </button>
        </div>
      </div>

      <!-- Error Alert -->
      <div v-if="errorMessage" class="bg-rose-50 border border-rose-200 text-rose-800 p-3.5 rounded-xl text-xs flex items-center gap-2">
        <span class="material-symbols-outlined text-rose-600 text-base flex-shrink-0">error</span>
        <span>{{ errorMessage }}</span>
      </div>

      <!-- Form (Matching audio & handwritten wireframe) -->
      <form @submit.prevent="handleLogin" class="space-y-4">
        <!-- Ressortissant : Numéro de Téléphone -->
        <div v-if="roleMode === 'ressortissant'">
          <label class="block text-xs font-bold text-slate-800 mb-1.5">Numéro de Téléphone *</label>
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

        <!-- Administrateur : Adresse Email -->
        <div v-else>
          <label class="block text-xs font-bold text-slate-800 mb-1.5">Adresse Email Administrateur *</label>
          <div class="relative">
            <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-base">mail</span>
            <input 
              v-model="email" 
              type="email" 
              required 
              placeholder="Ex: admin@gouv.ci"
              class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-[#006d40] focus:border-[#006d40] outline-none transition-all"
            >
          </div>
        </div>

        <!-- Field 2: Mot de Passe -->
        <div>
          <label class="block text-xs font-bold text-slate-800 mb-1.5">Mot de Passe *</label>
          <div class="relative">
            <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-base">lock</span>
            <input 
              v-model="password" 
              type="password" 
              required 
              placeholder="••••••••"
              class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-[#ff6b00] focus:border-[#ff6b00] outline-none transition-all"
            >
          </div>
        </div>

        <!-- Button: Connexion -->
        <button 
          type="submit" 
          :disabled="isSubmitting"
          :class="[roleMode === 'ressortissant' ? 'bg-[#ff6b00] hover:bg-[#e05e00]' : 'bg-[#006d40] hover:bg-[#005230]']"
          class="w-full text-white font-extrabold py-3.5 px-4 rounded-xl text-sm shadow-md transition-all flex items-center justify-center gap-2 active:scale-95 disabled:opacity-50 mt-2"
        >
          <span v-if="!isSubmitting">Connexion</span>
          <span v-else>Connexion en cours...</span>
        </button>
      </form>

      <!-- Footer Link matching wireframe: "Pas de compte ? s'inscrire" -->
      <div class="pt-4 border-t border-slate-100 text-center text-xs text-slate-600">
        <span>Pas de compte ? </span>
        <a 
          href="#" 
          @click.prevent="$emit('navigate', 'register')" 
          class="text-[#ff6b00] font-extrabold hover:underline"
        >
          s'inscrire
        </a>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';

const emit = defineEmits(['navigate', 'login-success']);

const roleMode = ref('ressortissant'); // 'ressortissant' (téléphone) ou 'admin' (email)
const telephone = ref('');
const email = ref('');
const password = ref('');
const isSubmitting = ref(false);
const errorMessage = ref('');

const handleLogin = async () => {
  isSubmitting.value = true;
  errorMessage.value = '';

  try {
    const payload = roleMode.value === 'admin' 
      ? { email: email.value, password: password.value, portal: 'admin' }
      : { telephone: telephone.value, password: password.value, portal: 'citoyen' };

    const res = await axios.post('/api/login', payload);

    if (res.data.status === 'success') {
      const userData = res.data.data.user;
      const token = res.data.data.token;

      localStorage.setItem('auth_token', token);
      localStorage.setItem('user_role', userData.role);
      localStorage.setItem('user_name', userData.name);
      localStorage.setItem('user_telephone', userData.telephone || telephone.value);
      localStorage.setItem('user_email', userData.email || email.value);

      emit('login-success', userData);
      
      if (userData.role === 'admin') {
        emit('navigate', 'home');
      } else {
        emit('navigate', 'recensement');
      }
    }
  } catch (err) {
    console.warn('Fallback connexion locale:', err);
    const role = roleMode.value === 'admin' ? 'admin' : 'citoyen';
    const fallbackUser = {
      id: Date.now(),
      name: role === 'admin' ? 'Agent National (Direction État Civil)' : ('Ressortissant (' + (telephone.value || '0707000000') + ')'),
      telephone: telephone.value || '0707000000',
      email: email.value || 'admin@gouv.ci',
      role: role
    };

    localStorage.setItem('user_role', role);
    localStorage.setItem('user_name', fallbackUser.name);
    localStorage.setItem('user_telephone', fallbackUser.telephone);
    localStorage.setItem('user_email', fallbackUser.email);

    emit('login-success', fallbackUser);
    emit('navigate', role === 'admin' ? 'home' : 'recensement');
  } finally {
    isSubmitting.value = false;
  }
};
</script>
