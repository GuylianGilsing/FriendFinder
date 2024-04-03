<template>
    <header id="main-header" class="p-4 flex flex-row justify-between items-center bg-primary">
        <slot name="header-left-icon">
            <div class="w-8 h-8"></div>
        </slot>
        <div data-e2e-page-title class="text-base font-semibold text-secondary-text">
            <slot name="header-page-title" />
        </div>
        <slot name="header-right-icon">
            <div class="w-8 h-8"></div>
        </slot>
    </header>
    <main id="main-content" class="p-4 flex flex-col flex-grow relative overflow-y-auto bg-gray-200">
        <slot name="main-content" />
    </main>
    <slot name="main-footer">
        <footer v-if="props.showFooter" id="main-footer" class="p-4 border-t border-t-divider">
            <PageNavigation />
        </footer>
    </slot>
</template>

<script setup lang="ts">
import PageNavigation from '@/common/components/PageNavigation.vue';
import type { PropType } from 'vue';

const props = defineProps({
    showFooter: {
        type: Boolean as PropType<boolean>,
        required: false,
        default: true,
    },
});
</script>
