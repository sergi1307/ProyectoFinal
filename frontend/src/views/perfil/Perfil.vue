<script setup>
import { ref, onMounted, computed } from "vue";
import axios from "axios";
import NavBar from "@/components/NavBar.vue";
import Footer from "@/components/Footer.vue";

const usuario = ref({
  nombre: "",
  email: "",
  rol: "",
  avatar: null,
  pedidos: 0,
  favoritos: 0,
});

const mostrarModal = ref(false);
const editandoUsuario = ref({ nombre: "", email: "" });
const guardando = ref(false);

const nuevaFotoArchivo = ref(null);
const vistaPreviaUrl = ref(null);

const manejarCambioArchivo = (event) => {
  const archivo = event.target.files[0];

  if (!archivo) {
    nuevaFotoArchivo.value = null;
    vistaPreviaUrl.value = null;
    return;
  }

  nuevaFotoArchivo.value = archivo;

  const lector = new FileReader();

  lector.onload = (e) => {
    vistaPreviaUrl.value = e.target.result;
  };

  lector.readAsDataURL(archivo);
};

const cargarDatos = async () => {
  const datosLocales = localStorage.getItem("usuario");
  if (datosLocales) {
    const user = JSON.parse(datosLocales);
    usuario.value.nombre = user.name;
    usuario.value.email = user.email;
    usuario.value.rol = user.role;
    usuario.value.avatar = user.avatar;

    editandoUsuario.value.nombre = user.name;
    editandoUsuario.value.email = user.email;
  }

  try {
    const token = localStorage.getItem("token");
    const respuesta = await axios.get("http://localhost:8080/api/perfil", {
      headers: { Authorization: `Bearer ${token}` },
    });
    usuario.value.pedidos = respuesta.data.pedidos;
    usuario.value.favoritos = respuesta.data.favoritos;
  } catch (error) {
    console.error("Error al cargar estadísticas:", error);
  }
};

const actualizarPerfil = async () => {
  try {
    guardando.value = true;
    const token = localStorage.getItem("token");

    const formulario = new FormData();
    formulario.append("nombre", editandoUsuario.value.nombre);
    formulario.append("email", editandoUsuario.value.email);

    if (nuevaFotoArchivo.value) {
      formulario.append("avatar", nuevaFotoArchivo.value);
    }

    const respuesta = await axios.post(
      "http://localhost:8080/api/perfil/update",
      formulario,
      {
        headers: {
          Authorization: `Bearer ${token}`,
          "Content-Type": "multipart/form-data",
        },
      },
    );

    localStorage.setItem("usuario", JSON.stringify(respuesta.data.user));

    window.dispatchEvent(new Event("perfil-actualizado"));

    usuario.value.nombre = respuesta.data.user.name;
    usuario.value.email = respuesta.data.user.email;
    usuario.value.avatar = respuesta.data.user.avatar;

    mostrarModal.value = false;
    guardando.value = false;
  } catch (error) {
    guardando.value = false;
    console.error("Error al actualizar:", error.response?.data || error);
    alert("Hubo un error al guardar los cambios.");
  }
};

const confirmarEliminarCuenta = async () => {
  const confirmado = confirm("¿Estás seguro de que quieres eliminar tu cuenta definitivamente? Esta acción borrará todos tus datos y no se puede deshacer.");
  
  if (!confirmado) return;

  try {
    guardando.value = true;
    const token = localStorage.getItem("token");
    
    await axios.delete("http://localhost:8080/api/perfil/delete", {
      headers: { 
        Authorization: `Bearer ${token}` 
      },
    });

    alert("Tu cuenta ha sido eliminada correctamente. ¡Hasta pronto!");
    
    localStorage.clear();
    
    window.location.href = "/";
    
  } catch (error) {
    guardando.value = false;
    console.error("Error al eliminar la cuenta:", error.response?.data || error);
    
    if (error.response?.status === 401) {
      alert("Tu sesión ha expirado. Por favor, vuelve a iniciar sesión.");
      localStorage.clear();
      window.location.href = "/login";
    } else {
      alert("No se ha podido eliminar la cuenta. Por favor, inténtalo de nuevo más tarde.");
    }
  }
};

const obtenerRutaAvatar = (ruta) => {
  if (!ruta || typeof ruta !== "string" || ruta.includes(".vue")) return null;

  const base = ruta.startsWith("http")
    ? ruta
    : `http://localhost:8080/storage/${ruta}`;

  return `${base}?t=${new Date().getTime()}`;
};

onMounted(cargarDatos);
</script>

<template>
  <NavBar></NavBar>

  <main class="contenedor-principal">
    <h1 class="titulo-pagina">Mi Perfil</h1>

    <div class="cuadricula-perfil">
      <section class="tarjeta-principal">
        <div class="cabecera-tarjeta">
          <h2>Información Personal</h2>
          <p>Administra tu información de cuenta</p>
        </div>

        <div class="contenedor-avatar">
          <div class="circulo-avatar">
            <img
              v-if="usuario.avatar && obtenerRutaAvatar(usuario.avatar)"
              :src="obtenerRutaAvatar(usuario.avatar)"
              class="imagen-perfil"
            />

            <svg
              v-else
              viewBox="0 0 24 24"
              width="48"
              height="48"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              class="icono-avatar-svg"
            >
              <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
              <circle cx="12" cy="7" r="4"></circle>
            </svg>
          </div>
        </div>

        <div class="lista-campos">
          <div class="campo-informacion">
            <div class="icono-campo">
              <svg
                viewBox="0 0 24 24"
                width="20"
                height="20"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
              >
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                <circle cx="12" cy="7" r="4"></circle>
              </svg>
            </div>
            <div class="textos-campo">
              <span class="etiqueta-campo">Nombre</span>
              <span class="valor-campo">{{ usuario.nombre }}</span>
            </div>
          </div>

          <div class="campo-informacion">
            <div class="icono-campo">
              <svg
                viewBox="0 0 24 24"
                width="20"
                height="20"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
              >
                <path
                  d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"
                ></path>
                <polyline points="22,6 12,13 2,6"></polyline>
              </svg>
            </div>
            <div class="textos-campo">
              <span class="etiqueta-campo">Email</span>
              <span class="valor-campo">{{ usuario.email }}</span>
            </div>
          </div>
          <div
            v-if="usuario.rol === 'admin'"
            class="campo-informacion bloque-admin"
          >
            <div class="icono-campo">
              <svg
                viewBox="0 0 24 24"
                width="20"
                height="20"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
              >
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
              </svg>
            </div>
            <div class="textos-campo">
              <span class="etiqueta-campo">Rol</span>
              <span class="valor-campo badge-admin">Administrador</span>
            </div>
          </div>
        </div>

        <div class="acciones-perfil">
          <button class="boton-editar" @click="mostrarModal = true">
            {{ usuario.rol === "admin" ? "Cambiar Foto" : "Editar Perfil" }}
          </button>

          <button
            v-if="usuario.rol !== 'admin'"
            class="boton-eliminar"
            @click="confirmarEliminarCuenta"
          >
            Eliminar Cuenta
          </button>
        </div>
      </section>

      <aside class="columna-estadisticas">
        <router-link to="/pedidos" class="tarjeta-estadistica clickable">
          <div class="circulo-icono-estadistica">
            <svg
              viewBox="0 0 24 24"
              width="24"
              height="24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
            >
              <path
                d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"
              ></path>
              <line x1="3" y1="6" x2="21" y2="6"></line>
              <path d="M16 10a4 4 0 0 1-8 0"></path>
            </svg>
          </div>
          <div class="textos-estadistica">
            <span class="numero-estadistica">{{ usuario.pedidos || 0 }}</span>
            <span class="etiqueta-estadistica">Pedidos</span>
          </div>
        </router-link>

        <router-link to="/favoritos" class="tarjeta-estadistica clickable">
          <div class="circulo-icono-estadistica">
            <svg
              viewBox="0 0 24 24"
              width="24"
              height="24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
            >
              <path
                d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"
              ></path>
            </svg>
          </div>
          <div class="textos-estadistica">
            <span class="numero-estadistica">{{ usuario.favoritos || 0 }}</span>
            <span class="etiqueta-estadistica">Favoritos</span>
          </div>
        </router-link>
      </aside>
    </div>

    <div v-if="mostrarModal" class="capa-modal">
      <div class="contenido-modal">
        <h3 class="titulo-modal">
          {{ usuario.rol === "admin" ? "Actualizar Foto" : "Editar Perfil" }}
        </h3>

        <form @submit.prevent="actualizarPerfil" class="formulario-edicion">
          <div class="seccion-vista-previa">
            <label class="etiqueta-foto">Foto de perfil</label>
            <div class="contenedor-previsualizacion">
              <div class="circulo-avatar-preview">
                <img
                  v-if="vistaPreviaUrl"
                  :src="vistaPreviaUrl"
                  class="imagen-perfil-preview"
                />
                <img
                  v-else-if="usuario.avatar"
                  :src="obtenerRutaAvatar(usuario.avatar)"
                  class="imagen-perfil-preview"
                />
                <svg
                  v-else
                  viewBox="0 0 24 24"
                  width="40"
                  height="40"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  class="icono-avatar-svg-preview"
                >
                  <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                  <circle cx="12" cy="7" r="4"></circle>
                </svg>
              </div>

              <label for="input-archivo-perfil" class="boton-subir-foto">
                <svg
                  viewBox="0 0 24 24"
                  width="16"
                  height="16"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                >
                  <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                  <polyline points="17 8 12 3 7 8"></polyline>
                  <line x1="12" y1="3" x2="12" y2="15"></line>
                </svg>
                Seleccionar nueva imagen
              </label>
              <input
                type="file"
                id="input-archivo-perfil"
                @change="manejarCambioArchivo"
                accept="image/*"
                style="display: none"
              />
            </div>
          </div>

          <template v-if="usuario.rol !== 'admin'">
            <div class="grupo-input">
              <label>Nombre Completo</label>
              <input v-model="editandoUsuario.nombre" type="text" required />
            </div>
            <div class="grupo-input">
              <label>Correo Electrónico</label>
              <input v-model="editandoUsuario.email" type="email" required />
            </div>
          </template>

          <div class="botones-modal">
            <button
              type="button"
              class="boton-cancelar"
              @click="mostrarModal = false"
            >
              Cancelar
            </button>
            <button type="submit" class="boton-guardar" :disabled="guardando">
              {{ guardando ? "Guardando..." : "Guardar Cambios" }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </main>
  <Footer></Footer>
</template>

<style lang="scss" scoped>
@import "@/assets/scss/perfil";
</style>
