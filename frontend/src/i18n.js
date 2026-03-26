import { createI18n } from 'vue-i18n';

const messages = {
  esp: {
    navbar: { login: 'Iniciar Sesión', register: 'Registrarse', logout: 'Cerrar Sesión', admin: 'Eres Administrador', user: 'Eres Usuario' }
  },
  val: {
    navbar: { login: 'Iniciar Sessió', register: 'Registrar-se', logout: 'Tancar Sessió', admin: 'Eres Administrador', user: 'Eres Usuari' }
  },
  eng: {
    navbar: { login: 'Login', register: 'Register', logout: 'Logout', admin: 'You are Admin', user: 'You are User' }
  }
};

const i18n = createI18n({
  legacy: false,
  locale: localStorage.getItem('idioma') || 'esp',
  fallbackLocale: 'esp',
  messages,
});

export default i18n;