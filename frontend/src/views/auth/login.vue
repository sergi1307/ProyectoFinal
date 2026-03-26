<script setup>
import { ref } from "vue";
import axios from "axios";
import { useRouter } from 'vue-router';
import "../../assets/css/auth.css";
import Footer from "@/components/Footer.vue";

const router = useRouter();

const email = ref("");
const password = ref("");

const enviarDatos = async () => {
  if (!email.value || !password.value) {
    console.log("Datos introducidos no válidos");
    return;
  }

  const payload = {
    email: email.value,
    password: password.value,
  };

  try {
    const response = await axios.post(
      "http://localhost:8080/api/auth/login",
      payload
    );

    const data = response.data; 

    if (data.token) {
      localStorage.setItem('token', data.token);
      localStorage.setItem('usuario', JSON.stringify(data.usuario));

      console.log("Login realizado con éxito");
      
      router.push('/general');
    }

  } catch (error) {
    if (error.response && error.response.status === 401) {
      alert("Email o contraseña incorrectos");
    } else {
      console.error("Error en el login:", error);
    }
  }
};
</script>

<template>
  <main>
    <router-link to="/" class="inicio">Volver al inicio</router-link>
    <form class="container" @submit.prevent="enviarDatos">
      <h1>Iniciar Sesión</h1>
      <p>Ingresa tu email y contraseña para acceder</p>
      
      <label for="email">Email:</label>
      <input
        type="email"
        id="email"
        v-model="email"
        placeholder="example@gmail.com"
        required
      />

      <label for="password">Contraseña:</label>
      <input
        type="password"
        id="password"
        v-model="password"
        placeholder="••••••••••"
        required
      />

      <button type="submit">Iniciar Sesión</button>

      <div id="frase"></div>
      <p id="normal">
        ¿No tienes cuenta?
        <router-link to="/register" id="cambiar">Regístrate</router-link>
      </p>
    </form>
  </main>
  <Footer></Footer>
</template>