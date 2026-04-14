<script setup>
import { ref, onMounted, computed } from "vue";
import axios from "axios";
import NavBar from "../../components/NavBar.vue";
import Footer from "../../components/Footer.vue";
import ProductCard from "../../components/products/ProductCard.vue";

const favoritos = ref([]);
const cargando = ref(true);

const cargarFavoritos = async () => {
  try {
    cargando.value = true;
    const res = await axios.get('http://localhost:8080/api/products/favorites', {
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
    const res = await axios.post(`http://localhost:8080/api/products/${productId}/favorite`, {}, {
      headers: { Authorization: `Bearer ${localStorage.getItem('token')}` }
    });
    
    // Si el backend confirma que ya no es favorito, lo quitamos de la lista visual
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

<style scoped lang="scss">
@use "@/assets/scss/variables" as *;

.favoritos-contenedor {
  padding: 3rem 2rem;
  background: #f8fbff;
  min-height: 80vh;
  max-width: 1300px;
  margin: 0 auto;
}

.cabecera-seccion {
  margin-bottom: 3rem;
  text-align: left;

  .titulo-principal {
    font-size: 2.2rem;
    font-weight: 800;
    color: #1a202c;
    margin-bottom: 0.5rem;
  }

  .subtitulo {
    color: #718096;
    font-size: 1.1rem;
  }
}

.rejilla-favoritos {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 2.5rem;
  align-items: start;
}

.estado-vacio {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 5rem 1rem;
  text-align: center;

  .ilustracion {
    width: 120px;
    height: 120px;
    background: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 10px 25px rgba(0,0,0,0.05);
    margin-bottom: 2rem;
    color: #e2e8f0;

    svg { width: 60px; }
  }

  h3 { font-size: 1.5rem; color: #2d3748; margin-bottom: 1rem; }
  p { color: #718096; margin-bottom: 2rem; max-width: 400px; }

  .btn-explorar {
    background: $primary-blue;
    color: white;
    padding: 12px 30px;
    border-radius: 10px;
    font-weight: 700;
    text-decoration: none;
    transition: transform 0.2s;

    &:hover { transform: translateY(-3px); }
  }
}

.loader-contenedor {
  text-align: center;
  padding: 5rem;
  color: $primary-blue;
  font-weight: 600;
}
</style>