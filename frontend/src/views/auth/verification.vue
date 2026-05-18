<script setup>
import { ref, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import axios from "axios";
import "../../assets/css/auth.css";
import { useToast } from '@/composables/useToast';

const { addToast } = useToast();
const route = useRoute();
const router = useRouter();
const email = ref("");
const code = ref("");
const newPassword = ref("");
const confirmPassword = ref("");
const errorMsg = ref("");
const mode = ref("verify");
const paso = ref(1);

onMounted(() => {
  mode.value = route.query.mode || "verify";
  email.value = route.query.email || "";

  if (email.value) {
    paso.value = 2;
  }
});

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

    paso.value = 2;
  } catch (error) {
    errorMsg.value = "Hubo un error al intentar enviar el correo.";
  }
};

const procesarCodigo = async () => {
  errorMsg.value = "";
  if (!code.value || code.value.length !== 6) {
    errorMsg.value = "El código debe tener 6 dígitos exactos.";
    return;
  }

  if (mode.value === "verify") {
    try {
      await axios.post(`http://localhost:8080/api/auth/verificar-email`, {
        email: email.value,
        code: code.value,
      });
      addToast("Email verificado con éxito. Ya puedes iniciar sesión.", 'success');
      router.push("/login");
    } catch (error) {
      errorMsg.value = "Código incorrecto o expirado.";
    }
  } else {
    paso.value = 3;
  }
};

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
    addToast("Contraseña actualizada con éxito. Inicia sesión con tu nueva clave.", 'success');
    router.push("/login");
  } catch (error) {
    errorMsg.value =
      "El código ha expirado o es incorrecto. Vuelve a intentarlo.";
    paso.value = 1;
  }
};

const reenviarCodigo = async () => {
  await solicitarCodigo();
  addToast("Te hemos enviado un nuevo código.", 'success');
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
