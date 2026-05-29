# NEXUS 🚀

![Estado del Proyecto](https://img.shields.io/badge/estado-en%20producción-brightgreen)
![Laravel](https://img.shields.io/badge/Laravel-13.1.1-FF2D20)
![PHP](https://img.shields.io/badge/PHP-8.4-777BB4)
![Vue.js](https://img.shields.io/badge/Vue.js-3.5.27-4FC08D)
![Node.js](https://img.shields.io/badge/Node.js-v24.13.0-339933)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1)
![Docker](https://img.shields.io/badge/Docker-contenedores-2496ED)

## 📖 Descripción

Este proyecto es el Trabajo de Fin de Ciclo para 2º de DAW. Consiste en una aplicación web diseñada para vender o comprar productos digitales como: iconos, librerías de programación, pack de texturas para videojuegos...

El objetivo principal es resolver el problema de las plataformas que te ofrecen estos servicios pero solo si pagas de forma mensual, permitiendo a los usuarios comprar lo que quieran sin límite de uso ni de duración.

## 📸 Video Prototipo

https://github.com/user-attachments/assets/57aa71ef-8d9e-46eb-bb0c-7129f941fdc1

## 🛠️ Tecnologías Utilizadas

### Backend
* **Laravel 13.1.1**: Framework PHP con arquitectura API REST para gestionar la lógica de negocio de forma independiente del frontend.
* **PHP 8.4**: Lenguaje base del servidor.
* **Eloquent ORM**: Motor de base de datos que simplifica y optimiza la gestión de consultas complejas.
* **Laravel Sanctum**: Autenticación y protección de la API.
* **Laravel DomPDF**: Generación de documentos PDF (facturas, informes).
* **Protección integrada** frente a ataques comunes: inyecciones SQL, XSS y CSRF.

### Frontend
* **Vue.js 3.5.27**: Framework progresivo con arquitectura basada en componentes reactivos (Composition API).
* **Vite**: Empaquetador de nueva generación para optimización de builds y HMR (Hot Module Replacement).
* **Vue Router**: Gestión de rutas de la SPA.
* **Axios / Fetch**: Consumo de la API REST.
* **Vue I18n**: Internacionalización y soporte multiidioma.
* **SASS**: Preprocesador CSS para estilos modulares y reutilizables.
* **Vue Toastification**: Notificaciones emergentes.

### Base de Datos
* **MySQL 8.0**: Arquitectura relacional robusta con cumplimiento ACID.
* **Motor InnoDB**: Optimizado para transacciones concurrentes y alta disponibilidad.
* **Índices avanzados** y técnicas de replicación para un rendimiento eficiente y escalable.

### Entorno - Docker
* **Contenedor MySQL**: Almacenamiento de datos de la BD.
* **Contenedor PHP (8.4-fpm)**: Ejecución de la lógica de negocio Laravel.
* **Contenedor Nginx**: Servidor web y proxy inverso.

### Herramientas
* **Git / GitHub**: Control de versiones.
* **Trello**: Gestión de tareas (Scrum/Kanban).
* **Postman**: Pruebas de Endpoints.

## ⚙️ Instalación y Configuración

Sigue estos pasos para levantar el proyecto en local.

### Prerrequisitos
* Docker y Docker Compose
* Node.js (v20.19.0 o superior)
* NPM
* Git

### 1. Clonar el Repositorio
```bash
git clone https://github.com/tu-usuario/nombre-repo.git
cd nombre-repo
```

### 2. Configuración del Backend (API Laravel)
```bash
cd backend/src
composer install
cp .env.example .env
php artisan key:generate
```

**Variables de Entorno:** Edita el archivo `.env` en la carpeta `backend/src` con la siguiente estructura:
```env
APP_NAME=Nexus
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8080

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=Nexus
DB_USERNAME=nexus
DB_PASSWORD=nexus

SANCTUM_STATEFUL_DOMAINS=localhost:5173
```

### 3. Levantar los contenedores Docker
**Desde la carpeta `backend`:**
```bash
cd backend
docker compose up -d
```

Esto levantará automáticamente los tres servicios:
- **mysql** en el puerto `3306`
- **php-fpm** (Laravel)
- **nginx** en el puerto `8080`

**Ejecutar migraciones y seeders:**
```bash
docker exec -it <nombre_contenedor_php> php artisan migrate --seed
```

### 4. Configuración del Frontend (Cliente Vue)
**En una nueva terminal:**
```bash
cd frontend
npm install
```

**Para iniciar el cliente:**
```bash
npm run dev
```

El cliente estará disponible en `http://localhost:5173`

### 5. Base de Datos
El contenedor Docker inicializa la base de datos automáticamente con las credenciales definidas en `docker-compose.yml`. Si necesitas importar el esquema manualmente:
```bash
docker exec -i mysql_docker mysql -u nexus -pnexus Nexus < bd/init.sql
```

## 🗂️ Estructura del Proyecto
**El proyecto sigue una arquitectura MVC (Modelo-Vista-Controlador):**
```
nombre-repo/
├── backend/                    # API REST (Laravel + PHP)
│   ├── docker-compose.yml      # Orquestación de contenedores
│   ├── nginx/                  # Configuración del servidor web
│   ├── php/                    # Dockerfile PHP 8.4-fpm
│   └── src/                    # Código fuente Laravel
│       ├── app/
│       │   ├── Http/Controllers/  # Lógica de las rutas
│       │   └── Models/            # Modelos Eloquent
│       ├── routes/
│       │   └── api.php            # Definición de Endpoints
│       └── database/              # Migraciones y seeders
├── frontend/                   # SPA (Vue.js 3)
│   ├── src/
│   │   ├── components/         # Componentes reutilizables
│   │   ├── views/              # Vistas principales
│   │   ├── routes/             # Configuración Vue Router
│   │   ├── services/           # Llamadas a la API
│   │   └── composables/        # Lógica reutilizable (Composition API)
│   └── vite.config.js
└── bd/                         # Scripts SQL
    └── init.sql
```

## ✨ Funcionalidades Principales

* Registro e inicio de sesión de usuarios con autenticación via Sanctum.
* CRUD completo de productos digitales (iconos, librerías, texturas...).
* Sistema de carrito de compra y pasarela de pago.
* Filtrado y búsqueda de productos por categoría.
* Panel de administración protegido.
* Sistema de reseñas y valoraciones.
* Gestión de pedidos e incidencias.
* Generación de facturas en PDF.
* Soporte multiidioma (Vue I18n).

## ✒️ Autores
* **Inés Álvarez Calle**
* **Sergi Molina Barberà**
