import axios from 'axios';

const baseURL = 'https://nexus-isaria.es/api';

console.log('[Nexus API] Base URL loaded:', baseURL);

// Crear una instancia de Axios con la URL base
const api = axios.create({
  baseURL: baseURL,
  withCredentials: true,
  headers: {
    'Accept': 'application/json',
  }
});

// Interceptor de peticiones para añadir el Token Bearer de forma automática
api.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('token');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

// Interceptor de respuestas para manejar errores comunes (como 401 Unauthorized)
api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response && error.response.status === 401) {
      // Por ejemplo, redirigir al login si el token expira o es inválido
      localStorage.removeItem('token');
      localStorage.removeItem('user');
      window.location.href = '/login';
    }
    return Promise.reject(error);
  }
);

export default api;
