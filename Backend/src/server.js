console.log("1. Iniciando script server.js...");

import 'dotenv/config';
import app from './app.js';

console.log("2. Importaciones completadas");

const PORT = process.env.PORT || 3000;

console.log(`3. Intentando escuchar en el puerto ${PORT}...`);

app.listen(PORT, '0.0.0.0', () => {
  console.log(`🚀 SERVIDOR LISTO y corriendo en el puerto ${PORT}`);
});

console.log("4. Fin del archivo server.js (El servidor debería seguir vivo)");