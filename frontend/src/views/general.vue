<script setup>
import NavBar from "@/components/NavBar.vue";
import Footer from "@/components/Footer.vue";

const categorias = [
  "Cursos",
  "eBooks",
  "Software",
  "Plantillas",
  "Audio",
  "Fotografía",
  "Video",
  "Fuentes",
  "Otros",
];
</script>

<template>
  <NavBar />
  <main class="general-container">
    <section class="explore-hero">
      <div class="badge-spark">
        <svg
          viewBox="0 0 24 24"
          fill="none"
          stroke="currentColor"
          stroke-width="2"
          class="spark-icon"
        >
          <path
            d="M12 3l1.912 5.886L20 10.8l-5.886 1.912L12 21l-1.912-5.886L4 13.2l5.886-1.912z"
          />
        </svg>
        <span>Descubre productos digitales únicos</span>
      </div>

      <h1>
        Tu contenido digital,<br />
        <span class="azul-gradient">a un clic de distancia</span>
      </h1>

      <p class="subtitulo-explorar">
        Explora miles de recursos creados por creadores independientes
      </p>

      <div class="categories-row">
        <button v-for="cat in categorias" :key="cat" class="cat-pill">
          {{ cat }}
        </button>
      </div>
    </section>

    <section class="destacados">
      <div class="destacados-contenido">
        <div class="destacados-header">
          <div class="titulos-destacados">
            <h2>Destacados</h2>
            <p>Los productos más populares del momento</p>
          </div>
          <router-link to="/explorar" class="ver-todo"
            >Ver todo &rarr;</router-link
          >
        </div>

        <div class="grid-productos">
          <div class="producto-tarjeta" v-for="n in 4" :key="n">
            <div class="img-contenedor">
              <span class="badge">Cursos</span>
              <img src="../assets/iconos/curso.jpg" alt="Producto" />
              <button class="favorito-btn">
                <svg
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                >
                  <path
                    d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"
                  ></path>
                </svg>
              </button>
            </div>
            <div class="producto-info">
              <h3>Curso Completo de Diseño...</h3>
              <p class="autor">por Design Academy</p>
              <div class="producto-footer">
                <div class="rating"><span class="estrella">⭐</span> 4.8</div>
                <div class="precio">49.99€</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="tendencias">
      <div class="tendencias-contenido">
        <div class="tendencias-header">
          <h2>Tendencias</h2>
          <p>Lo más buscado esta semana</p>
        </div>

        <div class="grid-tendencias">
          <div class="tendencia-tarjeta" v-for="n in 2" :key="n">
            <div class="img-tendencia">
              <img src="../assets/iconos/plantillas.jpg" alt="Tendencia" />
              <button class="favorito-btn">
                <svg
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                >
                  <path
                    d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"
                  ></path>
                </svg>
              </button>
            </div>
            <div class="tendencia-info">
              <h3>Pack 100 Plantillas de Instagram</h3>
              <div class="tendencia-footer">
                <div class="rating"><span class="estrella">⭐</span> 4.5</div>
                <div class="precio">19.99€</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
  <Footer />
</template>

<style scoped>
.general-container {
  background-color: #ffffff;
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  align-items: center;
  width: 100%;
}

.explore-hero {
  padding: 6rem 2rem 4rem;
  text-align: center;
  display: flex;
  flex-direction: column;
  align-items: center;
  max-width: 1100px;
}

.badge-spark {
  display: inline-flex;
  align-items: center;
  gap: 0.6rem;
  background-color: #eff6ff;
  color: #2563eb;
  padding: 0.6rem 1.2rem;
  border-radius: 50px;
  font-weight: 600;
  font-size: 0.9rem;
  margin-bottom: 2rem;
}

.spark-icon {
  width: 18px;
  height: 18px;
}

h1 {
  font-size: 4rem;
  font-weight: 900;
  color: #111827;
  line-height: 1.1;
  letter-spacing: -0.02em;
  margin-bottom: 1.5rem;
}

.azul-gradient {
  color: #1d4ed8;
}

.subtitulo-explorar {
  font-size: 1.4rem;
  color: #6b7280;
  margin-bottom: 3rem;
  max-width: 700px;
}

.categories-row {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 0.8rem;
}

.cat-pill {
  background-color: #ffffff;
  border: 1px solid #e5e7eb;
  color: #111827;
  padding: 0.6rem 1.4rem;
  border-radius: 50px;
  font-size: 0.95rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s ease;
}

.cat-pill:hover {
  border-color: #2563eb;
  color: #2563eb;
  background-color: #f0f7ff;
  transform: translateY(-2px);
}

.destacados,
.tendencias {
  padding: 4rem 2rem;
  display: flex;
  justify-content: center;
  width: 100%;
  box-sizing: border-box;
}

.tendencias {
  background-color: #f8faff;
}

.destacados-contenido,
.tendencias-contenido {
  max-width: 1200px;
  width: 100%;
}

.destacados-header,
.tendencias-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-end;
  margin-bottom: 2.5rem;
}

.titulos-destacados h2,
.tendencias-header h2 {
  font-size: 2.5rem;
  font-weight: 800;
  color: #111827;
  margin-bottom: 0.5rem;
}

.ver-todo {
  text-decoration: none;
  color: #111827;
  font-weight: 600;
  transition: color 0.2s;
}

.ver-todo:hover {
  color: #2563eb;
}

/* --- GRIDS --- */
.grid-productos,
.grid-tendencias {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 1.5rem;
}

.producto-tarjeta,
.tendencia-tarjeta {
  border: 1px solid #e5e7eb;
  border-radius: 12px;
  overflow: hidden;
  background: white;
  transition: box-shadow 0.3s ease;
  display: flex;
  flex-direction: column;
  position: relative;
}

.producto-tarjeta:hover,
.tendencia-tarjeta:hover {
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
}

.img-contenedor,
.img-tendencia {
  position: relative;
  height: 180px;
  overflow: hidden;
  background: #f3f4f6;
}

.img-tendencia {
  height: 250px;
}

.img-contenedor img,
.img-tendencia img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.4s ease;
}

.producto-tarjeta:hover img,
.tendencia-tarjeta:hover img {
  transform: scale(1.08);
}

.favorito-btn {
  position: absolute;
  top: 8px;
  right: 8px;
  background: white;
  border: none;
  border-radius: 50%;
  width: 36px;
  height: 36px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  opacity: 0;
  transition: all 0.2s ease;
  z-index: 2;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.favorito-btn svg {
  width: 18px;
  height: 18px;
  color: #111827;
}

.producto-tarjeta:hover .favorito-btn,
.tendencia-tarjeta:hover .favorito-btn {
  opacity: 1;
}

.favorito-btn:hover {
  color: #ef4444;
  transform: scale(1.1);
}

.badge {
  position: absolute;
  top: 8px;
  left: 8px;
  background: white;
  padding: 0.3rem 0.8rem;
  border-radius: 6px;
  font-size: 0.8rem;
  font-weight: 700;
  z-index: 2;
}

.producto-info,
.tendencia-info {
  padding: 1.5rem;
  flex-grow: 1;
  display: flex;
  flex-direction: column;
}

h3 {
  font-size: 1.1rem;
  color: #1d4ed8;
  font-weight: 700;
  margin-bottom: 0.5rem;
}

.autor {
  color: #6b7280;
  font-size: 0.9rem;
  margin-bottom: 1.5rem;
}

.producto-footer,
.tendencia-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: auto;
  border-top: 1px solid #f3f4f6;
  padding-top: 1rem;
}

.precio {
  color: #1d4ed8;
  font-weight: 800;
  font-size: 1.2rem;
}

@media (max-width: 1024px) {
  .grid-productos,
  .grid-tendencias {
    grid-template-columns: repeat(2, 1fr);
  }
  h1 {
    font-size: 3rem;
  }
}

@media (max-width: 768px) {
  .explore-hero {
    padding-top: 4rem;
  }
  .categories-row {
    gap: 0.5rem;
  }
}

@media (max-width: 640px) {
  .grid-productos,
  .grid-tendencias {
    grid-template-columns: 1fr;
  }
  .destacados-header {
    flex-direction: column;
    align-items: flex-start;
    gap: 1rem;
  }
}
</style>
