<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';

const userRole = ref(null);
const router = useRouter();

onMounted(() => {
  const user = localStorage.getItem('usuario');
  
  if (user) {
    const userData = JSON.parse(user);
    userRole.value = userData.rol;
  }
});

const logout = () => {
  localStorage.removeItem('usuario');
  localStorage.removeItem('token');
  userRole.value = null;
  router.push('/');
};
</script>

<template>
  <nav class="navbar">
    <div class="navbar-container">
      <router-link to="/" class="logo-container">
        <img
          src="../assets/logos/Logo_gr.png"
          alt="Logo Nexus"
          class="imagen-logo"
        />
      </router-link>

      <div v-if="userRole === 'admin'" class="acciones">
        <div class="cartel admin">👤 Admin</div>
        <button @click="logout" class="boton-salir">Cerrar Sesión</button>
      </div>

      <div v-else-if="userRole === 'user'" class="acciones">
        <div class="cartel user">👤 Usuario</div>
        <button @click="logout" class="boton-salir">Cerrar Sesión</button>
      </div>

      <div v-else class="acciones">
        <router-link to="/login" class="enlace">Iniciar Sesión</router-link>
        <router-link to="/register" class="boton">Registrarse</router-link>
      </div>
    </div>
  </nav>
</template>

<style scoped>
.navbar {
  background-color: #ffffff;
  width: 100%;
  text-align: center;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
  position: sticky;
  top: 0;
  z-index: 100;
  font-family:
    -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial,
    sans-serif;
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
  border-radius: 6px;
}

.enlace:hover {
  color: #2563eb;
  padding: 0.6rem;
  background-color: rgba(37, 99, 235, 0.1);
  border: 1px solid #2563eb;
}

.boton {
  text-decoration: none;
  background-color: #2563eb;
  color: white;
  border: none;
  padding: 0.6rem 1.5rem;
  border-radius: 6px;
  font-weight: 600;
  font-size: 0.95rem;
  cursor: pointer;
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
</style>