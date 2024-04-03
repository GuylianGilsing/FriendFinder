export function routeIsActive(routePath: string): boolean {
    return window.location.pathname.startsWith(routePath);
}
