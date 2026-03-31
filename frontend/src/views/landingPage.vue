<script setup>
import { useI18n } from 'vue-i18n';
import NavBar from '@/components/NavBar.vue';
import Footer from '@/components/Footer.vue';

const { t } = useI18n();
</script>

<template>
  <NavBar></NavBar>
  <main>
    <section class="hero">
      <div class="hero-contenido">
        <div class="etiqueta">
          <img src="../assets/iconos/estrella.png" alt="Estrella" />
          <span>{{ $t('home.welcome') }}</span>
        </div>

        <h1>
          {{ $t('home.heroTitle') }} <br />
          <span class="azul">{{ $t('home.heroTitleAzul') }}</span>
        </h1>

        <p class="subtitulo">
          {{ $t('home.heroSub') }}
        </p>

        <div class="botones-hero">
          <router-link to="/register" class="btn-empezar">
            {{ $t('home.startFree') }} <span>&rarr;</span>
          </router-link>
          <router-link to="/login" class="btn-secundario">
            {{ $t('navbar.login') }}
          </router-link>
        </div>

        <div class="beneficios">
          <div class="item-beneficio">
            <img src="../assets/iconos/check.png" alt="check" class="icono-check" />
            <span>{{ $t('home.noHidden') }}</span>
          </div>
          <div class="item-beneficio">
            <img src="../assets/iconos/check.png" alt="check" class="icono-check" />
            <span>{{ $t('home.securePay') }}</span>
          </div>
          <div class="item-beneficio">
            <img src="../assets/iconos/check.png" alt="check" class="icono-check" />
            <span>{{ $t('home.instant') }}</span>
          </div>
        </div>
      </div>
    </section>

    <section class="features">
      <div class="features-contenido">
        <div class="features-header">
          <h2>{{ $t('home.whyNexus') }}</h2>
          <p>{{ $t('home.whyNexusSub') }}</p>
        </div>

        <div class="grid-tarjetas">
          <div class="tarjeta">
            <div class="icono-contenedor"><span style="font-size: 1.5rem">⚡</span></div>
            <h3>{{ $t('home.fast') }}</h3>
            <p>{{ $t('home.fastDesc') }}</p>
          </div>

          <div class="tarjeta">
            <div class="icono-contenedor"><span style="font-size: 1.5rem">🛡️</span></div>
            <h3>{{ $t('home.safe') }}</h3>
            <p>{{ $t('home.safeDesc') }}</p>
          </div>

          <div class="tarjeta">
            <div class="icono-contenedor"><span style="font-size: 1.5rem">🌍</span></div>
            <h3>{{ $t('home.global') }}</h3>
            <p>{{ $t('home.globalDesc') }}</p>
          </div>
        </div>
      </div>
    </section>

    <section class="categorias">
      <div class="categorias-contenido">
        <div class="categorias-header">
          <h2>{{ $t('home.exploreCat') }}</h2>
          <p>{{ $t('home.exploreCatSub') }}</p>
        </div>

        <div class="grid-tarjetas-cat">
          <div class="cat-tarjetas">
            <div class="categoria-tarjeta">
              <p><img src="../assets/iconos/estrellas.png" alt="estrella"></p>
              <p>{{ $t('categorias.cursos') }}</p>
            </div>
            <div class="categoria-tarjeta">
              <p><img src="../assets/iconos/estrellas.png" alt="estrella"></p>
              <p>{{ $t('categorias.ebooks') }}</p>
            </div>
            <div class="categoria-tarjeta">
              <p><img src="../assets/iconos/estrellas.png" alt="estrella"></p>
              <p>{{ $t('categorias.software') }}</p>
            </div>
            <div class="categoria-tarjeta">
              <p><img src="../assets/iconos/estrellas.png" alt="estrella"></p>
              <p>{{ $t('categorias.plantillas') }}</p>
            </div>
            <div class="categoria-tarjeta">
              <p><img src="../assets/iconos/estrellas.png" alt="estrella"></p>
              <p>{{ $t('categorias.audio') }}</p>
            </div>
            <div class="categoria-tarjeta">
              <p><img src="../assets/iconos/estrellas.png" alt="estrella"></p>
              <p>{{ $t('categorias.fotografia') }}</p>
            </div>
            <div class="categoria-tarjeta">
              <p><img src="../assets/iconos/estrellas.png" alt="estrella"></p>
              <p>{{ $t('categorias.video') }}</p>
            </div>
            <div class="categoria-tarjeta">
              <p><img src="../assets/iconos/estrellas.png" alt="estrella"></p>
              <p>{{ $t('categorias.fuentes') }}</p>
            </div>
            <div class="categoria-tarjeta">
              <p><img src="../assets/iconos/estrellas.png" alt="estrella"></p>
              <p>{{ $t('categorias.otros') }}</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="comenzar">
      <div class="comenzar-contenido">
        <div class="comenzar-header">
          <h1 class="titulo-comenzar">{{ $t('home.ready') }}</h1>
          <p class="p-comenzar">{{ $t('home.readySub') }}</p>

          <div class="botones-comenzar">
            <router-link to="/register" class="no-cuenta">
              {{ $t('home.startFree') }} &rarr;
            </router-link>
            <router-link to="/login" class="si-cuenta">
              {{ $t('auth.haveAccount') }}
            </router-link>
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

.destacados {
  background-color: #ffffff;
  padding: 4rem 2rem;
  display: flex;
  justify-content: center;
  width: 100%;
}

.destacados-contenido {
  max-width: 1200px;
  width: 100%;
}

.destacados-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-end;
  margin-bottom: 2.5rem;
}

.titulos-destacados h2 {
  font-size: 2.5rem;
  font-weight: 800;
  color: #111827;
  margin: 0 0 0.5rem 0;
}

.titulos-destacados p {
  font-size: 1.1rem;
  color: #6b7280;
  margin: 0;
}

.ver-todo {
  text-decoration: none;
  color: #111827;
  font-weight: 600;
  font-size: 1rem;
  transition: color 0.2s;
  padding-bottom: 0.5rem;
}

.ver-todo:hover {
  color: #2563eb;
}

.grid-productos {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 1.5rem;
}

.producto-tarjeta {
  border: 1px solid #e5e7eb;
  border-radius: 12px;
  overflow: hidden;
  background-color: white;
  transition: box-shadow 0.3s ease;
  display: flex;
  flex-direction: column;
}

.producto-tarjeta:hover {
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
}

.img-contenedor {
  position: relative;
  height: 180px;
  width: 100%;
  background-color: #f3f4f6;
  overflow: hidden;
}

.img-contenedor img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.4s ease;
}

.producto-tarjeta:hover .img-contenedor img {
  transform: scale(1.08); 
}

.badge {
  position: absolute;
  top: 8px;
  left: 8px;
  background-color: rgba(255, 255, 255, 0.95);
  color: #111827;
  padding: 0.3rem 0.8rem;
  border-radius: 6px;
  font-size: 0.8rem;
  font-weight: 700;
  box-shadow: 0 2px 4px rgba(0,0,0,0.05);
  z-index: 2;
}

.favorito-btn {
  position: absolute;
  top: 8px;
  right: 8px; 
  margin: 0 !important;
  padding: 0;
  background-color: white;
  border: none;
  border-radius: 50%;
  width: 36px;
  height: 36px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  color: #111827;
  opacity: 0; 
  transition: all 0.2s ease;
  z-index: 2;
}

.favorito-btn svg {
  width: 18px;
  height: 18px;
  transition: fill 0.2s, color 0.2s;
}

.producto-tarjeta:hover .favorito-btn {
  opacity: 1;
}

.favorito-btn:hover {
  transform: scale(1.1);
  color: #ef4444;
}

.producto-info {
  padding: 1.5rem;
  display: flex;
  flex-direction: column;
  flex-grow: 1;
}

.producto-info h3 {
  margin: 0 0 0.5rem 0;
  font-size: 1.1rem;
  color: #1d4ed8;
  font-weight: 700;
  line-height: 1.4;
}

.autor {
  color: #6b7280;
  font-size: 0.9rem;
  margin: 0 0 1.5rem 0;
}

.producto-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: auto;
  padding-top: 1rem;
  border-top: 1px solid #f3f4f6;
}

.rating {
  display: flex;
  align-items: center;
  gap: 0.3rem;
  font-weight: 700;
  color: #111827;
  font-size: 0.95rem;
}

.estrella {
  color: #fbbf24;
}

.votos {
  color: #9ca3af;
  font-weight: normal;
  font-size: 0.85rem;
}

.precio {
  color: #1d4ed8;
  font-weight: 800;
  font-size: 1.25rem;
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
  background: linear-gradient(90deg, #1d4ed8 0%, #3b82f6 50%, #1d4ed8 100%);
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

@media (max-width: 1024px) {
  .grid-productos {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 640px) {
  .grid-productos {
    grid-template-columns: 1fr;
  }
  .destacados-header {
    flex-direction: column;
    align-items: flex-start;
    gap: 1rem;
  }
}
</style>