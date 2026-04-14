<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const incidents = ref([]);
const loading = ref(true);

const fetchIncidents = async () => {
  try {
    const response = await axios.get('/incidents');
    incidents.value = response.data;
  } catch (error) {
    console.error("Error cargando incidencias:", error);
  } finally {
    loading.value = false;
  }
};

onMounted(fetchIncidents);
</script>

<template>
  <div class="admin-container">
    <h1>Panel de Incidencias (Admin)</h1>
    
    <div v-if="loading">Cargando incidencias...</div>
    
    <div v-else-if="incidents.length === 0">No hay incidencias registradas.</div>
    
    <table v-else border="1" style="width: 100%; border-collapse: collapse; margin-top: 20px;">
      <thead>
        <tr style="background-color: #0f172a; color: white;">
          <th>Usuario</th>
          <th>Asunto</th>
          <th>Prioridad</th>
          <th>Descripción</th>
          <th>Fecha</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="incident in incidents" :key="incident.id">
          <td>{{ incident.user?.name || 'Desconocido' }}</td>
          <td>{{ incident.subject }}</td>
          <td>
            <span :style="{ color: incident.priority === 'alta' ? 'red' : 'black' }">
              {{ incident.priority.toUpperCase() }}
            </span>
          </td>
          <td>{{ incident.description }}</td>
          <td>{{ new Date(incident.created_at).toLocaleString() }}</td>
        </tr>
      </tbody>
    </table>
    
    <button @click="fetchIncidents" style="margin-top: 20px; padding: 10px;">
      Actualizar Lista
    </button>
  </div>
</template>

<style scoped>
.admin-container {
  padding: 2rem;
  font-family: sans-serif;
}
th, td {
  padding: 10px;
  text-align: left;
}
</style>