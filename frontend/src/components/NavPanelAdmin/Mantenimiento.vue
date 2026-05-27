<script setup>
import { ref, onMounted } from 'vue';
import api from "@/services/api";
import { useToast } from '@/composables/useToast';

const { addToast } = useToast();
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
    addToast(t('toasts.statusPermissionError'), 'error');
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
      <h1 class="titulo-pagina">{{ $t('mantenimiento.tituloPagina') }}</h1>
      <div class="badge-sistema" :class="{ 'modo-critico': isMaintenance }">
        <span class="punto-latido"></span>
        {{ isMaintenance ? $t('mantenimiento.estadoActivo') : $t('mantenimiento.estadoLinea') }}
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
            <div class="pulse-ring" :class="isMaintenance ? 'ring-red' : 'ring-blue'"></div>
          </div>
          <div class="status-text-central">
            <h2>{{ isMaintenance ? $t('mantenimiento.accesoRestringido') : $t('mantenimiento.accesoPublico') }}</h2>
            <p>{{ isMaintenance ? $t('mantenimiento.descRestringido') : $t('mantenimiento.descPublico') }}</p>
          </div>
        </div>

        <div class="control-panel">
          <div class="control-card">
            <span class="control-label">{{ $t('mantenimiento.interruptorMaestro') }}</span>
            
            <div class="master-switch-wrapper">
              <label class="master-switch">
                <input type="checkbox" :checked="isMaintenance" @change="confirmarCambio" :disabled="loading">
                <div class="switch-design">
                  <span class="switch-text off">{{ $t('mantenimiento.off') }}</span>
                  <span class="switch-slider"></span>
                  <span class="switch-text on">{{ $t('mantenimiento.on') }}</span>
                </div>
              </label>
            </div>

            <div class="warning-box" v-if="!isMaintenance">
              <i class="fas fa-info-circle"></i>
              <span>{{ $t('mantenimiento.avisoLinea') }}</span>
            </div>
            <div class="warning-box critical" v-else>
              <i class="fas fa-exclamation-triangle"></i>
              <span>{{ $t('mantenimiento.avisoCritico') }}</span>
            </div>
          </div>

          <div class="logs-miniatura">
            <span class="tiny-label">{{ $t('mantenimiento.ultimaActividad') }}</span>
            <div class="log-entry">
              <i class="fas fa-history"></i>
              <span>{{ lastUpdate ? formatFecha(lastUpdate) : $t('mantenimiento.sinRegistros') }}</span>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</template>