import { createApp } from 'vue'
import './style.css'
import App from './App.vue'
import { createWebHashHistory, createRouter } from 'vue-router'
import { createPinia } from 'pinia'
import StoreSettings from './components/StoreSettings.vue'
import HubSettings from './components/HubSettings.vue'
import TabNavigation from './components/TabNavigation.vue'

const routes = [
    {
        path: '/', components: { default: StoreSettings, tab: TabNavigation },
    },
    {
        path: '/hub', components: { default: HubSettings, tab: TabNavigation },
    },
]

const router = createRouter({
    history: createWebHashHistory(),
    routes,
})
const pinia = createPinia()
const app = createApp(App)
app.use(router)
app.use(pinia)
app.mount('#mso-admin-app')
