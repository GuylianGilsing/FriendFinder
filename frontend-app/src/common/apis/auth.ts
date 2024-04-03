import { auth } from '@/axios';
import Request from '@/common/request-wrapper';

export async function deleteUser(id: string): Promise<boolean> {
    const [, error] = await Request(auth().delete(`/admin/realms/${import.meta.env.VITE_KEYCLOAK_REALM}/users/${id}`));

    if (error !== null) {
        console.warn(error);
        return false;
    }

    return true;
}
