import mysql from 'mysql2/promise';
import pool from '../config/db.js';

export const crearUsuario = async (username, email, password) => {
    try {
        const query = 'INSERT INTO users (username, email, password) VALUES (?, ?, ?)';
        const [resultado] = await pool.query(query, [username, email, password]);
        return resultado;
        
    } catch (error) {
        console.error('Error en consulta: ', error);
        throw error;
    }
}

export const buscarUsuario = async (email) => {
    try {
        const query = 'SELECT * FROM users WHERE email = ?';
        const [usuarios] = await pool.query(query, [email]);
        return usuarios[0];

    } catch (error) {
        console.error('Error en consulta: ', error);
        throw error;
    }
}