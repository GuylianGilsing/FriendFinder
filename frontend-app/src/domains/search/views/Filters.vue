<template>
    <PopupLayout>
        <template #header-left-icon>
            <RouterLink to="/search">
                <XMarkIcon class="w-8 h-8" />
            </RouterLink>
        </template>
        <template #header-popup-title>{{ $t('search.filters.popup.title') }}</template>
        <template #main-content>TODO: Add filters here...</template>
    </PopupLayout>
</template>

<script setup lang="ts">
import PopupLayout from '@/common/layouts/PopupLayout.vue';
import { RouterLink } from 'vue-router';
import { XMarkIcon } from '@heroicons/vue/24/outline';
</script>
