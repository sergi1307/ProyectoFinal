<script setup>
import { ref, onMounted } from "vue";
import { useI18n } from "vue-i18n";
import ModalIncidencias from "./modalIncidencias.vue";

const esAdmin = ref(false);
const { t } = useI18n();
const isModalOpen = ref(false);

const verificarRol = async () => {
  const usuarioRaw = JSON.parse(localStorage.getItem("usuario"));

  if (usuarioRaw) {
    esAdmin.value = usuarioRaw.role === "admin";
    console.log(esAdmin.value);
  }
};

onMounted(() => {
  verificarRol();
});
</script>

<template>
  <footer>
    <div class="footer-contenedor">
      <div id="sobreNosotros">
        <h3>{{ $t("footer.aboutTitle") }}</h3>
        <p>{{ $t("footer.aboutText") }}</p>
        <div class="redes-sociales">
          <a href="#">
            <img
              src="../assets/iconos/Facebook.png"
              alt="Facebook"
              class="img-normal"
            />
            <img
              src="../assets/iconos/Facebook-hover.png"
              alt="Facebook"
              class="img-hover"
              aria-hidden="true"
            />
          </a>
          <a href="#">
            <img src="../assets/iconos/X.png" alt="X" class="img-normal" />
            <img
              src="../assets/iconos/X-hover.png"
              alt="X"
              class="img-hover"
              aria-hidden="true"
            />
          </a>
          <a href="#">
            <img
              src="../assets/iconos/Instagram.png"
              alt="Instagram"
              class="img-normal"
            />
            <img
              src="../assets/iconos/Instagram-hover.png"
              alt="Instagram"
              class="img-hover"
              aria-hidden="true"
            />
          </a>
          <a href="#">
            <img
              src="../assets/iconos/Tiktok.png"
              alt="Tiktok"
              class="img-normal"
            />
            <img
              src="../assets/iconos/Tiktok-hover.png"
              alt="Tiktok"
              class="img-hover"
              aria-hidden="true"
            />
          </a>
        </div>
      </div>

      <div id="legal">
        <h3>{{ $t("footer.legalTitle") }}</h3>
        <router-link :to="{ name: 'legal', query: { section: 'tos' } }">{{
          $t("footer.terms")
        }}</router-link>
        <router-link :to="{ name: 'legal', query: { section: 'privacy' } }">{{
          $t("footer.privacy")
        }}</router-link>
        <router-link :to="{ name: 'legal', query: { section: 'cookies' } }">{{
          $t("footer.cookies")
        }}</router-link>
        <router-link :to="{ name: 'legal', query: { section: 'refunds' } }">{{
          $t("footer.refunds")
        }}</router-link>
        <router-link :to="{ name: 'legal', query: { section: 'vendor' } }">{{
          $t("footer.sellerAgreement")
        }}</router-link>
      </div>

      <div id="soporteTecnico">
        <h3>{{ $t("footer.supportTitle") }}</h3>
        <div class="email">
          <img src="../assets/iconos/email.png" alt="Email" />
          <p>{{ $t("auth.email") }}:</p>
          <a href="mailto:soporte@nexus.com">soporte@nexus.com</a>
          <p class="horario">{{ $t("footer.hours") }}</p>
        </div>
        <div v-if="!esAdmin" id="incidencias">
          <button @click="isModalOpen = true">
            <svg
              class="icono-incidencia"
              aria-hidden="true"
              xmlns="http://www.w3.org/2000/svg"
              viewBox="0 0 64 64"
            >
              <g
                fill="none"
                stroke="currentColor"
                stroke-width="3"
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-miterlimit="10"
              >
                <path
                  d="M47.5,10.1h-7.8C38.9,6.6,35.7,4,32,4s-6.9,2.6-7.7,6.1h-7.8C14,10.1,12,12.1,12,14.6v39.3 c0,2.5,2,4.5,4.5,4.5h31c2.5,0,4.5-2,4.5-4.5V14.6C52,12.1,50,10.1,47.5,10.1z"
                />
                <path d="M32,4c-2.2,0-4,1.8-4,4v4h8V8C36,5.8,34.2,4,32,4z" />
                <line x1="20" y1="22" x2="44" y2="22" />
                <line x1="20" y1="30" x2="34" y2="30" />
                <path
                  d="M43.3,38.1l8.5,14.7c1.1,1.9-0.3,4.2-2.5,4.2H32.7c-2.2,0-3.6-2.4-2.5-4.2l8.5-14.7 C39.8,36.2,42.2,36.2,43.3,38.1z"
                />
                <line x1="41" y1="43" x2="41" y2="49" />
                <circle cx="41" cy="53" r="0.5" />
              </g>
            </svg>
            {{ $t("footer.sendIncident") }}
          </button>
        </div>
        <ModalIncidencias
          v-if="!esAdmin"
          :isOpen="isModalOpen"
          @close="isModalOpen = false"
        />
      </div>

      <div id="recursos">
        <h3>{{ $t("footer.resourcesTitle") }}</h3>
        <a href="">{{ $t("footer.helpCenter") }}</a>
        <a href="">{{ $t("footer.sellerGuide") }}</a>
        <a href="">{{ $t("footer.buyerGuide") }}</a>
        <a href="">{{ $t("footer.apiDocs") }}</a>
        <a href="">{{ $t("footer.blog") }}</a>
      </div>
    </div>

    <div class="derechos">
      <p class="copy">
        &copy; 2026 Nexus Marketplace. {{ $t("footer.allRights") }}
      </p>
      <p>{{ $t("footer.securePlatform") }}</p>
    </div>
  </footer>
</template>