<script setup>
import { ref, onMounted, computed, watch, onUnmounted } from "vue";
import api from "@/services/api";
import NavBar from "../components/NavBar.vue";
import Footer from "../components/Footer.vue";
import ProductCard from "../components/products/ProductCard.vue";

const paginaActual = ref(1);
const totalPaginas = ref(1);
const productos = ref([]);
const categorias = ref([]);
const cargando = ref(true);
const mostrarFiltros = ref(false);
const usuarioActual = ref(null);
const searchQuery = ref("");
const ordenSeleccionado = ref("Destacados");
const opcionesOrden = [
  "Destacados",
  "Más populares",
  "Mejor calificados",
  "Precio: Menor a Mayor",
  "Precio: Mayor a Menor",
];

const precioMin = ref(0);
const precioMax = ref(1000);
const maxTotal = 1000;

const calificacionSeleccionada = ref("Todas las calificaciones");
const calificaciones = [
  { label: "Todas las calificaciones", value: "todas" },
  { label: "3+ estrellas", value: 3 },
  { label: "4+ estrellas", value: 4 },
  { label: "4.5+ estrellas", value: 4.5 },
];

const categoriasSeleccionadas = ref([]);
const busquedaCategoria = ref("");
const mostrarDropdownCategorias = ref(false);

const estiloBarra = computed(() => {
  const dep1 = (precioMin.value / maxTotal) * 100;
  const dep2 = (precioMax.value / maxTotal) * 100;
  return {
    background: `linear-gradient(to right, #e2e8f0 ${dep1}%, #0097b2 ${dep1}%, #0097b2 ${dep2}%, #e2e8f0 ${dep2}%)`,
  };
});

const categoriasDisponibles = computed(() => {
  return categorias.value.filter(
    (cat) => !categoriasSeleccionadas.value.includes(cat.id),
  );
});

const categoriasFiltradasPorBusqueda = computed(() => {
  const query = busquedaCategoria.value.toLowerCase();
  return categoriasDisponibles.value.filter((cat) =>
    cat.name.toLowerCase().includes(query),
  );
});

const productosFiltrados = computed(() => {
  return productos.value
    .filter((p) => {
      const idDuenoProducto = Number(p.user_id || p.user?.id);
      const idMio = usuarioActual.value ? Number(usuarioActual.value.id) : null;
      const esMio = idMio && idDuenoProducto === idMio;
      if (esMio) return false;

      const query = searchQuery.value.toLowerCase();
      const cumpleNombre =
        !query ||
        (p.title || "").toLowerCase().includes(query) ||
        (p.description || "").toLowerCase().includes(query);

      const cumpleCategoria =
        categoriasSeleccionadas.value.length === 0 ||
        p.categories?.some((c) => categoriasSeleccionadas.value.includes(c.id));

      const cumplePrecio =
        p.price >= precioMin.value && p.price <= precioMax.value;

      const ratingMinimo = calificaciones.find(
        (c) => c.label === calificacionSeleccionada.value,
      )?.value;
      const cumpleRating =
        ratingMinimo === "todas" || Number(p.rating || 0) >= ratingMinimo;

      return cumpleNombre && cumpleCategoria && cumplePrecio && cumpleRating;
    })
    .sort((a, b) => {
      if (ordenSeleccionado.value === "Precio: Menor a Mayor")
        return a.price - b.price;
      if (ordenSeleccionado.value === "Precio: Mayor a Menor")
        return b.price - a.price;
      if (ordenSeleccionado.value === "Mejor calificados")
        return b.rating - a.rating;
      return 0;
    });
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

const limpiarFiltros = () => {
  ordenSeleccionado.value = "Destacados";
  categoriasSeleccionadas.value = [];
  calificacionSeleccionada.value = "Todas las calificaciones";
  precioMin.value = 0;
  precioMax.value = 1000;
  searchQuery.value = "";
  console.log("Filtros reseteados. Orden actual:", ordenSeleccionado.value);
  mostrarFiltros.value = false;
};

const seleccionarCategoria = (categoria) => {
  if (categoria && !categoriasSeleccionadas.value.includes(categoria.id)) {
    categoriasSeleccionadas.value.push(categoria.id);
  }
  busquedaCategoria.value = "";
  mostrarDropdownCategorias.value = false;
};

const añadirCategoria = (event) => {
  const id = Number(event.target.value);
  if (id && !categoriasSeleccionadas.value.includes(id)) {
    categoriasSeleccionadas.value.push(id);
  }
  event.target.value = "";
};

const quitarCategoria = (id) => {
  categoriasSeleccionadas.value = categoriasSeleccionadas.value.filter(
    (catId) => catId !== id,
  );
};

const nombreCategoria = (id) => {
  return categorias.value.find((c) => c.id === id)?.name || "";
};

const obtenerCategorias = async () => {
  try {
    const res = await api.get(`/categories`);
    categorias.value = res.data;
  } catch (error) {
    console.error("Error al cargar las categorias:", error);
  }
};

const cargarDatos = async () => {
  try {
    cargando.value = true;
    const token = localStorage.getItem("token");

    const params = {
      page: paginaActual.value,
      search: searchQuery.value,
      categories: categoriasSeleccionadas.value.join(","),
      min_price: precioMin.value,
      max_price: precioMax.value,
      min_rating: calificaciones.find(
        (c) => c.label === calificacionSeleccionada.value,
      )?.value,
      order: ordenSeleccionado.value,
    }

    const resProd = await api.get(
      "/products/index",
      {
        params,
        headers: { Authorization: `Bearer ${localStorage.getItem("token")}` },
      },
    );

    productos.value = resProd.data.data;
    totalPaginas.value = resProd.data.last_page;
    paginaActual.value = resProd.data.current_page;
    console.log("Productos recibidos (ya filtrados):", productos.value.length);
  } catch (error) {
    console.error("Error al cargar productos:", error);
  } finally {
    cargando.value = false;
  }
};

watch([searchQuery, categoriasSeleccionadas, precioMin, precioMax, calificacionSeleccionada, ordenSeleccionado], () => {
  paginaActual.value = 1;
  cargarDatos();
});

const cambiarPagina = (p) => {
  paginaActual.value = p;
  cargarDatos();
  window.scrollTo(0, 0);
}

const toggleFavorito = async (productId) => {
  try {
    const res = await api.post(
      `/products/${productId}/favorite`,
      {},
      { headers: { Authorization: `Bearer ${localStorage.getItem("token")}` } },
    );

    const producto = productos.value.find((p) => p.id === productId);
    if (producto) {
      producto.is_favorite = res.data.is_favorite;
    }
  } catch (error) {
    console.error("Error al procesar favorito:", error);
  }
};

const handleAgregado = (productId) => {
  alert("¡Producto añadido al carrito correctamente!");
};

onMounted(async () => {
  await cargarDatos();
  await obtenerCategorias();

  window.addEventListener("scroll", manejarScroll);
});

watch(mostrarFiltros, (estaAbierto) => {
  if (estaAbierto) {
    document.body.style.overflow = 'hidden';
  } else {
    document.body.style.overflow = '';
  }
});

onUnmounted(() => {
  document.body.style.overflow = '';

  window.removeEventListener("scroll", manejarScroll);
});

// ==========================================
// 8. BOTÓN FLOTANTE (Scroll)
// ==========================================
const mostrarBotonFlotante = ref(false);

const manejarScroll = () => {
  if (window.scrollY > 250) {
    mostrarBotonFlotante.value = true;
  } else {
    mostrarBotonFlotante.value = false;
  }
}
</script>

<template>
  <NavBar></NavBar>
  <div class="explorar-page">
    <div class="explorar-contenedor">
      <header class="cabecera-explorar">
        <div class="info-titulo">
          <h1 class="titulo-seccion">Explorar Productos</h1>
          <p class="contador-productos">
            {{ productosFiltrados.length }} productos encontrados
          </p>
        </div>

        <div class="acciones-derecha">
          <div class="buscador-explorar">
            <div class="search-wrapper">
              <svg
                viewBox="0 0 24 24"
                fill="none"
                stroke="#6b7280"
                stroke-width="2"
                class="search-icon"
              >
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
              </svg>
              <input
                type="text"
                v-model="searchQuery"
                :placeholder="$t('navbar.search')"
                class="search-input"
              />
            </div>
          </div>
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
          @agregado="handleAgregado"
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
            <div
              v-for="opcion in opcionesOrden"
              :key="opcion"
              class="control-f"
            >
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

            <div class="buscador-categorias-wrapper">
              <input
                type="text"
                v-model="busquedaCategoria"
                placeholder="Buscar y añadir categoría..."
                class="input-buscar-categoria"
                @focus="mostrarDropdownCategorias = true"
                @blur="
                  setTimeout(() => (mostrarDropdownCategorias = false), 200)
                "
              />

              <ul
                v-if="
                  mostrarDropdownCategorias &&
                  categoriasFiltradasPorBusqueda.length > 0
                "
                class="dropdown-categorias"
              >
                <li
                  v-for="cat in categoriasFiltradasPorBusqueda"
                  :key="cat.id"
                  @click="seleccionarCategoria(cat)"
                >
                  {{ cat.name }}
                </li>
              </ul>

              <div
                v-else-if="
                  mostrarDropdownCategorias && busquedaCategoria !== ''
                "
                class="dropdown-categorias vacio"
              >
                No se encontraron categorías
              </div>
            </div>

            <div
              class="chips-container"
              v-if="categoriasSeleccionadas.length > 0"
            >
              <div
                v-for="id in categoriasSeleccionadas"
                :key="id"
                class="chip-categoria"
              >
                <span>{{ nombreCategoria(id) }}</span>
                <button @click="quitarCategoria(id)" class="btn-quitar-chip">
                  &times;
                </button>
              </div>
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
          <button class="btn-limpiar" @click="limpiarFiltros">
            Limpiar filtros
          </button>
        </div>
      </aside>
      <transition name="fade-flotante">
        <button
          v-if="mostrarBotonFlotante && !mostrarFiltros"
          class="btn-flotante-filtros"
          @click="mostrarFiltros = true"
          title="Abrir filtros"
        >
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
        </button>
      </transition>

      <div class="paginacion-container" v-if="totalPaginas > 1">
  <button 
    :disabled="paginaActual === 1" 
    @click="cambiarPagina(paginaActual - 1)"
    class="btn-pag"
  > Anterior </button>

  <span class="info-pag">Página {{ paginaActual }} de {{ totalPaginas }}</span>

  <button 
    :disabled="paginaActual === totalPaginas" 
    @click="cambiarPagina(paginaActual + 1)"
    class="btn-pag"
  > Siguiente </button>
</div>

    </div>
  </div>
  <Footer></Footer>
</template>
