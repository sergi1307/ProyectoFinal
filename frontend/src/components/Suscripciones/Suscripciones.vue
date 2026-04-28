<script setup>
import { ref } from "vue";
import axios from "axios";
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
    const response = await axios.post(
      "http://localhost:8080/api/user/subscribe",
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

<style scoped lang="scss">
@use "@/assets/scss/variables" as *;

.pricing-container {
  padding: 4rem 2rem;
  background-color: $bg-color;
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  align-items: center;
}

.pricing-header {
  text-align: center;
  margin-bottom: 4rem;

  h2 {
    font-size: 2.5rem;
    color: $text-main;
    margin-bottom: 0.5rem;
    font-weight: 800;
  }

  p {
    font-size: 1.1rem;
    color: $text-muted;
  }
}

.pricing-grid {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 2rem;
  max-width: 1200px;
  width: 100%;
}

.pricing-card {
  background: $bg-card;
  border-radius: $border-radius-lg;
  padding: 2.5rem 2rem;
  flex: 1;
  min-width: 300px;
  max-width: 350px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
  display: flex;
  flex-direction: column;
  position: relative;
  transition: $transition-base;
  border: 1px solid #edf2f7;

  &:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 25px rgba(0, 0, 0, 0.08);
  }

  &.is-highlighted {
    border: 2px solid $primary-glow;
    transform: scale(1.03);
    box-shadow: 0 15px 35px rgba(12, 192, 223, 0.15);
    z-index: 2;

    &:hover {
      transform: scale(1.05) translateY(-5px);
    }
  }
}

.badge {
  position: absolute;
  top: -14px;
  left: 50%;
  transform: translateX(-50%);
  background: linear-gradient(135deg, $primary-glow, $primary-blue);
  color: white;
  padding: 4px 16px;
  border-radius: 20px;
  font-size: 0.8rem;
  font-weight: 700;
  letter-spacing: 1px;
  text-transform: uppercase;
  box-shadow: 0 4px 10px rgba(12, 192, 223, 0.3);
}

.card-header {
  text-align: center;
  margin-bottom: 2rem;
  padding-bottom: 2rem;
  border-bottom: 1px solid #edf2f7;

  .plan-name {
    font-size: 1.5rem;
    color: $text-main;
    font-weight: 700;
    margin: 0 0 0.5rem 0;
  }

  .plan-target {
    font-size: 0.85rem;
    color: $text-muted;
    margin: 0 0 1.5rem 0;
  }

  .plan-price {
    display: flex;
    justify-content: center;
    align-items: flex-start;
    color: $text-main;

    .currency {
      font-size: 1.2rem;
      font-weight: 600;
      margin-top: 0.5rem;
    }

    .amount {
      font-size: 3.5rem;
      font-weight: 800;
      line-height: 1;
    }

    .period {
      align-self: flex-end;
      color: $text-muted;
      margin-bottom: 0.5rem;
      margin-left: 0.25rem;
    }
  }
}

.card-body {
  flex-grow: 1;
  margin-bottom: 2rem;

  .features-list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 1rem;

    li {
      display: flex;
      align-items: flex-start;
      gap: 10px;
      color: $text-main;
      font-size: 0.95rem;

      .check-icon {
        width: 20px;
        height: 20px;
        color: $primary-glow;
        flex-shrink: 0;
        margin-top: 2px;
      }
    }
  }
}

.card-footer {
  .btn-suscribe {
    width: 100%;
    padding: 12px;
    border-radius: $border-radius-md;
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    transition: $transition-base;
    border: 2px solid $primary-blue;
    background: transparent;
    color: $primary-blue;

    &:hover {
      background: rgba(0, 151, 178, 0.05);
    }

    &.btn-primary {
      background: $primary-blue;
      color: white;
      box-shadow: 0 4px 12px rgba(0, 151, 178, 0.2);

      &:hover {
        background: $primary-hover;
        box-shadow: 0 6px 15px rgba(0, 151, 178, 0.3);
      }
    }
  }
}

@media (max-width: 768px) {
  .pricing-card.is-highlighted {
    transform: scale(1);

    &:hover {
      transform: translateY(-5px);
    }
  }
}
</style>
