<template>
  <div class="min-h-screen flex flex-col bg-[#f8f9ff] text-[#0b1c30] font-sans antialiased">
    <Navbar 
      :activeView="activeView" 
      :userRole="userRole" 
      :userName="userName"
      @navigate="handleNavigate" 
      @logout="handleLogout"
    />
    
    <main class="flex-1">
      <keep-alive>
        <component 
          :is="currentViewComponent" 
          :userRole="userRole"
          @navigate="handleNavigate" 
          @login-success="onLoginSuccess"
        />
      </keep-alive>
    </main>

    <Footer @navigate="handleNavigate" />
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import Navbar from './components/Navbar.vue';
import Footer from './components/Footer.vue';

import HomeView from './views/HomeView.vue';
import RecensementView from './views/RecensementView.vue';
import ModerationView from './views/ModerationView.vue';
import StatsView from './views/StatsView.vue';
import LoginView from './views/LoginView.vue';
import RegisterView from './views/RegisterView.vue';

const activeView = ref('home');
const userRole = ref('guest'); // 'guest', 'citoyen', 'admin'
const userName = ref('');
const userEmail = ref('');

const loadSession = () => {
  const role = localStorage.getItem('user_role');
  const name = localStorage.getItem('user_name');
  const email = localStorage.getItem('user_email');

  if (role) {
    userRole.value = role;
    userName.value = name || '';
    userEmail.value = email || '';
  }
};

const handleNavigate = (view) => {
  if (view === 'moderation' && userRole.value !== 'admin') {
    activeView.value = 'login';
    return;
  }
  activeView.value = view;
};

const onLoginSuccess = (user) => {
  userRole.value = user.role;
  userName.value = user.name;
  userEmail.value = user.email;
};

const handleLogout = () => {
  localStorage.removeItem('auth_token');
  localStorage.removeItem('user_role');
  localStorage.removeItem('user_name');
  localStorage.removeItem('user_email');

  userRole.value = 'guest';
  userName.value = '';
  userEmail.value = '';
  activeView.value = 'home';
};

const currentViewComponent = computed(() => {
  switch (activeView.value) {
    case 'login':
      return LoginView;
    case 'register':
      return RegisterView;
    case 'recensement':
      return RecensementView;
    case 'moderation':
      return userRole.value === 'admin' ? ModerationView : LoginView;
    case 'stats':
      return StatsView;
    case 'home':
    default:
      return HomeView;
  }
});

onMounted(() => {
  loadSession();
});
</script>
