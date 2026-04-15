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
    },
    esPropio: {
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
    console.log(actualId);
    console.log(props.producto.user_id);

    if (!actualId || !props.producto.user_id) return false;
    return String(props.producto.user_id) === String(actualId);
});

const obtenerImagen = (ruta) => {
    if (!ruta) return 'https://via.placeholder.com/300x400?text=Sin+Portada';
    if (ruta.startsWith('http')) return ruta;
    return `http://localhost:8080/storage/${ruta}`;
}
</script>

<template>
  <div class="tarjeta-producto">
    <div class="contenedor-imagen-producto">
      <div class="categorias-wrapper" v-if="producto.categories && producto.categories.length > 0">
        <span v-for="cat in producto.categories" :key="cat.id" class="categoria-badge">
          {{ cat.name }}
        </span>
      </div>

      <img :src="obtenerImagen(producto.image_url)" :alt="producto.title" />
      
      <button 
        v-if="!esPropio"
        class="boton-favorito" 
        :class="{ activo: isFavorite }" 
        @click.stop="emit('favorito', producto.id)"
        title="Añadir a favoritos"
      >
        <svg viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
        </svg>
      </button>
    </div>

    <div class="informacion-producto">
      <h3 class="titulo">{{ producto.title }}</h3>
      
      <div class="pie-tarjeta">
        <div class="valoracion"><span>⭐</span> {{ producto.rating || '4.8' }}</div>
        <div class="precio-producto">{{ producto.price }}€</div>
      </div>

      <div class="acciones-tarjeta">
        <button class="btn-action btn-ver" @click="emit('ver', producto.id)">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
            <span>Ver</span>
        </button>

        <button v-if="esMiProducto" class="btn-action btn-edit" @click="emit('editar', producto.id)">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
        </button>

        <button v-if="esMiProducto" class="btn-action btn-delete" @click="emit('eliminar', producto.id)">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2-2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
        </button>
      </div>
    </div>
  </div>
</template>

<style scoped lang="scss">
@use "@/assets/scss/variables" as *;

.tarjeta-producto {
  background: #ffffff;
  border-radius: 16px;
  overflow: hidden;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  display: flex;
  flex-direction: column;
  width: 100%;
  max-width: 280px;

  &:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 25px rgba(0, 0, 0, 0.1);
  }
}

.contenedor-imagen-producto {
  position: relative;
  width: 100%;
  aspect-ratio: 4 / 3;
  overflow: hidden;

  img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
  }

  &:hover img {
    transform: scale(1.05);
  }
}

.boton-favorito {
  position: absolute;
  top: -1.5rem;
  right: 10px;
  background: rgba(255, 255, 255, 0.9);
  border: none;
  border-radius: 50%;
  width: 36px;
  height: 36px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  color: #a0aec0;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
  transition: all 0.3s ease;
  z-index: 10;

  svg {
    width: 20px;
    height: 20px;
    fill: none;
    transition: fill 0.3s ease, color 0.3s ease;
  }

  &:hover {
    transform: scale(1.1);
    color: #ef4444;
  }

  &.activo {
    color: #ef4444;
    svg {
      fill: #ef4444;
    }
  }
}

.categorias-wrapper {
  position: absolute;
  top: 12px;
  left: 12px;
  display: flex;
  flex-direction: column;
  gap: 4px;
  z-index: 10;
}

.categoria-badge {
  background: $primary-blue;
  backdrop-filter: blur(4px);
  color: white;
  padding: 4px 10px;
  border-radius: 6px;
  font-size: 0.7rem;
  font-weight: 600;
  letter-spacing: 0.5px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.informacion-producto {
  padding: 16px;
  display: flex;
  flex-direction: column;
  flex-grow: 1;

  .titulo {
    font-size: 1.1rem;
    font-weight: 700;
    color: #2d3748;
    margin: 0 0 12px 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
}

.pie-tarjeta {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 12px;

  .valoracion {
    font-size: 0.9rem;
    font-weight: 600;
    color: #4a5568;
    display: flex;
    align-items: center;
    gap: 4px;
  }

  .precio-producto {
    font-size: 1.2rem;
    font-weight: 800;
    color: #1a202c; /* Puedes cambiar esto por tu variable $primary-blue si lo prefieres */
  }
}

.acciones-tarjeta {
  display: flex;
  gap: 8px;
  margin-top: auto;
  padding-top: 12px;
  border-top: 1px solid #edf2f7;

  .btn-action {
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
    border: none;
    height: 36px;
  }

  .btn-ver {
    flex-grow: 1;
    background: #f1f5f9;
    color: #475569;
    gap: 6px;
    font-weight: 600;
    font-size: 0.85rem;
    &:hover { background: #e2e8f0; color: #1e293b; }
  }

  .btn-edit, .btn-delete {
    width: 36px;
    flex-shrink: 0;
  }

  .btn-edit {
    background: #fffbeb;
    color: #d97706;
    &:hover { background: #fef3c7; }
  }

  .btn-delete {
    background: #fef2f2;
    color: #ef4444;
    &:hover { background: #fee2e2; }
  }
}
</style>