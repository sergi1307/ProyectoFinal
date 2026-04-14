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

<style lang="scss" scoped>
$primary-blue: #0097b2;
$bg-color: #f8fbff;
$bg-card: #ffffff;
$text-main: #313131;
$text-muted: #6b7280;
$border-radius: 12px;

.legal-layout {
  min-height: 100vh;
  background-color: $bg-color;
  padding-bottom: 60px;
}

.legal-main-content {
  display: flex;
  flex-direction: column;
  align-items: center;
  max-width: 900px;
  margin: 40px auto;
  padding: 0 20px;
}

.back-container {
  width: 100%;
  margin-bottom: 1rem;
}

.btn-volver {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: none;
  border: none;
  color: $text-muted;
  font-weight: bold;
  font-size: 0.95rem;
  cursor: pointer;
  padding: 0;
  transition: color 0.3s ease;
  
  &:hover {
    color: $primary-blue;
  }
}

.document-card {
  background: $bg-card;
  padding: 4rem;
  border-radius: $border-radius;
  box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.05);
  width: 100%;
  box-sizing: border-box;
}

.legal-header {
  text-align: center;
  margin-bottom: 3rem;
  
  h1 {
    font-size: 2.2rem;
    color: $primary-blue;
    margin-bottom: 0.5rem;
  }
  
  .meta {
    font-size: 0.9rem;
    color: $text-muted;
  }
}

.legal-section {
  margin: 2rem 0;
  scroll-margin-top: 100px;

  h2 {
    font-size: 1.6rem;
    color: $text-main;
    margin-bottom: 1.5rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid rgba($primary-blue, 0.1);
  }
}

.divider {
  border: none;
  height: 1px;
  background-color: #f0f0f0;
  margin: 3rem 0;
}

.body-text {
  line-height: 1.8;
  color: #444;

  h3 { 
    margin-top: 2rem; 
    color: $primary-blue; 
    font-size: 1.1rem;
    margin-bottom: 0.8rem;
  }
  
  p {
    margin-bottom: 1.2rem;
    text-align: justify;
  }

  ul {
    margin-bottom: 1.5rem;
    padding-left: 2rem;
    li {
      margin-bottom: 0.5rem;
      text-align: justify;
    }
  }
}

.legal-table {
  width: 100%;
  border-collapse: collapse;
  margin: 2rem 0;
  td, th { padding: 14px 12px; border: 1px solid #eee; text-align: left;}
  th { background: #fafafa; color: $text-main; font-weight: bold; }
  td { font-size: 0.95rem; }
}

.alert-box {
  background: #fff5f5;
  border-left: 4px solid #fc8181;
  padding: 1.5rem;
  color: #c53030;
  border-radius: 6px;
  margin: 1.5rem 0;
  line-height: 1.6;
}

.contact-footer {
  width: 100%;
  margin-top: 2rem;
  text-align: center;
  color: $text-muted;
  a { color: $primary-blue; font-weight: bold; text-decoration: none; }
}

@media (max-width: 600px) {
  .document-card {
    padding: 2rem 1.5rem;
  }
  
  .legal-header h1 {
    font-size: 1.8rem;
  }

  .legal-table td, .legal-table th {
    padding: 10px 8px;
    font-size: 0.85rem;
  }
}
</style>