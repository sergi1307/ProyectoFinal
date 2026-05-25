<script setup>
import { ref, onMounted } from 'vue';
import api from "@/services/api";

const listaProductos = ref([]);
const vistaActual = ref('pendientes');

const obtenerImagen = (ruta) => {
  if (!ruta) return 'https://via.placeholder.com/150?text=Sin+Imagen';
  if (ruta.startsWith("http")) return ruta;
  return `${import.meta.env.VITE_STORAGE_BASE_URL || 'https://nexus-isaria.es/storage'}/${ruta}`;
};

const cargarVista = async (tipo) => {
  vistaActual.value = tipo;
  listaProductos.value = [];

  try {
    let token = localStorage.getItem("token");
    if (!token) return;
    token = token.replace(/^"(.*)"$/, '$1');

    const endpoint = tipo === 'pendientes' 
      ? '/admin/reports'
      : '/admin/reports/history';

    const response = await api.get(endpoint, {
      headers: { Authorization: `Bearer ${token}` }
    });

    listaProductos.value = response.data;
  } catch (error) {
    console.error(`Error cargando ${tipo}:`, error);
  }
};

const aprobar = async (id) => {
  if (!confirm("¿Seguro que quieres APROBAR este producto y descartar las denuncias?")) return;
  try {
    let token = localStorage.getItem("token").replace(/^"(.*)"$/, '$1');
    await api.post(`/admin/products/${id}/approve`, {}, {
      headers: { Authorization: `Bearer ${token}` }
    });
    listaProductos.value = listaProductos.value.filter(p => p.id !== id);
  } catch (error) {
    console.error("Error al aprobar:", error);
    alert("Error al aprobar el producto.");
  }
};

const rechazar = async (id) => {
  if (!confirm("¿Seguro que quieres RECHAZAR y ocultar este producto del marketplace?")) return;
  try {
    let token = localStorage.getItem("token").replace(/^"(.*)"$/, '$1');
    await api.post(`/admin/products/${id}/reject`, {}, {
      headers: { Authorization: `Bearer ${token}` }
    });
    listaProductos.value = listaProductos.value.filter(p => p.id !== id);
  } catch (error) {
    console.error("Error al rechazar:", error);
    alert("Error al rechazar el producto.");
  }
};

onMounted(() => {
  cargarVista('pendientes');
});
</script>

<template>
  <div class="moderacion-section fade-in">
    <header class="section-header">
      <h2>Moderación de Productos</h2>
      <p>Gestiona las denuncias pendientes o revisa el historial de productos retirados.</p>
    </header>

    <div class="tabs-container">
      <button 
        @click="cargarVista('pendientes')" 
        :class="{ activo: vistaActual === 'pendientes' }"
      >
        Denuncias Pendientes
      </button>
      <button 
        @click="cargarVista('historial')" 
        :class="{ activo: vistaActual === 'historial' }"
      >
        Historial de Retirados
      </button>
    </div>

    <div v-if="listaProductos.length === 0" class="empty-state">
      <p>No hay productos en esta sección en este momento.</p>
    </div>

    <div class="moderacion-grid" v-else>
      <article v-for="item in listaProductos" :key="item.id" class="moderacion-card">
        
        <div class="image-container">
          <img :src="obtenerImagen(item.image_url)" class="product-img" />
        </div>

        <div class="card-content">
          <div class="product-info">
            <h3 class="product-title">{{ item.title }}</h3>
            <p class="product-desc">{{ item.description }}</p>
            <p class="product-price">Precio: <span>{{ Number(item.price || 0).toFixed(2) }}€</span></p>
          </div>

          <div class="user-meta">
            <p><strong>Subido por:</strong> {{ item.user?.name || 'Desconocido' }}</p>
            <p><strong>Fecha:</strong> {{ new Date(item.created_at).toLocaleDateString() }}</p>
          </div>

          <div class="caja-denuncias" v-if="item.reports && item.reports.length > 0">
            <p class="titulo-denuncia">
              🚨 Denuncias ({{ item.reports.length }})
            </p>
            <ul class="lista-motivos">
              <li v-for="reporte in item.reports" :key="reporte.id">
                <strong>{{ reporte.reason }}</strong>
                <p v-if="reporte.details" class="detalle-txt">"{{ reporte.details }}"</p>
                <span class="reportador">Por: {{ reporte.user?.name || 'Anónimo' }}</span>
              </li>
            </ul>
          </div>

          <div class="card-actions" v-if="vistaActual === 'pendientes'">
            <button @click="aprobar(item.id)" class="btn-aprobar">Aprobar</button>
            <button @click="rechazar(item.id)" class="btn-rechazar">Rechazar</button>
          </div>

          <div class="historial-badge" v-else>
            <span>🛑 Retirado del Marketplace</span>
          </div>
        </div>
      </article>
    </div>
  </div>
</template>