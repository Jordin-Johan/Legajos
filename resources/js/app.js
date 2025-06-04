import './bootstrap'
import NProgress from 'nprogress'
import 'nprogress/nprogress.css'

// Configura NProgress
NProgress.configure({ showSpinner: false, trickleSpeed: 200 })

// Para navegación Livewire SPA (wire:navigate)
window.addEventListener('flux:navigating', () => {
    NProgress.start()
})

window.addEventListener('flux:navigated', () => {
    NProgress.done()
})

// Para navegación tradicional (como login → dashboard)
window.addEventListener('beforeunload', () => {
    NProgress.start()
})
window.addEventListener('load', () => {
    NProgress.done()
})
