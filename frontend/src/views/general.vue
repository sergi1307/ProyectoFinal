<script setup>
import { ref, computed } from "vue";
import { useI18n } from 'vue-i18n';
import NavBar from "@/components/NavBar.vue";
import Footer from "@/components/Footer.vue";

const { t } = useI18n();

const categorias = computed(() => [
  t('categorias.cursos'),
  t('categorias.ebooks'),
  t('categorias.software'),
  t('categorias.plantillas'),
  t('categorias.audio'),
  t('categorias.fotografia'),
  t('categorias.video'),
  t('categorias.fuentes'),
  t('categorias.otros'),
]);

const favoritoDestacado1 = ref(false);
const favoritoDestacado2 = ref(false);
const favoritoDestacado3 = ref(false);
const favoritoDestacado4 = ref(false);
const favoritoTendencia1 = ref(false);
const favoritoTendencia2 = ref(false);
</script>

<template>
  <NavBar></NavBar>
  <main class="contenedor-general">
    <section class="hero-explorar">
      <div class="etiqueta-brillante">
        <img src="../assets/iconos/brillos.png" class="icono-brillo"/>
        <span>{{ $t('explorar.discover') }}</span>
      </div>

      <h1>
        {{ $t('explorar.heroTitle').split(',')[0] }},<br />
        <span class="azul-degradado">{{ $t('explorar.heroTitle').split(',')[1] }}</span>
      </h1>

      <p class="subtitulo-hero">
        {{ $t('home.heroSub') }} 
      </p>

      <div class="fila-categorias">
        <button
          v-for="categoria in categorias"
          :key="categoria"
          class="boton-categoria"
        >
          {{ categoria }}
        </button>
      </div>
    </section>

    <section class="seccion-destacados">
      <div class="contenido-destacados">
        <div class="cabecera-destacados">
          <div class="titulos-seccion">
            <h2>{{ $t('explorar.featured') }}</h2>
            <p class="descripcion-seccion">{{ $t('home.whyNexusSub') }}</p>
          </div>
          <router-link to="/explorar" class="enlace-ver-todo">{{ $t('explorar.seeAll') }} &rarr;</router-link>
        </div>

        <div class="cuadricula-productos">
          <div class="tarjeta-producto">
            <div class="contenedor-imagen">
              <span class="etiqueta-categoria">{{ $t('categorias.cursos') }}</span>
              <img src="../assets/iconos/curso.jpg" alt="Curso" />
              <button class="boton-favorito" :class="{ activo: favoritoDestacado1 }" @click="favoritoDestacado1 = !favoritoDestacado1">
                <svg viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
              </button>
            </div>
            <div class="informacion-producto">
              <h3>{{ $t('productos.cursoDiseno') }}...</h3>
              <p class="nombre-autor">{{ $t('explorar.by') }} Design Academy</p>
              <div class="pie-tarjeta">
                <div class="valoracion"><span>⭐</span> 4.8 <span class="cantidad-votos">(342)</span></div>
                <div class="precio-producto">49.99€</div>
              </div>
            </div>
          </div>

          <div class="tarjeta-producto">
            <div class="contenedor-imagen">
              <span class="etiqueta-categoria">{{ $t('categorias.ebooks') }}</span>
              <img src="../assets/iconos/ebook.jpg" alt="eBook" />
              <button class="boton-favorito" :class="{ activo: favoritoDestacado2 }" @click="favoritoDestacado2 = !favoritoDestacado2">
                <svg viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
              </button>
            </div>
            <div class="informacion-producto">
              <h3>{{ $t('productos.ebookMarketing') }}...</h3>
              <p class="nombre-autor">{{ $t('explorar.by') }} Marketing Pro</p>
              <div class="pie-tarjeta">
                <div class="valoracion"><span>⭐</span> 4.6 <span class="cantidad-votos">(189)</span></div>
                <div class="precio-producto">24.99€</div>
              </div>
            </div>
          </div>

          <div class="tarjeta-producto">
            <div class="contenedor-imagen">
              <span class="etiqueta-categoria">{{ $t('categorias.audio') }}</span>
              <img src="../assets/iconos/musica.jpg" alt="Audio" />
              <button class="boton-favorito" :class="{ activo: favoritoDestacado3 }" @click="favoritoDestacado3 = !favoritoDestacado3">
                <svg viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
              </button>
            </div>
            <div class="informacion-producto">
              <h3>{{ $t('productos.packSamples') }}...</h3>
              <p class="nombre-autor">{{ $t('explorar.by') }} Sound Factory</p>
              <div class="pie-tarjeta">
                <div class="valoracion"><span>⭐</span> 4.9 <span class="cantidad-votos">(567)</span></div>
                <div class="precio-producto">39.99€</div>
              </div>
            </div>
          </div>

          <div class="tarjeta-producto">
            <div class="contenedor-imagen">
              <span class="etiqueta-categoria">{{ $t('categorias.software') }}</span>
              <img src="../assets/iconos/software.jpg" alt="Software" />
              <button class="boton-favorito" :class="{ activo: favoritoDestacado4 }" @click="favoritoDestacado4 = !favoritoDestacado4">
                <svg viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
              </button>
            </div>
            <div class="informacion-producto">
              <h3>{{ $t('productos.plantillaWeb') }}</h3>
              <p class="nombre-autor">{{ $t('explorar.by') }} Design Academy</p>
              <div class="pie-tarjeta">
                <div class="valoracion"><span>⭐</span> 4.7 <span class="cantidad-votos">(234)</span></div>
                <div class="precio-producto">79.99€</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="anuncio-creadores">
      <div class="caja-azul-anuncio">
        <h2>{{ $t('explorar.creators') }}</h2>
        <p>{{ $t('home.readySub') }}</p>
        <router-link to="/vender" class="boton-crear-producto">
          {{ $t('explorar.createProduct') }} <span>&rarr;</span>
        </router-link>
      </div>
    </section>

    <section class="seccion-tendencias">
      <div class="contenido-tendencias">
        <div class="cabecera-tendencias">
          <div class="titulos-seccion">
            <h2>{{ $t('explorar.trends') }}</h2>
            <p class="descripcion-seccion">{{ $t('home.exploreCatSub') }}</p>
          </div>
        </div>

        <div class="cuadricula-tendencias">
          <div class="tarjeta-tendencia">
            <div class="contenedor-imagen-tendencia">
              <img src="../assets/iconos/plantillas.jpg" alt="Instagram" />
              <button class="boton-favorito" :class="{ activo: favoritoTendencia1 }" @click="favoritoTendencia1 = !favoritoTendencia1">
                <svg viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
              </button>
            </div>
            <div class="informacion-tendencia">
              <h3>{{ $t('productos.packInsta') }}</h3>
              <div class="pie-tarjeta">
                <div class="valoracion"><span>⭐</span> 4.5</div>
                <div class="precio-producto">19.99€</div>
              </div>
            </div>
          </div>

          <div class="tarjeta-tendencia">
            <div class="contenedor-imagen-tendencia">
              <img src="../assets/iconos/presets.jpg" alt="Presets" />
              <button class="boton-favorito" :class="{ activo: favoritoTendencia2 }" @click="favoritoTendencia2 = !favoritoTendencia2">
                <svg viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
              </button>
            </div>
            <div class="informacion-tendencia">
              <h3>{{ $t('productos.presetsLightroom') }}</h3>
              <div class="pie-tarjeta">
                <div class="valoracion"><span>⭐</span> 4.8</div>
                <div class="precio-producto">29.99€</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
  <Footer></Footer>
</template>

<style scoped>
.contenedor-general {
  background-color: #ffffff;
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  align-items: center;
  width: 100%;
}

.hero-explorar {
  padding: 6rem 2rem 4rem;
  text-align: center;
  display: flex;
  flex-direction: column;
  align-items: center;
  max-width: 1100px;
}

.etiqueta-brillante {
  display: inline-flex;
  align-items: center;
  gap: 0.6rem;
  background-color: #f0fdff;
  color: #0097b2;
  padding: 0.6rem 1.2rem;
  border-radius: 50px;
  font-weight: 600;
  font-size: 0.9rem;
  margin-bottom: 2rem;
}

.icono-brillo {
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

.azul-degradado {
  color: #0097b2;
}

.subtitulo-hero {
  font-size: 1.4rem;
  color: #6b7280;
  margin-bottom: 3rem;
  max-width: 700px;
}

.fila-categorias {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 0.8rem;
}

.boton-categoria {
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

.boton-categoria:hover {
  background-color: #0097b2;
  color: #ffffff;
  border-color: #0097b2;
  transform: translateY(-2px);
}

.seccion-destacados,
.seccion-tendencias {
  padding: 4rem 2rem;
  display: flex;
  justify-content: center;
  width: 100%;
  box-sizing: border-box;
}

.seccion-tendencias {
  background-color: #f8faff;
}

.contenido-destacados,
.contenido-tendencias {
  max-width: 1200px;
  width: 100%;
}

.cabecera-destacados,
.cabecera-tendencias {
  display: flex;
  justify-content: space-between;
  align-items: flex-end;
  margin-bottom: 2.5rem;
}

.titulos-seccion h2 {
  font-size: 2.5rem;
  font-weight: 800;
  color: #111827;
  margin-bottom: 0.5rem;
}

.descripcion-seccion {
  font-size: 1.1rem;
  color: #6b7280;
  margin: 0;
}

.enlace-ver-todo {
  text-decoration: none;
  color: #111827;
  font-weight: 600;
  transition: color 0.2s;
}

.enlace-ver-todo:hover {
  color: #0097b2;
}

.anuncio-creadores {
  max-width: 1200px;
  margin: 2rem auto;
  padding: 0 2rem;
  width: 100%;
  box-sizing: border-box;
}

.caja-azul-anuncio {
  background: linear-gradient(90deg, #0097b2 0%, #0cc0df 50%, #0097b2 100%);
  border-radius: 20px;
  padding: 4rem;
  color: white;
  text-align: left;
  box-shadow: 0 10px 25px rgba(0, 151, 178, 0.2);
}

.caja-azul-anuncio h2 {
  font-size: 3rem;
  font-weight: 800;
  margin: 0 0 1rem 0;
  color: white;
}

.caja-azul-anuncio p {
  font-size: 1.2rem;
  max-width: 600px;
  margin-bottom: 2.5rem;
  opacity: 0.9;
}

.boton-crear-producto {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  background-color: #f0fdff;
  color: #0097b2;
  text-decoration: none;
  padding: 1rem 2rem;
  border-radius: 10px;
  font-weight: 700;
  font-size: 1.1rem;
  transition: all 0.3s ease;
}

.boton-crear-producto:hover {
  background-color: #ffffff;
  color: #0cc0df;
  transform: translateY(-2px);
}

.cuadricula-productos,
.cuadricula-tendencias {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 1.5rem;
}

.tarjeta-producto,
.tarjeta-tendencia {
  border: 1px solid #e5e7eb;
  border-radius: 12px;
  overflow: hidden;
  background: white;
  transition: box-shadow 0.3s ease;
  display: flex;
  flex-direction: column;
  position: relative;
}

.tarjeta-producto:hover,
.tarjeta-tendencia:hover {
  box-shadow: 0 10px 20px rgba(12, 192, 223, 0.15);
}

.contenedor-imagen,
.contenedor-imagen-tendencia {
  position: relative;
  height: 180px;
  overflow: hidden;
  background: #f3f4f6;
}

.contenedor-imagen-tendencia {
  height: 250px;
}

.contenedor-imagen img,
.contenedor-imagen-tendencia img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.4s ease;
}

.tarjeta-producto:hover img,
.tarjeta-tendencia:hover img {
  transform: scale(1.08);
}

.boton-favorito {
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
  margin: 0 !important;
}

.boton-favorito svg {
  width: 18px;
  height: 18px;
  color: #111827;
  fill: transparent;
  transition:
    color 0.2s,
    fill 0.2s;
}

.tarjeta-producto:hover .boton-favorito,
.tarjeta-tendencia:hover .boton-favorito {
  opacity: 1;
}

.boton-favorito.activo,
.boton-favorito:hover {
  opacity: 1;
}

.boton-favorito.activo svg,
.boton-favorito:hover svg {
  color: #ef4444;
  fill: #ef4444;
}

.boton-favorito:not(.activo) svg {
  color: #111827;
  fill: transparent;
}

.etiqueta-categoria {
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

.informacion-producto,
.informacion-tendencia {
  padding: 1.5rem;
  flex-grow: 1;
  display: flex;
  flex-direction: column;
}

h3 {
  font-size: 1.1rem;
  font-weight: 700;
  margin-bottom: 0.5rem;
}

.nombre-autor {
  color: #6b7280;
  font-size: 0.9rem;
  margin-bottom: 1.5rem;
}

.pie-tarjeta {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: auto;
  border-top: 1px solid #f3f4f6;
  padding-top: 1rem;
}

.cantidad-votos {
  color: #9ca3af;
  font-weight: normal;
  font-size: 0.85rem;
  margin-left: 4px;
}

.precio-producto {
  color: #0097b2;
  font-weight: 800;
  font-size: 1.2rem;
}

@media (max-width: 1024px) {
  .cuadricula-productos,
  .cuadricula-tendencias {
    grid-template-columns: repeat(2, 1fr);
  }
  h1 {
    font-size: 3rem;
  }
}

@media (max-width: 768px) {
  .hero-explorar {
    padding-top: 4rem;
  }
  .fila-categorias {
    gap: 0.5rem;
  }
  .caja-azul-anuncio {
    padding: 2.5rem;
  }
  .caja-azul-anuncio h2 {
    font-size: 2rem;
  }
}

@media (max-width: 640px) {
  .cuadricula-productos,
  .cuadricula-tendencias {
    grid-template-columns: 1fr;
  }
  .cabecera-destacados {
    flex-direction: column;
    align-items: flex-start;
    gap: 1rem;
  }
}
</style>