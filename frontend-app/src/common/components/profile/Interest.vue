<template>
    <div class="px-3 py-2 inline-flex flex-row items-center gap-2 rounded-3xl text-secondary-text capitalize leading-none bg-primary">
        <slot />
        <XMarkIcon v-if="props.useRemoveBtn" @click="removeInterest" class="w-5 h-5 text-secondary-text" />
    </div>
</template>

<script setup lang="ts">
import type { PropType } from 'vue';
import { XMarkIcon } from '@heroicons/vue/24/solid';

const props = defineProps({
    index: {
        type: Number as PropType<number>,
        required: false,
        default: 0,
    },
    useRemoveBtn: {
        type: Boolean as PropType<boolean>,
        required: false,
        default: false,
    },
});

const emit = defineEmits(['remove']);

function removeInterest() {
    emit('remove', props.index);
}
</script>
