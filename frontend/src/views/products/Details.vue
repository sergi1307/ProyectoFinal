<script setup>
import { ref, onMounted, computed } from "vue";
import { useRoute } from "vue-router";
import api from "@/services/api";
import imagenPorDefecto from "@/assets/logos/Logo.png";
import Navbar from "@/components/NavBar.vue";
import Footer from "@/components/Footer.vue";

const route = useRoute();
const producto = ref(null);
const cargando = ref(true);
const agregando = ref(false);
const enviandoReview = ref(false);
const hoverRating = ref(0);
const nuevaReview = ref({ rating: 5, comment: "" });
const mostrarModalReporte = ref(false);
const enviandoReporte = ref(false);
const reporte = ref({ reason: "Contenido inapropiado", details: "" });
const motivosReporte = [
  "Contenido inapropiado",
  "Imagen engañosa",
  "Precio abusivo o falso",
  "Derechos de autor / Plagio",
  "Estafa o Fraude",
  "Otro",
];

const formatoArchivo = computed(() => {
  if (!producto.value?.file_url) return "Desconocido";
  return producto.value.file_url.split(".").pop().toUpperCase();
});

const tamanoArchivo = computed(() => {
  const bytes = producto.value?.file_size;
  if (!bytes) return "Por determinar";

  const i = Math.floor(Math.log(bytes) / Math.log(1024));

  return (
    (bytes / Math.pow(1024, i)).toFixed(2) +
    " " +
    ["B", "KB", "MB", "GB", "TB"][i]
  );
});

const fechaActualizacion = computed(() => {
  if (!producto.value?.updated_at) return "Reciente";
  return new Date(producto.value.updated_at).toLocaleDateString();
});

const obtenerProducto = async () => {
  try {
    cargando.value = true;
    const token = localStorage.getItem("token");
    const { id } = route.params;

    const res = await api.get(`/products/${id}`, {
      headers: { Authorization: `Bearer ${token}` },
    });

    producto.value = res.data;
  } catch (error) {
    console.error("Error al cargar el producto:", error);
  } finally {
    cargando.value = false;
  }
};

const obtenerImagen = (ruta) => {
  if (!ruta) return imagenPorDefecto;
  if (ruta.startsWith("http")) return ruta;
  return `${import.meta.env.VITE_STORAGE_BASE_URL || 'https://nexus-isaria.es/storage'}/${ruta}`;
};

const manejarErrorImagen = (event) => {
  event.target.src = imagenPorDefecto;
};

const agregarAlCarrito = async () => {
  try {
    agregando.value = true;
    const token = localStorage.getItem("token");

    await api.post(
      `/cart`,
      { product_id: producto.value.id, quantity: 1 },
      { headers: { Authorization: `Bearer ${token}` } },
    );

    let carritoLocal = JSON.parse(localStorage.getItem("carrito")) || [];
    carritoLocal.push(producto.value);
    localStorage.setItem("carrito", JSON.stringify(carritoLocal));

    window.dispatchEvent(new CustomEvent("cart-updated"));
  } catch (error) {
    console.error("Error al añadir el producto:", error);
  } finally {
    agregando.value = false;
  }
};

const obtenerRutaAvatar = (ruta) => {
  if (!ruta || ruta === "0" || ruta === 0) return null;
  const limpia = ruta.replace(/^storage\//, "").replace(/^\//, "");
  return `${import.meta.env.VITE_STORAGE_BASE_URL || 'https://nexus-isaria.es/storage'}/${limpia}`;
};

const toggleFavorito = async () => {
  try {
    const token = localStorage.getItem("token");
    const res = await api.post(
      `/products/${producto.value.id}/favorite`,
      {},
      {
        headers: { Authorization: `Bearer ${token}` },
      },
    );
    producto.value.is_favorite = res.data.is_favorite;
  } catch (error) {
    console.error("Error al actualizar favorito:", error);
  }
};

const calcularRating = (event, n) => {
  const rect = event.target.getBoundingClientRect();
  return event.clientX - rect.left < rect.width / 2 ? n - 0.5 : n;
};

const handleMouseMove = (event, n) =>
  (hoverRating.value = calcularRating(event, n));
const setRating = (event, n) =>
  (nuevaReview.value.rating = calcularRating(event, n));

const enviarReview = async () => {
  try {
    enviandoReview.value = true;
    const token = localStorage.getItem("token");

    await api.post(
      `/products/${producto.value.id}/reviews`,
      nuevaReview.value,
      {
        headers: { Authorization: `Bearer ${token}` },
      },
    );

    nuevaReview.value = { rating: 5, comment: "" };
    hoverRating.value = 0;

    await obtenerProducto();
  } catch (error) {
    console.error("Error al enviar la review:", error);
    alert("No se pudo enviar la valoración.");
  } finally {
    enviandoReview.value = false;
  }
};

const enviarDenuncia = async () => {
  try {
    enviandoReporte.value = true;
    let token = localStorage.getItem("token");
    if (!token) {
      alert("Debes iniciar sesión para denunciar un producto.");
      return;
    }
    token = token.replace(/^"(.*)"$/, "$1");
    await api.post(
      `/products/${producto.value.id}/report`,
      reporte.value,
      {
        headers: {
          Authorization: `Bearer ${token}`,
          Accept: "application/json",
        },
      },
    );
    alert("Gracias. El equipo de moderación revisará el producto.");
    mostrarModalReporte.value = false;
    reporte.value = { reason: "Contenido inapropiado", details: "" };
  } catch (error) {
    console.error("Error al enviar denuncia:", error);
    alert("No se pudo enviar la denuncia.");
  } finally {
    enviandoReporte.value = false;
  }
};

onMounted(obtenerProducto);
</script>

<template>
  <Navbar />

  <div class="pagina-detalle" v-if="!cargando && producto">
    <div class="contenedor-detalle">
      <div class="galeria-producto">
        <div class="sticky-image">
          <img
            :src="obtenerImagen(producto.image_url)"
            :alt="producto.title"
            class="imagen-principal"
            @error="manejarErrorImagen"
          />
        </div>
      </div>

      <div class="info-compra">
        <nav class="breadcrumb">
          <router-link to="/explorar">Explorar</router-link> /
          <span>{{ producto.categories?.[0]?.name || "Digital" }}</span>
        </nav>

        <h1 class="titulo">{{ producto.title }}</h1>

        <div class="meta-superior">
          <div class="rating">
            <div class="estrellas-stack">
              <div class="estrellas-fondo" style="font-size: 1.2rem">
                ★★★★★
                <div
                  class="estrellas-relleno"
                  :style="{ width: producto.rating * 20 + '%' }"
                >
                  ★★★★★
                </div>
              </div>
            </div>
            <span class="count"
              >({{ producto.reviews_count || 0 }} reseñas)</span
            >
          </div>
          <span class="badge-vendedor">Vendedor Verificado</span>
          <span 
            v-if="producto.user?.plan && producto.user.plan !== 'Básico'" 
            :class="[
              'badge-vendedor-titulo', 
              'badge-exclusivo',
              'badge-' + producto.user.plan.toLowerCase()
            ]"
          >
            {{ producto.user.plan }}
          </span>
        </div>

        <div class="precio">{{ producto.price }}€</div>
        <p class="descripcion">
          {{ producto.description || "Sin descripción disponible." }}
        </p>

        <div class="caracteristicas">
          <div class="item-feat">
            <svg
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
            >
              <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
              <polyline points="7 10 12 15 17 10"></polyline>
              <line x1="12" y1="15" x2="12" y2="3"></line>
            </svg>
            <span>Descarga instantánea</span>
          </div>
          <div class="item-feat">
            <svg
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
            >
              <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
              <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
            </svg>
            <span>Pago seguro garantizado</span>
          </div>
        </div>

        <div class="caja-detalles-archivo">
          <h3 class="titulo-detalles">Detalles del Archivo</h3>
          <ul class="lista-detalles">
            <li>
              <span class="detalle-label">Formato:</span>
              <span class="detalle-valor">{{ formatoArchivo }}</span>
            </li>
            <li>
              <span class="detalle-label">Tamaño:</span>
              <span class="detalle-valor">{{ tamanoArchivo }}</span>
            </li>
            <li>
              <span class="detalle-label">Actualizado:</span>
              <span class="detalle-valor">{{ fechaActualizacion }}</span>
            </li>
          </ul>
        </div>

        <div class="acciones-compra">
          <button
            @click="agregarAlCarrito"
            :disabled="agregando"
            class="btn-principal"
          >
            <span>{{ agregando ? "Añadiendo..." : "Añadir al Carrito" }}</span>
          </button>

          <button
            @click="toggleFavorito"
            class="btn-favorito-detalle"
            :class="{ activo: producto.is_favorite }"
          >
            <svg
              width="24"
              height="24"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
            >
              <path
                d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"
              ></path>
            </svg>
          </button>
        </div>

        <div class="info-vendedor">
          <div
            class="vendedor-avatar-wrapper"
            :class="
              producto.user?.plan && producto.user.plan !== 'Básico'
                ? 'plan-' + producto.user.plan.toLowerCase()
                : ''
            "
          >
            <img
              v-if="
                producto.user?.avatar && obtenerRutaAvatar(producto.user.avatar)
              "
              :src="obtenerRutaAvatar(producto.user.avatar)"
              class="avatar-img"
            />
            <div v-else class="avatar-placeholder">
              <svg
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
              >
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                <circle cx="12" cy="7" r="4"></circle>
              </svg>
            </div>
          </div>

          <div class="vendedor-txt">
            <p class="nombre">
              Publicado por <span>{{ producto.user?.name || "Nexus" }}</span>
            </p>
            <router-link to="/">Ver perfil completo</router-link>
          </div>
        </div>

        <button @click="mostrarModalReporte = true" class="btn-reportar-enlace">
          <svg
            width="14"
            height="14"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
          >
            <path
              d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"
            ></path>
            <line x1="4" y1="22" x2="4" y2="15"></line>
          </svg>
          Denunciar este producto
        </button>
      </div>
    </div>

    <section class="seccion-reviews">
      <div class="reviews-header">
        <div class="titulo-reviews">
          <h2>Opiniones de la comunidad</h2>
          <p v-if="producto.reviews_count > 0">
            Basado en {{ producto.reviews_count }} valoraciones
          </p>
        </div>

        <div class="score-global" v-if="producto.reviews_count > 0">
          <span class="nota-media">{{ producto.rating }}</span>
          <div class="estrellas-stack">
            <div class="estrellas-fondo">
              ★★★★★
              <div
                class="estrellas-relleno"
                :style="{ width: producto.rating * 20 + '%' }"
              >
                ★★★★★
              </div>
            </div>
            <span class="txt-total">Puntuación media</span>
          </div>
        </div>
      </div>

      <div class="grid-reviews" v-if="producto.reviews?.length > 0">
        <div
          v-for="review in producto.reviews"
          :key="review.id_review"
          class="card-review"
        >
          <div class="review-top">
            <img
              :src="`https://ui-avatars.com/api/?name=${review.user?.name}&background=0097b2&color=fff`"
              class="avatar-mini"
            />
            <div class="user-info">
              <p class="user-name">{{ review.user?.name }}</p>
              <div class="user-stars-wrapper">
                <div class="stars-fondo-mini">
                  ★★★★★
                  <div
                    class="stars-relleno-mini"
                    :style="{ width: review.rating * 20 + '%' }"
                  >
                    ★★★★★
                  </div>
                </div>
              </div>
            </div>
            <span class="review-date">{{
              new Date(review.created_at).toLocaleDateString()
            }}</span>
          </div>
          <p class="review-body">
            {{ review.comment || "El usuario no ha dejado un comentario." }}
          </p>
        </div>
      </div>

      <div class="reviews-vacias" v-else>
        <div class="icono-vacio">💬</div>
        <h3>Sin opiniones todavía</h3>
        <p>¡Sé el primero en compartir tu experiencia!</p>
      </div>

      <div class="formulario-review">
        <div class="card-review">
          <h3>Escribe una reseña</h3>
          <div class="rating-input-wrapper">
            <div class="stars-base">
              <div class="stars-hover-area">
                <span
                  v-for="n in 5"
                  :key="n"
                  @mousemove="handleMouseMove($event, n)"
                  @mouseleave="hoverRating = 0"
                  @click="setRating($event, n)"
                  >★</span
                >
              </div>
              <div
                class="stars-active"
                :style="{
                  width: (hoverRating || nuevaReview.rating) * 20 + '%',
                }"
              >
                <span>★</span><span>★</span><span>★</span><span>★</span
                ><span>★</span>
              </div>
            </div>
            <span class="rating-value"
              >{{ hoverRating || nuevaReview.rating }} / 5</span
            >
          </div>
          <textarea
            v-model="nuevaReview.comment"
            placeholder="¿Qué te ha parecido el producto?"
          ></textarea>
          <button
            @click="enviarReview"
            :disabled="enviandoReview"
            class="btn-principal"
          >
            {{ enviandoReview ? "Enviando..." : "Publicar reseña" }}
          </button>
        </div>
      </div>
    </section>
  </div>

  <div v-else class="loader">
    <div class="spinner"></div>
  </div>

  <div
    v-if="mostrarModalReporte"
    class="overlay-reporte"
    @click.self="mostrarModalReporte = false"
  >
    <div class="modal-reporte">
      <h3>Denunciar producto</h3>
      <p>Ayúdanos a mantener Nexus seguro. ¿Por qué reportas este contenido?</p>

      <div class="campo">
        <label>Motivo</label>
        <select v-model="reporte.reason">
          <option
            v-for="motivo in motivosReporte"
            :key="motivo"
            :value="motivo"
          >
            {{ motivo }}
          </option>
        </select>
      </div>

      <div class="campo">
        <label>Detalles adicionales (opcional)</label>
        <textarea
          v-model="reporte.details"
          placeholder="Explica brevemente el problema..."
        ></textarea>
      </div>

      <div class="acciones-modal">
        <button @click="mostrarModalReporte = false" class="btn-cancelar">
          Cancelar
        </button>
        <button
          @click="enviarDenuncia"
          :disabled="enviandoReporte"
          class="btn-confirmar"
        >
          {{ enviandoReporte ? "Enviando..." : "Enviar denuncia" }}
        </button>
      </div>
    </div>
  </div>
  <Footer />
</template>
