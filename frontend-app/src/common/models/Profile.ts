import type ProfileDetails from "@/common/models/ProfileDetails";
import type ProfileImages from "@/common/models/ProfileImages";

export default interface Profile {
    id: string;
    displayName: string;
    dateOfBirth: Date;
    gender?: string;
    location?: {
        place: string;
        province: string;
        country: string;
    };
    details: ProfileDetails;
    images?: ProfileImages;
}
