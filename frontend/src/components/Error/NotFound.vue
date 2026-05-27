<script setup>
import { useRouter } from 'vue-router';
import NavBar from '../NavBar.vue';
import Footer from '../Footer.vue';

const router = useRouter();

const volverAtras = () => {
  if (window.history.length > 1) {
    router.back();
  } else {
    router.push('/');
  }
};
</script>

<template>
  <NavBar></NavBar>
  <div class="not-found-container">
    <div class="not-found-card">
      <h1 class="error-code">404</h1>
      <h2 class="error-title">{{ $t('notFound.title') }}</h2>
      
      <p class="error-desc">
        {{ $t('notFound.desc') }}
      </p>

      <button @click="volverAtras" class="btn-home" style="cursor: pointer; border: none; font-family: inherit;">
        {{ $t('notFound.goBack') }}
      </button>
    </div>
  </div>
  <Footer></Footer>
</template>