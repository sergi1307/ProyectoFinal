import { createApp } from 'vue'
import App from './App.vue'
import router from '../src/routes/routes.js'
import i18n from './i18n.js'
import axios from 'axios';
import Toast from "vue-toastification"
import "vue-toastification/dist/index.css"

const baseURL = 'https://nexus-isaria.es/api';

axios.defaults.baseURL = baseURL;
axios.defaults.withCredentials = true;

const app = createApp(App)

app.use(router)
app.use(i18n)
app.use(Toast)

app.mount('#app')