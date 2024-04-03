import type { RouteRecordRaw } from "vue-router";

const routes: readonly RouteRecordRaw[] = [
    {
        path: '/register',
        name: 'register',
        component: () => import('@/domains/register/views/Index.vue'),
    },
];

export default routes;
