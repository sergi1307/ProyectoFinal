<script setup>
import NavBar from '@/components/NavBar.vue';
import Footer from '@/components/Footer.vue';
</script>

<template>
  <NavBar></NavBar>
  <main>
    <section class="hero">
      <div class="hero-contenido">
        <div class="etiqueta">
          <img src="../assets/iconos/estrella.png" alt="Estrella" />
          <span>Bienvenido a Nexus</span>
        </div>

        <h1>
          El marketplace para <br />
          <span class="azul">creadores digitales</span>
        </h1>

        <p class="subtitulo">
          Compra, vende y descubre productos digitales únicos creados por una
          comunidad global de talento independiente.
        </p>

        <div class="botones-hero">
          <router-link to="/register" class="btn-empezar">
            Comenzar gratis <span>&rarr;</span>
          </router-link>
          <router-link to="/login" class="btn-secundario">
            Iniciar sesión
          </router-link>
        </div>

        <div class="beneficios">
          <div class="item-beneficio">
            <img
              src="../assets/iconos/check.png"
              alt="check"
              class="icono-check"
            />
            <span>Sin comisiones ocultas</span>
          </div>
          <div class="item-beneficio">
            <img
              src="../assets/iconos/check.png"
              alt="check"
              class="icono-check"
            />
            <span>Pago seguro</span>
          </div>
          <div class="item-beneficio">
            <img
              src="../assets/iconos/check.png"
              alt="check"
              class="icono-check"
            />
            <span>Descarga instantánea</span>
          </div>
        </div>
      </div>
    </section>

    <section class="features">
      <div class="features-contenido">
        <div class="features-header">
          <h2>¿Por qué elegir Nexus?</h2>
          <p>
            La plataforma diseñada para impulsar tu creatividad y negocio
            digital
          </p>
        </div>

        <div class="grid-tarjetas">
          <div class="tarjeta">
            <div class="icono-contenedor">
              <span style="font-size: 1.5rem">⚡</span>
            </div>
            <h3>Rápido y fácil</h3>
            <p>
              Configura tu tienda en minutos. Sube tus productos digitales y
              empieza a vender al instante.
            </p>
          </div>

          <div class="tarjeta">
            <div class="icono-contenedor">
              <span style="font-size: 1.5rem">🛡️</span>
            </div>
            <h3>100% Seguro</h3>
            <p>
              Transacciones protegidas y datos encriptados. Tu seguridad es
              nuestra prioridad.
            </p>
          </div>

          <div class="tarjeta">
            <div class="icono-contenedor">
              <span style="font-size: 1.5rem">🌍</span>
            </div>
            <h3>Comunidad global</h3>
            <p>
              Únete a miles de creadores y compradores de todo el mundo
              compartiendo su pasión.
            </p>
          </div>
        </div>
      </div>
    </section>

    <section class="categorias">
      <div class="categorias-contenido">
        <div class="categorias-header">
          <h2>Explora por categoría</h2>
          <p>Descubre productos digitales en todas las categorías</p>
        </div>

        <div class="grid-tarjetas-cat">
          <div class="cat-tarjetas">
            <div class="categoria-tarjeta">
              <p><img src="../assets/iconos/estrellas.png" alt="estrella"></p>
              <p>Cursos</p>
            </div>
            <div class="categoria-tarjeta">
              <p><img src="../assets/iconos/estrellas.png" alt="estrella"></p>
              <p>eBooks</p>
            </div>
            <div class="categoria-tarjeta">
              <p><img src="../assets/iconos/estrellas.png" alt="estrella"></p>
              <p>Software</p>
            </div>
            <div class="categoria-tarjeta">
              <p><img src="../assets/iconos/estrellas.png" alt="estrella"></p>
              <p>Plantillas</p>
            </div>
            <div class="categoria-tarjeta">
              <p><img src="../assets/iconos/estrellas.png" alt="estrella"></p>
              <p>Audio</p>
            </div>
            <div class="categoria-tarjeta">
              <p><img src="../assets/iconos/estrellas.png" alt="estrella"></p>
              <p>Fotografía</p>
            </div>
            <div class="categoria-tarjeta">
              <p><img src="../assets/iconos/estrellas.png" alt="estrella"></p>
              <p>Vídeo</p>
            </div>
            <div class="categoria-tarjeta">
              <p><img src="../assets/iconos/estrellas.png" alt="estrella"></p>
              <p>Fuentes</p>
            </div>
            <div class="categoria-tarjeta">
              <p><img src="../assets/iconos/estrellas.png" alt="estrella"></p>
              <p>Otros</p>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section class="comenzar">
      <div class="comenzar-contenido">
        <div class="comenzar-header">
          <h1 class="titulo-comenzar">¿Listo para comenzar?</h1>
          <p class="p-comenzar">Únete a Nexus hoy y descubre un mundo de posibilidades creativas.</p>

          <div class="botones-comenzar">
            <router-link to="/register" class="no-cuenta">Crear cuenta gratis →</router-link>
            <router-link to="/login" class="si-cuenta">Ya tengo cuenta</router-link>
          </div>
        </div>
      </div>
    </section>
  </main>
  <Footer></Footer>
</template>

<style scoped>
main {
  background: none;
  padding: 0;
  display: flex;
  flex-direction: column;
}

.hero {
  background: linear-gradient(to bottom, #f0fdff 0%, #ffffff 100%);
  padding: 5rem 2rem;
  width: 100%;
  display: flex;
  justify-content: center;
}

.hero-contenido {
  text-align: center;
  display: flex;
  flex-direction: column;
  align-items: center;
}

.etiqueta {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  background-color: #eff6ff;
  color: #2563eb;
  padding: 0.5rem 1rem;
  border-radius: 50px;
  font-weight: 500;
  font-size: 0.9rem;
  margin-bottom: 2rem;
  border: 1px solid #dbeafe;
}

.etiqueta img {
  width: 16px;
  height: 16px;
}

h1 {
  font-size: 3.5rem;
  line-height: 1.1;
  font-weight: 800;
  color: #111827;
  margin-top: 0;
  margin-bottom: 1.5rem;
}

.azul {
  color: #2563eb;
}

.subtitulo {
  font-size: 1.25rem;
  color: #6b7280;
  line-height: 1.6;
  margin-bottom: 2.5rem;
  max-width: 600px;
}

.botones-hero {
  display: flex;
  gap: 1rem;
  margin-bottom: 3rem;
  flex-wrap: wrap;
  justify-content: center;
  align-items: center;
}

.btn-empezar, .btn-secundario {
  text-decoration: none;
  padding: 0.9rem 2rem;
  border-radius: 8px;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  transition: all 0.2s;
}

.btn-empezar {
  background-color: #2563eb;
  color: white;
  border: none;
}

.btn-empezar:hover {
  background-color: #1d4ed8;
}

.btn-secundario {
  background-color: white;
  color: #1f2937;
  border: 1px solid #e5e7eb;
}

.btn-secundario:hover {
  background-color: #f9fafb;
  border-color: #d1d5db;
}

.beneficios {
  display: flex;
  gap: 2rem;
  flex-wrap: wrap;
  justify-content: center;
}

.item-beneficio {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  color: #4b5563;
  font-size: 0.95rem;
  font-weight: 500;
}

.icono-check {
  width: 20px;
  height: 20px;
}

.features {
  background-color: #ffffff;
  width: 100%;
  padding: 5rem 2rem;
  display: flex;
  justify-content: center;
}

.features-contenido {
  max-width: 1100px;
  width: 100%;
  text-align: center;
}

.features-header h2 {
  font-size: 2.5rem;
  font-weight: 800;
  color: #111827;
  margin-bottom: 0.5rem;
  margin-top: 0;
}

.features-header p {
  font-size: 1.1rem;
  color: #6b7280;
  margin-bottom: 3.5rem;
}

.grid-tarjetas {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 2rem;
  justify-items: center;
}

.tarjeta {
  background-color: white;
  border: 1px solid #f3f4f6;
  border-radius: 12px;
  padding: 2.5rem 1.5rem;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
  transition: all 0.2s;
  display: flex;
  flex-direction: column;
  align-items: center;
  height: 100%;
}

.tarjeta:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
}

.icono-contenedor {
  background-color: #eff6ff;
  width: 60px;
  height: 60px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 1.5rem;
}

.categorias {
  background-color: #ffffff;
  width: 100%;
  padding: 5rem 2rem 2rem 2rem;
  display: flex;
  justify-content: center;
}

.categorias-contenido {
  max-width: 1100px;
  width: 100%;
  text-align: center;
}

.categorias-header h2 {
  font-size: 2.5rem;
  font-weight: 800;
  color: #111827;
  margin-bottom: 0.5rem;
}

.categorias-header p {
  font-size: 1.1rem;
  color: #6b7280;
  margin-bottom: 3.5rem;
}

.grid-tarjetas-cat {
  max-width: 1000px;
  margin: 0 auto;
}

.cat-tarjetas {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 2rem; 
}

.categoria-tarjeta {
  border: 2px solid #eff6ff;
  border-radius: 12px;
  padding: 2rem;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 1rem;
  background-color: white;
  transition: all 0.2s ease;
  cursor: pointer;
}

.categoria-tarjeta p:first-child {
  background-color: #eff6ff;
  width: 60px;
  height: 60px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 25%;
  margin: 0;
  overflow: hidden;
}

.categoria-tarjeta p:first-child img {
  width: 32px;
  height: 32px;
  object-fit: contain;
}

.categoria-tarjeta p:last-child {
  margin: 0;
  font-weight: 700;
  color: #1e293b;
  font-size: 1.1rem;
}

.categoria-tarjeta:hover {
  border: 2px solid #1d4ed8;
  background-color: #f8faff;
  transform: translateY(-4px);
}

.comenzar {
  max-width: 1200px;
  margin: 0 auto 4rem auto;
  padding: 0 2rem;
}

.comenzar-contenido {
  background: linear-gradient(-45deg, #3b82f6 0%, #2563eb 45%, #1d4ed8 100%);
  border-radius: 25px;
  padding: 5rem;
  text-align: center;
  color: #ffffff;
  box-shadow: 0 10px 25px rgba(37, 99, 235, 0.2);
}

.titulo-comenzar {
  font-size: 3rem;
  color: white;
  font-weight: bold;
  margin-bottom: 2rem;
}

.p-comenzar {
  font-size: 1.5rem;
  font-weight: bold;
  margin-bottom: 3rem;
  opacity: 0.9;
}

.botones-comenzar {
  display: flex;
  justify-content: center;
  gap: 1.5rem;
  flex-wrap: wrap;
}

.no-cuenta, .si-cuenta {
  text-decoration: none;
  padding: 1rem 2rem;
  border-radius: 10px;
  font-weight: bold;
  font-size: 1rem;
  display: inline-block;
  transition: all 0.2s;
}

.no-cuenta {
  background-color: #ffffff;
  color: #2563eb;
  border: none;
}

.no-cuenta:hover {
  background-color: #f1f5f9;
  transform: translateY(-2px);
}

.si-cuenta {
  background-color: transparent;
  color: #ffffff;
  border: 2px solid rgba(255, 255, 255, 0.5);
}

.si-cuenta:hover {
  background-color: rgba(255, 255, 255, 0.1);
  border-color: #ffffff;
  transform: translateY(-2px);
}
</style>