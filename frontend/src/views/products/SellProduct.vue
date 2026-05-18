<script setup>
import NavBar from "@/components/NavBar.vue";
import Footer from "@/components/Footer.vue";
import { onMounted, ref } from "vue";
import { useRouter } from "vue-router";
import axios from "axios";
import { useToast } from '@/composables/useToast';

const { addToast } = useToast();
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
    addToast("Por favor, selecciona al menos una categoría.", 'error');
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
      headers: {
        Authorization: `Bearer ${token}`,
        'Content-Type': 'multipart/form-data'
      },
    });
    router.push("/mis-productos");
  } catch (error) {
    console.error("Error:", error);
    addToast("Error al crear el producto.", 'error');
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
        <button @click="volver" class="btn-back-visual">← {{ $t('vender.volver') }}</button>
      </header>

      <main class="wide-card">
        <div class="header-text">
          <h1>{{ $t('vender.titulo') }}</h1>
          <p>{{ $t('vender.subtitulo') }}</p>
        </div>

        <form @submit.prevent="crearProducto">
          
          <div class="input-block">
            <label for="nombre">{{ $t('vender.lblNombre') }}</label>
            <input id="nombre" v-model="producto.nombre" type="text" :placeholder="$t('vender.phNombre')" required />
          </div>

          <div class="input-block">
            <label for="descripcion">{{ $t('vender.lblDescripcion') }}</label>
            <textarea id="descripcion" v-model="producto.descripcion" :placeholder="$t('vender.phDescripcion')" rows="6" required></textarea>
          </div>

          <div class="two-columns">
            <div class="input-block">
              <label for="precio">{{ $t('vender.lblPrecio') }}</label>
              <input id="precio" v-model="producto.precio" type="number" step="0.01" min="0" placeholder="0.00" required />
            </div>
            <div class="input-block">
              <label>{{ $t('vender.lblCategorias') }}</label>
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
              <label>{{ $t('vender.lblArchivo') }}</label>
              <div :class="['upload-zone', { 'done': archivoReal }]">
                <input type="file" @change="manejarSubidaArchivoReal" required />
                <div class="zone-info">
                  <span>{{ archivoReal ? '✅ ' + $t('vender.archivoCargado') : '📁 ' + $t('vender.seleccionarArchivo') }}</span>
                </div>
              </div>
            </div>

            <div class="input-block">
              <label>{{ $t('vender.lblImagen') }}</label>
              <div :class="['upload-zone', { 'done': archivoImagen }]">
                <input type="file" accept="image/*" @change="manejarSubidaImagen" />
                <div class="zone-info">
                  <span>{{ archivoImagen ? '🖼️ ' + $t('vender.imagenCargada') : '📸 ' + $t('vender.seleccionarImagen') }}</span>
                </div>
              </div>
            </div>
          </div>

          <div v-if="vistaPrevia" class="full-preview">
            <img :src="vistaPrevia" alt="Preview" />
          </div>

          <div class="actions">
            <button type="submit" class="btn-main" :disabled="cargando">
              {{ cargando ? $t('vender.guardando') : $t('vender.btnCrear') }}
            </button>
          </div>
        </form>
      </main>

    </div>
  </div>
  
  <Footer />
</template>