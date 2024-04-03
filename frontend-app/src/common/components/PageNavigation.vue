<template>
    <div class="flex flex-row justify-center items-center gap-12">
        <RouterLink
            v-for="(item, index) in items"
            :key="index"
            :to="item.url"
            class="flex flex-col justify-center items-center gap-1"
        >
            <template v-if="routeIsActive(item.url)">
                <component :is="item.icon" class="w-6 h-6 text-primary" />
                <span class="text-primary">{{ item.label }}</span>
            </template>
            <template v-else>
                <component :is="item.icon" class="w-6 h-6" />
                <span>{{ item.label }}</span>
            </template>
        </RouterLink>
    </div>
</template>

<script setup lang="ts">
import { RouterLink } from 'vue-router';
import { useI18n } from 'vue-i18n'
import { MagnifyingGlassIcon, ChatBubbleLeftIcon } from '@heroicons/vue/24/outline';

import { routeIsActive } from '@/common/helpers/routing';

const { t } = useI18n();

const items = [
    {
        label: t('search.footer.label'),
        icon: MagnifyingGlassIcon,
        url: '/search',
    },
    {
        label: t('requests.footer.label'),
        icon: ChatBubbleLeftIcon,
        url: '/requests',
    },
];
</script>
@/common/helpers/routing
