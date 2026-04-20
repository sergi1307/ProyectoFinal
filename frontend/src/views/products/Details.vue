<script setup>
import { ref, onMounted, computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import axios from 'axios';
import Navbar from '../../components/NavBar.vue';
import Footer from '../../components/Footer.vue';

const route = useRoute();
const producto = ref(null);
const cargando = ref(true);
const agregando = ref(false);

const obtenerProducto = async () => {
  try {
    const token = localStorage.getItem("token");

    cargando.value = true;
    const { id } = route.params;
    const res = await axios.get(`http://localhost:8080/api/products/${id}`, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    });
    producto.value = res.data;
  } catch (error) {
    console.error("Error al cargar el producto:", error);
  } finally {
    cargando.value = false;
  }
}

const obtenerImagen = (ruta) => {
    if (!ruta) return "https://via.placeholder.com/600x800?text=Sin+Imagen";
    if (ruta.startsWith("http")) return ruta;
    return `http://localhost:8080/storage/${ruta}`;
}

const toggleFavorito = async () => {
    try {
        const token = localStorage.getItem("token");
        const res = await axios.post(`http://localhost:8080/api/products/${producto.value.id}/favorite`, {}, {
            headers: {
                Authorization: `Bearer ${token}`
            }
        });

        producto.value.is_favorite = res.data.is_favorite;
    } catch (error) {
        console.error("Error al actualizar favorito:", error);
    }
}

const agregarAlCarrito = async () => {
    try {
        agregando.value = true;
        const token = localStorage.getItem("token");
        await axios.post(`http://localhost:8080/api/cart`, {
            product_id: producto.value.id,
            quantity: 1
        }, {
            headers: {
                Authorization: `Bearer ${token}`
            }
        });

        let carritoLocal = JSON.parse(localStorage.getItem("carrito")) || [];
        carritoLocal.push(producto.value);
        localStorage.setItem("carrito", JSON.stringify(carritoLocal));

        window.dispatchEvent(new CustomEvent('cart-updated'));
    } catch (error) {
        console.error("Error al añadir el producto:", error);
    } finally {
        agregando.value = false;
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
                    <img :src="obtenerImagen(producto.image_url)" :alt="producto.title" class="imagen-principal">
                </div>
            </div>

            <div class="info-compra">
                <nav class="breadcrumb">
                    <router-link to="/explorar">Explorar</router-link> /
                    <span>{{ producto.categories?.[0]?.name || 'Digital' }}</span>
                </nav>

                <h1 class="titulo">{{ producto.title }}</h1>

                <div class="meta-superior">
                    <div class="rating">
                        <span class="estrellas">*****</span>
                        <span class="count">({{ producto.reviews_count || 0 }} reseñas)</span>
                    </div>
                    <span class="badge-vendedor">Vendedor Verificado</span>
                </div>

                <div class="precio">{{ producto.price }}€</div>

                <p class="descripcion">
                    {{ producto.description || "Sin descripción disponible." }}
                </p>

                <div class="caracteristicas">
                    <div class="item-feat">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                        <span>Descarga instantánea</span>
                    </div>
                    <div class="item-feat">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                        <span>Pago seguro garantizado</span>
                    </div>
                </div>

                <div class="acciones-compra">
                    <button @click="agregarAlCarrito" :disabled="agregando" class="btn-principal">
                        <svg v-if="!agregando" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                        <span>{{ agregando ? 'Añadiendo...' : 'Añadir al Carrito' }}</span>
                    </button>
                    
                    <button @click="toggleFavorito" class="btn-favorito-detalle" :class="{ 'activo': producto.is_favorite }">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                    </button>
                </div>

                <div class="info-vendedor">
                    <img src="https://ui-avatars.com/api/?name=Nexus+Seller&background=0097b2&color=fff" class="avatar" />
                    <div class="vendedor-txt">
                        <p class="nombre">Publicado por <span>{{ producto.user?.name || 'Nexus' }}</span></p>
                        <router-link to="/">Ver perfil completo</router-link>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div v-else class="loader">
        <div class="spinner"></div>
    </div>

    <Footer />
</template>