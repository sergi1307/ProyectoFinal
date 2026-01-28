import express from 'express';
import { login, registro } from '../controllers/AuthController.js';

const router = express.Router();

router.post('/registro', registro);

router.post('/login', login);

export default router;