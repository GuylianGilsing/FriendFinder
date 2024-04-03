<template>
    <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 ">
        <template v-if="validSocialPlatforms">
            <div v-for="validSocialPlatform in validSocialPlatforms" class="w-full flex flex-col gap-2">
                <div class="text-base font-bold leading-none">
                    {{ $t(`requests.socials.${validSocialPlatform}.label`) }}
                </div>
                <input
                    ref="inputField"
                    class="w-full px-4 py-2 border border-gray-400"
                    type="text"
                    :placeholder="$t(`requests.socials.${validSocialPlatform}.placeholder`)"
                    v-model="enteredSocials[validSocialPlatform]"
                    @input="onInput"
                >
            </div>
        </template>
    </div>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { getOptions } from '@/common/apis/contact-information';
import type ContactInformationOptions from '@/domains/requests/models/ContactInformationOptions';
import type { PropType } from 'vue';

const props = defineProps({
    modelValue: {
        type: Object as PropType<{ [key: string]: string }>,
        required: true,
        default: () => ({}),
    },
});

const validSocialPlatforms = ref<ContactInformationOptions['socials'] | null>(null);
const enteredSocials = ref<{ [key: string]: string }>(props.modelValue);

const emit = defineEmits(['update:modelValue']);

async function requestValidSocialPlatforms(): Promise<void> {
    const options = await getOptions();

    if (!options?.socials) {
        return;
    }

    validSocialPlatforms.value = options.socials;
}

function onInput(): void {
    const cleanupEmptyValues = (socials: { [key: string]: string }) => {
        let cleanSocials: { [key: string]: string } = {};

        for (const social in socials) {
            // Only keep socials that have a value
            if (socials[social].trim().length > 0) {
                cleanSocials[social] = socials[social];
            }
        }

        return cleanSocials;
    };

    emit('update:modelValue', cleanupEmptyValues(enteredSocials.value))
}

onMounted(() => {
    requestValidSocialPlatforms();
});
</script>
