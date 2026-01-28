import express from 'express';
import 'dotenv/config'; 

import authRoutes from './routes/routes.js'; 

const app = express();
app.use(express.json());

app.use('/api', authRoutes);

export default app;

const PORT = process.env.PORT || 3000;
app.listen(PORT, () => {
    console.log(`Servidor corriendo en puerto ${PORT}`);
});