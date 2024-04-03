import axios from 'axios';
import { keycloak } from './auth';

export const api = () => axios.create({
    baseURL: import.meta.env.VITE_GATEWAY_URL,
    headers: {
        'Authorization': `Bearer ${keycloak.token}`,
    },
    withCredentials: true,
});

export const auth = () => axios.create({
    baseURL: import.meta.env.VITE_KEYCLOAK_URL,
    headers: {
        'Authorization': `Bearer ${keycloak.token}`,
    },
    withCredentials: true,
});
