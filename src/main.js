import { createApp } from 'vue'
import './style.css'
import App from './App.vue'
import { createWebHashHistory, createRouter } from 'vue-router'

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

const app = createApp(App)
app.use(router)
app.mount('#mso-admin-app')
