<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import axios from 'axios';
import router from '@/routes/routes';
import "../../assets/css/auth.css";

const route = useRoute();
const email = ref('');
const code = ref('');

onMounted(() => {
    console.log("Query params:", route.query);
    email.value = route.query.email || '';
});

const verificarCodigo = async () => {
    if (!code.value) {
        console.log("Datos introducidos no válidos.");
        return;
    }

    if (code.value.length !== 6) {
        console.log("El código debe tener 6 dígitos.");
        return;
    }

    const payload = {
        "email": email.value,
        "code": code.value
    }

    try {
        const response = await axios.post(`http://localhost:8080/api/auth/verificar-email`, payload);
        console.log("Email verificado con éxito.");
        router.push('/login');
    } catch (error) {
        console.error("Error al verificar el código:", error.response.data);
    }
}

const reenviarCodigo = async () => {
    if (!email.value) {
        console.log("No se encontró el email.");
        return;
    }

    try {
        await axios.post(`http://localhost:8080/api/auth/reenviar-codigo`, { email: email.value });
        console.log("Código reenviado con éxito.");
    } catch (error) {
        console.error("Error al reenviar el código:", error);
    }
}
</script>

<template>
    <main>
        <router-link to="/" class="inicio">Volver al inicio</router-link>
        <form class="container" @submit.prevent="verificarCodigo">
            <h1>Verificar Email</h1>
            <p>Introduce el código de 6 dígitos que te hemos enviado a <strong>{{ email }}</strong></p>

            <label for="code">Código de verificación:</label>
            <input type="text" id="code" v-model="code" placeholder="000000" maxlength="6" />

            <button type="submit">Verificar</button>

            <div id="frase"></div>

            <p id="normal">
                ¿No has recibido el código?
                <span id="cambiar" @click="reenviarCodigo">Reenviar código</span>
            </p>
        </form>
    </main>
</template>