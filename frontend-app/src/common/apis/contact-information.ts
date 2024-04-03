import { api } from '@/axios';
import Request from '@/common/request-wrapper';
import type ContactInformationRequest from "@/domains/requests/models/ContactInformationRequest";
import type ContactInformationOptions from '@/domains/requests/models/ContactInformationOptions';
import { getProfile } from './profile-information';
import type ContactInformationRequestAnswer from '@/domains/requests/models/ContactInformationRequestAnswer';

export async function getOptions(): Promise<ContactInformationOptions | null> {
    const [response, error] = await Request(api().get('/api/contact-information/options'));

    if (error) {
        return null;
    }

    if (!response.data) {
        return null;
    }

    return response.data as ContactInformationOptions;
}

export async function getContactInformationRequests(): Promise<ContactInformationRequest[]> {
    const [response, error] = await Request(api().get('/api/contact-information'));

    if (error) {
        return [];
    }

    if (!response.data || !Array.isArray(response.data)) {
        return [];
    }

    return await addProfilesToRequests(response.data as ContactInformationRequest[]);
}

export async function getContactInformationRequest(id: string): Promise<ContactInformationRequest | null> {
    const [response, error] = await Request(api().get(`/api/contact-information/${id}`));

    if (error) {
        return null;
    }

    if (!response.data) {
        return null;
    }

    return await addProfileToRequest(response.data as ContactInformationRequest);
}

export async function createContactInformationRequest(
    profileID: string,
    message: string,
    socials: { [key: string]: string },
): Promise<ContactInformationRequest | null> {
    const data = {
        receiverProfileID: profileID,
        message,
        socials,
    };

    const [response, error] = await Request(api().post('/api/contact-information', data));

    if (error) {
        return null;
    }

    if (!response.data) {
        return null;
    }

    return response.data as ContactInformationRequest;
}

export async function answerContactInformationRequest(request: ContactInformationRequest, answer: 'accepted' | 'denied'): Promise<ContactInformationRequestAnswer | null> {
    const [response, error] = await Request(api().post(`/api/contact-information/${request.id}/answer`, { answer }));

    if (error) {
        return null;
    }

    if (!response.data) {
        return null;
    }

    return response.data as ContactInformationRequestAnswer;
}

async function addProfilesToRequests(requests: ContactInformationRequest[]): Promise<ContactInformationRequest[]> {
    const requestsWithProfile = [];

    for (const request of requests) {
        const requestWithProfile = await addProfileToRequest(request);

        if (!requestWithProfile) {
            continue;
        }

        requestsWithProfile.push(requestWithProfile);
    }

    return requestsWithProfile;
}

async function addProfileToRequest(request: ContactInformationRequest): Promise<ContactInformationRequest | null> {
    const profile = await getProfile(request.profileID);

    if (!profile) {
        return null;
    }

    request.profile = profile;

    return request;
}
