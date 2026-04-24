<script setup>
  import './assets/scss/main.scss';
  import CookieBanner from './components/Cookies/CookieBanner.vue';
</script>
<template>
  <router-view></router-view>
  <CookieBanner></CookieBanner>
</template>