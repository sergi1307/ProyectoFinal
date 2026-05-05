<script setup>
import { onMounted, watch, nextTick } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import NavBar from '@/components/NavBar.vue';
import Footer from '@/components/Footer.vue';

const route = useRoute();
const router = useRouter();

const goBack = () => {
  router.back();
};

const scrollToSection = async () => {
  await nextTick();
  const sectionId = route.query.section;
  
  if (sectionId) {
    const element = document.getElementById(sectionId);
    if (element) {
      element.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  }
};

onMounted(() => {
  scrollToSection();
});

watch(() => route.query.section, () => {
  scrollToSection();
});
</script>

<template>
  <div class="legal-layout">
    <NavBar/>

    <main class="legal-main-content">
      
      <div class="back-container">
        <button @click="goBack" class="btn-volver">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M19 12H5M12 19l-7-7 7-7"/>
          </svg>
          {{ $t('auth.backHome') }}
        </button>
      </div>

      <article class="document-card">
        
        <header class="legal-header">
          <h1>{{ $t('legal.mainTitle') }}</h1>
          <p class="meta">{{ $t('legal.lastUpdate') }}</p>
        </header>

        <section id="tos" class="legal-section">
          <h2>{{ $t('legal.tos.title') }}</h2>
          <div class="body-text">
            <h3>{{ $t('legal.tos.s1_h') }}</h3>
            <p>{{ $t('legal.tos.s1_p') }}</p>
            
            <h3>{{ $t('legal.tos.s2_h') }}</h3>
            <p>{{ $t('legal.tos.s2_p1') }} <strong>[X]%</strong> {{ $t('legal.tos.s2_p2') }}</p>
            
            <h3>{{ $t('legal.tos.s3_h') }}</h3>
            <p>{{ $t('legal.tos.s3_p') }}</p>
          </div>
        </section>

        <hr class="divider" />

        <section id="privacy" class="legal-section">
          <h2>{{ $t('legal.privacy.title') }}</h2>
          <div class="body-text">
            <h3>{{ $t('legal.privacy.s1_h') }}</h3>
            <p>{{ $t('legal.privacy.s1_p') }}</p>
            
            <h3>{{ $t('legal.privacy.s2_h') }}</h3>
            <p>{{ $t('legal.privacy.s2_p') }}</p>

            <h3>{{ $t('legal.privacy.s3_h') }}</h3>
            <p>{{ $t('legal.privacy.s3_p') }}</p>
          </div>
        </section>

        <hr class="divider" />

        <section id="cookies" class="legal-section">
          <h2>{{ $t('legal.cookies.title') }}</h2>
          <div class="body-text">
            <p>{{ $t('legal.cookies.intro') }}</p>
            <table class="legal-table">
              <thead>
                <tr>
                  <th>{{ $t('legal.cookies.th1') }}</th>
                  <th>{{ $t('legal.cookies.th2') }}</th>
                  <th>{{ $t('legal.cookies.th3') }}</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td><strong>{{ $t('legal.cookies.cat1_t') }}</strong></td>
                  <td>{{ $t('legal.cookies.cat1_p') }}</td>
                  <td>{{ $t('legal.cookies.dur_s') }}</td>
                </tr>
                <tr>
                  <td><strong>{{ $t('legal.cookies.cat2_t') }}</strong></td>
                  <td>{{ $t('legal.cookies.cat2_p') }}</td>
                  <td>{{ $t('legal.cookies.dur_a') }}</td>
                </tr>
                <tr>
                  <td><strong>{{ $t('legal.cookies.cat3_t') }}</strong></td>
                  <td>{{ $t('legal.cookies.cat3_p') }}</td>
                  <td>{{ $t('legal.cookies.dur_m') }}</td>
                </tr>
              </tbody>
            </table>
            <p>{{ $t('legal.cookies.footer') }}</p>
          </div>
        </section>

        <hr class="divider" />

        <section id="refunds" class="legal-section">
          <h2>{{ $t('legal.refunds.title') }}</h2>
          <div class="body-text">
            <div class="alert-box">
              <strong>{{ $t('legal.refunds.alert_h') }}</strong> {{ $t('legal.refunds.alert_p') }}
            </div>
            <h3>{{ $t('legal.refunds.s1_h') }}</h3>
            <p>{{ $t('legal.refunds.s1_p') }}</p>
            <ul>
              <li>{{ $t('legal.refunds.li1') }}</li>
              <li>{{ $t('legal.refunds.li2') }}</li>
            </ul>
            <p>{{ $t('legal.refunds.footer') }}</p>
          </div>
        </section>

        <hr class="divider" />

        <section id="vendor" class="legal-section">
          <h2>{{ $t('legal.vendor.title') }}</h2>
          <div class="body-text">
            <h3>{{ $t('legal.vendor.s1_h') }}</h3>
            <p>{{ $t('legal.vendor.s1_p') }}</p>
            
            <h3>{{ $t('legal.vendor.s2_h') }}</h3>
            <p>{{ $t('legal.vendor.s2_p1') }} <strong>[X]</strong> {{ $t('legal.vendor.s2_p2') }}</p>

            <h3>{{ $t('legal.vendor.s3_h') }}</h3>
            <p>{{ $t('legal.vendor.s3_p') }}</p>
          </div>
        </section>

      </article>

      <footer class="contact-footer">
        <p>{{ $t('legal.footer_q') }} <a href="mailto:soporte@nexus.com">soporte@nexus.com</a></p>
      </footer>
    </main>
  </div>

  <Footer/>
</template>