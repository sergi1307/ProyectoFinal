<script>

</script>
<template>
    <nav class="navbar">
        <a href="#" class="logo-container">
            <img src="../assets/logos/Logo_gr.png" alt="Logo Nexus" class="imagen-logo">
        </a>

        <div class="buscador">
            <svg class="lupa" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            <input type="text" placeholder="Buscar productos...">
        </div>

        <div class="acciones">
            <a href="#" class="enlace">Iniciar Sesión</a>
            <button class="boton">Registrarse</button>
        </div>
    </nav>
</template>

<style scoped>
    .navbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 40px;
        background-color: #fff;
        border-bottom: 1px solid #eee;
        height: 70px;
    }

    .logo-container {
        display: flex;
        align-items: center;
    }

    .imagen-logo {
        height: 100px;
        width: auto;
        object-fit: contain;
    }

    .buscador {
        display: flex;
        align-items: center;
        background-color: #f8f9fa;
        padding: 10px 16px;
        border-radius: 6px;
        width: 400px;
    }

    .lupa {
        width: 18px;
        height: 18px;
        color: #6b7280;
        margin-right: 10px;
    }

    .buscador input {
        border: none;
        background-color: transparent;
        outline: none;
        width: 100%;
        font-size: 15px;
        color: #333;
    }

    .buscador input::placeholder {
        color: #888;
    }

    .buscador:focus-within {
        background-color: #fff;
        outline: 2px solid #0097b2;
        box-shadow: 0 0 8px rgba(0, 151, 178, 0.3);
    }

    .acciones {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .enlace {
        text-decoration: none;
        color: #000;
        font-weight: 600;
        font-size: 14px;
        padding: 10px 20px;
        border-radius: 6px;
        transition: background 0.2s;
    }

    .enlace:hover {
        color: white;
        background-color: #0097b2;
    }

    .boton {
        background-color: #0097b2;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: background 0.2s;
    }

    .boton:hover {
        background-color: #008ca5;
    }
</style>