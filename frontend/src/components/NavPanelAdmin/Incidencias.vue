<script setup>
import { ref, computed, onMounted } from "vue";
import axios from "axios";

const incidencias = ref([]);
const loading = ref(true);
const busqueda = ref("");
const mostrandoHistorial = ref(false);

const modalVisible = ref(false);
const incidenciaSeleccionada = ref(null);

const stats = ref({
  total: 0,
  abiertas: 0,
  progreso: 0,
  resueltas: 0,
  cerradas: 0,
});

const cargarIncidencias = async () => {
  const token = localStorage.getItem("token");
  if (!token) return;

  try {
    loading.value = true;
    const response = await axios.get("http://localhost:8080/api/incidents", {
      headers: {
        Authorization: `Bearer ${token}`,
        Accept: "application/json",
      },
    });

    const datosOrdenados = response.data.sort((a, b) => a.id - b.id);
    incidencias.value = datosOrdenados;
    actualizarEstadisticas();
  } catch (error) {
    console.error("Error cargando incidencias:", error);
  } finally {
    loading.value = false;
  }
};

const cambiarEstado = async (id, nuevoEstado) => {
  const token = localStorage.getItem("token");
  try {
    const response = await axios.put(
      `http://localhost:8080/api/incidents/${id}`,
      { status: nuevoEstado },
      {
        headers: {
          Authorization: `Bearer ${token}`,
          Accept: "application/json",
        },
      },
    );

    if (response.status === 200 || response.status === 204) {
      const index = incidencias.value.findIndex((i) => i.id === id);
      if (index !== -1) {
        incidencias.value[index].status = nuevoEstado;
        actualizarEstadisticas();
        if (
          incidenciaSeleccionada.value &&
          incidenciaSeleccionada.value.id === id
        ) {
          incidenciaSeleccionada.value.status = nuevoEstado;
        }
      }
    }
  } catch (error) {
    console.error("Error al actualizar la base de datos:", error);
  }
};

const actualizarEstadisticas = () => {
  const data = incidencias.value;
  stats.value.total = data.length;
  stats.value.abiertas = data.filter((i) => i.status === "pendiente").length;
  stats.value.progreso = data.filter((i) => i.status === "en proceso").length;
  stats.value.resueltas = data.filter((i) => i.status === "resuelta").length;
  stats.value.cerradas = data.filter((i) => i.status === "cerrada").length;
};

const enviarmensaje = (email, asunto) => {
  if (!email) {
    alert("Este usuario no tiene un correo electrónico asociado.");
    return;
  }
  const mailtoLink = `mailto:${email}?subject=Respuesta a incidencia: ${encodeURIComponent(asunto)}`;
  window.location.href = mailtoLink;
};

const abrirModal = (incidencia) => {
  incidenciaSeleccionada.value = incidencia;
  modalVisible.value = true;
  document.body.style.overflow = "hidden";
};

const cerrarModal = () => {
  modalVisible.value = false;
  incidenciaSeleccionada.value = null;
  document.body.style.overflow = "";
};

const incidenciasFiltradas = computed(() => {
  let listaBase = incidencias.value.filter((incidencia) => {
    if (mostrandoHistorial.value) {
      return (
        incidencia.status === "resuelta" || incidencia.status === "cerrada"
      );
    } else {
      return (
        incidencia.status === "pendiente" || incidencia.status === "en proceso"
      );
    }
  });

  if (!busqueda.value) {
    return listaBase;
  }

  const termino = busqueda.value.toLowerCase();

  return listaBase.filter((incidencia) => {
    const id = String(incidencia.id);
    const asunto = (incidencia.subject || "").toLowerCase();
    const descripcion = (incidencia.description || "").toLowerCase();
    const estado = (incidencia.status || "").toLowerCase();
    const usuario = (incidencia.user?.name || "").toLowerCase();

    return (
      id.includes(termino) ||
      asunto.includes(termino) ||
      descripcion.includes(termino) ||
      estado.includes(termino) ||
      usuario.includes(termino)
    );
  });
});

onMounted(() => {
  cargarIncidencias();
});
</script>

<template>
  <div class="panel-incidencias">
    <div class="stats-grid">
      <div class="stat-card">
        <span class="label">Total</span>
        <span class="value">{{ stats.total }}</span>
      </div>
      <div class="stat-card abiertos">
        <span class="label text-blue">Abiertas</span>
        <span class="value text-blue">{{ stats.abiertas }}</span>
      </div>
      <div class="stat-card progreso">
        <span class="label text-orange">En Progreso</span>
        <span class="value text-orange">{{ stats.progreso }}</span>
      </div>
      <div class="stat-card resueltas">
        <span class="label text-green">Resueltas</span>
        <span class="value text-green">{{ stats.resueltas }}</span>
      </div>
      <div class="stat-card cerradas">
        <span class="label text-gray">Cerradas</span>
        <span class="value text-gray">{{ stats.cerradas }}</span>
      </div>
    </div>

    <div class="main-container">
      <div class="toolbar-incidencias">
        <div class="toolbar-left">
          <button @click="cargarIncidencias" class="btn-refresh">
            Actualizar Lista
          </button>
        </div>

        <div class="toolbar-right">
          <div class="search-container">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="18"
              height="18"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
              class="search-icon"
            >
              <circle cx="11" cy="11" r="8"></circle>
              <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            <input
              type="text"
              v-model="busqueda"
              placeholder="Buscar incidencia..."
              class="input-buscador"
            />
          </div>

          <button
            @click="mostrandoHistorial = !mostrandoHistorial"
            :class="['btn-history', { 'active-mode': mostrandoHistorial }]"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="18"
              height="18"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
              style="margin-right: 5px"
            >
              <template v-if="mostrandoHistorial">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
              </template>

              <template v-else>
                <path d="M3 3v5h5"></path>
                <path d="M3.05 13A9 9 0 1 0 6 5.3L3 8"></path>
                <path d="M12 7v5l4 2"></path>
              </template>
            </svg>

            {{ mostrandoHistorial ? "Volver atrás" : "Historial" }}
          </button>
        </div>
      </div>

      <div v-if="loading" class="empty-state-text" style="padding: 20px">
        Cargando incidencias...
      </div>

      <p
        v-else-if="incidencias.length === 0"
        class="empty-state-text"
        style="padding: 20px"
      >
        No hay incidencias registradas.
      </p>

      <div v-else class="table-responsive">
        <table class="data-table">
          <thead>
            <tr>
              <th>ID</th>
              <th class="hide-mobile">Usuario</th>
              <th>Asunto</th>
              <th class="hide-tablet">Descripción</th>
              <th>Estado</th>
              <th class="hide-mobile">Fecha</th>
              <th style="text-align: center">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="incidencia in incidenciasFiltradas" :key="incidencia.id">
              <td class="id-cell">#{{ incidencia.id }}</td>

              <td class="hide-mobile">
                {{ incidencia.user?.name || "ID: " + incidencia.user_id }}
              </td>

              <td>
                <strong>{{ incidencia.subject }}</strong>
              </td>

              <td class="desc-cell hide-tablet">
                {{ incidencia.description }}
              </td>

              <td>
                <span
                  :class="['status-badge', incidencia.status.replace(' ', '-')]"
                >
                  {{ incidencia.status }}
                </span>
              </td>

              <td class="hide-mobile">
                {{ new Date(incidencia.created_at).toLocaleDateString() }}
              </td>

              <td style="text-align: center">
                <div class="action-buttons">
                  <button
                    @click="abrirModal(incidencia)"
                    class="icon-btn view-btn"
                    title="Ver detalles"
                  >
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      width="18"
                      height="18"
                      viewBox="0 0 24 24"
                      fill="none"
                      stroke="currentColor"
                      stroke-width="2"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                    >
                      <path
                        d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"
                      ></path>
                      <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                  </button>

                  <div class="edit-dropdown">
                    <button class="icon-btn pencil-btn" title="Cambiar estado">
                      <svg
                        width="18"
                        height="18"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                      >
                        <path
                          d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"
                        ></path>
                      </svg>
                    </button>
                    <div class="dropdown-menu">
                      <button
                        @click="cambiarEstado(incidencia.id, 'pendiente')"
                      >
                        Pendiente
                      </button>
                      <button
                        @click="cambiarEstado(incidencia.id, 'en proceso')"
                      >
                        En proceso
                      </button>
                      <button @click="cambiarEstado(incidencia.id, 'resuelta')">
                        Resuelta
                      </button>
                      <button @click="cambiarEstado(incidencia.id, 'cerrada')">
                        Cerrada
                      </button>
                    </div>
                  </div>

                  <button
                    @click="
                      enviarmensaje(incidencia.user?.email, incidencia.subject)
                    "
                    class="icon-btn msg-btn"
                    title="Enviar correo"
                  >
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      width="18"
                      height="18"
                      viewBox="0 0 24 24"
                      fill="none"
                      stroke="currentColor"
                      stroke-width="2"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                    >
                      <rect width="20" height="16" x="2" y="4" rx="2" />
                      <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7" />
                    </svg>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div v-if="modalVisible" class="modal-overlay" @click.self="cerrarModal">
      <div class="modal-content">
        <div class="modal-header">
          <h2>Detalles de Incidencia #{{ incidenciaSeleccionada?.id }}</h2>
          <button @click="cerrarModal" class="close-btn">&times;</button>
        </div>

        <div class="modal-body" v-if="incidenciaSeleccionada">
          <div class="detail-group">
            <div class="detail-item">
              <span class="detail-label">Usuario:</span>
              <span class="detail-value">{{
                incidenciaSeleccionada.user?.name || "Desconocido"
              }}</span>
            </div>
            <div class="detail-item">
              <span class="detail-label">Email:</span>
              <span class="detail-value">{{
                incidenciaSeleccionada.user?.email || "Sin email"
              }}</span>
            </div>
          </div>

          <div class="detail-group">
            <div class="detail-item">
              <span class="detail-label">Fecha:</span>
              <span class="detail-value">{{
                new Date(incidenciaSeleccionada.created_at).toLocaleString()
              }}</span>
            </div>
            <div class="detail-item">
              <span class="detail-label">Estado:</span>
              <span
                :class="[
                  'status-badge',
                  incidenciaSeleccionada.status.replace(' ', '-'),
                ]"
              >
                {{ incidenciaSeleccionada.status }}
              </span>
            </div>
          </div>

          <div class="detail-block">
            <span class="detail-label">Asunto:</span>
            <div class="detail-box subject-box">
              {{ incidenciaSeleccionada.subject }}
            </div>
          </div>

          <div class="detail-block">
            <span class="detail-label">Descripción:</span>
            <div class="detail-box desc-box">
              {{ incidenciaSeleccionada.description }}
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button class="btn-cancel" @click="cerrarModal">Cerrar</button>
          <button
            class="btn-reply"
            @click="
              enviarmensaje(
                incidenciaSeleccionada?.user?.email,
                incidenciaSeleccionada?.subject,
              )
            "
          >
            Responder por correo
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.panel-incidencias {
  width: 100%;
}

.stats-grid {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 20px;
  margin-bottom: 25px;
}

.stat-card {
  flex: 0 0 calc(20% - 16px);
  background: #ffffff;
  padding: 24px;
  border-radius: 16px;
  border: 1px solid #e2e8f0;
  display: flex;
  flex-direction: column;
  gap: 8px;
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
  transition: transform 0.2s ease;
  min-width: 140px;
}

.stat-card:hover {
  transform: translateY(-2px);
}

@media (max-width: 1200px) {
  .stat-card {
    flex: 0 0 calc(33.333% - 14px);
  }
}

@media (max-width: 600px) {
  .stat-card {
    flex: 0 0 100%;
  }
}

.stat-card .label {
  font-size: 0.9rem;
  font-weight: 600;
  color: #64748b;
}
.stat-card .value {
  font-size: 2.2rem;
  font-weight: 700;
  color: #0f172a;
}
.text-blue {
  color: #2563eb !important;
}
.text-orange {
  color: #d97706 !important;
}
.text-green {
  color: #16a34a !important;
}
.text-gray {
  color: #94a3b8 !important;
}

.main-container {
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 18px;
  padding: 20px;
  overflow: visible !important;
  position: relative;
  z-index: 1;
}

.empty-state-text {
  color: #94a3b8;
  font-size: 1.1rem;
  font-weight: 500;
  text-align: center;
}

.table-responsive {
  width: 100%;
  overflow: visible !important;
  padding-bottom: 20px;
}

.data-table {
  width: 100%;
  border-collapse: separate;
  border-spacing: 0;
  color: #313131;
  text-align: left;
}

.data-table th {
  background-color: #f8fbff;
  color: #6b7280;
  padding: 14px 12px;
  border-bottom: 2px solid #0097b2;
  font-size: 0.9rem;
  white-space: nowrap;
}

.data-table td {
  padding: 12px;
  border-bottom: 1px solid #f1f5f9;
  vertical-align: middle;
  position: relative;
}

.data-table tr:hover {
  z-index: 101;
  position: relative;
}

.id-cell {
  font-weight: bold;
  color: #0097b2;
}

.desc-cell {
  max-width: 250px;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

@media (max-width: 1024px) {
  .hide-tablet {
    display: none !important;
  }
}
@media (max-width: 768px) {
  .hide-mobile {
    display: none !important;
  }
}

.action-buttons {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
}

.icon-btn {
  background: transparent;
  border: none;
  padding: 8px;
  border-radius: 6px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.2s;
}

.view-btn {
  color: #3b82f6;
}
.view-btn:hover {
  background: #eff6ff;
  color: #2563eb;
}

.pencil-btn {
  color: #0097b2;
}
.pencil-btn:hover {
  background-color: #f0fdff;
  color: #00869e;
}

.msg-btn {
  color: #64748b;
}
.msg-btn:hover {
  background: #f1f5f9;
  color: #0f172a;
}

.edit-dropdown {
  position: relative;
  display: inline-block;
  z-index: 100;
}
.dropdown-menu {
  visibility: hidden;
  opacity: 0;
  position: absolute;
  top: 100%;
  right: 0;
  background-color: #ffffff;
  min-width: 150px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
  border-radius: 8px;
  border: 1px solid #e2e8f0;
  z-index: 9999;
  padding: 6px;
  margin-top: 5px;
  transition: all 0.2s ease;
  transform: translateY(10px);
}
.edit-dropdown:hover .dropdown-menu {
  visibility: visible;
  opacity: 1;
  transform: translateY(0);
}
.dropdown-menu button {
  padding: 10px 14px;
  width: 100%;
  border: none;
  background: transparent;
  color: #334155;
  font-size: 0.85rem;
  font-weight: 600;
  text-align: left;
  cursor: pointer;
  border-radius: 6px;
  display: block;
  margin-bottom: 2px;
}
.dropdown-menu button:hover {
  background-color: #f0f9ff;
  color: #0097b2;
}
.dropdown-menu button:last-child {
  margin-bottom: 0;
}

.status-badge {
  padding: 6px 12px;
  border-radius: 12px;
  font-size: 0.75rem;
  font-weight: 700;
  white-space: nowrap;
}
.status-badge.pendiente {
  background: #e0f2fe;
  color: #0369a1;
}
.status-badge.en-proceso {
  background: #fff7ed;
  color: #ea580c;
}
.status-badge.resuelta {
  background: #f0fdf4;
  color: #16a34a;
}
.status-badge.cerrada {
  background: #f1f5f9;
  color: #475569;
}

.priority-badge {
  padding: 4px 10px;
  border-radius: 6px;
  font-size: 0.7rem;
  font-weight: 800;
  border: 1px solid transparent;
}
.priority-badge.alta {
  color: #ef4444;
  background: #fef2f2;
  border-color: #fecaca;
}
.priority-badge.media {
  color: #f59e0b;
  background: #fffbeb;
  border-color: #fde68a;
}
.priority-badge.baja {
  color: #10b981;
  background: #ecfdf5;
  border-color: #a7f3d0;
}

.btn-refresh {
  margin: 10px 0px !important;
  padding: 10px 20px;
  background-color: #0097b2;
  color: white;
  border: none;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
  transition: background-color 0.2s;
}
.btn-refresh:hover {
  background-color: #00869e;
}

.modal-overlay {
  position: fixed;
  inset: 0;
  background-color: rgba(15, 23, 42, 0.6);
  backdrop-filter: blur(4px);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
  padding: 20px;
}

.modal-content {
  background: #ffffff;
  width: 100%;
  max-width: 600px;
  border-radius: 16px;
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
  display: flex;
  flex-direction: column;
  max-height: 90vh;
  animation: modalFadeIn 0.3s ease-out;
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px 24px;
  border-bottom: 1px solid #f1f5f9;
}

.modal-header h2 {
  margin: 0;
  font-size: 1.25rem;
  font-weight: 800;
  color: #0f172a;
}

.close-btn {
  background: none;
  border: none;
  font-size: 1.8rem;
  color: #94a3b8;
  cursor: pointer;
  line-height: 1;
  padding: 0;
  transition: color 0.2s;
}
.close-btn:hover {
  color: #ef4444;
}

.modal-body {
  padding: 24px;
  overflow-y: auto;
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.detail-group {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
}

.detail-item {
  flex: 1;
  min-width: 200px;
  display: flex;
  flex-direction: column;
  gap: 6px;
}

.detail-label {
  font-size: 0.85rem;
  font-weight: 700;
  color: #64748b;
  text-transform: uppercase;
}

.detail-value {
  font-size: 1rem;
  color: #1e293b;
  font-weight: 500;
}

.detail-block {
  display: flex;
  flex-direction: column;
  gap: 6px;
}

.detail-box {
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  border-radius: 8px;
  padding: 12px 16px;
  font-size: 0.95rem;
  color: #334155;
  line-height: 1.5;
}

.subject-box {
  font-weight: 600;
  background: #f0fdff;
  border-color: #bae6fd;
  color: #00869e;
}

.modal-footer {
  padding: 16px 24px;
  border-top: 1px solid #f1f5f9;
  display: flex;
  justify-content: flex-end;
  gap: 12px;
}

.btn-cancel {
  background: white;
  border: 1px solid #cbd5e1;
  color: #475569;
  padding: 10px 18px;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
}
.btn-cancel:hover {
  background: #f8fafc;
}

.btn-reply {
  background: #0097b2;
  border: none;
  color: white;
  padding: 10px 18px;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
}
.btn-reply:hover {
  background: #00869e;
}

@keyframes modalFadeIn {
  from {
    opacity: 0;
    transform: scale(0.95);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}

.toolbar-incidencias {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
  width: 100%;
}

.toolbar-right {
  display: flex;
  align-items: center;
  gap: 15px;
}

.search-container {
  position: relative;
  display: flex;
  align-items: center;
}

.search-icon {
  position: absolute;
  left: 14px;
  color: #718096;
  pointer-events: none;
}

.input-buscador {
  padding: 10px 16px 10px 42px;
  border: 1px solid #cbd5e0;
  border-radius: 8px;
  font-size: 0.95rem;
  color: #2d3748;
  background-color: #f8fafc;
  outline: none;
  transition: all 0.3s ease;
  width: 260px;
}

.input-buscador:focus {
  background-color: #ffffff;
  border-color: #0cc0df;
  box-shadow: 0 0 0 3px rgba(12, 192, 223, 0.15);
}

.btn-history {
  display: flex;
  align-items: center;
  background-color: #0cc0df;
  color: #ffffff;
  border: none;
  padding: 10px 18px;
  border-radius: 8px;
  font-weight: 600;
  font-size: 0.95rem;
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: 0 4px 10px rgba(12, 192, 223, 0.25);
}

.btn-history svg {
  margin-right: 8px;
}

.btn-history:hover {
  background-color: #5ce1e6;
  box-shadow: 0 6px 14px rgba(12, 192, 223, 0.4);
  transform: translateY(-1px);
}

.btn-history:active {
  transform: translateY(1px);
  box-shadow: 0 2px 5px rgba(12, 192, 223, 0.2);
}
</style>
