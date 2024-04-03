import type Profile from "@/common/models/Profile";
import type ContactInformationRequestAnswer from "./ContactInformationRequestAnswer";

export default interface ContactInformationRequest {
    id: string,
    identity: string;
    profileID: string;
    profile: Profile|null;
    message?: string;
    messageSummary?: string;
    socials: { [key: string]: string; }[] | null;
    answer: ContactInformationRequestAnswer | null;
}
