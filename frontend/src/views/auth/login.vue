<script setup>
import { ref } from "vue";
import axios from "axios";
import { useRouter } from 'vue-router';
import { useI18n } from 'vue-i18n';
import "../../assets/css/auth.css";
import NavBar from "@/components/NavBar.vue";
import Footer from "@/components/Footer.vue";

const { t } = useI18n();
const router = useRouter();
const email = ref("");
const password = ref("");
const errorMsg = ref("");

const enviarDatos = async () => {
  errorMsg.value = "";
  if (!email.value || !password.value) return;

  try {
    const response = await axios.post("http://localhost:8080/api/auth/login", {
      email: email.value,
      password: password.value,
    });

    const data = response.data; 

    if (data.token && data.usuario) {
      localStorage.setItem('token', data.token);
      localStorage.setItem('usuario', JSON.stringify(data.usuario));

      window.dispatchEvent(new Event('login-success'));
      window.dispatchEvent(new Event('storage'));

      if (data.usuario.role === 'admin') {
        router.push('/admin');
      } else {
        router.push('/general');
      }
    }
  } catch (error) {
    if (error.response) {
      if (error.response.status === 401) {
        errorMsg.value = t('auth.errorLogin');
      } 
      else if (error.response.status === 403) {
        errorMsg.value = error.response.data.error;
      } 
      else {
        errorMsg.value = t('auth.errorConn');
      }
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
    <form class="container" @submit.prevent="enviarDatos">
      <h1>{{ $t('auth.titleLogin') }}</h1>
      
      <p v-if="errorMsg" style="color: #ef4444; text-align: center; margin-bottom: 1rem; font-weight: 600;">
        {{ errorMsg }}
      </p>

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

      <button type="submit">{{ $t('auth.titleLogin') }}</button>
      
      <p id="normal">
        {{ $t('auth.noAccount') }} 
        <router-link to="/register" id="cambiar">{{ $t('navbar.register') }}</router-link>
      </p>
    </form>
  </main>
  <Footer></Footer>
</template>