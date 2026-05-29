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
const email = ref("");
const password = ref("");
const errorMsg = ref("");

const enviarDatos = async () => {
  errorMsg.value = "";
  if (!email.value || !password.value) return;

  try {
    const response = await api.post("/auth/login", {
      email: email.value,
      password: password.value,
    });

    const data = response.data; 
    console.log("Respuesta de la API:", data);
    const userData = data.usuario || data.user;

    if (data.token && userData) {
      localStorage.setItem('token', data.token);
      localStorage.setItem('usuario', JSON.stringify(userData));

      try {
        const resCart = await api.get("/cart", {
          headers: { Authorization: `Bearer ${data.token}` },
        });

        console.log(resCart);
        
        const carritoRecuperado = resCart.data.map(item => ({
          id: item.product_id,
          quantity: item.quantity
        }));
        
        localStorage.setItem("carrito", JSON.stringify(carritoRecuperado));
      } catch (cartError) {
        console.error("No se pudo recuperar el carrito al iniciar sesión:", cartError);
        localStorage.setItem("carrito", JSON.stringify([]));
      }

      window.dispatchEvent(new Event('login-success'));
      window.dispatchEvent(new Event('storage'));
      window.dispatchEvent(new CustomEvent('cart-updated'));

      if (userData.role === 'admin') {
        router.push('/admin');
      } else if (userData.primer_login === true || userData.primer_login === 1) {
        router.push('/suscripciones');
      } else {
        router.push('/general');
      }
    } else {
      console.error("El backend no devolvió el token o el objeto de usuario. Respuesta:", data);
      errorMsg.value = "Error en la respuesta del servidor.";
    }
  } catch (error) {
    if (error.response) {
      if (error.response.status === 401) {
        errorMsg.value = t('auth.errorLogin');
      } 
      else if (error.response.status === 403) {
        if (error.response.data.unverified) {
          router.push({ path: '/verificar-email', query: { email: email.value } });
        } else {
          errorMsg.value = error.response.data.error;
        }
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

      <router-link class="resetPassword" to="/verificar-email?mode=reset">{{ $t('auth.forgotPassword') }}</router-link>

      <button type="submit">{{ $t('auth.titleLogin') }}</button>
      
      <p id="normal">
        {{ $t('auth.noAccount') }} 
        <router-link to="/register" id="cambiar">{{ $t('navbar.register') }}</router-link>
      </p>
    </form>
  </main>
  <Footer></Footer>
</template>