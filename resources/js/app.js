// Ce code sert à initialiser et monter l'application frontend Vue 3.
// Il fonctionne avec le composant racine App.vue, le style app.css et la méthode createApp() de Vue.
// Dans le but d'accrocher l'application au conteneur DOM HTML '#app'.
// Pour régler le démarrage de l'interface utilisateur dynamique.
import { createApp } from 'vue';
import App from './App.vue';
import '../css/app.css';

const app = createApp(App);
app.mount('#app');

