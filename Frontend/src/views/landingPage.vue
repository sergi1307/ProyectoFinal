<script></script>
<template>
  <nav class="navbar">
    <div class="navbar-container">
      <a href="#" class="logo-container">
        <img
          src="../assets/logos/Logo_gr.png"
          alt="Logo Nexus"
          class="imagen-logo"
        />
      </a>

      <div class="acciones">
        <router-link to="/login" class="enlace">Iniciar Sesión</router-link>
        <router-link to="/register" class="boton">Registrarse</router-link>
      </div>
    </div>
  </nav>

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
          <router-link to="/login" class="btn-secundario"
            >Iniciar sesión</router-link
          >
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
  </main>
  <footer>
    <p>&copy; Copyright 2026 ISARIA</p>
  </footer>
</template>

<style scoped>
.navbar {
  background-color: #ffffff;
  width: 100%;
  text-align: center;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
  position: sticky;
  top: 0;
  z-index: 100;
  font-family:
    -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial,
    sans-serif;
}

.navbar-container {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem 2rem;
  max-width: 1200px;
  margin: 0 auto;
}

.logo-container {
  display: flex;
  align-items: center;
  text-decoration: none;
}

.imagen-logo {
  height: 60px;
  width: auto;
  object-fit: contain;
  display: block;
}

.acciones {
  display: flex;
  align-items: center;
  gap: 2rem;
}

.enlace {
  text-decoration: none;
  color: #1f2937;
  font-weight: 500;
  font-size: 1rem;
  transition: all 0.4s;
  border-radius: 6px;
}

.enlace:hover {
  color: #2563eb;
  padding: 0.6rem;
  background-color: rgba(37, 99, 235, 0.1);
  border: 1px solid #2563eb;
}

.boton {
  text-decoration: none;
  background-color: #2563eb;
  color: white;
  border: none;
  padding: 0.6rem 1.5rem;
  border-radius: 6px;
  font-weight: 600;
  font-size: 0.95rem;
  cursor: pointer;
  transition: background-color 0.2s;
}

.boton:hover {
  background-color: #1d4ed8;
}

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

.btn-empezar {
  background-color: #2563eb;
  text-decoration: none;
  color: white;
  border: none;
  padding: 0.9rem 2rem;
  border-radius: 8px;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  transition: background-color 0.2s;
}

.btn-empezar:hover {
  background-color: #1d4ed8;
}

.btn-secundario {
  background-color: white;
  text-decoration: none;
  color: #1f2937;
  padding: 0.9rem 2rem;
  border: 1px solid #e5e7eb;
  border-radius: 8px;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  transition: background-color 0.2s;
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
  transition:
    transform 0.2s,
    box-shadow 0.2s;
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

.tarjeta h3 {
  margin-top: 0;
  font-size: 1.25rem;
  color: #111827;
}

.tarjeta p {
  color: #6b7280;
  line-height: 1.6;
  font-size: 0.95rem;
}

footer {
  background-color: #1d4ed8;
  display: flex;
  justify-content: center;
  align-items: center;
  color: white;
  font-style: italic;
  padding: 2em;
}
</style>
