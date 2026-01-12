const express = require('express');
const cors = require('cors');
const helmet = require('helmet');
const routes = require('./routes'); // Importamos el índice de rutas

const app = express();

// Middlewares globales
app.use(express.json());
app.use(cors());
app.use(helmet());

// Rutas base
app.use('/api/v1', routes);

// Manejo de errores 404
app.use((req, res, next) => {
    res.status(404).json({ message: "Endpoint no encontrado" });
});

module.exports = app;