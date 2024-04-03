import '@/assets/css/main.scss';

import { createApp } from 'vue';
import { createPinia } from 'pinia';
import { profileServiceKey } from '@/domains/search/dependency-injection-keys';
import { requestServiceKey } from '@/domains/requests/dependency-injection-keys';
import { keycloak } from '@/auth';

import ProfileService from '@/domains/search/services/ProfileService';

import App from './App.vue';
import router from '@/router';
import i18n from '@/i18n';

keycloak.init({ onLoad: 'check-sso', adapter: 'default', checkLoginIframe: false })
    .then(() => {
        const app = createApp(App);

        app.use(createPinia());
        app.use(router);
        app.use(i18n);

        app.provide(profileServiceKey, new ProfileService());

        app.mount(document.body);
    })
    .catch(() => { window.location.reload() });
