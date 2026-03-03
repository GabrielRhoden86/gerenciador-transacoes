import { registerPlugins } from '@/plugins'
import App from './App.vue'
import { createApp } from 'vue'
import { createPinia } from 'pinia' // Adicione isso

// Styles
import 'unfonts.css'
import 'vuetify/styles'

const app = createApp(App)
const pinia = createPinia() // Cria a instância

app.use(pinia)        // INSTALA O PINIA AQUI MANUALMENTE
registerPlugins(app)  // Depois instala os outros plugins

app.mount('#app')
