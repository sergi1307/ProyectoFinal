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
  <NavBar></NavBar>
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
  <Footer></Footer>
</template>

<style scoped lang="scss">
.btn-create{
  display: flex;
  align-items: center;
  gap: 0.4rem;
  background-color: #0097b2;
  color: white;
  text-decoration: none;
  padding: 0.6rem 1.2rem;
  border-radius: 6px;
  font-weight: 600;
  font-size: 0.95rem;
  transition: background-color 0.2s;
}

.btn-create:hover{
  background-color: #00869e;
}

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