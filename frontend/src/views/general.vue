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