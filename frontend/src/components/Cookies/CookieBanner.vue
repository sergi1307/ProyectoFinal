<script setup>
import { ref, onMounted } from 'vue';

const mostrarModal = ref(false);

onMounted(() => {
    const cookiesGuardadas = localStorage.getItem('nexus_cookies_consent');

    if (!cookiesGuardadas) {
        mostrarModal.value = true;
        document.body.style.overflow = 'hidden';
    }
});

const aceptarCookies = (tipo) => {
    localStorage.setItem('nexus_cookies_consent', tipo);

    mostrarModal.value = false;
    document.body.style.overflow = '';

    if (tipo === 'todas') {
        console.log("Cookies analíticas activadas.");
    }
};
</script>

<template>
    <div v-if="mostrarModal" class="cookie-overlay">

        <div class="cookie-modal">
            <div class="cookie-icon">🍪</div>
            <h2>{{ $t('cookieBanner.title') }}</h2>
            <p>
                {{ $t('cookieBanner.description') }}
            </p>

            <div class="cookie-acciones">
                <button class="btn-outline" @click="aceptarCookies('esenciales')">{{ $t('cookieBanner.btnEssential') }}</button>
                <button class="btn-primary" @click="aceptarCookies('todas')">{{ $t('cookieBanner.btnAll') }}</button>
            </div>
        </div>

    </div>
</template>