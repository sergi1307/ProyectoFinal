<script setup>
import { ref, computed, onMounted } from 'vue';
import api from "@/services/api";

const logs = ref([]);
const loading = ref(false);
const filterClase = ref("");
const filterAccion = ref("");
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
  return [...new Set(logs.value.map((l) => l.clase))].sort();
});

const accionesFiltradas = computed(() => {
  if (!filterClase.value) return [];
  const filtrados = logs.value.filter((l) => l.clase === filterClase.value);
  return [...new Set(filtrados.map((l) => l.accion))].sort();
});

const logsFiltrados = computed(() => {
  return logs.value.filter((log) => {
    const matchClase = !filterClase.value || log.clase === filterClase.value;
    const matchAccion =
      !filterAccion.value || log.accion === filterAccion.value;
    return matchClase && matchAccion;
  });
});

const generarClase = (texto) => {
  if (!texto) return "sistema";
  let t = texto
    .toLowerCase()
    .normalize("NFD")
    .replace(/[\u0300-\u036f]/g, "")
    .replace(/\s+/g, "");
  if (t === "enproceso") return "en-proceso";
  console.log("Clase generada:", t);
  return t;
};

const formatFecha = (dateString) => {
  return new Date(dateString).toLocaleString("es-ES", {
    day: "2-digit",
    month: "2-digit",
    year: "numeric",
    hour: "2-digit",
    minute: "2-digit",
    second: "2-digit",
  });
};

const openModal = (log) => {
  selectedLog.value = log;
};
const closeModal = () => {
  selectedLog.value = null;
};

onMounted(fetchLogs);
</script>

<template>
  <div class="panel-reembolsos fade-in">
    <div class="stats-grid" style="margin-bottom: 2rem">
      <div class="stat-card">
        <span class="label">{{ $t("auditoria.totalEventos") }}</span>
        <span class="value">{{ logs.length }}</span>
      </div>
      <div class="stat-card">
        <span class="label">{{ $t("auditoria.filtrados") }}</span>
        <span class="value text-blue">{{ logsFiltrados.length }}</span>
      </div>
    </div>

    <div class="tabla-container">
      <h3>{{ $t("auditoria.historial") }}</h3>

      <div class="toolbar-incidencias">
        <div class="toolbar-left">
          <div class="search-container">
            <select
              v-model="filterClase"
              @change="filterAccion = ''"
              class="input-buscador"
            >
              <option value="">{{ $t("auditoria.todasClases") }}</option>
              <option v-for="clase in clasesUnicas" :key="clase" :value="clase">
                {{ clase }}
              </option>
            </select>

            <select
              v-model="filterAccion"
              :disabled="!filterClase"
              class="input-buscador"
              style="margin-left: 10px"
            >
              <option value="">{{ $t("auditoria.todasAcciones") }}</option>
              <option
                v-for="accion in accionesFiltradas"
                :key="accion"
                :value="accion"
              >
                {{ accion }}
              </option>
            </select>
          </div>
        </div>

        <div class="toolbar-right">
          <button class="btn-refresh" @click="fetchLogs" :disabled="loading">
            <i class="fas fa-sync-alt" :class="{ 'fa-spin': loading }"></i>
            {{ $t("auditoria.actualizar") }}
          </button>
        </div>
      </div>

      <div class="responsive-table">
        <table v-if="!loading && logsFiltrados.length > 0">
          <thead>
            <tr>
              <th>{{ $t("auditoria.thFecha") }}</th>
              <th>{{ $t("auditoria.thUsuario") }}</th>
              <th>{{ $t("auditoria.thClase") }}</th>
              <th>{{ $t("auditoria.thAccion") }}</th>
              <th>{{ $t("auditoria.thDescripcion") }}</th>
              <th style="text-align: center">
                {{ $t("auditoria.thDetalles") }}
              </th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="log in logsFiltrados" :key="log.id">
              <td class="id-cell">{{ formatFecha(log.created_at) }}</td>
              <td class="bold">
                {{ log.user ? log.user.name : $t("auditoria.sistema") }}
              </td>

              <td>
                <span :class="['status-badge', generarClase(log.clase)]">
                  {{ log.clase }}
                </span>
              </td>

              <td>
                <span :class="['status-badge', generarClase(log.accion)]">
                  {{ log.accion }}
                </span>
              </td>

              <td class="desc-cell" :title="log.descripcion">
                {{ log.descripcion }}
              </td>
              <td style="text-align: center">
                <button
                  @click="openModal(log)"
                  class="btn-action-view"
                  :title="$t('auditoria.titleVerDetalles')"
                >
                  <i class="fas fa-eye"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>

        <div
          v-if="!loading && logsFiltrados.length === 0"
          class="empty-state-text"
          style="padding: 40px"
        >
          {{ $t("auditoria.noResultados") }}
        </div>

        <div v-if="loading" class="superglobal-loader">
          <div class="spinner"></div>
        </div>
      </div>
    </div>

    <div v-if="selectedLog" class="modal-overlay" @click.self="closeModal">
      <div class="modal-content">
        <div class="modal-header">
          <h2>{{ $t("auditoria.detallesRegistro") }}</h2>
          <button @click="closeModal" class="close-btn-modal">&times;</button>
        </div>
        <div class="modal-body">
          <span class="detail-label">{{ $t("auditoria.lblDescripcion") }}</span>
          <div class="reason-box">{{ selectedLog.descripcion }}</div>

          <div v-if="selectedLog.payload" style="margin-top: 15px">
            <span class="detail-label">{{ $t("auditoria.lblDatosJson") }}</span>
            <pre
              class="reason-box"
              style="font-size: 0.8rem; background: #1e293b; color: #38bdf8"
              >{{ JSON.stringify(selectedLog.payload, null, 2) }}</pre
            >
          </div>
        </div>
        <div class="modal-footer">
          <button @click="closeModal" class="btn-footer-secondary">
            {{ $t("auditoria.btnCerrar") }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<style lang="scss" scoped>
.status-badge {
  padding: 6px 16px !important;
  border-radius: 50px !important;
  font-size: 0.75rem !important;
  font-weight: 800 !important;
  text-transform: uppercase !important;
  display: inline-flex !important;
  align-items: center !important;
  justify-content: center !important;
  gap: 6px !important;
  white-space: nowrap !important;
  border: 1px solid currentColor !important;
}

.status-badge.reembolso {
  color: #059669 !important;
  background-color: rgba(5, 150, 105, 0.15) !important;
}

.status-badge.aprobar {
  color: #047857 !important;
  background-color: rgba(4, 120, 87, 0.15) !important;
}

.status-badge.mantenimientooff {
  color: #15803d !important;
  background-color: rgba(21, 128, 61, 0.15) !important;
}

.status-badge.activar {
  color: #166534 !important;
  background-color: rgba(22, 101, 52, 0.15) !important;
}

.status-badge.usuario {
  color: #0891b2 !important;
  background-color: rgba(8, 145, 178, 0.15) !important;
}

.status-badge.crear {
  color: #0284c7 !important;
  background-color: rgba(2, 132, 199, 0.15) !important;
}

.status-badge.carrito {
  color: #2563eb !important;
  background-color: rgba(37, 99, 235, 0.15) !important;
}

.status-badge.actualizarperfil {
  color: #1d4ed8 !important;
  background-color: rgba(29, 78, 216, 0.15) !important;
}

.status-badge.anadir {
  color: #4338ca !important;
  background-color: rgba(67, 56, 202, 0.15) !important;
}

.status-badge.producto {
  color: #7c3aed !important;
  background-color: rgba(124, 58, 237, 0.15) !important;
}

.status-badge.login {
  color: #9333ea !important;
  background-color: rgba(147, 51, 234, 0.15) !important;
}

.status-badge.registro {
  color: #c026d3 !important;
  background-color: rgba(192, 38, 211, 0.15) !important;
}

.status-badge.favoritos {
  color: #db2777 !important;
  background-color: rgba(219, 39, 119, 0.15) !important;
}

.status-badge.mantenimientoon {
  color: #dc2626 !important;
  background-color: rgba(220, 38, 38, 0.15) !important;
}

.status-badge.banear {
  color: #b91c1c !important;
  background-color: rgba(185, 28, 28, 0.15) !important;
}

.status-badge.rechazar {
  color: #991b1b !important;
  background-color: rgba(153, 27, 27, 0.15) !important;
}

.status-badge.incidencia {
  color: #ea580c !important;
  background-color: rgba(234, 88, 12, 0.15) !important;
}

.status-badge.denunciar {
  color: #92400e !important;
  background-color: rgba(146, 64, 14, 0.15) !important;
}

.status-badge.sistema {
  color: #475569 !important;
  background-color: rgba(71, 85, 105, 0.15) !important;
}

.status-badge.resena,
.status-badge.reseña {
  color: #ca8a04 !important;
  background-color: rgba(202, 138, 4, 0.15) !important;
}
</style>
