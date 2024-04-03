<template>
    <AppLayout>
        <template #header-page-title>{{ $t('profile.page.title') }}</template>
        <template #main-content>
            <div class="p-2 bg-background-light">
                <div class="flex flex-col items-center gap-2">
                    <div v-if="deleteProfileErrorMessage" class="text-base text-center text-danger font-bold leading-none">{{ deleteProfileErrorMessage }}</div>
                    <button @click="deleteProfileWithInformation" type="submit" class="px-4 py-2 text-secondary-text border-2 border-primary rounded-lg bg-primary">
                        <template v-if="!isDeleting">Delete profile</template>
                        <template v-else>
                            <Loader class="w-4 h-4 leading-none" />
                        </template>
                    </button>
                </div>
            </div>
        </template>
    </AppLayout>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import AppLayout from '@/common/layouts/AppLayout.vue';
import Loader from '@/common/components/Loader.vue';
import { deleteProfile } from '@/common/apis/profile-information';
import { deleteUser } from '@/common/apis/auth';
import { keycloak } from '@/auth';
import { useI18n } from 'vue-i18n';

const i18n = useI18n();

const isDeleting = ref<boolean>(false);
const deleteProfileErrorMessage = ref<string | null>(null);

async function deleteProfileWithInformation() {
    if (isDeleting.value) {
        return;
    }

    deleteProfileErrorMessage.value = null;
    isDeleting.value = true;

    if (!await deleteProfile(keycloak.subject ?? '')) {
        isDeleting.value = false;
        deleteProfileErrorMessage.value = i18n.t('profile.delete.failed');
        return;
    }

    if (!await deleteUser(keycloak.subject ?? '')) {
        isDeleting.value = false;
        deleteProfileErrorMessage.value = i18n.t('profile.delete.failed');
        return;
    }

    window.location.reload();
}
</script>
