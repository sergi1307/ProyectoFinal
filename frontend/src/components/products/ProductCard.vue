<script setup>
import { defineProps, defineEmits, computed } from 'vue';

const props = defineProps({
    producto: {
        type: Object,
        required: true
    },
    isFavorite: {
        type: Boolean,
        default: false
    }
});

const emit = defineEmits(['eliminar', 'ver', 'editar', 'favorito']);

const obtenerUsuarioId = () => {
  const userData = localStorage.getItem('usuario');
  if (!userData) return null;
  try {
    const user = JSON.parse(userData);
    return user.id || null;
  } catch (error) {
    return null;
  }
}

const esMiProducto = computed(() => {
    const actualId = obtenerUsuarioId();
    return props.producto.user_id === actualId;
});

const obtenerImagen = (ruta) => {
    if (!ruta) return 'https://via.placeholder.com/300x400?text=Sin+Portada';
    if (ruta.startsWith('http')) return ruta;
    return `http://localhost:8080/storage/${ruta}`;
}
</script>

<template>
    <div class="producto-card">
        <button 
            v-if="esMiProducto"
            class="btn-favorito" 
            :class="{ 'is-active': isFavorite }"
            @click.stop="emit('favorito', producto.id)"
            title="Añadir a favoritos"
        >
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" :fill="isFavorite ? 'currentColor' : 'none'" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
            </svg>
        </button>

        <div class="categorias-wrapper" v-if="producto.categories && producto.categories.length > 0">
            <span v-for="cat in producto.categories" :key="cat.id" class="categoria-badge">
                {{ cat.name }}
            </span>
        </div>

        <div class="card-image">
            <img :src="obtenerImagen(producto.image_url)" :alt="producto.title">
        </div>

        <div class="card-body">
            <div class="info-principal">
                <h3 class="producto-titulo">{{ producto.title }}</h3>
                <p class="producto-desc">{{ producto.description }}</p>
            </div>

            <div class="precio-container">
                <span class="monto">{{ producto.price }}</span>
                <span class="moneda">€</span>
            </div>

            <div class="card-actions">
                <button class="btn-action btn-ver" @click="emit('ver', producto.id)">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    <span>Ver</span>
                </button>

                <button class="btn-action btn-edit" @click="emit('editar', producto.id)">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                </button>

                <button class="btn-action btn-delete" @click="emit('eliminar', producto.id)">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                </button>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
@use "@/assets/scss/variables" as *;

.producto-card {
  background: white;
  border-radius: 14px;
  border: 1px solid #edf2f7;
  overflow: hidden;
  position: relative;
  display: flex;
  flex-direction: column;
  transition: all 0.3s ease;
  height: 100%;

  &:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 24px rgba(0,0,0,0.06);
    border-color: #e2e8f0;
  }
}

.categorias-wrapper {
  position: absolute;
  top: 10px;
  right: 10px;
  display: flex;
  flex-direction: column;
  align-items: flex-end;
  gap: 4px;
  z-index: 2;
  max-width: 80% ;
}

.categoria-badge {
  background: rgba($primary-blue, 0.85);
  backdrop-filter: blur(8px);
  color: white;
  padding: 4px 10px;
  border-radius: 6px;
  font-size: 0.65rem;
  font-weight: 700;
  text-transform: uppercase;
  white-space: nowrap;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  border: 1px solid rgba(255, 255, 255, 0.2);
}

.card-image {
  width: 100%;
  aspect-ratio: 4 / 5;
  background: #f1f5f9;
  img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
}

.card-body {
  padding: 1.5rem;
  display: flex;
  flex-direction: column;
  flex-grow: 1;
}

.info-principal {
  margin-bottom: 1.25rem;
  .producto-titulo {
    font-size: 1.15rem;
    font-weight: 700;
    color: #1a202c;
    margin-bottom: 0.5rem;
    line-height: 1.3;
  }
  .producto-desc {
    font-size: 0.85rem;
    color: #718096;
    display: -webkit-box;
    -webkit-box-orient: vertical;
    overflow: hidden;
    line-height: 1.5;
  }
}

.precio-container {
  margin-top: auto;
  margin-bottom: 1.5rem;
  display: flex;
  align-items: baseline;
  gap: 2px;
  color: $primary-blue;
  .moneda { font-size: 1.6rem; font-weight: 600; }
  .monto { font-size: 1.6rem; font-weight: 800; }
}

.card-actions {
  display: flex;
  gap: 0.6rem;
  align-items: center;

  .btn-action {
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.2s ease;
    border: 1px solid transparent;
    padding: 0.6rem;
    height: 42px;
  }

  .btn-ver {
    flex-grow: 1;
    background: #f8fafc;
    border-color: #e2e8f0;
    color: #475569;
    gap: 8px;
    font-weight: 700;
    font-size: 0.9rem;
    &:hover {
      background: white;
      border-color: $primary-blue;
      color: $primary-blue;
    }
  }

  .btn-edit {
    width: 42px;
    background: #fffbeb;
    border-color: #fef3c7;
    color: #d97706;
    &:hover { background: #fef3c7; border-color: #fbbf24; }
  }

  .btn-delete {
    width: 42px;
    background: #fef2f2;
    border-color: #fee2e2;
    color: #ef4444;
    &:hover { background: #fee2e2; border-color: #f87171; }
  }
}

.btn-favorito {
  position: absolute;
  top: 10px;
  left: 10px;
  z-index: 3;
  background: white;
  border: none;
  width: 36px;
  height: 36px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  color: #718096;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  transition: all 0.3s ease;

  &:hover {
    transform: scale(1.1);
    color: #ef4444; // Rojo al pasar el ratón
  }

  &.is-active {
    color: #ef4444;
    background: white;
    animation: heartBeat 0.3s ease-in-out;
  }
}

// Animación opcional para cuando se marca
@keyframes heartBeat {
  0% { transform: scale(1); }
  50% { transform: scale(1.3); }
  100% { transform: scale(1.1); }
}
</style>