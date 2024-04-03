<template>
    <div class="flex flex-col gap-4">
        <div class="text-base font-bold leading-none">{{ $t('profileInformation.interestsManager.title') }}</div>
        <div v-if="interests.length > 0" class="flex flex-row flex-wrap gap-2">
            <Interest
                v-for="(interest, index) in interests"
                :use-remove-btn="true"
                :index="index"
                @remove="removeInterest"
                data-test="interest"
            >
                {{ interest }}
            </Interest>
        </div>
        <div class="flex flex-col gap-4">
            <form @submit="addInterest" data-test="form">
                <div class="mb-2 flex flex-col gap-2">
                    <div v-for="errorMessage in errorMessages" class="text-base text-danger font-bold leading-none" data-test="errorMessage">{{ errorMessage }}</div>
                </div>
                <input ref="inputField" class="w-full px-4 py-2 border border-gray-400" type="text" v-model="interest" placeholder="Enter an interest here..." data-test="input-field">
                <div class="mt-2 flex flex-row justify-end">
                    <button type="submit" class="px-4 py-2 text-secondary-text border-2 border-primary rounded-lg bg-primary">Add interest</button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup lang="ts">
import { type PropType, ref } from 'vue';
import Interest from '@/common/components/profile/Interest.vue';
import { useI18n } from 'vue-i18n';

const i18n = useI18n();

const props = defineProps({
    modelValue: {
        type: Array as PropType<string[]>,
        required: true,
    },
});

const interests = ref<string[]>(props.modelValue);
const inputField = ref<HTMLInputElement>();
const interest = ref<string>('');
const errorMessages = ref<string[]>([]);

const emit = defineEmits(['update:modelValue']);

function addInterest(event: Event) {
    event.preventDefault();
    errorMessages.value = [];

    if (interest.value.trim().length === 0) {
        errorMessages.value = [`${i18n.t('profileInformation.interestsManager.errorMessages.interestEmpty')}`];
        return;
    }

    if (interests.value.includes(interest.value)) {
        errorMessages.value = [`${i18n.t('profileInformation.interestsManager.errorMessages.interestAlreadyAdded')}`];
        return;
    }

    interests.value.push(interest.value);
    interest.value = '';

    emit('update:modelValue', interests);
}

function removeInterest(index: number) {
    interests.value.splice(index, 1);
    emit('update:modelValue', interests);
}
</script>
