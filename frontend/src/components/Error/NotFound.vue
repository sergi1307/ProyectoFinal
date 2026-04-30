<script setup>
</script>

<template>
  <div class="not-found-container">
    <div class="not-found-card">
      <div class="icon-wrapper">
        <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="error-icon">
          <circle cx="12" cy="12" r="10"></circle>
          <path d="m15 9-6 6"></path>
          <path d="m9 9 6 6"></path>
        </svg>
      </div>

      <h1 class="error-code">404</h1>
      <h2 class="error-title">¡Ups! Te has salido de la órbita</h2>
      
      <p class="error-desc">
        La página que buscas en Nexus no existe, ha sido movida o actualmente no está disponible.
      </p>

      <router-link to="/" class="btn-home">
        Volver al Inicio
      </router-link>
    </div>
  </div>
</template>