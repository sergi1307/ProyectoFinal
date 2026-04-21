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

<style scoped lang="scss">
@use "@/assets/scss/variables" as *;

.not-found-container {
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 100vh;
  background-color: $bg-color;
  padding: 20px;
}

.not-found-card {
  background-color: $bg-card;
  padding: 3rem 2rem;
  border-radius: $border-radius-lg;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
  text-align: center;
  max-width: 450px;
  width: 100%;
  transition: $transition-base;
  
  &:hover {
    box-shadow: 0 15px 40px rgba(12, 192, 223, 0.1);
    transform: translateY(-2px);
  }
}

.icon-wrapper {
  margin-bottom: 1rem;
  
  .error-icon {
    color: $primary-light;
  }
}

.error-code {
  font-size: 6rem;
  font-weight: 900;
  color: $primary-blue;
  margin: 0;
  line-height: 1;
  text-shadow: 0 4px 15px rgba(12, 192, 223, 0.2);
}

.error-title {
  font-size: 1.5rem;
  color: $text-main;
  margin: 1rem 0;
  font-weight: 700;
}

.error-desc {
  color: $text-muted;
  font-size: 0.95rem;
  line-height: 1.5;
  margin-bottom: 2rem;
}

.btn-home {
  display: inline-block;
  background-color: $primary-blue;
  color: #ffffff;
  text-decoration: none;
  padding: 12px 24px;
  border-radius: $border-radius-md;
  font-weight: 600;
  transition: $transition-base;
  box-shadow: 0 4px 10px rgba(0, 151, 178, 0.25);

  &:hover {
    background-color: $primary-hover;
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(0, 151, 178, 0.4);
  }
  
  &:active {
    transform: translateY(0);
  }
}
</style>