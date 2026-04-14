import { createApp } from 'vue'
import App from './App.vue'
import router from '../src/routes/routes.js'
import i18n from './i18n.js'
import axios from 'axios'
import Toast from "vue-toastification"
import "vue-toastification/dist/index.css"

axios.defaults.baseURL = 'http://127.0.0.1:8000' 
axios.defaults.withCredentials = true 

const app = createApp(App)

app.use(router)
app.use(i18n)
app.use(Toast)

app.mount('#app')