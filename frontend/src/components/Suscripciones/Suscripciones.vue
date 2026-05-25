<script setup>
import { ref } from "vue";
import api from "@/services/api";
import NavBar from "../NavBar.vue";
import Footer from "../Footer.vue";
import { useRouter } from "vue-router";

const router = useRouter();

const planes = ref([
  {
    id: "basico",
    name: "Nexus Básico",
    target: "Para creadores que empiezan",
    price: "0",
    period: "/ mes",
    highlighted: false,
    features: [
      "30% de comisión retenida",
      "Máximo 10 productos en tienda",
      "Visibilidad estándar en buscador",
      "Soporte por email (48h)",
      "Retiros mensuales por PayPal",
    ],
    buttonText: "Empezar a Vender",
  },
  {
    id: "pro",
    name: "Nexus Pro",
    target: "Para vendedores regulares",
    price: "14.99",
    period: "/ mes",
    highlighted: true,
    badge: "Más Rentable",
    features: [
      "Solo 15% de comisión retenida",
      "Productos ilimitados",
      'Insignia de "Vendedor Pro"',
      'Aparición en sección "Tendencias"',
      "Retiros prioritarios",
    ],
    buttonText: "Hacerme Pro",
  },
  {
    id: "studio",
    name: "Nexus Ultra",
    target: "Para agencias y top sellers",
    price: "39.99",
    period: "/ mes",
    highlighted: false,
    features: [
      "Solo 5% de comisión retenida",
      "Productos ilimitados",
      'Aparición en portada "Destacados"',
      'Insignia de "Vendedor Ultra"',
      "Panel de analíticas avanzadas",
      "Soporte VIP directo",
    ],
    buttonText: "Subir a Studio",
  },
]);

const suscribirse = async (planId) => {
  const nombresOficiales = { basico: "Básico", pro: "Pro", studio: "Ultra" };
  const planParaEnviar = nombresOficiales[planId];

  try {
    const token = localStorage.getItem("token");
    const response = await api.post(
      "/user/subscribe",
      { plan: planParaEnviar },
      {
        headers: {
          Authorization: `Bearer ${token}`,
          Accept: "application/json",
        },
      },
    );

    const usuarioLoc = JSON.parse(localStorage.getItem("usuario")) || {};
    usuarioLoc.plan = response.data.plan;
    localStorage.setItem("usuario", JSON.stringify(usuarioLoc));

    window.dispatchEvent(new Event("perfil-actualizado"));

    alert("¡Plan actualizado a " + response.data.plan + "!");

    router.push("/perfil");
  } catch (error) {
    console.error(error);
    alert("Error al cambiar de plan");
  }
};
</script>

<template>
  <NavBar></NavBar>
  <div class="pricing-container">
    <div class="pricing-header">
      <h2>Monetiza tu creatividad en Nexus</h2>
      <p>
        Elige el plan de vendedor que mejor se adapte a tu volumen de ventas y
        maximiza tus beneficios.
      </p>
    </div>

    <div class="pricing-grid">
      <div
        v-for="plan in planes"
        :key="plan.id"
        :class="['pricing-card', { 'is-highlighted': plan.highlighted }]"
      >
        <div v-if="plan.badge" class="badge">{{ plan.badge }}</div>

        <div class="card-header">
          <h3 class="plan-name">{{ plan.name }}</h3>
          <p class="plan-target">{{ plan.target }}</p>
          <div class="plan-price">
            <span class="currency">€</span>
            <span class="amount">{{ plan.price }}</span>
            <span class="period">{{ plan.period }}</span>
          </div>
        </div>

        <div class="card-body">
          <ul class="features-list">
            <li v-for="(feature, index) in plan.features" :key="index">
              <svg
                class="check-icon"
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
              >
                <polyline points="20 6 9 17 4 12"></polyline>
              </svg>
              <span>{{ feature }}</span>
            </li>
          </ul>
        </div>

        <div class="card-footer">
          <button
            type="button"
            @click.prevent="suscribirse(plan.id)"
            :class="['btn-suscribe', { 'btn-primary': plan.highlighted }]"
          >
            {{ plan.buttonText }}
          </button>
        </div>
      </div>
    </div>
  </div>
  <Footer></Footer>
</template>