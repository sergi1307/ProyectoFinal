const healthService = require('../services/health.service');

const getHealth = (req, res) => {
    try {
        const status = healthService.checkSystemStatus();
        res.status(200).json(status);
    } catch (error) {
        res.status(500).json({ message: error.message });
    }
};

module.exports = { getHealth };