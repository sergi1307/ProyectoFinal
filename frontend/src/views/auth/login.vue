<script setup>
import { ref } from "vue";
import axios from "axios";
import { useRouter } from 'vue-router';
import "../../assets/css/auth.css";
import Footer from "@/components/Footer.vue";

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

      router.push('/general');
    }
  } catch (error) {
    if (error.response && error.response.status === 401) {
      errorMsg.value = "Email o contraseña incorrectos";
    } else {
      errorMsg.value = "Error de conexión con el servidor";
    }
  }
};
</script>

<template>
  <main>
    <router-link to="/" class="inicio">Volver al inicio</router-link>
    <form class="container" @submit.prevent="enviarDatos">
      <h1>Iniciar Sesión</h1>
      <p v-if="errorMsg" style="color: #ef4444; text-align: center; margin-bottom: 1rem;">
        {{ errorMsg }}
      </p>

      <label for="email">Email:</label>
      <input type="email" id="email" v-model="email" placeholder="example@gmail.com" required />

      <label for="password">Contraseña:</label>
      <input type="password" id="password" v-model="password" placeholder="••••••••••" required />

      <button type="submit">Iniciar Sesión</button>
      <p id="normal">
        ¿No tienes cuenta? <router-link to="/register" id="cambiar">Regístrate</router-link>
      </p>
    </form>
  </main>
  <Footer></Footer>
</template>