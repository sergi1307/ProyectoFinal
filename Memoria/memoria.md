# Índice
- [1. Introducción y Objetivos](#1-introducción-y-objetivos)
    - [1.1 Motivación](#11-motivación)
    - [1.2 Objetivos](#12-objetivos)
- [2. Análisis de Requisitos](#2-análisis-de-requisitos)
    - [2.1 Requisitos Funcionales](#21-requisitos-funcionales)
    - [2.2 Requisitos No Funcionales](#22-requisitos-no-funcionales)

# 1. Introducción y Objetivos

## 1.1 Motivación

El proyecto **Nexus** nace como respuesta a la creciente demanda de recursos digitales (iconos, plantillas, librerías de programación, etc.) por parte de creadores y desarrolladores. Actualmente, gran parte del mercado obliga al usuario a atarse a modelos de suscripción mensual para acceder a este tipo de contenido. La motivación principal de Nexus es ofrecer una alternativa más flexible y justa, democratizando el acceso a estos recursos mediante una plataforma de compra directa (o un modelo mixto), mejorando así la experiencia y la rentabilidad tanto para los creadores como para los consumidores.

## 1.2 Objetivos

- Objetivo General:
    - Diseñar y desarrollar un e-commerce tipo Marketplace completo y funcional para la distribución de contenido digital.
- Objetivos Específicos:
    - Implementar un sistema de registro, autenticación y gestión de roles.
    - Integrar una pasarela de pago segura para procesar las transacciones de los productos.
    - Desarrollar paneles de control (dashboards) intuitivos para la gestión del catálogo y la administración de la plataforma.
    - Crear un módulo eficiente para la gestión de incidencias y soporte al usuario.
    - Desarrollar un sistema de gestión de planes de suscripción y control de acceso al contenido.
    - Implementar verificación por correo electrónico.

# 2. Análisis de Requisitos

En esta sección se detallan las funcionalidades que debe cumplir la plataforma **Nexus**, así como las restricciones y características técnicas necesarias para garantizar un correcto funcionamiento y una buena experiencia de usuario.

## 2.1 Requisitos Funcionales

Los requisitos funcionales se han dividido en función de los diferentes roles de usuario que interactúan con el sistema.

### 2.1.1 Usuario No Registrado

- **RF-01**: El usuario podrá visualizar la página inicial y ver nuestros términos y condiciones.
- **RF-02**: El usuario podrá acceder al formulario de registro para crear una cuenta nueva.

### 2.1.2 Usuario Registrado

- **RF-03**: El sistema exigirá la verificación por correo electrónico tras el registro para activar la cuenta.
- **RF-04**: El usuario podrá añadir productos a un carrito de la compra y proceder al pago.
- **RF-05**: El usuario podrá suscribirse a un plan mensual o anual mediante la pasarela de pago para acceder a contenido premium.
- **RF-06**: El usuario tendrá acceso a una biblioteca personal o historial de compras donde podrá descargar los recursos adquiridos.
- **RF-07**: El usuario podrá abrir tickets de soporte o reportar incidencias relacionadas con los productos o la plataforma.
- **RF-08**: El usuario podrá ver los productos en tendencia, los destacados, así como explorarlos todo.
- **RF-09**: El usuario podrá añadir productos a favoritos.
- **RF-10**: El usuario podrá cambiar su contraseña y editar su perfil.

### 2.1.3 Administrador

- **RF-11**: El administrador tendrá acceso a un panel de control exclusivo.
- **RF-12**: El administrador podrá gestionar los roles de los usuarios así como bloquear cuentas.
- **RF-13**: El administrador podrá gestionar los productos digitales del catálogo.
- **RF-14**: El administrador podrá visualizar las estadísticas de ventas, suscripciones activas y métricas de la plataforma.
- **RF-15**: El administrador podrá gestionar los reembolsos.
- **RF-16**: El administrador podrá ver los logs de la aplicación.

## 2.2 Requisitos No Funcionales

- **RNF-01**: Las contraseñas de los usuarios deben almacenarse de forma encriptada en la base de datos.
- **RNF-02**: Las transacciones económicas se realizarán a través de una pasarela de pago segura, sin almacenar datos bancarios sensibles en los servidores propios.
- **RNF-03**: La interfaz de usuario debe adaptarse correctamente a dispositivos móviles, tablets y ordenadores de escritorio.
- **RNF-04**: El tiempo de carga de las páginas principales y del catálogo no debe superar los 3 segundos en condiciones de red óptimas.
- **RNF-05**: El sistema debe estar preparado para estar operativo 24/7 y gestionar la concurrencia de múltiples usuarios navegando y descargando recursos simultáneamente.

# 3. Stack Tecnólogico y Herramientas

En este capítulo se detallan las tecnologías, lenguajes de programación y herramientas de software que se han seleccionado para el desarrollo y despliegue de la plataforma Nexus, justificando la elección de cada una de ellas para garantizar un sistema escalable y robusto.

## 3.1 Frontend

- **HTML5**: Utilizado como lenguaje de marcado base para definir la estructura semántica de todas las vistas de la plataforma.
- **SCSS**: Empleado como preprocesador de CSS para estilizar la aplicación. Su uso ha permitido mantener un código de estilos modular y escalable mediante variables y anidamiento, facilitando un diseño coherente en todo el marketplace.
- **Vue.js**: Seleccionado como framework principal de JavaScript para construir la interfaz de usuario. Se ha elegido por su reactividad y su arquitectura basada en componentes, lo que permite crear una experiencia dinámica, rápida e interactiva para el usuario final.

## 3.2 Backend

- **Laravel (PHP)**: Seleccionado como framework de backend por su robustez y su arquitectura basada en el patrón MVC (Modelo-Vista-Controlador). Se ha utilizado para desarrollar de forma limpia y segura toa la lógica de negocio, la gestión de rutas, la autenticación de usuarios y la comunicación fluida con la base de datos.

## 3.3 Base de Datos

- **SQL (Base de Datos Relacional)**: Se ha optado por un sistema de gestión de bases de datos relacional para garantizar la integridad y coherencia de la información. Su uso permite relacionar de forma estructurada y eficiente los datos de las cuentas de usuario, el catálogo de productos digitales, los roles y el historial de compras.

## 3.4 Integraciones y Servicios de Terceros

- **Pasarela de Pago (PayPal)**: 