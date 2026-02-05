// filepath: c:\Users\saeed\EarthCoop\earthcoop\resources\js\app.js
import "./bootstrap";
import { createApp } from "vue";
import $ from "jquery";

// تنظیم jQuery به صورت global
window.$ = $;
window.jQuery = $;

// ایجاد اپلیکیشن Vue
const app = createApp({
  mounted() {
    // در اینجا می‌توانید کدهای اختصاصی دیگر را قرار دهید.
    // مثال: مدیریت دکمه‌های آکاردئونی
    const accordionButtons = document.querySelectorAll(".accordion-button");
    accordionButtons.forEach((button) => {
      button.addEventListener("click", () => {});
    });
  },
});

import ExampleComponent from "./components/ExampleComponent.vue";
app.component("example-component", ExampleComponent);
app.mount("#app");

// Register service worker in production-like environments
if ("serviceWorker" in navigator) {
  // Defer registration to after load to avoid blocking
  window.addEventListener("load", () => {
    const swUrl = "/sw.js";
    navigator.serviceWorker
      .register(swUrl)
      .then((reg) => {
        // Listen for updates
        reg.addEventListener("updatefound", () => {
          const newWorker = reg.installing;
          newWorker.addEventListener("statechange", () => {
            if (newWorker.state === "installed") {
              if (navigator.serviceWorker.controller) {
                // New update available
              } else {
              }
            }
          });
        });
      })
      .catch((err) => {});
  });
}
