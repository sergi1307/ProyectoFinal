# NEXUS 🚀

![Estado del Proyecto](https://img.shields.io/badge/estado-pendiente-yellow)
![Vue.js](https://img.shields.io/badge/vue.js-3.x-4FC08D)
![Node.js](https://img.shields.io/badge/node.js-18.x-339933)
![SQL](https://img.shields.io/badge/database-SQL-blue)

## 📖 Descripción

Este proyecto es el Trabajo de Fin de Ciclo para 2º de DAW. Consiste en una aplicación web diseñada para vender o comprar productos digitales como: iconos, librerías de programación, pack de texturas para videojuegos...

El objetivo principal es resolver el problema de las plataformas que te ofrecen estos servicios pero solo si pagas de forma mensual, permitiendo a los usuarios comprar lo que quieran sin límite de uso ni de duración.

## 📸 Video Prototipo

https://github.com/user-attachments/assets/57aa71ef-8d9e-46eb-bb0c-7129f941fdc1

## 🛠️ Tecnologías Utilizadas

### Backend
* **Node.js**: Entorno de ejecución.
* **Express**: Framework para la creación de la API REST.
* **mysql2, cors**: Para la conexión con el frontend y con la base de datos.
* **JWT (JsonWebToken)**: Para la autenticación y seguridad.

### Frontend
* **Vue.js 3**: Framework progresivo (Composition API).
* **Vite**: Empaquetador y servidor de desarrollo.
* **Vue Router**: Gestión de rutas.
* **Axios / Fetch**: Consumo de la API.

### Base de Datos
* **MySQL**: Diseño relacional con tablas normalizadas.

### Herramientas
* **Git**: Control de versiones.
* **Trello**: Gestión de tareas (Scrum/Kanban).
* **Postman**: Pruebas de Endpoints.

## ⚙️ Instalación y Configuración

Sigue estos pasos para levantar el proyecto en local.

### Prerrequisitos
* Node.js (v16 o superior)
* NPM
* Motor SQL instalado o en un contenedor Docker
* Git

### 1. Clonar el Repositorio
```bash
git clone [https://github.com/tu-usuario/nombre-repo.git](https://github.com/tu-usuario/nombre-repo.git)
cd nombre-repo
```

### 2. Configuración del Backend (API)
```bash
cd backend
npm install
```

**Variables de Entorno:** Crea un archivo .env en la carpeta backend con la siguiente estructura:
```code
PORT=3000
DB_HOST=localhost
DB_USER=root
DB_PASSWORD=tu_contraseña
DB_NAME=nombre_base_datos
JWT_SECRET=tu_palabra_secreta
```

**Para iniciar el servidor:**
```bash
npm run dev
# o
npm start
```

### 3. Configuración del Frontend (Cliente)
**En una nueva terminal:**
```bash
cd frontend
npm install
```

**Para iniciar el cliente:**
```bash
npm run dev
```

### 4. Base de Datos
**Abre una terminal:**
```bash
docker exec -it mysql_docker mysql -u root -p  #Una vez hecho, introduce tu contraseña de administrador
CREATE DATABASE NombreBaseDeDatos
exit
```

## 🗂️ Estructura del Proyecto
**El proyecto sigue una arquitectura MVC (Modelo-Vista-Controlador):**
```code
nombre-repo/
├── backend/            # API REST (Node + Express)
│   ├── src/
│   │   ├── controllers/# Lógica de las rutas
│   │   ├── routes/     # Definición de Endpoints
│   │   ├── models/     # Consultas SQL
│   │   └── config/     # Conexión a BD
├── frontend/           # SPA (Vue.js)
│   ├── src/
│   │   ├── components/ # Componentes reutilizables
│   │   ├── views/      # Vistas principales
│   │   ├── stores/     # Estado (Pinia)
│   │   └── services/   # Llamadas a la API
└── database/           # Scripts SQL y diagrama ER
```

## ✨ Funcionalidades Principales

* Registro e inicio de sesión de usuarios (Auth).
* CRUD completo.
* Filtrado y búsqueda de datos.
* Panel de administración protegido.

## ✒️ Autores
* **Inés Álvarez Calle**
* **Sergi Molina Barberà**
