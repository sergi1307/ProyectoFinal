<script setup>
import { ref } from "vue";
import axios from "axios";
import "../../assets/css/auth.css";

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
      "http://localhost:3000/api/login",
      payload,
    );
    console.log("Login realizado con éxito");
  } catch (error) {
    console.error("Error en el login:", error);
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
      />

      <label for="password">Contraseña:</label>
      <input
        type="password"
        id="password"
        v-model="password"
        placeholder="••••••••••"
      />

      <button type="submit">Iniciar Sesión</button>

      <div id="frase"></div>
      <p id="normal">
        ¿No tienes cuenta?
        <router-link to="/register" id="cambiar">Regístrate</router-link>
      </p>
    </form>
  </main>
</template>