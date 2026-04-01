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

<style scoped lang="scss">
.container-wide {
  max-width: 1200px;
  margin: 2rem auto;
  padding: 0 1.5rem;
}

.header-section {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 3rem;
}

.productos-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 2rem;
}
</style>