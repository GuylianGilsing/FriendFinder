<template>
    <AppLayout>
        <template #header-left-icon>
            <RouterLink to="/search">
                <XMarkIcon class="w-8 h-8 text-secondary-text" />
            </RouterLink>
        </template>
        <template #header-page-title>{{ $t('requests.new.page.title') }}</template>
        <template #header-right-icon>
            <RouterLink to="/profile">
                <UserCircleIcon class="w-8 h-8 text-secondary-text" />
            </RouterLink>
        </template>
        <template #main-content>
            <div class="-m-4 flex flex-col flex-grow justify-between">
                <template v-if="profile">
                    <ProfileRequestCard :profile="profile" :new-request="true" />
                </template>
                <div class="p-4 flex flex-col gap-4 bg-white">
                    <div class="flex flex-col gap-2">
                        <div class="text-base font-bold leading-none">{{ $t('requests.new.message.label') }}</div>
                        <textarea
                            id="new-request-message"
                            class="w-full px-4 py-2 border border-gray-400"
                            rows="4"
                            :placeholder="$t('requests.new.message.placeholder')"
                            v-model="enteredMessage"
                        ></textarea>
                    </div>
                    <SocialMediaInputs v-model="enteredSocials" />
                    <div v-if="globalErrorMessage" class="text-base text-center text-danger font-bold leading-none">{{ globalErrorMessage }}</div>
                    <div class="flex flex-row justify-center">
                        <button type="button" @click="handleCreateRequest" :disabled="!canCreateRequest && !creating" class="px-4 py-2 text-base text-secondary-text border-2 border-primary rounded-lg bg-primary disabled:opacity-50">
                            <template v-if="creating">
                                <Loader class="w-4 h-4 p-2 leading-none" />
                            </template>
                            <template v-else>{{ $t('requests.new.create') }}</template>
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </AppLayout>
</template>

<script setup lang="ts">
import { ref, computed, onBeforeMount } from 'vue';
import { RouterLink, useRoute, useRouter } from 'vue-router';
import AppLayout from '@/common/layouts/AppLayout.vue';
import { XMarkIcon, UserCircleIcon } from '@heroicons/vue/24/outline';
import type Profile from '@/common/models/Profile';
import ProfileRequestCard from '@/domains/requests/components/ProfileRequestCard.vue';
import SocialMediaInputs from '@/domains/requests/components/SocialMediaInputs.vue';
import { getProfile } from '@/common/apis/profile-information';
import Loader from '@/common/components/Loader.vue';
import { createContactInformationRequest } from '@/common/apis/contact-information';

const route = useRoute();
const router = useRouter();

const profile = ref<Profile | null>(null);
const creating = ref<boolean>(false);
const globalErrorMessage = ref<string | null>(null);

const enteredSocials = ref<{ [key: string]: string }>({});
const enteredMessage = ref<string>('');

const canCreateRequest = computed(() => {
    if (Object.keys(enteredSocials.value).length === 0) {
        return false;
    }

    if (enteredMessage.value.trim().length === 0) {
        return false;
    }

    return true;
});

async function handleCreateRequest(): Promise<void> {
    if (creating.value || !profile.value || !canCreateRequest.value) {
        return;
    }

    globalErrorMessage.value = null;
    creating.value = true;

    const request = await createContactInformationRequest(
        profile.value.id,
        enteredMessage.value,
        enteredSocials.value
    );

    if (!request) {
        globalErrorMessage.value = 'Contact information requests couldn\'t be sent';
        creating.value = false;
        return;
    }

    router.push(`/requests/${request.id}`);
}

onBeforeMount(async () => {
    if (!route.params.profileID) {
        router.push('/requests');
        return;
    }

    profile.value = await getProfile(route.params.profileID as string);

    if (!profile.value) {
        router.push('/requests');
        return;
    }
});
</script>
