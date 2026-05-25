<script setup>
import { ref, computed, onMounted } from 'vue';
import api from "@/services/api";

const logs = ref([]);
const loading = ref(false);
const filterClase = ref('');
const filterAccion = ref('');
const selectedLog = ref(null);

const fetchLogs = async () => {
  const token = localStorage.getItem("token");
  loading.value = true;
  try {
    const response = await api.get(`/admin/logs`, {
        headers: {
            Authorization: `Bearer ${token}`
        }
    });
    logs.value = response.data;
  } catch (error) {
    console.error("Error al obtener logs:", error);
  } finally {
    loading.value = false;
  }
};

const clasesUnicas = computed(() => {
  return [...new Set(logs.value.map(l => l.clase))].sort();
});

const accionesFiltradas = computed(() => {
  if (!filterClase.value) return [];
  const filtrados = logs.value.filter(l => l.clase === filterClase.value);
  return [...new Set(filtrados.map(l => l.accion))].sort();
});

const logsFiltrados = computed(() => {
  return logs.value.filter(log => {
    const matchClase = !filterClase.value || log.clase === filterClase.value;
    const matchAccion = !filterAccion.value || log.accion === filterAccion.value;
    return matchClase && matchAccion;
  });
});

const formatFecha = (dateString) => {
  return new Date(dateString).toLocaleString('es-ES', {
    day: '2-digit', month: '2-digit', year: 'numeric',
    hour: '2-digit', minute: '2-digit', second: '2-digit'
  });
};

const openModal = (log) => { selectedLog.value = log; };
const closeModal = () => { selectedLog.value = null; };

onMounted(fetchLogs);
</script>

<template>
  <div class="panel-reembolsos fade-in">
    
    <div class="stats-grid" style="margin-bottom: 2rem;">
      <div class="stat-card">
        <span class="label">Total Eventos</span>
        <span class="value">{{ logs.length }}</span>
      </div>
      <div class="stat-card">
        <span class="label">Filtrados</span>
        <span class="value text-blue">{{ logsFiltrados.length }}</span>
      </div>
    </div>

    <div class="tabla-container">
      <h3>Historial de Auditoría</h3>

      <div class="toolbar-incidencias">
        <div class="toolbar-left">
          <div class="search-container">
            <select v-model="filterClase" @change="filterAccion = ''" class="input-buscador">
              <option value="">Todas las Clases</option>
              <option v-for="clase in clasesUnicas" :key="clase" :value="clase">{{ clase }}</option>
            </select>

            <select v-model="filterAccion" :disabled="!filterClase" class="input-buscador" style="margin-left: 10px;">
              <option value="">Todas las Acciones</option>
              <option v-for="accion in accionesFiltradas" :key="accion" :value="accion">{{ accion }}</option>
            </select>
          </div>
        </div>

        <div class="toolbar-right">
          <button class="btn-refresh" @click="fetchLogs" :disabled="loading">
            <i class="fas fa-sync-alt" :class="{ 'fa-spin': loading }"></i>
            Actualizar
          </button>
        </div>
      </div>

      <div class="responsive-table">
        <table v-if="!loading && logsFiltrados.length > 0">
          <thead>
            <tr>
              <th>Fecha</th>
              <th>Usuario</th>
              <th>Clase</th>
              <th>Acción</th>
              <th>Descripción</th>
              <th style="text-align: center;">Detalles</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="log in logsFiltrados" :key="log.id">
              <td class="id-cell">{{ formatFecha(log.created_at) }}</td>
              <td class="bold">{{ log.user ? log.user.name : 'Sistema' }}</td>
              <td><span class="status-badge resuelta">{{ log.clase }}</span></td>
              <td><span class="status-badge pendiente">{{ log.accion }}</span></td>
              <td class="desc-cell" :title="log.descripcion">{{ log.descripcion }}</td>
              <td style="text-align: center;">
                <button @click="openModal(log)" class="btn-action-view">
                  <i class="fas fa-eye"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>

        <div v-if="!loading && logsFiltrados.length === 0" class="empty-state-text" style="padding: 40px;">
          No se encontraron registros de auditoría.
        </div>
        
        <div v-if="loading" class="superglobal-loader">
          <div class="spinner"></div>
        </div>
      </div>
    </div>

    <div v-if="selectedLog" class="modal-overlay" @click.self="closeModal">
      <div class="modal-content">
        <div class="modal-header">
          <h2>Detalles del Registro</h2>
          <button @click="closeModal" class="close-btn-modal">&times;</button>
        </div>
        <div class="modal-body">
          <span class="detail-label">Descripción</span>
          <div class="reason-box">{{ selectedLog.descripcion }}</div>
          
          <div v-if="selectedLog.payload" style="margin-top: 15px;">
            <span class="detail-label">Datos JSON</span>
            <pre class="reason-box" style="font-size: 0.8rem; background: #1e293b; color: #38bdf8;">{{ JSON.stringify(selectedLog.payload, null, 2) }}</pre>
          </div>
        </div>
        <div class="modal-footer">
          <button @click="closeModal" class="btn-footer-secondary">Cerrar</button>
        </div>
      </div>
    </div>
  </div>
</template>