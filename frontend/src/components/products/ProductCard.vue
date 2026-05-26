<script setup>
import { defineProps, defineEmits, computed, ref } from "vue";
import imagenPorDefecto from "@/assets/logos/Logo.png";
import axios from "axios";
import { useToast } from '@/composables/useToast';
import { useI18n } from 'vue-i18n';

const { t, te } = useI18n();
const { addToast } = useToast();
const props = defineProps({
  producto: {
    type: Object,
    required: true,
  },
  isFavorite: {
    type: Boolean,
    default: false,
  },
  esPropio: {
    type: Boolean,
    default: false,
  },
  modoPublico: {
    type: Boolean,
    default: false,
  }
});

const emit = defineEmits(["eliminar", "ver", "editar", "favorito", "agregado"]);

const agregando = ref(false);

const obtenerUsuarioId = () => {
  const userData = localStorage.getItem("usuario");
  if (!userData) return null;
  try {
    const user = JSON.parse(userData);
    return user.id || user.user?.id || user.data?.id || null;
  } catch (error) {
    return null;
  }
};

const traducirCategoria = (nombreDB) => {
  if (!nombreDB) return "";
  const clave = nombreDB
    .toLowerCase()
    .normalize("NFD")
    .replace(/[\u0300-\u036f]/g, "")
    .replace(/\s+/g, "");
  const ruta = `categorias.${clave}`;
  if (!te(ruta)) {
    console.warn(`No se encontró traducción para la clave: ${ruta} basada en: ${nombreDB}`);
  }
  return te(ruta) ? t(ruta) : nombreDB;
};

const esMiProducto = computed(() => {
  if (props.esPropio) return true;

  const userData = localStorage.getItem("usuario");
  if (!userData) return false;

  try {
    const userLogueado = JSON.parse(userData);
    const actualId = obtenerUsuarioId();

    const productoOwnerId = props.producto.user_id || props.producto.user?.id;
    if (actualId && productoOwnerId && Number(actualId) === Number(productoOwnerId)) {
      return true;
    }

    const miEmail = userLogueado.email?.trim().toLowerCase();
    const emailDueño = props.producto.user?.email?.trim().toLowerCase();

    if (miEmail && emailDueño && miEmail === emailDueño) {
      return true;
    }

    return false;
  } catch (e) {
    return false;
  }
});

const obtenerImagen = (ruta) => {
  if (!ruta) return imagenPorDefecto;
  if (ruta.startsWith("http")) return ruta;
  return `http://localhost:8080/storage/${ruta}`;
};

const manejarErrorImagen = (event) => {
  event.target.src = imagenPorDefecto;
}

const agregarAlCarrito = async () => {
  try {
    agregando.value = true;
    const token = localStorage.getItem("token");
    
    await axios.post('http://localhost:8080/api/cart', {
      product_id: props.producto.id,
      quantity: 1
    }, {
      headers: { Authorization: `Bearer ${token}` }
    });

    let carritoLocal = JSON.parse(localStorage.getItem('carrito') || '[]');
    const index = carritoLocal.findIndex(item => item.id === props.producto.id);

    if (index !== -1) {
      carritoLocal[index].quantity += 1;
    } else {
      carritoLocal.push({ id: props.producto.id, quantity: 1 });
    }
    localStorage.setItem('carrito', JSON.stringify(carritoLocal));

    emit('agregado', props.producto.id);
    window.dispatchEvent(new CustomEvent('cart-updated'));
    
  } catch (error) {
    console.error("Error al agregar al carrito:", error);
    addToast(t('productoCard.errorCarrito'), 'error');
  } finally {
    agregando.value = false;
  }
};
</script>

<template>
  <div class="tarjeta-producto" @click="emit('ver', producto.id)">
    <div class="contenedor-imagen-producto">
      <div class="categorias-wrapper" v-if="producto.categories?.length">
        <span v-for="cat in producto.categories" :key="cat.id" class="categoria-badge">
          {{ traducirCategoria(cat.name) }}
        </span>
      </div>

      <img :src="obtenerImagen(producto.image_url)" :alt="producto.title" @error="manejarErrorImagen" />
      
      <button 
        v-if="!esMiProducto"
        class="boton-favorito" 
        :class="{ activo: isFavorite }" 
        @click.stop="emit('favorito', producto.id)"
      >
        <svg viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" fill="none"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
      </button>
    </div>

    <div class="informacion-producto">
      <h3 class="titulo">{{ producto.title }}</h3>
      <p class="desc">{{ producto.description }}</p>
      
      <div class="meta">
        <span class="contenedor-estrellas">
          <span class="estrella">&#9733;</span> 
          <span class="puntuacion">{{ producto.reviews_avg_rating || '0.0' }}</span>
        </span>
        <span class="reviews">({{ producto.reviews_count || 0 }} {{ $t('productoCard.resenas') }})</span>
      </div>

      <div v-if="!esMiProducto || modoPublico" class="autor-wrapper">
        <p class="autor">{{ $t('productoCard.por') }} <span class="nombre-vendedor">{{ producto.user?.name || $t('productoCard.vendedorPorDefecto') }}</span></p>
        
        <span 
          v-if="producto.user?.plan && producto.user.plan !== 'Básico'" 
          :class="['badge-vendedor-titulo', 'badge-exclusivo', 'badge-' + producto.user.plan.toLowerCase()]"
        >
          {{ producto.user.plan }}
        </span>
      </div>

      <hr class="linea-separacion">

      <div class="pie-tarjeta">
        <div class="precio-producto">{{ producto.price }}€</div>
        
        <button 
          v-if="!esMiProducto" 
          class="btn-comprar" 
          @click.stop="agregarAlCarrito"
          :disabled="agregando"
        >
          <svg v-if="!agregando" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
          <span>{{ agregando ? $t('productoCard.agregando') : $t('productoCard.agregar') }}</span>
        </button>
      </div>

      <div v-if="esMiProducto && !modoPublico" class="acciones-tarjeta">
        <button class="btn-action btn-ver" @click="emit('ver', producto.id)">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
          <span>{{ $t('productoCard.ver') }}</span>
        </button>

        <button class="btn-action btn-edit" @click="emit('editar', producto.id)">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
          <span>{{ $t('productoCard.editar') }}</span>
        </button>

        <button class="btn-action btn-delete" @click="emit('eliminar', producto.id)">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="48" height="48" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M3 6h18"></path>
            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
            <line x1="10" y1="11" x2="10" y2="17"></line>
            <line x1="14" y1="11" x2="14" y2="17"></line>
          </svg>
          <span>{{ $t('productoCard.borrar') }}</span>
        </button>
      </div>
    </div>
  </div>
</template>