<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useToast } from '@/composables/useToast';

const { addToast } = useToast();
const solicitudes = ref([]);
const cargando = ref(true);
const token = localStorage.getItem('token');
const modalVisible = ref(false);
const solicitudSeleccionada = ref(null);

const cargarSolicitudes = async () => {
  try {
    cargando.value = true;
    const res = await axios.get('http://localhost:8080/api/admin/refund-requests', {
      headers: { Authorization: `Bearer ${token}` }
    });
    solicitudes.value = res.data;
  } catch (error) {
    console.error("Error cargando solicitudes:", error);
  } finally {
    cargando.value = false;
  }
};

const abrirModal = (solicitud) => {
  solicitudSeleccionada.value = solicitud;
  modalVisible.value = true;
  document.body.style.overflow = "hidden";
};

const cerrarModal = () => {
  modalVisible.value = false;
  solicitudSeleccionada.value = null;
  document.body.style.overflow = "";
};

const procesarAccion = async (idOrder, ruta) => {
  const msg = ruta === 'approve' ? '¿Confirmar el envío de dinero por PayPal?' : '¿Rechazar oficialmente esta solicitud?';
  if (!confirm(msg)) return;

  try {
    await axios.post(`http://localhost:8080/api/admin/refunds/${idOrder}/${ruta}`, {}, {
      headers: { Authorization: `Bearer ${token}` }
    });
    addToast('Operación finalizada', 'success');
    cerrarModal();
    cargarSolicitudes();
  } catch (error) {
    addToast("Error: " + (error.response?.data?.error || "Error de servidor", 'error'));
  }
};

onMounted(cargarSolicitudes);
</script>

<template>
  <div class="panel-reembolsos">
    <div class="tabla-container">
      <h3>Gestión de Devoluciones</h3>

      <div v-if="cargando" class="empty-state-text" style="padding: 2rem;">Conectando con el servidor...</div>
      <div v-else-if="solicitudes.length === 0" class="empty-state-text" style="padding: 2rem;">No hay solicitudes registradas.</div>

      <div v-else class="responsive-table">
        <table>
          <thead>
            <tr>
              <th>ID Pedido</th>
              <th>Importe</th>
              <th>Estado</th>
              <th>Fecha Solicitud</th>
              <th style="text-align: right">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="sol in solicitudes" :key="sol.id">
              <td class="id-cell">#{{ sol.order_id }}</td>
              <td class="bold">{{ sol.total }}€</td>
              <td>
                <span :class="['status-badge', sol.status]">
                  {{ sol.status === 'pending' ? 'Pendiente' : (sol.status === 'approved' ? 'Aprobado' : 'Rechazado') }}
                </span>
              </td>
              <td>{{ new Date(sol.created_at).toLocaleDateString() }}</td>
              <td style="text-align: right">
                <button @click="abrirModal(sol)" class="btn-action-view">Ver Detalles</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div v-if="modalVisible" class="modal-overlay" @click.self="cerrarModal">
      <div class="modal-content">
        <div class="modal-header">
          <h2>Detalles de Petición #{{ solicitudSeleccionada?.order_id }}</h2>
          <button @click="cerrarModal" class="close-btn-modal">&times;</button>
        </div>
        
        <div class="modal-body">
          <div style="margin-bottom: 1.5rem; display: flex; justify-content: space-between;">
             <div>
                <span class="detail-label">Importe a devolver</span>
                <p style="font-size: 1.5rem; font-weight: 900; color: #1e293b; margin: 0;">{{ solicitudSeleccionada?.total }}€</p>
             </div>
             <div style="text-align: right;">
                <span class="detail-label">Estado</span>
                <span :class="['status-badge', solicitudSeleccionada?.status]">
                   {{ solicitudSeleccionada?.status }}
                </span>
             </div>
          </div>

          <span class="detail-label">Motivo de la devolución</span>
          <div class="reason-box">
            {{ solicitudSeleccionada?.reason || 'El usuario no ha proporcionado un motivo detallado.' }}
          </div>
        </div>

        <div class="modal-footer">
          <button v-if="solicitudSeleccionada?.status !== 'pending'" class="btn-action-view" @click="cerrarModal">Cerrar Historial</button>
          
          <template v-else>
            <button class="btn-footer-secondary" @click="cerrarModal">Cancelar</button>
            <button class="btn-footer-reject" @click="procesarAccion(solicitudSeleccionada.order_id, 'reject')">Rechazar Petición</button>
            <button class="btn-footer-approve" @click="procesarAccion(solicitudSeleccionada.order_id, 'approve')">Aprobar y Pagar</button>
          </template>
        </div>
      </div>
    </div>
  </div>
</template>
