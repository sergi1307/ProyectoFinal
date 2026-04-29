<script setup>
import { ref } from "vue";
import NavBar from "./NavBar.vue";
import Footer from "./Footer.vue";
import Incidencias from "./NavPanelAdmin/Incidencias.vue";
import Ingresos from "./NavPanelAdmin/Ingresos.vue";
import Usuarios from "./NavPanelAdmin/Usuarios.vue";
import Reembolsos from "./NavPanelAdmin/Reembolsos.vue";

const activeTab = ref("incidencias");

const tabs = [
  { id: "incidencias", label: "Incidencias" },
  { id: "usuarios", label: "Usuarios" },
  { id: "ingresos", label: "Ingresos" },
  { id: "reembolsos", label: "Reembolsos" },
  { id: "imagenes", label: "Imágenes" },
  { id: "mantenimiento", label: "Mantenimiento" },
  { id: "logs", label: "Logs" },
];
</script>

<template>
  <NavBar></NavBar>
  <div class="admin-panel">
    <div class="submenu-container">
      <nav class="submenu desktop-tabs">
        <button
          v-for="tab in tabs"
          :key="tab.id"
          :class="['tab-btn', { active: activeTab === tab.id }]"
          @click="activeTab = tab.id"
        >
          <span class="icon-wrapper">
            <svg
              v-if="tab.id === 'incidencias'"
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
                d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"
              ></path>
              <line x1="9" y1="9" x2="15" y2="9"></line>
              <line x1="9" y1="13" x2="15" y2="13"></line>
            </svg>
            <svg
              v-if="tab.id === 'usuarios'"
              width="18"
              height="18"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
            >
              <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
              <circle cx="9" cy="7" r="4"></circle>
              <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
              <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            </svg>
            <svg
              v-if="tab.id === 'ingresos'"
              width="18"
              height="18"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
            >
              <line x1="12" y1="1" x2="12" y2="23"></line>
              <path
                d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"
              ></path>
            </svg>
            <svg
              v-if="tab.id === 'reembolsos'"
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
                d="M10 1.5l-4 3 4 3.5"
                transform="rotate(-10, 7.5, 4.5)"
              ></path>
              <path d="M20 12c0-4.4-3.6-8-8-8-0.8 0-1.6.1-2.4.3"></path>
              <path
                d="M14 22.5l4-3-4-3.5"
                transform="rotate(-10, 16.5, 19.5)"
              ></path>
              <path d="M4 12c0 4.4 3.6 8 8 8 0.8 0 1.6-.1 2.4-.3"></path>
            </svg>
            <svg
              v-if="tab.id === 'imagenes'"
              width="18"
              height="18"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
            >
              <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
              <circle cx="8.5" cy="8.5" r="1.5"></circle>
              <polyline points="21 15 16 10 5 21"></polyline>
            </svg>
            <svg
              v-if="tab.id === 'mantenimiento'"
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
                d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.1a2 2 0 0 1-1-1.72v-.51a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"
              ></path>
              <circle cx="12" cy="12" r="3"></circle>
            </svg>
            <svg
              v-if="tab.id === 'logs'"
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
                d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"
              ></path>
              <polyline points="14 2 14 8 20 8"></polyline>
              <line x1="16" y1="13" x2="8" y2="13"></line>
              <line x1="16" y1="17" x2="8" y2="17"></line>
              <polyline points="10 9 9 9 8 9"></polyline>
            </svg>
          </span>
          {{ tab.label }}
        </button>
      </nav>

      <div class="mobile-tab-selector">
        <select v-model="activeTab">
          <option v-for="tab in tabs" :key="tab.id" :value="tab.id">
            Sección: {{ tab.label }}
          </option>
        </select>
        <div class="select-arrow">
          <svg
            viewBox="0 0 24 24"
            width="18"
            height="18"
            fill="none"
            stroke="currentColor"
            stroke-width="2.5"
            stroke-linecap="round"
            stroke-linejoin="round"
          >
            <polyline points="6 9 12 15 18 9"></polyline>
          </svg>
        </div>
      </div>
    </div>

    <div class="tab-content">
      <div v-if="activeTab === 'incidencias'" class="fade-in">
        <Incidencias />
      </div>

      <div v-else-if="activeTab === 'usuarios'" class="fade-in">
        <Usuarios />
      </div>

      <div v-else-if="activeTab === 'ingresos'" class="fade-in">
        <Ingresos />
      </div>

      <div v-else-if="activeTab === 'reembolsos'" class="fade-in">
        <Reembolsos />
      </div>

      <div v-else class="fade-in">
        <div class="main-container">
          <p class="empty-state-text">
            Sección de {{ activeTab }} en desarrollo...
          </p>
        </div>
      </div>
    </div>
  </div>
  <Footer></Footer>
</template>

<style scoped>
.admin-panel {
  padding: 40px 20px;
  background-color: #f8fafc;
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  align-items: center;
  font-family:
    "Inter",
    system-ui,
    -apple-system,
    sans-serif;
}
.submenu-container {
  display: flex;
  justify-content: center;
  margin-bottom: 35px;
  width: 100%;
}
.submenu {
  display: flex;
  align-items: center;
  gap: 12px;
  background: #f1f5f9;
  padding: 8px;
  border-radius: 20px;
  border: 1px solid #e2e8f0;
  width: fit-content;
  justify-content: center;
}
.tab-btn {
  margin: 0 !important;
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 12px 28px;
  border: none;
  background: transparent;
  color: #1e293b;
  font-weight: 500;
  font-size: 0.95rem;
  cursor: pointer;
  border-radius: 14px;
  transition: all 0.3s ease-in-out;
  white-space: nowrap;
}
.icon-wrapper {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 18px;
  height: 18px;
  color: inherit;
}
.tab-btn.active {
  background: #ffffff;
  color: #0f172a;
  box-shadow:
    0 10px 15px -3px rgba(0, 0, 0, 0.08),
    0 4px 6px -2px rgba(0, 0, 0, 0.04);
}
.tab-btn:hover:not(.active) {
  background: rgba(255, 255, 255, 0.6);
  color: #0f172a;
}
.tab-content {
  width: 100%;
  max-width: 1400px;
}

.main-container {
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 18px;
  padding: 20px 20px;
  text-align: center;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
}
.empty-state-text {
  color: #94a3b8;
  font-size: 1.1rem;
  font-weight: 500;
}
.fade-in {
  animation: fadeIn 0.3s ease-out;
}
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(5px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.mobile-tab-selector {
  display: none;
}

@media (max-width: 1050px) {
  .desktop-tabs {
    display: none !important;
  }
  .mobile-tab-selector {
    display: block;
    position: relative;
    width: 90%;
    max-width: 350px;
    margin: 0 auto;
    transition: transform 0.2s ease;
  }
  .mobile-tab-selector:hover {
    transform: translateY(-2px);
  }
  .mobile-tab-selector select {
    width: 100%;
    appearance: none;
    -webkit-appearance: none;
    background: #ffffff;
    border: 2px solid #e2e8f0;
    border-radius: 16px;
    padding: 14px 50px 14px 20px;
    font-size: 1.05rem;
    font-weight: 700;
    color: #1e293b;
    cursor: pointer;
    box-shadow:
      0 4px 10px rgba(0, 0, 0, 0.03),
      0 1px 3px rgba(0, 0, 0, 0.05);
    outline: none;
    transition: all 0.3s ease;
  }
  .mobile-tab-selector select:hover {
    border-color: #cbd5e1;
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.06);
  }
  .mobile-tab-selector select:focus {
    border-color: #0097b2;
    box-shadow: 0 0 0 4px rgba(0, 151, 178, 0.15);
  }
  .select-arrow {
    position: absolute;
    right: 14px;
    top: 50%;
    transform: translateY(-50%);
    pointer-events: none;
    color: #0097b2;
    background-color: #f0fdff;
    padding: 6px;
    border-radius: 50%;
    display: flex;
    transition: all 0.3s ease;
  }
  .mobile-tab-selector select:hover + .select-arrow {
    background-color: #e0f2fe;
    color: #0284c7;
  }
}
@media (max-width: 480px) {
  .submenu-container {
    padding: 0 10px;
  }
  .mobile-tab-selector {
    max-width: 100%;
  }
}
</style>
