require('./bootstrap');

import Alpine from 'alpinejs';
import { createApp } from 'vue';
import SalesForm from './components/SalesForm.vue';

window.Alpine = Alpine;

Alpine.start();

const app = createApp({});

app.component('SalesForm', SalesForm);

app.mount("#app");
