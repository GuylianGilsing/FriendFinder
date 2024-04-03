import type { RouteRecordRaw } from "vue-router";

const routes: readonly RouteRecordRaw[] = [
    {
        path: '/',
        name: 'home',
        component: () => import('@/domains/auth/views/Index.vue'),
    },
];

export default routes;
