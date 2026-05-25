<script setup>
import { ref, onMounted } from 'vue';
import api from "@/services/api";

const isMaintenance = ref(false);
const loading = ref(false);
const lastUpdate = ref(null);

const fetchStatus = async () => {
  try {
    const token = localStorage.getItem("token");
    const res = await api.get(`/admin/maintenance-status`, {
        headers: {
          Authorization: `Bearer ${token}`,
        },
    });
    isMaintenance.value = res.data.active;
    lastUpdate.value = res.data.updated_at;
  } catch (error) {
    console.error("Error al obtener estado:", error);
  }
};

const confirmarCambio = () => {
  const mensaje = isMaintenance.value 
    ? "¿Seguro que quieres abrir la web al público?" 
    : "Al activar el mantenimiento, los clientes NO podrán comprar. ¿Continuar?";
  
  if (confirm(mensaje)) {
    toggleStatus();
  } else {
    const checkbox = document.querySelector('.switch input');
    checkbox.checked = isMaintenance.value;
  }
};

const toggleStatus = async () => {
  loading.value = true;
  try {
    const token = localStorage.getItem("token");
    const nuevoEstado = !isMaintenance.value;
    await api.post(`/admin/toggle-maintenance`, { active: nuevoEstado }, {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    });
    isMaintenance.value = nuevoEstado;
    lastUpdate.value = new Date();
  } catch (error) {
    alert("No se pudo cambiar el estado. Verifica tus permisos.");
  } finally {
    loading.value = false;
  }
};

const formatFecha = (date) => {
  return new Date(date).toLocaleString();
};

onMounted(fetchStatus);
</script>

<template>
  <div class="panel-mantenimiento fade-in">
    <div class="cabecera-explorar">
      <h1 class="titulo-pagina">Gestión de Disponibilidad</h1>
      <div class="badge-sistema" :class="{ 'modo-critico': isMaintenance }">
        <span class="punto-latido"></span>
        {{ isMaintenance ? 'MANTENIMIENTO ACTIVO' : 'SISTEMA EN LÍNEA' }}
      </div>
    </div>

    <div class="main-container glass-container">
      <div class="mantenimiento-layout">
        
        <div class="status-visual">
          <div class="orb-container">
            <div class="orb" :class="isMaintenance ? 'orb-red' : 'orb-blue'">
              <i v-if="!isMaintenance" class="fas fa-rocket"></i>
              <i v-else class="fas fa-tools"></i>
            </div>
            <div class="pulse-ring"></div>
          </div>
          <div class="status-text-central">
            <h2>{{ isMaintenance ? 'Acceso Restringido' : 'Acceso Público' }}</h2>
            <p>{{ isMaintenance ? 'Solo el equipo administrativo puede acceder a Nexus.' : 'La plataforma opera con normalidad para todos los usuarios.' }}</p>
          </div>
        </div>

        <div class="control-panel">
          <div class="control-card">
            <span class="control-label">Interruptor Maestro</span>
            
            <div class="master-switch-wrapper">
              <label class="master-switch">
                <input type="checkbox" :checked="isMaintenance" @change="confirmarCambio" :disabled="loading">
                <div class="switch-design">
                  <span class="switch-text off">OFF</span>
                  <span class="switch-slider"></span>
                  <span class="switch-text on">ON</span>
                </div>
              </label>
            </div>

            <div class="warning-box" v-if="!isMaintenance">
              <i class="fas fa-info-circle"></i>
              <span>Activar esto redirigirá a todos los clientes a la página de espera.</span>
            </div>
            <div class="warning-box critical" v-else>
              <i class="fas fa-exclamation-triangle"></i>
              <span>El marketplace está oculto. Las ventas están pausadas.</span>
            </div>
          </div>

          <div class="logs-miniatura">
            <span class="tiny-label">ÚLTIMA ACTIVIDAD</span>
            <div class="log-entry">
              <i class="fas fa-history"></i>
              <span>{{ lastUpdate ? formatFecha(lastUpdate) : 'Sin registros recientes' }}</span>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</template>