import { api } from '@/axios';
import Request from '@/common/request-wrapper';
import type Profile from "@/common/models/Profile";

export async function getProfile(profileID: string): Promise<Profile|null> {
    const [response, error] = await Request(api().get(`/api/profile-information/${profileID}`));

    if (error !== null) {
        return null;
    }

    const profile: Profile = {
        id: response.data.identity,
        displayName: response.data.displayName,
        dateOfBirth: new Date(response.data.dateOfBirth),
        details: {
            interests: response.data.interests,
        },
    };

    return profile;
}

export async function createProfile(
    displayName: string,
    dateOfBirth: string,
    interests: string[]
): Promise<boolean> {
    // Very ugly fix. In production, arrays are somehow passed as refs to this function...
    let fixedInterests = interests;

    if (!Array.isArray(interests) && typeof interests === 'object') {
        fixedInterests = (interests as any).value;
    }

    const profile = {
        displayName,
        dateOfBirth: new Date(dateOfBirth).toISOString(),
        interests: fixedInterests,
    };

    const [, error] = await Request(api().post(`/api/profile-information`, profile));

    if (error !== null) {
        console.warn(error);
        return false;
    }

    return true;
}

export async function deleteProfile(identity: string): Promise<boolean> {
    if (identity.trim().length === 0) {
        return false;
    }

    const [response, error] = await Request(api().delete(`/api/profile-information/${identity}`));

    if (error) {
        return false;
    }

    return response && response.status && response.status === 204;
}
