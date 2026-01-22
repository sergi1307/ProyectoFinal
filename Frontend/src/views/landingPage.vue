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
        <a href="#" class="enlace">Iniciar Sesión</a>
        <button class="boton">Registrarse</button>
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
          <button class="btn-empezar">
            Comenzar gratis <span>&rarr;</span>
          </button>
          <button class="btn-secundario">Iniciar sesión</button>
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

    <section class="seccion-final">
      <div class="tarjeta-azul">
        <h2>¿Listo para comenzar?</h2>
        <p>
          Únete a Nexus hoy y descubre un mundo de posibilidades creativas.
        </p>

        <div class="botones-cta">
          <button class="btn-blanco">
            Crear cuenta gratis <span>&rarr;</span>
          </button>
          <button class="btn-borde">Ya tengo cuenta</button>
        </div>
      </div>
    </section>
  </main>
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
  font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica,
    Arial, sans-serif;
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
  transition: color 0.2s;
}

.enlace:hover {
  color: #2563eb;
}

.boton {
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
  width: 100%;
  padding: 0;
  display: flex;
  flex-direction: column;
}

.hero {
  background: linear-gradient(to bottom, #f0fdff 0%, #ffffff 100%);
  width: 100%;
  padding: 5rem 2rem;
  display: flex;
  justify-content: center;
}

.hero-contenido {
  max-width: 900px;
  width: 100%;
  text-align: center;
  display: flex;
  flex-direction: column;
  align-items: center;
}

/* Estilos internos del Hero */
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
}

.btn-empezar {
  background-color: #2563eb;
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
  color: #1f2937;
  border: 1px solid #e5e7eb;
  padding: 0.9rem 2rem;
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
  transition: transform 0.2s, box-shadow 0.2s;
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

.seccion-final {
  width: 100%;
  display: flex;
  justify-content: center;
  padding: 2rem 2rem 6rem 2rem;
  background-color: white;
}

.tarjeta-azul {
  background-color: #2563eb;
  width: 100%;
  max-width: 900px;
  border-radius: 20px;
  padding: 4rem 2rem;
  text-align: center;
  color: white;
  box-shadow: 0 10px 25px rgba(37, 99, 235, 0.25);
}

.tarjeta-azul h2 {
  font-size: 2.5rem;
  font-weight: 800;
  margin-top: 0;
  margin-bottom: 1rem;
  color: white;
}

.tarjeta-azul p {
  font-size: 1.1rem;
  margin-bottom: 2.5rem;
  color: #dbeafe;
}

.botones-cta {
  display: flex;
  gap: 1rem;
  justify-content: center;
  flex-wrap: wrap;
}

.btn-blanco {
  background-color: white;
  color: #2563eb;
  border: none;
  padding: 0.8rem 1.5rem;
  border-radius: 8px;
  font-weight: 700;
  font-size: 1rem;
  cursor: pointer;
  transition: transform 0.2s;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.btn-blanco:hover {
  transform: scale(1.05);
}

.btn-borde {
  background-color: transparent;
  color: white;
  border: 1px solid rgba(255, 255, 255, 0.5);
  padding: 0.8rem 1.5rem;
  border-radius: 8px;
  font-weight: 600;
  font-size: 1rem;
  cursor: pointer;
  transition: background-color 0.2s;
}

.btn-borde:hover {
  background-color: rgba(255, 255, 255, 0.1);
  border-color: white;
}
</style>