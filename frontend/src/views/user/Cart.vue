<script setup>
import { ref, computed, onMounted, nextTick } from "vue";
import { useI18n } from "vue-i18n";
import axios from "axios";
import imagenPorDefecto from "@/assets/logos/Logo.png";
import NavBar from "@/components/NavBar.vue";
import Footer from "@/components/Footer.vue";
import { useToast } from '@/composables/useToast';

const { t } = useI18n();
const { addToast } = useToast();
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

    let carritoLocal = JSON.parse(localStorage.getItem("carrito")) || [];
    const index = carritoLocal.findIndex(p => p.id === item.product.id);
    if (index !== -1) {
      carritoLocal[index].quantity = nuevaCantidad;
      localStorage.setItem("carrito", JSON.stringify(carritoLocal));
    }

    window.dispatchEvent(new CustomEvent("cart-updated"));

  } catch (error) {
    console.error("Error al actualizar la cantidad:", error);
  } finally {
    item.procesando = false;
  }
};

const eliminarProducto = async (item) => {
  if (!confirm(t('cart.confirmRemove'))) return;

  try {
    item.procesando = true;

    await axios.delete(`http://localhost:8080/api/cart/${item.id_cart_item}`, {
      headers: { Authorization: `Bearer ${token}` },
    });

    productosCarrito.value = productosCarrito.value.filter(
      (p) => p.id_cart_item !== item.id_cart_item,
    );

    let carritoLocal = JSON.parse(localStorage.getItem("carrito")) || [];
    carritoLocal = carritoLocal.filter(p => p.id !== item.product.id);
    localStorage.setItem("carrito", JSON.stringify(carritoLocal));

    window.dispatchEvent(new CustomEvent("cart-updated"));
    
  } catch (error) {
    console.error("Error al eliminar del carrito:", error);
    addToast(t('cart.toast.removeError'), 'error');
    item.procesando = false;
  }
};

const obtenerImagen = (ruta) => {
  if (!ruta) return imagenPorDefecto;
  if (ruta.startsWith("http")) return ruta;
  return `http://localhost:8080/storage/${ruta}`;
};

const manejarErrorImagen = (event) => {
  event.target.src = imagenPorDefecto;
};

const inicializarPaypal = () => {
  const container = document.getElementById("paypal-button-container");

  if (window.paypal && container) {
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
              `http://localhost:8080/api/paypal/capture-order/${data.orderID}`,
              {},
              { headers: { Authorization: `Bearer ${token}` } },
            );
            addToast(t('cart.toast.paymentSuccess'), 'success');
            productosCarrito.value = [];
            localStorage.removeItem("carrito");

            window.dispatchEvent(new CustomEvent('cart-updated'));
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
      <h1 class="titulo-pagina">{{ t('cart.title') }}</h1>

      <div v-if="cargando" class="estado-carga">
        <p>{{ t('cart.loading') }}</p>
      </div>

      <div v-else-if="productosCarrito.length === 0" class="carrito-vacio">
        <p>{{ t('cart.emptyCart') }}</p>
        <router-link to="/explorar" class="btn-explorar">
          {{ t('cart.exploreProducts') }}
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
              @error="manejarErrorImagen" 
            />

            <div class="producto-info">
              <div class="info-texto">
                <h3 class="producto-titulo">{{ item.product.title }}</h3>
                <p class="producto-categoria">{{ t('cart.digitalProduct') }}</p>
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
                  :title="t('cart.removeFromCart')"
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
            <h2 class="resumen-titulo">{{ t('cart.orderSummary') }}</h2>

            <div class="resumen-fila">
              <span>{{ t('cart.subtotalItems', { count: totalItems }) }}</span>
              <span>{{ subtotal.toFixed(2) }}€</span>
            </div>

            <div class="resumen-fila comision-fila">
              <span>{{ t('cart.feesAndService') }}</span>
              <span>{{ comision.toFixed(2) }}€</span>
            </div>

            <hr class="divisor" />

            <div class="resumen-fila fila-total">
              <span>{{ t('cart.total') }}</span>
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
              {{ t('cart.continueShopping') }}
            </router-link>
          </div>
        </aside>
      </div>
    </div>
  </div>

  <Footer></Footer>
</template>

<style lang="scss">
@use "@/assets/scss/carrito";
</style>