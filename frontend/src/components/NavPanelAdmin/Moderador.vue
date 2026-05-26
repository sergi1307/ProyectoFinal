<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useToast } from '@/composables/useToast';

const { addToast } = useToast();
const listaProductos = ref([]);
const vistaActual = ref('pendientes');

const obtenerImagen = (ruta) => {
  if (!ruta) return 'https://via.placeholder.com/150?text=Sin+Imagen';
  if (ruta.startsWith("http")) return ruta;
  return `http://localhost:8080/storage/${ruta}`;
};

const cargarVista = async (tipo) => {
  vistaActual.value = tipo;
  listaProductos.value = [];

  try {
    let token = localStorage.getItem("token");
    if (!token) return;
    token = token.replace(/^"(.*)"$/, '$1');

    const endpoint = tipo === 'pendientes' 
      ? 'http://localhost:8080/api/admin/reports'
      : 'http://localhost:8080/api/admin/reports/history';

    const response = await axios.get(endpoint, {
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
    await axios.post(`http://localhost:8080/api/admin/products/${id}/approve`, {}, {
      headers: { Authorization: `Bearer ${token}` }
    });
    listaProductos.value = listaProductos.value.filter(p => p.id !== id);
  } catch (error) {
    console.error("Error al aprobar:", error);
    addToast(t('toasts.approveProductError'), 'error');
  }
};

const rechazar = async (id) => {
  if (!confirm("¿Seguro que quieres RECHAZAR y ocultar este producto del marketplace?")) return;
  try {
    let token = localStorage.getItem("token").replace(/^"(.*)"$/, '$1');
    await axios.post(`http://localhost:8080/api/admin/products/${id}/reject`, {}, {
      headers: { Authorization: `Bearer ${token}` }
    });
    listaProductos.value = listaProductos.value.filter(p => p.id !== id);
  } catch (error) {
    console.error("Error al rechazar:", error);
    addToast(t('toasts.rejectProductError'), 'error');
  }
};

onMounted(() => {
  cargarVista('pendientes');
});
</script>

<template>
  <div class="moderacion-section fade-in">
    <header class="section-header">
      <h2>{{ $t('moderacion.titulo') }}</h2>
      <p>{{ $t('moderacion.descripcion') }}</p>
    </header>

    <div class="tabs-container">
      <button 
        @click="cargarVista('pendientes')" 
        :class="{ activo: vistaActual === 'pendientes' }"
      >
        {{ $t('moderacion.tabPendientes') }}
      </button>
      <button 
        @click="cargarVista('historial')" 
        :class="{ activo: vistaActual === 'historial' }"
      >
        {{ $t('moderacion.tabHistorial') }}
      </button>
    </div>

    <div v-if="listaProductos.length === 0" class="empty-state">
      <p>{{ $t('moderacion.emptyState') }}</p>
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
            <p class="product-price">{{ $t('moderacion.precio') }} <span>{{ Number(item.price || 0).toFixed(2) }}€</span></p>
          </div>

          <div class="user-meta">
            <p><strong>{{ $t('moderacion.subidoPor') }}</strong> {{ item.user?.name || $t('moderacion.desconocido') }}</p>
            <p><strong>{{ $t('moderacion.fecha') }}</strong> {{ new Date(item.created_at).toLocaleDateString() }}</p>
          </div>

          <div class="caja-denuncias" v-if="item.reports && item.reports.length > 0">
            <p class="titulo-denuncia">
              🚨 {{ $t('moderacion.denuncias') }} ({{ item.reports.length }})
            </p>
            <ul class="lista-motivos">
              <li v-for="reporte in item.reports" :key="reporte.id">
                <strong>{{ reporte.reason }}</strong>
                <p v-if="reporte.details" class="detalle-txt">"{{ reporte.details }}"</p>
                <span class="reportador">{{ $t('moderacion.por') }} {{ reporte.user?.name || $t('moderacion.anonimo') }}</span>
              </li>
            </ul>
          </div>

          <div class="card-actions" v-if="vistaActual === 'pendientes'">
            <button @click="aprobar(item.id)" class="btn-aprobar">{{ $t('moderacion.btnAprobar') }}</button>
            <button @click="rechazar(item.id)" class="btn-rechazar">{{ $t('moderacion.btnRechazar') }}</button>
          </div>

          <div class="historial-badge" v-else>
            <span>🛑 {{ $t('moderacion.badgeRetirado') }}</span>
          </div>
        </div>
      </article>
    </div>
  </div>
</template>