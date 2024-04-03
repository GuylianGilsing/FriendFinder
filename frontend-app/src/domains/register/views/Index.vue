<template>
    <AppLayout :show-footer="false">
        <template #header-page-title>{{ $t('register.page.title') }}</template>
        <template  #main-content>
            <div class="flex flex-col gap-4">
                <div class="p-4 flex flex-col gap-4 bg-white">
                    <div class="flex flex-col gap-2">
                        <div class="text-base font-bold leading-none">{{ $t('register.fields.displayName.title') }}</div>
                        <input ref="inputField" class="w-full px-4 py-2 border border-gray-400" type="text" v-model="displayName" :placeholder="$t('register.fields.displayName.placeholder')" data-test="input-field">
                    </div>
                    <div class="flex flex-col gap-2">
                        <div v-if="!dateOfBirthErrorMessage" class="text-base font-bold leading-none">{{ $t('register.fields.dateOfBirth.title') }}</div>
                        <div v-else class="text-base text-danger font-bold leading-none">{{ dateOfBirthErrorMessage }}</div>
                        <input ref="inputField" class="w-full px-4 py-2 border border-gray-400" type="date" v-model="dateOfBirth" data-test="input-field">
                    </div>
                </div>
                <div class="p-4 bg-white">
                    <InterestsManager v-model="interests" />
                </div>
            </div>
        </template>
        <template #main-footer>
            <div class="p-4 flex flex-col gap-4">
                <div v-if="globalErrorMessage" class="text-base text-center text-danger font-bold leading-none">{{ globalErrorMessage }}</div>
                <div class="flex flex-row justify-center items-center gap-4">
                    <button @click="handleCancelRegistration" class="px-4 py-2 text-base text-primary-text rounded-lg border-2 border-primary disabled:opacity-50">
                        <template v-if="canceling">
                            <Loader class="w-4 h-4 p-2 leading-none" />
                        </template>
                        <template v-else>{{ $t('register.buttons.cancel') }}</template>
                    </button>
                    <button type="button" @click="handleCreateProfile" :disabled="!canCreateProfile && !creating" class="px-4 py-2 text-base text-secondary-text border-2 border-primary rounded-lg bg-primary disabled:opacity-50">
                        <template v-if="creating">
                            <Loader class="w-4 h-4 p-2 leading-none" />
                        </template>
                        <template v-else>{{ $t('register.buttons.create') }}</template>
                    </button>
                </div>
            </div>
        </template>
    </AppLayout>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import AppLayout from '@/common/layouts/AppLayout.vue';
import InterestsManager from '@/domains/profile-information/components/InterestsManager.vue';
import Loader from '@/common/components/Loader.vue';
import { computed } from 'vue';
import { calculateAge } from '@/common/helpers/profile';
import { useI18n } from 'vue-i18n';
import { createProfile } from '@/common/apis/profile-information';
import { useRouter } from 'vue-router';
import { deleteUser } from '@/common/apis/auth';
import { keycloak } from '@/auth';
import { onMounted } from 'vue';

const i18n = useI18n();
const router = useRouter();

const interests = ref<string[]>([]);
const displayName = ref<string>(keycloak.profile?.username ?? '');
const dateOfBirth = ref<string>('');

const dateOfBirthErrorMessage = ref<string | null>(null);
const globalErrorMessage = ref<string | null>(null);

const creating = ref<boolean>(false);
const canceling = ref<boolean>(false);

const canCreateProfile = computed<boolean>(() => {
    if (creating.value || canceling.value) {
        return false;
    }

    if (interests.value.length <= 0) {
        return false;
    }

    if (displayName.value.trim().length <= 0) {
        return false;
    }

    if (dateOfBirth.value.trim().length <= 0) {
        return false;
    }

    return true;
});

onMounted(async () => {
    const profileInformation = await keycloak.loadUserProfile();

    if (displayName.value.trim().length === 0) {
        displayName.value = profileInformation.firstName ?? '';
    }
});

async function handleCreateProfile(): Promise<void> {
    dateOfBirthErrorMessage.value = null;
    globalErrorMessage.value = null;

    if (!canCreateProfile.value) {
        return;
    }

    if (!dateOfBirthIsValid(dateOfBirth.value)) {
        dateOfBirthErrorMessage.value = i18n.t('register.errorMessages.userTooYoung');
        return;
    }

    creating.value = true;

    if (await createProfile(displayName.value, dateOfBirth.value, interests.value)) {
        router.push('/search');
        return;
    }

    globalErrorMessage.value = i18n.t('register.errorMessages.cannotCreateProfile');
    creating.value = false;
}

async function handleCancelRegistration(): Promise<void> {
    globalErrorMessage.value = null;
    canceling.value = true;

    if (await deleteUser(keycloak.subject ?? '')) {
        window.location.reload();
        return;
    }

    globalErrorMessage.value = i18n.t('register.errorMessages.cannotCancelRegistration');
    canceling.value = false;
}

function dateOfBirthIsValid(dateOfBirth: string): boolean {
    const minimumAge = 18;
    const date = new Date(dateOfBirth);

    if (!(date instanceof Date)) {
        return false;
    }

    if (calculateAge(date) < minimumAge) {
        return false;
    }

    return true;
}
</script>
