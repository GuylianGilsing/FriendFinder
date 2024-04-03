<template>
    <AppLayout>
        <template #header-left-icon>
            <RouterLink to="/requests">
                <ArrowLeftIcon class="w-8 h-8 text-secondary-text" />
            </RouterLink>
        </template>
        <template #header-page-title>{{ $t('requests.detail.page.title') }}</template>
        <template #header-right-icon>
            <RouterLink to="/profile">
                <UserCircleIcon class="w-8 h-8 text-secondary-text" />
            </RouterLink>
        </template>
        <template #main-content>
            <div v-if="loadingRequest" class="-m-4 flex flex-col flex-grow justify-center items-center">
                <Loader />
            </div>
            <div v-else class="-m-4 relative flex flex-col flex-grow gap-4">
                <template v-if="profile">
                    <ProfileRequestCard :profile="profile" :new-request="true" />
                </template>
                <div v-if="contactInformationRequest" class="px-4 flex flex-col gap-4">
                    <div class="p-4 flex flex-col gap-2 bg-white">
                        <div class="text-base">{{ contactInformationRequest.message }}</div>
                    </div>
                    <div v-if="acceptedMessage" class="py-2 px-4 text-center font-semibold bg-white">{{ acceptedMessage }}</div>
                    <div v-if="canDisplaySocials" class="p-4 flex flex-col gap-2 bg-white">
                        <div class="font-semibold">Contact information</div>
                        <div class="grid grid-cols-1 sm:grid-cols-2">
                            <div v-for="(handle, platform) in contactInformationRequest.socials">
                                <span class="font-semibold">{{ $t(`requests.socials.${platform}.label`) }}</span>: {{ handle }}
                            </div>
                        </div>
                    </div>
                </div>
                <div v-if="canAnswer" class="left-[50%] bottom-4 absolute flex flex-col gap-2 -translate-x-2/4">
                    <div v-if="globalErrorMessage" class="text-base text-center text-danger font-bold leading-none">{{ globalErrorMessage }}</div>
                    <div class="flex flex-row justify-center gap-4">
                        <div class="p-2 rounded-full bg-white">
                            <template v-if="givingAnswer === 'accepted'">
                                <Loader />
                            </template>
                            <template v-else>
                                <CheckCircleIcon class="w-6 h-6" @click="giveAnswer('accepted')" />
                            </template>
                        </div>
                        <div class="p-2 rounded-full bg-white">
                            <template v-if="givingAnswer === 'denied'">
                                <Loader />
                            </template>
                            <template v-else>
                                <XCircleIcon class="w-6 h-6" @click="giveAnswer('denied')" />
                            </template>
                        </div>
                        <div class="p-2 rounded-full bg-white">
                            <FlagIcon class="w-6 h-6" />
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </AppLayout>
</template>

<script setup lang="ts">
import { ref, onBeforeMount, computed } from 'vue';
import { RouterLink, useRoute, useRouter } from 'vue-router';
import AppLayout from '@/common/layouts/AppLayout.vue';
import { ArrowLeftIcon, UserCircleIcon, CheckCircleIcon, XCircleIcon, FlagIcon } from '@heroicons/vue/24/outline';
import type Profile from '@/common/models/Profile';
import ProfileRequestCard from '@/domains/requests/components/ProfileRequestCard.vue';
import Loader from '@/common/components/Loader.vue';
import type ContactInformationRequest from '../models/ContactInformationRequest';
import { getContactInformationRequest, answerContactInformationRequest } from '@/common/apis/contact-information';
import { capitalize } from '@/common/helpers/string';
import { getInitiator } from '@/domains/requests/utils/pronouns';

const route = useRoute();
const router = useRouter();

const loadingRequest = ref<boolean>(false);
const givingAnswer = ref<string | null>(null);
const globalErrorMessage = ref<string | null>(null);

const profile = ref<Profile | null>(null);
const contactInformationRequest = ref<ContactInformationRequest | null>(null);

const acceptedMessage = computed(() => {
    if (!contactInformationRequest.value || !contactInformationRequest.value.answer) {
        return null;
    }

    const initiator = getInitiator(contactInformationRequest.value)
    const answer = contactInformationRequest.value.answer.answer;

    return `${capitalize(initiator)} have ${ answer } ${ initiator === 'you' ? 'their' : 'your' } request.`;
});

const canAnswer = computed(() => {
    if (!contactInformationRequest.value) {
        return false;
    }

    if (contactInformationRequest.value.answer) {
        return false;
    }

    if (contactInformationRequest.value.identity !== contactInformationRequest.value.profileID) {
        return false;
    }

    return true;
});

const canDisplaySocials = computed(() => {
    if (!contactInformationRequest.value) {
        return false;
    }

    if (!contactInformationRequest.value.socials) {
        return false;
    }

    if (!contactInformationRequest.value.answer || contactInformationRequest.value.answer.answer === 'denied') {
        return false;
    }

    return true;
});

async function giveAnswer(answer: 'accepted' | 'denied') : Promise<void> {
    if (givingAnswer.value || !contactInformationRequest.value) {
        return;
    }

    givingAnswer.value = answer;
    globalErrorMessage.value = null;

    const givenAnswer = await answerContactInformationRequest(contactInformationRequest.value, answer);

    if (givenAnswer === null) {
        globalErrorMessage.value = 'Can\'t answer contact information request';
        return;
    }

    contactInformationRequest.value.answer = givenAnswer;

    givingAnswer.value = null;
}

onBeforeMount(async () => {
    loadingRequest.value = true;

    if (!route.params.requestID) {
        router.push('/requests');
        return;
    }

    contactInformationRequest.value = await getContactInformationRequest(route.params.requestID as string);

    if (!contactInformationRequest.value) {
        router.push('/requests');
        return;
    }

    profile.value = contactInformationRequest.value.profile;

    if (!profile.value) {
        router.push('/requests');
        return;
    }

    loadingRequest.value = false;
});
</script>
