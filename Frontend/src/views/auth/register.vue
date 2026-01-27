<script setup>
import { ref } from 'vue';
import axios from 'axios';
import "../../assets/css/auth.css";

const name = ref('');
const email = ref('');
const password = ref('');
const confirmPassword = ref('');

const enviarDatos = async () => {
    if (!name.value || !email.value || !password.value) {
        console.log("Datos introducidos no válidos");
        return;
    }

    if (password.value !== confirmPassword.value) {
        console.log("Las contraseñas no coinciden");
        return;
    }

    const payload = {
        "name": name.value,
        "email": email.value,
        "password": password.value
    }

    try {
        const response = await axios.post("http://localhost:3000/api/users/register", payload);
        console.log("Registro realizado con éxito");
    } catch (error) {
        console.error("Error en el register:", error);
    }
    
}
</script>

<template>
  <main>
    <form class="container" @submit.prevent="enviarDatos">
      <h1>Crear Cuenta</h1>
      <p>Ingresa tus datos para registrarte</p>
      <label for="name">Nombre:</label>
      <input type="text" id="name" v-model="name" placeholder="Tu Nombre" />

      <label for="email">Email:</label>
      <input type="email" id="email" v-model="email" placeholder="example@gmail.com" />

      <label for="password">Contraseña:</label>
      <input type="password" id="password" v-model="password" placeholder="••••••••••" />

      <label for="confirmPassword">Confirmar Contraseña:</label>
      <input type="password" id="confirmPassword" v-model="confirmPassword" placeholder="••••••••••" />

      <button type="submit">Registrarse</button>

      <div id="frase"></div>
      <p id="normal">
        ¿Ya tienes cuenta?
        <router-link to="/login" id="cambiar">Inicia Sesión</router-link>
      </p>
    </form>
  </main>
</template>