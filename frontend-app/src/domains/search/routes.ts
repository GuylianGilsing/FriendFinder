import type { RouteRecordRaw } from "vue-router";

const routes: readonly RouteRecordRaw[] = [
    {
        path: '/search',
        name: 'search',
        component: () => import('@/domains/search/views/Index.vue'),
    },
    {
        path: '/search/filters',
        name: 'search-filters',
        component: () => import('@/domains/search/views/Filters.vue'),
    },
];

export default routes;
