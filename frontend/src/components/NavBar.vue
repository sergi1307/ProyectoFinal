<script setup>
import { ref, onMounted, onUnmounted, watch } from 'vue';
import { useI18n } from 'vue-i18n';
import { useRoute, useRouter } from 'vue-router';

const { locale, t } = useI18n();
const route = useRoute();
const router = useRouter();

const userRole = ref(null);
const usuarioActual = ref(null);
const showDropdown = ref(false);
const isMenuOpen = ref(false);
const cartCount = ref(0);

const actualizarEstado = () => {
  const userRaw = localStorage.getItem('usuario');
  const token = localStorage.getItem('token');
  if (userRaw) {
    const userData = JSON.parse(userRaw);
    userRole.value = userData.role;
    usuarioActual.value = userData; 
  }else{
    userRole.value = null;
    usuarioActual.value = null;
  }
};

const obtenerRutaAvatar = (ruta) => {
  if (!ruta || ruta === '0' || ruta === 0) return null;
  const limpia = ruta.replace(/^storage\//, '').replace(/^\//, '');
  return `http://localhost:8080/storage/${limpia}`;
};

const obtenerRutaInicio = () => {
  if (!userRole.value) return '/';
  if (userRole.value === 'admin') return '/';
  return '/general';
};
const actualizarCarrito = () => {
  const cartData = localStorage.getItem('carrito');
  if (cartData) {
    try {
      const items = JSON.parse(cartData);
      cartCount.value = items.reduce((total, item) => total + (item.quantity || 1), 0);
    } catch (error) {
      cartCount.value = 0;
    }
  } else {
    cartCount.value = 0;
  }
}

const toggleMenu = () => {
  isMenuOpen.value = !isMenuOpen.value;
  if (isMenuOpen.value) document.body.style.overflow = 'hidden';
  else document.body.style.overflow = '';
};

const cerrarMenu = () => {
  isMenuOpen.value = false;
  document.body.style.overflow = '';
};

onMounted(() => {
  actualizarEstado();
  actualizarCarrito();
  window.addEventListener('cart-updated', actualizarCarrito);
  window.addEventListener('storage', actualizarEstado);
  window.addEventListener('login-success', actualizarEstado);
  window.addEventListener('perfil-actualizado', actualizarEstado);
});

onUnmounted(() => {
  window.removeEventListener('storage', actualizarEstado);
  window.removeEventListener('login-success', actualizarEstado);
  window.removeEventListener('perfil-actualizado', actualizarEstado);
  document.body.style.overflow = '';
});

watch(() => route.path, () => {
  actualizarEstado();
  cerrarMenu();
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
  cerrarMenu();
  router.push('/login');
};
</script>

<template>
  <nav class="navbar">
    <div class="navbar-container">
      
      <div class="navbar-izquierda">
        <button class="burger-btn" @click="toggleMenu" aria-label="Menu">
          <svg v-if="!isMenuOpen" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
          <svg v-else viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </button>

        <router-link :to="obtenerRutaInicio()" class="logo-container">
          <img src="../assets/logos/Logo_gr.png" alt="Logo" class="imagen-logo" />
        </router-link>

        <template v-if="userRole">
          <div class="nav-links hide-on-mobile">
            <router-link v-if="userRole !== 'admin'" to="/explorar" class="nav-item">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="nav-icon"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
              {{ $t('explorar.featured') }}
            </router-link>
            
            <router-link v-if="userRole === 'user'" to="/mis-productos" class="nav-item">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="nav-icon"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
              {{ $t('navbar.products') }}
            </router-link>

            <router-link v-if="userRole === 'user'" to="/pedidos" class="nav-item">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="nav-icon">
                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"></path>
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <path d="M16 10a4 4 0 0 1-8 0"></path>
              </svg>
             Pedidos
            </router-link>

            <router-link v-if="userRole === 'user'" to="/suscripciones" class="nav-item">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="nav-icon">
                <circle cx="12" cy="8" r="7"></circle>
                <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline>
              </svg>
              Suscripciones
            </router-link>
            
            <template v-if="userRole === 'admin'">
              <router-link to="/admin" class="nav-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="nav-icon"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                {{ $t('navbar.admin') }}
              </router-link>
            </template>
          </div>
        </template>
      </div>

      <div class="navbar-derecha">
        <div class="idioma-wrapper hide-on-mobile">
          <div class="action-btn" @click.stop="showDropdown = !showDropdown">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M2 5h12"></path>
              <path d="M7 2h1"></path>
              <path d="m4 14 6-6 2-3"></path>
              <path d="m5 8 6 6"></path>
              <path d="m22 22-5-10-5 10"></path>
              <path d="M14 18h6"></path>
            </svg>
            <span style="margin-left: 5px; font-size: 0.8rem; font-weight: bold;">{{ locale.toUpperCase() }}</span>
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
          <template v-if="userRole">
            <router-link v-if="userRole === 'user'" to="/vender" class="boton-vender hide-on-mobile">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="plus-icon"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
              {{ $t('navbar.sell') }}
            </router-link>
            
            <div class="icon-actions-group">
              <template v-if="userRole === 'user'">
                <router-link to="/favoritos" class="action-btn cart-btn hide-on-mobile"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg></router-link>
                <router-link to="/carrito" class="action-btn">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="9" cy="21" r="1"></circle>
                    <circle cx="20" cy="21" r="1"></circle>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                  </svg>
                  <span v-if="cartCount > 0" class="cart-badge">{{ cartCount }}</span>
                </router-link>
              </template>
              
              <router-link to="/perfil" class="action-btn profile-link hide-on-mobile">
                <template v-if="usuarioActual?.avatar && obtenerRutaAvatar(usuarioActual.avatar)">
                  <img 
                    :src="obtenerRutaAvatar(usuarioActual.avatar)" 
                    alt="Perfil" 
                    class="avatar-circle"
                  />
                </template>
                
                <template v-else>
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="nav-icon">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                  </svg>
                </template>
              </router-link>
              
              <button @click="logout" class="action-btn logout-btn hide-on-mobile">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
              </button>
            </div>
          </template>

          <template v-else>
            <router-link to="/login" class="enlace hide-on-mobile">{{ $t('navbar.login') }}</router-link>
            <router-link to="/register" class="boton-registro hide-on-mobile">{{ $t('navbar.register') }}</router-link>
          </template>
        </div>
      </div>
    </div>

    <transition name="slide-menu">
      <div v-if="isMenuOpen" class="mobile-drawer">
        <div class="mobile-drawer-content">
          
          <div v-if="userRole" class="mobile-user-profile">
            <img v-if="usuarioActual?.avatar && obtenerRutaAvatar(usuarioActual.avatar)" :src="obtenerRutaAvatar(usuarioActual.avatar)" class="mobile-avatar" />
            <div v-else class="mobile-avatar-placeholder">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="24" height="24">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                <circle cx="12" cy="7" r="4"></circle>
              </svg>
            </div>
            <div class="mobile-user-info">
              <span class="mobile-user-name">{{ usuarioActual?.name || 'Usuario' }}</span>
              <span class="mobile-user-email">{{ usuarioActual?.email || '' }}</span>
            </div>
          </div>

          <div class="mobile-lang-group">
            <button @click="seleccionarIdioma('esp')" :class="{'active': locale === 'esp'}">ESP</button>
            <button @click="seleccionarIdioma('val')" :class="{'active': locale === 'val'}">VAL</button>
            <button @click="seleccionarIdioma('eng')" :class="{'active': locale === 'eng'}">ENG</button>
          </div>

          <div v-if="userRole" class="mobile-nav-links">
            <router-link v-if="userRole !== 'admin'" to="/explorar" class="mobile-nav-item">{{ $t('explorar.featured') }}</router-link>
            <router-link v-if="userRole === 'user'" to="/mis-productos" class="mobile-nav-item">{{ $t('navbar.products') }}</router-link>
            <router-link v-if="userRole === 'user'" to="/pedidos" class="mobile-nav-item">Pedidos</router-link>
            <router-link v-if="userRole === 'user'" to="/favoritos" class="mobile-nav-item">Favoritos</router-link>
            <router-link to="/perfil" class="mobile-nav-item">Mi Perfil</router-link>

            <div class="mobile-divider"></div>

            <router-link v-if="userRole === 'user'" to="/vender" class="mobile-nav-item mobile-btn-vender">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
              {{ $t('navbar.sell') }}
            </router-link>
            
            <router-link v-if="userRole === 'admin'" to="/admin" class="mobile-nav-item mobile-btn-admin">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
              {{ $t('navbar.admin') }}
            </router-link>

            <button @click="logout" class="mobile-nav-item mobile-logout-btn">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
              Cerrar sesión
            </button>
          </div>

          <div v-else class="mobile-nav-links">
            <router-link to="/login" class="mobile-nav-item">{{ $t('navbar.login') }}</router-link>
            <div class="mobile-divider"></div>
            <div class="mobile-action-wrapper">
              <router-link to="/register" class="mobile-btn-registro">{{ $t('navbar.register') }}</router-link>
            </div>
          </div>

        </div>
      </div>
    </transition>

    <div v-if="isMenuOpen" class="mobile-overlay" @click="cerrarMenu"></div>
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
  font-family: "Segoe UI", Helvetica, Arial, sans-serif;
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
}

.nav-item:hover {
  background-color: #0097b2;
  color: white;
}

.nav-item:hover svg{
  color: white;
}

.nav-icon {
  width: 20px;
  height: 20px;
}

.navbar-derecha {
  display: flex;
  align-items: center;
  gap: 1.5rem;
}

.idioma-wrapper {
  position: relative;
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
  padding: 0.4rem;
}

.opcion-idioma {
  padding: 0.8rem 1rem;
  color: #374151;
  font-weight: 600;
  font-size: 0.9rem;
  cursor: pointer;
  text-align: center;
  transition: all 0.2s ease;
  border-radius: 4px;
  margin: 0.2rem;
}

.opcion-idioma:hover {
  background-color: #0097b2;
  color: #ffffff;
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
  background-color: #0097b2;
  color: white;
  text-decoration: none;
  padding: 0.6rem 1.2rem;
  border-radius: 6px;
  font-weight: 600;
  font-size: 0.95rem;
  transition: background-color 0.2s;
}

.boton-vender:hover {
  background-color: #00869e;
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
  position: relative;
}

.action-btn:hover {
  background-color: #0097b2;
  color: #ffffff;
}

.action-btn svg {
  width: 24px;
  height: 24px;
}

.cart-btn {
  position: relative;
}

.cart-badge {
  position: absolute;
  top: -2px;
  right: -2px;
  background-color: #ef4444;
  color: white;
  font-size: 0.7rem;
  font-weight: bold;
  min-width: 18px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 2px;
  border: 2px solid #ffffff;
  line-height: 1;
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
  padding: 0.6rem 1.5rem;
  border-radius: 6px;
  display: flex;
  align-items: center;
  transition: all 0.2s ease;
}

.enlace:hover {
  background-color: #0097b2;
  color: #ffffff;
}

.boton-registro {
  text-decoration: none;
  background-color: #0097b2;
  color: white;
  padding: 0.6rem 1.5rem;
  border-radius: 6px;
  font-weight: 600;
  font-size: 0.95rem;
  transition: background-color 0.2s;
}

.boton-registro:hover {
  background-color: #00869e;
}

.profile-link {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 40px;
  height: 40px;
  padding: 0;
  overflow: hidden;
  border-radius: 50%;
  transition: border-color 0.3s;
}

.profile-link:hover {
  border-color: #00869e;
}

.profile-link:hover svg {
  stroke: #ffffff;
}

.avatar-circle {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.nav-icon {
  width: 24px;
  height: 24px;
  color:black;
}

.fade-enter-active, .fade-leave-active {
  transition: opacity 0.2s, transform 0.2s;
}

.fade-enter-from, .fade-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}

.burger-btn {
  display: none;
  background: none;
  border: none;
  color: #111827;
  cursor: pointer;
  padding: 5px;
}
.burger-btn svg {
  width: 28px;
  height: 28px;
}

.mobile-drawer {
  position: fixed;
  top: 80px; 
  left: 0;
  width: min(85vw, 320px);
  height: calc(100vh - 80px);
  background-color: #ffffff;
  z-index: 99;
  box-shadow: 4px 0 20px rgba(0, 0, 0, 0.08);
  overflow-y: auto;
  display: flex;
  flex-direction: column;
}

.mobile-drawer-content {
  display: flex;
  flex-direction: column;
  height: 100%;
  padding: 0;
}

.mobile-user-profile {
  display: flex;
  align-items: center;
  gap: 14px;
  padding: 24px;
  background-color: #f8fafc;
  border-bottom: 1px solid #f1f5f9;
}
.mobile-avatar {
  width: 48px;
  height: 48px;
  border-radius: 50%;
  object-fit: cover;
  border: 2px solid #ffffff;
  box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}
.mobile-avatar-placeholder {
  width: 48px;
  height: 48px;
  border-radius: 50%;
  background-color: #e2e8f0;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #64748b;
}
.mobile-user-info {
  display: flex;
  flex-direction: column;
  overflow: hidden;
}
.mobile-user-name {
  font-weight: 700;
  font-size: 1.05rem;
  color: #0f172a;
  white-space: nowrap;
  text-overflow: ellipsis;
  overflow: hidden;
}
.mobile-user-email {
  font-size: 0.85rem;
  color: #64748b;
  white-space: nowrap;
  text-overflow: ellipsis;
  overflow: hidden;
}

.mobile-lang-group {
  display: flex;
  gap: 10px;
  padding: 20px 24px;
  border-bottom: 1px solid #f1f5f9;
}
.mobile-lang-group button {
  flex: 1;
  padding: 10px;
  border: 1px solid #e2e8f0;
  background: transparent;
  border-radius: 8px;
  font-weight: 600;
  font-size: 0.85rem;
  color: #475569;
  cursor: pointer;
  transition: all 0.2s;
}
.mobile-lang-group button.active {
  background-color: #0097b2;
  color: white;
  border-color: #0097b2;
}

.mobile-nav-links {
  display: flex;
  flex-direction: column;
  padding: 12px 0;
  flex-grow: 1;
}
.mobile-nav-item {
  text-decoration: none;
  color: #334155;
  font-weight: 500;
  font-size: 1rem;
  padding: 14px 24px;
  display: flex;
  align-items: center;
  gap: 12px;
  transition: all 0.2s ease;
  border-left: 3px solid transparent;
}
.mobile-nav-item:hover {
  background-color: #f8fafc;
  color: #0097b2;
  border-left-color: #0097b2;
}
.mobile-divider {
  height: 1px;
  background-color: #f1f5f9;
  margin: 8px 24px;
}

.mobile-btn-vender, .mobile-btn-admin {
  color: #0097b2;
  font-weight: 600;
}
.mobile-action-wrapper {
  padding: 12px 24px;
}
.mobile-btn-registro {
  display: block;
  background-color: #0097b2;
  color: white;
  text-align: center;
  padding: 14px;
  border-radius: 8px;
  font-weight: 600;
  text-decoration: none;
  transition: background-color 0.2s;
}
.mobile-btn-registro:hover {
  background-color: #00869e;
}
.mobile-logout-btn {
  background: none;
  border: none;
  color: #ef4444;
  text-align: left;
  cursor: pointer;
  width: 100%;
  margin-bottom: 24px;
  font-size: 1rem;
}
.mobile-logout-btn:hover {
  background-color: #fef2f2;
  border-left-color: #ef4444;
}

.mobile-overlay {
  position: fixed;
  top: 80px;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.4);
  z-index: 98;
  backdrop-filter: blur(2px);
}

.slide-menu-enter-active, .slide-menu-leave-active {
  transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
.slide-menu-enter-from, .slide-menu-leave-to {
  transform: translateX(-100%);
}


@media (max-width: 1024px) {
  .burger-btn {
    display: block;
  }
  
  .hide-on-mobile {
    display: none !important;
  }

  .navbar-container {
    padding: 0 1.5rem;
    gap: 1rem;
  }
}

@media (max-width: 480px) {
  .imagen-logo {
    height: 35px;
  }
  .navbar-container {
    padding: 0 1rem;
  }
}
</style>