<template>
    <AppLayout>
        <template #header-left-icon>
            <RouterLink to="/search/filters" class="w-8 h-8">
                <AdjustmentsHorizontalIcon class="text-secondary-text" />
            </RouterLink>
        </template>
        <template #header-page-title>{{ $t('search.page.title') }}</template>
        <template #header-right-icon>
            <RouterLink to="/profile">
                <UserCircleIcon class="w-8 h-8 text-secondary-text" />
            </RouterLink>
        </template>
        <template #main-content>
            <div v-if="isLoadingData" id="app-layout-loader-wrapper" class="top-[50%] left-[50%] absolute">
                <Loader/>
                <div class="mt-2">{{ $t('search.data.loading') }}</div>
            </div>
            <template v-else>
                <div v-if="profiles.length === 0" class="p-2 text-center bg-background-light">{{ $t('search.data.none') }}</div>
                <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <ProfileCard v-for="profile in profiles" :profile="profile" />
                </div>
            </template>
        </template>
    </AppLayout>
</template>

<script setup lang="ts">
import { inject, onMounted, ref } from 'vue';
import { RouterLink } from 'vue-router';
import AppLayout from '@/common/layouts/AppLayout.vue';
import { UserCircleIcon, AdjustmentsHorizontalIcon } from '@heroicons/vue/24/outline';
import ProfileCard from '../components/ProfileCard.vue';
import { profileServiceKey } from '@/domains/search/dependency-injection-keys';
import ProfileService from '@/domains/search/services/ProfileService';
import type Profile from '@/common/models/Profile';
import Loader from '@/common/components/Loader.vue';
import { useFiltersStore } from '@/domains/search/stores/filter';

const profiles = ref<Profile[]>([]);
const isLoadingData = ref<boolean>(false);

const filtersStore = useFiltersStore();
const profileService: ProfileService|null = inject(profileServiceKey) ?? null;

async function fetchProfileData(): Promise<void> {
    isLoadingData.value = true;

    if (profileService === null) {
        profiles.value = [];
        isLoadingData.value = false;

        return;
    }

    if (filtersStore.getActiveFilters.value.length > 0) {
        profiles.value = await profileService.getFilteredProfiles(filtersStore.getActiveFilters.value);
        isLoadingData.value = false;

        return;
    }

    profiles.value = await profileService.getProfiles();

    isLoadingData.value = false;
}

onMounted(async () => {
    await fetchProfileData();
});
</script>
