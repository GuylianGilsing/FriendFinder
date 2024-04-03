<template>
    <AppLayout>
        <template #header-page-title>{{ $t('requests.page.title') }}</template>
        <template #header-right-icon>
            <RouterLink to="/profile">
                <UserCircleIcon class="w-8 h-8 text-secondary-text" />
            </RouterLink>
        </template>
        <template #main-content>
            <div class="-m-4">
                <div v-if="isLoadingData" id="app-layout-loader-wrapper" class="top-[50%] left-[50%] absolute">
                    <Loader/>
                    <div class="mt-2">{{ $t('search.data.loading') }}</div>
                </div>
                <template v-else>
                    <template v-for="request of contactInformationRequests">
                        <template v-if="request.profile !== null">
                            <ProfileCard :profile="request.profile" :request="request" />
                        </template>
                    </template>
                </template>
            </div>
        </template>
    </AppLayout>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { RouterLink } from 'vue-router';
import AppLayout from '@/common/layouts/AppLayout.vue';
import { UserCircleIcon } from '@heroicons/vue/24/outline';
import type ContactInformationRequest from '@/domains/requests/models/ContactInformationRequest';
import Loader from '@/common/components/Loader.vue';
import ProfileCard from '@/domains/requests/components/ProfileCard.vue';
import { getContactInformationRequests } from '@/common/apis/contact-information';

const contactInformationRequests = ref<ContactInformationRequest[]>([]);
const isLoadingData = ref<boolean>(false);

async function fetchRequests(): Promise<void> {
    isLoadingData.value = true;

    contactInformationRequests.value = await getContactInformationRequests();

    isLoadingData.value = false;
}

onMounted(async () => {
    fetchRequests();
});
</script>
