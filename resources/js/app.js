import './bootstrap'

import { createApp } from 'vue'
import { createRouter, createWebHistory } from 'vue-router'
import { i18nVue } from 'laravel-vue-i18n'

import App from './App.vue'
import Home from './views/Home.vue'


const routes = [
    {
        name: 'home',
        path: '/',
        component: Home,
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

const app = createApp(App)
app.use(router)
app.use(i18nVue, {
    lang: 'en',
    resolve: (lang) => import(`../lang/${lang}.json`),
})
app.mount('#app')
