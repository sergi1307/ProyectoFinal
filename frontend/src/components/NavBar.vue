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
  return `${import.meta.env.VITE_STORAGE_BASE_URL || 'https://nexus-isaria.es/storage'}/${limpia}`;
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
             {{ $t('navbar.orders') }}
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
              
              <router-link 
                to="/perfil" 
                class="action-btn profile-link hide-on-mobile"
                :class="usuarioActual?.plan && usuarioActual.plan !== 'Básico' ? 'plan-' + usuarioActual.plan.toLowerCase() : ''"
              >
                <template v-if="usuarioActual?.avatar && obtenerRutaAvatar(usuarioActual.avatar)">
                  <img 
                    :src="obtenerRutaAvatar(usuarioActual.avatar)" 
                    alt="Perfil" 
                    class="avatar-circle"
                  />
                </template>
                <template v-else>
                  <div class="avatar-circle svg-wrapper">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="nav-icon">
                      <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                      <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                  </div>
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
            <div 
              class="mobile-avatar-wrapper"
              :class="usuarioActual?.plan && usuarioActual.plan !== 'Básico' ? 'plan-' + usuarioActual.plan.toLowerCase() : ''"
            >
              <img v-if="usuarioActual?.avatar && obtenerRutaAvatar(usuarioActual.avatar)" :src="obtenerRutaAvatar(usuarioActual.avatar)" class="mobile-avatar" />
              <div v-else class="mobile-avatar-placeholder">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="24" height="24">
                  <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                  <circle cx="12" cy="7" r="4"></circle>
                </svg>
              </div>
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