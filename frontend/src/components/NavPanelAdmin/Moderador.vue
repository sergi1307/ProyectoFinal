<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const productosPendientes = ref([]);

const fetchReports = async () => {
  try {
    const response = await axios.get('/api/admin/reports');
    productosPendientes.value = response.data.map(p => ({
      id: p.id,
      nombre: p.title,
      descripcion: p.description,
      precio: p.price,
      imagen: p.image_url,
      subidoPor: p.user.name,
      fecha: new Date(p.created_at).toLocaleString(),
      motivos: p.reports.map(r => r.reason).join(', ')
    }));
  } catch (error) {
    console.error("Error cargando denuncias", error);
  }
};

const aprobar = async (id) => {
  await axios.post(`/api/admin/products/${id}/approve`);
  productosPendientes.value = productosPendientes.value.filter(p => p.id !== id);
};

const rechazar = async (id) => {
  await axios.post(`/api/admin/products/${id}/reject`);
  productosPendientes.value = productosPendientes.value.filter(p => p.id !== id);
};

onMounted(fetchReports);
</script>

<template>
  <div class="moderacion-section fade-in">
    <header class="section-header">
      <h2>Moderación de Imágenes</h2>
      <p>Modera las imágenes, descripciones y precios subidos por los usuarios.</p>
    </header>

    <div v-if="productosPendientes.length === 0" class="empty-state">
      <p>No hay productos pendientes de revisión.</p>
    </div>

    <div class="moderacion-grid">
      <article v-for="item in productosPendientes" :key="item.id" class="moderacion-card">
        <div class="image-container">
          <img :src="item.imagen" :alt="item.nombre" class="product-img" />
        </div>

        <div class="card-content">
          <div class="product-info">
            <h3 class="product-title">{{ item.nombre }}</h3>
            <p class="product-desc">{{ item.descripcion }}</p>
            <p class="product-price">Precio: <span>${{ item.precio.toFixed(2) }}</span></p>
          </div>

          <div class="user-meta">
            <p><strong>Subido por:</strong> {{ item.subidoPor }}</p>
            <p><strong>Fecha:</strong> {{ item.fecha }}</p>
          </div>

          <div class="card-actions">
            <button @click="aprobar(item.id)" class="btn-aprobar">Aprobar</button>
            <button @click="rechazar(item.id)" class="btn-rechazar">Rechazar</button>
          </div>
        </div>
      </article>
    </div>
  </div>
</template>