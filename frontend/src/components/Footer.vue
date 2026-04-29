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

<style scoped>

footer {
  background-color: #0f172a;
  color: #94a3b8;
  font-family: "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
  padding: 5rem 2rem 3rem 2rem;
}

h3 {
  color: #ffffff;
  font-size: 1.15rem;
  font-weight: 600;
  margin-top: 0;
  margin-bottom: 1.5rem;
  letter-spacing: 0.5px;
}

p {
  font-size: 0.95rem;
  line-height: 1.7;
  margin-top: 0;
  margin-bottom: 1rem;
}

.footer-contenedor a {
  display: table;
  color: #cbd5e1;
  text-decoration: none;
  font-size: 0.95rem;
  margin-bottom: 1rem;
  transition: all 0.2s ease-in-out;
}

.footer-contenedor a:hover {
  color: #0cc0df;
  transform: translateX(4px);
}

#sobreNosotros p {
  margin-bottom: 1.8rem;
  text-align: left;
  max-width: 90%;
}

.redes-sociales {
  display: flex;
  gap: 1.2rem;
}

.redes-sociales a {
  display: inline-block;
  width: 24px;
  height: 24px;
  margin-bottom: 0;
}

.redes-sociales a img {
  width: 100%;
  height: 100%;
  object-fit: contain;
}

.img-normal { display: block; }
.img-hover { display: none; }
.redes-sociales a:hover .img-normal { display: none; }
.redes-sociales a:hover .img-hover { display: block; }
.redes-sociales a:hover { transform: translateY(-3px); }

.email {
  display: grid;
  grid-template-columns: 24px 1fr;
  gap: 0.2rem 1rem;
  margin-bottom: 1.8rem;
  align-items: start;
}

.email img {
  grid-column: 1;
  grid-row: 1 / span 3;
  width: 24px;
  height: 24px;
  object-fit: contain;
  margin-top: 2px;
}

.email p { color: #ffffff; margin-bottom: 0; font-weight: 500; }
.email a { color: #94a3b8; margin-bottom: 0; display: inline-block; }
.email .horario { color: #64748b; font-size: 0.85rem; margin-top: 0.3rem; }

#incidencias button {
  background-color: #ffffff;
  color: #0f172a;
  border: 1px solid transparent;
  border-radius: 8px;
  padding: 0.85rem 1.5rem;
  width: 100%;
  max-width: 280px;
  font-weight: 600;
  font-size: 0.95rem;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.6rem;
  transition: all 0.3s ease;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

#incidencias button:hover {
  background-color: #f8fafc;
  color: #0cc0df;
  border-color: #0cc0df;
  transform: translateY(-2px);
  box-shadow: 0 6px 8px -1px rgba(0, 0, 0, 0.15);
}

#incidencias button .icono-incidencia {
  width: 22px;
  height: 22px;
  object-fit: contain;
}

.derechos {
  text-align: center;
  margin-top: 5rem;
  padding-top: 2.5rem;
  border-top: 1px solid rgba(255, 255, 255, 0.05);
}

.derechos p { color: #64748b; font-size: 0.85rem; margin-bottom: 0.5rem; }
.derechos p.copy { color: #e2e8f0; font-size: 0.95rem; margin-bottom: 0.5rem; }

.footer-contenedor {
  display: grid;
  grid-template-columns: 2.5fr 1fr 1.5fr 1fr;
  gap: 4rem 2rem;
  max-width: 1300px;
  margin: 0 auto;
  align-items: flex-start;
}

@media (max-width: 968px) {
  .footer-contenedor {
    grid-template-columns: 1.5fr 1fr;
    gap: 3.5rem 2rem;
  }
}

@media (max-width: 650px) {
  .footer-contenedor {
    grid-template-columns: 1fr;
    gap: 3rem;
  }
  
  footer {
    padding: 3rem 1.5rem 2rem 1.5rem;
  }
  
  #sobreNosotros p {
    max-width: 100%;
  }

  #incidencias button {
    max-width: 100%;
  }
}
</style>