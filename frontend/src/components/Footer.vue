<script setup>
import { useI18n } from 'vue-i18n';
const { t } = useI18n();
</script>

<template>
    <footer>
        <div class="contenedor-principal">
            <div id="sobreNosotros">
                <h3>{{ $t('footer.aboutTitle') }}</h3>
                <p>{{ $t('footer.aboutText') }}</p>
                <div class="redes-sociales">
                  <a href="#"><img src="../assets/iconos/Facebook.png" alt="Facebook" class="img-normal"><img src="../assets/iconos/Facebook-hover.png" alt="Facebook" class="img-hover" aria-hidden="true"></a>
                  <a href="#"><img src="../assets/iconos/X.png" alt="X" class="img-normal"><img src="../assets/iconos/X-hover.png" alt="X" class="img-hover" aria-hidden="true"></a>
                  <a href="#"><img src="../assets/iconos/Instagram.png" alt="Instagram" class="img-normal"><img src="../assets/iconos/Instagram-hover.png" alt="Instagram" class="img-hover" aria-hidden="true"></a>
                  <a href="#"><img src="../assets/iconos/Tiktok.png" alt="Tiktok" class="img-normal"><img src="../assets/iconos/Tiktok-hover.png" alt="Tiktok" class="img-hover" aria-hidden="true"></a>
                </div>
            </div>

            <div id="legal">
                <h3>{{ $t('footer.legalTitle') }}</h3>
                <a href="">{{ $t('footer.terms') }}</a>
                <a href="">{{ $t('footer.privacy') }}</a>
                <a href="">{{ $t('footer.cookies') }}</a>
                <a href="">{{ $t('footer.refunds') }}</a>
                <a href="">{{ $t('footer.sellerAgreement') }}</a>
            </div>

            <div id="soporteTecnico">
                <h3>{{ $t('footer.supportTitle') }}</h3>
                <div class="email">
                    <img src="../assets/iconos/email.png" alt="Email">
                    <p>{{ $t('auth.email') }}:</p>
                    <a href="mailto:soporte@nexus.com">soporte@nexus.com</a>
                </div>
                
                <div class="telefono">
                    <img src="../assets/iconos/telefono.png" alt="Teléfono">
                    <p>{{ $t('footer.phone') }}:</p>
                    <a href="tel:+34900123456">+34 900 123 456</a>
                    <p>{{ $t('footer.hours') }}</p>
                </div>

                <div id="incidencias">
                    <button>
                        <svg class="icono-incidencia" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64">
                          <g fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10">
                            <path d="M47.5,10.1h-7.8C38.9,6.6,35.7,4,32,4s-6.9,2.6-7.7,6.1h-7.8C14,10.1,12,12.1,12,14.6v39.3
                              c0,2.5,2,4.5,4.5,4.5h31c2.5,0,4.5-2,4.5-4.5V14.6C52,12.1,50,10.1,47.5,10.1z"/>
                            
                            <path d="M32,4c-2.2,0-4,1.8-4,4v4h8V8C36,5.8,34.2,4,32,4z"/>
                            
                            <line x1="20" y1="22" x2="44" y2="22"/>
                            <line x1="20" y1="30" x2="34" y2="30"/>
                            
                            <path d="M43.3,38.1l8.5,14.7c1.1,1.9-0.3,4.2-2.5,4.2H32.7c-2.2,0-3.6-2.4-2.5-4.2l8.5-14.7
                              C39.8,36.2,42.2,36.2,43.3,38.1z"/>
                            
                            <line x1="41" y1="43" x2="41" y2="49"/>
                            <circle cx="41" cy="53" r="0.5"/>
                          </g>
                        </svg>
                        {{ $t('footer.sendIncident') }}
                    </button>
                </div>
            </div>

            <div id="recursos">
                <h3>{{ $t('footer.resourcesTitle') }}</h3>
                <a href="">{{ $t('footer.helpCenter') }}</a>
                <a href="">{{ $t('footer.sellerGuide') }}</a>
                <a href="">{{ $t('footer.buyerGuide') }}</a>
                <a href="">{{ $t('footer.apiDocs') }}</a>
                <a href="">{{ $t('footer.blog') }}</a>
            </div>            
        </div>

        <div class="derechos">
            <p class="copy">&copy; 2026 Nexus Marketplace. {{ $t('footer.allRights') }}</p>
            <p>{{ $t('footer.securePlatform') }}</p>
        </div>
    </footer>
</template>

<style scoped>
footer {
  background-color: #0f172a;
  color: #94a3b8;
  font-family: "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
  padding: 4rem 2rem 2rem 2rem;
}

.contenedor-principal {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 3rem;
  max-width: 1200px;
  margin: 0 auto;
}

h3 {
  color: #ffffff;
  font-size: 1.1rem;
  font-weight: 600;
  margin-top: 0;
  margin-bottom: 1.5rem;
}

p {
  font-size: 1rem;
  line-height: 1.6;
  margin-top: 0;
  margin-bottom: 1rem;
}

.contenedor-principal a {
  display: block;
  color: #cbd5e1;
  text-decoration: none;
  font-size: 1rem;
  margin-bottom: 0.8rem;
  transition: color 0.2s;
}

.contenedor-principal a:hover {
  color: #0cc0df;
}

#sobreNosotros p {
  margin-bottom: 1.5rem;
  text-align: justify;
}

.redes-sociales {
  display: flex;
  gap: 1rem;
}

.redes-sociales a {
  display: inline-block;
  width: 25px;
  height: 25px;
  margin-bottom: 0;
}

.redes-sociales a img {
  width: 25px;
  height: 25px;
  object-fit: contain;
}

.img-normal {
  display: block;
}

.img-hover {
  display: none;
}

.redes-sociales a:hover .img-normal {
  display: none;
}

.redes-sociales a:hover .img-hover {
  display: block;
}

.email, .telefono {
  display: grid;
  grid-template-columns: 24px 1fr;
  gap: 0.2rem 0.8rem;
  margin-bottom: 1.5rem;
  align-items: start;
}

.email img, .telefono img {
  grid-column: 1;
  grid-row: 1 / span 3;
  width: 25px;
  height: 25px;
  object-fit: contain;
}

.email p, .telefono p {
  color: #ffffff;
  margin-bottom: 0;
}

.email a, .telefono p:last-child {
  color: #94a3b8;
  margin-bottom: 0;
}

.telefono p:last-child {
  font-size: 0.8rem;
}

#incidencias button {
  background-color: #ffffff;
  color: #0f172a; 
  border: none;
  border-radius: 6px;
  padding: 0.75rem 1.5rem;
  width: 100%;
  font-weight: 600;
  font-size: 1rem;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  transition: background-color 0.3s ease, color 0.3s ease;
}

#incidencias button:hover {
  background-color: #f1f5f9;
  color: #0cc0df; 
}

#incidencias button .icono-incidencia {
  width: 25px;
  height: 25px;
  object-fit: contain;
}

.derechos {
  text-align: center;
  margin-top: 4rem;
  padding-top: 2rem;
  border-top: 1px solid #1e293b;
}

.derechos p {
  color: #94a3b8;
  font-size: 0.8rem;
  margin-bottom: 0.5rem;
}

.derechos p.copy {
  color: white;
  font-size: 0.9rem;
  margin-bottom: 0.5rem;
}
</style>