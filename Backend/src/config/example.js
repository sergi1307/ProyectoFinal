// Configuraciones centralizadas
const config = {
    env: process.env.NODE_ENV || 'dev',
    isProd: process.env.NODE_ENV === 'production'
}

module.exports = config;