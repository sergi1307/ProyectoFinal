import { createApp } from 'vue'
import App from './App.vue'
import router from '../src/routes/routes.js'
import { createI18n } from 'vue-i18n'

const messages = {
  esp: {
    nb: { login: 'Iniciar Sesión', reg: 'Registrarse', out: 'Cerrar Sesión', adm: 'Eres Administrador', usr: 'Eres Usuario' }
  },
  val: {
    nb: { login: 'Iniciar Sessió', reg: 'Registrar-se', out: 'Tancar Sessió', adm: 'Ets Administrador', usr: 'Ets Usuari' }
  },
  eng: {
    nb: { login: 'Login', reg: 'Register', out: 'Logout', adm: 'You are Admin', usr: 'You are User' }
  }
}

const i18n = createI18n({
  legacy: false, 
  locale: localStorage.getItem('idioma') || 'esp',
  messages
})

const app = createApp(App)

app.use(router)
app.use(i18n)

app.mount('#app')