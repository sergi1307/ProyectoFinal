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
  
  <div class="vender-page">
    <div class="vender-contenedor">
      
      <header class="form-nav">
        <button @click="volver" class="btn-back-visual">← Volver</button>
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
  </div>
  
  <Footer />
</template>