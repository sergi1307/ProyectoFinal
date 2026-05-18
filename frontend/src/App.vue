<script setup>
import { cargandoGlobal } from '@/loaderState';
import LoadingSpinner from '@/components/LoadingSpinner/LoadingSpinner.vue';
import CookieBanner from './components/Cookies/CookieBanner.vue';
import ToastContainer from './components/ToastContainer.vue';
</script>

<template>
  <div v-if="cargandoGlobal" class="superglobal-loader">
    <LoadingSpinner />
  </div>

  <router-view></router-view>

  <ToastContainer />
  
  <CookieBanner></CookieBanner>
</template>

<style lang="scss">
@import "@/assets/scss/main";
</style>