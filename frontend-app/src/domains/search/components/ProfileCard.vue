<template>
    <div>
        <div>
            <img
                :src="thumbnailImage"
                :alt="`${props.profile.displayName}'s profile image`"
                class="w-full h-full max-h-64 block object-cover object-center"
            >
        </div>
        <div class="p-3 relative bg-background-light">
            <div class="mb-1">{{ displayNameAndAge }}</div>
            <div class="text-xs" v-if="hasLocationAndGender">{{ props.profile.gender }}, {{ location }}</div>
            <div class="mt-3">
                <ProfileInterests :interests="props.profile.details.interests" />
            </div>
            <RouterLink :to="`/requests/new/${props.profile.id}`" class="top-3 right-3 absolute">
                <HeartIcon class="w-6 h-6" />
            </RouterLink>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed, type PropType } from 'vue';
import type Profile from '@/common/models/Profile';
import { calculateAge } from '@/common/helpers/profile';
import ProfileInterests from '@/common/components/profile/ProfileInterests.vue';
import testImage from '@/assets/img/test-image.svg';
import { HeartIcon } from '@heroicons/vue/24/outline';
import { RouterLink } from 'vue-router';

const props = defineProps({
    profile: {
        type: Object as PropType<Profile>,
        required: true,
    },
});

const thumbnailImage = computed(() => {
    return props.profile?.images?.displayImage ?? testImage;
});

const displayNameAndAge = computed(() => {
    return `${props.profile.displayName}, ${calculateAge(props.profile.dateOfBirth)}`;
});

const location = computed(() => {
    return `${props.profile?.location?.place} - ${props.profile?.location?.province}`;
});

const gender = computed(() => {
    return props.profile.gender;
});

const hasLocationAndGender = computed(() => {
    if (!location.value || !gender.value) {
        return false;
    }

    return true;
});
</script>
