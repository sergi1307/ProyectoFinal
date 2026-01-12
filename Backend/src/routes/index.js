const express = require('express');
const router = express.Router();

// Importar archivos de rutas específicos
// const userRoutes = require('./user.routes');

// Ejemplo de ruta de estado
const { getHealth } = require('../controllers/health.controller');

router.get('/health', getHealth);
// router.use('/users', userRoutes);

module.exports = router;