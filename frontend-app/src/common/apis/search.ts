import { api } from '@/axios';
import Request from '@/common/request-wrapper';
import type Profile from "@/common/models/Profile";

export async function searchProfiles(): Promise<Profile[]> {
    const [response, error] = await Request(api().get('/api/search/profile'));

    if (error) {
        return [];
    }

    if (!response.data) {
        return [];
    }

    const profiles: Profile[] = [];

    for (const profile of response.data) {
        profiles.push({
            id: profile.identity,
            displayName: profile.displayName,
            dateOfBirth: new Date(profile.dateOfBirth),
            details: {
                interests: profile.interests,
            },
        });
    }

    return profiles;
}
