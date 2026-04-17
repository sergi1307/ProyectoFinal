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
  // Añade aquí la lógica o petición a axios para borrar
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
          :es-propio="true"
          @eliminar="handleEliminar"
        />
      </div>
    </div>
  </div>
  <Footer></Footer>
</template>

<style scoped lang="scss">
@use '../../assets/scss/variables' as *;

.mis-productos-page {
    background-color: $bg-color;
    min-height: 100vh;
    padding-bottom: 4rem;

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
        flex-wrap: wrap;
        gap: 1rem;

        .titles {
            h1 {
                font-size: 2rem;
                font-weight: 800;
                color: $text-main;
                margin-bottom: 0.5rem;
            }

            p {
                color: $text-muted;
                font-size: 1rem;
                margin: 0;
            }
        }

        .btn-create {
            background-color: $primary-blue;
            color: white;
            text-decoration: none;
            padding: 0.8rem 1.5rem;
            border-radius: $border-radius-md;
            font-weight: bold;
            display: inline-flex;
            align-items: center;
            transition: $transition-base;

            &:hover {
                background-color: $primary-hover;
                transform: translateY(-2px);
                box-shadow: 0 4px 10px rgba(0, 151, 178, 0.2);
            }
        }
    }

    .productos-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 1.5rem;
        justify-items: center;
        justify-content: center;
    }
}

@media (max-width: 640px) {
    .mis-productos-page .header-section {
        flex-direction: column;
        align-items: flex-start;
        
        .btn-create {
            width: 100%;
            justify-content: center;
        }
    }
}
</style>