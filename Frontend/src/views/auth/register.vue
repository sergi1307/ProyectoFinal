<script></script>

<template>
    <main>
        <form class="container">
            <h1>Crear Cuenta</h1>
            <p>Ingresa tus datos para registrarte</p>
            <label for="name">Nombre:</label>
            <input type="text" id="name" placeholder="Tu Nombre">

            <label for="email">Email:</label>
            <input type="email" id="email" placeholder="example@gmail.com">
    
            <label for="password">Contraseña:</label>
            <input type="password" id="password" placeholder="••••••••••">

            <label for="confirmPassword">Confirmar Contraseña:</label>
            <input type="password" id="confirmPassword" placeholder="••••••••••">

            <button type="submit">Registrarse</button>

            <div id="frase"></div>
            <p id="normal">¿Ya tienes cuenta? <router-link to="/login" id="login">Inicia Sesión</router-link></p>
        </form>
    </main>
</template>

<style scoped>
    main {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        width: 100%;
        background-color: #f8fbff;
    }

    .container {
        display: flex;
        flex-direction: column;
        width: 90%;
        max-width: 500px;
        border-radius: 10px;
        background-color: #ffffff;
        padding: 2em;
        line-height: 2em;
        box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.3);
        transition: all 0.5s;
    }

    .container:hover {
        box-shadow: 0px 0px 8px rgb(37, 99, 235);
    }

    .container h1 {
        font-size: 1.5em;
    }

    .container p {
        color: #313131;
        font-size: 0.9em;
    }

    .container label {
        margin-top: 1em;
        font-weight: bold;
    }

    .container input {
        padding: 0.6em;
        border-radius: 6px;
        border: 1px solid rgba(0, 0, 0, 0.1);
        color: #313131;
        transition: all 0.3s;
    }

    .container input:focus {
        box-shadow: 0px 0px 4px #2563eb;
        outline: none;
    }

    button {
        padding: 0.6em;
        margin: 2em 0em;
        background-color: #2563eb;
        border:none;
        border-radius: 8px;
        color: white;
        font-weight: bold;
        font-size: 1em;
    }

    #frase {
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        text-align: center;
    }

    #normal {
        text-align: center;
    }

    #normal #login {
        color: #2563eb;
        font-weight: bold;
        text-decoration: none;
    }
</style>