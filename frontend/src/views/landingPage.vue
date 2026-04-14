<script setup>
import { useI18n } from 'vue-i18n';
import NavBar from '@/components/NavBar.vue';
import Footer from '@/components/Footer.vue';

const { t } = useI18n();
</script>

<template>
  <NavBar></NavBar>
  <main class="home-main">
    <section class="hero">
      <div class="hero-contenido">
        <div class="etiqueta">
          <img src="../assets/iconos/brillos.png" alt="Estrella" class="brillos"/>
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