<script setup>
import { ref, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import axios from "axios";
import "../../assets/css/auth.css";

const route = useRoute();
const router = useRouter();

// Variables de estado
const email = ref("");
const code = ref("");
const newPassword = ref("");
const confirmPassword = ref("");
const errorMsg = ref("");

// Control del flujo
const mode = ref("verify");
const paso = ref(1); // 1: Email, 2: Código, 3: Nueva contraseña

onMounted(() => {
  mode.value = route.query.mode || "verify";
  email.value = route.query.email || "";

  // Si ya venimos con un email (ej. desde el registro), saltamos al paso 2
  if (email.value) {
    paso.value = 2;
  }
});

// PASO 1: Enviar correo
const solicitarCodigo = async () => {
  errorMsg.value = "";
  if (!email.value) {
    errorMsg.value = "Por favor, introduce tu correo electrónico.";
    return;
  }

  try {
    const endpoint =
      mode.value === "reset" ? "forgot-password" : "reenviar-codigo";
    await axios.post(`http://localhost:8080/api/auth/${endpoint}`, {
      email: email.value,
    });

    paso.value = 2; // Avanzamos al paso del código
  } catch (error) {
    errorMsg.value = "Hubo un error al intentar enviar el correo.";
  }
};

// PASO 2: Validar código en frontend y decidir qué hacer
const procesarCodigo = async () => {
  errorMsg.value = "";
  if (!code.value || code.value.length !== 6) {
    errorMsg.value = "El código debe tener 6 dígitos exactos.";
    return;
  }

  if (mode.value === "verify") {
    // Si estamos verificando cuenta nueva, aquí termina el flujo y llamamos a la API
    try {
      await axios.post(`http://localhost:8080/api/auth/verificar-email`, {
        email: email.value,
        code: code.value,
      });
      alert("Email verificado con éxito. Ya puedes iniciar sesión.");
      router.push("/login");
    } catch (error) {
      errorMsg.value = "Código incorrecto o expirado.";
    }
  } else {
    // Si estamos reseteando contraseña, pasamos al Paso 3 para pedir las claves
    paso.value = 3;
  }
};

// PASO 3: Enviar la nueva contraseña (Solo modo Reset)
const cambiarPassword = async () => {
  errorMsg.value = "";

  if (newPassword.value !== confirmPassword.value) {
    errorMsg.value = "Las contraseñas no coinciden.";
    return;
  }

  if (newPassword.value.length < 8) {
    errorMsg.value = "La contraseña debe tener al menos 8 caracteres.";
    return;
  }

  try {
    await axios.post(`http://localhost:8080/api/auth/reset-password`, {
      email: email.value,
      code: code.value,
      password: newPassword.value,
    });
    alert(
      "Contraseña actualizada con éxito. Inicia sesión con tu nueva clave.",
    );
    router.push("/login");
  } catch (error) {
    errorMsg.value =
      "El código ha expirado o es incorrecto. Vuelve a intentarlo.";
    paso.value = 1; // Le devolvemos al inicio si falló en el backend
  }
};

// Reutilizamos la función de solicitar código para el botón de "Reenviar"
const reenviarCodigo = async () => {
  await solicitarCodigo();
  alert("Te hemos enviado un nuevo código.");
};
</script>

<template>
  <main>
    <router-link to="/login" class="inicio">← Volver al login</router-link>

    <form
      class="container"
      @submit.prevent="
        paso === 1
          ? solicitarCodigo()
          : paso === 2
            ? procesarCodigo()
            : cambiarPassword()
      "
    >
      <h1>
        {{ mode === "reset" ? "Recuperar Contraseña" : "Verificar Email" }}
      </h1>

      <p
        v-if="errorMsg"
        style="
          color: #ef4444;
          text-align: center;
          margin-bottom: 1rem;
          font-weight: 600;
        "
      >
        {{ errorMsg }}
      </p>

      <template v-if="paso === 1">
        <p style="text-align: center; margin-bottom: 1.5rem">
          Introduce tu correo electrónico para recibir el código de 6 dígitos.
        </p>
        <label for="email">Correo electrónico:</label>
        <input
          type="email"
          id="email"
          v-model="email"
          placeholder="tu@email.com"
          required
        />

        <button type="submit">Enviar código</button>
      </template>

      <template v-if="paso === 2">
        <p style="text-align: center; margin-bottom: 1.5rem">
          Introduce el código de 6 dígitos que te hemos enviado a
          <strong>{{ email }}</strong>
        </p>

        <label for="code">Código de verificación:</label>
        <input
          type="text"
          id="code"
          v-model="code"
          placeholder="000000"
          maxlength="6"
          required
        />

        <button type="submit">
          {{ mode === "reset" ? "Siguiente paso" : "Verificar cuenta" }}
        </button>

        <p id="normal">
          ¿No has recibido el código?
          <span id="cambiar" @click="reenviarCodigo" style="cursor: pointer"
            >Reenviar código</span
          >
        </p>
      </template>

      <template v-if="paso === 3">
        <p style="text-align: center; margin-bottom: 1.5rem">
          Crea una nueva contraseña para tu cuenta.
        </p>

        <label for="newPassword">Nueva contraseña:</label>
        <input
          type="password"
          id="newPassword"
          v-model="newPassword"
          placeholder="••••••••••"
          required
        />

        <label for="confirmPassword">Confirmar contraseña:</label>
        <input
          type="password"
          id="confirmPassword"
          v-model="confirmPassword"
          placeholder="••••••••••"
          required
        />

        <button type="submit">Guardar y cambiar contraseña</button>
      </template>
    </form>
  </main>
</template>
