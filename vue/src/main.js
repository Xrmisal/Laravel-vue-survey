import { createApp } from 'vue'
import store from './store'
import router from './router'
import './style.css'
import App from './App.vue'

createApp(App)
    .use(store)
    .use(router)
    .mount('#app')

