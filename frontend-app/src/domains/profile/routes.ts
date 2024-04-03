import type { RouteRecordRaw } from "vue-router";

const routes: readonly RouteRecordRaw[] = [
    {
        path: '/profile',
        name: 'profile',
        component: () => import('@/domains/profile/views/Index.vue'),
    },
];

export default routes;
