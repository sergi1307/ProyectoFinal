// Funciones de utilidad generales
const formatDate = (date) => {
    return new Date(date).toLocaleDateString();
};

module.exports = { formatDate };