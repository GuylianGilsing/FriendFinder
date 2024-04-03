import type Profile from "@/common/models/Profile";
import TestImage from '@/assets/img/test-image.svg';
import type SearchFilter from "@/domains/search/models/SearchFilter";
import { searchProfiles } from "@/common/apis/search";

export default class ProfileService {
    public async getProfiles(): Promise<Profile[]> {
        return await searchProfiles();
    }

    public getFilteredProfiles(filters: SearchFilter[]): Promise<Profile[]> {
        filters; // Exists to stop ESLint whining about an unused variable.

        // TODO: Implement API call...

        return new Promise<Profile[]>((resolve) => {
            setTimeout(() => { resolve([]); }, 1000);
        });
    }

    // TODO: Change ID so that it represents the proper backend ID (still not sure about UUIDs or integer based IDs)
    public getByID(id: any): Promise<Profile | null> {
        id; // Exists to stop ESLint whining about an unused variable.

        // TODO: Implement API call...

        return new Promise<Profile>((resolve) => {
            resolve({
                id: 'id-123',
                displayName: 'Emily',
                dateOfBirth: new Date('2000-02-01'),
                gender: 'female',
                location: {
                    place: 'Eindhoven',
                    province: 'Noord-Brabant',
                    country: 'Nederland',
                },
                details: {
                    interests: ['gaming', 'tennis', 'reading'],
                    questions: [
                        { title: 'Question 1', answer: 'Lorem ipsum dolor sit amet.' },
                        { title: 'Question 2', answer: 'Lorem ipsum dolor sit amet.' },
                        { title: 'Question 3', answer: 'Lorem ipsum dolor sit amet.' },
                    ],
                },
                images: {
                    displayImage: TestImage,
                    uploadedImages: [TestImage, TestImage, TestImage, TestImage],
                },
            });
        });
    }
}
