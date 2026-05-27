<script setup>
import api from "@/services/api";
import { ref, computed } from "vue";
import NavBar from "../NavBar.vue";
import Footer from "../Footer.vue";
import { useRouter } from "vue-router";
import { useToast } from '@/composables/useToast';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();
const { addToast } = useToast();
const router = useRouter();

const planes = computed(() => [
  {
    id: "basico",
    name: t('planes.nombreBasico'),
    target: t('planes.basicoTarget'),
    price: "0",
    period: t('planes.mes'),
    highlighted: false,
    features: [
      t('planes.bFeat1'),
      t('planes.bFeat2'),
      t('planes.bFeat3'),
      t('planes.bFeat4'),
      t('planes.bFeat5'),
    ],
    buttonText: t('planes.btnEmpezar'),
  },
  {
    id: "pro",
    name: "Nexus Pro",
    target: t('planes.proTarget'),
    price: "14.99",
    period: t('planes.mes'),
    highlighted: true,
    badge: t('planes.badgeRentable'),
    features: [
      t('planes.pFeat1'),
      t('planes.pFeat2'),
      t('planes.pFeat3'),
      t('planes.pFeat4'),
      t('planes.pFeat5'),
    ],
    buttonText: t('planes.btnPro'),
  },
  {
    id: "studio",
    name: "Nexus Ultra",
    target: t('planes.ultraTarget'),
    price: "39.99",
    period: t('planes.mes'),
    highlighted: false,
    features: [
      t('planes.uFeat1'),
      t('planes.uFeat2'),
      t('planes.uFeat3'),
      t('planes.uFeat4'),
      t('planes.uFeat5'),
      t('planes.uFeat6'),
    ],
    buttonText: t('planes.btnUltra'),
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

    addToast(t('planes.toastSuccess') + " " + response.data.plan + "!", 'success');

    router.push("/perfil");
  } catch (error) {
    console.error(error);
    addToast(t('planes.toastError'), 'error');
  }
};
</script>

<template>
  <NavBar></NavBar>
  <div class="pricing-container">
    <div class="pricing-header">
      <h2>{{ $t('planes.titulo') }}</h2>
      <p>{{ $t('planes.subtitulo') }}</p>
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