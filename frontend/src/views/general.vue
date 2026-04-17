<script setup>
import { ref, computed, onMounted } from "vue";
import { useI18n } from 'vue-i18n';
import axios from "axios";
import NavBar from "@/components/NavBar.vue";
import Footer from "@/components/Footer.vue";
import ProductCard from "@/components/products/ProductCard.vue";

const { t } = useI18n();

const categorias = computed(() => [
  t('categorias.cursos'),
  t('categorias.ebooks'),
  t('categorias.software'),
  t('categorias.plantillas'),
  t('categorias.audio'),
  t('categorias.fotografia'),
  t('categorias.video'),
  t('categorias.fuentes'),
  t('categorias.otros'),
]);

const productosDestacados = ref([]);
const cargando = ref(true);
const favoritos = ref([]);

const obtenerProductosDestacados = async () => {
  try {
    cargando.value = true;
    const response = await axios.get('http://localhost:8080/api/products');
    
    productosDestacados.value = response.data; 
  } catch (error) {
    console.error("Error al cargar destacados desde la BD:", error);
  } finally {
    cargando.value = false;
  }
};

onMounted(() => {
  obtenerProductosDestacados();
});

const toggleFavorito = (id) => {
  if (favoritos.value.includes(id)) {
    favoritos.value = favoritos.value.filter(favId => favId !== id);
  } else {
    favoritos.value.push(id);
  }
};

const manejarAgregado = (id) => {
  console.log(`Producto ${id} agregado desde la vista principal`);
};

const favoritoTendencia1 = ref(false);
const favoritoTendencia2 = ref(false);
</script>

<template>
  <NavBar></NavBar>
  <main class="contenedor-general">
    
    <section class="hero-explorar">
      <div class="etiqueta-brillante">
        <img src="../assets/iconos/brillos.png" class="icono-brillo"/>
        <span>{{ $t('explorar.discover') }}</span>
      </div>

      <h1>
        {{ $t('explorar.heroTitle').split(',')[0] }},<br />
        <span class="azul-degradado">{{ $t('explorar.heroTitle').split(',')[1] }}</span>
      </h1>

      <p class="subtitulo-hero">
        {{ $t('home.heroSub') }} 
      </p>

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
            <h2>Destacados</h2>
            <p class="descripcion-seccion">{{ $t('home.whyNexusSub') }}</p>
          </div>
          <router-link to="/explorar" class="enlace-ver-todo">{{ $t('explorar.seeAll') }} &rarr;</router-link>
        </div>

        <div v-if="cargando" style="text-align: center; padding: 2rem; color: #666;">
          Cargando productos destacados...
        </div>

        <div class="cuadricula-productos">
          <ProductCard
            v-for="producto in productosDestacados"
            :key="producto.id"
            :producto="producto"
            :isFavorite="favoritos.includes(producto.id)"
            :modoPublico="true"  @favorito="toggleFavorito"
            @agregado="manejarAgregado"
          />
        </div>
      </div>
    </section>

    <section class="anuncio-creadores">
      <div class="caja-azul-anuncio">
        <h2>{{ $t('explorar.creators') }}</h2>
        <p>{{ $t('home.readySub') }}</p>
        <router-link to="/vender" class="boton-crear-producto">
          {{ $t('explorar.createProduct') }} <span>&rarr;</span>
        </router-link>
      </div>
    </section>

    <section class="seccion-tendencias">
      <div class="contenido-tendencias">
        <div class="cabecera-tendencias">
          <div class="titulos-seccion">
            <h2>{{ $t('explorar.trends') }}</h2>
            <p class="descripcion-seccion">{{ $t('home.exploreCatSub') }}</p>
          </div>
        </div>

        <div class="cuadricula-tendencias">
          <div class="tarjeta-tendencia">
            <div class="contenedor-imagen-tendencia">
              <img src="../assets/iconos/plantillas.jpg" alt="Instagram" />
              <button class="boton-favorito" :class="{ activo: favoritoTendencia1 }" @click="favoritoTendencia1 = !favoritoTendencia1">
                <svg viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
              </button>
            </div>
            <div class="informacion-tendencia">
              <h3>{{ $t('productos.packInsta') }}</h3>
              <div class="pie-tarjeta">
                <div class="valoracion"><span>⭐</span> 4.5</div>
                <div class="precio-producto">19.99€</div>
              </div>
            </div>
          </div>

          <div class="tarjeta-tendencia">
            <div class="contenedor-imagen-tendencia">
              <img src="../assets/iconos/presets.jpg" alt="Presets" />
              <button class="boton-favorito" :class="{ activo: favoritoTendencia2 }" @click="favoritoTendencia2 = !favoritoTendencia2">
                <svg viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
              </button>
            </div>
            <div class="informacion-tendencia">
              <h3>{{ $t('productos.presetsLightroom') }}</h3>
              <div class="pie-tarjeta">
                <div class="valoracion"><span>⭐</span> 4.8</div>
                <div class="precio-producto">29.99€</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
  <Footer></Footer>
</template>