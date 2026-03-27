<script setup>
import { ref } from "vue";
import axios from "axios";
import { useRouter } from 'vue-router';
import "../../assets/css/auth.css";
import Footer from "@/components/Footer.vue";

const router = useRouter();
const name = ref("");
const email = ref("");
const password = ref("");
const password_confirmation = ref(""); 
const errorMsg = ref("");

const registrarUsuario = async () => {
  errorMsg.value = "";

  if (password.value !== password_confirmation.value) {
    errorMsg.value = "Las contraseñas no coinciden";
    return;
  }

  try {
    await axios.post("http://127.0.0.1:8080/api/auth/registro", {
      name: name.value,
      email: email.value,
      password: password.value,
      password_confirmation: password_confirmation.value
    });

    try {
        const response = await axios.post("http://localhost:8080/api/auth/registro", payload);
        console.log("Registro realizado con éxito");
        router.push(`/verificar-email?email=${email.value}`);
    } catch (error) {
        console.error("Error en el register:", error.response.data);
    }
  }
};
</script>

<template>
  <main>
    <router-link to="/" class="inicio">Volver al inicio</router-link>
    <form class="container" @submit.prevent="registrarUsuario">
      <h1>Crear Cuenta</h1>
      <p v-if="errorMsg" style="color: #ef4444; text-align: center; margin-bottom: 1rem;">
        {{ errorMsg }}
      </p>

      <label for="name">Nombre de usuario:</label>
      <input type="text" id="name" v-model="name" placeholder="Tu nombre" required />

      <label for="email">Email:</label>
      <input type="email" id="email" v-model="email" placeholder="example@gmail.com" required />

      <label for="password">Contraseña:</label>
      <input type="password" id="password" v-model="password" placeholder="••••••••••" required />

      <label for="password_confirm">Repetir Contraseña:</label>
      <input type="password" id="password_confirm" v-model="password_confirmation" placeholder="••••••••••" required />

      <button type="submit">Registrarse</button>
      <p id="normal">
        ¿Ya tienes cuenta? <router-link to="/login" id="cambiar">Inicia sesión</router-link>
      </p>
    </form>
  </main>
  <Footer></Footer>
</template>