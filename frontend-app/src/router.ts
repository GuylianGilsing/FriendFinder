import { createRouter, createWebHistory } from 'vue-router';
import { keycloak } from '@/auth';

import authRoutes from '@/domains/auth/routes';
import searchRoutes from '@/domains/search/routes';
import requestsRoutes from '@/domains/requests/routes';
import registerRoutes from '@/domains/register/routes';
import profileRoutes from '@/domains/profile/routes';
import { getProfile } from './common/apis/profile-information';

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        ...authRoutes,
        ...searchRoutes,
        ...requestsRoutes,
        ...registerRoutes,
        ...profileRoutes,
    ],
});

// Keycloak authentication
router.beforeEach(async (to) => {
    const whitelistedPaths = ['/'];
    const currentPathIsWhitelisted = whitelistedPaths.includes(to.path);

    // Keep unauthenticated users on the whitelisted pages
    if (!keycloak.authenticated && currentPathIsWhitelisted) {
        return true;
    }

    // Keep authenticated users on non-whitelisted pages
    if (keycloak.authenticated && !currentPathIsWhitelisted) {
        return true;
    }

    // Redirect authenticated users away from whitelisted pages
    if (keycloak.authenticated && currentPathIsWhitelisted) {
        return { path: '/search' };
    }

    // Keep unauthenticated users away from non-whitelisted pages
    return { path: '/' };
});

// Require profile registration
router.beforeEach(async (to) => {
    if (!keycloak.authenticated) {
        return true;
    }

    const profile = await getProfile(keycloak.subject ?? '');

    // Route users with no profile to the registration page
    if (profile === null && to.path !== '/register') {
        return { path: '/register' };
    }

    // Route users with a profile away from the register page
    if (profile !== null && to.path === '/register') {
        return { path: '/search' };
    }

    // Allow users with no profile on the register page
    return true;
});

export default router;
