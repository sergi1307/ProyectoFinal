<script setup>
import { ref, computed, onMounted, nextTick } from "vue";
import axios from "axios";
import NavBar from "@/components/NavBar.vue";
import Footer from "@/components/Footer.vue";

const paypalRendered = ref(false);
const productosCarrito = ref([]);
const cargando = ref(true);
const token = localStorage.getItem("token");

const totalItems = computed(() => {
  return productosCarrito.value.reduce((acc, item) => acc + item.quantity, 0);
});

const subtotal = computed(() => {
  return productosCarrito.value.reduce((acc, item) => {
    return acc + Number(item.product.price) * item.quantity;
  }, 0);
});

const comision = computed(() => {
  return subtotal.value * 0.05;
});

const total = computed(() => {
  return subtotal.value + comision.value;
});

const cargarCarrito = async () => {
  try {
    cargando.value = true;
    const res = await axios.get(`http://localhost:8080/api/cart`, {
      headers: { Authorization: `Bearer ${token}` },
    });
    productosCarrito.value = res.data.map((item) => ({
      ...item,
      procesando: false,
    }));

    nextTick(() => {
      if (productosCarrito.value.length > 0) inicializarPaypal();
    });
  } catch (error) {
    console.error("Error al cargar el carrito:", error);
  } finally {
    cargando.value = false;
  }
};

const actualizarCantidad = async (item, nuevaCantidad) => {
  if (nuevaCantidad < 1) return;

  try {
    item.procesando = true;

    await axios.put(
      `http://localhost:8080/api/cart/${item.id_cart_item}`,
      { quantity: nuevaCantidad },
      { headers: { Authorization: `Bearer ${token}` } },
    );

    item.quantity = nuevaCantidad;
  } catch (error) {
    console.error("Error al actualizar la cantidad:", error);
  } finally {
    item.procesando = false;
  }
};

const eliminarProducto = async (item) => {
  if (!confirm("¿Seguro que quieres quitar este producto del carrito?")) return;

  try {
    item.procesando = true;

    await axios.delete(`http://localhost:8080/api/cart/${item.id_cart_item}`, {
      headers: { Authorization: `Bearer ${token}` },
    });

    productosCarrito.value = productosCarrito.value.filter(
      (p) => p.id_cart_item !== item.id_cart_item,
    );
  } catch (error) {
    console.error("Error al eliminar del carrito:", error);
    item.procesando = false;
  }
};

const obtenerImagen = (ruta) => {
  if (!ruta) return "https://via.placeholder.com/300x300?text=Sin+Imagen";
  if (ruta.startsWith("http")) return ruta;
  return `http://localhost:8080/storage/${ruta}`;
};

const inicializarPaypal = () => {
  // Quitamos la comprobación de paypalRendered.value para probar si es eso
  const container = document.getElementById("paypal-button-container");

  if (window.paypal && container) {
    // Limpiamos el contenedor por si acaso había algo antes
    container.innerHTML = "";

    window.paypal
      .Buttons({
        style: {
          layout: "vertical",
          color: "gold",
          shape: "rect",
          label: "pay",
        },
        createOrder: async () => {
          const res = await axios.post(
            "http://localhost:8080/api/paypal/create-order",
            { items: productosCarrito.value },
            { headers: { Authorization: `Bearer ${token}` } },
          );

          console.log("Respuesta de Laravel:", res.data);
          return res.data.id;
        },
        onApprove: async (data) => {
          try {
            await axios.post(
              `http://localhost:8080/api/paypal/capture-order/${data.orderID}`, // Ajusta la URL si hace falta
              {},
              { headers: { Authorization: `Bearer ${token}` } },
            );
            alert("¡Pago completado!");
            productosCarrito.value = [];
          } catch (error) {
            console.error("Error al capturar el pago:", error);
          }
        },
      })
      .render("#paypal-button-container")
      .then(() => {
        paypalRendered.value = true;
      })
      .catch((err) => {
        console.error("Error al renderizar PayPal:", err);
      });
  } else {
    setTimeout(inicializarPaypal, 500);
  }
};

const cargarScriptPaypal = () => {
  return new Promise((resolve) => {
    if (window.paypal) {
      resolve();
      return;
    }
    const script = document.createElement("script");
    script.src =
      "https://www.paypal.com/sdk/js?client-id=AQkVCAVURWlxeKRKSRV7k6kDiGF1z40assglYEW012wfkpixHZp5bFepPqLugPU4inF6J03m099YaMY7&currency=EUR";
    script.async = true;
    script.onload = () => resolve();
    document.head.appendChild(script);
  });
};

onMounted(async () => {
  await cargarScriptPaypal();
  await cargarCarrito();
});
</script>

<template>
  <NavBar></NavBar>

  <div class="carrito-page">
    <div class="container-wide">
      <h1 class="titulo-pagina">Mi Carrito</h1>

      <div v-if="cargando" class="estado-carga">
        <p>Cargando tu carrito...</p>
      </div>

      <div v-else-if="productosCarrito.length === 0" class="carrito-vacio">
        <p>Aún no tienes productos en tu carrito.</p>
        <router-link to="/explorar" class="btn-explorar">
          Explorar Productos
        </router-link>
      </div>

      <div v-else class="carrito-contenedor">
        <div class="productos-seccion">
          <div
            v-for="item in productosCarrito"
            :key="item.id_cart_item"
            class="tarjeta-producto"
            :class="{ deshabilitado: item.procesando }"
          >
            <img
              :src="obtenerImagen(item.product.image_url)"
              :alt="item.product.title"
              class="producto-img"
            />

            <div class="producto-info">
              <div class="info-texto">
                <h3 class="producto-titulo">{{ item.product.title }}</h3>
                <p class="producto-categoria">Producto Digital</p>
              </div>

              <div class="controles-fila">
                <div class="control-cantidad">
                  <button
                    @click="actualizarCantidad(item, item.quantity - 1)"
                    class="btn-cant"
                    :disabled="item.quantity <= 1 || item.procesando"
                  >
                    -
                  </button>

                  <span class="cantidad-num">{{ item.quantity }}</span>

                  <button
                    @click="actualizarCantidad(item, item.quantity + 1)"
                    class="btn-cant"
                    :disabled="item.procesando"
                  >
                    +
                  </button>
                </div>

                <button
                  @click="eliminarProducto(item)"
                  class="btn-eliminar"
                  title="Eliminar del carrito"
                  :disabled="item.procesando"
                >
                  <svg 
                    xmlns="http://www.w3.org/2000/svg" 
                    viewBox="0 0 24 24" 
                    fill="none" 
                    stroke="currentColor" 
                    stroke-width="2" 
                    stroke-linecap="round" 
                    stroke-linejoin="round"
                  >
                    <path d="M3 6h18"></path>
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    <line x1="10" y1="11" x2="10" y2="17"></line>
                    <line x1="14" y1="11" x2="14" y2="17"></line>
                  </svg>
                </button>
              </div>
            </div>

            <div class="producto-precio-columna">
              <p class="producto-precio">
                {{ Number(item.product.price).toFixed(2) }}€
              </p>
            </div>
          </div>
        </div>

        <aside class="resumen-seccion">
          <div class="tarjeta-resumen">
            <h2 class="resumen-titulo">Resumen del Pedido</h2>

            <div class="resumen-fila">
              <span>Subtotal ({{ totalItems }} items)</span>
              <span>{{ subtotal.toFixed(2) }}€</span>
            </div>

            <div class="resumen-fila comision-fila">
              <span>Tasas y servicio</span>
              <span>{{ comision.toFixed(2) }}€</span>
            </div>

            <hr class="divisor" />

            <div class="resumen-fila fila-total">
              <span>Total</span>
              <span class="precio-total">{{ total.toFixed(2) }}€</span>
            </div>

            <div
              v-if="productosCarrito.length > 0"
              id="paypal-button-container"
              style="
                margin-top: 1.5rem;
                min-height: 150px;
                position: relative;
                z-index: 1;
              "
            ></div>

            <router-link
              to="/explorar"
              class="btn-secundario"
              style="margin-top: 1rem; display: block; text-align: center"
            >
              Continuar Comprando
            </router-link>
          </div>
        </aside>
      </div>
    </div>
  </div>

  <Footer></Footer>
</template>

<style lang="scss">
/* Si importas el SCSS desde un archivo global, puedes borrar esta etiqueta <style> */
/* Si lo enlazas aquí, asegúrate de que la ruta sea la correcta */
@use "@/assets/scss/carrito";
</style>
