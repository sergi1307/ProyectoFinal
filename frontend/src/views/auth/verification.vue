<script setup>
import { ref, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import api from "@/services/api";
import "../../assets/css/auth.css";
import { useToast } from '@/composables/useToast';
import NavBar from "@/components/NavBar.vue";
import Footer from "@/components/Footer.vue";

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
    await api.post(`/auth/${endpoint}`, {
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
      await api.post(`/auth/verificar-email`, {
        email: email.value,
        code: code.value,
      });
      addToast(t('toasts.emailVerified'), 'success');
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
    await api.post(`/auth/reset-password`, {
      email: email.value,
      code: code.value,
      password: newPassword.value,
    });
    addToast(t('toasts.passwordUpdated'), 'success');
    router.push("/login");
  } catch (error) {
    errorMsg.value =
      "El código ha expirado o es incorrecto. Vuelve a intentarlo.";
    paso.value = 1;
  }
};

const reenviarCodigo = async () => {
  await solicitarCodigo();
  addToast(t('toasts.codeSent'), 'success');
};
</script>

<template>
  <NavBar></NavBar>
  <main>
    <router-link to="/login" class="inicio">{{ $t('verificacion.volverLogin') }}</router-link>

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
        {{ mode === "reset" ? $t('verificacion.tituloRecuperar') : $t('verificacion.tituloVerificar') }}
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
          {{ $t('verificacion.descPaso1') }}
        </p>
        <label for="email">{{ $t('verificacion.lblEmail') }}</label>
        <input
          type="email"
          id="email"
          v-model="email"
          :placeholder="$t('verificacion.phEmail')"
          required
        />

        <button type="submit">{{ $t('verificacion.btnEnviar') }}</button>
      </template>

      <template v-if="paso === 2">
        <p style="text-align: center; margin-bottom: 1.5rem">
          {{ $t('verificacion.descPaso2') }}
          <strong>{{ email }}</strong>
        </p>

        <label for="code">{{ $t('verificacion.lblCodigo') }}</label>
        <input
          type="text"
          id="code"
          v-model="code"
          placeholder="000000"
          maxlength="6"
          required
        />

        <button type="submit">
          {{ mode === "reset" ? $t('verificacion.btnSiguiente') : $t('verificacion.btnVerificar') }}
        </button>

        <p id="normal">
          {{ $t('verificacion.preguntaCodigo') }}
          <span id="cambiar" @click="reenviarCodigo" style="cursor: pointer"
            >{{ $t('verificacion.btnReenviar') }}</span
          >
        </p>
      </template>

      <template v-if="paso === 3">
        <p style="text-align: center; margin-bottom: 1.5rem">
          {{ $t('verificacion.descPaso3') }}
        </p>

        <label for="newPassword">{{ $t('verificacion.lblNuevaPass') }}</label>
        <input
          type="password"
          id="newPassword"
          v-model="newPassword"
          placeholder="••••••••••"
          required
        />

        <label for="confirmPassword">{{ $t('verificacion.lblConfirmaPass') }}</label>
        <input
          type="password"
          id="confirmPassword"
          v-model="confirmPassword"
          placeholder="••••••••••"
          required
        />

        <button type="submit">{{ $t('verificacion.btnGuardarPass') }}</button>
      </template>
    </form>
  </main>
  <Footer></Footer>
</template>
