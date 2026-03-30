<script setup>
import { ref, onMounted, onUnmounted, watch } from 'vue';
import { useI18n } from 'vue-i18n';
import { useRoute, useRouter } from 'vue-router';

const { locale } = useI18n();
const route = useRoute();
const router = useRouter();

const userRole = ref(null);
const showDropdown = ref(false);

const actualizarEstado = () => {
  const userRaw = localStorage.getItem('usuario');
  if (userRaw) {
    const userData = JSON.parse(userRaw);
    userRole.value = userData.role; 
    console.log("NavBar dice: El rol es", userRole.value);
  }
};

onMounted(() => {
  actualizarEstado();
  window.addEventListener('storage', actualizarEstado);
  window.addEventListener('login-success', actualizarEstado);
});

onUnmounted(() => {
  window.removeEventListener('storage', actualizarEstado);
  window.removeEventListener('login-success', actualizarEstado);
});

watch(() => route.path, () => {
  actualizarEstado();
}, { immediate: true });

const seleccionarIdioma = (code) => {
  locale.value = code;
  localStorage.setItem('idioma', code);
  showDropdown.value = false;
};

const logout = () => {
  localStorage.removeItem('usuario');
  localStorage.removeItem('token');
  userRole.value = null;
  router.push('/login');
};
</script>

<template>
  <nav class="navbar">
    <div class="navbar-container">
      
      <div class="navbar-izquierda">
        <router-link to="/" class="logo-container">
          <img src="../assets/logos/Logo_gr.png" alt="Logo" class="imagen-logo" />
        </router-link>

        <template v-if="userRole === 'user'">
          <div class="nav-links">
            <router-link to="/explorar" class="nav-item">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="nav-icon"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
              Explorar
            </router-link>
            <router-link to="/mis-productos" class="nav-item">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="nav-icon"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
              Mis Productos
            </router-link>
          </div>
        </template>
      </div>

      <div class="navbar-centro" v-if="userRole === 'user'">
        <div class="search-wrapper">
          <svg viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="2" class="search-icon"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
          <input type="text" placeholder="Buscar productos..." class="search-input" />
        </div>
      </div>

      <div class="navbar-derecha">
        <div class="idioma-wrapper">
          <div class="iconos-container" @click.stop="showDropdown = !showDropdown">
            <img src="../assets/iconos/idioma.png" class="icono-idioma" />
            <img src="../assets/iconos/idioma-hover.png" class="icono-hover" />
          </div>
          <transition name="fade">
            <div v-if="showDropdown" class="dropdown-idiomas">
              <div @click="seleccionarIdioma('esp')" class="opcion-idioma">ESP</div>
              <div @click="seleccionarIdioma('val')" class="opcion-idioma">VAL</div>
              <div @click="seleccionarIdioma('eng')" class="opcion-idioma">ENG</div>
            </div>
          </transition>
        </div>

        <div class="acciones">
          <template v-if="userRole === 'admin'">
            <div class="cartel admin">👤 {{ $t('nb.adm') }}</div>
            <button @click="logout" class="boton-salir">{{ $t('nb.out') }}</button>
          </template>

          <template v-else-if="userRole === 'user'">
            <router-link to="/vender" class="boton-vender">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="plus-icon"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
              Vender
            </router-link>
            
            <div class="icon-actions-group">
              <router-link to="/favoritos" class="action-btn"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg></router-link>
              <router-link to="/carrito" class="action-btn"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg></router-link>
              <router-link to="/perfil" class="action-btn"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg></router-link>
              <button @click="logout" class="action-btn logout-btn"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg></button>
            </div>
          </template>

          <template v-else>
            <router-link to="/login" class="enlace">{{ $t('nb.login') }}</router-link>
            <router-link to="/register" class="boton-registro">{{ $t('nb.reg') }}</router-link>
          </template>
        </div>
      </div>
    </div>
  </nav>
</template>

<style scoped>
.navbar {
  background-color: #ffffff;
  width: 100%;
  height: 80px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
  position: sticky;
  top: 0;
  z-index: 100;
  font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
  box-sizing: border-box;
}

.navbar-container {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0 2rem;
  height: 100%;
  max-width: 1440px;
  margin: 0 auto;
  box-sizing: border-box;
  gap: 2rem;
}

.navbar-izquierda {
  display: flex;
  align-items: center;
  gap: 2.5rem;
}

.logo-container {
  display: flex;
  align-items: center;
  text-decoration: none;
}

.imagen-logo {
  height: 45px;
  width: auto;
  object-fit: contain;
}

.nav-links {
  display: flex;
  align-items: center;
  gap: 1.5rem;
}

.nav-item {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  text-decoration: none;
  color: #111827;
  font-weight: 500;
  font-size: 0.95rem;
  padding: 0.5rem 0.8rem;
  border-radius: 6px;
  transition: all 0.2s ease;
}

.nav-item:hover {
  background-color: #2563eb;
  color: #ffffff;
}

.nav-icon {
  width: 20px;
  height: 20px;
}

.navbar-centro {
  flex-grow: 1;
  display: flex;
  justify-content: center;
  max-width: 600px;
}

.search-wrapper {
  display: flex;
  align-items: center;
  background-color: #f3f4f6;
  border-radius: 8px;
  padding: 0.6rem 1.2rem;
  width: 100%;
  gap: 0.8rem;
  transition: background-color 0.2s, box-shadow 0.2s;
}

.search-wrapper:focus-within {
  background-color: #ffffff;
  box-shadow: 0 0 0 2px #2563eb;
}

.search-icon {
  width: 18px;
  height: 18px;
}

.search-input {
  border: none;
  background: transparent;
  outline: none;
  width: 100%;
  font-size: 0.95rem;
  color: #374151;
}

.search-input::placeholder {
  color: #6b7280;
}

.navbar-derecha {
  display: flex;
  align-items: center;
  gap: 1.5rem;
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
  right: -20px;
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
  gap: 1.5rem;
}

.boton-vender {
  display: flex;
  align-items: center;
  gap: 0.4rem;
  background-color: #2563eb;
  color: white;
  text-decoration: none;
  padding: 0.6rem 1.2rem;
  border-radius: 6px;
  font-weight: 600;
  font-size: 0.95rem;
  transition: background-color 0.2s;
}

.boton-vender:hover {
  background-color: #1d4ed8;
}

.plus-icon {
  width: 18px;
  height: 18px;
}

.icon-actions-group {
  display: flex;
  align-items: center;
  gap: 1.2rem;
}

.action-btn {
  background: transparent;
  border: none;
  color: #111827;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  text-decoration: none;
  padding: 0.5rem;
  border-radius: 6px;
  transition: all 0.2s ease;
}

.action-btn:hover {
  background-color: #2563eb;
  color: #ffffff;
}

.action-btn svg {
  width: 24px;
  height: 24px;
}

.logout-btn {
  color: #ef4444;
  margin-left: 0.5rem;
  padding: 0.5rem;
  border-radius: 6px;
  transition: all 0.2s ease;
}

.logout-btn:hover {
  background-color: #fee2e2;
  color: #ef4444;
}

.enlace {
  text-decoration: none;
  color: #1f2937;
  font-weight: 500;
  font-size: 1rem;
  padding: 0.5rem;
}

.enlace:hover {
  color: #2563eb;
}

.boton-registro {
  text-decoration: none;
  background-color: #2563eb;
  color: white;
  padding: 0.6rem 1.5rem;
  border-radius: 6px;
  font-weight: 600;
  font-size: 0.95rem;
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

.boton-salir {
  background-color: transparent;
  color: #ef4444;
  border: 1px solid #ef4444;
  padding: 0.5rem 1rem;
  border-radius: 6px;
  cursor: pointer;
  font-weight: 600;
}

.fade-enter-active, .fade-leave-active {
  transition: opacity 0.2s, transform 0.2s;
}

.fade-enter-from, .fade-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}
</style>