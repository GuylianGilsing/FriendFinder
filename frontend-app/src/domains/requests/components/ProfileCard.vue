<template>
    <RouterLink :to="`/requests/${props.request.id}`" class="p-4 flex flex-row items-center gap-4 border-b border-divider bg-background-light">
        <img
            :src="profileImage"
            :alt="`${props.profile.displayName}'s profile image`"
            class="w-12 h-12 object-cover object-center"
        >
        <div class="leading-none text-sm">
            <div class="font-semibold">{{ props.profile.displayName }}</div>
            <div class="mt-2">{{ props.request.messageSummary }}</div>
        </div>
    </RouterLink>
</template>

<script setup lang="ts">
import { computed, type PropType } from 'vue';
import type Profile from '@/common/models/Profile';
import type ContactInformationRequest from '@/domains/requests/models/ContactInformationRequest';
import testImage from '@/assets/img/test-image.svg';

const props = defineProps({
    profile: {
        type: Object as PropType<Profile>,
        required: true,
    },
    request: {
        type: Object as PropType<ContactInformationRequest>,
        required: true,
    },
});

const profileImage = computed(() => {
    if (props.profile?.images?.displayImage) {
        return props.profile?.images?.displayImage;
    }

    return testImage;
});
</script>
