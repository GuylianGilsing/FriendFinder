import { createI18n } from "vue-i18n";

import searchTranslations from '@/domains/search/translations';
import requestsTranslations from '@/domains/requests/translations';
import registerTranslations from '@/domains/register/translations';
import profileInformationTranslations from '@/domains/profile-information/translations';
import profileTranslations from '@/domains/profile/translations';

const i18n = createI18n({
    locale: 'en',
    fallbackLocale: 'en',
    messages: {
        en: {
            ...searchTranslations.en,
            ...requestsTranslations.en,
            ...registerTranslations.en,
            ...profileInformationTranslations.en,
            ...profileTranslations.en,
        },
    },
    legacy: false,
});

export default i18n;
