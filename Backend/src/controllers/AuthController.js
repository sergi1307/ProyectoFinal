import bcrypt from "bcryptjs";
import { buscarUsuario, crearUsuario } from '../models/Auth.js';
import dotenv from "dotenv";
import jwt from "jsonwebtoken";

dotenv.config();
const claveSecreta = process.env.JWT_SECRET;

export const registro = async (req, res) => {
    try {
        const { name, email, password } = req.body;
        if(!name || !email || !password){
            return res.status(400).json({ error: 'Faltan datos' });
        }

        const salt = bcrypt.genSaltSync(10);
        const hash = bcrypt.hashSync(password, salt);
        const usuario = await crearUsuario(name, email, hash);

        res.status(201).json({ mensaje: 'Usuario registrado con éxito' });
    } catch (error) {
        console.error('Error en el registro: ', error);
        throw error;
    }
}

export const login = async (req, res) => {
    try {
        const { email, password } = req.body;
        const user = await buscarUsuario(email);
        if (!user) {
            return res.status(404).json({ error: 'Usuario no encontrado' });
        }

        const esCorrecta = await bcrypt.compare(password, user.password);
        if (!esCorrecta) {
            return res.status(401).json({ error: 'Contraseña incorrecta' });
        }

        const token = jwt.sign(
            { id: user.id, email: user.email },
            claveSecreta,
            { expiresIn: '8h' }
        );

        

        res.status(200).json({
            mensaje: 'Login exitoso',
            token: token,
            usuario: {
                id: user.id,
                name: user.name,
                email: user.email
            }
        });

    } catch (error) {
        console.error('Error en el login: ', error);
        throw error;
    }
};