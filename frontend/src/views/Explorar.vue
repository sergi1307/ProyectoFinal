<script setup>
import { ref, onMounted, computed } from "vue";
import axios from "axios";
import NavBar from "../components/NavBar.vue";
import Footer from "../components/Footer.vue";
import ProductCard from "../components/products/ProductCard.vue";

const productos = ref([]);
const categorias = ref([
  { id: 1, name: "Cursos" },
  { id: 2, name: "eBooks" },
  { id: 3, name: "Software" },
  { id: 4, name: "Plantillas" },
  { id: 5, name: "Audio" },
  { id: 6, name: "Fotografía" },
  { id: 7, name: "Video" },
  { id: 8, name: "Fuentes" },
  { id: 9, name: "Otros" },
]);

const cargando = ref(true);
const mostrarFiltros = ref(false);

const opcionesOrden = [
  "Destacados",
  "Más populares",
  "Mejor calificados",
  "Precio: Menor a Mayor",
  "Precio: Mayor a Menor",
];
const ordenSeleccionado = ref("Destacados");
const categoriasSeleccionadas = ref([]);
const calificacionSeleccionada = ref("Todas las calificaciones");

const calificaciones = [
  { label: "Todas las calificaciones", value: "todas" },
  { label: "3+ estrellas", value: 3 },
  { label: "4+ estrellas", value: 4 },
  { label: "4.5+ estrellas", value: 4.5 },
];

const precioMin = ref(0);
const precioMax = ref(1000);
const maxTotal = 1000;

const estiloBarra = computed(() => {
  const dep1 = (precioMin.value / maxTotal) * 100;
  const dep2 = (precioMax.value / maxTotal) * 100;
  return {
    background: `linear-gradient(to right, #e2e8f0 ${dep1}%, #0097b2 ${dep1}%, #0097b2 ${dep2}%, #e2e8f0 ${dep2}%)`,
  };
});

const validarPrecios = () => {
  if (precioMin.value > precioMax.value - 50) {
    precioMin.value = precioMax.value - 50;
  }
};

const validarPreciosMax = () => {
  if (precioMax.value < precioMin.value + 50) {
    precioMax.value = precioMin.value + 50;
  }
};

const cargarDatos = async () => {
  try {
    cargando.value = true;
    
    const resProd = await axios.get('http://localhost:8080/api/products/index', {
      headers: { 
        Authorization: `Bearer ${localStorage.getItem('token')}` 
      }
    });
    
    productos.value = resProd.data;

    console.log("Productos recibidos (ya filtrados):", productos.value.length);

  } catch (error) {
    console.error("Error al cargar productos:", error);
  } finally {
    cargando.value = false;
  }
};

const toggleFavorito = async (productId) => {
  try {
    const res = await axios.post(
      `http://localhost:8080/api/products/${productId}/favorite`,
      {},
      {
        headers: { Authorization: `Bearer ${localStorage.getItem("token")}` },
      },
    );

    const producto = productos.value.find((p) => p.id === productId);
    if (producto) {
      producto.is_favorite = res.data.is_favorite;
    }
  } catch (error) {
    console.error("Error al procesar favorito:", error);
  }
};

const productosFiltrados = computed(() => {
  let resultado = [...productos.value];

  // Filtro por precio
  resultado = resultado.filter(
    (p) => p.price >= precioMin.value && p.price <= precioMax.value,
  );

  // Filtro por categorías
  if (categoriasSeleccionadas.value.length > 0) {
    resultado = resultado.filter((p) =>
      p.categories.some((cat) =>
        categoriasSeleccionadas.value.includes(cat.id),
      ),
    );
  }

  return resultado;
});

onMounted(cargarDatos);
</script>

<template>
  <NavBar></NavBar>
  <div class="explorar-contenedor">
    <header class="cabecera-explorar">
      <div class="info-titulo">
        <h2 class="titulo-seccion">Explorar Productos</h2>
        <p class="contador-productos">
          {{ productosFiltrados.length }} productos encontrados
        </p>
      </div>

      <button class="boton-filtros-disparador" @click="mostrarFiltros = true">
        <svg
          width="18"
          height="18"
          viewBox="0 0 24 24"
          fill="none"
          stroke="currentColor"
          stroke-width="2"
          stroke-linecap="round"
          stroke-linejoin="round"
        >
          <line x1="4" y1="21" x2="4" y2="14"></line>
          <line x1="4" y1="10" x2="4" y2="3"></line>
          <line x1="12" y1="21" x2="12" y2="12"></line>
          <line x1="12" y1="8" x2="12" y2="3"></line>
          <line x1="20" y1="21" x2="20" y2="16"></line>
          <line x1="20" y1="12" x2="20" y2="3"></line>
          <line x1="1" y1="14" x2="7" y2="14"></line>
          <line x1="9" y1="8" x2="15" y2="8"></line>
          <line x1="17" y1="16" x2="23" y2="16"></line>
        </svg>
        Filtros
      </button>
    </header>

    <main v-if="!cargando" class="rejilla-productos">
      <ProductCard
        v-for="producto in productosFiltrados"
        :key="producto.id"
        :producto="producto"
        :isFavorite="producto.is_favorite"
        @favorito="toggleFavorito"
        @ver="$router.push(`/producto/${$event}`)"
      />
    </main>

    <div
      v-if="mostrarFiltros"
      class="overlay-oscuro"
      @click="mostrarFiltros = false"
    ></div>

    <aside :class="['panel-lateral', { abierto: mostrarFiltros }]">
      <div class="panel-header">
        <div class="header-txt">
          <h3>Personalizar Filtros</h3>
          <p>Encuentra exactamente lo que buscas</p>
        </div>
        <button class="close-btn" @click="mostrarFiltros = false">
          &times;
        </button>
      </div>

      <div class="panel-scroll">
        <section class="seccion-f">
          <h4>Ordenar por</h4>
          <div v-for="opcion in opcionesOrden" :key="opcion" class="control-f">
            <input
              type="radio"
              :id="opcion"
              :value="opcion"
              v-model="ordenSeleccionado"
              name="orden"
            />
            <label :for="opcion">{{ opcion }}</label>
          </div>
        </section>

        <hr class="linea" />

        <section class="seccion-f">
          <h4>Categorías</h4>
          <div v-for="cat in categorias" :key="cat.id" class="control-f">
            <input
              type="checkbox"
              :id="'c-' + cat.id"
              :value="cat.id"
              v-model="categoriasSeleccionadas"
            />
            <label :for="'c-' + cat.id">{{ cat.name }}</label>
          </div>
        </section>

        <hr class="linea" />

        <section class="seccion-f">
          <h4>Rango de Precio</h4>
          <div class="contenedor-rango">
            <div class="barra-progreso" :style="estiloBarra"></div>
            <input
              type="range"
              v-model.number="precioMin"
              min="0"
              :max="maxTotal"
              step="10"
              @input="validarPrecios"
              class="input-rango"
            />
            <input
              type="range"
              v-model.number="precioMax"
              min="0"
              :max="maxTotal"
              step="10"
              @input="validarPreciosMax"
              class="input-rango"
            />
          </div>
          <div class="price-inputs">
            <span class="box">{{ precioMin }}€</span>
            <span class="separador">-</span>
            <span class="box">{{ precioMax }}€</span>
          </div>
        </section>

        <hr class="linea" />

        <section class="seccion-f">
          <h4>Calificación Mínima</h4>
          <div class="calif-box">
            <div
              v-for="c in calificaciones"
              :key="c.value"
              :class="[
                'calif-item',
                { active: calificacionSeleccionada === c.label },
              ]"
              @click="calificacionSeleccionada = c.label"
            >
              <span v-if="c.value !== 'todas'" class="stars-gold">★★★★☆</span>
              {{ c.label }}
            </div>
          </div>
        </section>
      </div>

      <div class="panel-footer">
        <button class="btn-ver-prod" @click="mostrarFiltros = false">
          Ver {{ productosFiltrados.length }} Productos
        </button>
      </div>
    </aside>
  </div>
  <Footer></Footer>
</template>

<style scoped>
/* Respetando tus estilos originales intactos */
main {
  align-items: baseline;
}

.explorar-contenedor, .favoritos-contenedor {
  /* Reducimos el padding superior de 2rem/3rem a 1rem */
  padding: 1rem 2rem 2rem 2rem; 
}

.rejilla-productos, .rejilla-favoritos {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); 
  /* Reducimos el espacio entre tarjetas de 2rem a 1.2rem */
  gap: 1.2rem; 
  max-width: 1200px;
  margin: 0 auto;
  /* Forzamos a que se peguen arriba */
  align-items: start; 
}

.cabecera-explorar {
  display: flex;
  justify-content: space-between;
  align-items: flex-end;
  margin-bottom: 2rem;
}

.titulo-seccion {
  font-size: 1.8rem;
  font-weight: 700;
  color: #313131;
}

.contador-productos {
  color: #6b7280;
  margin-top: 4px;
}

.boton-filtros-disparador {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 8px 18px;
  background-color: #0097b2;
  border: 1px solid rgba(0, 151, 178, 0.2);
  border-radius: 10px;
  color: white;
  font-size: 0.95rem;
  font-weight: 600;
  cursor: pointer;
}

.boton-filtros-disparador:hover {
  background-color: #00869e;
  border-color: white;
}

.boton-filtros-disparador svg {
  color: white;
}

.overlay-oscuro {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.4);
  z-index: 999;
}

.panel-lateral {
  position: fixed;
  top: 0;
  right: -420px;
  width: 400px;
  height: 100vh;
  background: white;
  z-index: 1000;
  transition: right 0.3s ease;
  display: flex;
  flex-direction: column;
  box-shadow: -10px 0 30px rgba(0, 0, 0, 0.1);
}

.panel-lateral.abierto {
  right: 0;
}

.panel-header {
  padding: 5px 25px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-bottom: 1px solid #f1f5f9;
}

.header-txt {
  padding: 10px 0;
  margin: 0;
}

.header-txt h3 {
  font-size: 1.3rem;
  font-weight: 800;
  margin-bottom: 10px;
  line-height: 1.1;
}

.header-txt p {
  color: #64748b;
  font-size: 0.9rem;
  margin: 0;
  line-height: 1.1;
}

.close-btn {
  margin: 0;
  padding: 0;
  line-height: 1;
  background: none;
  border: none;
  font-size: 2rem;
  cursor: pointer;
  color: #94a3b8;
}

.panel-scroll {
  flex: 1;
  overflow-y: auto;
  padding: 25px;
}

.seccion-f h4 {
  font-size: 1.1rem;
  margin-bottom: 15px;
  color: #1e293b;
}

.control-f {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 12px;
  cursor: pointer;
}

.control-f input {
  accent-color: #0097b2;
  width: 18px;
  height: 18px;
}

.linea {
  border: 0;
  border-top: 1px solid #f1f5f9;
  margin: 25px 0;
}

.contenedor-rango {
  position: relative;
  width: 100%;
  height: 30px;
  display: flex;
  align-items: center;
}

.barra-progreso {
  position: absolute;
  height: 6px;
  width: 100%;
  border-radius: 10px;
  z-index: 1;
}

.input-rango {
  position: absolute;
  width: 100%;
  height: 6px;
  appearance: none;
  background: none;
  pointer-events: none;
  z-index: 2;
  margin: 0;
}

.input-rango::-webkit-slider-thumb {
  appearance: none;
  height: 18px;
  width: 18px;
  border-radius: 50%;
  background: #ffffff;
  border: 2px solid #0097b2;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
  cursor: pointer;
  pointer-events: auto;
  transition: transform 0.1s;
}

.input-rango::-webkit-slider-thumb:active {
  transform: scale(1.2);
  background: #0097b2;
}

.input-rango::-moz-range-thumb {
  height: 18px;
  width: 18px;
  border-radius: 50%;
  background: #ffffff;
  border: 2px solid #0097b2;
  cursor: pointer;
  pointer-events: auto;
}

.price-inputs {
  display: flex;
  justify-content: space-between;
  margin-top: 10px;
}

.box {
  background: #f0fafd;
  color: #0097b2;
  padding: 4px 12px;
  border-radius: 6px;
  font-weight: 700;
  font-size: 0.9rem;
}

.calif-item {
  padding: 12px;
  border: 1px solid #e2e8f0;
  border-radius: 8px;
  margin-bottom: 8px;
  cursor: pointer;
  transition: all 0.2s;
}

.calif-item.active {
  background: #eff6ff;
  border-color: #0097b2;
  color: #0097b2;
  font-weight: 600;
}

.stars-gold {
  color: #facc15;
  margin-right: 8px;
}

.panel-footer {
  padding: 20px 25px;
  border-top: 1px solid #f1f5f9;
}

.btn-ver-prod {
  width: 100%;
  padding: 14px;
  background: #0097b2;
  color: white;
  border: none;
  border-radius: 8px;
  font-weight: 700;
  cursor: pointer;
}

.btn-ver-prod:hover {
  background: #00869e;
}
</style>
