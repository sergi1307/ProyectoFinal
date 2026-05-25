<script setup>
import NavBar from "@/components/NavBar.vue";
import Footer from "@/components/Footer.vue";
import ProductoCard from "@/components/products/ProductCard.vue";
import { ref, onMounted, computed } from "vue";
import api from "@/services/api";

const productos = ref([]);
const token = localStorage.getItem("token");

const searchQuery = ref("");

const productosFiltrados = computed(() => {
  if (!searchQuery.value) return productos.value;
  
  const query = searchQuery.value.toLowerCase();
  
  return productos.value.filter(p => {
    const titulo = p.title || p.name || ""; 
    const descripcion = p.description || "";
    
    return titulo.toLowerCase().includes(query) || 
           descripcion.toLowerCase().includes(query);
  });
});

const cargarProductos = async () => {
  try {
    const response = await api.get("/products/me", {
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
          <p>{{ productosFiltrados.length }} productos publicados</p>
        </div>
        
        <div class="actions-right">
          <div class="search-wrapper">
            <svg viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="2" class="search-icon">
              <circle cx="11" cy="11" r="8"></circle>
              <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            <input 
              type="text" 
              v-model="searchQuery"
              placeholder="Buscar en mis productos..." 
              class="search-input" 
            />
          </div>
          
          <router-link to="/vender" class="btn-create">+ Crear Producto</router-link>
        </div>
      </div>

      <div class="productos-grid">
        <ProductoCard 
          v-for="item in productosFiltrados" 
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
    flex-wrap: wrap;
    gap: 1rem;
    margin-bottom: 2rem;

    .titles {
      margin-right: auto;

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

    .actions-right {
      display: flex;
      align-items: center;
      gap: 1rem;
      flex: 1;
      justify-content: flex-end;
      max-width: 600px;

      .search-wrapper {
        display: flex;
        align-items: center;
        background-color: #f3f4f6;
        border-radius: 8px;
        padding: 0.6rem 1.2rem;
        width: 100%;
        max-width: 300px;
        gap: 0.8rem;
        transition: background-color 0.2s, box-shadow 0.2s;

        &:focus-within {
          background-color: #ffffff;
          box-shadow: 0 0 0 2px #0cc0df;
        }

        .search-icon {
          width: 18px;
          height: 18px;
          flex-shrink: 0;
        }

        .search-input {
          border: none;
          background: transparent;
          outline: none;
          width: 100%;
          font-size: 0.95rem;
          color: #374151;

          &::placeholder {
            color: #6b7280;
          }
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
        white-space: nowrap;

        &:hover {
          background-color: $primary-hover;
          transform: translateY(-2px);
          box-shadow: 0 4px 10px rgba(0, 151, 178, 0.2);
        }
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
    align-items: stretch;
    
    .titles {
      margin-bottom: 0.5rem;
    }
    
    .actions-right {
      flex-direction: column;
      align-items: stretch;
      max-width: 100%;
      
      .search-wrapper {
        max-width: 100%;
      }

      .btn-create {
        width: 100%;
        justify-content: center;
      }
    }
  }
}
</style>