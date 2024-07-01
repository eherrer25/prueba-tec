import { createApp, markRaw } from 'vue'
import { createPinia } from 'pinia'
import  piniaPluginPersistedstate  from 'pinia-plugin-persistedstate'
import 'bootstrap/dist/css/bootstrap.min.css'
import '@fortawesome/fontawesome-free/css/all.min.css'
import 'bootstrap/dist/js/bootstrap.bundle'
import './style.css'
import App from './App.vue'
import router from './router'
import axios from 'axios'


window.axios = axios
// window.axios.defaults.baseURL = 'http://localhost'
window.axios.defaults.headers.common['Accept'] = 'application/json'
window.axios.defaults.headers.common['Content-type'] = 'application/json'
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
window.axios.defaults.withCredentials = true

// window.axios.interceptors.request.use(function(config) {
//   config.headers.common = {
//     Authorization: `Bearer ${localStorage.getItem("authToken")}`,
//     "Content-Type": "application/json",
//     Accept: "application/json"
//   };

//   return config;
// });

const pinia = createPinia()
pinia.use(({store}) => {
    store.router = markRaw(router)
})
pinia.use(piniaPluginPersistedstate)

const app = createApp(App)

app.use(pinia)
app.use(router)

app.mount('#app')
