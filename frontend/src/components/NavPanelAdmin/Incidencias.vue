<script setup>
import { ref, computed, onMounted } from "vue";
import axios from "axios";
import { useToast } from '@/composables/useToast';

const { addToast } = useToast();
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
    addToast(t('toasts.noEmail'), 'error');
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
        <span class="label">{{ $t('incidencias.total') }}</span>
        <span class="value">{{ stats.total }}</span>
      </div>
      <div class="stat-card abiertos">
        <span class="label text-blue">{{ $t('incidencias.abiertas') }}</span>
        <span class="value text-blue">{{ stats.abiertas }}</span>
      </div>
      <div class="stat-card progreso">
        <span class="label text-orange">{{ $t('incidencias.enProgreso') }}</span>
        <span class="value text-orange">{{ stats.progreso }}</span>
      </div>
      <div class="stat-card resueltas">
        <span class="label text-green">{{ $t('incidencias.resueltas') }}</span>
        <span class="value text-green">{{ stats.resueltas }}</span>
      </div>
      <div class="stat-card cerradas">
        <span class="label text-gray">{{ $t('incidencias.cerradas') }}</span>
        <span class="value text-gray">{{ stats.cerradas }}</span>
      </div>
    </div>

    <div class="main-container">
      <div class="toolbar-incidencias">
        <div class="toolbar-left">
          <button @click="cargarIncidencias" class="btn-refresh">
            {{ $t('incidencias.btnActualizar') }}
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
              :placeholder="$t('incidencias.buscar')"
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

            {{ mostrandoHistorial ? $t('incidencias.volverAtras') : $t('incidencias.historial') }}
          </button>
        </div>
      </div>

      <div v-if="loading" class="empty-state-text" style="padding: 20px">
        {{ $t('incidencias.cargando') }}
      </div>

      <p
        v-else-if="incidencias.length === 0"
        class="empty-state-text"
        style="padding: 20px"
      >
        {{ $t('incidencias.noHay') }}
      </p>

      <div v-else class="table-responsive">
        <table class="data-table">
          <thead>
            <tr>
              <th>ID</th>
              <th class="hide-mobile">{{ $t('incidencias.thUsuario') }}</th>
              <th>{{ $t('incidencias.thAsunto') }}</th>
              <th class="hide-tablet">{{ $t('incidencias.thDescripcion') }}</th>
              <th>{{ $t('incidencias.thEstado') }}</th>
              <th class="hide-mobile">{{ $t('incidencias.thFecha') }}</th>
              <th style="text-align: center">{{ $t('incidencias.thAcciones') }}</th>
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
                    :title="$t('incidencias.titleVerDetalles')"
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
                    <button class="icon-btn pencil-btn" :title="$t('incidencias.titleCambiarEstado')">
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
                        {{ $t('incidencias.estadoPendiente') }}
                      </button>
                      <button
                        @click="cambiarEstado(incidencia.id, 'en proceso')"
                      >
                        {{ $t('incidencias.estadoProceso') }}
                      </button>
                      <button @click="cambiarEstado(incidencia.id, 'resuelta')">
                        {{ $t('incidencias.estadoResuelta') }}
                      </button>
                      <button @click="cambiarEstado(incidencia.id, 'cerrada')">
                        {{ $t('incidencias.estadoCerrada') }}
                      </button>
                    </div>
                  </div>

                  <button
                    @click="
                      enviarmensaje(incidencia.user?.email, incidencia.subject)
                    "
                    class="icon-btn msg-btn"
                    :title="$t('incidencias.titleEnviarCorreo')"
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
          <h2>{{ $t('incidencias.detallesTitulo') }} #{{ incidenciaSeleccionada?.id }}</h2>
          <button @click="cerrarModal" class="close-btn-modal">&times;</button>
        </div>

        <div class="modal-body" v-if="incidenciaSeleccionada">
          <div class="detail-group">
            <div class="detail-item">
              <span class="detail-label">{{ $t('incidencias.lblUsuario') }}</span>
              <span class="detail-value">{{
                incidenciaSeleccionada.user?.name || $t('incidencias.desconocido')
              }}</span>
            </div>
            <div class="detail-item">
              <span class="detail-label">{{ $t('incidencias.lblEmail') }}</span>
              <span class="detail-value">{{
                incidenciaSeleccionada.user?.email || $t('incidencias.sinEmail')
              }}</span>
            </div>
          </div>

          <div class="detail-group">
            <div class="detail-item">
              <span class="detail-label">{{ $t('incidencias.lblFecha') }}</span>
              <span class="detail-value">{{
                new Date(incidenciaSeleccionada.created_at).toLocaleString()
              }}</span>
            </div>
            <div class="detail-item">
              <span class="detail-label">{{ $t('incidencias.lblEstado') }}</span>
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
            <span class="detail-label">{{ $t('incidencias.lblAsunto') }}</span>
            <div class="detail-box subject-box">
              {{ incidenciaSeleccionada.subject }}
            </div>
          </div>

          <div class="detail-block">
            <span class="detail-label">{{ $t('incidencias.lblDescripcion') }}</span>
            <div class="detail-box desc-box">
              {{ incidenciaSeleccionada.description }}
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button class="btn-cancel" @click="cerrarModal">{{ $t('incidencias.btnCerrar') }}</button>
          <button
            class="btn-reply"
            @click="
              enviarmensaje(
                incidenciaSeleccionada?.user?.email,
                incidenciaSeleccionada?.subject,
              )
            "
          >
            {{ $t('incidencias.btnResponder') }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>