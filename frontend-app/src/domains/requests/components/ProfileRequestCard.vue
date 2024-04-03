<template>
    <div class="p-4 flex flex-row justify-between items-center gap-4 border-b border-divider bg-background-light">
        <img
            :src="profileImage"
            :alt="`${props.profile.displayName}'s profile image`"
            class="w-12 h-12 object-cover object-center"
        >
        <div class="flex flex-row flex-grow justify-between items-center">
            <div class="leading-none text-sm">
                <div class="font-semibold">{{ props.profile.displayName }}</div>
                <div class="mt-2">{{ genderAndAge }}</div>
            </div>
            <div v-if="!props.newRequest" class="flex flex-row items-center gap-2">
                <CheckCircleIcon class="w-8 h-8 text-primary-text" />
                <XCircleIcon class="w-8 h-8 text-primary-text" />
                <FlagIcon class="w-8 h-8 text-primary-text" />
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed, type PropType } from 'vue';
import type Profile from '@/common/models/Profile';
import { calculateAge } from '@/common/helpers/profile';
import { CheckCircleIcon, XCircleIcon, FlagIcon } from '@heroicons/vue/24/outline';
import testImage from '@/assets/img/test-image.svg';

const props = defineProps({
    newRequest: {
        type: Boolean as PropType<boolean>,
        required: false,
        default: false,
    },
    profile: {
        type: Object as PropType<Profile>,
        required: true,
    },
});

const profileImage = computed(() => {
    if (props.profile?.images?.displayImage) {
        return props.profile?.images?.displayImage;
    }

    return testImage;
});

const genderAndAge = computed(() => {
    const age = calculateAge(props.profile.dateOfBirth);

    if (!props.profile.gender) {
        return age;
    }
    return `${props.profile.gender}, ${age}`;
});
</script>
