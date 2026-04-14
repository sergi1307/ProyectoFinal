<script setup>
import NavBar from "@/components/NavBar.vue";
import Footer from "@/components/Footer.vue";
import ProductoCard from "@/components/products/ProductCard.vue";
import { ref, onMounted } from "vue";
import axios from "axios";

const productos = ref([]);
const token = localStorage.getItem("token");

const cargarProductos = async () => {
  try {
    const response = await axios.get("http://localhost:8080/api/products/me", {
      headers: { Authorization: `Bearer ${token}` }
    });
    productos.value = response.data;
    console.log(productos.value);
  } catch (error) {
    console.error("Error cargando productos", error);
  }
};

const handleEliminar = async (id) => {
  if (!confirm("¿Seguro que quieres borrar este producto?")) return;
  // Aquí iría la lógica de axios.delete...
};

onMounted(cargarProductos);
</script>

<template>
  <NavBar />
  <div class="mis-productos-page">
    <div class="container-wide">
      <div class="header-section">
        <div class="titles">
          <h1>Mis Productos</h1>
          <p>{{ productos.length }} productos publicados</p>
        </div>
        <router-link to="/vender" class="btn-create">+ Crear Producto</router-link>
      </div>

      <div class="productos-grid">
        <ProductoCard 
          v-for="item in productos" 
          :key="item.id" 
          :producto="item" 
          @eliminar="handleEliminar"
        />
      </div>
    </div>
  </div>
  <Footer />
</template>