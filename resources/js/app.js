// filepath: c:\Users\saeed\EarthCoop\earthcoop\resources\js\app.js
import './bootstrap';
import { createApp } from 'vue';
import $ from 'jquery';

// تنظیم jQuery به صورت global
window.$ = $;
window.jQuery = $;

// ایجاد اپلیکیشن Vue
const app = createApp({
    mounted() {
        // بررسی بارگذاری jQuery
        console.log("jQuery version:", window.$ ? $.fn.jquery : "jQuery not loaded!");

        // در اینجا می‌توانید کدهای اختصاصی دیگر را قرار دهید.
        // مثال: مدیریت دکمه‌های آکاردئونی
        const accordionButtons = document.querySelectorAll('.accordion-button');
        accordionButtons.forEach(button => {
            button.addEventListener('click', () => {
                console.log('Accordion button clicked!');
            });
        });
    }
});

import ExampleComponent from './components/ExampleComponent.vue';
app.component('example-component', ExampleComponent);
app.mount('#app');

// Register service worker in production-like environments
if ('serviceWorker' in navigator) {
    // Defer registration to after load to avoid blocking
    window.addEventListener('load', () => {
        const swUrl = '/sw.js';
        navigator.serviceWorker.register(swUrl).then(reg => {
            console.log('Service worker registered:', reg);

            // Listen for updates
            reg.addEventListener('updatefound', () => {
                const newWorker = reg.installing;
                newWorker.addEventListener('statechange', () => {
                    if (newWorker.state === 'installed') {
                        if (navigator.serviceWorker.controller) {
                            // New update available
                            console.log('New content available; please refresh.');
                        } else {
                            console.log('Content cached for offline use.');
                        }
                    }
                });
            });
        }).catch(err => console.warn('SW registration failed:', err));
    });
}