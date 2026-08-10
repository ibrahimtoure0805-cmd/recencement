<template>
  <div class="ml-64 p-8 max-w-4xl mx-auto space-y-6">
    <div class="bg-white p-6 rounded-2xl shadow-level-1 border border-slate-100 flex items-center justify-between">
      <div>
        <div class="flex items-center space-x-2">
          <span class="bg-orange-100 text-[#ff6b00] text-xs font-extrabold px-2.5 py-0.5 rounded-full uppercase tracking-wider">Formulaire Officiel</span>
          <span class="text-xs text-slate-400 font-mono">• Réf: ANStat-2026</span>
        </div>
        <h1 class="text-2xl font-extrabold text-[#0b1c30] mt-1 tracking-tight">Fiche d'Enrôlement Citoyen &amp; Déclaration de Recensement</h1>
        <p class="text-slate-500 text-xs mt-0.5">Complétez vos informations d'état civil, votre ancrage territorial ANStat et vos origines.</p>
      </div>
      <div class="hidden sm:block text-right">
        <div class="text-xs font-bold text-slate-400 font-mono">Étape {{ currentStep }} / 4</div>
        <div class="w-28 bg-slate-100 h-2 rounded-full mt-1.5 overflow-hidden">
          <div class="bg-[#ff6b00] h-full transition-all duration-300" :style="{ width: (currentStep * 25) + '%' }"></div>
        </div>
      </div>
    </div>

    <div class="grid grid-cols-4 gap-3 text-center text-xs font-bold">
      <button 
        @click="currentStep = 1" 
        :class="[currentStep === 1 ? 'bg-[#ff6b00] text-white shadow-md' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50']"
        class="py-3 px-2 rounded-xl transition-all flex items-center justify-center space-x-1.5 active:scale-95"
      >
        <span class="material-symbols-outlined text-base">badge</span>
        <span>1. État Civil</span>
      </button>

      <button 
        @click="currentStep = 2" 
        :class="[currentStep === 2 ? 'bg-[#ff6b00] text-white shadow-md' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50']"
        class="py-3 px-2 rounded-xl transition-all flex items-center justify-center space-x-1.5 active:scale-95"
      >
        <span class="material-symbols-outlined text-base">account_balance</span>
        <span>2. ANStat</span>
      </button>

      <button 
        @click="currentStep = 3" 
        :class="[currentStep === 3 ? 'bg-[#ff6b00] text-white shadow-md' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50']"
        class="py-3 px-2 rounded-xl transition-all flex items-center justify-center space-x-1.5 active:scale-95"
      >
        <span class="material-symbols-outlined text-base">location_city</span>
        <span>3. Coutumier</span>
      </button>

      <button 
        @click="currentStep = 4" 
        :class="[currentStep === 4 ? 'bg-[#ff6b00] text-white shadow-md' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50']"
        class="py-3 px-2 rounded-xl transition-all flex items-center justify-center space-x-1.5 active:scale-95"
      >
        <span class="material-symbols-outlined text-base">public</span>
        <span>4. Domiciliation</span>
      </button>
    </div>

    <div v-if="successMessage" class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-xl flex items-center justify-between shadow-xs">
      <div class="flex items-center space-x-3">
        <span class="material-symbols-outlined text-emerald-600 text-2xl">check_circle</span>
        <span class="text-xs font-semibold">{{ successMessage }}</span>
      </div>
      <button @click="successMessage = ''" class="text-emerald-700 hover:text-emerald-900 font-bold text-xs">✖</button>
    </div>

    <div v-if="errorMessage" class="bg-rose-50 border border-rose-200 text-rose-800 p-4 rounded-xl flex items-center justify-between shadow-xs">
      <div class="flex items-center space-x-3">
        <span class="material-symbols-outlined text-rose-600 text-2xl">warning</span>
        <span class="text-xs font-semibold">{{ errorMessage }}</span>
      </div>
      <button @click="errorMessage = ''" class="text-rose-700 hover:text-rose-900 font-bold text-xs">✖</button>
    </div>

    <form @submit.prevent="submitForm" class="bg-white p-8 rounded-2xl shadow-level-1 border border-slate-100 space-y-6">
      
      <div v-if="currentStep === 1" class="space-y-4">
        <div class="border-b border-slate-100 pb-3">
          <h2 class="text-lg font-bold text-slate-900">Étape 1 : État Civil &amp; Identité Citoyenne</h2>
          <p class="text-slate-500 text-xs">Veuillez indiquer vos informations d'identité officielles.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Nom de Famille *</label>
            <input v-model="form.nom" type="text" required placeholder="Ex: KOUASSI" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-[#ff6b00] focus:border-[#ff6b00] outline-none transition-all">
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Prénoms *</label>
            <input v-model="form.prenom" type="text" required placeholder="Ex: Yao Jean-Baptiste" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-[#ff6b00] focus:border-[#ff6b00] outline-none transition-all">
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Numéro de Téléphone *</label>
            <input v-model="form.telephone" type="tel" required placeholder="Ex: 0707000000" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-[#ff6b00] focus:border-[#ff6b00] outline-none transition-all">
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Sexe *</label>
            <select v-model="form.sexe" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-[#ff6b00] focus:border-[#ff6b00] outline-none transition-all">
              <option value="">-- Sélectionnez le sexe --</option>
              <option value="M">Masculin (M)</option>
              <option value="F">Féminin (F)</option>
            </select>
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Date de Naissance *</label>
            <input v-model="form.date_naissance" type="date" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-[#ff6b00] focus:border-[#ff6b00] outline-none transition-all">
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Lieu de Naissance *</label>
            <input v-model="form.lieu_naissance" type="text" required placeholder="Ex: Abidjan, Yamoussoukro, etc." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-[#ff6b00] focus:border-[#ff6b00] outline-none transition-all">
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Profession / Activité</label>
            <input v-model="form.profession" type="text" placeholder="Ex: Enseignant, Informaticien, Commerçant..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-[#ff6b00] focus:border-[#ff6b00] outline-none transition-all">
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Nom de Famille / Lignée</label>
            <input v-model="form.famille" type="text" placeholder="Ex: Famille N'Dri" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-[#ff6b00] focus:border-[#ff6b00] outline-none transition-all">
          </div>
        </div>
      </div>

      <div v-if="currentStep === 2" class="space-y-4">
        <div class="border-b border-slate-100 pb-3 flex justify-between items-center">
          <div>
            <h2 class="text-lg font-bold text-slate-900">Étape 2 : Rattachement Administratif (Cascade ANStat)</h2>
            <p class="text-slate-500 text-xs">Sélectionnez successivement vos territoires d'origine officielle.</p>
          </div>
          <span class="text-[10px] bg-orange-100 text-[#ff6b00] font-mono font-bold px-2 py-1 rounded">Obligatoire</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">1. District Autonome / Régional *</label>
            <select v-model="selectedDistrict" @change="onDistrictChange" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-[#ff6b00] outline-none">
              <option value="">-- Choisir un District --</option>
              <option v-for="d in districts" :key="d.id" :value="d.id">
                {{ d.nom_district || d.code_district }}
              </option>
            </select>
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">2. Région Administrative *</label>
            <select v-model="selectedRegion" @change="onRegionChange" :disabled="!filteredRegions.length" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-[#ff6b00] outline-none disabled:bg-slate-100">
              <option value="">-- {{ filteredRegions.length ? 'Choisir une Région' : 'Sélectionnez d\'abord un District' }} --</option>
              <option v-for="r in filteredRegions" :key="r.id" :value="r.id">
                {{ r.nom_reg || r.cod_reg }}
              </option>
            </select>
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">3. Département *</label>
            <select v-model="selectedDepartement" @change="onDepartementChange" :disabled="!filteredDepartements.length" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-[#ff6b00] outline-none disabled:bg-slate-100">
              <option value="">-- {{ filteredDepartements.length ? 'Choisir un Département' : 'Sélectionnez d\'abord une Région' }} --</option>
              <option v-for="dep in filteredDepartements" :key="dep.id" :value="dep.id">
                {{ dep.nom_dep || dep.cod_dep }}
              </option>
            </select>
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">4. Sous-Préfecture (Pivot ANStat) *</label>
            <select v-model="form.sous_prefecture_id" :disabled="!filteredSousPrefectures.length" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-[#ff6b00] outline-none disabled:bg-slate-100">
              <option value="">-- {{ filteredSousPrefectures.length ? 'Choisir une Sous-Préfecture' : 'Sélectionnez d\'abord un Département' }} --</option>
              <option v-for="sp in filteredSousPrefectures" :key="sp.id" :value="sp.id">
                {{ sp.nom_sp || sp.cod_sp }}
              </option>
            </select>
          </div>
        </div>
      </div>

      <div v-if="currentStep === 3" class="space-y-4">
        <div class="border-b border-slate-100 pb-3 flex justify-between items-center">
          <div>
            <h2 class="text-lg font-bold text-slate-900">Étape 3 : Origines Coutumières et Traditionnelles</h2>
            <p class="text-slate-500 text-xs">Informations sur votre terroir coutumier d'origine (Facultatif).</p>
          </div>
          <span class="text-[10px] bg-slate-100 text-slate-600 font-mono px-2 py-1 rounded">Optionnel (0..1)</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Canton Coutumier</label>
            <select v-model="selectedCanton" @change="onCantonChange" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-[#ff6b00] outline-none">
              <option value="">-- Aucun / Non précisé --</option>
              <option v-for="c in cantons" :key="c.id" :value="c.id">{{ c.nom }}</option>
            </select>
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Tribu Traditionnelle</label>
            <select v-model="selectedTribu" @change="onTribuChange" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-[#ff6b00] outline-none">
              <option value="">-- Aucune / Non précisée --</option>
              <option v-for="t in filteredTribus" :key="t.id" :value="t.id">{{ t.nom }}</option>
            </select>
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Village / Localité</label>
            <select v-model="form.village_id" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-[#ff6b00] outline-none">
              <option value="">-- Aucun / Non précisé --</option>
              <option v-for="v in filteredVillages" :key="v.id" :value="v.id">{{ v.nom }}</option>
            </select>
          </div>
        </div>
      </div>

      <div v-if="currentStep === 4" class="space-y-4">
        <div class="border-b border-slate-100 pb-3">
          <h2 class="text-lg font-bold text-slate-900">Étape 4 : Domiciliation et Pays de Résidence</h2>
          <p class="text-slate-500 text-xs">Indiquez votre lieu de résidence actuel (Territoire national ou Diaspora).</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Pays de Résidence *</label>
            <select v-model="form.pays_id" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-[#ff6b00] outline-none">
              <option value="">-- Choisir un Pays --</option>
              <option v-for="p in paysList" :key="p.id" :value="p.id">
                {{ p.nom }} {{ p.code_iso ? '(' + p.code_iso + ')' : '' }}
              </option>
            </select>
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Ville de Résidence *</label>
            <input v-model="form.ville" type="text" required placeholder="Ex: Abidjan, Paris, Montréal..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-[#ff6b00] outline-none">
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Quartier / Commune</label>
            <input v-model="form.quartier" type="text" placeholder="Ex: Cocody, Marcory..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-[#ff6b00] outline-none">
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Adresse Complète</label>
            <input v-model="form.adresse" type="text" placeholder="Ex: Rue des Jardins, Appt 4B" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-[#ff6b00] outline-none">
          </div>
        </div>
      </div>

      <div class="pt-6 border-t border-slate-100 flex justify-between items-center">
        <button 
          type="button"
          v-if="currentStep > 1"
          @click="currentStep--"
          class="px-5 py-2.5 rounded-xl border border-slate-300 text-slate-700 text-xs font-bold hover:bg-slate-50 transition-colors"
        >
          ← Étape Précédente
        </button>
        <div v-else></div>

        <button 
          type="button"
          v-if="currentStep < 4"
          @click="currentStep++"
          class="px-6 py-2.5 rounded-xl bg-[#ff6b00] hover:bg-[#e05e00] text-white text-xs font-bold shadow-md transition-all active:scale-95"
        >
          Suivant →
        </button>

        <button 
          type="submit"
          v-if="currentStep === 4"
          :disabled="isSubmitting"
          class="px-8 py-3 rounded-xl bg-[#006d40] hover:bg-[#005230] text-white text-xs font-bold shadow-lg transition-all flex items-center space-x-2 disabled:opacity-50 active:scale-95"
        >
          <span v-if="!isSubmitting">Soumettre ma Déclaration</span>
          <span v-else>Traitement en cours...</span>
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import axios from 'axios';

const currentStep = ref(1);
const isSubmitting = ref(false);
const successMessage = ref('');
const errorMessage = ref('');

const districts = ref([]);
const regions = ref([]);
const departements = ref([]);
const sousPrefectures = ref([]);

const cantons = ref([]);
const tribus = ref([]);
const villages = ref([]);

const paysList = ref([]);

const selectedDistrict = ref('');
const selectedRegion = ref('');
const selectedDepartement = ref('');

const selectedCanton = ref('');
const selectedTribu = ref('');

const filteredRegions = ref([]);
const filteredDepartements = ref([]);
const filteredSousPrefectures = ref([]);

const filteredTribus = ref([]);
const filteredVillages = ref([]);

const form = reactive({
  nom: '',
  prenom: '',
  telephone: '',
  sexe: '',
  date_naissance: '',
  lieu_naissance: '',
  profession: '',
  famille: '',
  sous_prefecture_id: '',
  village_id: '',
  pays_id: '',
  ville: '',
  quartier: '',
  adresse: ''
});

const loadInitialData = async () => {
  try {
    const [distRes, regRes, depRes, spRes, paysRes, cantRes, tribRes, villRes] = await Promise.all([
      axios.get('/api/districts').catch(() => ({ data: [] })),
      axios.get('/api/regions').catch(() => ({ data: [] })),
      axios.get('/api/departements').catch(() => ({ data: [] })),
      axios.get('/api/sous-prefectures').catch(() => ({ data: [] })),
      axios.get('/api/pays').catch(() => ({ data: [] })),
      axios.get('/api/cantons').catch(() => ({ data: [] })),
      axios.get('/api/tribus').catch(() => ({ data: [] })),
      axios.get('/api/villages').catch(() => ({ data: [] }))
    ]);

    districts.value = distRes.data.data || distRes.data || [];
    regions.value = regRes.data.data || regRes.data || [];
    departements.value = depRes.data.data || depRes.data || [];
    sousPrefectures.value = spRes.data.data || spRes.data || [];
    
    paysList.value = paysRes.data.data || paysRes.data || [
      { id: 1, nom: "Côte d'Ivoire", code_iso: "CI" },
      { id: 2, nom: "France", code_iso: "FR" },
      { id: 3, nom: "Canada", code_iso: "CA" },
      { id: 4, nom: "États-Unis", code_iso: "US" }
    ];

    cantons.value = cantRes.data.data || cantRes.data || [];
    tribus.value = tribRes.data.data || tribRes.data || [];
    villages.value = villRes.data.data || villRes.data || [];
  } catch (err) {
    console.error('Erreur chargement référentiel:', err);
  }
};

const onDistrictChange = () => {
  selectedRegion.value = '';
  selectedDepartement.value = '';
  form.sous_prefecture_id = '';
  
  if (!selectedDistrict.value) {
    filteredRegions.value = [];
    return;
  }
  const distObj = districts.value.find(d => d.id == selectedDistrict.value);
  const distCode = distObj?.code_district;

  filteredRegions.value = regions.value.filter(r => 
    r.cod_dist === distCode || r.district_id == selectedDistrict.value
  );
};

const onRegionChange = () => {
  selectedDepartement.value = '';
  form.sous_prefecture_id = '';
  
  if (!selectedRegion.value) {
    filteredDepartements.value = [];
    return;
  }
  const regObj = regions.value.find(r => r.id == selectedRegion.value);
  const regCode = regObj?.cod_reg;

  filteredDepartements.value = departements.value.filter(d => 
    d.cod_reg === regCode || d.region_id == selectedRegion.value
  );
};

const onDepartementChange = () => {
  form.sous_prefecture_id = '';
  
  if (!selectedDepartement.value) {
    filteredSousPrefectures.value = [];
    return;
  }
  const depObj = departements.value.find(d => d.id == selectedDepartement.value);
  const depCode = depObj?.cod_dep;

  filteredSousPrefectures.value = sousPrefectures.value.filter(sp => 
    sp.cod_dep === depCode || sp.departement_id == selectedDepartement.value
  );
};

const onCantonChange = () => {
  selectedTribu.value = '';
  form.village_id = '';
  if (!selectedCanton.value) {
    filteredTribus.value = [];
    return;
  }
  filteredTribus.value = tribus.value.filter(t => t.canton_id == selectedCanton.value);
};

const onTribuChange = () => {
  form.village_id = '';
  if (!selectedTribu.value) {
    filteredVillages.value = [];
    return;
  }
  filteredVillages.value = villages.value.filter(v => v.tribu_id == selectedTribu.value);
};

const submitForm = async () => {
  isSubmitting.value = true;
  successMessage.value = '';
  errorMessage.value = '';

  try {
    const payload = { ...form };
    const res = await axios.post('/api/ressortissants', payload);
    
    successMessage.value = 'Votre déclaration de recensement a été enregistrée avec succès sous le statut initial "en_attente" !';
    
    Object.keys(form).forEach(k => form[k] = '');
    currentStep.value = 1;
  } catch (err) {
    console.error('Erreur enregistrement:', err);
    errorMessage.value = err.response?.data?.message || 'Erreur lors de l\'enregistrement de votre déclaration. Veuillez vérifier vos données.';
  } finally {
    isSubmitting.value = false;
  }
};

onMounted(() => {
  loadInitialData();
});
</script>
