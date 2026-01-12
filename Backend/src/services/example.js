const checkSystemStatus = () => {
    // Aquí podría ir lógica compleja: verificar conexión a DB, memoria, etc.
    return {
        status: 'OK',
        uptime: process.uptime(),
        timestamp: new Date()
    };
};

module.exports = { checkSystemStatus };