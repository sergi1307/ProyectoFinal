<script setup>
import { ref, onMounted } from "vue";
import api from "@/services/api";

const ingresosTotales = ref(0);
const pedidosTotales = ref(0);
const ticketPromedio = ref(0);
const pedidosRecientes = ref([]);
const cargando = ref(true);

const obtenerDatosIngresos = async () => {
  try {
    cargando.value = true;
    const token = localStorage.getItem("token");

    const res = await api.get(
      `/admin/ingresos`,
      {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      },
    );

    ingresosTotales.value = res.data.ingresos_totales;
    pedidosTotales.value = res.data.pedidos_totales;
    ticketPromedio.value = res.data.ticket_promedio;
    pedidosRecientes.value = res.data.pedidos_recientes;
  } catch (error) {
    console.error("Error al cargar estadísticas:", error);
  } finally {
    cargando.value = false;
  }
};

onMounted(() => {
  obtenerDatosIngresos();
});
</script>

<template>
  <div class="panel-ingresos">
    <div class="grid-stats">
      <div class="stat-card">
        <span class="label">Ingresos Totales</span>
        <h2 class="value">{{ (ingresosTotales || 0).toFixed(2) }}€</h2>
      </div>

      <div class="stat-card">
        <span class="label">Pedidos Totales</span>
        <h2 class="value">{{ pedidosTotales || 0 }}</h2>
      </div>

      <div class="stat-card">
        <span class="label">Ticket Promedio</span>
        <h2 class="value">{{ (ticketPromedio || 0).toFixed(2) }}€</h2>
      </div>
    </div>

    <div class="tabla-container">
      <h3>Pedidos Recientes</h3>
      <div class="responsive-table">
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Fecha</th>
              <th>Usuario</th> <th>Total</th>
              <th>Estado</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="pedido in pedidosRecientes" :key="pedido.id">
              <td class="id-cell">#{{ pedido.id }}</td>
              <td>{{ new Date(pedido.fecha).toLocaleDateString() }}</td>
              <td>{{ pedido.usuario || 'N/A' }}</td>
              <td class="bold">{{ pedido.total }}€</td>
              <td>
                <span :class="['status-badge', pedido.status]">
                  {{ pedido.status }}
                </span>
              </td>
            </tr>
            <tr v-if="pedidosRecientes.length === 0">
              <td colspan="5" class="empty-msg">
                No hay pedidos registrados todavía.
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>