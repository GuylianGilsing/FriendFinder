import type ContactInformationRequest from "../models/ContactInformationRequest";

export function getInitiator(request: ContactInformationRequest): 'you' | 'they' {
    if (request.identity === request.profileID) {
        return 'you';
    }

    return 'they';
}
