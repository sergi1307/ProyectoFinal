<script setup>
import NavBar from "@/components/NavBar.vue";
import Footer from "@/components/Footer.vue";
import { onMounted, ref } from "vue";
import { useRouter } from "vue-router";
import axios from "axios";

const router = useRouter();

const cargando = ref(false);
const vistaPrevia = ref(null);
const archivoImagen = ref(null);
const archivoReal = ref(null);
const categoriasDisponibles = ref([]);

const token = localStorage.getItem("token");

const producto = ref({
  nombre: "",
  descripcion: "",
  precio: null,
  categorias: []
});

const obtenerCategorias = async () => {
  try {
    const response = await axios.get(`http://localhost:8080/api/categories`);
    if (Array.isArray(response.data)) {
      categoriasDisponibles.value = response.data;
    } else if (response.data && Array.isArray(response.data.data)) {
      categoriasDisponibles.value = response.data.data;
    }
  } catch (error) {
    console.error("Error al obtener categorías:", error);
  }
};

const manejarSubidaArchivoReal = (evento) => {
  const archivo = evento.target.files[0];
  archivoReal.value = archivo || null;
};

const manejarSubidaImagen = (evento) => {
  const archivo = evento.target.files[0];
  if (!archivo) {
    archivoImagen.value = null;
    vistaPrevia.value = null;
    return;
  }
  archivoImagen.value = archivo;
  if (vistaPrevia.value) URL.revokeObjectURL(vistaPrevia.value);
  vistaPrevia.value = URL.createObjectURL(archivo);
};

const crearProducto = async () => {
  if (producto.value.categorias.length === 0) {
    alert("Por favor, selecciona al menos una categoría.");
    return;
  }

  const formData = new FormData();
  formData.append("nombre", producto.value.nombre);
  formData.append("descripcion", producto.value.descripcion);
  formData.append("precio", producto.value.precio);

  producto.value.categorias.forEach((id) => formData.append("categorias[]", id));

  if (archivoReal.value) formData.append("file_real", archivoReal.value);
  if (archivoImagen.value) formData.append("imagen", archivoImagen.value);

  cargando.value = true;

  try {
    await axios.post(`http://localhost:8080/api/products/store`, formData, {
      headers: { Authorization: `Bearer ${token}` },
    });
    router.push("/mis-productos");
  } catch (error) {
    console.error("Error:", error);
    alert("Error al crear el producto.");
  } finally {
    cargando.value = false;
  }
};

const volver = () => router.back();

onMounted(() => obtenerCategorias());
</script>

<template>
  <NavBar />
  <div class="super-wide-container">
    <header class="form-nav">
      <button @click="volver" class="btn-back">← Volver</button>
    </header>

    <main class="wide-card">
      <div class="header-text">
        <h1>Crear Nuevo Producto</h1>
        <p>Introduce los detalles de tu producto digital a continuación</p>
      </div>

      <form @submit.prevent="crearProducto">
        
        <div class="input-block">
          <label for="nombre">Nombre del Producto *</label>
          <input id="nombre" v-model="producto.nombre" type="text" placeholder="Ej: Curso de Programación Avanzada" required />
        </div>

        <div class="input-block">
          <label for="descripcion">Descripción *</label>
          <textarea id="descripcion" v-model="producto.descripcion" placeholder="Explica de qué trata tu producto..." rows="6" required></textarea>
        </div>

        <div class="two-columns">
          <div class="input-block">
            <label for="precio">Precio (€) *</label>
            <input id="precio" v-model="producto.precio" type="number" step="0.01" min="0" placeholder="0.00" required />
          </div>
          <div class="input-block">
            <label>Categorías *</label>
            <div class="pills-flex">
              <label v-for="cat in categoriasDisponibles" :key="cat.id" class="pill">
                <input type="checkbox" :value="cat.id" v-model="producto.categorias" />
                <span>{{ cat.name }}</span>
              </label>
            </div>
          </div>
        </div>

        <div class="upload-grid">
          <div class="input-block">
            <label>Archivo Digital (ZIP, PDF, etc.) *</label>
            <div :class="['upload-zone', { 'done': archivoReal }]">
              <input type="file" @change="manejarSubidaArchivoReal" required />
              <div class="zone-info">
                <span>{{ archivoReal ? '✅ Archivo cargado' : '📁 Seleccionar archivo' }}</span>
              </div>
            </div>
          </div>

          <div class="input-block">
            <label>Imagen de Portada (JPG, PNG)</label>
            <div :class="['upload-zone', { 'done': archivoImagen }]">
              <input type="file" accept="image/*" @change="manejarSubidaImagen" />
              <div class="zone-info">
                <span>{{ archivoImagen ? '🖼️ Imagen cargada' : '📸 Seleccionar imagen' }}</span>
              </div>
            </div>
          </div>
        </div>

        <div v-if="vistaPrevia" class="full-preview">
          <img :src="vistaPrevia" alt="Preview" />
        </div>

        <div class="actions">
          <button type="submit" class="btn-main" :disabled="cargando">
            {{ cargando ? "Guardando..." : "Crear Producto" }}
          </button>
        </div>
      </form>
    </main>
  </div>
  <Footer />
</template>

<style scoped lang="scss">
@use "@/assets/scss/variables" as *;

// LA CLAVE: Ancho máximo de 1200px para que se vea ancho de verdad
.super-wide-container {
  width: 100%;
  max-width: 1200px; 
  margin: 2rem auto;
  padding: 0 2rem;
}

.wide-card {
  background: white;
  border-radius: 20px;
  padding: 4rem; // Mucho espacio interno
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.05);
  border: 1px solid #eee;
}

.header-text {
  margin-bottom: 3rem;
  h1 { font-size: 2.2rem; font-weight: 800; color: #1a202c; }
  p { color: #718096; font-size: 1.1rem; }
}

.input-block {
  margin-bottom: 2rem;
  label { display: block; font-weight: 700; color: #2d3748; margin-bottom: 0.8rem; }
  input, textarea {
    width: 100%;
    padding: 1rem;
    border: 2px solid #edf2f7;
    border-radius: 12px;
    background: #f7fafc;
    font-size: 1rem;
    &:focus { outline: none; border-color: $primary-blue; background: white; }
  }
}

// Dos columnas anchas para precio y categorías
.two-columns {
  display: grid;
  grid-template-columns: 1fr 2fr;
  gap: 3rem;
}

.pills-flex {
  display: flex;
  flex-wrap: wrap;
  gap: 0.8rem;
  .pill {
    cursor: pointer;
    input { display: none; }
    span { 
      padding: 0.6rem 1.4rem; 
      background: #edf2f7; 
      border-radius: 100px; 
      font-size: 0.9rem;
      display: inline-block;
      transition: 0.2s;
    }
    input:checked + span { background: $primary-blue; color: white; }
  }
}

// Grid para los archivos
.upload-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 2rem;
  margin-top: 2rem;
}

.upload-zone {
  position: relative;
  height: 100px;
  border: 2px dashed #cbd5e0;
  border-radius: 15px;
  background: #fdfdfd;
  display: flex;
  align-items: center;
  justify-content: center;
  input { position: absolute; width: 100%; height: 100%; opacity: 0; cursor: pointer; }
  .zone-info { color: #718096; font-weight: 600; }
  &.done { border-color: #48bb78; background: #f0fff4; .zone-info { color: #2f855a; } }
}

.full-preview {
  margin-top: 2rem;
  text-align: center;
  background: #f8fafc;
  padding: 2rem;
  border-radius: 15px;
  img { max-width: 100%; max-height: 400px; border-radius: 10px; }
}

.actions {
  margin-top: 4rem;
  .btn-main {
    width: 100%;
    padding: 1.5rem;
    background: $primary-blue;
    color: white;
    border: none;
    border-radius: 15px;
    font-size: 1.2rem;
    font-weight: 800;
    cursor: pointer;
    &:hover { background: darken($primary-blue, 5%); }
  }
}

@media (max-width: 900px) {
  .two-columns, .upload-grid { grid-template-columns: 1fr; }
  .wide-card { padding: 2rem; }
}
</style>