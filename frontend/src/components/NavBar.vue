<script setup>
import { ref, onMounted } from 'vue';
import { useI18n } from 'vue-i18n';

const { locale } = useI18n();
const userRole = ref(null);
const showDropdown = ref(false);

onMounted(() => {
  const user = localStorage.getItem('usuario');
  if (user) {
    const userData = JSON.parse(user);
    userRole.value = userData.rol;
  }
});

const seleccionarIdioma = (code) => {
  locale.value = code;
  localStorage.setItem('idioma', code);
  showDropdown.value = false;
};

const logout = () => {
  localStorage.removeItem('usuario');
  localStorage.removeItem('token');
  userRole.value = null;
};
</script>

<template>
  <nav class="navbar">
    <div class="navbar-container">
      <router-link to="/" class="logo-container">
        <img src="../assets/logos/Logo_gr.png" alt="Logo" class="imagen-logo" />
      </router-link>

      <div class="navbar-derecha">
        <div class="idioma-wrapper">
          <div class="iconos-container" @click.stop="showDropdown = !showDropdown">
            <img src="../assets/iconos/idioma.png" class="icono-idioma" aria-hidden="true"/>
            <img src="../assets/iconos/idioma-hover.png" class="icono-hover" aria-hidden="true">
          </div>
          
          <transition name="fade">
            <div v-if="showDropdown" class="dropdown-idiomas">
              <div @click="seleccionarIdioma('esp')" class="opcion-idioma">ESP</div>
              <div @click="seleccionarIdioma('val')" class="opcion-idioma">VAL</div>
              <div @click="seleccionarIdioma('eng')" class="opcion-idioma">ENG</div>
            </div>
          </transition>
        </div>

        <div v-if="userRole === 'admin'" class="acciones">
          <div class="cartel admin">👤 {{ $t('nb.adm') }}</div>
          <button @click="logout" class="boton-salir">{{ $t('nb.out') }}</button>
        </div>

        <div v-else-if="userRole === 'user'" class="acciones">
          <div class="cartel user">👤 {{ $t('nb.usr') }}</div>
          <button @click="logout" class="boton-salir">{{ $t('nb.out') }}</button>
        </div>

        <div v-else class="acciones">
          <router-link to="/login" class="enlace">{{ $t('nb.login') }}</router-link>
          <router-link to="/register" class="boton">{{ $t('nb.reg') }}</router-link>
        </div>
      </div>
    </div>
  </nav>
</template>

<style scoped>
.navbar {
  background-color: #ffffff;
  width: 100%;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
  position: sticky;
  top: 0;
  z-index: 100;
  font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
}

.navbar-container {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem 2rem;
  max-width: 1200px;
  margin: 0 auto;
}

.logo-container {
  display: flex;
  align-items: center;
  text-decoration: none;
}

.imagen-logo {
  height: 60px;
  width: auto;
  object-fit: contain;
  display: block;
}

.navbar-derecha {
  display: flex;
  align-items: center;
  gap: 2rem;
}

.idioma-wrapper {
  position: relative;
  cursor: pointer;
}

.iconos-container {
  display: flex;
  align-items: center;
  height: 25px;
}

.icono-idioma, .icono-hover {
  height: 25px;
  width: auto;
}

.icono-hover {
  display: none;
}

.idioma-wrapper:hover .icono-idioma {
  display: none;
}
.idioma-wrapper:hover .icono-hover {
  display: block;
}

.dropdown-idiomas {
  position: absolute;
  top: 130%;
  right: 0;
  background-color: white;
  min-width: 100px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
  border-radius: 8px;
  border: 1px solid #f3f4f6;
  z-index: 1000;
  overflow: hidden;
}

.opcion-idioma {
  padding: 0.8rem 1rem;
  color: #374151;
  font-weight: 600;
  font-size: 0.9rem;
  cursor: pointer;
  text-align: center;
  transition: background-color 0.2s;
}

.opcion-idioma:hover {
  background-color: #f9fafb;
  color: #2563eb;
}

.acciones {
  display: flex;
  align-items: center;
  gap: 2rem;
}

.enlace {
  text-decoration: none;
  color: #1f2937;
  font-weight: 500;
  font-size: 1rem;
  transition: all 0.4s;
  padding: 0.5rem;
}

.enlace:hover {
  color: #2563eb;
  background-color: rgba(37, 99, 235, 0.1);
  border-radius: 6px;
}

.boton {
  text-decoration: none;
  background-color: #2563eb;
  color: white;
  padding: 0.6rem 1.5rem;
  border-radius: 6px;
  font-weight: 600;
  font-size: 0.95rem;
  transition: background-color 0.2s;
}

.boton:hover {
  background-color: #1d4ed8;
}

.cartel {
  padding: 0.5rem 1rem;
  border-radius: 8px;
  font-weight: 600;
  font-size: 0.9rem;
}

.cartel.admin {
  background-color: #fef08a;
  color: #854d0e;
  border: 1px solid #facc15;
}

.cartel.user {
  background-color: #e0f2fe;
  color: #075985;
  border: 1px solid #7dd3fc;
}

.boton-salir {
  background-color: transparent;
  color: #ef4444;
  border: 1px solid #ef4444;
  padding: 0.5rem 1rem;
  border-radius: 6px;
  cursor: pointer;
  font-weight: 600;
  transition: all 0.2s;
}

.boton-salir:hover {
  background-color: #ef4444;
  color: white;
}

.fade-enter-active, .fade-leave-active {
  transition: opacity 0.2s, transform 0.2s;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}
</style>