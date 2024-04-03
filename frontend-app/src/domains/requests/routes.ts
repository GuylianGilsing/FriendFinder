import type { RouteRecordRaw } from "vue-router";

const routes: readonly RouteRecordRaw[] = [
    {
        path: '/requests',
        name: 'requests',
        component: () => import('@/domains/requests/views/Index.vue'),
    },
    {
        path: '/requests/:requestID',
        name: 'view-request',
        component: () => import('@/domains/requests/views/Detail.vue'),
    },
    {
        path: '/requests/new/:profileID',
        name: 'create-new-request',
        component: () => import('@/domains/requests/views/Create.vue'),
    },
];

export default routes;
