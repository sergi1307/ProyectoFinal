<script setup>
import { ref, onMounted, computed } from "vue";
import axios from "axios";
import NavBar from "./NavBar.vue";
import Footer from "./Footer.vue";

const productos = ref([]);
const categorias = ref([
  { id: 1, name: 'Cursos' },
  { id: 2, name: 'eBooks' },
  { id: 3, name: 'Software' },
  { id: 4, name: 'Plantillas' },
  { id: 5, name: 'Audio' },
  { id: 6, name: 'Fotografía' },
  { id: 7, name: 'Video' },
  { id: 8, name: 'Fuentes' },
  { id: 9, name: 'Otros' }
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
    background: `linear-gradient(to right, #e2e8f0 ${dep1}%, #0097b2 ${dep1}%, #0097b2 ${dep2}%, #e2e8f0 ${dep2}%)`
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
    const resProd = await axios.get('http://localhost:8080/api/products');
    productos.value = resProd.data.map(p => ({
      ...p,
      isFavorite: false 
    }));
  } catch (error) {
    console.error("Error al cargar productos:", error);
  } finally {
    cargando.value = false;
  }
};

const toggleFavorito = (producto) => {
  producto.isFavorite = !producto.isFavorite;
};

const productosFiltrados = computed(() => {
  return productos.value;
});

const obtenerImagen = (url) =>
  url ? `http://localhost:8080/storage/${url}` : "/placeholder.png";

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
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
      <div
        v-for="producto in productosFiltrados"
        :key="producto.id"
        class="tarjeta-producto"
      >
        <div class="contenedor-imagen">
          <img :src="obtenerImagen(producto.image_url)" :alt="producto.title" />
          <span class="etiqueta-categoria" v-if="producto.categories?.length">{{
            producto.categories[0].name
          }}</span>
            <button 
            class="boton-favorito" 
            :class="{ activo: producto.isFavorite }" 
            @click="toggleFavorito(producto)">
            <svg viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
            </svg>
          </button>
        </div>
        <div class="info-producto">
          <h3>{{ producto.title }}</h3>
          <p class="desc">{{ producto.description }}</p>
          
          <div class="meta">
            <span class="estrellas">★ {{ producto.rating || "5.0" }}</span>
            <span class="reviews">({{ producto.reviews_count || 0 }} reseñas)</span>
          </div>

          <p class="autor">
            Por <span>{{ producto.user?.name || 'Vendedor Nexus' }}</span>
          </p>
        </div>
      </div>
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
main {
 align-items: baseline;
}

.explorar-contenedor {
  padding: 2rem;
  background: #f8fbff;
  min-height: 100vh;
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
  background-color:#00869e;
  border-color: white;
}

.boton-filtros-disparador svg {
  color: white;
}

.rejilla-productos {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 2rem;
}

.tarjeta-producto {
  background: white;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
}

.contenedor-imagen {
  position: relative;
  height: 220px;
}

.contenedor-imagen img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.etiqueta-categoria {
  position: absolute;
  top: 12px;
  left: 12px;
  background: #0097b2;
  color: white;
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 0.75rem;
  font-weight: 700;
}

.boton-favorito {
  position: absolute;
  top: 8px;
  right: 8px;
  background: white;
  border: none;
  border-radius: 50%;
  width: 36px;
  height: 36px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  opacity: 0;
  transition: all 0.2s ease;
  z-index: 2;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  margin: 0 !important;
}

.boton-favorito svg {
  width: 18px;
  height: 18px;
  color: #111827;
  fill: transparent;
  transition:
    color 0.2s,
    fill 0.2s;
}

.boton-favorito,
.boton-favorito {
  opacity: 1;
}

.boton-favorito.activo,
.boton-favorito:hover {
  opacity: 1;
}

.boton-favorito.activo svg,
.boton-favorito:hover svg {
  color: #ef4444;
  fill: #ef4444;
}

.boton-favorito:not(.activo) svg {
  color: #111827;
  fill: transparent;
}

.info-producto {
  padding: 1.2rem;
}

.info-producto h3 {
  font-size: 1.1rem;
  margin-bottom: 8px;
}

.desc {
  color: #6b7280;
  font-size: 0.9rem;
  margin-bottom: 12px;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.meta {
  display: flex;
  align-items: center;
  gap: 8px;
  margin-bottom: 10px;
}

.estrellas {
  font-weight: 700;
  color: #313131;
}

.reviews {
  color: #9ca3af;
  font-size: 0.85rem;
}

.autor {
  font-size: 0.85rem;
  color: #6b7280;
}

.autor span {
  font-weight: 600;
  color: #313131;
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
  box-shadow: 0 2px 4px rgba(0,0,0,0.2);
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