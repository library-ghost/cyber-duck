require('./bootstrap');

import Alpine from 'alpinejs';
import moment from 'moment'
import { createApp } from 'vue';
import SalesForm from './components/SalesForm.vue';

window.Alpine = Alpine;

Alpine.start();

const app = createApp({});

app.config.globalProperties.$filters = {
    formatDate(date) {
        return moment(String(date)).format('YYYY-MM-DD hh:mm')
    },
}

app.component('SalesForm', SalesForm);

app.mount("#app");
