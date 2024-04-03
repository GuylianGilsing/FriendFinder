<template>
    <div class="flex flex-row flex-wrap gap-2 text-xs">
        <Interest v-for="interest in props.interests">{{ interest }}</Interest>
    </div>
</template>

<script setup lang="ts">
import { type PropType } from 'vue';
import type Profile from '@/common/models/Profile';
import Interest from '@/common/components/profile/Interest.vue';

const props = defineProps({
    interests: {
        type: Array as PropType<Profile['details']['interests']>,
        required: true,
    },
});
</script>
