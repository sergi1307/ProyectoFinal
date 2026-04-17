<script setup>
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';
import NavBar from '@/components/NavBar.vue';
import Footer from '@/components/Footer.vue';

const productosCarrito = ref([]);
const cargando = ref(true);
const token = localStorage.getItem('token');

const totalItems = computed(() => {
    return productosCarrito.value.reduce((acc, item) => acc + item.quantity, 0);
});

const subtotal = computed(() => {
    return productosCarrito.value.reduce((acc, item) => {
        return acc + (Number(item.product.price) * item.quantity);
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
            headers: {
                Authorization: `Bearer ${token}`
            }
        });

        productosCarrito.value = res.data.map(item => ({
            ...item,
            procesando: false
        }));
    } catch (error) { 
        console.error('Error al cargar el carrito:', error);
    } finally {
        cargando.value = false;
    }
};

const actualizarCantidad = async (item, nuevaCantidad) => {
  if (nuevaCantidad < 1) return;
  
  try {
    item.procesando = true; 
    
    await axios.put(`http://localhost:8080/api/cart/${item.id_cart_item}`, 
      { quantity: nuevaCantidad },
      { headers: { Authorization: `Bearer ${token}` } } 
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
      headers: { Authorization: `Bearer ${token}` } 
    });
    
    productosCarrito.value = productosCarrito.value.filter(p => p.id_cart_item !== item.id_cart_item);
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

onMounted(cargarCarrito);
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
            :class="{ 'deshabilitado': item.procesando }"
          >
            <img :src="obtenerImagen(item.product.image_url)" :alt="item.product.title" class="producto-img" />
            
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
                  >-</button>
                  
                  <span class="cantidad-num">{{ item.quantity }}</span>
                  
                  <button 
                    @click="actualizarCantidad(item, item.quantity + 1)" 
                    class="btn-cant"
                    :disabled="item.procesando"
                  >+</button>
                </div>
                
                <button 
                  @click="eliminarProducto(item)" 
                  class="btn-eliminar" 
                  title="Eliminar del carrito"
                  :disabled="item.procesando"
                >
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="3 6 5 6 21 6"></polyline>
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2-2v2"></path>
                  </svg>
                </button>
              </div>
            </div>

            <div class="producto-precio-columna">
                <p class="producto-precio">{{ Number(item.product.price).toFixed(2) }}€</p>
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
            
            <button class="btn-principal">Proceder al Pago</button>
            <router-link to="/explorar" class="btn-secundario">Continuar Comprando</router-link>
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
@use '@/assets/scss/carrito';
</style>