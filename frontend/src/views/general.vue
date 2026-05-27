<script setup>
import { ref, computed, onMounted } from "vue";
import { useI18n } from "vue-i18n";
import api from "@/services/api";
import NavBar from "@/components/NavBar.vue";
import Footer from "@/components/Footer.vue";
import ProductCard from "@/components/products/ProductCard.vue";
import { useToast } from '@/composables/useToast';

const { addToast } = useToast();
const { t } = useI18n();

const token = localStorage.getItem("token");
const authHeader = token ? { Authorization: `Bearer ${token}` } : {};

const categorias = computed(() => [
  t("categorias.cursos"),
  t("categorias.ebooks"),
  t("categorias.software"),
  t("categorias.plantillas"),
  t("categorias.audio"),
  t("categorias.fotografia"),
  t("categorias.video"),
  t("categorias.fuentes"),
  t("categorias.otros"),
]);

const productosDestacados = ref([]);
const productosTendencia = ref([]);
const cargandoDestacados = ref(true);
const cargandoTendencias = ref(true);

const cargarDatosHome = async () => {
  try {
    const res = await api.get(`/products/destacados`, { headers: authHeader });
    productosDestacados.value = res.data.data || res.data;
  } catch (e) {
    console.error("Error destacados:", e);
  } finally {
    cargandoDestacados.value = false;
  }

  try {
    const res = await api.get(`/products/tendencias`, { headers: authHeader });
    productosTendencia.value = res.data;
  } catch (e) {
    console.error("Error tendencias:", e);
  } finally {
    cargandoTendencias.value = false;
  }
};

onMounted(cargarDatosHome);

const manejarFavorito = async (producto) => {
  if (!token) {
    addToast(t('explorar.toast.authRequired'), 'error');
    return;
  }
  try {
    const res = await api.post(
      `/products/${producto.id}/favorite`,
      {},
      { headers: authHeader },
    );
    producto.is_favorite = res.data.is_favorite;
  } catch (e) {
    console.error("Error al toggle favorito:", e);
  }
};

const manejarAgregado = (id) => {
  console.log(`Producto ${id} añadido al carrito`);
};
</script>

<template>
  <NavBar></NavBar>
  <main class="contenedor-general">
    <section class="hero-explorar">
      <div class="etiqueta-brillante">
        <img src="../assets/iconos/brillos.png" class="icono-brillo" />
        <span>{{ $t("explorar.discover") }}</span>
      </div>

      <h1>
        {{ $t("explorar.heroTitle").split(",")[0] }},<br />
        <span class="azul-degradado">{{
          $t("explorar.heroTitle").split(",")[1]
        }}</span>
      </h1>

      <p class="subtitulo-hero">{{ $t("home.heroSub") }}</p>

      <div class="fila-categorias">
        <button
          v-for="categoria in categorias"
          :key="categoria"
          class="boton-categoria"
        >
          {{ categoria }}
        </button>
      </div>
    </section>

    <section class="seccion-destacados">
      <div class="contenido-destacados">
        <div class="cabecera-destacados">
          <div class="titulos-seccion">
            <h2>{{ $t('explorar.destacados') }}</h2>
            <p class="descripcion-seccion">{{ $t("home.whyNexusSub") }}</p>
          </div>
          <router-link to="/explorar" class="enlace-ver-todo"
            >{{ $t("explorar.seeAll") }} &rarr;</router-link
          >
        </div>

        <div v-if="cargandoDestacados" class="loader-placeholder">
          {{ $t("comun.cargando") }}...
        </div>

        <div v-else class="rejilla-comun-nexus">
          <ProductCard
            v-for="producto in productosDestacados"
            :key="producto.id"
            :producto="producto"
            :isFavorite="producto.is_favorite"
            :modoPublico="true"
            @favorito="manejarFavorito(producto)"
            @agregado="manejarAgregado"
            @ver="$router.push(`/producto/${$event}`)"
          />
        </div>
      </div>
    </section>

    <section class="anuncio-creadores">
      <div class="caja-azul-anuncio">
        <h2>{{ $t("explorar.creators") }}</h2>
        <p>{{ $t("home.readySub") }}</p>
        <router-link to="/vender" class="boton-crear-producto">
          {{ $t("explorar.createProduct") }} <span>&rarr;</span>
        </router-link>
      </div>
    </section>

    <section class="seccion-tendencias">
      <div class="contenido-tendencias">
        <div class="cabecera-tendencias">
          <div class="titulos-seccion">
            <h2>{{ $t("explorar.trends") }}</h2>
            <p class="descripcion-seccion">{{ $t("home.exploreCatSub") }}</p>
          </div>
        </div>

        <div v-if="cargandoTendencias" class="loader-placeholder">
          {{ $t("comun.cargando") }}...
        </div>

        <div v-else class="rejilla-comun-nexus">
          <ProductCard
            v-for="producto in productosTendencia"
            :key="'trend-' + producto.id"
            :producto="producto"
            :isFavorite="producto.is_favorite"
            :modoPublico="true"
            @favorito="manejarFavorito(producto)"
            @agregado="manejarAgregado"
            @ver="$router.push(`/producto/${$event}`)"
          />
        </div>
      </div>
    </section>
  </main>
  <Footer></Footer>
</template>

<style scoped>
.loader-placeholder {
  text-align: center;
  padding: 4rem;
  color: #94a3b8;
  font-style: italic;
}

.contenedor-general section {
  margin-bottom: 6rem;
}

.rejilla-comun-nexus {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 2.5rem;
  width: 100%;
}

.cabecera-destacados, .cabecera-tendencias {
  display: flex;
  justify-content: space-between;
  align-items: flex-end;
  margin-bottom: 2.5rem;
}

.titulos-seccion h2 {
  font-size: 2rem;
  font-weight: 800;
  color: #1e293b;
  margin-bottom: 0.5rem;
}

.descripcion-seccion {
  color: #64748b;
  font-size: 1.1rem;
}

.enlace-ver-todo {
  color: #0097b2;
  font-weight: 600;
  text-decoration: none;
  transition: color 0.2s;
}

.enlace-ver-todo:hover {
  color: #007a8f;
}

@media (max-width: 1024px) {
  .rejilla-comun-nexus {
    grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    gap: 1.5rem;
  }
}

@media (max-width: 640px) {
  .rejilla-comun-nexus {
    grid-template-columns: 1fr;
    gap: 2rem;
  }
  
  .cabecera-destacados {
    flex-direction: column;
    align-items: flex-start;
    gap: 1rem;
  }
}
</style>