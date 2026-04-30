<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";

const usuarios = ref([]);
const cargando = ref(true);

const cargarUsuarios = async () => {
  const token = localStorage.getItem("token");
  if (!token) return;

  try {
    cargando.value = true;
    const response = await axios.get("http://localhost:8080/api/users", {
      headers: {
        Authorization: `Bearer ${token}`,
        Accept: "application/json",
      },
    });
    usuarios.value = response.data;
  } catch (error) {
    console.error(
      "Error al cargar usuarios:",
      error.response?.data || error.message,
    );
  } finally {
    cargando.value = false;
  }
};

const alternarBan = async (user) => {
  if (user.active === undefined) user.active = true;

  const accion = user.active ? "banear" : "activar";
  if (!confirm(`¿Estás seguro de que quieres ${accion} a ${user.name}?`))
    return;

  const token = localStorage.getItem("token");
  try {
    await axios.post(
      `http://localhost:8080/api/users/${user.id}/toggle-status`,
      {},
      {
        headers: { Authorization: `Bearer ${token}` },
      },
    );

    user.active = !user.active;

    alert(`Usuario ${user.active ? "activado" : "baneado"} con éxito`);
  } catch (error) {
    console.error("Error al cambiar estado:", error);
    alert("No se pudo procesar la acción en el servidor.");
  }
};

onMounted(cargarUsuarios);
</script>

<template>
  <div class="gestion-container">
    <header class="gestion-header">
      <h2>Gestión de Usuarios</h2>
      <p>Gestiona los usuarios de la plataforma</p>
    </header>

    <div class="tabla-wrapper">
      <table class="tabla-usuarios">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Email</th>
            <th>Estado</th>
            <th>Rol</th>
            <th class="text-center">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="user in usuarios" :key="user.id">
            <td class="nombre-cell">{{ user.name }}</td>
            <td class="email-cell">{{ user.email }}</td>
            <td>
              <span
                :class="[
                  'badge',
                  user.active == 0 &&
                  user.active !== null &&
                  user.active !== undefined
                    ? 'badge-baneado'
                    : 'badge-activo',
                ]"
              >
                {{
                  user.active == 0 &&
                  user.active !== null &&
                  user.active !== undefined
                    ? "Baneado"
                    : "Activo"
                }}
              </span>
            </td>
            <td>
              <span class="badge-rol">{{ user.role }}</span>
            </td>
            <td class="acciones-cell">
              <div v-if="user.role !== 'admin'">
                <button
                  v-if="user.active != 0"
                  @click="alternarBan(user)"
                  class="btn-ban btn-rojo"
                  title="Banear usuario"
                >
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
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="4.93" y1="4.93" x2="19.07" y2="19.07"></line>
                  </svg>
                </button>

                <button
                  v-else
                  @click="alternarBan(user)"
                  class="btn-ban btn-verde"
                  title="Activar usuario"
                >
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
                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <line x1="19" y1="8" x2="19" y2="14"></line>
                    <line x1="16" y1="11" x2="22" y2="11"></line>
                  </svg>
                </button>
              </div>

              <span v-else class="texto-protegido">Protegido</span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<style scoped>
.gestion-container {
  background: white;
  border-radius: 12px;
  padding: 20px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
  margin-top: 15px;
}

.gestion-header h2 {
  margin: 0;
  font-size: 1.2rem;
  color: #1a202c;
}

.gestion-header p {
  color: #718096;
  font-size: 0.9rem;
  margin: 4px 0 15px 0;
}

.tabla-usuarios {
  width: 100%;
  border-collapse: collapse;
  text-align: center;
}

.tabla-usuarios th {
  padding: 10px;
  font-weight: 600;
  border-bottom: 1px solid #edf2f7;
  font-size: 0.85rem;
  text-align: center; 
  vertical-align: middle;
}

.tabla-usuarios td {
  padding: 10px;
  border-bottom: 1px solid #f7fafc;
  font-size: 0.9rem;
  text-align: center; 
  vertical-align: middle;
}

.nombre-cell {
  font-weight: 500;
  text-align: center;
}

.badge {
  padding: 2px 10px;
  border-radius: 6px;
  font-size: 0.8rem;
}

.badge-activo {
  background-color: #f0fff4;
  color: #22c55e;
}

.badge-baneado {
  background-color: #fff5f5;
  color: #e53e3e;
}

.badge-rol {
  background-color: #0097b2;
  color: white;
  padding: 2px 10px;
  border-radius: 6px;
  font-size: 0.8rem;
}

.btn-ban {
  border: none;
  padding: 8px;
  border-radius: 8px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s ease;
  margin: 0 auto;
}

.btn-rojo {
  background-color: #ef4444;
  color: white;
}

.btn-rojo:hover {
  background-color: #b03232;
  color: white;
}

.btn-verde {
  background-color: #22c55e;
  color: white;
}

.btn-verde:hover {
  background-color: #188942;
  color: white;
}

.acciones-cell {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100%;
  border-bottom: 1px solid #f7fafc;
}

.texto-protegido {
  color: #cbd5e1;
  font-size: 0.8rem;
  font-style: italic;
}

.text-center {
  text-align: center;
}
</style>