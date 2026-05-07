<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import NavBar from "../../components/NavBar.vue";
import Footer from "../../components/Footer.vue";

const pedidos = ref([]);
const cargando = ref(true);
const error = ref(null);

const infoEstado = (status) => {
  const estados = {
    pending: { texto: "Pendiente", clase: "status-pending" },
    completed: { texto: "Completado", clase: "status-completed" },
    cancelled: { texto: "Cancelado", clase: "status-cancelled" },
    refunded: { texto: "Reembolsado", clase: "status-refunded" },
  };
  return estados[status] || { texto: status, clase: "status-default" };
};

const formatearFecha = (fecha) => {
  if (!fecha) return "";
  const date = new Date(fecha);
  return new Intl.DateTimeFormat("es-ES", {
    year: "numeric",
    month: "long",
    day: "numeric",
    hour: "2-digit",
    minute: "2-digit",
  }).format(date);
};

const cargarPedidos = async () => {
  try {
    cargando.value = true;
    error.value = null;

    const token = localStorage.getItem("token");

    const respuesta = await fetch("http://localhost:8080/api/orders", {
      method: "GET",
      headers: {
        Accept: "application/json",
        Authorization: `Bearer ${token}`,
      },
    });

    if (!respuesta.ok) {
      throw new Error("No se pudo conectar con el servidor.");
    }

    const datos = await respuesta.json();
    console.log("DATOS RECIBIDOS DE LARAVEL:", datos);
    pedidos.value = datos;
  } catch (e) {
    error.value = e.message;
    console.error("Error cargando los pedidos:", e);
  } finally {
    cargando.value = false;
  }
};

const solicitarReembolso = async (idPedido) => {
  if (
    !confirm(
      "¿Estás seguro de que quieres solicitar el reembolso de este pedido?",
    )
  ) {
    return;
  }

  try {
    const token = localStorage.getItem("token");

    const respuesta = await axios.post(
      `http://localhost:8080/api/orders/${idPedido}/refund`,
      {},
      {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      },
    );

    alert("Reembolso solicitado correctamente.");

    await cargarPedidos();
  } catch (e) {
    const mensajeError =
      e.response?.data?.error || "No se pudo procesar el reembolso.";
    alert("Error: " + mensajeError);
    console.error("Error en la petición de reembolso:", e);
  }
};

onMounted(() => {
  cargarPedidos();
});
</script>

<template>
  <NavBar></NavBar>

  <div class="explorar-page">
    <div class="explorar-contenedor">
      <header class="cabecera-explorar">
        <div class="info-titulo">
          <h1 class="titulo-seccion">Mis Pedidos</h1>
          <p class="contador-productos">
            {{ pedidos.length }} pedidos en tu historial
          </p>
        </div>

        <div class="acciones-derecha">
          <button class="boton-action-primary">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="20"
              height="20"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
            >
              <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
              <polyline points="7 10 12 15 17 10"></polyline>
              <line x1="12" y1="15" x2="12" y2="3"></line>
            </svg>
            Descargar Todo
          </button>
        </div>
      </header>

      <main class="pedidos">
        <div v-if="cargando" class="status-message">
          <p>Cargando tus pedidos...</p>
        </div>

        <div v-else-if="error" class="status-message error">
          <p>Hubo un problema: {{ error }}</p>
        </div>

        <div v-else-if="pedidos.length === 0" class="status-message">
          <p>Aún no tienes pedidos registrados.</p>
        </div>

        <template v-else>
          <div
            v-for="pedido in pedidos"
            :key="pedido.id_order"
            class="order-card"
          >
            <div class="order-header">
              <div class="order-info-main">
                <div class="order-title-status">
                  <h2 class="order-id">Pedido #{{ pedido.id_order }}</h2>
                  <span
                    class="status-badge"
                    :class="infoEstado(pedido.status).clase"
                  >
                    {{ infoEstado(pedido.status).texto }}
                  </span>
                </div>
                <p class="order-date">
                  {{ formatearFecha(pedido.created_at) }}
                </p>
              </div>
              <div class="order-total">
                <p class="total-price">{{ pedido.total }}€</p>
                <p class="total-products">
                  {{ pedido.details?.length || 0 }} productos
                </p>
              </div>
            </div>

            <div class="products-list">
              <div
                v-for="detalle in pedido.details"
                :key="detalle.id_detail"
                class="product-item"
              >
                <div
                  class="product-image"
                  :style="{
                    backgroundImage: detalle.product?.image_url
                      ? `url(${detalle.product.image_url})`
                      : 'linear-gradient(135deg, #1e293b, #0f172a)',
                  }"
                ></div>

                <div class="product-details">
                  <p class="product-name">
                    {{ detalle.product?.title || "Producto no disponible" }}
                  </p>
                  <p class="product-quantity">Precio: {{ detalle.price }}€</p>
                </div>
              </div>
            </div>

            <div class="order-footer">
              <div class="order-actions">
                <button class="btn-card-action">
                  <svg
                    class="btn-icon"
                    xmlns="http://www.w3.org/2000/svg"
                    width="16"
                    height="16"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  >
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    <polyline points="7 10 12 15 17 10"></polyline>
                    <line x1="12" y1="15" x2="12" y2="3"></line>
                  </svg>
                  Factura
                </button>
                <button class="btn-card-action">
                  <svg
                    class="btn-icon"
                    xmlns="http://www.w3.org/2000/svg"
                    width="16"
                    height="16"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  >
                    <path
                      d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"
                    ></path>
                    <circle cx="12" cy="12" r="3"></circle>
                  </svg>
                  Ver Detalles
                </button>
                <button
                  v-if="pedido.status === 'completed'"
                  @click="solicitarReembolso(pedido.id_order)"
                  class="btn-card-action btn-refund"
                >
                  <svg
                    class="btn-icon"
                    xmlns="http://www.w3.org/2000/svg"
                    width="16"
                    height="16"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  >
                    <path
                      d="M10 1.5l-4 3 4 3.5"
                      transform="rotate(-10, 7.5, 4.5)"
                    ></path>
                    <path d="M20 12c0-4.4-3.6-8-8-8-0.8 0-1.6.1-2.4.3"></path>
                    <path
                      d="M14 22.5l4-3-4-3.5"
                      transform="rotate(-10, 16.5, 19.5)"
                    ></path>
                    <path d="M4 12c0 4.4 3.6 8 8 8 0.8 0 1.6-.1 2.4-.3"></path>
                  </svg>
                  Solicitar Reembolso
                </button>
              </div>
            </div>
          </div>
        </template>
      </main>
    </div>
  </div>

  <Footer></Footer>
</template>