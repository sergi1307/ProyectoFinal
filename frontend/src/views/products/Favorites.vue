<script setup>
import { ref, onMounted, computed } from "vue";
import api from "@/services/api";
import NavBar from "../../components/NavBar.vue";
import Footer from "../../components/Footer.vue";
import ProductCard from "../../components/products/ProductCard.vue";

const favoritos = ref([]);
const cargando = ref(true);

const cargarFavoritos = async () => {
  try {
    cargando.value = true;
    const res = await api.get('/products/favorites', {
      headers: { Authorization: `Bearer ${localStorage.getItem('token')}` }
    });
    favoritos.value = res.data;
  } catch (error) {
    console.error("Error al cargar favoritos:", error);
  } finally {
    cargando.value = false;
  }
};

const manejarFavorito = async (productId) => {
  try {
    const res = await api.post(`/products/${productId}/favorite`, {}, {
      headers: { Authorization: `Bearer ${localStorage.getItem('token')}` }
    });
    
    if (!res.data.is_favorite) {
      favoritos.value = favoritos.value.filter(p => p.id !== productId);
    }
  } catch (error) {
    console.error("Error al actualizar favorito:", error);
  }
};

onMounted(cargarFavoritos);
</script>

<template>
  <NavBar />
  <div class="favoritos-contenedor">
    <header class="cabecera-seccion">
      <div class="info-titulo">
        <h2 class="titulo-principal">Mis Favoritos</h2>
        <p class="subtitulo">
          Tienes {{ favoritos.length }} productos guardados en tu lista de deseos
        </p>
      </div>
    </header>

    <main v-if="!cargando" class="contenido-principal">
      <div v-if="favoritos.length > 0" class="rejilla-favoritos">
        <ProductCard 
          v-for="producto in favoritos"
          :key="producto.id"
          :producto="producto"
          :isFavorite="true" 
          @favorito="manejarFavorito"
          @ver="$router.push(`/producto/${$event}`)"
        />
      </div>

      <div v-else class="estado-vacio">
        <div class="ilustracion">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
          </svg>
        </div>
        <h3>Aún no tienes favoritos</h3>
        <p>Explora nuestro marketplace y guarda los productos que más te gusten.</p>
        <router-link to="/explorar" class="btn-explorar">Ir a Explorar</router-link>
      </div>
    </main>

    <div v-else class="loader-contenedor">
      <p>Cargando tu colección...</p>
    </div>
  </div>
  <Footer />
</template>