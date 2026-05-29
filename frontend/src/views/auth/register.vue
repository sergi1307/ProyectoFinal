<script setup>
import { ref } from "vue";
import api from "@/services/api";
import { useRouter } from 'vue-router';
import { useI18n } from 'vue-i18n';
import "../../assets/css/auth.css";
import NavBar from "@/components/NavBar.vue";
import Footer from "@/components/Footer.vue";

const { t } = useI18n();
const router = useRouter();
const name = ref("");
const email = ref("");
const password = ref("");
const password_confirmation = ref(""); 
const errorMsg = ref("");

const registrarUsuario = async () => {
  errorMsg.value = "";

  if (password.value !== password_confirmation.value) {
    errorMsg.value = t('auth.passMismatch');
    return;
  }

  const payload = {
    name: name.value,
    email: email.value,
    password: password.value,
    password_confirmation: password_confirmation.value
  };

  try {
    const response = await api.post("/auth/registro", payload);
    console.log("Registro realizado con éxito", response.data);
    
    router.push(`/verificar-email?email=${email.value}`);

  } catch (error) {
    console.error("Error en el registro:", error.response?.data);
    
    if (error.response && error.response.data && error.response.data.mensaje) {
      errorMsg.value = error.response.data.mensaje;
    } else if (error.response && error.response.data && error.response.data.errors) {
      errorMsg.value = Object.values(error.response.data.errors)[0][0];
    } else {
      errorMsg.value = t('auth.errorConn');
    }
  }
};
</script>

<template>
  <NavBar></NavBar>
  <main>
    <router-link to="/" class="inicio">←  {{ $t('auth.backHome') }}</router-link>
    <form class="container" @submit.prevent="registrarUsuario">
      <h1>{{ $t('auth.titleRegister') }}</h1>
      
      <p v-if="errorMsg" style="color: #ef4444; text-align: center; margin-bottom: 1rem; font-weight: 600;">
        {{ errorMsg }}
      </p>

      <label for="name">{{ $t('auth.username') }}:</label>
      <input 
        type="text" 
        id="name" 
        v-model="name" 
        :placeholder="$t('auth.username')" 
        required 
      />

      <label for="email">{{ $t('auth.email') }}:</label>
      <input 
        type="email" 
        id="email" 
        v-model="email" 
        :placeholder="$t('auth.emailPlaceholder')"
        required 
      />

      <label for="password">{{ $t('auth.password') }}:</label>
      <input 
        type="password" 
        id="password" 
        v-model="password" 
        placeholder="••••••••••" 
        required 
      />

      <label for="password_confirm">{{ $t('auth.confirmPassword') }}:</label>
      <input 
        type="password" 
        id="password_confirm" 
        v-model="password_confirmation" 
        placeholder="••••••••••" 
        required 
      />

      <button type="submit">{{ $t('navbar.register') }}</button>
      
      <p id="normal">
        {{ $t('auth.haveAccount') }} 
        <router-link to="/login" id="cambiar">{{ $t('navbar.login') }}</router-link>
      </p>
    </form>
  </main>
  <Footer></Footer>
</template>